<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
  <title>{{ $drug['generic'] }} · Drug Guide · NurSync (CI)</title>

  @vite(['resources/css/app.css', 'resources/js/app.js'])
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
  <style> body { font-family: 'Poppins', ui-sans-serif, system-ui, sans-serif; } </style>
</head>

<body class="min-h-screen bg-slate-50">
<main class="min-h-screen flex">
  {{-- Sidebar --}}
  @include('partials.faculty-sidebar', ['active' => 'drug_guide'])

  {{-- Main --}}
  <section class="flex-1">
    <div class="container mx-auto px-8 py-12 space-y-6">

      {{-- Title + back/print actions (mirrors pattern) --}}
      <div class="rounded-xl border border-slate-200 bg-white p-5">
        <div class="flex flex-wrap items-start justify-between gap-3">
          <div>
            <div class="flex items-center gap-2">
              <span class="inline-flex h-9 w-9 items-center justify-center rounded-xl bg-slate-100">
                <i data-lucide="pill" class="h-5 w-5 text-slate-700"></i>
              </span>
              <h1 class="text-[24px] sm:text-[28px] font-extrabold tracking-tight text-slate-900">
                {{ $drug['generic'] }}
              </h1>
              @if(!empty($drug['high_alert']))
                <span class="ml-2 rounded-full bg-rose-100 px-2 py-1 text-[11px] font-semibold text-rose-700">High-alert</span>
              @endif
            </div>
            <div class="mt-1 text-sm text-slate-600">
              @if(!empty($drug['class'])) <span class="font-medium">Class:</span> {{ $drug['class'] }} @endif
              @if(!empty($drug['brands'])) · <span class="font-medium">Brands:</span> {{ implode(', ', $drug['brands']) }} @endif
              @if(!empty($drug['updated_at'])) · Updated {{ \Illuminate\Support\Carbon::parse($drug['updated_at'])->toFormattedDateString() }} @endif
            </div>
          </div>

          <div class="flex items-center gap-2">
            <a href="{{ route('faculty.drug_guide.index') }}"
               class="rounded-lg border px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50 inline-flex items-center gap-2">
              <i data-lucide="arrow-left" class="h-4 w-4"></i> Back
            </a>
            <button onclick="window.print()"
               class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-medium text-white hover:opacity-95 inline-flex items-center gap-2">
              <i data-lucide="printer" class="h-4 w-4"></i> Print
            </button>
          </div>
        </div>
      </div>

      {{-- Monograph content (tabs -> section blocks matching your card style) --}}
      <div class="grid gap-6 md:grid-cols-2">
        {{-- Left column --}}
        <div class="space-y-6">
          <section class="rounded-xl border border-slate-200 bg-white p-5">
            <h3 class="text-sm font-semibold text-slate-900">Indications</h3>
            <p class="mt-2 text-sm text-slate-700">{{ $drug['indications'] }}</p>
          </section>

          <section class="rounded-xl border border-slate-200 bg-white p-5">
            <h3 class="text-sm font-semibold text-slate-900">Contraindications</h3>
            <p class="mt-2 text-sm text-slate-700">{{ $drug['contraindications'] }}</p>
          </section>

          <section class="rounded-xl border border-slate-200 bg-white p-5">
            <h3 class="text-sm font-semibold text-slate-900">Adverse Effects</h3>
            <p class="mt-2 text-sm text-slate-700">{{ $drug['adverse_effects'] }}</p>
          </section>
        </div>

        {{-- Right column --}}
        <div class="space-y-6">
          <section class="rounded-xl border border-slate-200 bg-white p-5">
            <h3 class="text-sm font-semibold text-slate-900">Dosing</h3>
            <ul class="mt-2 space-y-2">
              @foreach($drug['dosing'] as $d)
                <li class="text-sm text-slate-700">
                  <span class="font-medium">{{ ucfirst($d['pop']) }}</span> • {{ $d['route'] }} — {{ $d['text'] }}
                </li>
              @endforeach
            </ul>
          </section>

          <section class="rounded-xl border border-slate-200 bg-white p-5">
            <h3 class="text-sm font-semibold text-slate-900">Nursing Responsibilities</h3>
            <p class="mt-2 text-sm text-slate-700">{{ $drug['nursing_responsibilities'] }}</p>
          </section>

          <section class="rounded-xl border border-slate-200 bg-white p-5">
            <h3 class="text-sm font-semibold text-slate-900">Patient Teaching</h3>
            <p class="mt-2 text-sm text-slate-700">{{ $drug['patient_teaching'] }}</p>
          </section>
        </div>
      </div>

      {{-- Full-width sections --}}
      <section class="rounded-xl border border-slate-200 bg-white p-5">
        <h3 class="text-sm font-semibold text-slate-900">Monitoring</h3>
        <p class="mt-2 text-sm text-slate-700">{{ $drug['monitoring'] }}</p>
      </section>

      <section class="rounded-xl border border-slate-200 bg-white p-5">
        <h3 class="text-sm font-semibold text-slate-900">Interactions</h3>
        @if(empty($drug['interactions']))
          <p class="mt-2 text-sm text-slate-500">No interactions listed in this entry.</p>
        @else
          <ul class="mt-2 space-y-2 text-sm">
            @foreach($drug['interactions'] as $ix)
              <li>
                <span class="font-medium">{{ $ix['with'] }}</span>
                <span class="ml-1 inline-flex items-center rounded-full border px-1.5 py-0.5 text-[11px] uppercase
                             {{ $ix['severity']==='major' ? 'bg-rose-50 text-rose-700 border-rose-200' :
                                ($ix['severity']==='moderate' ? 'bg-amber-50 text-amber-700 border-amber-200' :
                                                                'bg-slate-100 text-slate-700 border-slate-200') }}">
                  {{ $ix['severity'] }}
                </span>
                <span class="text-slate-700"> · {{ $ix['note'] }}</span>
              </li>
            @endforeach
          </ul>
        @endif
      </section>

      {{-- References + disclaimer footer (matches your page footer block) --}}
      <div class="rounded-xl border border-slate-200 bg-white p-5">
        <div class="space-y-3">
          @if(!empty($drug['references']))
            <div class="flex items-start gap-3">
              <i data-lucide="book-open" class="h-5 w-5 text-slate-500 mt-0.5"></i>
              <p class="text-[13px] leading-6 text-slate-600">
                References: {{ implode('; ', $drug['references']) }}
              </p>
            </div>
          @endif
          <div class="flex items-start gap-3">
            <i data-lucide="info" class="h-5 w-5 text-slate-500 mt-0.5"></i>
            <p class="text-[13px] leading-6 text-slate-600">
              Educational reference for on-campus training. Verify against institutional policies.
              @if(!empty($drug['updated_at']))
                <br>Last reviewed: <span class="font-medium text-slate-700">
                  {{ \Illuminate\Support\Carbon::parse($drug['updated_at'])->toDayDateTimeString() }}
                </span>
              @endif
            </p>
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
