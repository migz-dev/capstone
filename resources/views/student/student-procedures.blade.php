<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <title>Procedures Library · NurSync</title>
  <link rel="icon" type="image/x-icon" href="{{ asset('CON_LOGO.ico') }}">
  <link rel="shortcut icon" type="image/x-icon" href="{{ asset('CON_LOGO.ico') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', ui-sans-serif, system-ui, sans-serif;
        }
    </style>
</head>

<body class="min-h-screen bg-slate-50">
    <main class="min-h-screen flex">
        {{-- Sidebar (unchanged) --}}
        @php($active = 'procedures')
        @include('partials.sidebar')

        {{-- Main content --}}
        <section class="flex-1">
            <div class="container mx-auto px-8 py-12">
                <div class="space-y-6">

                    {{-- Title + subtitle --}}
                    <header class="space-y-2">
                        <h1 class="text-[32px] font-extrabold tracking-tight text-slate-900">Procedures Library</h1>
                        <p class="text-sm text-slate-500">
                            Browse nursing skills with step-by-step guides, safety reminders, and quick practice
                            checklists.
                        </p>
                    </header>
                    <!-- ...rest of your content unchanged... -->


                    {{-- Search + filters (exact spacing/density) --}}
                    <div class="rounded-xl border border-slate-200 bg-white p-5">
                        <div class="flex items-center gap-3">
                            <div class="relative flex-1">
                                <i data-lucide="search" class="absolute left-3 top-3.5 h-4 w-4 text-slate-400"></i>
                                <input type="text" placeholder="Search exams by name, description, or topics..."
                                    class="w-full rounded-lg border border-slate-200 bg-slate-50 pl-9 pr-3 py-2 text-sm placeholder:text-slate-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-slate-200" />
                            </div>
                            <div class="hidden sm:flex items-center gap-2">
                                <button
                                    class="rounded-lg px-3 py-1.5 text-xs font-medium text-white bg-slate-900">All</button>
                                <button
                                    class="rounded-lg px-3 py-1.5 text-xs font-medium text-slate-700 bg-slate-100">Beginner</button>
                                <button
                                    class="rounded-lg px-3 py-1.5 text-xs font-medium text-slate-700 bg-slate-100">Intermediate</button>
                                <button
                                    class="rounded-lg px-3 py-1.5 text-xs font-medium text-slate-700 bg-slate-100">Advanced</button>
                                <button
                                    class="rounded-lg px-3 py-1.5 text-xs font-medium text-slate-700 bg-slate-100 inline-flex items-center gap-1">
                                    <i data-lucide="badge-check" class="h-3.5 w-3.5"></i> Free
                                </button>
                            </div>
                        </div>
                    </div>

                    {{-- 3-step info strip --}}
                    <div class="rounded-xl border border-slate-200 bg-white p-5">
                        <div class="grid gap-6 sm:grid-cols-3">
                            <div class="flex items-start gap-3">
                                <i data-lucide="notebook-pen" class="h-5 w-5 text-slate-600 mt-0.5"></i>
                                <div>
                                    <div class="text-sm font-semibold text-slate-900">Choose your Procedure</div>
                                    <div class="text-xs text-slate-500">Select from our range of campus-approved skill
                                        guides tailored to your level.</div>
                                </div>
                            </div>
                            <div class="flex items-start gap-3">
                                <i data-lucide="play-circle" class="h-5 w-5 text-slate-600 mt-0.5"></i>
                                <div>
                                    <div class="text-sm font-semibold text-slate-900">Practice the Steps</div>
                                    <div class="text-xs text-slate-500">Complete the guide at your own pace with a
                                        checklist, timers, and a demo video.</div>
                                </div>
                            </div>
                            <div class="flex items-start gap-3">
                                <i data-lucide="award" class="h-5 w-5 text-slate-600 mt-0.5"></i>
                                <div>
                                    <div class="text-sm font-semibold text-slate-900">Get Assessed</div>
                                    <div class="text-xs text-slate-500">Earn a rubric score during Return Demo—your
                                        results will appear in the dashboard.</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Cards grid (two columns, CodeCred density) --}}
                    <div class="grid gap-6 md:grid-cols-2">

                        <!-- Card: Hand Hygiene -->
                        <article class="rounded-xl border border-slate-200 bg-white p-5">
                            <div class="flex items-start justify-between gap-4">
                                <div class="flex items-center gap-3">
                                    <span
                                        class="inline-flex h-9 w-9 items-center justify-center rounded-xl bg-slate-100">
                                        <i data-lucide="hand" class="h-5 w-5 text-slate-700"></i>
                                    </span>
                                    <h3 class="text-base sm:text-lg font-semibold text-slate-900">Hand Hygiene</h3>
                                </div>
                                <span
                                    class="rounded-full bg-violet-100 text-violet-700 text-[11px] font-semibold px-2 py-1">Beginner</span>
                            </div>
                            <p class="mt-2 text-sm text-slate-600">
                                WHO 5 Moments, proper handwashing and alcohol rub technique, and infection-control
                                reminders.
                            </p>
                            <div class="mt-3 flex flex-wrap items-center gap-2 text-[11px]">
                                <span class="rounded-full bg-slate-100 px-2 py-1 text-slate-700">Infection
                                    Control</span>
                                <span class="rounded-full bg-slate-100 px-2 py-1 text-slate-700">Safety</span>
                                <span class="rounded-full bg-slate-100 px-2 py-1 text-slate-700">PPE</span>
                            </div>
                            <div class="mt-3 flex items-center gap-5 text-xs text-slate-500">
                                <span class="inline-flex items-center gap-1">
                                    <i data-lucide="clock" class="h-3.5 w-3.5"></i> 10 minutes
                                </span>
                                <span class="inline-flex items-center gap-1">
                                    <i data-lucide="video" class="h-3.5 w-3.5"></i> Demo
                                </span>
                                <span class="inline-flex items-center gap-1">
                                    <i data-lucide="file-text" class="h-3.5 w-3.5"></i> PDF
                                </span>
                            </div>
                            <div class="mt-4 flex items-center gap-3">
                                {{-- Open Guide (static UI) --}}
                                <a href="{{ route('student.procedures.open-guide') }}"
                                    class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-medium text-white hover:opacity-95">
                                    Open Guide
                                </a>

                                {{-- Practice (static UI) --}}
                                <a href="{{ route('student.procedures.practice') }}"
                                    class="rounded-lg border px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">
                                    Practice
                                </a>
                            </div>
                        </article>


                        <!-- Card: Vital Signs -->
                        <article class="rounded-xl border border-slate-200 bg-white p-5">
                            <div class="flex items-start justify-between gap-4">
                                <div class="flex items-center gap-3">
                                    <span
                                        class="inline-flex h-9 w-9 items-center justify-center rounded-xl bg-slate-100">
                                        <i data-lucide="activity" class="h-5 w-5 text-slate-700"></i>
                                    </span>
                                    <h3 class="text-base sm:text-lg font-semibold text-slate-900">Vital Signs
                                        (T-P-R-BP-SpO₂)</h3>
                                </div>
                                <span
                                    class="rounded-full bg-emerald-100 text-emerald-700 text-[11px] font-semibold px-2 py-1">Beginner</span>
                            </div>
                            <p class="mt-2 text-sm text-slate-600">
                                Accurate measurement techniques, normal ranges, documentation, and patient safety
                                considerations.
                            </p>
                            <div class="mt-3 flex flex-wrap items-center gap-2 text-[11px]">
                                <span class="rounded-full bg-slate-100 px-2 py-1 text-slate-700">Assessment</span>
                                <span class="rounded-full bg-slate-100 px-2 py-1 text-slate-700">Monitoring</span>
                                <span class="rounded-full bg-slate-100 px-2 py-1 text-slate-700">Documentation</span>
                            </div>
                            <div class="mt-3 flex items-center gap-5 text-xs text-slate-500">
                                <span class="inline-flex items-center gap-1"><i data-lucide="clock"
                                        class="h-3.5 w-3.5"></i> 20 minutes</span>
                                <span class="inline-flex items-center gap-1"><i data-lucide="video"
                                        class="h-3.5 w-3.5"></i> Demo</span>
                                <span class="inline-flex items-center gap-1"><i data-lucide="file-text"
                                        class="h-3.5 w-3.5"></i> PDF</span>
                            </div>
                            <div class="mt-4 flex items-center gap-3">
                                <a href="/student/procedures/vital-signs"
                                    class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-medium text-white hover:opacity-95">Open
                                    Guide</a>
                                <a href="/student/procedures/vital-signs/practice"
                                    class="rounded-lg border px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">Practice</a>
                            </div>
                        </article>

                        <!-- Card: Medication Administration (PO) -->
                        <article class="rounded-xl border border-slate-200 bg-white p-5">
                            <div class="flex items-start justify-between gap-4">
                                <div class="flex items-center gap-3">
                                    <span
                                        class="inline-flex h-9 w-9 items-center justify-center rounded-xl bg-slate-100">
                                        <i data-lucide="pill" class="h-5 w-5 text-slate-700"></i>
                                    </span>
                                    <h3 class="text-base sm:text-lg font-semibold text-slate-900">Medication
                                        Administration (PO)</h3>
                                </div>
                                <span
                                    class="rounded-full bg-amber-100 text-amber-700 text-[11px] font-semibold px-2 py-1">Intermediate</span>
                            </div>
                            <p class="mt-2 text-sm text-slate-600">
                                10 Rights of Medication, verification workflow, patient education, and error-prevention
                                strategies.
                            </p>
                            <div class="mt-3 flex flex-wrap items-center gap-2 text-[11px]">
                                <span class="rounded-full bg-slate-100 px-2 py-1 text-slate-700">Pharmacology</span>
                                <span class="rounded-full bg-slate-100 px-2 py-1 text-slate-700">Safety</span>
                                <span class="rounded-full bg-slate-100 px-2 py-1 text-slate-700">Documentation</span>
                            </div>
                            <div class="mt-3 flex items-center gap-5 text-xs text-slate-500">
                                <span class="inline-flex items-center gap-1"><i data-lucide="clock"
                                        class="h-3.5 w-3.5"></i> 25 minutes</span>
                                <span class="inline-flex items-center gap-1"><i data-lucide="video"
                                        class="h-3.5 w-3.5"></i> Demo</span>
                                <span class="inline-flex items-center gap-1"><i data-lucide="file-text"
                                        class="h-3.5 w-3.5"></i> PDF</span>
                            </div>
                            <div class="mt-4 flex items-center gap-3">
                                <a href="/student/procedures/meds-po"
                                    class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-medium text-white hover:opacity-95">Open
                                    Guide</a>
                                <a href="/student/procedures/meds-po/practice"
                                    class="rounded-lg border px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">Practice</a>
                            </div>
                        </article>

                        <!-- Card: Sterile Gloves & Dressing Change -->
                        <article class="rounded-xl border border-slate-200 bg-white p-5">
                            <div class="flex items-start justify-between gap-4">
                                <div class="flex items-center gap-3">
                                    <span
                                        class="inline-flex h-9 w-9 items-center justify-center rounded-xl bg-slate-100">
                                        <i data-lucide="shield" class="h-5 w-5 text-slate-700"></i>
                                    </span>
                                    <h3 class="text-base sm:text-lg font-semibold text-slate-900">Sterile Gloves &
                                        Dressing Change</h3>
                                </div>
                                <span
                                    class="rounded-full bg-rose-100 text-rose-700 text-[11px] font-semibold px-2 py-1">Advanced</span>
                            </div>
                            <p class="mt-2 text-sm text-slate-600">
                                Maintaining a sterile field, sterile glove technique, wound assessment, and secure
                                dressing application.
                            </p>
                            <div class="mt-3 flex flex-wrap items-center gap-2 text-[11px]">
                                <span class="rounded-full bg-slate-100 px-2 py-1 text-slate-700">Asepsis</span>
                                <span class="rounded-full bg-slate-100 px-2 py-1 text-slate-700">Wound Care</span>
                                <span class="rounded-full bg-slate-100 px-2 py-1 text-slate-700">Safety</span>
                            </div>
                            <div class="mt-3 flex items-center gap-5 text-xs text-slate-500">
                                <span class="inline-flex items-center gap-1"><i data-lucide="clock"
                                        class="h-3.5 w-3.5"></i> 30 minutes</span>
                                <span class="inline-flex items-center gap-1"><i data-lucide="video"
                                        class="h-3.5 w-3.5"></i> Demo</span>
                                <span class="inline-flex items-center gap-1"><i data-lucide="file-text"
                                        class="h-3.5 w-3.5"></i> PDF</span>
                            </div>
                            <div class="mt-4 flex items-center gap-3">
                                <a href="/student/procedures/sterile-dressing"
                                    class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-medium text-white hover:opacity-95">Open
                                    Guide</a>
                                <a href="/student/procedures/sterile-dressing/practice"
                                    class="rounded-lg border px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">Practice</a>
                            </div>
                        </article>

                        <!-- Card: IM Injection (Z-track) -->
                        <article class="rounded-xl border border-slate-200 bg-white p-5">
                            <div class="flex items-start justify-between gap-4">
                                <div class="flex items-center gap-3">
                                    <span
                                        class="inline-flex h-9 w-9 items-center justify-center rounded-xl bg-slate-100">
                                        <i data-lucide="syringe" class="h-5 w-5 text-slate-700"></i>
                                    </span>
                                    <h3 class="text-base sm:text-lg font-semibold text-slate-900">IM Injection (Z-track)
                                    </h3>
                                </div>
                                <span
                                    class="rounded-full bg-amber-100 text-amber-700 text-[11px] font-semibold px-2 py-1">Intermediate</span>
                            </div>
                            <p class="mt-2 text-sm text-slate-600">
                                Landmarking, needle selection, dosage checks, and post-administration monitoring using
                                the Z-track method.
                            </p>
                            <div class="mt-3 flex flex-wrap items-center gap-2 text-[11px]">
                                <span class="rounded-full bg-slate-100 px-2 py-1 text-slate-700">Injections</span>
                                <span class="rounded-full bg-slate-100 px-2 py-1 text-slate-700">Pharmacology</span>
                                <span class="rounded-full bg-slate-100 px-2 py-1 text-slate-700">Safety</span>
                            </div>
                            <div class="mt-3 flex items-center gap-5 text-xs text-slate-500">
                                <span class="inline-flex items-center gap-1"><i data-lucide="clock"
                                        class="h-3.5 w-3.5"></i> 20 minutes</span>
                                <span class="inline-flex items-center gap-1"><i data-lucide="video"
                                        class="h-3.5 w-3.5"></i> Demo</span>
                                <span class="inline-flex items-center gap-1"><i data-lucide="file-text"
                                        class="h-3.5 w-3.5"></i> PDF</span>
                            </div>
                            <div class="mt-4 flex items-center gap-3">
                                <a href="/student/procedures/im-injection"
                                    class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-medium text-white hover:opacity-95">Open
                                    Guide</a>
                                <a href="/student/procedures/im-injection/practice"
                                    class="rounded-lg border px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">Practice</a>
                            </div>
                        </article>

                        <!-- Card: IV Insertion (Cannulation) -->
                        <article class="rounded-xl border border-slate-200 bg-white p-5">
                            <div class="flex items-start justify-between gap-4">
                                <div class="flex items-center gap-3">
                                    <span
                                        class="inline-flex h-9 w-9 items-center justify-center rounded-xl bg-slate-100">
                                        <i data-lucide="workflow" class="h-5 w-5 text-slate-700"></i>
                                    </span>
                                    <h3 class="text-base sm:text-lg font-semibold text-slate-900">IV Insertion
                                        (Cannulation)</h3>
                                </div>
                                <span
                                    class="rounded-full bg-rose-100 text-rose-700 text-[11px] font-semibold px-2 py-1">Advanced</span>
                            </div>
                            <p class="mt-2 text-sm text-slate-600">
                                Site selection, aseptic prep, catheter advancement, securing lines, and complication
                                prevention.
                            </p>
                            <div class="mt-3 flex flex-wrap items-center gap-2 text-[11px]">
                                <span class="rounded-full bg-slate-100 px-2 py-1 text-slate-700">IV Therapy</span>
                                <span class="rounded-full bg-slate-100 px-2 py-1 text-slate-700">Asepsis</span>
                                <span class="rounded-full bg-slate-100 px-2 py-1 text-slate-700">Monitoring</span>
                            </div>
                            <div class="mt-3 flex items-center gap-5 text-xs text-slate-500">
                                <span class="inline-flex items-center gap-1"><i data-lucide="clock"
                                        class="h-3.5 w-3.5"></i> 35 minutes</span>
                                <span class="inline-flex items-center gap-1"><i data-lucide="video"
                                        class="h-3.5 w-3.5"></i> Demo</span>
                                <span class="inline-flex items-center gap-1"><i data-lucide="file-text"
                                        class="h-3.5 w-3.5"></i> PDF</span>
                            </div>
                            <div class="mt-4 flex items-center gap-3">
                                <a href="/student/procedures/iv-insertion"
                                    class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-medium text-white hover:opacity-95">Open
                                    Guide</a>
                                <a href="/student/procedures/iv-insertion/practice"
                                    class="rounded-lg border px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">Practice</a>
                            </div>
                        </article>

                    </div>

                    {{-- Footer note --}}
                    <div class="rounded-xl border border-slate-200 bg-white p-5">
                        <div class="flex items-start gap-3">
                            <i data-lucide="info" class="h-5 w-5 text-slate-500 mt-0.5"></i>
                            <p class="text-[13px] leading-6 text-slate-600">
                                All materials are for on-campus training and simulation. No real patient data is
                                collected or stored.
                            </p>
                        </div>
                    </div>

                </div>
            </div>
        </section>
    </main>

    @include('partials.student-footer')

    <script src="https://unpkg.com/lucide@latest"></script>
    <script> lucide.createIcons(); </script>
</body>

</html>