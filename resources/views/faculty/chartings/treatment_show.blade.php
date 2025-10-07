<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" /><meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Treatment — View · NurSync — CI</title>
  @vite(['resources/css/app.css','resources/js/app.js'])
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
  <style>body{font-family:'Poppins',ui-sans-serif,system-ui,sans-serif}</style>
</head>
<body class="min-h-screen bg-slate-50">
<main class="min-h-screen flex">
  @include('partials.faculty-sidebar', ['active'=>'chartings'])
  <section class="flex-1 px-6 md:px-8 py-8 md:py-10">
    @php
      $pname = $treatment->patient->full_name ?? '—';
      $unit  = $treatment->encounter->unit ?? '—';
    @endphp
    <div class="flex items-center justify-between">
      <div class="flex items-center gap-3">
        <span class="inline-flex h-9 w-9 items-center justify-center rounded-xl bg-emerald-50 text-emerald-700 ring-1 ring-emerald-100">
          <i data-lucide="stethoscope" class="h-5 w-5"></i>
        </span>
        <div>
          <h1 class="text-2xl font-bold">Treatment — {{ $pname }}</h1>
          <p class="text-[13px] text-slate-500 mt-0.5">Enc#{{ $treatment->encounter_id }} · {{ $unit }}</p>
        </div>
      </div>
      <a href="{{ route('faculty.chartings.treatment.index') }}" class="text-[13px] px-3 h-9 inline-flex items-center rounded-lg bg-slate-100 hover:bg-slate-200">
        <i data-lucide="arrow-left" class="h-4 w-4 mr-1"></i> Back to List
      </a>
    </div>

    <div class="mt-6 bg-white rounded-2xl border border-slate-200 shadow-sm p-5 grid md:grid-cols-3 gap-4">
      <div><div class="text-[12px] text-slate-500">Procedure</div><div class="text-[15px] font-semibold">{{ $treatment->procedure_name }}</div></div>
      <div><div class="text-[12px] text-slate-500">Indication</div><div class="text-[15px] font-semibold">{{ $treatment->indication ?: '—' }}</div></div>
      <div><div class="text-[12px] text-slate-500">Consent</div><div class="text-[15px] font-semibold">{{ $treatment->consent_obtained ? 'Yes' : 'No' }}</div></div>

      <div><div class="text-[12px] text-slate-500">Sterile Technique</div><div class="text-[15px] font-semibold">{{ $treatment->sterile_technique ? 'Yes' : 'No' }}</div></div>
      <div><div class="text-[12px] text-slate-500">Started</div><div class="text-[15px] font-semibold">{{ $treatment->started_at?->format('Y-m-d H:i') ?? '—' }}</div></div>
      <div><div class="text-[12px] text-slate-500">Ended</div><div class="text-[15px] font-semibold">{{ $treatment->ended_at?->format('Y-m-d H:i') ?? '—' }}</div></div>

      <div><div class="text-[12px] text-slate-500">Performed by</div><div class="text-[15px] font-semibold">{{ $treatment->performed_by ?: '—' }}</div></div>
      <div><div class="text-[12px] text-slate-500">Assisted by</div><div class="text-[15px] font-semibold">{{ $treatment->assisted_by ?: '—' }}</div></div>

      <div class="md:col-span-3"><div class="text-[12px] text-slate-500">Pre-procedure notes</div><div class="text-[14px]">{{ $treatment->pre_notes ?: '—' }}</div></div>
      <div class="md:col-span-3"><div class="text-[12px] text-slate-500">Outcome</div><div class="text-[14px]">{{ $treatment->outcome ?: '—' }}</div></div>
      <div class="md:col-span-3"><div class="text-[12px] text-slate-500">Complications</div><div class="text-[14px]">{{ $treatment->complications ?: '—' }}</div></div>
      <div class="md:col-span-3"><div class="text-[12px] text-slate-500">Post-procedure notes</div><div class="text-[14px]">{{ $treatment->post_notes ?: '—' }}</div></div>
      <div class="md:col-span-3"><div class="text-[12px] text-slate-500">Remarks</div><div class="text-[14px]">{{ $treatment->remarks ?: '—' }}</div></div>
    </div>
  </section>
</main>
@include('partials.faculty-footer')
<script src="https://unpkg.com/lucide@latest"></script>
<script>lucide.createIcons();</script>
</body>
</html>
