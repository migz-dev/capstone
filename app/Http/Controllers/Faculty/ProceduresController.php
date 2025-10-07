<?php

namespace App\Http\Controllers\Faculty;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\Procedure;
use App\Models\ProcedureStep;

class ProceduresController extends Controller
{
    /* =========================
     * Helpers
     * ========================= */
    protected function parseCsv(?string $csv): array
    {
        if (!$csv) return [];
        return array_values(array_filter(array_map('trim', explode(',', $csv)), fn($s) => $s !== ''));
    }

    protected function uniqueSlug(string $title, ?int $ignoreId = null): string
    {
        $base = Str::slug($title);
        $slug = $base;
        $i = 2;
        while (
            Procedure::when($ignoreId, fn($q) => $q->where('id', '!=', $ignoreId))
                ->where('slug', $slug)->exists()
        ) {
            $slug = "{$base}-{$i}";
            $i++;
        }
        return $slug;
    }

    protected function findBySlug(string $slug): Procedure
    {
        return Procedure::with(['steps' => fn($q) => $q->orderBy('step_no')])
            ->where('slug', $slug)
            ->firstOrFail();
    }

    /** Convert pasted video URLs (YouTube/Vimeo/Shorts/etc.) into an embeddable player URL */
    private function toEmbedUrl(?string $url): ?string
    {
        if (!$url) return null;
        $url = trim($url);

        // Vimeo
        if (preg_match('~https?://(?:www\.)?vimeo\.com/(?:.*?/)?(\d+)~i', $url, $m)) {
            return "https://player.vimeo.com/video/{$m[1]}";
        }

        // YouTube variants
        if (preg_match('~https?://(?:www\.|m\.)?youtu(?:be\.com|\.be)/.+~i', $url)) {
            if (preg_match('~youtu\.be/([A-Za-z0-9_-]{11})~', $url, $m)) {
                return "https://www.youtube-nocookie.com/embed/{$m[1]}?rel=0";
            }
            if (preg_match('~[?&]v=([A-Za-z0-9_-]{11})~', $url, $m)) {
                return "https://www.youtube-nocookie.com/embed/{$m[1]}?rel=0";
            }
            if (preg_match('~youtube\.com/shorts/([A-Za-z0-9_-]{11})~', $url, $m)) {
                return "https://www.youtube-nocookie.com/embed/{$m[1]}?rel=0";
            }
            if (preg_match('~youtube\.com/embed/([A-Za-z0-9_-]{11})~', $url, $m)) {
                return "https://www.youtube-nocookie.com/embed/{$m[1]}?rel=0";
            }
        }

        // Already an embed URL from a known provider
        if (preg_match('~^https?://(www\.youtube(-nocookie)?\.com|player\.vimeo\.com)/embed/~i', $url)) {
            return $url;
        }

        return null;
    }

    /* =========================
     * Library / Static
     * ========================= */
    public function index()
    {
        $procedures = Procedure::orderByDesc('updated_at')->get();
        return view('faculty.ci-procedures', compact('procedures'));
    }

    public function create()
    {
        return view('faculty.ci-procedures-create');
    }

    // (Optional stubs kept if routes still exist)
    public function import()     { return view('faculty.ci-procedures-import'); }
    public function templates()  { return view('faculty.ci-procedures-templates'); }

    /* =========================
     * Create (POST /faculty/procedures)
     * ========================= */
    public function store(Request $request)
    {
        $data = $request->validate([
            'title'        => 'required|string|max:255',
            'description'  => 'nullable|string',
            'level'        => 'required|in:beginner,intermediate,advanced',
            'ppe_csv'      => 'nullable|string',
            'tags_csv'     => 'nullable|string',
            'hazards_text' => 'nullable|string',
            'video_url'    => 'nullable|string|max:255', // pasted YT/Vimeo URL (optional)
            'video_file'   => 'nullable|file|mimetypes:video/mp4,video/webm,video/ogg|max:204800', // 200 MB
            'action'       => 'nullable|in:draft,publish',
        ]);

        $video   = $request->file('video_file');
        $savedSlug = null;

        DB::transaction(function () use ($data, $video, &$savedSlug) {

            $procedure = new Procedure();
            $procedure->title        = $data['title'];
            $procedure->slug         = $this->uniqueSlug($data['title']);
            $procedure->description  = $data['description'] ?? '';
            $procedure->level        = $data['level'];

            // Button overrides select
            $intent = $data['action'] ?? 'draft'; // 'publish' | 'draft'
            $procedure->status       = $intent === 'publish' ? 'published' : 'draft';
            $procedure->published_at = $procedure->status === 'published' ? now() : null;

            $procedure->ppe_json     = $this->parseCsv($data['ppe_csv'] ?? null);
            $procedure->tags_json    = $this->parseCsv($data['tags_csv'] ?? null);
            $procedure->hazards_text = $data['hazards_text'] ?? null;
            $procedure->video_url    = $this->toEmbedUrl($data['video_url'] ?? null); // embed link (optional)
            $procedure->created_by   = auth('faculty')->id();
            $procedure->updated_by   = auth('faculty')->id();
            $procedure->save();

            // Optional uploaded video (takes precedence over URL on the Show page)
            if ($video) {
                Storage::makeDirectory('public/videos');
                $stored = $video->store('videos', 'public'); // storage/app/public/videos/...
                $procedure->video_path = 'storage/'.$stored; // e.g. storage/videos/abc.mp4
                $procedure->save();
            }

            $savedSlug = $procedure->slug;
        });

        return redirect()
            ->route('faculty.procedures.edit', $savedSlug)
            ->with('ok', 'Procedure created successfully.');
    }

    /* =========================
     * Read / Assist / Edit (by slug)
     * ========================= */
    public function show(string $slug)
    {
        $procedure = $this->findBySlug($slug);
        return view('faculty.ci-procedures-show', compact('procedure'));
    }

    public function assist(string $slug)
    {
        $procedure = $this->findBySlug($slug);
        return view('faculty.ci-procedures-assist', compact('procedure'));
    }

    public function edit(string $slug)
    {
        $procedure = $this->findBySlug($slug);
        return view('faculty.ci-procedures-edit', compact('procedure'));
    }

    /* =========================
     * Update (POST /faculty/procedures/{slug})
     * ========================= */
    public function update(Request $request, string $slug)
    {
        $procedure = $this->findBySlug($slug);

        $data = $request->validate([
            'title'                    => 'required|string|max:255',
            'description'              => 'nullable|string',
            'level'                    => 'required|in:beginner,intermediate,advanced',
            'status'                   => 'required|in:draft,published',
            'video_url'                => 'nullable|string|max:255', // keep supporting pasted links
            'video_file'               => 'nullable|file|mimetypes:video/mp4,video/webm,video/ogg|max:204800',
            'remove_video'             => 'nullable|boolean',

            'hazards_text'             => 'nullable|string',
            'ppe_csv'                  => 'nullable|string',
            'tags_csv'                 => 'nullable|string',

            'steps'                    => 'nullable|array',
            'steps.*.step_no'          => 'required|integer|min:1',
            'steps.*.title'            => 'nullable|string|max:255',
            'steps.*.body'             => 'required|string',
            'steps.*.rationale'        => 'nullable|string',
            'steps.*.caution'          => 'nullable|string',
            'steps.*.duration_seconds' => 'nullable|integer|min:0',

            'action'                   => 'nullable|in:draft,publish',
        ]);

        DB::transaction(function () use ($request, $procedure, $data) {
            $procedure->title       = $data['title'];
            $procedure->slug        = $this->uniqueSlug($data['title'], $procedure->id);
            $procedure->description = $data['description'] ?? '';
            $procedure->level       = $data['level'];

            // Normalize intent from button or select
            $intent = $data['action'] ?? $data['status'];  // 'publish' | 'draft' | 'published'
            $procedure->status = in_array($intent, ['publish', 'published'], true) ? 'published' : 'draft';
            $procedure->published_at = $procedure->status === 'published'
                ? ($procedure->published_at ?? now())
                : null;

            $procedure->video_url    = $this->toEmbedUrl($data['video_url'] ?? null);
            $procedure->hazards_text = $data['hazards_text'] ?? null;
            $procedure->ppe_json     = $this->parseCsv($data['ppe_csv'] ?? null);
            $procedure->tags_json    = $this->parseCsv($data['tags_csv'] ?? null);
            $procedure->updated_by   = auth('faculty')->id();

            // Video removal (if requested and no replacement uploaded)
            if ($request->boolean('remove_video') && !$request->hasFile('video_file')) {
                if (!empty($procedure->video_path)) {
                    Storage::disk('public')->delete(str_replace('storage/', '', $procedure->video_path));
                }
                $procedure->video_path = null;
            }

            // Video replacement
            if ($request->hasFile('video_file')) {
                if (!empty($procedure->video_path)) {
                    Storage::disk('public')->delete(str_replace('storage/', '', $procedure->video_path));
                }
                Storage::makeDirectory('public/videos');
                $stored = $request->file('video_file')->store('videos', 'public');
                $procedure->video_path = 'storage/'.$stored;
            }

            $procedure->save();

            // Steps (replace with submitted set)
            if ($request->filled('steps')) {
                $incoming = collect($data['steps'])->sortBy('step_no')->values()->all();
                $procedure->steps()->delete();
                foreach ($incoming as $row) {
                    $procedure->steps()->create([
                        'step_no'          => (int) $row['step_no'],
                        'title'            => $row['title'] ?? null,
                        'body'             => $row['body'],
                        'rationale'        => $row['rationale'] ?? null,
                        'caution'          => $row['caution'] ?? null,
                        'duration_seconds' => isset($row['duration_seconds']) ? (int) $row['duration_seconds'] : null,
                    ]);
                }
            }
        });

        return redirect()
            ->route('faculty.procedures.edit', $procedure->slug)
            ->with('ok', 'Procedure saved.');
    }
}
