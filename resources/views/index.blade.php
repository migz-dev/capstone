@extends('layouts.public')

@section('title', 'NurSync — Assist. Practice. Excel.')

@section('content')
  {{-- HERO (centered, grid background) --}}
  <section class="relative isolate overflow-hidden" aria-label="Hero">
    <div class="absolute inset-0 -z-10 bg-grid-slate"></div>

    <div class="mx-auto max-w-5xl px-4 sm:px-6 pt-20 pb-12 sm:pt-24 sm:pb-16 text-center">
      {{-- pill badge --}}
      <div
        class="inline-flex items-center justify-center rounded-full bg-slate-900 text-white text-xs font-semibold px-4 py-2 shadow-sm">
        Web & Mobile Nurse Assistance System
      </div>

      {{-- big headline --}}
      <h1 class="mt-6 text-[38px] leading-[1.05] font-extrabold tracking-tight text-slate-900 sm:text-[56px]">
        Assist clinical training.<br class="hidden sm:block" />
        Standardize return demonstrations.
      </h1>

      {{-- subtitle --}}
      <p class="mt-4 text-base sm:text-lg text-slate-600 max-w-2xl mx-auto">
        Plan and score skills labs, manage return demos, and keep student nurses aligned with CI instructions—
        all in one place designed for simulation and practice.
      </p>

      {{-- CTAs --}}
      <div class="mt-8 flex flex-wrap items-center justify-center gap-3">
        <a href="{{ url('/register') }}" class="btn-green-light">
          Get Started
          <svg xmlns="http://www.w3.org/2000/svg" class="size-4" viewBox="0 0 24 24" fill="currentColor">
            <path d="M13.5 4.5l6 6-6 6M3 12h16.5" />
          </svg>
        </a>
        <a href="#how" class="btn-outline">See How It Works</a>
      </div>

      {{-- metrics row --}}
      <div class="mt-12 grid grid-cols-3 gap-6 max-w-2xl mx-auto">
        <div class="metric">
          <div class="metric-number">572+</div>
          <div class="metric-label">CI & Student Users</div>
        </div>
        <div class="metric">
          <div class="metric-number">730+</div>
          <div class="metric-label">Skills Sessions & Demos</div>
        </div>
        <div class="metric">
          <div class="metric-number">92%</div>
          <div class="metric-label">On-time Demo Results</div>
        </div>
      </div>

      {{-- faint logo strip (optional) --}}
      <div class="mt-10 flex items-center justify-center gap-8 opacity-30 grayscale">
        <img src="/logos/nurse1.svg" alt="nurse1" class="h-8">
        <img src="/logos/nurse2.svg" alt="nurse2" class="h-8">
        <img src="/logos/nurse3.svg" alt="nurse3" class="h-8">
        <img src="/logos/nurse4.svg" alt="nurse4" class="h-8">
      </div>
    </div>
  </section>

  {{-- HOW IT WORKS --}}
  <section id="how" class="section-pad bg-slate-50/60" aria-labelledby="how-title">
    <div class="mx-auto max-w-7xl px-4 sm:px-6">
      <div class="grid lg:grid-cols-2 gap-8">

        {{-- LEFT: Who’s it for? --}}
        <div class="rounded-2xl bg-white p-6 sm:p-8 shadow-sm border border-slate-200" role="region"
          aria-labelledby="who-title">
          <span
            class="inline-flex items-center rounded-full bg-sky-100 text-sky-900 text-xs font-semibold px-3 py-1">Who’s it
            for?</span>
          <h2 id="who-title" class="mt-4 text-2xl sm:text-3xl font-bold tracking-tight">Made for skills labs & clinical instruction
          </h2>
          <p class="mt-3 text-slate-600 max-w-prose">
            From Level I to senior interns, NurSync supports simulation, skills validation, and standardized return demonstrations.
          </p>

          @php
            $audience = [
              ['icon' => '🏥', 't' => 'Skills Lab & Program Offices', 'd' => 'Coordinate sessions and standardize procedures'],
              ['icon' => '🧑‍🏫', 't' => 'Clinical Instructors', 'd' => 'Schedule, rubric-score, and publish results with audit trails'],
              ['icon' => '🩺', 't' => 'Student Nurses', 'd' => 'See assigned demos and view published results (read-only)'],
              ['icon' => '🛡️', 't' => 'Admins', 'd' => 'Oversight, reports, and secure records management'],
            ];
          @endphp

          <div class="mt-6 space-y-4">
            @foreach($audience as $a)
              <div class="flex items-start gap-4 rounded-xl border border-slate-200 bg-slate-50 p-4">
                <div class="text-xl">{{ $a['icon'] }}</div>
                <div>
                  <div class="font-semibold">{{ $a['t'] }}</div>
                  <div class="text-sm text-slate-600">{{ $a['d'] }}</div>
                </div>
              </div>
            @endforeach
          </div>
        </div>

        {{-- RIGHT: Four simple steps --}}
        <div class="rounded-2xl bg-white p-6 sm:p-8 shadow-sm border border-slate-200" role="region"
          aria-labelledby="steps-title">
          <span
            class="inline-flex items-center rounded-full bg-green-100 text-green-900 text-xs font-semibold px-3 py-1">How
            to get started</span>
          <h3 id="steps-title" class="mt-4 text-2xl sm:text-3xl font-bold tracking-tight">Four simple steps</h3>
          <p class="mt-2 text-slate-700">Get your skills demos organized quickly:</p>

          @php
            $steps = [
              ['n' => '1', 'i' => '✨', 't' => 'Create your account', 'd' => 'Sign up in less than a minute'],
              ['n' => '2', 'i' => '👥', 't' => 'Join your cohort', 'd' => 'Your CI links you to the correct section/session'],
              ['n' => '3', 'i' => '🗓️', 't' => 'View demo schedules', 'd' => 'Know your skill, date, time, CI, and room'],
              ['n' => '4', 'i' => '📋', 't' => 'Perform & review', 'd' => 'Complete the demo and view published results securely'],
            ];
          @endphp

          <div class="mt-6 space-y-6">
            @foreach($steps as $s)
              <div class="flex items-start gap-4">
                <div class="shrink-0 grid place-items-center size-10 rounded-xl bg-brand text-white font-bold">
                  {{ $s['n'] }}
                </div>
                <div class="min-w-0">
                  <div class="font-semibold">{{ $s['i'] }} {{ $s['t'] }}</div>
                  <div class="text-sm text-slate-700">{{ $s['d'] }}</div>
                </div>
              </div>
            @endforeach
          </div>

          <div class="mt-8">
            <a href="{{ url('/register') }}" class="btn-black">Get Started</a>
          </div>
        </div>

      </div>
    </div>
  </section>

  {{-- ABOUT --}}
  <section id="about" class="section-pad" aria-labelledby="about-title">
    <div class="mx-auto max-w-5xl px-4 sm:px-6 text-center">
      <h2 id="about-title" class="text-3xl sm:text-4xl font-extrabold tracking-tight">What does NurSync do?</h2>
      <p class="mt-3 text-slate-600 max-w-3xl mx-auto">
        We help programs run consistent skills validations, organize return demonstrations, and record competency results—without hospital data.
      </p>

      <div class="mt-10 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
          <div class="mx-auto mb-3 size-10 grid place-items-center rounded-xl bg-blue-100 text-xl">🧰</div>
          <h3 class="font-semibold">Skills Library</h3>
          <p class="mt-2 text-sm text-slate-600">Standardized procedure guides and checklists for simulations.</p>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
          <div class="mx-auto mb-3 size-10 grid place-items-center rounded-xl bg-green-100 text-xl">🧪</div>
          <h3 class="font-semibold">Return Demonstrations</h3>
          <p class="mt-2 text-sm text-slate-600">Schedule sessions and score using clear, rubric-based criteria.</p>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
          <div class="mx-auto mb-3 size-10 grid place-items-center rounded-xl bg-purple-100 text-xl">📜</div>
          <h3 class="font-semibold">Competency Records</h3>
          <p class="mt-2 text-sm text-slate-600">Publish results securely, with audit trails and admin oversight.</p>
        </div>
      </div>
    </div>
  </section>
@endsection
