{{-- resources/views/faculty/chartings.blade.php --}}
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
  <title>Chartings Library · NurSync (CI)</title>

  @vite(['resources/css/app.css', 'resources/js/app.js'])
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
  <style> body{font-family:'Poppins',ui-sans-serif,system-ui,sans-serif;} </style>
</head>

<body class="min-h-screen bg-slate-50">
<main class="min-h-screen flex">
  {{-- Sidebar --}}
  @include('partials.faculty-sidebar', ['active' => 'chartings'])

  {{-- Main content --}}
  <section class="flex-1">
    <div class="container mx-auto px-8 py-12 space-y-6">

      {{-- Title --}}
      <header class="space-y-2">
        <h1 class="text-[32px] font-extrabold tracking-tight text-slate-900">Chartings Library (CI)</h1>
        <p class="text-sm text-slate-500">
          Personal, CI-only documentation hubs. Create entries fast, standardize formats, and review your logs.
        </p>
      </header>

      {{-- Quick actions --}}
      <div class="rounded-xl border border-slate-200 bg-white p-5">
        <div class="flex flex-wrap items-center gap-3">
          <a href="{{ route('faculty.chartings.vital_signs.create') }}"
             class="inline-flex items-center gap-2 rounded-lg bg-slate-900 px-4 py-2 text-sm font-medium text-white hover:opacity-95">
            <i data-lucide="activity" class="h-4 w-4"></i> New Vital Signs
          </a>
          <a href="/faculty/chartings/nurses-notes/create"
             class="inline-flex items-center gap-2 rounded-lg border px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">
            <i data-lucide="notebook-pen" class="h-4 w-4"></i> New Nurse’s Notes
          </a>
          <a href="/faculty/chartings/mar/create"
             class="inline-flex items-center gap-2 rounded-lg border px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">
            <i data-lucide="pill" class="h-4 w-4"></i> New MAR
          </a>
          <a href="/faculty/chartings/intake-output/create"
             class="inline-flex items-center gap-2 rounded-lg border px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">
            <i data-lucide="beaker" class="h-4 w-4"></i> New I&amp;O
          </a>
          <a href="/faculty/chartings/treatment/create"
             class="inline-flex items-center gap-2 rounded-lg border px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">
            <i data-lucide="stethoscope" class="h-4 w-4"></i> New Treatment
          </a>
          <a href="/faculty/chartings/ncp/create"
             class="inline-flex items-center gap-2 rounded-lg border px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">
            <i data-lucide="target" class="h-4 w-4"></i> New NCP
          </a>
        </div>
      </div>

      {{-- Search --}}
      <div class="rounded-xl border border-slate-200 bg-white p-5">
        <div class="relative">
          <i data-lucide="search" class="absolute left-3 top-3.5 h-4 w-4 text-slate-400"></i>
          <input id="searchBox" type="text" placeholder="Search chartings (type, description, keywords)…"
                 class="w-full rounded-lg border border-slate-200 bg-slate-50 pl-9 pr-3 py-2 text-sm placeholder:text-slate-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-slate-200" />
        </div>
      </div>

      {{-- 3-step info --}}
      <div class="rounded-xl border border-slate-200 bg-white p-5">
        <div class="grid gap-6 sm:grid-cols-3">
          <div class="flex items-start gap-3">
            <i data-lucide="clipboard-list" class="h-5 w-5 text-slate-600 mt-0.5"></i>
            <div>
              <div class="text-sm font-semibold text-slate-900">Standardize Forms</div>
              <div class="text-xs text-slate-500">Use consistent formats (DAR, SOAPIE, etc.) across cases.</div>
            </div>
          </div>
          <div class="flex items-start gap-3">
            <i data-lucide="user-lock" class="h-5 w-5 text-slate-600 mt-0.5"></i>
            <div>
              <div class="text-sm font-semibold text-slate-900">CI-Only Visibility</div>
              <div class="text-xs text-slate-500">Your entries are private to your CI account by design.</div>
            </div>
          </div>
          <div class="flex items-start gap-3">
            <i data-lucide="download" class="h-5 w-5 text-slate-600 mt-0.5"></i>
            <div>
              <div class="text-sm font-semibold text-slate-900">Export Anytime</div>
              <div class="text-xs text-slate-500">Export logs for review, coaching, or term documentation.</div>
            </div>
          </div>
        </div>
      </div>

      @php
        $chartingCards = [
[
  'title'   => "Nurse’s Notes",
  'icon'    => 'notebook-pen',
  'desc'    => 'DAR, SOAPIE, PIE… capture narrative nursing notes quickly.',
  'open'    => route('faculty.chartings.nurses_notes.index'),
  'create'  => route('faculty.chartings.nurses_notes.create'),
  'keywords'=> 'nurses notes dar soapie pie narrative progress documentation',
  'chip'    => 'bg-emerald-100 text-emerald-700',
],

          [
            'title' => 'Vital Signs',
            'icon'  => 'activity',
            'desc'  => 'Temperature, pulse, respirations, BP, SpO₂, pain scale.',
            'open'  => route('faculty.chartings.vital_signs.index'),
            'create'=> route('faculty.chartings.vital_signs.create'),
            'keywords' => 'vitals vital signs tpr bp spo2 pain',
            'chip'  => 'bg-sky-100 text-sky-700',
          ],
          [
            'title' => 'Medication Admin Record (MAR)',
            'icon'  => 'pill',
            'desc'  => 'Schedules, administrations, PRNs, effects, and omissions.',
            'open'  => '/faculty/chartings/mar',
            'create'=> '/faculty/chartings/mar/create',
            'keywords' => 'mar medication meds prn schedule administration',
            'chip'  => 'bg-purple-100 text-purple-700',
          ],
          [
            'title' => 'Intake & Output',
            'icon'  => 'beaker',
            'desc'  => 'Track fluids in/out with balances and types.',
            'open'  => '/faculty/chartings/intake-output',
            'create'=> '/faculty/chartings/intake-output/create',
            'keywords' => 'i&o intake output fluids balance urine oral iv',
            'chip'  => 'bg-amber-100 text-amber-700',
          ],
          [
            'title' => 'Treatment / Procedure',
            'icon'  => 'stethoscope',
            'desc'  => 'Wound care, IV insertion, procedures, outcomes.',
            'open'  => '/faculty/chartings/treatment',
            'create'=> '/faculty/chartings/treatment/create',
            'keywords' => 'treatment procedure wound iv cannulation dressing',
            'chip'  => 'bg-rose-100 text-rose-700',
          ],
          [
            'title' => 'Nursing Care Plan (NCP)',
            'icon'  => 'target',
            'desc'  => 'NANDA Dx, goals, interventions, evaluations.',
            'open'  => '/faculty/chartings/ncp',
            'create'=> '/faculty/chartings/ncp/create',
            'keywords' => 'ncp nursing care plan nanda goals interventions eval',
            'chip'  => 'bg-slate-100 text-slate-700',
          ],
        ];
      @endphp

      {{-- Cards grid --}}
      <div id="cardsGrid" class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
        @foreach ($chartingCards as $c)
          <article class="js-card rounded-xl border border-slate-200 bg-white p-5"
                   data-keywords="{{ strtolower($c['title'].' '.$c['desc'].' '.$c['keywords']) }}">
            <div class="flex items-start justify-between gap-4">
              <div class="flex items-center gap-3">
                <span class="inline-flex h-9 w-9 items-center justify-center rounded-xl bg-slate-100">
                  <i data-lucide="{{ $c['icon'] }}" class="h-5 w-5 text-slate-700"></i>
                </span>
                <h3 class="text-base sm:text-lg font-semibold text-slate-900">{{ $c['title'] }}</h3>
              </div>
              <span class="rounded-full px-2 py-1 text-[11px] font-semibold {{ $c['chip'] }}">Charting</span>
            </div>

            <p class="mt-2 text-sm text-slate-600">{{ $c['desc'] }}</p>

            <div class="mt-4 flex flex-wrap items-center gap-3">
              <a href="{{ $c['open'] }}"
                 class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-medium text-white hover:opacity-95">
                Open Page
              </a>
              <a href="{{ $c['create'] }}"
                 class="rounded-lg border px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50 inline-flex items-center gap-2">
                <i data-lucide="plus" class="h-4 w-4"></i> New Entry
              </a>
            </div>
          </article>
        @endforeach
      </div>

      {{-- Footer note --}}
      <div class="rounded-xl border border-slate-200 bg-white p-5">
        <div class="flex items-start gap-3">
          <i data-lucide="info" class="h-5 w-5 text-slate-500 mt-0.5"></i>
          <p class="text-[13px] leading-6 text-slate-600">
            Charting data is private per CI account. Use exports for term reports or coaching summaries.
          </p>
        </div>
      </div>

    </div>
  </section>
</main>

@include('partials.faculty-footer')

<script src="https://unpkg.com/lucide@latest"></script>
<script>
  lucide.createIcons();

  // Simple client-side search (like Procedures Library)
  const q = document.getElementById('searchBox');
  const cards = [...document.querySelectorAll('.js-card')];

  function render() {
    const needle = (q?.value || '').toLowerCase().trim();
    let shown = 0;
    cards.forEach(card => {
      const ok = !needle || card.dataset.keywords.includes(needle);
      card.style.display = ok ? '' : 'none';
      if (ok) shown++;
    });
  }

  q?.addEventListener('input', render);
  render();
</script>
</body>
</html>
