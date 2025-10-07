<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8"/><meta name="viewport" content="width=device-width, initial-scale=1"/>
  <title>New Intake & Output · NurSync — CI</title>
  @vite(['resources/css/app.css','resources/js/app.js'])
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
  <style>body{font-family:'Poppins',ui-sans-serif,system-ui,sans-serif}</style>
</head>
<body class="min-h-screen bg-slate-50">
<main class="min-h-screen flex">
  @include('partials.faculty-sidebar', ['active'=>'chartings'])
  <section class="flex-1 px-6 md:px-8 py-8 md:py-10">

    <div class="flex items-center justify-between">
      <div class="flex items-center gap-3">
        <span class="inline-flex h-9 w-9 items-center justify-center rounded-xl bg-emerald-50 text-emerald-700 ring-1 ring-emerald-100">
          <i data-lucide="beaker" class="h-5 w-5"></i>
        </span>
        <div>
          <h1 class="text-2xl font-bold">New Intake & Output</h1>
          <p class="text-[13px] text-slate-500 mt-0.5">Enter shift window and fluid amounts.</p>
        </div>
      </div>
      <a href="{{ route('faculty.chartings.io.index') }}"
         class="text-[13px] px-3 h-9 inline-flex items-center rounded-lg bg-slate-100 hover:bg-slate-200">
        <i data-lucide="arrow-left" class="h-4 w-4 mr-1"></i> Back to List
      </a>
    </div>

    @if ($errors->any())
      <div class="mt-4 rounded-xl border border-rose-200 bg-rose-50 text-rose-800 px-4 py-3 text-[13px]">
        <strong class="font-semibold">Please fix the following:</strong>
        <ul class="list-disc ml-5 mt-1">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
      </div>
    @endif

    <form action="{{ route('faculty.chartings.io.store') }}" method="POST" class="mt-6" novalidate>
      @csrf

      {{-- Quick patient + encounter --}}
      <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5 space-y-5">
        <div class="grid md:grid-cols-3 gap-4">
          <div>
            <label class="block text-[12px] text-slate-500 mb-1">Patient Last Name <span class="text-rose-600">*</span></label>
            <input type="text" name="quick_patient[last_name]" value="{{ old('quick_patient.last_name') }}" class="w-full rounded-xl border-slate-300 text-[14px]" required />
          </div>
          <div>
            <label class="block text-[12px] text-slate-500 mb-1">Patient First Name <span class="text-rose-600">*</span></label>
            <input type="text" name="quick_patient[first_name]" value="{{ old('quick_patient.first_name') }}" class="w-full rounded-xl border-slate-300 text-[14px]" required />
          </div>
          <div>
            <label class="block text-[12px] text-slate-500 mb-1">Hospital No (optional)</label>
            <input type="text" name="quick_patient[hospital_no]" value="{{ old('quick_patient.hospital_no') }}" class="w-full rounded-xl border-slate-300 text-[14px]" />
          </div>
        </div>

        <div class="grid md:grid-cols-3 gap-4">
          <div>
            <label class="block text-[12px] text-slate-500 mb-1">Unit / Area <span class="text-rose-600">*</span></label>
            <input type="text" name="quick_encounter[unit]" value="{{ old('quick_encounter.unit') }}" class="w-full rounded-xl border-slate-300 text-[14px]" required />
          </div>
          <div>
            <label class="block text-[12px] text-slate-500 mb-1">Encounter Started At <span class="text-rose-600">*</span></label>
            <input type="datetime-local" name="quick_encounter[started_at]" value="{{ old('quick_encounter.started_at') }}" class="w-full rounded-xl border-slate-300 text-[14px]" required />
          </div>
          <div>
            <label class="block text-[12px] text-slate-500 mb-1">Encounter Notes (optional)</label>
            <input type="text" name="quick_encounter[remarks]" value="{{ old('quick_encounter.remarks') }}" class="w-full rounded-xl border-slate-300 text-[14px]" />
          </div>
        </div>
      </div>

      {{-- I&O --}}
      <div class="mt-5 bg-white rounded-2xl border border-slate-200 shadow-sm p-5 space-y-5">
        <div class="grid md:grid-cols-3 gap-4">
          <div>
            <label class="block text-[12px] text-slate-500 mb-1">Shift Start</label>
            <input type="datetime-local" name="started_at" value="{{ old('started_at') }}" class="w-full rounded-xl border-slate-300 text-[14px]" />
          </div>
          <div>
            <label class="block text-[12px] text-slate-500 mb-1">Shift End</label>
            <input type="datetime-local" name="ended_at" value="{{ old('ended_at') }}" class="w-full rounded-xl border-slate-300 text-[14px]" />
          </div>
        </div>

        <div class="grid md:grid-cols-3 gap-4">
          <div>
            <label class="block text-[12px] text-slate-500 mb-1">Intake — Oral (ml)</label>
            <input type="number" min="0" name="intake_oral_ml" value="{{ old('intake_oral_ml') }}" class="w-full rounded-xl border-slate-300 text-[14px]" />
          </div>
          <div>
            <label class="block text-[12px] text-slate-500 mb-1">Intake — IV (ml)</label>
            <input type="number" min="0" name="intake_iv_ml" value="{{ old('intake_iv_ml') }}" class="w-full rounded-xl border-slate-300 text-[14px]" />
          </div>
          <div>
            <label class="block text-[12px] text-slate-500 mb-1">Intake — Tube (ml)</label>
            <input type="number" min="0" name="intake_tube_ml" value="{{ old('intake_tube_ml') }}" class="w-full rounded-xl border-slate-300 text-[14px]" />
          </div>
        </div>

        <div class="grid md:grid-cols-4 gap-4">
          <div>
            <label class="block text-[12px] text-slate-500 mb-1">Output — Urine (ml)</label>
            <input type="number" min="0" name="output_urine_ml" value="{{ old('output_urine_ml') }}" class="w-full rounded-xl border-slate-300 text-[14px]" />
          </div>
          <div>
            <label class="block text-[12px] text-slate-500 mb-1">Output — Stool (ml)</label>
            <input type="number" min="0" name="output_stool_ml" value="{{ old('output_stool_ml') }}" class="w-full rounded-xl border-slate-300 text-[14px]" />
          </div>
          <div>
            <label class="block text-[12px] text-slate-500 mb-1">Output — Emesis (ml)</label>
            <input type="number" min="0" name="output_emesis_ml" value="{{ old('output_emesis_ml') }}" class="w-full rounded-xl border-slate-300 text-[14px]" />
          </div>
          <div>
            <label class="block text-[12px] text-slate-500 mb-1">Output — Drain (ml)</label>
            <input type="number" min="0" name="output_drain_ml" value="{{ old('output_drain_ml') }}" class="w-full rounded-xl border-slate-300 text-[14px]" />
          </div>
        </div>

        <div>
          <label class="block text-[12px] text-slate-500 mb-1">Remarks (optional)</label>
          <input type="text" name="remarks" value="{{ old('remarks') }}" class="w-full rounded-xl border-slate-300 text-[14px]" />
        </div>
      </div>

      <div class="mt-5 flex items-center gap-2">
        <button type="submit" class="inline-flex items-center h-10 px-4 rounded-xl bg-emerald-600 text-white text-[13px] font-semibold">
          <i data-lucide="save" class="h-4 w-4 mr-2"></i> Save I&O
        </button>
        <a href="{{ route('faculty.chartings.io.index') }}" class="inline-flex items-center h-10 px-3 rounded-xl bg-slate-100 text-[13px]">Cancel</a>
      </div>
    </form>
  </section>
</main>
@include('partials.faculty-footer')
<script src="https://unpkg.com/lucide@latest"></script>
<script>lucide.createIcons();</script>
</body>
</html>
