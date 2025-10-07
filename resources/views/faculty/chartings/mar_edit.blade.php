<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" /><meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Edit MAR · NurSync — CI</title>
  @vite(['resources/css/app.css','resources/js/app.js'])
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
  <style>body{font-family:'Poppins',ui-sans-serif,system-ui,sans-serif}</style>
</head>
<body class="min-h-screen bg-slate-50">
<main class="min-h-screen flex">
  @include('partials.faculty-sidebar', ['active'=>'chartings'])
  <section class="flex-1 px-6 md:px-8 py-8 md:py-10">
    @php $pname = $mar->patient->full_name ?? '—'; @endphp
    <div class="flex items-center justify-between">
      <div class="flex items-center gap-3">
        <span class="inline-flex h-9 w-9 items-center justify-center rounded-xl bg-emerald-50 text-emerald-700 ring-1 ring-emerald-100">
          <i data-lucide="pill" class="h-5 w-5"></i>
        </span>
        <div>
          <h1 class="text-2xl font-bold">Edit MAR — {{ $pname }}</h1>
          <p class="text-[13px] text-slate-500 mt-0.5">Enc#{{ $mar->encounter_id }}</p>
        </div>
      </div>
      <a href="{{ route('faculty.chartings.mar.index') }}" class="text-[13px] px-3 h-9 inline-flex items-center rounded-lg bg-slate-100 hover:bg-slate-200">
        <i data-lucide="arrow-left" class="h-4 w-4 mr-1"></i> Back to MAR
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

    <form action="{{ route('faculty.chartings.mar.update', $mar) }}" method="POST" class="mt-6" novalidate>
      @csrf @method('PUT')
      <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5 space-y-5">
        <div class="grid md:grid-cols-3 gap-4">
          <div>
            <label class="block text-[12px] text-slate-500 mb-1">Medication <span class="text-rose-600">*</span></label>
            <input type="text" name="medication" value="{{ old('medication', $mar->medication) }}"
                   class="w-full rounded-xl border-slate-300 text-[14px] @error('medication') ring-2 ring-rose-300 @enderror" required />
          </div>
          <div>
            <label class="block text-[12px] text-slate-500 mb-1">Dose Amount</label>
            <input type="number" step="0.01" min="0" name="dose_amount" value="{{ old('dose_amount', $mar->dose_amount) }}"
                   class="w-full rounded-xl border-slate-300 text-[14px]" />
          </div>
          <div>
            <label class="block text-[12px] text-slate-500 mb-1">Dose Unit</label>
            <input type="text" name="dose_unit" value="{{ old('dose_unit', $mar->dose_unit) }}"
                   class="w-full rounded-xl border-slate-300 text-[14px]" />
          </div>
        </div>

        <div class="grid md:grid-cols-3 gap-4">
          <div>
            <label class="block text-[12px] text-slate-500 mb-1">Route</label>
            <input type="text" name="route" value="{{ old('route', $mar->route) }}"
                   class="w-full rounded-xl border-slate-300 text-[14px]" />
          </div>
          <div>
            <label class="block text-[12px] text-slate-500 mb-1">Frequency</label>
            <input type="text" name="frequency" value="{{ old('frequency', $mar->frequency) }}"
                   class="w-full rounded-xl border-slate-300 text-[14px]" />
          </div>
          <div class="flex items-end gap-2">
            <label class="inline-flex items-center gap-2 text-[14px]">
              <input type="checkbox" name="is_prn" value="1" @checked(old('is_prn', $mar->is_prn)) />
              <span>PRN</span>
            </label>
          </div>
        </div>

        <div class="grid md:grid-cols-3 gap-4">
          <div>
            <label class="block text-[12px] text-slate-500 mb-1">PRN Reason</label>
            <input type="text" name="prn_reason" value="{{ old('prn_reason', $mar->prn_reason) }}"
                   class="w-full rounded-xl border-slate-300 text-[14px]" />
          </div>
          <div>
            <label class="block text-[12px] text-slate-500 mb-1">Administered At</label>
            <input type="datetime-local" name="administered_at"
                   value="{{ old('administered_at', optional($mar->administered_at)->format('Y-m-d\TH:i')) }}"
                   class="w-full rounded-xl border-slate-300 text-[14px]" />
          </div>
          <div>
            <label class="block text-[12px] text-slate-500 mb-1">Omitted Reason</label>
            <input type="text" name="omitted_reason" value="{{ old('omitted_reason', $mar->omitted_reason) }}"
                   class="w-full rounded-xl border-slate-300 text-[14px]" />
          </div>
        </div>

        <div>
          <label class="block text-[12px] text-slate-500 mb-1">Effects (optional)</label>
          <textarea name="effects" rows="3" class="w-full rounded-xl border-slate-300 text-[14px]">{{ old('effects', $mar->effects) }}</textarea>
        </div>
        <div>
          <label class="block text-[12px] text-slate-500 mb-1">Remarks (optional)</label>
          <input type="text" name="remarks" value="{{ old('remarks', $mar->remarks) }}"
                 class="w-full rounded-xl border-slate-300 text-[14px]" />
        </div>
      </div>

      <div class="mt-5 flex items-center gap-2">
        <button type="submit" class="inline-flex items-center h-10 px-4 rounded-xl bg-emerald-600 text-white text-[13px] font-semibold">
          <i data-lucide="save" class="h-4 w-4 mr-2"></i> Update MAR
        </button>
        <a href="{{ route('faculty.chartings.mar.show', $mar) }}" class="inline-flex items-center h-10 px-3 rounded-xl bg-slate-100 text-[13px]">Cancel</a>
      </div>
    </form>
  </section>
</main>
@include('partials.faculty-footer')
<script src="https://unpkg.com/lucide@latest"></script>
<script>lucide.createIcons();</script>
</body>
</html>
