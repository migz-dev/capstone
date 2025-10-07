<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Procedure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminProcedureController extends Controller
{
    /**
     * List procedures with search + status filters
     */
    public function index(Request $req)
    {
        // Eager load the creator relations used in the list
        $procedures = Procedure::with(['author','adminCreator'])
            ->search($req->q ?? null)
            ->status($req->status ?? null)
            ->latest('updated_at')
            ->paginate(10);

        return view('admin.admin-resources', compact('procedures'));
    }

    /**
     * Create page (Admin)
     */
    public function create()
    {
        return view('admin.procedures.create');
    }

    /**
     * Store – CI-parity create; then go to Edit to add steps
     */
    public function store(Request $req)
    {
        $data = $req->validate([
            'title'        => ['required','string','max:200'],
            'level'        => ['nullable','in:beginner,intermediate,advanced'],
            'description'  => ['nullable','string','max:5000'],
            'ppe_csv'      => ['nullable','string','max:1000'],
            'tags_csv'     => ['nullable','string','max:1000'],
            'video_url'    => ['nullable','string','max:1000'],
            'video_file'   => ['nullable','file','mimetypes:video/mp4,video/webm,video/ogg','max:204800'], // 200MB
            'hazards_text' => ['nullable','string','max:5000'],
            'action'       => ['nullable','in:draft,publish'],
        ]);

        $p = new Procedure();
        $p->title        = $data['title'];
        $p->level        = $data['level'] ?? 'beginner';
        $p->description  = $data['description'] ?? null;
        $p->hazards_text = $data['hazards_text'] ?? null;

        // CSV → JSON
        $p->ppe_json  = $this->csvToArray($data['ppe_csv']  ?? '');
        $p->tags_json = $this->csvToArray($data['tags_csv'] ?? '');

        // Video: prefer file over URL
        if ($req->hasFile('video_file')) {
            $req->file('video_file')->store('procedures/videos', 'public');
            $p->video_url = null;
        } else {
            $p->video_url = $this->normalizeVideoUrl($data['video_url'] ?? null);
        }

        // Status / publish time
        $action = $data['action'] ?? 'draft';
        $p->status       = $action === 'publish' ? 'published' : 'draft';
        $p->published_at = $p->status === 'published' ? now() : null;

        // AUDIT → write to admin columns so names show via accessors
        $adminId = auth('admin')->id();
        $p->created_by_admin = $adminId;
        $p->updated_by_admin = $adminId;

        // Slug + save
        $p->ensureSlug();
        $p->save();

        // Go to Edit to add steps
        return redirect()
            ->route('admin.procedures.edit', $p)
            ->with('ok', 'Draft created. You can add steps now.');
    }

    /**
     * Review page
     */
    public function show(Procedure $procedure)
    {
        $procedure->load([
            'steps',
            'attachments',
            // both creator possibilities
            'author',        // faculty creator (legacy)
            'adminCreator',  // admin creator (new)
            // both updater possibilities
            'editorFaculty', // faculty updater
            'adminEditor',   // admin updater (new)
        ]);

        return view('admin.procedures.show', compact('procedure'));
    }

    /**
     * Edit (metadata + steps)
     */
    public function edit(Procedure $procedure)
    {
        $procedure->load('steps');
        return view('admin.procedures.edit', compact('procedure'));
    }

    /**
     * Update – metadata, status, video, CSV fields, and steps
     */
    public function update(Request $req, Procedure $procedure)
    {
        $data = $req->validate([
            'title'        => ['required','string','max:200'],
            'level'        => ['nullable','in:beginner,intermediate,advanced'],
            'description'  => ['nullable','string','max:5000'],
            'ppe_csv'      => ['nullable','string','max:1000'],
            'tags_csv'     => ['nullable','string','max:1000'],
            'video_url'    => ['nullable','string','max:1000'],
            'video_file'   => ['nullable','file','mimetypes:video/mp4,video/webm,video/ogg','max:204800'],
            'remove_video' => ['nullable','boolean'],
            'hazards_text' => ['nullable','string','max:5000'],
            'action'       => ['nullable','in:draft,publish'],
        ]);

        DB::transaction(function () use ($req, $procedure, $data) {

            // Meta
            $procedure->title        = $data['title'];
            $procedure->level        = $data['level'] ?? $procedure->level;
            $procedure->description  = $data['description'] ?? null;
            $procedure->hazards_text = $data['hazards_text'] ?? null;

            // CSV → JSON (allow blank to clear)
            $procedure->ppe_json  = $this->csvToArray($data['ppe_csv']  ?? '');
            $procedure->tags_json = $this->csvToArray($data['tags_csv'] ?? '');

            // Video
            if ($req->boolean('remove_video')) {
                $procedure->video_url = null;
            } elseif ($req->hasFile('video_file')) {
                $req->file('video_file')->store('procedures/videos', 'public');
                $procedure->video_url = null;
            } else {
                $procedure->video_url = $this->normalizeVideoUrl($data['video_url'] ?? null);
            }

            // Status via action
            if (!empty($data['action'])) {
                $procedure->status       = $data['action'] === 'publish' ? 'published' : 'draft';
                $procedure->published_at = $procedure->status === 'published' ? now() : null;
            }

            // AUDIT → admin column
            $procedure->updated_by_admin = auth('admin')->id();

            // Save meta
            $procedure->ensureSlug();
            $procedure->save();

            // Steps (replace-all if provided)
            $incoming = $req->input('steps', []);
            $clean = [];
            foreach ($incoming as $i => $step) {
                $body = trim((string)($step['body'] ?? ''));
                if ($body === '') continue;

                $clean[] = [
                    'step_no'          => isset($step['step_no']) ? (int)$step['step_no'] : ($i + 1),
                    'title'            => trim((string)($step['title'] ?? '')) ?: null,
                    'body'             => $body,
                    'rationale'        => trim((string)($step['rationale'] ?? '')) ?: null,
                    'caution'          => trim((string)($step['caution'] ?? '')) ?: null,
                    'duration_seconds' => isset($step['duration_seconds']) && $step['duration_seconds'] !== ''
                        ? max(0, (int)$step['duration_seconds'])
                        : null,
                ];
            }

            if (!empty($clean)) {
                $procedure->steps()->delete();
                usort($clean, fn($a,$b) => $a['step_no'] <=> $b['step_no']);
                foreach ($clean as $row) {
                    $procedure->steps()->create($row);
                }
            }
        });

        return redirect()
            ->route('admin.procedures.show', $procedure)
            ->with('ok', 'Procedure updated.');
    }

    /**
     * Destroy
     */
    public function destroy(Procedure $procedure)
    {
        $procedure->delete();

        return redirect()
            ->route('admin.procedures.index')
            ->with('ok', 'Procedure deleted.');
    }

    /**
     * Publish / Unpublish
     */
    public function publish(Procedure $procedure)
    {
        $procedure->publish();
        $procedure->updated_by_admin = auth('admin')->id();
        $procedure->save();

        return back()->with('ok', 'Procedure published.');
    }

    public function unpublish(Procedure $procedure)
    {
        $procedure->unpublish();
        $procedure->updated_by_admin = auth('admin')->id();
        $procedure->save();

        return back()->with('ok', 'Procedure moved back to draft.');
    }

    /* ===========================
     * Helpers
     * =========================== */
    private function csvToArray(?string $csv): array
    {
        if (!$csv) return [];
        return array_values(array_filter(array_map('trim', explode(',', $csv)), fn($s) => $s !== ''));
    }

    private function normalizeVideoUrl(?string $url): ?string
    {
        if (!$url) return null;
        $u = trim($url);

        // YouTube
        if (preg_match('~(?:youtube\.com/watch\?v=|youtu\.be/)([A-Za-z0-9_\-]{6,})~', $u, $m)) {
            return 'https://www.youtube.com/embed/' . $m[1];
        }

        // Vimeo
        if (preg_match('~vimeo\.com/(\d+)~', $u, $m)) {
            return 'https://player.vimeo.com/video/' . $m[1];
        }

        return $u; // fallback
    }
}
