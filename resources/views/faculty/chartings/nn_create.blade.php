{{-- resources/views/faculty/chartings/nurses_notes/nn_create.blade.php --}}
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" /><meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>New Nurse’s Notes · NurSync — CI</title>
  @vite(['resources/css/app.css','resources/js/app.js'])
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
  <style> body{font-family:'Poppins',ui-sans-serif,system-ui,sans-serif;} </style>
</head>
<body class="min-h-screen bg-slate-50">
<main class="min-h-screen flex">
  @include('partials.faculty-sidebar', ['active'=>'chartings'])

  <section class="flex-1 px-6 md:px-8 py-8 md:py-10">
    {{-- Header --}}
    <div class="flex items-center justify-between">
      <div class="flex items-center gap-3">
        <span class="inline-flex h-9 w-9 items-center justify-center rounded-xl bg-emerald-50 text-emerald-700 ring-1 ring-emerald-100">
          <i data-lucide="notebook-pen" class="h-5 w-5"></i>
        </span>
        <div>
          <h1 class="text-2xl font-bold">New Nurse’s Notes</h1>
          <p class="text-[13px] text-slate-500 mt-0.5">Pick a format or write a narrative note.</p>
        </div>
      </div>
      <a href="{{ route('faculty.chartings.nurses_notes.index') }}"
         class="text-[13px] px-3 h-9 inline-flex items-center rounded-lg bg-slate-100 hover:bg-slate-200">
        <i data-lucide="arrow-left" class="h-4 w-4 mr-1"></i> Back to List
      </a>
    </div>

    {{-- Validation summary --}}
    @if ($errors->any())
      <div class="mt-4 rounded-xl border border-rose-200 bg-rose-50 text-rose-800 px-4 py-3 text-[13px]">
        <strong class="font-semibold">Please fix the following:</strong>
        <ul class="list-disc ml-5 mt-1">
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    {{-- FORM --}}
    <form class="mt-6" method="POST" action="{{ route('faculty.chartings.nurses_notes.store') }}" novalidate>
      @csrf
      <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5 space-y-5">

        {{-- Top row: Patient (free text), Noted at, Format --}}
        <div class="grid md:grid-cols-4 gap-4">

          {{-- Patient (free text, with optional suggestions) --}}
          <div class="md:col-span-2">
            <label class="block text-[12px] text-slate-500 mb-1">Patient (name or code)</label>
            @php
              $encOpts = (isset($encounters) && $encounters instanceof \Illuminate\Support\Collection) ? $encounters : collect();
              $patientOld = old('patient_name', request('patient_name'));
            @endphp
            <input type="text"
                   name="patient_name"
                   list="{{ $encOpts->isNotEmpty() ? 'patientsList' : '' }}"
                   value="{{ $patientOld }}"
                   placeholder="e.g., Juan Dela Cruz"
                   class="w-full rounded-xl border-slate-300 text-[14px] @error('patient_name') ring-2 ring-rose-300 @enderror">
            @if ($encOpts->isNotEmpty())
              <datalist id="patientsList">
                @foreach ($encOpts as $e)
                  @php $p = $e->patient->full_name ?? null; @endphp
                  @if($p)<option value="{{ $p }}"></option>@endif
                @endforeach
              </datalist>
            @endif
            <p class="mt-1 text-[12px] text-slate-500">Free text. Used for quick labeling in lists/headers.</p>
            @error('patient_name') <p class="mt-1 text-[12px] text-rose-600">{{ $message }}</p> @enderror
          </div>

          {{-- Noted at --}}
          <div>
            <label class="block text-[12px] text-slate-500 mb-1">Noted at <span class="text-rose-600">*</span></label>
            <input type="datetime-local" name="noted_at"
                   value="{{ old('noted_at') }}"
                   class="w-full rounded-xl border-slate-300 text-[14px] @error('noted_at') ring-2 ring-rose-300 @enderror" required>
            @error('noted_at') <p class="mt-1 text-[12px] text-rose-600">{{ $message }}</p> @enderror
          </div>

          {{-- Format --}}
          <div>
            <label class="block text-[12px] text-slate-500 mb-1">Format</label>
            <select id="fmt" name="format"
                    class="w-full rounded-xl border-slate-300 text-[14px] @error('format') ring-2 ring-rose-300 @enderror">
              @php $fmt = old('format','narrative'); @endphp
              <option value="narrative" {{ $fmt==='narrative'?'selected':'' }}>Narrative</option>
              <option value="dar"       {{ $fmt==='dar'?'selected':'' }}>DAR</option>
              <option value="soapie"    {{ $fmt==='soapie'?'selected':'' }}>SOAPIE</option>
              <option value="pie"       {{ $fmt==='pie'?'selected':'' }}>PIE</option>
            </select>
            @error('format') <p class="mt-1 text-[12px] text-rose-600">{{ $message }}</p> @enderror
          </div>
        </div>

        {{-- Narrative --}}
        <div id="boxNarrative" class="{{ $fmt==='narrative' ? '' : 'hidden' }}">
          <label class="block text-[12px] text-slate-500 mb-1">Narrative Note</label>
          <textarea name="narrative" rows="6"
                    class="w-full rounded-xl border-slate-300 text-[14px] @error('narrative') ring-2 ring-rose-300 @enderror"
                    placeholder="Patient assessed…">{{ old('narrative') }}</textarea>
          @error('narrative') <p class="mt-1 text-[12px] text-rose-600">{{ $message }}</p> @enderror
        </div>

        {{-- DAR --}}
        <div id="boxDAR" class="{{ $fmt==='dar' ? 'grid md:grid-cols-3 gap-4' : 'hidden grid md:grid-cols-3 gap-4' }}">
          <div>
            <label class="block text-[12px] text-slate-500 mb-1">D – Data</label>
            <textarea name="dar_data" rows="4"
                      class="w-full rounded-xl border-slate-300 text-[14px] @error('dar_data') ring-2 ring-rose-300 @enderror">{{ old('dar_data') }}</textarea>
            @error('dar_data') <p class="mt-1 text-[12px] text-rose-600">{{ $message }}</p> @enderror
          </div>
          <div>
            <label class="block text-[12px] text-slate-500 mb-1">A – Action</label>
            <textarea name="dar_action" rows="4"
                      class="w-full rounded-xl border-slate-300 text-[14px] @error('dar_action') ring-2 ring-rose-300 @enderror">{{ old('dar_action') }}</textarea>
            @error('dar_action') <p class="mt-1 text-[12px] text-rose-600">{{ $message }}</p> @enderror
          </div>
          <div>
            <label class="block text-[12px] text-slate-500 mb-1">R – Response</label>
            <textarea name="dar_response" rows="4"
                      class="w-full rounded-xl border-slate-300 text-[14px] @error('dar_response') ring-2 ring-rose-300 @enderror">{{ old('dar_response') }}</textarea>
            @error('dar_response') <p class="mt-1 text-[12px] text-rose-600">{{ $message }}</p> @enderror
          </div>
        </div>

        {{-- SOAPIE --}}
        <div id="boxSOAPIE" class="{{ $fmt==='soapie' ? 'grid md:grid-cols-3 gap-4' : 'hidden grid md:grid-cols-3 gap-4' }}">
          <div>
            <label class="block text-[12px] text-slate-500 mb-1">S – Subjective</label>
            <textarea name="soapie_s" rows="3"
                      class="w-full rounded-xl border-slate-300 text-[14px] @error('soapie_s') ring-2 ring-rose-300 @enderror">{{ old('soapie_s') }}</textarea>
            @error('soapie_s') <p class="mt-1 text-[12px] text-rose-600">{{ $message }}</p> @enderror
          </div>
          <div>
            <label class="block text-[12px] text-slate-500 mb-1">O – Objective</label>
            <textarea name="soapie_o" rows="3"
                      class="w-full rounded-xl border-slate-300 text-[14px] @error('soapie_o') ring-2 ring-rose-300 @enderror">{{ old('soapie_o') }}</textarea>
            @error('soapie_o') <p class="mt-1 text-[12px] text-rose-600">{{ $message }}</p> @enderror
          </div>
          <div>
            <label class="block text-[12px] text-slate-500 mb-1">A – Assessment</label>
            <textarea name="soapie_a" rows="3"
                      class="w-full rounded-xl border-slate-300 text-[14px] @error('soapie_a') ring-2 ring-rose-300 @enderror">{{ old('soapie_a') }}</textarea>
            @error('soapie_a') <p class="mt-1 text-[12px] text-rose-600">{{ $message }}</p> @enderror
          </div>
          <div>
            <label class="block text-[12px] text-slate-500 mb-1">P – Plan</label>
            <textarea name="soapie_p" rows="3"
                      class="w-full rounded-xl border-slate-300 text-[14px] @error('soapie_p') ring-2 ring-rose-300 @enderror">{{ old('soapie_p') }}</textarea>
            @error('soapie_p') <p class="mt-1 text-[12px] text-rose-600">{{ $message }}</p> @enderror
          </div>
          <div>
            <label class="block text-[12px] text-slate-500 mb-1">I – Intervention</label>
            <textarea name="soapie_i" rows="3"
                      class="w-full rounded-xl border-slate-300 text-[14px] @error('soapie_i') ring-2 ring-rose-300 @enderror">{{ old('soapie_i') }}</textarea>
            @error('soapie_i') <p class="mt-1 text-[12px] text-rose-600">{{ $message }}</p> @enderror
          </div>
          <div>
            <label class="block text-[12px] text-slate-500 mb-1">E – Evaluation</label>
            <textarea name="soapie_e" rows="3"
                      class="w-full rounded-xl border-slate-300 text-[14px] @error('soapie_e') ring-2 ring-rose-300 @enderror">{{ old('soapie_e') }}</textarea>
            @error('soapie_e') <p class="mt-1 text-[12px] text-rose-600">{{ $message }}</p> @enderror
          </div>
        </div>

        {{-- PIE --}}
        <div id="boxPIE" class="{{ $fmt==='pie' ? 'grid md:grid-cols-3 gap-4' : 'hidden grid md:grid-cols-3 gap-4' }}">
          <div>
            <label class="block text-[12px] text-slate-500 mb-1">P – Problem</label>
            <textarea name="pie_p" rows="4"
                      class="w-full rounded-xl border-slate-300 text-[14px] @error('pie_p') ring-2 ring-rose-300 @enderror">{{ old('pie_p') }}</textarea>
            @error('pie_p') <p class="mt-1 text-[12px] text-rose-600">{{ $message }}</p> @enderror
          </div>
          <div>
            <label class="block text-[12px] text-slate-500 mb-1">I – Intervention</label>
            <textarea name="pie_i" rows="4"
                      class="w-full rounded-xl border-slate-300 text-[14px] @error('pie_i') ring-2 ring-rose-300 @enderror">{{ old('pie_i') }}</textarea>
            @error('pie_i') <p class="mt-1 text-[12px] text-rose-600">{{ $message }}</p> @enderror
          </div>
          <div>
            <label class="block text-[12px] text-slate-500 mb-1">E – Evaluation</label>
            <textarea name="pie_e" rows="4"
                      class="w-full rounded-xl border-slate-300 text-[14px] @error('pie_e') ring-2 ring-rose-300 @enderror">{{ old('pie_e') }}</textarea>
            @error('pie_e') <p class="mt-1 text-[12px] text-rose-600">{{ $message }}</p> @enderror
          </div>
        </div>

      </div>

      {{-- Actions --}}
      <div class="mt-5 flex items-center gap-2">
        <button type="submit" class="inline-flex items-center h-10 px-4 rounded-xl bg-emerald-600 text-white text-[13px] font-semibold">
          <i data-lucide="save" class="h-4 w-4 mr-2"></i> Save Note
        </button>
        <a href="{{ route('faculty.chartings.nurses_notes.index') }}"
           class="inline-flex items-center h-10 px-3 rounded-xl bg-slate-100 text-[13px]">Cancel</a>
      </div>
    </form>
  </section>
</main>

@include('partials.faculty-footer')
<script src="https://unpkg.com/lucide@latest"></script>
<script>
  lucide.createIcons();
  const fmt = document.getElementById('fmt');
  const boxes = {
    narrative: document.getElementById('boxNarrative'),
    dar:       document.getElementById('boxDAR'),
    soapie:    document.getElementById('boxSOAPIE'),
    pie:       document.getElementById('boxPIE'),
  };
  function sync() {
    Object.values(boxes).forEach(b => b.classList.add('hidden'));
    (boxes[fmt.value] || boxes.narrative).classList.remove('hidden');
  }
  fmt.addEventListener('change', sync);
  sync();
</script>
</body>
</html>
