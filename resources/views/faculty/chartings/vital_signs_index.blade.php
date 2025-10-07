<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
  <title>Vital Signs · NurSync — CI</title>
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
      <div class="flex items-center justify-between">
        <div class="flex items-center gap-3">
          <span
            class="inline-flex h-9 w-9 items-center justify-center rounded-xl bg-emerald-50 text-emerald-700 ring-1 ring-emerald-100">
            <i data-lucide="activity" class="h-5 w-5"></i>
          </span>
          <div>
            <h1 class="text-2xl font-bold">Vital Signs</h1>
            <p class="text-[13px] text-slate-500 mt-0.5">Entries visible only to your CI account.</p>
          </div>
        </div>
        <a href="{{ route('faculty.chartings.vital_signs.create') }}"
          class="inline-flex items-center h-9 px-3 rounded-lg bg-emerald-600 text-white text-[12px] font-semibold">
          <i data-lucide="plus" class="h-3.5 w-3.5 mr-1"></i> New Entry
        </a>
      </div>

      @if (session('status'))
        <div class="mt-4 rounded-xl border border-emerald-200 bg-emerald-50 text-emerald-800 px-4 py-2 text-[13px]">
          {{ session('status') }}</div>
      @endif

      <div class="mt-6 bg-white rounded-2xl border border-slate-200 shadow-sm p-4 md:p-5 overflow-x-auto">
        <table class="min-w-full text-[13px]">
          <thead class="text-slate-500">
            <tr>
              <th class="text-left py-2 pr-3">Patient</th>
              <th class="text-left py-2 pr-3">T</th>
              <th class="text-left py-2 pr-3">P</th>
              <th class="text-left py-2 pr-3">R</th>
              <th class="text-left py-2 pr-3">BP</th>
              <th class="text-left py-2 pr-3">SpO₂</th>
              <th class="text-left py-2 pr-3">Pain</th>
              <th class="text-right py-2 pl-3">Actions</th>
            </tr>
          </thead>
          <tbody>
            @forelse($vitals as $v)
              @php
                $name = $v->encounter->patient->full_name ?? '—';
                $t = isset($v->temp_c) ? rtrim(rtrim(number_format((float) $v->temp_c, 1), '0'), '.') : '—';
                $p = $v->pulse_bpm ?? '—';
                $r = $v->resp_rate ?? '—';
                $bp = ($v->bp_systolic && $v->bp_diastolic) ? ($v->bp_systolic . '/' . $v->bp_diastolic) : '—';
                $o2 = isset($v->spo2) ? ($v->spo2 . '%') : '—';
                $pain = $v->pain_scale ?? '—';
              @endphp
              <tr class="border-t border-slate-100">
                <td class="py-3 pr-3">{{ $name }}</td>
                <td class="py-3 pr-3">{{ $t }}</td>
                <td class="py-3 pr-3">{{ $p }}</td>
                <td class="py-3 pr-3">{{ $r }}</td>
                <td class="py-3 pr-3">{{ $bp }}</td>
                <td class="py-3 pr-3">{{ $o2 }}</td>
                <td class="py-3 pr-3">{{ $pain }}</td>
                <td class="py-3 pl-3">
                  <div class="flex items-center justify-end gap-1.5">
                    <a href="{{ route('faculty.chartings.vital_signs.show', $v) }}"
                      class="inline-flex h-8 w-8 items-center justify-center rounded-lg text-slate-600 hover:bg-slate-100"
                      title="Open" aria-label="Open">
                      <i data-lucide="eye" class="h-4 w-4"></i>
                      <span class="sr-only">Open</span>
                    </a>

                    <a href="{{ route('faculty.chartings.vital_signs.edit', $v) }}"
                      class="inline-flex h-8 w-8 items-center justify-center rounded-lg text-emerald-700 hover:bg-emerald-50"
                      title="Edit" aria-label="Edit">
                      <i data-lucide="pencil" class="h-4 w-4"></i>
                      <span class="sr-only">Edit</span>
                    </a>
<form action="{{ route('faculty.chartings.vital_signs.destroy', $v) }}"
      method="POST"
      class="inline js-delete-vital"
      data-name="{{ $v->encounter?->patient?->full_name ?? 'this entry' }}">
  @csrf @method('DELETE')
  <button type="submit"
          class="inline-flex h-8 w-8 items-center justify-center rounded-lg text-rose-700 hover:bg-rose-50"
          title="Delete" aria-label="Delete">
    <i data-lucide="trash-2" class="h-4 w-4"></i>
    <span class="sr-only">Delete</span>
  </button>
</form>
                  </div>

                </td>
              </tr>
            @empty
              <tr>
                <td colspan="8" class="py-6 text-center text-slate-500">No vitals yet.</td>
              </tr>
            @endforelse
          </tbody>
        </table>

        <div class="mt-4">{{ $vitals->links() }}</div>
      </div>
    </section>
  </main>
  @include('partials.faculty-footer')
  <script src="https://unpkg.com/lucide@latest"></script>
  <script> lucide.createIcons(); </script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
  document.addEventListener('submit', function (e) {
    const form = e.target.closest('form.js-delete-vital');
    if (!form) return;

    e.preventDefault(); // stop normal submit

    const label = form.dataset.name || 'this entry';
    Swal.fire({
      title: 'Delete entry?',
      html: `This will permanently remove <b>${label}</b> vitals.`,
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Yes, delete',
      cancelButtonText: 'Cancel',
      reverseButtons: true,
      confirmButtonColor: '#dc2626', // rose-600
      cancelButtonColor: '#64748b',  // slate-500
    }).then((res) => {
      if (res.isConfirmed) {
        // submit without re-triggering this listener
        HTMLFormElement.prototype.submit.call(form);
      }
    });
  });
</script>

</body>

</html>