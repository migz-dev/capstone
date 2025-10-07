<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
  <title>Admin • Review Procedure · NurSync</title>
  <link rel="icon" type="image/x-icon" href="{{ asset('CON_LOGO.ico') }}">
  <link rel="shortcut icon" type="image/x-icon" href="{{ asset('CON_LOGO.ico') }}">
  @vite(['resources/css/app.css','resources/js/app.js'])
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
  <style> body { font-family: 'Poppins', ui-sans-serif, system-ui, sans-serif; } </style>
</head>
<body class="min-h-screen bg-slate-50">
<main class="min-h-screen flex">
  {{-- Sidebar --}}
  @include('partials.admin-sidebar', ['active' => 'procedures'])

  <section class="flex-1 min-w-0">
    {{-- Header --}}
    <header class="sticky top-0 z-30 bg-white/80 backdrop-blur border-b border-slate-200">
      <div class="max-w-6xl mx-auto px-4 sm:px-6 h-14 flex items-center justify-between">
        <div class="flex items-center gap-3">
          <a href="{{ route('admin.procedures.index') }}"
             class="inline-flex items-center rounded-xl border border-slate-200 bg-white px-2.5 py-1.5 text-[12px] hover:bg-slate-50">
            <i data-lucide="arrow-left" class="h-4 w-4 mr-1"></i> Back
          </a>
          <div class="inline-flex h-8 w-8 items-center justify-center rounded-xl bg-slate-900 text-white shadow-sm">
            <i data-lucide="stethoscope" class="h-4 w-4"></i>
          </div>
          <div>
            <h1 class="text-[15px] sm:text-[16px] font-semibold leading-tight">{{ $procedure->title }}</h1>
            <p class="text-[12px] text-slate-500 -mt-0.5">Review details, steps, and attachments.</p>
          </div>
        </div>

        {{-- Publish / Unpublish --}}
        <div class="flex items-center gap-2">
          @if($procedure->is_published)
            <form method="POST" action="{{ route('admin.procedures.unpublish', $procedure) }}">
              @csrf @method('PATCH')
              <button class="inline-flex items-center gap-2 rounded-xl bg-amber-500 text-white px-3 py-2 text-[13px] hover:bg-amber-600">
                <i data-lucide="undo-2" class="h-4 w-4"></i> Unpublish
              </button>
            </form>
          @else
            <form method="POST" action="{{ route('admin.procedures.publish', $procedure) }}">
              @csrf @method('PATCH')
              <button class="inline-flex items-center gap-2 rounded-xl bg-green-600 text-white px-3 py-2 text-[13px] hover:bg-green-700">
                <i data-lucide="check" class="h-4 w-4"></i> Publish
              </button>
            </form>
          @endif
        </div>
      </div>
    </header>

    <div class="max-w-6xl mx-auto px-4 sm:px-6 py-6 space-y-6">
      {{-- Flash --}}
      @if(session('ok'))
        <div class="rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">{{ session('ok') }}</div>
      @endif

      {{-- Meta --}}
      <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5">
        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4 text-[13px]">
          <div>
            <div class="text-slate-500">Status</div>
            <div class="mt-1">
              @if($procedure->is_published)
                <span class="inline-flex items-center gap-1.5 rounded-lg bg-green-50 text-green-700 px-2 py-1 text-[12px] font-medium">
                  <i data-lucide="check-circle" class="h-3.5 w-3.5"></i> Published
                </span>
              @else
                <span class="inline-flex items-center gap-1.5 rounded-lg bg-yellow-50 text-yellow-700 px-2 py-1 text-[12px] font-medium">
                  <i data-lucide="clock" class="h-3.5 w-3.5"></i> Draft
                </span>
              @endif
            </div>
          </div>
          <div>
            <div class="text-slate-500">Created By</div>
            <div class="mt-1 font-medium text-slate-800">{{ $procedure->created_by_name }}</div>
          </div>
          <div>
            <div class="text-slate-500">Updated By</div>
            <div class="mt-1 text-slate-800">{{ $procedure->updated_by_name }}</div>
          </div>
          <div>
            <div class="text-slate-500">Created</div>
            <div class="mt-1 text-slate-800">{{ optional($procedure->created_at)->format('M d, Y • h:i a') }}</div>
          </div>
          <div>
            <div class="text-slate-500">Updated</div>
            <div class="mt-1 text-slate-800">{{ optional($procedure->updated_at)->format('M d, Y • h:i a') }}</div>
          </div>
          <div>
            <div class="text-slate-500">Published At</div>
            <div class="mt-1 text-slate-800">{{ optional($procedure->published_at)->format('M d, Y • h:i a') ?: '—' }}</div>
          </div>
        </div>
      </div>

      {{-- Description --}}
      @if($procedure->description)
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5">
          <h2 class="text-[14px] font-semibold mb-2">Description</h2>
          <p class="text-[13px] text-slate-700 leading-6">{{ $procedure->description }}</p>
        </div>
      @endif

      {{-- Media (video/pdf) --}}
      @if($procedure->video_url || $procedure->pdf_path)
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5 space-y-4">
          <h2 class="text-[14px] font-semibold">Media</h2>
          @if($procedure->video_url)
            <div class="aspect-video w-full">
              <iframe class="w-full h-full rounded-xl border border-slate-200"
                      src="{{ $procedure->video_url }}" allowfullscreen></iframe>
            </div>
          @endif
          @if($procedure->pdf_path)
            <a target="_blank"
               href="{{ route('files.procedure', ['path' => basename($procedure->pdf_path)]) }}"
               class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-3 py-2 text-[13px] hover:bg-slate-50">
              <i data-lucide="file-text" class="h-4 w-4"></i> Open PDF
            </a>
          @endif
        </div>
      @endif

      {{-- Steps --}}
      <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5">
        <h2 class="text-[14px] font-semibold mb-4">Steps</h2>
        @if($procedure->steps->isEmpty())
          <p class="text-[13px] text-slate-500">No steps yet.</p>
        @else
          <ol class="list-decimal ml-5 space-y-4">
            @foreach($procedure->steps as $s)
              <li>
                <div class="font-medium text-slate-900">{{ $s->step_no }}. {{ $s->title }}</div>
                @if($s->body)
                  <div class="mt-1 text-[13px] text-slate-700 leading-6">{{ $s->body }}</div>
                @endif
                <div class="mt-1 flex flex-wrap gap-2 text-[12px]">
                  @if($s->rationale)
                    <span class="inline-flex items-center gap-1 rounded-lg bg-blue-50 text-blue-700 px-2 py-0.5">
                      <i data-lucide="lightbulb" class="h-3.5 w-3.5"></i> Rationale: {{ $s->rationale }}
                    </span>
                  @endif
                  @if($s->caution)
                    <span class="inline-flex items-center gap-1 rounded-lg bg-rose-50 text-rose-700 px-2 py-0.5">
                      <i data-lucide="alert-triangle" class="h-3.5 w-3.5"></i> Caution: {{ $s->caution }}
                    </span>
                  @endif
                  @if($s->duration_seconds)
                    <span class="inline-flex items-center gap-1 rounded-lg bg-slate-100 text-slate-700 px-2 py-0.5">
                      <i data-lucide="timer" class="h-3.5 w-3.5"></i>
                      {{ floor($s->duration_seconds/60) ? floor($s->duration_seconds/60).' min ' : '' }}{{ $s->duration_seconds%60 }} sec
                    </span>
                  @endif
                </div>
              </li>
            @endforeach
          </ol>
        @endif
      </div>

      {{-- Attachments --}}
      <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5">
        <h2 class="text-[14px] font-semibold mb-3">Attachments</h2>
        @if($procedure->attachments->isEmpty())
          <p class="text-[13px] text-slate-500">No attachments.</p>
        @else
          <ul class="text-[13px] space-y-2">
            @foreach($procedure->attachments as $a)
              <li>
                <a target="_blank" href="{{ asset($a->path) }}" class="inline-flex items-center gap-2 text-slate-800 hover:underline">
                  <i data-lucide="{{ $a->type === 'pdf' ? 'file-text' : ($a->type === 'image' ? 'image' : 'paperclip') }}" class="h-4 w-4"></i>
                  {{ $a->label ?? strtoupper($a->type) }}
                </a>
                @if($a->uploaded_at)
                  <span class="text-slate-400">• {{ $a->uploaded_at->format('M d, Y') }}</span>
                @endif
              </li>
            @endforeach
          </ul>
        @endif
      </div>
    </div>
  </section>
</main>

<script src="https://unpkg.com/lucide@latest"></script>
<script> lucide.createIcons(); </script>
</body>
</html>
