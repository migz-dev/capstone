<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
  <title>Open Guide · NurSync – Nurse Assistance (CI)</title>

  @vite(['resources/css/app.css','resources/js/app.js'])
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
  <style> body { font-family: 'Poppins', ui-sans-serif, system-ui, sans-serif; } </style>
</head>

<body class="min-h-screen bg-slate-50">
<main class="min-h-screen flex">
  {{-- Sidebar (CI) --}}
  @include('partials.faculty-sidebar', ['active' => 'procedures'])

  {{-- Main content --}}
  <section class="flex-1">
    <div class="container mx-auto px-8 py-12 space-y-8">

      {{-- Header --}}
      <header>
        <div class="flex items-center gap-3">
          <span class="inline-flex h-8 w-8 items-center justify-center rounded-xl bg-slate-100 text-slate-700">
            <i data-lucide="book-open" class="h-4 w-4"></i>
          </span>
          <h1 class="text-[28px] font-extrabold leading-tight tracking-tight text-slate-900">
            {{ $procedure->title ?? 'Procedure' }} – Open Guide
          </h1>
        </div>
        @if(!empty($procedure->description))
          <p class="mt-2 text-sm text-slate-500">
            {{ $procedure->description }}
          </p>
        @endif
      </header>

      {{-- Guide content --}}
      <div class="rounded-2xl border border-slate-200/70 bg-white p-8 space-y-6">
        {{-- Learning Objectives (optional) --}}
        @if(!empty($procedure->learning_objectives ?? null))
          <div>
            <h2 class="text-lg font-semibold text-slate-900">Learning Objectives</h2>
            <ul class="mt-2 list-disc list-inside text-sm text-slate-600">
              @foreach(($procedure->learning_objectives ?? []) as $obj)
                <li>{{ $obj }}</li>
              @endforeach
            </ul>
          </div>
        @endif

        {{-- PPE / Safety (optional chips) --}}
        @php($ppe = (array) ($procedure->ppe_json ?? []))
        @if(!empty($ppe) || !blank($procedure->hazards_text))
          <div class="grid gap-6 md:grid-cols-2">
            @if(!empty($ppe))
              <div>
                <h2 class="text-lg font-semibold text-slate-900">PPE & Equipment</h2>
                <div class="mt-2 flex flex-wrap gap-2">
                  @foreach($ppe as $item)
                    <span class="rounded-full bg-slate-100 px-2.5 py-1 text-[12px] text-slate-700">{{ $item }}</span>
                  @endforeach
                </div>
              </div>
            @endif

            @if(!blank($procedure->hazards_text))
              <div>
                <h2 class="text-lg font-semibold text-slate-900">Hazards / Safety Notes</h2>
                <p class="mt-2 text-sm text-slate-600">{{ trim((string) $procedure->hazards_text) }}</p>
              </div>
            @endif
          </div>
        @endif

        {{-- Steps --}}
        <div>
          <h2 class="text-lg font-semibold text-slate-900">Step-by-Step Walkthrough</h2>
          <div class="mt-3 space-y-3">
            @forelse(($procedure->steps ?? collect()) as $step)
              <div class="p-4 rounded-xl bg-slate-50 border border-slate-200">
                <strong class="block text-slate-800">
                  Step {{ $step->step_no }}{{ $step->title ? ':' : '' }}
                </strong>
                @if(!empty($step->title))
                  <div class="text-[13px] text-slate-700 font-medium">{{ $step->title }}</div>
                @endif
                <div class="mt-1 text-sm text-slate-700">{{ $step->body }}</div>

                @if(!empty($step->rationale) || !empty($step->caution))
                  <div class="mt-2 grid gap-2 md:grid-cols-2">
                    @if(!empty($step->rationale))
                      <div class="text-xs text-slate-600">
                        <span class="font-semibold text-slate-800">Rationale:</span>
                        {{ $step->rationale }}
                      </div>
                    @endif
                    @if(!empty($step->caution))
                      <div class="text-xs text-rose-700">
                        <span class="font-semibold">Caution:</span> {{ $step->caution }}
                      </div>
                    @endif
                  </div>
                @endif
              </div>
            @empty
              <div class="p-4 rounded-xl bg-amber-50 border border-amber-200 text-amber-800 text-sm">
                No steps added yet. Use <span class="font-semibold">Edit</span> to add steps.
              </div>
            @endforelse
          </div>
        </div>

        {{-- Demo Video --}}
        <div>
          <h2 class="text-lg font-semibold text-slate-900">Demo Video</h2>
          <div class="mt-2">
            @if(!empty($procedure->video_path))
              {{-- Uploaded video file takes precedence --}}
              <video
                class="w-full rounded-xl border border-slate-200 bg-black/5"
                style="aspect-ratio: 16 / 9;"
                controls
                playsinline
                preload="metadata"
              >
                <source src="{{ asset($procedure->video_path) }}">
                Your browser does not support the video tag.
              </video>
            @elseif(!empty($procedure->video_url))
              {{-- Fallback to embedded provider link --}}
              <div class="aspect-video rounded-xl overflow-hidden border border-slate-200 bg-black/5">
                <iframe class="w-full h-full" src="{{ $procedure->video_url }}" allowfullscreen loading="lazy"></iframe>
              </div>
            @else
              <div class="aspect-video rounded-xl bg-slate-100 flex items-center justify-center text-slate-400">
                [ No video attached ]
              </div>
            @endif
          </div>
        </div>

      </div>

    </div>
  </section>
</main>

@includeIf('partials.faculty-footer')
@includeWhen(!View::exists('partials.faculty-footer'), 'partials.student-footer')

<script src="https://unpkg.com/lucide@latest"></script>
<script> lucide.createIcons(); </script>
</body>
</html>
