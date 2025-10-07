{{-- resources/views/faculty/encounters-open.blade.php --}}
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Manage Sessions · NurSync — CI</title>
  @vite(['resources/css/app.css','resources/js/app.js'])
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
  <style> body { font-family: 'Poppins', ui-sans-serif, system-ui, sans-serif; } </style>
</head>
<body class="min-h-screen bg-slate-50">
  <main class="min-h-screen flex">
    @include('partials.faculty-sidebar', ['active' => 'chartings'])

    <section class="flex-1 px-6 md:px-8 py-8 md:py-10">
      <div class="flex items-center justify-between">
        <div class="flex items-center gap-3">
          <span class="inline-flex h-9 w-9 items-center justify-center rounded-xl bg-slate-100">
            <i data-lucide="folder-open" class="h-5 w-5 text-slate-700"></i>
          </span>
          <div>
            <h1 class="text-2xl font-bold">Manage Sessions</h1>
            <p class="text-[13px] text-slate-500 mt-0.5">Open an encounter, then continue documenting.</p>
          </div>
        </div>
        <a href="{{ route('faculty.chartings.index') }}"
           class="rounded-xl border border-slate-200 px-3 py-2 text-[13px] hover:bg-slate-50">Back</a>
      </div>

      <div class="mt-6 grid gap-6 md:grid-cols-2">
        {{-- Quick open by ID --}}
        <div class="rounded-2xl border border-slate-200 bg-white p-5">
          <div class="text-sm font-semibold text-slate-900">Open by Encounter ID</div>
          <form method="GET" action="{{ route('faculty.chartings.document.vitals') }}" class="mt-3 flex items-center gap-3">
            <input type="number" name="encounter_id" class="rounded-xl border border-slate-200 px-3 py-2 text-[13px] w-40" placeholder="e.g., 1001" required>
            <button class="rounded-xl bg-emerald-600 text-white px-4 py-2 text-[13px] font-semibold hover:bg-emerald-700">
              Open in Vitals
            </button>
          </form>
          <p class="mt-2 text-[12px] text-slate-500">We’ll pass the ID to the Vitals form.</p>
        </div>

        {{-- (Optional) Recent encounters list placeholder --}}
        <div class="rounded-2xl border border-slate-200 bg-white p-5">
          <div class="text-sm font-semibold text-slate-900">Recent Encounters</div>
          <p class="mt-2 text-[12px] text-slate-500">Hook this up later to your encounters table.</p>
          <ul class="mt-3 space-y-2 text-[13px]">
            <li class="flex items-center justify-between rounded-xl border border-slate-100 px-3 py-2">
              <span>#1001 · Ward MS</span>
              <a href="{{ route('faculty.chartings.document.vitals', ['encounter_id'=>1001]) }}" class="text-emerald-700 hover:underline">Open</a>
            </li>
            <li class="flex items-center justify-between rounded-xl border border-slate-100 px-3 py-2">
              <span>#1002 · PACU</span>
              <a href="{{ route('faculty.chartings.document.vitals', ['encounter_id'=>1002]) }}" class="text-emerald-700 hover:underline">Open</a>
            </li>
          </ul>
        </div>
      </div>
    </section>
  </main>

  @include('partials.faculty-footer')

  <script src="https://unpkg.com/lucide@latest"></script>
  <script> lucide.createIcons(); </script>
</body>
</html>
