<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8"/><meta name="viewport" content="width=device-width, initial-scale=1"/>
  <title>I&O — View · NurSync — CI</title>
  @vite(['resources/css/app.css','resources/js/app.js'])
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
  <style>body{font-family:'Poppins',ui-sans-serif,system-ui,sans-serif}</style>
</head>
<body class="min-h-screen bg-slate-50">
<main class="min-h-screen flex">
  @include('partials.faculty-sidebar', ['active'=>'chartings'])
  <section class="flex-1 px-6 md:px-8 py-8 md:py-10">
    @php $pname = $io->patient->full_name ?? '—'; $unit = $io->encounter->unit ?? '—'; @endphp
    <div class="flex items-center justify-between">
      <div class="flex items-center gap-3">
        <span class="inline-flex h-9 w-9 items-center justify-center rounded-xl bg-emerald-50 text-emerald-700 ring-1 ring-emerald-100">
          <i data-lucide="beaker" class="h-5 w-5"></i>
        </span>
        <div>
          <h1 class="text-2xl font-bold">Intake & Output — {{ $pname }}</h1>
          <p class="text-[13px] text-slate-500 mt-0.5">Enc#{{ $io->encounter_id }} · {{ $unit }}</p>
        </div>
      </div>
      <a href="{{ route('faculty.chartings.io.index') }}" class="text-[13px] px-3 h-9 inline-flex items-center rounded-lg bg-slate-100 hover:bg-slate-200">
        <i data-lucide="arrow-left" class="h-4 w-4 mr-1"></i> Back to List
      </a>
    </div>

    <div class="mt-6 bg-white rounded-2xl border border-slate-200 shadow-sm p-5 grid md:grid-cols-3 gap-4">
      <div><div class="text-[12px] text-slate-500">Shift</div><div class="text-[15px] font-semibold">{{ $io->started_at?->format('Y-m-d H:i') ?? '—' }} – {{ $io->ended_at?->format('Y-m-d H:i') ?? '—' }}</div></div>

      <div class="md:col-span-3"><hr class="my-2 border-slate-100"/></div>

      <div><div class="text-[12px] text-slate-500">Intake Oral</div><div class="text-[15px] font-semibold">{{ $io->intake_oral_ml ?? 0 }} ml</div></div>
      <div><div class="text-[12px] text-slate-500">Intake IV</div><div class="text-[15px] font-semibold">{{ $io->intake_iv_ml ?? 0 }} ml</div></div>
      <div><div class="text-[12px] text-slate-500">Intake Tube</div><div class="text-[15px] font-semibold">{{ $io->intake_tube_ml ?? 0 }} ml</div></div>

      <div><div class="text-[12px] text-slate-500">Output Urine</div><div class="text-[15px] font-semibold">{{ $io->output_urine_ml ?? 0 }} ml</div></div>
      <div><div class="text-[12px] text-slate-500">Output Stool</div><div class="text-[15px] font-semibold">{{ $io->output_stool_ml ?? 0 }} ml</div></div>
      <div><div class="text-[12px] text-slate-500">Output Emesis</div><div class="text-[15px] font-semibold">{{ $io->output_emesis_ml ?? 0 }} ml</div></div>
      <div><div class="text-[12px] text-slate-500">Output Drain</div><div class="text-[15px] font-semibold">{{ $io->output_drain_ml ?? 0 }} ml</div></div>

      <div class="md:col-span-3"><hr class="my-2 border-slate-100"/></div>

      <div><div class="text-[12px] text-slate-500">Total Intake</div><div class="text-[15px] font-semibold">{{ $io->intake_total_ml }} ml</div></div>
      <div><div class="text-[12px] text-slate-500">Total Output</div><div class="text-[15px] font-semibold">{{ $io->output_total_ml }} ml</div></div>
      <div><div class="text-[12px] text-slate-500">Balance</div><div class="text-[15px] font-semibold {{ $io->balance_ml < 0 ? 'text-rose-600' : 'text-emerald-700' }}">{{ $io->balance_ml }} ml</div></div>

      <div class="md:col-span-3">
        <div class="text-[12px] text-slate-500">Remarks</div>
        <div class="text-[14px]">{{ $io->remarks ?: '—' }}</div>
      </div>
    </div>
  </section>
</main>
@include('partials.faculty-footer')
<script src="https://unpkg.com/lucide@latest"></script>
<script>lucide.createIcons();</script>
</body>
</html>
