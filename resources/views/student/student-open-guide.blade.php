<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
  <title>Open Guide · NurSync – Nurse Assistance</title>

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
            <i data-lucide="book-open" class="h-4 w-4"></i>
          </span>
          <h1 class="text-[28px] font-extrabold leading-tight tracking-tight text-slate-900">
            Hand Hygiene – Open Guide
          </h1>
        </div>
        <p class="mt-2 text-sm text-slate-500">
          WHO 5 Moments, proper handwashing and alcohol rub technique, and infection-control reminders.
        </p>
      </header>

      <!-- Dummy guide content -->
      <div class="rounded-2xl border border-slate-200/70 bg-white p-8 space-y-6">
        <div>
          <h2 class="text-lg font-semibold text-slate-900">Learning Objectives</h2>
          <ul class="mt-2 list-disc list-inside text-sm text-slate-600">
            <li>Identify the WHO 5 Moments for Hand Hygiene.</li>
            <li>Demonstrate proper alcohol hand rub technique.</li>
            <li>Perform correct handwashing steps (40–60 seconds).</li>
          </ul>
        </div>

        <div>
          <h2 class="text-lg font-semibold text-slate-900">Step-by-Step Walkthrough</h2>
          <div class="mt-3 space-y-3">
            <div class="p-4 rounded-xl bg-slate-50 border border-slate-200">
              <strong class="block text-slate-800">Step 1:</strong>
              Remove jewelry and don PPE as appropriate.
            </div>
            <div class="p-4 rounded-xl bg-slate-50 border border-slate-200">
              <strong class="block text-slate-800">Step 2:</strong>
              Apply alcohol-based hand rub or soap and water.
            </div>
            <div class="p-4 rounded-xl bg-slate-50 border border-slate-200">
              <strong class="block text-slate-800">Step 3:</strong>
              Perform 7-step hand hygiene technique until dry (20–30s for ABHR, 40–60s for wash).
            </div>
          </div>
        </div>

        <div>
          <h2 class="text-lg font-semibold text-slate-900">Demo Video</h2>
          <div class="mt-2 aspect-video rounded-xl bg-slate-100 flex items-center justify-center text-slate-400">
            [ Embedded Demo Video Placeholder ]
          </div>
        </div>

        <div class="text-right">
          <a href="/storage/procedures/hand-hygiene.pdf"
             class="inline-flex items-center gap-2 rounded-xl bg-slate-900 px-4 py-2 text-sm font-medium text-white hover:opacity-95">
            <i data-lucide="download" class="h-4 w-4"></i> Download PDF
          </a>
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
