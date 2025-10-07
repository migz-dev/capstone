<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" /><meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Edit Nursing Care Plan · NurSync — CI</title>
  @vite(['resources/css/app.css','resources/js/app.js'])
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
  <style> body{font-family:'Poppins',ui-sans-serif,system-ui,sans-serif;} </style>
</head>
<body class="min-h-screen bg-slate-50">
<main class="min-h-screen flex">
  @include('partials.faculty-sidebar', ['active'=>'chartings'])

  <section class="flex-1 px-6 md:px-8 py-8 md:py-10">
    <div class="flex items-center justify-between">
      <div class="flex items-center gap-3">
        <span class="inline-flex h-9 w-9 items-center justify-center rounded-xl bg-slate-100">
          <i data-lucide="target" class="h-5 w-5 text-slate-700"></i>
        </span>
        <div>
          <h1 class="text-2xl font-bold">Edit Nursing Care Plan</h1>
          <p class="text-[13px] text-slate-500 mt-0.5">{{ $plan->patient_name ?: '—' }}</p>
        </div>
      </div>
      <a href="{{ route('faculty.chartings.ncp.show', $plan) }}"
         class="text-[13px] px-3 h-9 inline-flex items-center rounded-lg bg-slate-100 hover:bg-slate-200">
        <i data-lucide="arrow-left" class="h-4 w-4 mr-1"></i> Back to View
      </a>
    </div>

    @if ($errors->any())
      <div class="mt-4 rounded-xl border border-rose-200 bg-rose-50 text-rose-800 px-4 py-3 text-[13px]">
        <strong class="font-semibold">Please fix the following:</strong>
        <ul class="list-disc ml-5 mt-1">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
      </div>
    @endif

    <form class="mt-6" method="POST" action="{{ route('faculty.chartings.ncp.update', $plan) }}" novalidate>
      @csrf @method('PUT')
      <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5 space-y-6">
        <div class="grid md:grid-cols-3 gap-4">
          <div class="md:col-span-1">
            <label class="block text-[12px] text-slate-500 mb-1">Patient Name <span class="text-rose-600">*</span></label>
            <input type="text" name="patient_name" value="{{ old('patient_name', $plan->patient_name) }}"
                   class="w-full rounded-xl border-slate-300 text-[14px] @error('patient_name') ring-2 ring-rose-300 @enderror" required>
            @error('patient_name') <p class="mt-1 text-[12px] text-rose-600">{{ $message }}</p> @enderror
          </div>
          <div>
            <label class="block text-[12px] text-slate-500 mb-1">Noted at <span class="text-rose-600">*</span></label>
            <input type="datetime-local" name="noted_at"
                   value="{{ old('noted_at', optional($plan->noted_at)->format('Y-m-d\TH:i')) }}"
                   class="w-full rounded-xl border-slate-300 text-[14px] @error('noted_at') ring-2 ring-rose-300 @enderror" required>
            @error('noted_at') <p class="mt-1 text-[12px] text-rose-600">{{ $message }}</p> @enderror
          </div>
        </div>

        <div class="grid md:grid-cols-3 gap-4">
          <div>
            <label class="block text-[12px] text-slate-500 mb-1">NANDA Dx (Primary) <span class="text-rose-600">*</span></label>
            <textarea name="dx_primary" rows="3"
              class="w-full rounded-xl border-slate-300 text-[14px] @error('dx_primary') ring-2 ring-rose-300 @enderror">{{ old('dx_primary', $plan->dx_primary) }}</textarea>
            @error('dx_primary') <p class="mt-1 text-[12px] text-rose-600">{{ $message }}</p> @enderror
          </div>
          <div>
            <label class="block text-[12px] text-slate-500 mb-1">Related to</label>
            <textarea name="dx_related_to" rows="3" class="w-full rounded-xl border-slate-300 text-[14px]">{{ old('dx_related_to', $plan->dx_related_to) }}</textarea>
          </div>
          <div>
            <label class="block text-[12px] text-slate-500 mb-1">As evidenced by</label>
            <textarea name="dx_as_evidenced_by" rows="3" class="w-full rounded-xl border-slate-300 text-[14px]">{{ old('dx_as_evidenced_by', $plan->dx_as_evidenced_by) }}</textarea>
          </div>
        </div>

        <div class="grid md:grid-cols-2 gap-4">
          <div>
            <label class="block text-[12px] text-slate-500 mb-1">Short-term Goal</label>
            <textarea name="goal_short" rows="3" class="w-full rounded-xl border-slate-300 text-[14px]">{{ old('goal_short', $plan->goal_short) }}</textarea>
          </div>
          <div>
            <label class="block text-[12px] text-slate-500 mb-1">Long-term Goal</label>
            <textarea name="goal_long" rows="3" class="w-full rounded-xl border-slate-300 text-[14px]">{{ old('goal_long', $plan->goal_long) }}</textarea>
          </div>
        </div>

        <div>
          <label class="block text-[12px] text-slate-500 mb-1">Nursing Interventions <span class="text-rose-600">*</span></label>
          <textarea name="interventions" rows="6"
            class="w-full rounded-xl border-slate-300 text-[14px] @error('interventions') ring-2 ring-rose-300 @enderror">{{ old('interventions', $plan->interventions) }}</textarea>
          @error('interventions') <p class="mt-1 text-[12px] text-rose-600">{{ $message }}</p> @enderror
        </div>

        <div>
          <label class="block text-[12px] text-slate-500 mb-1">Evaluation</label>
          <textarea name="evaluation" rows="4" class="w-full rounded-xl border-slate-300 text-[14px]">{{ old('evaluation', $plan->evaluation) }}</textarea>
        </div>

        <div>
          <label class="block text-[12px] text-slate-500 mb-1">Remarks (optional)</label>
          <input type="text" name="remarks" value="{{ old('remarks', $plan->remarks) }}" class="w-full rounded-xl border-slate-300 text-[14px]" />
        </div>
      </div>

      <div class="mt-5 flex items-center gap-2">
        <button class="inline-flex items-center h-10 px-4 rounded-xl bg-emerald-600 text-white text-[13px] font-semibold">
          <i data-lucide="save" class="h-4 w-4 mr-2"></i> Update Plan
        </button>
        <a href="{{ route('faculty.chartings.ncp.show', $plan) }}" class="inline-flex items-center h-10 px-3 rounded-xl bg-slate-100 text-[13px]">Cancel</a>
      </div>
    </form>
  </section>
</main>

@include('partials.faculty-footer')
<script src="https://unpkg.com/lucide@latest"></script>
<script>lucide.createIcons();</script>
</body>
</html>
