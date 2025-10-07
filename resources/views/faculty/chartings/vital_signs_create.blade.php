<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
  <title>New Vital Signs · NurSync — CI</title>
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
    @include('partials.faculty-sidebar', ['active' => 'chartings'])
    <section class="flex-1 px-6 md:px-8 py-8 md:py-10">

      {{-- Header --}}
      <div class="flex items-center justify-between">
        <div class="flex items-center gap-3">
          <span
            class="inline-flex h-9 w-9 items-center justify-center rounded-xl bg-emerald-50 text-emerald-700 ring-1 ring-emerald-100">
            <i data-lucide="activity" class="h-5 w-5"></i>
          </span>
          <div>
            <h1 class="text-2xl font-bold">New Vital Signs</h1>
            <p class="text-[13px] text-slate-500 mt-0.5">Record T, P, R, BP, SpO₂, and pain scale.</p>
          </div>
        </div>
        <a href="{{ route('faculty.chartings.vital_signs.index') }}"
          class="text-[13px] px-3 h-9 inline-flex items-center rounded-lg bg-slate-100 hover:bg-slate-200">
          <i data-lucide="arrow-left" class="h-4 w-4 mr-1"></i> Back to Vital Signs
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


{{-- Form --}}
<form action="{{ route('faculty.chartings.vital_signs.store') }}" method="POST" class="mt-6" novalidate>
  @csrf
  <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5 space-y-5">

    {{-- Quick Encounter (inline create) --}}
    <input type="hidden" name="create_encounter" value="1">

    <div class="grid md:grid-cols-3 gap-4">
      {{-- Patient (Last, First) --}}
      <div class="md:col-span-1">
        <label for="qp_last" class="block text-[12px] text-slate-500 mb-1">
          Patient Last Name <span class="text-rose-600">*</span>
        </label>
        <input id="qp_last" type="text" name="quick_patient[last_name]" value="{{ old('quick_patient.last_name') }}"
               class="w-full rounded-xl border-slate-300 text-[14px] @error('quick_patient.last_name') ring-2 ring-rose-300 @enderror"
               placeholder="Dela Cruz" required />
        @error('quick_patient.last_name') <p class="mt-1 text-[12px] text-rose-600">{{ $message }}</p> @enderror
      </div>

      <div class="md:col-span-1">
        <label for="qp_first" class="block text-[12px] text-slate-500 mb-1">
          Patient First Name <span class="text-rose-600">*</span>
        </label>
        <input id="qp_first" type="text" name="quick_patient[first_name]" value="{{ old('quick_patient.first_name') }}"
               class="w-full rounded-xl border-slate-300 text-[14px] @error('quick_patient.first_name') ring-2 ring-rose-300 @enderror"
               placeholder="Juan" required />
        @error('quick_patient.first_name') <p class="mt-1 text-[12px] text-rose-600">{{ $message }}</p> @enderror
      </div>

      {{-- Unit --}}
      <div class="md:col-span-1">
        <label for="qe_unit" class="block text-[12px] text-slate-500 mb-1">
          Unit / Area <span class="text-rose-600">*</span>
        </label>
        <input id="qe_unit" type="text" name="quick_encounter[unit]" value="{{ old('quick_encounter.unit') }}"
               class="w-full rounded-xl border-slate-300 text-[14px] @error('quick_encounter.unit') ring-2 ring-rose-300 @enderror"
               placeholder="MS Ward" required />
        @error('quick_encounter.unit') <p class="mt-1 text-[12px] text-rose-600">{{ $message }}</p> @enderror
      </div>
    </div>

    <div class="grid md:grid-cols-3 gap-4">
      {{-- Encounter Started At --}}
      <div>
        <label for="qe_started" class="block text-[12px] text-slate-500 mb-1">
          Encounter Started At <span class="text-rose-600">*</span>
        </label>
        <input id="qe_started" type="datetime-local" name="quick_encounter[started_at]"
               value="{{ old('quick_encounter.started_at') }}"
               class="w-full rounded-xl border-slate-300 text-[14px] @error('quick_encounter.started_at') ring-2 ring-rose-300 @enderror"
               required autocomplete="off" />
        @error('quick_encounter.started_at') <p class="mt-1 text-[12px] text-rose-600">{{ $message }}</p> @enderror
      </div>

      {{-- Optional encounter notes --}}
      <div class="md:col-span-2">
        <label for="qe_notes" class="block text-[12px] text-slate-500 mb-1">Encounter Notes (optional)</label>
        <input id="qe_notes" type="text" name="quick_encounter[notes]" value="{{ old('quick_encounter.notes') }}"
               class="w-full rounded-xl border-slate-300 text-[14px] @error('quick_encounter.notes') ring-2 ring-rose-300 @enderror"
               placeholder="Initial assessment / reason for encounter…" />
        @error('quick_encounter.notes') <p class="mt-1 text-[12px] text-rose-600">{{ $message }}</p> @enderror
      </div>
    </div>

    {{-- Vital taken_at (separate from encounter started_at) --}}
    <div class="grid md:grid-cols-3 gap-4">
      <div>
        <label for="taken_at" class="block text-[12px] text-slate-500 mb-1">
          Vital Taken At <span class="text-rose-600">*</span>
        </label>
        <input id="taken_at" type="datetime-local" name="taken_at" value="{{ old('taken_at') }}"
               class="w-full rounded-xl border-slate-300 text-[14px] @error('taken_at') ring-2 ring-rose-300 @enderror"
               required autocomplete="off" />
        @error('taken_at') <p class="mt-1 text-[12px] text-rose-600">{{ $message }}</p> @enderror
      </div>
    </div>

    {{-- Measurements --}}
    <div class="grid md:grid-cols-6 gap-3">
      <div>
        <label for="temp_c" class="block text-[12px] text-slate-500 mb-1">Temp (°C)</label>
        <input id="temp_c" type="number" step="0.1" min="30" max="45" name="temp_c" value="{{ old('temp_c') }}"
               placeholder="T °C" class="w-full rounded-xl border-slate-300 text-[14px] @error('temp_c') ring-2 ring-rose-300 @enderror" />
        @error('temp_c') <p class="mt-1 text-[12px] text-rose-600">{{ $message }}</p> @enderror
      </div>

      <div>
        <label for="pulse_bpm" class="block text-[12px] text-slate-500 mb-1">Pulse (bpm)</label>
        <input id="pulse_bpm" type="number" min="20" max="220" name="pulse_bpm" value="{{ old('pulse_bpm') }}"
               placeholder="P bpm" class="w-full rounded-xl border-slate-300 text-[14px] @error('pulse_bpm') ring-2 ring-rose-300 @enderror" />
        @error('pulse_bpm') <p class="mt-1 text-[12px] text-rose-600">{{ $message }}</p> @enderror
      </div>

      <div>
        <label for="resp_rate" class="block text-[12px] text-slate-500 mb-1">Resp (cpm)</label>
        <input id="resp_rate" type="number" min="5" max="80" name="resp_rate" value="{{ old('resp_rate') }}"
               placeholder="R cpm" class="w-full rounded-xl border-slate-300 text-[14px] @error('resp_rate') ring-2 ring-rose-300 @enderror" />
        @error('resp_rate') <p class="mt-1 text-[12px] text-rose-600">{{ $message }}</p> @enderror
      </div>

      <div>
        <label for="bp_systolic" class="block text-[12px] text-slate-500 mb-1">BP Systolic</label>
        <input id="bp_systolic" type="number" min="50" max="260" name="bp_systolic" value="{{ old('bp_systolic') }}"
               placeholder="BP Sys" class="w-full rounded-xl border-slate-300 text-[14px] @error('bp_systolic') ring-2 ring-rose-300 @enderror" />
        @error('bp_systolic') <p class="mt-1 text-[12px] text-rose-600">{{ $message }}</p> @enderror
      </div>

      <div>
        <label for="bp_diastolic" class="block text-[12px] text-slate-500 mb-1">BP Diastolic</label>
        <input id="bp_diastolic" type="number" min="20" max="180" name="bp_diastolic" value="{{ old('bp_diastolic') }}"
               placeholder="BP Dia" class="w-full rounded-xl border-slate-300 text-[14px] @error('bp_diastolic') ring-2 ring-rose-300 @enderror" />
        @error('bp_diastolic') <p class="mt-1 text-[12px] text-rose-600">{{ $message }}</p> @enderror
      </div>

      <div>
        <label for="spo2" class="block text-[12px] text-slate-500 mb-1">SpO₂ (%)</label>
        <input id="spo2" type="number" min="0" max="100" name="spo2" value="{{ old('spo2') }}"
               placeholder="SpO₂ %" class="w-full rounded-xl border-slate-300 text-[14px] @error('spo2') ring-2 ring-rose-300 @enderror" />
        @error('spo2') <p class="mt-1 text-[12px] text-rose-600">{{ $message }}</p> @enderror
      </div>
    </div>

    {{-- Remarks + Pain --}}
    <div class="grid md:grid-cols-3 gap-4 items-center">
      <div class="md:col-span-2">
        <label for="remarks" class="block text-[12px] text-slate-500 mb-1">Remarks (optional)</label>
        <input id="remarks" type="text" name="remarks" value="{{ old('remarks') }}"
               class="w-full rounded-xl border-slate-300 text-[14px] @error('remarks') ring-2 ring-rose-300 @enderror"
               placeholder="Patient calm, no distress..." />
        @error('remarks') <p class="mt-1 text-[12px] text-rose-600">{{ $message }}</p> @enderror
      </div>
      <div>
        <label for="pain_scale" class="block text-[12px] text-slate-500 mb-1">Pain Scale</label>
        @php $oldPain = old('pain_scale', 0); @endphp
        <div class="flex items-center gap-3">
          <input id="pain_scale" type="range" min="0" max="10" value="{{ $oldPain }}" name="pain_scale"
                 class="w-full" oninput="document.getElementById('painOut').textContent = this.value" />
          <output id="painOut" class="text-[13px] w-8 text-center">{{ $oldPain }}</output>
        </div>
        @error('pain_scale') <p class="mt-1 text-[12px] text-rose-600">{{ $message }}</p> @enderror
      </div>
    </div>
  </div>

  {{-- Actions --}}
  <div class="mt-5 flex items-center gap-2">
    <button type="submit" class="inline-flex items-center h-10 px-4 rounded-xl bg-emerald-600 text-white text-[13px] font-semibold">
      <i data-lucide="save" class="h-4 w-4 mr-2"></i> Save Vitals
    </button>
    <a href="{{ route('faculty.chartings.vital_signs.index') }}" class="inline-flex items-center h-10 px-3 rounded-xl bg-slate-100 text-[13px]">Cancel</a>
  </div>
</form>


    </section>
  </main>

  @include('partials.faculty-footer')
  <script src="https://unpkg.com/lucide@latest"></script>
<script>
  lucide.createIcons();
  const pr = document.getElementById('pain_scale'), po = document.getElementById('painOut');
  pr?.addEventListener('input', () => po.textContent = pr.value);
</script>
</body>

</html>