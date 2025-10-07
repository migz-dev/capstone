<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Throwable;

class RegisterWithRegcardController extends Controller
{
    public function store(Request $req)
    {
        $req->validate([
            'full_name'    => ['required','string','max:150'],
            'email'        => ['required','string','email','max:191', Rule::unique('users','email')],
            'student_no'   => ['required','string','max:20', Rule::unique('students','student_number')],
            'password'     => ['required','string','min:8','confirmed'],
            'regcard_file' => ['required','file','mimes:pdf,jpg,jpeg,png','max:8192'],
        ]);

        $term = DB::table('academic_terms')->where('is_current',1)->first();
        if (!$term) {
            return back()->withErrors(['register' => 'We could not complete verification at this time. Please try again later.'])->withInput();
        }

        // --- 1) Read reg-card from a TEMP copy and verify BSN + Year 2–4 + current AY/Sem ---
        $upload  = $req->file('regcard_file');
        $ext     = strtolower($upload->getClientOriginalExtension());
        $orig    = $upload->getClientOriginalName();

        $tmpDir      = 'tmp_regcards';
        $tmpFilename = uniqid('rc_', true).'.'.$ext;
        $tmpPath     = Storage::disk('local')->putFileAs($tmpDir, $upload, $tmpFilename);
        $absTmpPath  = Storage::disk('local')->path($tmpDir.'/'.$tmpFilename);

        try {
            $text = $this->extractText($absTmpPath, $ext);
            $meta = $this->parseMeta($text);

            $termOk = $meta['semester'] === (int)$term->semester
                && $meta['ay_start'] !== null && $meta['ay_end'] !== null
                && (int)$meta['ay_start'] === (int)$term->ay_start
                && (int)$meta['ay_end']   === (int)$term->ay_end;

            $yearOk = in_array((int)$meta['year'], [2,3,4], true);

            if (!($meta['is_bsn'] && $yearOk && $termOk)) {
                // Hard stop: do NOT create user if any check fails (generic error)
                return back()
                    ->withErrors(['register' => 'We could not complete verification at this time. Please try again later.'])
                    ->withInput();
            }
        } finally {
            try { @unlink($absTmpPath); } catch (\Throwable $e) {}
        }

        // --- 2) All checks passed -> create everything atomically and ACTIVATE with expiry ---
        try {
            DB::beginTransaction();

            // 2a) Create user
            $userId = DB::table('users')->insertGetId([
                'name'       => (string) $req->input('full_name'),
                'email'      => (string) $req->input('email'),
                'password'   => Hash::make((string) $req->input('password')),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // 2b) Create student (use parsed year if available)
            $studentId = DB::table('students')->insertGetId([
                'student_number' => (string) $req->input('student_no'),
                'full_name'      => (string) $req->input('full_name'),
                'program'        => 'Bachelor of Science in Nursing',
                'year_level'     => (int) ($meta['year'] ?? 0),
                'section'        => null,
                'email'          => (string) $req->input('email'),
                'is_active'      => 1,
                'created_at'     => now(),
                'updated_at'     => now(),
            ]);

            // 2c) Store reg-card PERMANENTLY on the PUBLIC disk
            $dir        = "regcards/{$term->code}";
            $filename   = (string) $req->input('student_no') . '.' . $ext;
            Storage::disk('public')->putFileAs($dir, $upload, $filename);
            $publicPath = $dir.'/'.$filename;
            $abs        = Storage::disk('public')->path($publicPath);
            $sha256     = hash_file('sha256', $abs);

            $fileId = DB::table('regcard_files')->insertGetId([
                'student_id'        => $studentId,
                'term_id'           => $term->id,
                'original_filename' => $orig,
                'storage_path'      => $publicPath,  // relative; use asset('storage/'.$path)
                'mime_type'         => $upload->getMimeType(),
                'size_bytes'        => $upload->getSize(),
                'sha256'            => $sha256,
                'uploaded_at'       => now(),
            ]);

            // 2d) Activate with a 4-month expiry; store parsed meta in ocr_json
            DB::table('student_semester_statuses')->updateOrInsert(
                ['student_id' => $studentId, 'term_id' => $term->id],
                [
                    'status'          => 'active',
                    'reason'          => null,
                    'regcard_file_id' => $fileId,
                    'ocr_json'        => json_encode($meta),
                    'validated_by'    => null,
                    'validated_at'    => now(),
                    'expires_at'      => now()->addMonths(4),
                    'updated_at'      => now(),
                    'created_at'      => now(),
                ]
            );

            // 2e) Audit
            DB::table('validation_audit_log')->insert([
                'student_id' => $studentId,
                'term_id'    => $term->id,
                'action'     => 'auto_approved',
                'actor_type' => 'system',
                'actor_id'   => null,
                'details'    => json_encode(['file_id'=>$fileId,'phase'=>'register','checks'=>'bsn+year+term']),
                'created_at' => now(),
            ]);

            DB::commit();

            Auth::loginUsingId($userId);
            return redirect('/student/dashboard')->with('success', 'Welcome to NurSync.');

        } catch (Throwable $e) {
            DB::rollBack();
            report($e);
            return back()->withErrors(['register' => 'Registration failed. Please try again.'])->withInput();
        }
    }

    /**
     * Best-effort text extraction:
     * - PDF: try 'pdftotext' (Poppler); fall back to raw bytes (text-layer PDFs).
     * - Images: return empty (no OCR) → fail-closed.
     */
    private function extractText(string $absPath, string $ext): string
    {
        $ext = strtolower($ext);

        if ($ext === 'pdf') {
            $pdftotext = strtoupper(substr(PHP_OS, 0, 3)) === 'WIN' ? 'pdftotext.exe' : 'pdftotext';
            @exec("{$pdftotext} -v", $out, $ok);
            if ($ok === 0) {
                $txtFile = $absPath.'.txt';
                @exec("{$pdftotext} -layout -nopgbrk ".escapeshellarg($absPath).' '.escapeshellarg($txtFile));
                if (is_file($txtFile)) {
                    $txt = @file_get_contents($txtFile) ?: '';
                    @unlink($txtFile);
                    return $txt;
                }
            }
            $bytes = @file_get_contents($absPath) ?: '';
            return preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F]+/u', ' ', $bytes) ?: '';
        }

        // jpg/png: no OCR yet
        return '';
    }

    /**
     * Parse useful metadata from the reg-card text.
     * Returns: ['is_bsn'=>bool,'year'=>int|null,'semester'=>1|2|null,'ay_start'=>int|null,'ay_end'=>int|null]
     */
    private function parseMeta(?string $text): array
    {
        $text = $text ?? '';
        $hay  = mb_strtolower($text);

        $isBsn = (strpos($hay, 'bachelor of science in nursing') !== false) || (preg_match('/\bbsn\b/i', $text) === 1);

        $semester = null;
        if (preg_match('/\b(First|Second)\s+Semester\b/i', $text, $m)) {
            $semester = strtolower($m[1]) === 'first' ? 1 : 2;
        }

        $ayStart = null; $ayEnd = null;
        if (preg_match('/AY\s*(\d{4})\s*[-–]\s*(\d{4})/i', $text, $m)) {
            $ayStart = (int) $m[1];
            $ayEnd   = (int) $m[2];
        }

        $year = null;
        if (preg_match('/Year\s*Level\s*:\s*(First|Second|Third|Fourth)\s+Year/i', $text, $m)
            || preg_match('/\b(First|Second|Third|Fourth)\s+Year\b/i', $text, $m)
            || preg_match('/\b([2-4])(?:st|nd|rd|th)?\s*Year\b/i', $text, $m)) {
            $map = ['first'=>1,'second'=>2,'third'=>3,'fourth'=>4];
            $key = strtolower($m[1]);
            $year = is_numeric($key) ? (int)$key : ($map[$key] ?? null);
        }

        return [
            'is_bsn'   => $isBsn,
            'year'     => $year,
            'semester' => $semester,
            'ay_start' => $ayStart,
            'ay_end'   => $ayEnd,
        ];
    }
}
