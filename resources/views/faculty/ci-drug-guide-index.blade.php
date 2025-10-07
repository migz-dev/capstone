<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
  <title>Drug Guide · NurSync (CI)</title>

  @vite(['resources/css/app.css', 'resources/js/app.js'])
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
  <style> body { font-family: 'Poppins', ui-sans-serif, system-ui, sans-serif; } </style>
</head>

<body class="min-h-screen bg-slate-50">
<main class="min-h-screen flex">
  {{-- Sidebar --}}
  @include('partials.faculty-sidebar', ['active' => 'drug_guide'])

  {{-- Main content --}}
  <section class="flex-1">
    <div class="container mx-auto px-8 py-12 space-y-6">

      {{-- Title + subtitle --}}
      <header class="space-y-2">
        <h1 class="text-[32px] font-extrabold tracking-tight text-slate-900">Drug Guide (CI)</h1>
        <p class="text-sm text-slate-500">
          Point-of-care monographs with nursing responsibilities, teaching, dosing, interactions, and safety notes.
        </p>
      </header>

      {{-- Flash alerts --}}
      @if(session('ok'))
        <div class="rounded-xl border border-emerald-200 bg-emerald-50 p-3 text-emerald-800 text-sm">
          {{ session('ok') }}
        </div>
      @endif
      @if(session('err'))
        <div class="rounded-xl border border-rose-200 bg-rose-50 p-3 text-rose-800 text-sm">
          {{ session('err') }}
        </div>
      @endif

      {{-- Top actions (CI-only) --}}
      <div class="rounded-xl border border-slate-200 bg-white p-5">
        <div class="flex flex-wrap items-center gap-3">
          @if($canManage)
            <a href="{{ route('faculty.drug_guide.create') }}"
               class="inline-flex items-center gap-2 rounded-lg bg-slate-900 px-4 py-2 text-sm font-medium text-white hover:opacity-95">
              <i data-lucide="plus" class="h-4 w-4"></i> New Drug
            </a>
          @endif
        </div>
      </div>

      {{-- Search + filters --}}
      <div class="rounded-xl border border-slate-200 bg-white p-5">
        <form method="GET" class="flex flex-col sm:flex-row sm:items-center gap-3">
          <div class="relative flex-1">
            <i data-lucide="search" class="absolute left-3 top-3.5 h-4 w-4 text-slate-400"></i>
            <input name="q" value="{{ $filters['q'] ?? '' }}"
                   placeholder="Search by generic or brand (e.g., Amoxicillin, Betaloc)…"
                   class="w-full rounded-lg border border-slate-200 bg-slate-50 pl-9 pr-3 py-2 text-sm placeholder:text-slate-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-slate-200" />
          </div>

          <select name="class" class="rounded-lg border border-slate-200 bg-white px-2 py-2 text-sm">
            <option value="">All classes</option>
            @foreach(($filters['classes'] ?? []) as $c)
              <option value="{{ $c }}" @selected(($filters['class'] ?? '') === $c)>{{ $c }}</option>
            @endforeach
          </select>

          <select name="route" class="rounded-lg border border-slate-200 bg-white px-2 py-2 text-sm">
            <option value="">All routes</option>
            @foreach(($filters['routes'] ?? []) as $r)
              <option value="{{ $r }}" @selected(($filters['route'] ?? '') === $r)>{{ $r }}</option>
            @endforeach
          </select>

          <select name="age" class="rounded-lg border border-slate-200 bg-white px-2 py-2 text-sm">
            <option value="">All ages</option>
            @foreach(($filters['ages'] ?? []) as $a)
              <option value="{{ $a }}" @selected(($filters['age'] ?? '') === $a)>{{ ucfirst($a) }}</option>
            @endforeach
          </select>

          <div class="flex items-center gap-2">
            <button class="rounded-lg border px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">Apply</button>
            @php
              $hasFilters = !empty(($filters['q'] ?? '')) || !empty(($filters['class'] ?? '')) || !empty(($filters['route'] ?? '')) || !empty(($filters['age'] ?? ''));
            @endphp
            @if($hasFilters)
              <a href="{{ route('faculty.drug_guide.index') }}" class="rounded-lg px-3 py-2 text-sm text-slate-600 hover:text-slate-900">Clear</a>
            @endif
          </div>
        </form>

        {{-- Active filter chips --}}
        @if($hasFilters)
          <div class="mt-3 flex flex-wrap items-center gap-2 text-[11px]">
            @if(!empty($filters['q']))     <span class="rounded-full bg-slate-100 px-2 py-1 text-slate-700">Search: “{{ $filters['q'] }}”</span>@endif
            @if(!empty($filters['class'])) <span class="rounded-full bg-slate-100 px-2 py-1 text-slate-700">Class: {{ $filters['class'] }}</span>@endif
            @if(!empty($filters['route'])) <span class="rounded-full bg-slate-100 px-2 py-1 text-slate-700">Route: {{ $filters['route'] }}</span>@endif
            @if(!empty($filters['age']))   <span class="rounded-full bg-slate-100 px-2 py-1 text-slate-700">Age: {{ ucfirst($filters['age']) }}</span>@endif
          </div>
        @endif
      </div>

      {{-- 3-step info strip --}}
      <div class="rounded-xl border border-slate-200 bg-white p-5">
        <div class="grid gap-6 sm:grid-cols-3">
          <div class="flex items-start gap-3">
            <i data-lucide="notebook-pen" class="h-5 w-5 text-slate-600 mt-0.5"></i>
            <div>
              <div class="text-sm font-semibold text-slate-900">Curate Monographs</div>
              <div class="text-xs text-slate-500">Indications, dosing, contraindications, nursing responsibilities.</div>
            </div>
          </div>
          <div class="flex items-start gap-3">
            <i data-lucide="pill" class="h-5 w-5 text-slate-600 mt-0.5"></i>
            <div>
              <div class="text-sm font-semibold text-slate-900">Interactions & Safety</div>
              <div class="text-xs text-slate-500">Major/minor interactions, high-alert flags, and monitoring.</div>
            </div>
          </div>
          <div class="flex items-start gap-3">
            <i data-lucide="shield-check" class="h-5 w-5 text-slate-600 mt-0.5"></i>
            <div>
              <div class="text-sm font-semibold text-slate-900">Publish & Keep Updated</div>
              <div class="text-xs text-slate-500">Show last-reviewed date; maintain source references.</div>
            </div>
          </div>
        </div>
      </div>

      {{-- Empty state --}}
      @if($drugs->total() === 0)
        <div class="rounded-xl border border-slate-200 bg-white p-8">
          <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
              <h3 class="text-lg font-semibold text-slate-900">No drugs found</h3>
              <p class="text-sm text-slate-600 mt-1">Try adjusting your search or filters.</p>
            </div>
            @if($canManage)
              <a href="{{ route('faculty.drug_guide.create') }}"
                 class="inline-flex items-center gap-2 rounded-lg bg-slate-900 px-4 py-2 text-sm font-medium text-white hover:opacity-95">
                <i data-lucide="plus" class="h-4 w-4"></i> New Drug
              </a>
            @endif
          </div>
        </div>
      @endif

      {{-- Cards grid (mirrors procedure card style) --}}
      <div class="grid gap-6 md:grid-cols-2">
        @foreach($drugs as $d)
          @php
            $brands = $d['brands'] ?? [];
            $class  = $d['class'] ?? null;
            $warns  = $d['warnings'] ?? [];
            $updated = $d['updated_at'] ?? null;
          @endphp

          <article class="rounded-xl border border-slate-200 bg-white p-5">
            <div class="flex items-start justify-between gap-4">
              <div class="flex items-center gap-3">
                <span class="inline-flex h-9 w-9 items-center justify-center rounded-xl bg-slate-100">
                  <i data-lucide="pill" class="h-5 w-5 text-slate-700"></i>
                </span>
                <h3 class="text-base sm:text-lg font-semibold text-slate-900">
                  {{ $d['generic'] }}
                </h3>
              </div>

              <div class="flex items-center gap-2">
                @if(($d['high_alert'] ?? false) === true)
                  <span class="rounded-full px-2 py-1 text-[11px] font-semibold bg-rose-100 text-rose-700">
                    High-alert
                  </span>
                @endif
                @if(!empty($warns))
                  <span class="rounded-full px-2 py-1 text-[11px] font-semibold bg-amber-100 text-amber-700">
                    Safety
                  </span>
                @endif
              </div>
            </div>

            @if($class || !empty($brands))
              <div class="mt-2 text-sm text-slate-600">
                @if($class) <span class="font-medium">Class:</span> {{ $class }} @endif
                @if($class && !empty($brands)) · @endif
                @if(!empty($brands)) <span class="font-medium">Brands:</span> {{ implode(', ', $brands) }} @endif
              </div>
            @endif

            {{-- Warnings chips --}}
            @if(!empty($warns))
              <div class="mt-3 flex flex-wrap items-center gap-2 text-[11px]">
                @foreach($warns as $w)
                  <span class="rounded-full bg-amber-50 px-2 py-1 text-amber-700 border border-amber-200">{{ $w }}</span>
                @endforeach
              </div>
            @endif

            {{-- Actions --}}
            <div class="mt-4 flex flex-wrap items-center gap-3">
              <a href="{{ route('faculty.drug_guide.show', $d['id']) }}"
                 class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-medium text-white hover:opacity-95">
                Open Monograph
              </a>
              @if($canManage)
                <a href="{{ route('faculty.drug_guide.edit', $d['id']) }}"
                   class="rounded-lg border px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50 inline-flex items-center gap-2">
                  <i data-lucide="pencil" class="h-4 w-4"></i> Edit
                </a>
              @endif
            </div>

            {{-- Meta row --}}
            <div class="mt-3 flex items-center gap-5 text-xs text-slate-500">
              <span class="inline-flex items-center gap-1">
                <i data-lucide="calendar-clock" class="h-3.5 w-3.5"></i>
                @if($updated)
                  Updated {{ \Illuminate\Support\Carbon::parse($updated)->diffForHumans() }}
                @else
                  Updated —
                @endif
              </span>
            </div>
          </article>
        @endforeach
      </div>

      {{-- Pagination --}}
      <div class="rounded-xl border border-slate-200 bg-white p-4">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
          <div class="text-sm text-slate-600">
            Showing {{ $drugs->firstItem() ?? 0 }}–{{ $drugs->lastItem() ?? 0 }} of {{ $drugs->total() }} drugs
          </div>
          <div class="text-sm">
            {{ $drugs->onEachSide(1)->links() }}
          </div>
        </div>
      </div>

      {{-- Footer note --}}
      <div class="rounded-xl border border-slate-200 bg-white p-5">
        <div class="flex items-start gap-3">
          <i data-lucide="info" class="h-5 w-5 text-slate-500 mt-0.5"></i>
          <p class="text-[13px] leading-6 text-slate-600">
            Educational reference for on-campus training. Verify doses against institutional policy. No real patient data is stored.
          </p>
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
