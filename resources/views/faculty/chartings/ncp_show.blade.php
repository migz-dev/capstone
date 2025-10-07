<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" /><meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Nursing Care Plan — View · NurSync — CI</title>
  @vite(['resources/css/app.css','resources/js/app.js'])
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
  <style> body{font-family:'Poppins',ui-sans-serif,system-ui,sans-serif;} </style>
</head>
<body class="min-h-screen bg-slate-50">
<main class="min-h-screen flex">
  @include('partials.faculty-sidebar', ['active'=>'chartings'])

  <section class="flex-1 px-6 md:px-8 py-8 md:py-10">
    @php
      $at = optional($plan->noted_at)->format('Y-m-d H:i') ?? '—';
    @endphp

    <div class="flex items-center justify-between">
      <div class="flex items-center gap-3">
        <span class="inline-flex h-9 w-9 items-center justify-center rounded-xl bg-slate-100">
          <i data-lucide="target" class="h-5 w-5 text-slate-700"></i>
        </span>
        <div>
          <h1 class="text-2xl font-bold">Nursing Care Plan</h1>
          <p class="text-[13px] text-slate-500 mt-0.5">{{ $plan->patient_name ?: '—' }} · Noted {{ $at }}</p>
        </div>
      </div>

      <div class="flex items-center gap-2">
        <a href="{{ route('faculty.chartings.ncp.edit', $plan) }}"
           class="inline-flex items-center h-9 px-3 rounded-lg bg-emerald-600 text-white text-[12px] font-semibold">
          <i data-lucide="pencil" class="h-4 w-4 mr-1"></i> Edit
        </a>

        <form action="{{ route('faculty.chartings.ncp.destroy', $plan) }}" method="POST" class="inline js-del">
          @csrf @method('DELETE')
          <button class="inline-flex items-center h-9 px-3 rounded-lg bg-rose-50 text-rose-700 text-[12px] font-semibold">
            <i data-lucide="trash-2" class="h-4 w-4 mr-1"></i> Delete
          </button>
        </form>

        <a href="{{ route('faculty.chartings.ncp.index') }}"
           class="text-[13px] px-3 h-9 inline-flex items-center rounded-lg bg-slate-100 hover:bg-slate-200">
          <i data-lucide="arrow-left" class="h-4 w-4 mr-1"></i> Back
        </a>
      </div>
    </div>

    @if (session('status'))
      <div class="mt-4 rounded-xl border border-emerald-200 bg-emerald-50 text-emerald-800 px-4 py-2 text-[13px]">
        {{ session('status') }}
      </div>
    @endif

    <div class="mt-6 bg-white rounded-2xl border border-slate-200 shadow-sm p-5 space-y-6">
      <div class="grid md:grid-cols-3 gap-4">
        <div>
          <div class="text-[12px] text-slate-500">Patient</div>
          <div class="text-[15px] font-semibold">{{ $plan->patient_name ?: '—' }}</div>
        </div>
        <div>
          <div class="text-[12px] text-slate-500">Noted at</div>
          <div class="text-[15px] font-semibold">{{ $at }}</div>
        </div>
      </div>

      <div class="grid md:grid-cols-3 gap-4">
        <div>
          <div class="text-[12px] text-slate-500">NANDA Dx (Primary)</div>
          <div class="text-[14px] whitespace-pre-line">{{ $plan->dx_primary ?: '—' }}</div>
        </div>
        <div>
          <div class="text-[12px] text-slate-500">Related to</div>
          <div class="text-[14px] whitespace-pre-line">{{ $plan->dx_related_to ?: '—' }}</div>
        </div>
        <div>
          <div class="text-[12px] text-slate-500">As evidenced by</div>
          <div class="text-[14px] whitespace-pre-line">{{ $plan->dx_as_evidenced_by ?: '—' }}</div>
        </div>
      </div>

      <div class="grid md:grid-cols-2 gap-4">
        <div>
          <div class="text-[12px] text-slate-500">Short-term Goal</div>
          <div class="text-[14px] whitespace-pre-line">{{ $plan->goal_short ?: '—' }}</div>
        </div>
        <div>
          <div class="text-[12px] text-slate-500">Long-term Goal</div>
          <div class="text-[14px] whitespace-pre-line">{{ $plan->goal_long ?: '—' }}</div>
        </div>
      </div>

      <div>
        <div class="text-[12px] text-slate-500">Nursing Interventions</div>
        <div class="text-[14px] whitespace-pre-line">{{ $plan->interventions ?: '—' }}</div>
      </div>

      <div>
        <div class="text-[12px] text-slate-500">Evaluation</div>
        <div class="text-[14px] whitespace-pre-line">{{ $plan->evaluation ?: '—' }}</div>
      </div>

      @if (filled($plan->remarks))
        <div>
          <div class="text-[12px] text-slate-500">Remarks</div>
          <div class="text-[14px] whitespace-pre-line">{{ $plan->remarks }}</div>
        </div>
      @endif
    </div>
  </section>
</main>

@include('partials.faculty-footer')
<script src="https://unpkg.com/lucide@latest"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
  lucide.createIcons();
  document.querySelector('.js-del')?.addEventListener('submit', e => {
    e.preventDefault();
    const f = e.currentTarget;
    Swal.fire({title:'Delete this plan?', text:'This cannot be undone.', icon:'warning',
      showCancelButton:true, confirmButtonColor:'#dc2626'}).then(r => { if (r.isConfirmed) f.submit(); });
  });
</script>
</body>
</html>
