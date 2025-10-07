<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
  <title>Procedures Library · NurSync (CI)</title>

  @vite(['resources/css/app.css', 'resources/js/app.js'])
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
  <style> body { font-family: 'Poppins', ui-sans-serif, system-ui, sans-serif; } </style>
</head>

<body class="min-h-screen bg-slate-50">
<main class="min-h-screen flex">
  {{-- Sidebar --}}
  @include('partials.faculty-sidebar', ['active' => 'procedures'])

  {{-- Main content --}}
  <section class="flex-1">
    <div class="container mx-auto px-8 py-12 space-y-6">

      {{-- Title + subtitle --}}
      <header class="space-y-2">
        <h1 class="text-[32px] font-extrabold tracking-tight text-slate-900">Procedures Library (CI)</h1>
        <p class="text-sm text-slate-500">
          Create, curate, and deliver campus-approved procedure guides with steps, safety notes, and demo resources.
        </p>
      </header>

      {{-- Top actions (CI-only) --}}
      <div class="rounded-xl border border-slate-200 bg-white p-5">
        <div class="flex flex-wrap items-center gap-3">
          <a href="{{ route('faculty.procedures.create') }}"
             class="inline-flex items-center gap-2 rounded-lg bg-slate-900 px-4 py-2 text-sm font-medium text-white hover:opacity-95">
            <i data-lucide="plus" class="h-4 w-4"></i> New Procedure
          </a>
        </div>
      </div>

      {{-- Search + filters --}}
      <div class="rounded-xl border border-slate-200 bg-white p-5">
        <div class="flex items-center gap-3">
          <div class="relative flex-1">
            <i data-lucide="search" class="absolute left-3 top-3.5 h-4 w-4 text-slate-400"></i>
            <input id="searchBox" type="text" placeholder="Search procedures by name, tags, or hazards..."
                   class="w-full rounded-lg border border-slate-200 bg-slate-50 pl-9 pr-3 py-2 text-sm placeholder:text-slate-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-slate-200" />
          </div>
          <div class="hidden sm:flex items-center gap-2">
            <button data-level="all" class="js-filter-level rounded-lg px-3 py-1.5 text-xs font-medium text-white bg-slate-900">All</button>
            <button data-level="beginner" class="js-filter-level rounded-lg px-3 py-1.5 text-xs font-medium text-slate-700 bg-slate-100">Beginner</button>
            <button data-level="intermediate" class="js-filter-level rounded-lg px-3 py-1.5 text-xs font-medium text-slate-700 bg-slate-100">Intermediate</button>
            <button data-level="advanced" class="js-filter-level rounded-lg px-3 py-1.5 text-xs font-medium text-slate-700 bg-slate-100">Advanced</button>
          </div>
        </div>
      </div>

      {{-- 3-step info strip --}}
      <div class="rounded-xl border border-slate-200 bg-white p-5">
        <div class="grid gap-6 sm:grid-cols-3">
          <div class="flex items-start gap-3">
            <i data-lucide="notebook-pen" class="h-5 w-5 text-slate-600 mt-0.5"></i>
            <div>
              <div class="text-sm font-semibold text-slate-900">Author or Curate</div>
              <div class="text-xs text-slate-500">Define steps, PPE/equipment, hazards, and attach CI-only notes.</div>
            </div>
          </div>
          <div class="flex items-start gap-3">
            <i data-lucide="play-circle" class="h-5 w-5 text-slate-600 mt-0.5"></i>
            <div>
              <div class="text-sm font-semibold text-slate-900">Use Assist Mode</div>
              <div class="text-xs text-slate-500">Guide demos with step tracker, timers, and checklists—no grading.</div>
            </div>
          </div>
          <div class="flex items-start gap-3">
            <i data-lucide="shield-check" class="h-5 w-5 text-slate-600 mt-0.5"></i>
            <div>
              <div class="text-sm font-semibold text-slate-900">Publish & Update</div>
              <div class="text-xs text-slate-500">Publish for student view; updates are versioned and logged.</div>
            </div>
          </div>
        </div>
      </div>

      @php
        /** @var \Illuminate\Support\Collection|\App\Models\Procedure[] $procedures */
        $procedures = $procedures ?? collect();
        $levelChip = function($level) {
          return match($level) {
            'beginner' => 'bg-emerald-100 text-emerald-700',
            'intermediate' => 'bg-amber-100 text-amber-700',
            'advanced' => 'bg-rose-100 text-rose-700',
            default => 'bg-slate-100 text-slate-700',
          };
        };
        $pickIcon = function($p) {
          $t = strtolower(($p->slug ?? '') . ' ' . ($p->title ?? ''));
          return str_contains($t,'hand') ? 'hand'
               : (str_contains($t,'vital') ? 'activity'
               : (str_contains($t,'med') || str_contains($t,'drug') || str_contains($t,'po') ? 'pill'
               : (str_contains($t,'sterile') || str_contains($t,'dressing') ? 'shield' : 'stethoscope')));
        };
      @endphp

      {{-- Empty state --}}
      @if($procedures->isEmpty())
        <div class="rounded-xl border border-slate-200 bg-white p-8">
          <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
              <h3 class="text-lg font-semibold text-slate-900">No procedures yet</h3>
              <p class="text-sm text-slate-600 mt-1">Create your first guide to get started.</p>
            </div>
            <a href="{{ route('faculty.procedures.create') }}"
               class="inline-flex items-center gap-2 rounded-lg bg-slate-900 px-4 py-2 text-sm font-medium text-white hover:opacity-95">
              <i data-lucide="plus" class="h-4 w-4"></i> New Procedure
            </a>
          </div>
        </div>
      @endif

      {{-- Cards grid --}}
      <div id="cardsGrid" class="grid gap-6 md:grid-cols-2">
        @foreach($procedures as $p)
          @php
            $tags = (array) ($p->tags_json ?? []);
            $hasPdf = !empty($p->pdf_path);
            $hasVideo = !empty($p->video_url);
            $statusLockIcon = $p->status === 'published' ? 'lock' : 'lock-open';
            $statusLabel = $p->status === 'published' ? 'Published' : 'Draft';
            $icon = $pickIcon($p);
          @endphp

          <article
            class="js-card rounded-xl border border-slate-200 bg-white p-5"
            data-level="{{ $p->level }}"
            data-has-pdf="{{ $hasPdf ? '1' : '0' }}"
            data-keywords="{{ Str::of($p->title.' '.$p->description.' '.implode(' ', $tags).' '.($p->hazards_text ?? ''))->lower() }}"
          >
            <div class="flex items-start justify-between gap-4">
              <div class="flex items-center gap-3">
                <span class="inline-flex h-9 w-9 items-center justify-center rounded-xl bg-slate-100">
                  <i data-lucide="{{ $icon }}" class="h-5 w-5 text-slate-700"></i>
                </span>
                <h3 class="text-base sm:text-lg font-semibold text-slate-900">
                  {{ $p->title }}
                </h3>
              </div>
              <span class="rounded-full px-2 py-1 text-[11px] font-semibold {{ $levelChip($p->level) }}">
                {{ ucfirst($p->level) }}
              </span>
            </div>

            @if(!empty($p->description))
              <p class="mt-2 text-sm text-slate-600">{{ $p->description }}</p>
            @endif

            {{-- Tags --}}
            @if(!empty($tags))
              <div class="mt-3 flex flex-wrap items-center gap-2 text-[11px]">
                @foreach($tags as $t)
                  <span class="rounded-full bg-slate-100 px-2 py-1 text-slate-700">{{ $t }}</span>
                @endforeach
              </div>
            @endif

            {{-- Meta row --}}
            <div class="mt-3 flex items-center gap-5 text-xs text-slate-500">
              @if($hasVideo)
                <span class="inline-flex items-center gap-1"><i data-lucide="video" class="h-3.5 w-3.5"></i> Demo</span>
              @endif
              @if($hasPdf)
                <span class="inline-flex items-center gap-1"><i data-lucide="file-text" class="h-3.5 w-3.5"></i> PDF</span>
              @endif
              <span class="inline-flex items-center gap-1">
                <i data-lucide="{{ $statusLockIcon }}" class="h-3.5 w-3.5"></i> {{ $statusLabel }}
              </span>
            </div>

            {{-- Actions --}}
            <div class="mt-4 flex flex-wrap items-center gap-3">
              <a href="{{ route('faculty.procedures.show', $p->slug) }}"
                 class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-medium text-white hover:opacity-95">
                Open Guide
              </a>
              <a href="{{ route('faculty.procedures.assist', $p->slug) }}"
                 class="rounded-lg border px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50 inline-flex items-center gap-2">
                <i data-lucide="play-circle" class="h-4 w-4"></i> Assist Mode
              </a>
              <a href="{{ route('faculty.procedures.edit', $p->slug) }}"
                 class="rounded-lg border px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50 inline-flex items-center gap-2">
                <i data-lucide="pencil" class="h-4 w-4"></i> Edit
              </a>
            </div>
          </article>
        @endforeach
      </div>

      <!-- Pagination (no reload) -->
      <div id="pagerShell" class="rounded-xl border border-slate-200 bg-white p-4">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
          <!-- Summary -->
          <div id="pagerSummary" class="text-sm text-slate-600">
            Showing 0–0 of 0 procedures
          </div>

          <!-- Controls -->
          <nav class="flex items-center gap-1" aria-label="Pagination">
            <button id="btnPrev"
                    class="rounded-lg border px-3 py-1.5 text-sm font-medium text-slate-700 hover:bg-slate-50 disabled:opacity-40 disabled:hover:bg-transparent"
                    disabled>
              ‹ Prev
            </button>

            <div id="pageButtons" class="flex items-center gap-1"></div>

            <button id="btnNext"
                    class="rounded-lg border px-3 py-1.5 text-sm font-medium text-slate-700 hover:bg-slate-50 disabled:opacity-40 disabled:hover:bg-transparent"
                    disabled>
              Next ›
            </button>
          </nav>
        </div>
      </div>

      {{-- Footer note --}}
      <div class="rounded-xl border border-slate-200 bg-white p-5">
        <div class="flex items-start gap-3">
          <i data-lucide="info" class="h-5 w-5 text-slate-500 mt-0.5"></i>
          <p class="text-[13px] leading-6 text-slate-600">
            All materials are for on-campus training and simulation. No real patient data is collected or stored.
          </p>
        </div>
      </div>

    </div>
  </section>
</main>

@includeIf('partials.faculty-footer')
@includeWhen(!View::exists('partials.faculty-footer'), 'partials.student-footer')

<script src="https://unpkg.com/lucide@latest"></script>
<script>
  // Icons
  lucide.createIcons();

  // --- Client-side filter + pagination (search + level + optional PDF toggle) ---
  const q = document.getElementById('searchBox');
  const cards = [...document.querySelectorAll('.js-card')];

  // Filter state
  let level = 'all', pdfOnly = false;

  // Pagination state
  const pageSize = 10;
  let currentPage = 1;

  // Pager elements
  const pagerShell = document.getElementById('pagerShell');
  const pagerSummary = document.getElementById('pagerSummary');
  const pageButtons = document.getElementById('pageButtons');
  const btnPrev = document.getElementById('btnPrev');
  const btnNext = document.getElementById('btnNext');

  function getFilteredCards() {
    const needle = (q?.value || '').toLowerCase().trim();
    return cards.filter(card => {
      const okLevel = (level === 'all') || (card.dataset.level === level);
      const okPdf = !pdfOnly || card.dataset.hasPdf === '1';
      const okSearch = !needle || card.dataset.keywords.includes(needle);
      return okLevel && okPdf && okSearch;
    });
  }

  function renderPage() {
    const filtered = getFilteredCards();
    const total = filtered.length;
    const totalPages = Math.max(1, Math.ceil(total / pageSize));
    if (currentPage > totalPages) currentPage = totalPages;

    // Hide all
    cards.forEach(c => { c.style.display = 'none'; });

    // Slice bounds
    const startIdx = (currentPage - 1) * pageSize;
    const endIdx = Math.min(startIdx + pageSize, total);

    // Show current slice
    for (let i = startIdx; i < endIdx; i++) {
      filtered[i].style.display = '';
    }

    // Summary
    const humanStart = total === 0 ? 0 : startIdx + 1;
    const humanEnd = endIdx;
    pagerSummary.textContent = `Showing ${humanStart}–${humanEnd} of ${total} procedures`;

    // Controls
    btnPrev.disabled = (currentPage <= 1);
    btnNext.disabled = (currentPage >= totalPages);

    // Build page buttons
    buildPageButtons(totalPages);

    // Hide pager when <= 1 page
    pagerShell.style.display = totalPages <= 1 ? 'none' : '';
  }

  function buildPageButtons(totalPages) {
    pageButtons.innerHTML = '';

    const windowSize = 5;
    let start = Math.max(1, currentPage - Math.floor(windowSize / 2));
    let end = Math.min(totalPages, start + windowSize - 1);
    start = Math.max(1, Math.min(start, Math.max(1, totalPages - windowSize + 1)));

    const makeBtn = (label, page, isActive = false, disabled = false) => {
      const btn = document.createElement('button');
      btn.type = 'button';
      btn.textContent = label;
      btn.className = [
        'rounded-lg px-3 py-1.5 text-sm font-medium',
        isActive ? 'bg-slate-900 text-white' : 'border text-slate-700 hover:bg-slate-50',
        disabled ? 'opacity-50 cursor-not-allowed' : ''
      ].join(' ');
      btn.disabled = disabled;
      if (!disabled && !isActive) {
        btn.addEventListener('click', () => {
          currentPage = page;
          renderPage();
          scrollPagerIntoView();
        });
      }
      return btn;
    };

    if (start > 1) {
      pageButtons.appendChild(makeBtn('1', 1, currentPage === 1));
      if (start > 2) {
        const dots = document.createElement('span');
        dots.className = 'px-1 text-slate-400 select-none';
        dots.textContent = '…';
        pageButtons.appendChild(dots);
      }
    }

    for (let p = start; p <= end; p++) {
      pageButtons.appendChild(makeBtn(String(p), p, currentPage === p));
    }

    if (end < totalPages) {
      if (end < totalPages - 1) {
        const dots = document.createElement('span');
        dots.className = 'px-1 text-slate-400 select-none';
        dots.textContent = '…';
        pageButtons.appendChild(dots);
      }
      pageButtons.appendChild(makeBtn(String(totalPages), totalPages, currentPage === totalPages));
    }
  }

  function scrollPagerIntoView() {
    const rect = pagerShell.getBoundingClientRect();
    const vh = window.innerHeight || document.documentElement.clientHeight;
    const fullyVisible = rect.top >= 0 && rect.bottom <= vh;
    if (!fullyVisible) pagerShell.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
  }

  // Filter bindings
  q?.addEventListener('input', () => { currentPage = 1; renderPage(); });

  document.querySelectorAll('.js-filter-level').forEach(btn => {
    btn.addEventListener('click', () => {
      level = btn.dataset.level;
      document.querySelectorAll('.js-filter-level').forEach(b=>{
        b.classList.remove('bg-slate-900','text-white');
        b.classList.add('bg-slate-100','text-slate-700');
      });
      btn.classList.add('bg-slate-900','text-white');
      btn.classList.remove('bg-slate-100','text-slate-700');
      currentPage = 1;
      renderPage();
    });
  });

  // Optional PDF-only toggle support (will do nothing unless a #btnPdfOnly exists)
  document.getElementById('btnPdfOnly')?.addEventListener('click', (e) => {
    pdfOnly = !pdfOnly;
    e.currentTarget.classList.toggle('bg-slate-900');
    e.currentTarget.classList.toggle('text-white');
    e.currentTarget.classList.toggle('bg-slate-100');
    e.currentTarget.classList.toggle('text-slate-700');
    currentPage = 1;
    renderPage();
  });

  // Prev/Next
  btnPrev?.addEventListener('click', () => {
    if (currentPage > 1) {
      currentPage--;
      renderPage();
      scrollPagerIntoView();
    }
  });
  btnNext?.addEventListener('click', () => {
    currentPage++;
    renderPage();
    scrollPagerIntoView();
  });

  // First paint
  renderPage();
</script>
</body>
</html>
