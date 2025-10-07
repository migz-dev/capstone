<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
  <title>Practice · NurSync – Nurse Assistance</title>

  @vite(['resources/css/app.css','resources/js/app.js'])
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
  <style> body { font-family: 'Poppins', ui-sans-serif, system-ui, sans-serif; } </style>
</head>
<body class="min-h-screen bg-slate-50">
<main class="min-h-screen flex">
  {{-- Sidebar --}}
  @php($active = 'procedures')
  @include('partials.sidebar')

  {{-- Main content --}}
  <section class="flex-1">
    <div class="container mx-auto px-8 py-12 space-y-8">

      <!-- Header -->
      <header>
        <div class="flex items-center gap-3">
          <span class="inline-flex h-8 w-8 items-center justify-center rounded-xl bg-slate-100 text-slate-700">
            <i data-lucide="play-circle" class="h-4 w-4"></i>
          </span>
          <h1 class="text-[28px] font-extrabold leading-tight tracking-tight text-slate-900">
            Hand Hygiene — Practice Simulation
          </h1>
        </div>
        <p class="mt-2 text-sm text-slate-500">
          Simulate the steps without physical equipment. Train your sequence, timing, and decisions.
        </p>
      </header>

      <!-- Toolbar -->
      <div class="grid gap-4 lg:grid-cols-3">
        <div class="rounded-2xl border border-slate-200/70 bg-white p-5">
          <div class="text-[13px] font-medium text-slate-700 flex items-center gap-2">
            <i data-lucide="sliders-horizontal" class="h-4 w-4 text-slate-500"></i> Practice Mode
          </div>
          <div class="mt-3 flex flex-wrap gap-2">
            <button class="px-3 py-1.5 rounded-lg border text-sm hover:bg-slate-50" data-mode="quick">Quick Run</button>
            <button class="px-3 py-1.5 rounded-lg border text-sm hover:bg-slate-50" data-mode="full">Full Skill</button>
            <button class="px-3 py-1.5 rounded-lg border text-sm hover:bg-slate-50" data-mode="exam">Exam Simulation</button>
          </div>
          <p class="mt-2 text-xs text-slate-500">Tip: “Exam Simulation” adds stricter timing and fewer hints.</p>
        </div>

        <div class="rounded-2xl border border-slate-200/70 bg-white p-5">
          <div class="text-[13px] font-medium text-slate-700 flex items-center gap-2">
            <i data-lucide="sparkles" class="h-4 w-4 text-slate-500"></i> Scenario (Dummy)
          </div>
          <p class="mt-2 text-sm text-slate-600">
            <span class="font-medium text-slate-800">Prompt:</span> Before donning gloves for a wound dressing, what should you do?
          </p>
          <div class="mt-3 flex gap-2">
            <button class="px-3 py-1.5 rounded-lg border text-sm hover:bg-slate-50">Alcohol Rub</button>
            <button class="px-3 py-1.5 rounded-lg border text-sm hover:bg-slate-50">Handwash</button>
            <button class="px-3 py-1.5 rounded-lg border text-sm hover:bg-slate-50">No Action</button>
          </div>
        </div>

        <div class="rounded-2xl border border-slate-200/70 bg-white p-5">
          <div class="text-[13px] font-medium text-slate-700 flex items-center gap-2">
            <i data-lucide="clock" class="h-4 w-4 text-slate-500"></i> Timers
          </div>
          <div class="mt-3 grid grid-cols-2 gap-3">
            <div class="rounded-xl bg-slate-50 border p-3 text-center">
              <div class="text-xs text-slate-500">ABHR (20–30s)</div>
              <div class="mt-1 text-2xl font-bold" id="abhrTimer">00:30</div>
              <div class="mt-2 flex justify-center gap-2">
                <button class="px-2 py-1 text-xs rounded border hover:bg-slate-100" data-timer="abhr-start">Start</button>
                <button class="px-2 py-1 text-xs rounded border hover:bg-slate-100" data-timer="abhr-reset">Reset</button>
              </div>
            </div>
            <div class="rounded-xl bg-slate-50 border p-3 text-center">
              <div class="text-xs text-slate-500">Handwash (40–60s)</div>
              <div class="mt-1 text-2xl font-bold" id="washTimer">01:00</div>
              <div class="mt-2 flex justify-center gap-2">
                <button class="px-2 py-1 text-xs rounded border hover:bg-slate-100" data-timer="wash-start">Start</button>
                <button class="px-2 py-1 text-xs rounded border hover:bg-slate-100" data-timer="wash-reset">Reset</button>
              </div>
            </div>
          </div>
          <p class="mt-2 text-xs text-slate-500">These are guidance timers only.</p>
        </div>
      </div>

      <!-- Main two-column layout -->
      <div class="grid gap-6 lg:grid-cols-3">
        <!-- Left: Interactive checklist -->
        <div class="lg:col-span-2 rounded-2xl border border-slate-200/70 bg-white p-6">
          <div class="flex items-center justify-between">
            <div class="text-[13px] font-medium text-slate-700 flex items-center gap-2">
              <i data-lucide="check-square" class="h-4 w-4 text-slate-500"></i> Interactive Checklist (Dummy)
            </div>
            <div class="flex items-center gap-2">
              <label class="flex items-center gap-2 text-xs text-slate-600">
                <input type="checkbox" id="toggleHints" class="rounded"> Show hints
              </label>
              <button class="px-2 py-1 text-xs rounded-lg border hover:bg-slate-50" id="expandAll">Expand all</button>
              <button class="px-2 py-1 text-xs rounded-lg border hover:bg-slate-50" id="collapseAll">Collapse all</button>
            </div>
          </div>

          <div class="mt-4 divide-y divide-slate-200/70">
            <!-- Section 1 -->
            <details class="group py-4" open>
              <summary class="flex cursor-pointer list-none items-center justify-between">
                <span class="text-sm font-semibold text-slate-800">Prep & PPE</span>
                <i data-lucide="chevron-down" class="h-4 w-4 transition group-open:rotate-180"></i>
              </summary>
              <ul class="mt-3 space-y-2 text-sm text-slate-700">
                <li class="flex items-start gap-2">
                  <input type="checkbox" class="mt-1">
                  Remove jewelry; nails short; no acrylics.
                </li>
                <li class="flex items-start gap-2">
                  <input type="checkbox" class="mt-1">
                  Don PPE per scenario (mask/eye protection if indicated).
                </li>
              </ul>
              <p class="mt-2 text-xs text-slate-500 hidden hint">Hint: Rings and watches harbor microorganisms.</p>
            </details>

            <!-- Section 2 -->
            <details class="group py-4">
              <summary class="flex cursor-pointer list-none items-center justify-between">
                <span class="text-sm font-semibold text-slate-800">When to Wash vs. Rub</span>
                <i data-lucide="chevron-down" class="h-4 w-4 transition group-open:rotate-180"></i>
              </summary>
              <ul class="mt-3 space-y-2 text-sm text-slate-700">
                <li class="flex items-start gap-2">
                  <input type="checkbox" class="mt-1">
                  Choose ABHR if hands are not visibly soiled.
                </li>
                <li class="flex items-start gap-2">
                  <input type="checkbox" class="mt-1">
                  Choose handwash if visibly soiled or after bodily fluid exposure.
                </li>
              </ul>
              <div class="mt-3">
                <button class="inline-flex items-center gap-2 rounded-lg border px-3 py-1.5 text-xs hover:bg-slate-50"
                        id="triggerQuiz">
                  <i data-lucide="help-circle" class="h-3.5 w-3.5"></i> Quick quiz
                </button>
              </div>
              <p class="mt-2 text-xs text-slate-500 hidden hint">Hint: After removing gloves, perform hand hygiene.</p>
            </details>

            <!-- Section 3 -->
            <details class="group py-4">
              <summary class="flex cursor-pointer list-none items-center justify-between">
                <span class="text-sm font-semibold text-slate-800">Technique — Alcohol Rub (20–30s)</span>
                <i data-lucide="chevron-down" class="h-4 w-4 transition group-open:rotate-180"></i>
              </summary>
              <ul class="mt-3 space-y-2 text-sm text-slate-700">
                <li class="flex items-start gap-2"><input type="checkbox" class="mt-1"> Palm to palm</li>
                <li class="flex items-start gap-2"><input type="checkbox" class="mt-1"> Right palm over left dorsum (and vice versa)</li>
                <li class="flex items-start gap-2"><input type="checkbox" class="mt-1"> Palm to palm fingers interlaced</li>
                <li class="flex items-start gap-2"><input type="checkbox" class="mt-1"> Backs of fingers to opposing palms</li>
                <li class="flex items-start gap-2"><input type="checkbox" class="mt-1"> Rotational rubbing of thumbs</li>
                <li class="flex items-start gap-2"><input type="checkbox" class="mt-1"> Rotational rubbing of fingertips</li>
              </ul>
              <p class="mt-2 text-xs text-slate-500 hidden hint">Hint: Continue until hands are dry (~20–30s).</p>
            </details>

            <!-- Section 4 -->
            <details class="group py-4">
              <summary class="flex cursor-pointer list-none items-center justify-between">
                <span class="text-sm font-semibold text-slate-800">Technique — Handwash (40–60s)</span>
                <i data-lucide="chevron-down" class="h-4 w-4 transition group-open:rotate-180"></i>
              </summary>
              <ul class="mt-3 space-y-2 text-sm text-slate-700">
                <li class="flex items-start gap-2"><input type="checkbox" class="mt-1"> Wet hands & apply soap</li>
                <li class="flex items-start gap-2"><input type="checkbox" class="mt-1"> Repeat 7-step motions</li>
                <li class="flex items-start gap-2"><input type="checkbox" class="mt-1"> Rinse; keep hands above elbows</li>
                <li class="flex items-start gap-2"><input type="checkbox" class="mt-1"> Dry with towel; close tap with towel</li>
              </ul>
              <p class="mt-2 text-xs text-slate-500 hidden hint">Hint: Avoid touching faucet with clean hands.</p>
            </details>
          </div>

          <!-- Bottom controls -->
          <div class="mt-6 flex flex-wrap items-center justify-between gap-3">
            <div class="text-xs text-slate-500">
              Simulation only — no real patient data. View-only training.
            </div>
            <div class="flex items-center gap-2">
              <a href="{{ route('student.procedures.open-guide') }}"
                 class="rounded-lg border px-3 py-2 text-sm hover:bg-slate-50">
                <i data-lucide="book-open" class="h-4 w-4 mr-1.5 inline"></i> Open Guide
              </a>
              <button class="rounded-lg border px-3 py-2 text-sm hover:bg-slate-50" id="resetPractice">
                <i data-lucide="rotate-ccw" class="h-4 w-4 mr-1.5 inline"></i> Reset
              </button>
              <button class="rounded-lg bg-slate-900 px-3 py-2 text-sm font-medium text-white hover:opacity-95" id="finishPractice">
                <i data-lucide="flag-checkered" class="h-4 w-4 mr-1.5 inline"></i> Finish Practice
              </button>
            </div>
          </div>
        </div>

        <!-- Right: Summary & Rubric -->
        <aside class="rounded-2xl border border-slate-200/70 bg-white p-6">
          <div class="text-[13px] font-medium text-slate-700 flex items-center gap-2">
            <i data-lucide="clipboard-list" class="h-4 w-4 text-slate-500"></i> Practice Summary (Dummy)
          </div>

          <div class="mt-4 grid grid-cols-2 gap-3 text-center">
            <div class="rounded-xl bg-slate-50 border p-3">
              <div class="text-xs text-slate-500">Steps Completed</div>
              <div class="mt-1 text-2xl font-bold" id="stepsDone">0</div>
            </div>
            <div class="rounded-xl bg-slate-50 border p-3">
              <div class="text-xs text-slate-500">Hints Used</div>
              <div class="mt-1 text-2xl font-bold" id="hintsUsed">0</div>
            </div>
            <div class="rounded-xl bg-slate-50 border p-3">
              <div class="text-xs text-slate-500">Decision Errors</div>
              <div class="mt-1 text-2xl font-bold" id="errors">0</div>
            </div>
            <div class="rounded-xl bg-slate-50 border p-3">
              <div class="text-xs text-slate-500">Practice Time</div>
              <div class="mt-1 text-2xl font-bold" id="elapsed">00:00</div>
            </div>
          </div>

          <div class="mt-6">
            <h3 class="text-sm font-semibold text-slate-800">Self-Score (Mapped to CI Rubric)</h3>
            <ul class="mt-3 space-y-2 text-sm text-slate-700">
              <li class="flex items-center justify-between">
                <span>Safety in patient care</span>
                <span class="rounded bg-slate-100 px-2 py-0.5 text-xs">3/4</span>
              </li>
              <li class="flex items-center justify-between">
                <span>Sterile/clean technique</span>
                <span class="rounded bg-slate-100 px-2 py-0.5 text-xs">3/4</span>
              </li>
              <li class="flex items-center justify-between">
                <span>Organization & timing</span>
                <span class="rounded bg-slate-100 px-2 py-0.5 text-xs">4/4</span>
              </li>
            </ul>
          </div>

          <div class="mt-6 space-y-2">
            <button class="w-full rounded-lg border px-3 py-2 text-sm hover:bg-slate-50">
              <i data-lucide="file-down" class="h-4 w-4 mr-1.5 inline"></i> Download practice log (PDF)
            </button>
            <button class="w-full rounded-lg border px-3 py-2 text-sm hover:bg-slate-50">
              <i data-lucide="refresh-ccw" class="h-4 w-4 mr-1.5 inline"></i> Retry with new scenario
            </button>
          </div>
        </aside>
      </div>

      <!-- Campus note -->
      <div class="rounded-2xl border border-slate-200/70 bg-white p-5">
        <div class="flex items-start gap-3">
          <i data-lucide="shield-alert" class="h-5 w-5 text-slate-500 mt-0.5"></i>
          <p class="text-[13px] leading-6 text-slate-600">
            <span class="font-semibold text-slate-800">Note:</span> Campus training & simulation only. No real patient data is stored.
          </p>
        </div>
      </div>

    </div>
  </section>
</main>

@include('partials.student-footer')

<!-- Lucide -->
<script src="https://unpkg.com/lucide@latest"></script>
<script> lucide.createIcons(); </script>

<!-- Tiny dummy JS to make hints/timers feel alive (no frameworks) -->
<script>
(function(){
  // Hints toggle
  const toggle = document.getElementById('toggleHints');
  const hints = document.querySelectorAll('.hint');
  const incHints = () => {
    const n = document.getElementById('hintsUsed');
    n.textContent = String(parseInt(n.textContent || '0', 10) + 1);
  };
  toggle?.addEventListener('change', () => {
    hints.forEach(h => h.classList.toggle('hidden', !toggle.checked));
    if (toggle.checked) incHints();
  });

  // Expand / collapse all
  document.getElementById('expandAll')?.addEventListener('click', () => {
    document.querySelectorAll('details').forEach(d => d.open = true);
  });
  document.getElementById('collapseAll')?.addEventListener('click', () => {
    document.querySelectorAll('details').forEach(d => d.open = false);
  });

  // Timers (very simple countdowns)
  function startTimer(elId, seconds){
    const el = document.getElementById(elId);
    if (!el) return;
    clearInterval(el._t);
    el._remain = seconds;
    el._t = setInterval(() => {
      el._remain--;
      if (el._remain <= 0) { clearInterval(el._t); el._remain = 0; }
      const mm = String(Math.floor(el._remain/60)).padStart(2,'0');
      const ss = String(el._remain%60).padStart(2,'0');
      el.textContent = `${mm}:${ss}`;
    }, 1000);
  }
  function resetTimer(elId, seconds){
    const el = document.getElementById(elId);
    if (!el) return;
    clearInterval(el._t);
    el._remain = seconds;
    const mm = String(Math.floor(seconds/60)).padStart(2,'0');
    const ss = String(seconds%60).padStart(2,'0');
    el.textContent = `${mm}:${ss}`;
  }
  document.querySelector('[data-timer="abhr-start"]')?.addEventListener('click', () => startTimer('abhrTimer', 30));
  document.querySelector('[data-timer="abhr-reset"]')?.addEventListener('click', () => resetTimer('abhrTimer', 30));
  document.querySelector('[data-timer="wash-start"]')?.addEventListener('click', () => startTimer('washTimer', 60));
  document.querySelector('[data-timer="wash-reset"]')?.addEventListener('click', () => resetTimer('washTimer', 60));
  resetTimer('abhrTimer', 30); resetTimer('washTimer', 60);

  // Track steps checked
  const stepBoxes = Array.from(document.querySelectorAll('details input[type="checkbox"]'));
  const stepsDoneEl = document.getElementById('stepsDone');
  stepBoxes.forEach(cb => cb.addEventListener('change', () => {
    const done = stepBoxes.filter(x => x.checked).length;
    stepsDoneEl.textContent = String(done);
  }));

  // Simple stopwatch for practice time
  let elapsed = 0;
  setInterval(() => {
    elapsed++;
    const m = String(Math.floor(elapsed/60)).padStart(2,'0');
    const s = String(elapsed%60).padStart(2,'0');
    document.getElementById('elapsed').textContent = `${m}:${s}`;
  }, 1000);

  // Micro-quiz (very light modal)
  const quizBtn = document.getElementById('triggerQuiz');
  quizBtn?.addEventListener('click', () => {
    const ok = confirm('Hands are visibly soiled after patient contact. Choose:\n\nA) Alcohol Rub\nB) Handwash\n\nPress OK for Handwash.');
    if (!ok) {
      const e = document.getElementById('errors');
      e.textContent = String(parseInt(e.textContent || '0', 10) + 1);
    }
  });

  // Reset / Finish (dummy)
  document.getElementById('resetPractice')?.addEventListener('click', () => {
    stepBoxes.forEach(cb => cb.checked = false);
    stepsDoneEl.textContent = '0';
    document.getElementById('hintsUsed').textContent = '0';
    document.getElementById('errors').textContent = '0';
    elapsed = 0;
    resetTimer('abhrTimer', 30);
    resetTimer('washTimer', 60);
    alert('Practice reset.');
  });
  document.getElementById('finishPractice')?.addEventListener('click', () => {
    alert('Practice finished. (Dummy) A PDF log download would be generated here.');
  });
})();
</script>
</body>
</html>
