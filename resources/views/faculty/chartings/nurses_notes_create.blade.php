{{-- resources/views/faculty/chartings/nurses_notes_create.blade.php --}}
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
  <title>New Nurse’s Note · NurSync — CI</title>
  @vite(['resources/css/app.css','resources/js/app.js'])
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
  <style> body { font-family:'Poppins',ui-sans-serif,system-ui,sans-serif; } </style>
</head>
<body class="min-h-screen bg-slate-50">
<main class="min-h-screen flex">
  @include('partials.faculty-sidebar', ['active' => 'chartings'])

  <section class="flex-1 px-6 md:px-8 py-8 md:py-10">
    {{-- Header --}}
    <div class="flex items-center justify-between">
      <div class="flex items-center gap-3">
        <span class="inline-flex h-9 w-9 items-center justify-center rounded-xl bg-emerald-50 text-emerald-700 ring-1 ring-emerald-100">
          <i data-lucide="notebook-pen" class="h-5 w-5"></i>
        </span>
        <div>
          <h1 class="text-2xl font-bold">New Nurse’s Note</h1>
          <p class="text-[13px] text-slate-500 mt-0.5">Document observations and actions using Narrative, DAR, or SOAPIE.</p>
        </div>
      </div>
      <a href="{{ route('faculty.chartings.index') }}"
         class="text-[13px] px-3 h-9 inline-flex items-center rounded-lg bg-slate-100 hover:bg-slate-200">
        <i data-lucide="arrow-left" class="h-4 w-4 mr-1"></i> Back to Chartings
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
    <form action="{{ route('faculty.chartings.nurses_notes.store') }}" method="POST" enctype="multipart/form-data" class="mt-6" novalidate>
      @csrf

      <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5 space-y-5">
        {{-- Context row --}}
        <div class="grid md:grid-cols-3 gap-4">
          {{-- Patient name --}}
          <div>
            <label for="patient_name" class="block text-[12px] text-slate-500 mb-1">
              Patient Name <span class="text-rose-600">*</span>
            </label>
            <input id="patient_name" type="text" name="patient_name" required
                   value="{{ old('patient_name') }}"
                   placeholder="e.g., Santos, Maria L."
                   class="w-full rounded-xl border-slate-300 text-[14px] @error('patient_name') ring-2 ring-rose-300 @enderror" />
            @error('patient_name') <p class="mt-1 text-[12px] text-rose-600">{{ $message }}</p> @enderror
          </div>

          {{-- Unit / Ward --}}
          <div>
            <label for="unit" class="block text-[12px] text-slate-500 mb-1">
              Unit / Ward <span class="text-rose-600">*</span>
            </label>
            <input id="unit" type="text" name="unit" required
                   value="{{ old('unit') }}"
                   placeholder="e.g., MS Ward, OB, Pedia"
                   class="w-full rounded-xl border-slate-300 text-[14px] @error('unit') ring-2 ring-rose-300 @enderror" />
            @error('unit') <p class="mt-1 text-[12px] text-rose-600">{{ $message }}</p> @enderror
          </div>

          {{-- Date & Time --}}
          <div>
            <label for="noted_at" class="block text-[12px] text-slate-500 mb-1">
              Date & Time <span class="text-rose-600">*</span>
            </label>
            <input id="noted_at" type="datetime-local" name="noted_at" required
                   value="{{ old('noted_at') }}"
                   class="w-full rounded-xl border-slate-300 text-[14px] @error('noted_at') ring-2 ring-rose-300 @enderror" />
            @error('noted_at') <p class="mt-1 text-[12px] text-rose-600">{{ $message }}</p> @enderror
          </div>
        </div>

        {{-- Format --}}
        <div class="grid md:grid-cols-3 gap-4">
          <div>
            <label for="format" class="block text-[12px] text-slate-500 mb-1">Format <span class="text-rose-600">*</span></label>
            <select id="format" name="format" required
                    class="w-full rounded-xl border-slate-300 text-[14px] @error('format') ring-2 ring-rose-300 @enderror">
              @php $fmt = old('format','NARRATIVE'); @endphp
              <option value="NARRATIVE" @selected($fmt==='NARRATIVE')>Narrative</option>
              <option value="DAR"       @selected($fmt==='DAR')>DAR (Data–Action–Response)</option>
              <option value="SOAPIE"    @selected($fmt==='SOAPIE')>SOAPIE</option>
            </select>
            @error('format') <p class="mt-1 text-[12px] text-rose-600">{{ $message }}</p> @enderror
          </div>
        </div>

        {{-- Narrative / Content --}}
        <div>
          <label for="body" class="block text-[12px] text-slate-500 mb-1">
            Narrative / Content <span class="text-rose-600">*</span>
          </label>
          <textarea id="body" name="body" rows="8" required
                    class="w-full rounded-xl border-slate-300 text-[14px] @error('body') ring-2 ring-rose-300 @enderror"
                    placeholder="Write the note here...">{{ old('body') }}</textarea>
          @error('body') <p class="mt-1 text-[12px] text-rose-600">{{ $message }}</p> @enderror
          <p class="text-[12px] text-slate-500 mt-1">
            Tip: For <strong>DAR</strong> and <strong>SOAPIE</strong>, add clear headings in your content (e.g., <em>Data / Action / Response</em> or <em>S/O/A/P/I/E</em>).
          </p>
        </div>

        {{-- Optional vitals snapshot --}}
        <details class="rounded-xl border border-slate-200 p-4">
          <summary class="cursor-pointer text-[13px] font-semibold">Attach Vitals Snapshot (optional)</summary>
          <div class="grid md:grid-cols-6 gap-3 mt-3">
            <input name="snap_temp_c"     type="number" step="0.1" min="30" max="45"
                   value="{{ old('snap_temp_c') }}"     placeholder="T °C"
                   class="rounded-xl border-slate-300 text-[14px]" />
            <input name="snap_pulse_bpm"  type="number" min="20" max="220"
                   value="{{ old('snap_pulse_bpm') }}"  placeholder="P bpm"
                   class="rounded-xl border-slate-300 text-[14px]" />
            <input name="snap_resp_rate"  type="number" min="5" max="80"
                   value="{{ old('snap_resp_rate') }}"  placeholder="R cpm"
                   class="rounded-xl border-slate-300 text-[14px]" />
            <input name="snap_bp_sys"     type="number" min="50" max="260"
                   value="{{ old('snap_bp_sys') }}"     placeholder="BP Sys"
                   class="rounded-xl border-slate-300 text-[14px]" />
            <input name="snap_bp_dia"     type="number" min="20" max="180"
                   value="{{ old('snap_bp_dia') }}"     placeholder="BP Dia"
                   class="rounded-xl border-slate-300 text-[14px]" />
            <input name="snap_spo2"       type="number" min="0" max="100"
                   value="{{ old('snap_spo2') }}"       placeholder="SpO₂ %"
                   class="rounded-xl border-slate-300 text-[14px]" />
          </div>
        </details>

        {{-- Attachments --}}
        <div>
          <label for="attachments" class="block text-[12px] text-slate-500 mb-1">Attachments</label>
          <input id="attachments" type="file" name="attachments[]" multiple class="block w-full text-[13px]" />
          @error('attachments.*') <p class="mt-1 text-[12px] text-rose-600">{{ $message }}</p> @enderror
        </div>
      </div>

      {{-- Actions --}}
<div class="mt-5 flex items-center gap-2">
  <button type="submit"
          class="inline-flex items-center h-10 px-4 rounded-xl bg-emerald-600 text-white text-[13px] font-semibold">
    <i data-lucide="save" class="h-4 w-4 mr-2"></i> Save Note
  </button>

  <a href="{{ route('faculty.chartings.nurses_notes.index') }}"
     class="inline-flex items-center h-10 px-3 rounded-xl bg-slate-100 text-[13px]">
    Cancel
  </a>
</div>

    </form>
  </section>
</main>

@include('partials.faculty-footer')
<script src="https://unpkg.com/lucide@latest"></script>
<script> lucide.createIcons(); </script>
</body>
</html>
