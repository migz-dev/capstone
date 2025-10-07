<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" /><meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Edit Treatment · NurSync — CI</title>
  @vite(['resources/css/app.css','resources/js/app.js'])
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
  <style>body{font-family:'Poppins',ui-sans-serif,system-ui,sans-serif}</style>
</head>
<body class="min-h-screen bg-slate-50">
<main class="min-h-screen flex">
  @include('partials.faculty-sidebar', ['active'=>'chartings'])
  <section class="flex-1 px-6 md:px-8 py-8 md:py-10">
    @php $pname = $treatment->patient->full_name ?? '—'; $unit = $treatment->encounter->unit ?? '—'; @endphp
    <div class="flex items-center justify-between">
      <div class="flex items-center gap-3">
        <span class="inline-flex h-9 w-9 items-center justify-center rounded-xl bg-emerald-50 text-emerald-700 ring-1 ring-emerald-100">
          <i data-lucide="stethoscope" class="h-5 w-5"></i>
        </span>
        <div>
          <h1 class="text-2xl font-bold">Edit Treatment — {{ $pname }}</h1>
          <p class="text-[13px] text-slate-500 mt-0.5">Enc#{{ $treatment->encounter_id }} · {{ $unit }}</p>
        </div>
      </div>
      <a href="{{ route('faculty.chartings.treatment.index') }}" class="text-[13px] px-3 h-9 inline-flex items-center rounded-lg bg-slate-100 hover:bg-slate-200">
        <i data-lucide="arrow-left" class="h-4 w-4 mr-1"></i> Back to List
      </a>
    </div>

    @if (session('status'))
      <div class="mt-4 rounded-xl border border-emerald-200 bg-emerald-50 text-emerald-800 px-4 py-2 text-[13px]">{{ session('status') }}</div>
    @endif

    @if ($errors->any())
      <div class="mt-4 rounded-xl border border-rose-200 bg-rose-50 text-rose-800 px-4 py-3 text-[13px]">
        <strong class="font-semibold">Please fix the following:</strong>
        <ul class="list-disc ml-5 mt-1">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
      </div>
    @endif

    <form action="{{ route('faculty.chartings.treatment.update', $treatment) }}" method="POST" class="mt-6" novalidate>
      @csrf @method('PUT')

      <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5 space-y-5">
        <div class="grid md:grid-cols-3 gap-4">
          <div>
            <label class="block text-[12px] text-slate-500 mb-1">Procedure <span class="text-rose-600">*</span></label>
            <input type="text" name="procedure_name" value="{{ old('procedure_name',$treatment->procedure_name) }}"
                   class="w-full rounded-xl border-slate-300 text-[14px] @error('procedure_name') ring-2 ring-rose-300 @enderror" required />
          </div>
          <div>
            <label class="block text-[12px] text-slate-500 mb-1">Indication</label>
            <input type="text" name="indication" value="{{ old('indication',$treatment->indication) }}"
                   class="w-full rounded-xl border-slate-300 text-[14px]" />
          </div>
          <div class="flex items-end gap-4">
            <label class="inline-flex items-center gap-2 text-[14px]">
              <input type="checkbox" name="consent_obtained" value="1" @checked(old('consent_obtained',$treatment->consent_obtained)) />
              <span>Consent obtained</span>
            </label>
            <label class="inline-flex items-center gap-2 text-[14px]">
              <input type="checkbox" name="sterile_technique" value="1" @checked(old('sterile_technique',$treatment->sterile_technique)) />
              <span>Sterile technique</span>
            </label>
          </div>
        </div>

        <div class="grid md:grid-cols-3 gap-4">
          <div>
            <label class="block text-[12px] text-slate-500 mb-1">Started</label>
            <input type="datetime-local" name="started_at"
                   value="{{ old('started_at', optional($treatment->started_at)->format('Y-m-d\TH:i')) }}"
                   class="w-full rounded-xl border-slate-300 text-[14px]" />
          </div>
          <div>
            <label class="block text-[12px] text-slate-500 mb-1">Ended</label>
            <input type="datetime-local" name="ended_at"
                   value="{{ old('ended_at', optional($treatment->ended_at)->format('Y-m-d\TH:i')) }}"
                   class="w-full rounded-xl border-slate-300 text-[14px]" />
          </div>
          <div></div>
        </div>

        <div class="grid md:grid-cols-3 gap-4">
          <div>
            <label class="block text-[12px] text-slate-500 mb-1">Performed by</label>
            <input type="text" name="performed_by" value="{{ old('performed_by',$treatment->performed_by) }}"
                   class="w-full rounded-xl border-slate-300 text-[14px]" />
          </div>
          <div>
            <label class="block text-[12px] text-slate-500 mb-1">Assisted by</label>
            <input type="text" name="assisted_by" value="{{ old('assisted_by',$treatment->assisted_by) }}"
                   class="w-full rounded-xl border-slate-300 text-[14px]" />
          </div>
        </div>

        <div>
          <label class="block text-[12px] text-slate-500 mb-1">Pre-procedure notes</label>
          <textarea name="pre_notes" rows="3" class="w-full rounded-xl border-slate-300 text-[14px]">{{ old('pre_notes',$treatment->pre_notes) }}</textarea>
        </div>
        <div>
          <label class="block text-[12px] text-slate-500 mb-1">Outcome</label>
          <textarea name="outcome" rows="3" class="w-full rounded-xl border-slate-300 text-[14px]">{{ old('outcome',$treatment->outcome) }}</textarea>
        </div>
        <div>
          <label class="block text-[12px] text-slate-500 mb-1">Complications</label>
          <textarea name="complications" rows="3" class="w-full rounded-xl border-slate-300 text-[14px]">{{ old('complications',$treatment->complications) }}</textarea>
        </div>
        <div>
          <label class="block text-[12px] text-slate-500 mb-1">Post-procedure notes</label>
          <textarea name="post_notes" rows="3" class="w-full rounded-xl border-slate-300 text-[14px]">{{ old('post_notes',$treatment->post_notes) }}</textarea>
        </div>
        <div>
          <label class="block text-[12px] text-slate-500 mb-1">Remarks</label>
          <input type="text" name="remarks" value="{{ old('remarks',$treatment->remarks) }}"
                 class="w-full rounded-xl border-slate-300 text-[14px]" />
        </div>
      </div>

      <div class="mt-5 flex items-center gap-2">
        <button type="submit" class="inline-flex items-center h-10 px-4 rounded-xl bg-emerald-600 text-white text-[13px] font-semibold">
          <i data-lucide="save" class="h-4 w-4 mr-2"></i> Update Treatment
        </button>
        <a href="{{ route('faculty.chartings.treatment.show', $treatment) }}" class="inline-flex items-center h-10 px-3 rounded-xl bg-slate-100 text-[13px]">Cancel</a>
      </div>
    </form>
  </section>
</main>
@include('partials.faculty-footer')
<script src="https://unpkg.com/lucide@latest"></script>
<script>lucide.createIcons();</script>
</body>
</html>
