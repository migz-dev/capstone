<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" /><meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
  <title>Vital Signs — View · NurSync — CI</title>
  @vite(['resources/css/app.css','resources/js/app.js'])
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
  <style> body{font-family:'Poppins',ui-sans-serif,system-ui,sans-serif;} </style>
</head>
<body class="min-h-screen bg-slate-50">
<main class="min-h-screen flex">
  @include('partials.faculty-sidebar', ['active'=>'chartings'])

  <section class="flex-1 px-6 md:px-8 py-8 md:py-10">
    @php
      /** @var \App\Models\Vital $vital */
      $patient   = $vital->encounter->patient ?? null;
      $pname     = $patient->full_name ?? '—';
      $unit      = $vital->encounter->unit ?? '—';
      $encId     = $vital->encounter_id;
      $taken     = optional($vital->taken_at)->format('Y-m-d H:i') ?? '—';

      $fmt = fn($n, $dec=1) => isset($n) && $n !== '' ? rtrim(rtrim(number_format((float)$n,$dec), '0'), '.') : '—';
      $bp  = ($vital->bp_systolic && $vital->bp_diastolic) ? "{$vital->bp_systolic}/{$vital->bp_diastolic}" : '—';
      $spo2 = isset($vital->spo2) ? $vital->spo2.'%' : '—';
      $pain = isset($vital->pain_scale) ? "{$vital->pain_scale}/10" : '—';
    @endphp

    {{-- Header --}}
    <div class="flex items-center justify-between">
      <div class="flex items-center gap-3">
        <span class="inline-flex h-9 w-9 items-center justify-center rounded-xl bg-emerald-50 text-emerald-700 ring-1 ring-emerald-100">
          <i data-lucide="activity" class="h-5 w-5"></i>
        </span>
        <div>
          <h1 class="text-2xl font-bold">
            {{ $pname }}
          </h1>
          <p class="text-[13px] text-slate-500 mt-0.5">
            Taken {{ $taken }} · Enc#{{ $encId }} · {{ $unit }}
          </p>
        </div>
      </div>

      <div class="flex items-center gap-2">
        <a href="{{ route('faculty.chartings.vital_signs.index') }}"
           class="inline-flex h-9 w-9 items-center justify-center rounded-lg bg-slate-100 hover:bg-slate-200"
           title="Back" aria-label="Back">
          <i data-lucide="arrow-left" class="h-4 w-4"></i>
        </a>

        <a href="{{ route('faculty.chartings.vital_signs.edit', $vital) }}"
           class="inline-flex h-9 w-9 items-center justify-center rounded-lg bg-emerald-50 text-emerald-700 hover:bg-emerald-100"
           title="Edit" aria-label="Edit">
          <i data-lucide="pencil" class="h-4 w-4"></i>
        </a>

        <form action="{{ route('faculty.chartings.vital_signs.destroy', $vital) }}"
              method="POST"
              class="inline js-delete-vital"
              data-name="{{ $pname }} ({{ $taken }})">
          @csrf @method('DELETE')
          <button type="submit"
                  class="inline-flex h-9 w-9 items-center justify-center rounded-lg text-rose-700 hover:bg-rose-50"
                  title="Delete" aria-label="Delete">
            <i data-lucide="trash-2" class="h-4 w-4"></i>
          </button>
        </form>
      </div>
    </div>

    {{-- Patient & encounter summary --}}
    <div class="mt-6 grid gap-4 md:grid-cols-3">
      <div class="rounded-2xl border border-slate-200 bg-white p-5">
        <div class="text-[12px] text-slate-500">Patient</div>
        <div class="mt-1 text-[15px] font-semibold">{{ $pname }}</div>
      </div>
      <div class="rounded-2xl border border-slate-200 bg-white p-5">
        <div class="text-[12px] text-slate-500">Encounter</div>
        <div class="mt-1 text-[15px] font-semibold">#{{ $encId }} · {{ $unit }}</div>
      </div>
      <div class="rounded-2xl border border-slate-200 bg-white p-5">
        <div class="text-[12px] text-slate-500">Taken at</div>
        <div class="mt-1 text-[15px] font-semibold">{{ $taken }}</div>
      </div>
    </div>

    {{-- Vitals grid --}}
    <div class="mt-4 bg-white rounded-2xl border border-slate-200 shadow-sm p-5 grid md:grid-cols-3 gap-4">
      <div>
        <div class="text-[12px] text-slate-500">Temperature</div>
        <div class="text-[15px] font-semibold">{{ $fmt($vital->temp_c) }} {{ $vital->temp_c !== null ? '°C' : '' }}</div>
      </div>
      <div>
        <div class="text-[12px] text-slate-500">Pulse</div>
        <div class="text-[15px] font-semibold">{{ $vital->pulse_bpm ?? '—' }}{{ $vital->pulse_bpm !== null ? ' bpm' : '' }}</div>
      </div>
      <div>
        <div class="text-[12px] text-slate-500">Respirations</div>
        <div class="text-[15px] font-semibold">{{ $vital->resp_rate ?? '—' }}{{ $vital->resp_rate !== null ? ' cpm' : '' }}</div>
      </div>
      <div>
        <div class="text-[12px] text-slate-500">Blood Pressure</div>
        <div class="text-[15px] font-semibold">{{ $bp }}{{ $bp !== '—' ? ' mmHg' : '' }}</div>
      </div>
      <div>
        <div class="text-[12px] text-slate-500">SpO₂</div>
        <div class="text-[15px] font-semibold">{{ $spo2 }}</div>
      </div>
      <div>
        <div class="text-[12px] text-slate-500">Pain Scale</div>
        <div class="text-[15px] font-semibold">{{ $pain }}</div>
      </div>

      <div class="md:col-span-3">
        <div class="text-[12px] text-slate-500">Remarks</div>
        <div class="text-[14px] mt-1">{{ $vital->remarks ?: '—' }}</div>
      </div>
    </div>

    {{-- Audit (optional, shows if available) --}}
    <div class="mt-4 grid gap-4 md:grid-cols-2">
      <div class="rounded-2xl border border-slate-200 bg-white p-5">
        <div class="text-[12px] text-slate-500">Created</div>
        <div class="text-[14px] mt-1">{{ optional($vital->created_at)->format('Y-m-d H:i') ?? '—' }}</div>
      </div>
      <div class="rounded-2xl border border-slate-200 bg-white p-5">
        <div class="text-[12px] text-slate-500">Last Updated</div>
        <div class="text-[14px] mt-1">{{ optional($vital->updated_at)->format('Y-m-d H:i') ?? '—' }}</div>
      </div>
    </div>
  </section>
</main>

@include('partials.faculty-footer')

<script src="https://unpkg.com/lucide@latest"></script>
<script> lucide.createIcons(); </script>

{{-- SweetAlert2 delete confirm --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
  document.addEventListener('submit', function (e) {
    const form = e.target.closest('form.js-delete-vital');
    if (!form) return;

    e.preventDefault();

    const label = form.dataset.name || 'this entry';
    Swal.fire({
      title: 'Delete entry?',
      html: `This will permanently remove <b>${label}</b> vitals.`,
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Yes, delete',
      cancelButtonText: 'Cancel',
      reverseButtons: true,
      confirmButtonColor: '#dc2626',
      cancelButtonColor: '#64748b',
    }).then((res) => {
      if (res.isConfirmed) {
        HTMLFormElement.prototype.submit.call(form);
      }
    });
  });
</script>
</body>
</html>
