{{-- resources/views/faculty/chartings/nn_show.blade.php --}}
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" /><meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Nurse’s Note — View · NurSync — CI</title>
  @vite(['resources/css/app.css','resources/js/app.js'])
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
  <style> body{font-family:'Poppins',ui-sans-serif,system-ui,sans-serif;} </style>
</head>
<body class="min-h-screen bg-slate-50">
<main class="min-h-screen flex">
  @include('partials.faculty-sidebar', ['active'=>'chartings'])

  <section class="flex-1 px-6 md:px-8 py-8 md:py-10">
    @php
      /** @var \App\Models\NursesNote $note */
      $fmtRaw  = $note->format ?? 'narrative';
      $fmt     = strtoupper($fmtRaw);
      $at      = optional($note->noted_at)->format('Y-m-d H:i') ?? '—';

      // Prefer free-typed patient name; fallback to encounter’s patient full name
      $enc     = $note->encounter ?? null;
      $patient = $note->patient_name
                  ?? optional(optional($enc)->patient)->full_name
                  ?? '—';
    @endphp

    {{-- Header --}}
    <div class="flex items-center justify-between">
      <div class="flex items-center gap-3">
        <span class="inline-flex h-9 w-9 items-center justify-center rounded-xl bg-emerald-50 text-emerald-700 ring-1 ring-emerald-100">
          <i data-lucide="notebook-pen" class="h-5 w-5"></i>
        </span>
        <div>
          <h1 class="text-2xl font-bold">Nurse’s Note — {{ $fmt }}</h1>
          <p class="text-[13px] text-slate-500 mt-0.5">
            {{ $patient }} · Noted {{ $at }}
          </p>
        </div>
      </div>

      <div class="flex items-center gap-2">
        <a href="{{ route('faculty.chartings.nurses_notes.edit', $note) }}"
           class="inline-flex items-center h-9 px-3 rounded-lg bg-emerald-600 text-white text-[12px] font-semibold">
          <i data-lucide="pencil" class="h-4 w-4 mr-1"></i> Edit
        </a>

        <form action="{{ route('faculty.chartings.nurses_notes.destroy', $note) }}"
              method="POST" class="inline js-delete-form">
          @csrf @method('DELETE')
          <button type="submit"
                  class="inline-flex items-center h-9 px-3 rounded-lg bg-rose-50 text-rose-700 text-[12px] font-semibold">
            <i data-lucide="trash-2" class="h-4 w-4 mr-1"></i> Delete
          </button>
        </form>

        <a href="{{ route('faculty.chartings.nurses_notes.index') }}"
           class="text-[13px] px-3 h-9 inline-flex items-center rounded-lg bg-slate-100 hover:bg-slate-200">
          <i data-lucide="arrow-left" class="h-4 w-4 mr-1"></i> Back
        </a>
      </div>
    </div>

    {{-- Flash --}}
    @if (session('status'))
      <div class="mt-4 rounded-xl border border-emerald-200 bg-emerald-50 text-emerald-800 px-4 py-2 text-[13px]">
        {{ session('status') }}
      </div>
    @endif

    {{-- Body --}}
    <div class="mt-6 bg-white rounded-2xl border border-slate-200 shadow-sm p-5 space-y-5">
      <div class="grid md:grid-cols-2 gap-4">
        <div>
          <div class="text-[12px] text-slate-500">Noted at</div>
          <div class="text-[15px] font-semibold">{{ $at }}</div>
        </div>
        <div>
          <div class="text-[12px] text-slate-500">Format</div>
          <div class="text-[15px] font-semibold">{{ $fmt }}</div>
        </div>
      </div>

      {{-- Narrative --}}
      @if ($fmt === 'NARRATIVE')
        <div>
          <div class="text-[12px] text-slate-500">Narrative</div>
          <div class="text-[14px] leading-6 whitespace-pre-line">{{ $note->narrative ?: '—' }}</div>
        </div>
      @endif

      {{-- DAR --}}
      @if ($fmt === 'DAR')
        <div class="grid md:grid-cols-3 gap-4">
          <div>
            <div class="text-[12px] text-slate-500">D – Data</div>
            <div class="text-[14px] whitespace-pre-line">{{ $note->dar_data ?: '—' }}</div>
          </div>
          <div>
            <div class="text-[12px] text-slate-500">A – Action</div>
            <div class="text-[14px] whitespace-pre-line">{{ $note->dar_action ?: '—' }}</div>
          </div>
          <div>
            <div class="text-[12px] text-slate-500">R – Response</div>
            <div class="text-[14px] whitespace-pre-line">{{ $note->dar_response ?: '—' }}</div>
          </div>
        </div>
      @endif

      {{-- SOAPIE --}}
      @if ($fmt === 'SOAPIE')
        <div class="grid md:grid-cols-3 gap-4">
          <div><div class="text-[12px] text-slate-500">S – Subjective</div><div class="text-[14px] whitespace-pre-line">{{ $note->soapie_s ?: '—' }}</div></div>
          <div><div class="text-[12px] text-slate-500">O – Objective</div><div class="text-[14px] whitespace-pre-line">{{ $note->soapie_o ?: '—' }}</div></div>
          <div><div class="text-[12px] text-slate-500">A – Assessment</div><div class="text-[14px] whitespace-pre-line">{{ $note->soapie_a ?: '—' }}</div></div>
          <div><div class="text-[12px] text-slate-500">P – Plan</div><div class="text-[14px] whitespace-pre-line">{{ $note->soapie_p ?: '—' }}</div></div>
          <div><div class="text-[12px] text-slate-500">I – Intervention</div><div class="text-[14px] whitespace-pre-line">{{ $note->soapie_i ?: '—' }}</div></div>
          <div><div class="text-[12px] text-slate-500">E – Evaluation</div><div class="text-[14px] whitespace-pre-line">{{ $note->soapie_e ?: '—' }}</div></div>
        </div>
      @endif

      {{-- PIE --}}
      @if ($fmt === 'PIE')
        <div class="grid md:grid-cols-3 gap-4">
          <div><div class="text-[12px] text-slate-500">P – Problem</div><div class="text-[14px] whitespace-pre-line">{{ $note->pie_p ?: '—' }}</div></div>
          <div><div class="text-[12px] text-slate-500">I – Intervention</div><div class="text-[14px] whitespace-pre-line">{{ $note->pie_i ?: '—' }}</div></div>
          <div><div class="text-[12px] text-slate-500">E – Evaluation</div><div class="text-[14px] whitespace-pre-line">{{ $note->pie_e ?: '—' }}</div></div>
        </div>
      @endif

      {{-- Remarks (optional) --}}
      @if (filled($note->remarks))
        <div>
          <div class="text-[12px] text-slate-500">Remarks</div>
          <div class="text-[14px] whitespace-pre-line">{{ $note->remarks }}</div>
        </div>
      @endif
    </div>
  </section>
</main>

@include('partials.faculty-footer')

{{-- Icons + SweetAlert2 --}}
<script src="https://unpkg.com/lucide@latest"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
  lucide.createIcons();

  // SweetAlert confirm for delete
  document.querySelector('.js-delete-form')?.addEventListener('submit', function(e){
    e.preventDefault();
    const form = this;
    Swal.fire({
      title: 'Delete this note?',
      text: 'This action cannot be undone.',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Yes, delete',
      cancelButtonText: 'Cancel',
      confirmButtonColor: '#dc2626',
    }).then((res) => {
      if (res.isConfirmed) form.submit();
    });
  });
</script>
</body>
</html>
