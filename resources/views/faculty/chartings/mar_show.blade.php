<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" /><meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>MAR — View · NurSync — CI</title>
  @vite(['resources/css/app.css','resources/js/app.js'])
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
  <style>body{font-family:'Poppins',ui-sans-serif,system-ui,sans-serif}</style>
</head>
<body class="min-h-screen bg-slate-50">
<main class="min-h-screen flex">
  @include('partials.faculty-sidebar', ['active'=>'chartings'])
  <section class="flex-1 px-6 md:px-8 py-8 md:py-10">
    @php
      $pname = $mar->patient->full_name ?? '—';
      $unit  = $mar->encounter->unit ?? '—';
    @endphp
    <div class="flex items-center justify-between">
      <div class="flex items-center gap-3">
        <span class="inline-flex h-9 w-9 items-center justify-center rounded-xl bg-emerald-50 text-emerald-700 ring-1 ring-emerald-100">
          <i data-lucide="pill" class="h-5 w-5"></i>
        </span>
        <div>
          <h1 class="text-2xl font-bold">MAR — {{ $pname }}</h1>
          <p class="text-[13px] text-slate-500 mt-0.5">Enc#{{ $mar->encounter_id }} · {{ $unit }}</p>
        </div>
      </div>
      <a href="{{ route('faculty.chartings.mar.index') }}" class="text-[13px] px-3 h-9 inline-flex items-center rounded-lg bg-slate-100 hover:bg-slate-200">
        <i data-lucide="arrow-left" class="h-4 w-4 mr-1"></i> Back to MAR
      </a>
    </div>

    <div class="mt-6 bg-white rounded-2xl border border-slate-200 shadow-sm p-5 grid md:grid-cols-3 gap-4">
      <div><div class="text-[12px] text-slate-500">Medication</div><div class="text-[15px] font-semibold">{{ $mar->medication }}</div></div>
      <div><div class="text-[12px] text-slate-500">Dose</div><div class="text-[15px] font-semibold">
        @if(!is_null($mar->dose_amount)) {{ rtrim(rtrim(number_format($mar->dose_amount,2), '0'), '.') }} @endif {{ $mar->dose_unit }}
      </div></div>
      <div><div class="text-[12px] text-slate-500">Route</div><div class="text-[15px] font-semibold">{{ $mar->route }}</div></div>
      <div><div class="text-[12px] text-slate-500">Frequency</div><div class="text-[15px] font-semibold">{{ $mar->frequency }}</div></div>
      <div><div class="text-[12px] text-slate-500">PRN</div><div class="text-[15px] font-semibold">{{ $mar->is_prn ? 'Yes' : 'No' }}</div></div>
      <div><div class="text-[12px] text-slate-500">PRN Reason</div><div class="text-[15px] font-semibold">{{ $mar->prn_reason ?: '—' }}</div></div>
      <div><div class="text-[12px] text-slate-500">Administered At</div><div class="text-[15px] font-semibold">{{ $mar->administered_at?->format('Y-m-d H:i') ?? '—' }}</div></div>
      <div><div class="text-[12px] text-slate-500">Omitted Reason</div><div class="text-[15px] font-semibold">{{ $mar->omitted_reason ?: '—' }}</div></div>
      <div class="md:col-span-3">
        <div class="text-[12px] text-slate-500">Effects</div>
        <div class="text-[14px]">{{ $mar->effects ?: '—' }}</div>
      </div>
      <div class="md:col-span-3">
        <div class="text-[12px] text-slate-500">Remarks</div>
        <div class="text-[14px]">{{ $mar->remarks ?: '—' }}</div>
      </div>
    </div>
  </section>
</main>
@include('partials.faculty-footer')
<script src="https://unpkg.com/lucide@latest"></script>
<script>lucide.createIcons();</script>
</body>
</html>
