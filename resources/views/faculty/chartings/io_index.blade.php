<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8"/><meta name="viewport" content="width=device-width, initial-scale=1"/>
  <title>Intake & Output · NurSync — CI</title>
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
          <h1 class="text-2xl font-bold">Intake & Output</h1>
          <p class="text-[13px] text-slate-500 mt-0.5">Track fluids in/out with balances and types.</p>
        </div>
      </div>
      <a href="{{ route('faculty.chartings.io.create') }}" class="inline-flex items-center h-9 px-3 rounded-lg bg-emerald-600 text-white text-[12px] font-semibold">
        <i data-lucide="plus" class="h-3.5 w-3.5 mr-1"></i> New Entry
      </a>
    </div>

    @if (session('status'))
      <div class="mt-4 rounded-xl border border-emerald-200 bg-emerald-50 text-emerald-800 px-4 py-2 text-[13px]">{{ session('status') }}</div>
    @endif

    <div class="mt-6 bg-white rounded-2xl border border-slate-200 shadow-sm p-4 md:p-5 overflow-x-auto">
      <table class="min-w-full text-[13px]">
        <thead class="text-slate-500">
          <tr>
            <th class="text-left py-2 pr-3">Patient</th>
            <th class="text-left py-2 pr-3">Unit</th>
            <th class="text-left py-2 pr-3">Shift</th>
            <th class="text-left py-2 pr-3">Intake (ml)</th>
            <th class="text-left py-2 pr-3">Output (ml)</th>
            <th class="text-left py-2 pr-3">Balance (ml)</th>
            <th class="text-right py-2 pl-3">Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse($ios as $io)
            <tr class="border-t border-slate-100">
              <td class="py-3 pr-3">{{ $io->patient->full_name ?? '—' }}</td>
              <td class="py-3 pr-3">{{ $io->encounter->unit ?? '—' }}</td>
              <td class="py-3 pr-3">
                {{ $io->started_at?->format('Y-m-d H:i') ?? '—' }} – {{ $io->ended_at?->format('H:i') ?? '—' }}
              </td>
              <td class="py-3 pr-3">{{ $io->intake_total_ml }}</td>
              <td class="py-3 pr-3">{{ $io->output_total_ml }}</td>
              <td class="py-3 pr-3">
                @php $bal = $io->balance_ml; @endphp
                <span class="{{ $bal < 0 ? 'text-rose-600' : 'text-emerald-700' }}">{{ $bal }}</span>
              </td>
              <td class="py-3 pl-3">
                <div class="flex items-center justify-end gap-1">
                  <a href="{{ route('faculty.chartings.io.show',$io) }}"
                     class="inline-flex h-8 w-8 items-center justify-center rounded-lg text-slate-700 hover:bg-slate-100"
                     title="Open" aria-label="Open"><i data-lucide="eye" class="h-4 w-4"></i></a>
                  <a href="{{ route('faculty.chartings.io.edit',$io) }}"
                     class="inline-flex h-8 w-8 items-center justify-center rounded-lg text-emerald-700 hover:bg-emerald-50"
                     title="Edit" aria-label="Edit"><i data-lucide="pencil" class="h-4 w-4"></i></a>
                  <form action="{{ route('faculty.chartings.io.destroy',$io) }}" method="POST" class="inline js-delete-form">
                    @csrf @method('DELETE')
                    <button type="submit"
                      class="inline-flex h-8 w-8 items-center justify-center rounded-lg text-rose-700 hover:bg-rose-50"
                      title="Delete" aria-label="Delete"><i data-lucide="trash-2" class="h-4 w-4"></i></button>
                  </form>
                </div>
              </td>
            </tr>
          @empty
            <tr><td colspan="7" class="py-6 text-center text-slate-500">No I&O records yet.</td></tr>
          @endforelse
        </tbody>
      </table>
      <div class="mt-4">{{ $ios->links() }}</div>
    </div>
  </section>
</main>
@include('partials.faculty-footer')
<script src="https://unpkg.com/lucide@latest"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
  lucide.createIcons();
  document.addEventListener('click', (e) => {
    const form = e.target.closest('.js-delete-form');
    if (!form) return;
    e.preventDefault();
    Swal.fire({
      title: 'Delete record?',
      text: 'This action cannot be undone.',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Delete',
      cancelButtonText: 'Cancel',
      confirmButtonColor: '#dc2626'
    }).then((r)=>{ if(r.isConfirmed) form.submit(); });
  });
</script>
</body>
</html>
