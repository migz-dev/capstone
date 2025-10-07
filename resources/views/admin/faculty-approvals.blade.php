{{-- resources/views/admin/faculty-approvals.blade.php --}}
<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
  <title>Admin • Faculty Approvals · NurSync</title>

  @vite(['resources/css/app.css', 'resources/js/app.js'])
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
  <style>
    body { font-family: 'Poppins', ui-sans-serif, system-ui, sans-serif; }
  </style>
</head>

<body class="min-h-screen bg-slate-50">

  <main class="min-h-screen flex">
    {{-- Sidebar --}}
    @include('partials.admin-sidebar', ['active' => 'faculty-approvals'])

    {{-- Main --}}
    <section class="flex-1 min-w-0">
      {{-- Header --}}
      <header class="sticky top-0 z-30 bg-white/80 backdrop-blur border-b border-slate-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 h-14 flex items-center justify-between">
          <div class="flex items-center gap-3">
            <div class="inline-flex h-8 w-8 items-center justify-center rounded-xl bg-slate-900 text-white shadow-sm">
              <i data-lucide="shield-check" class="h-4 w-4"></i>
            </div>
            <div>
              <h1 class="text-[15px] sm:text-[16px] font-semibold leading-tight">Faculty Approvals</h1>
              <p class="text-[12px] text-slate-500 -mt-0.5">Review and approve pending faculty registrations.</p>
            </div>
          </div>
        </div>
      </header>

      {{-- Content --}}
      <div class="max-w-7xl mx-auto px-4 sm:px-6 py-6 space-y-6">

        {{-- Flash --}}
        @if (session('ok'))
          <div class="rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">
            {{ session('ok') }}
          </div>
        @endif

        {{-- Filters / tools --}}
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-4">
          <form method="get" class="flex flex-col sm:flex-row gap-3 sm:items-center sm:justify-between">
            <div class="flex items-center gap-3 w-full sm:w-auto">
              <div class="relative flex-1 sm:w-72">
                <i data-lucide="search" class="absolute left-3 top-2.5 h-4 w-4 text-slate-400"></i>
                <input
                  type="text"
                  name="q"
                  value="{{ $q ?? '' }}"
                  placeholder="Search name, email..."
                  class="w-full rounded-xl border-slate-200 pl-9 pr-3 py-2.5 text-sm focus:ring-2 focus:ring-slate-300">
              </div>

              {{-- Status filter --}}
              @php $curStatus = $status ?? 'pending'; @endphp
              <select name="status" class="rounded-xl border-slate-200 text-sm py-2.5 px-3 focus:ring-2 focus:ring-slate-300">
                <option value="" {{ $curStatus==='' ? 'selected' : '' }}>All status</option>
                <option value="pending"  {{ $curStatus==='pending'  ? 'selected' : '' }}>Pending</option>
                <option value="approved" {{ $curStatus==='approved' ? 'selected' : '' }}>Approved</option>
                <option value="rejected" {{ $curStatus==='rejected' ? 'selected' : '' }}>Rejected</option>
              </select>
            </div>

            <div class="flex items-center gap-2">
              <button class="inline-flex items-center gap-2 rounded-xl bg-white border border-slate-200 px-3 py-2 text-[13px] hover:bg-slate-50">
                <i data-lucide="filter" class="h-4 w-4"></i>
                Apply
              </button>
              <button type="button"
                class="inline-flex items-center gap-2 rounded-xl bg-white border border-slate-200 px-3 py-2 text-[13px] hover:bg-slate-50">
                <i data-lucide="settings-2" class="h-4 w-4"></i>
                Columns
              </button>
            </div>
          </form>
        </div>

        {{-- Table (Department column removed) --}}
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
          <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
              <thead class="bg-slate-50 border-b border-slate-200">
                <tr class="text-left text-slate-600">
                  <th class="px-4 py-3">Name</th>
                  <th class="px-4 py-3">Email</th>
                  <th class="px-4 py-3">Submitted</th>
                  <th class="px-4 py-3 text-right">Actions</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-slate-200">

                @forelse ($rows as $u)
                  @php
                    $isApproved = ($u->status ?? '') === 'approved';
                    $isRejected = ($u->status ?? '') === 'rejected';

                    $p = $u->id_file_path ?: '';
                    $idUrl = $p
                      ? (\Illuminate\Support\Str::startsWith($p, 'storage/')
                          ? asset($p)
                          : asset('storage/'.$p))
                      : null;
                  @endphp

                  <tr class="hover:bg-slate-50">
                    <td class="px-4 py-3">
                      <div class="flex items-center gap-3">
                        <div class="h-9 w-9 rounded-xl bg-slate-100 flex items-center justify-center">
                          <i data-lucide="graduation-cap" class="h-4 w-4 text-slate-500"></i>
                        </div>
                        <div>
                          <div class="font-medium text-slate-900">{{ $u->full_name }}</div>
                          <div class="text-[12px] text-slate-500">Faculty ID: {{ $u->faculty_id }}</div>
                        </div>
                      </div>
                    </td>

                    <td class="px-4 py-3 text-slate-700">{{ $u->email }}</td>

                    <td class="px-4 py-3 text-slate-700">
                      {{ \Illuminate\Support\Carbon::parse($u->created_at)->format('M d, Y') }}
                    </td>

                    <td class="px-4 py-3">
                      <div class="flex items-center justify-end gap-1.5">
                        {{-- View ID file (Blue) --}}
                        @if($idUrl)
                          <a href="{{ $idUrl }}" target="_blank" rel="noopener"
                             class="inline-flex items-center justify-center rounded-lg bg-blue-600 text-white p-2 hover:bg-blue-700"
                             title="View submitted ID">
                            <i data-lucide="eye" class="h-4 w-4"></i>
                          </a>
                        @endif

                        {{-- Approve (Green) --}}
                        <form method="post" action="{{ route('admin.faculty.approve', $u->id) }}">
                          @csrf
                          <button type="submit"
                            class="inline-flex items-center justify-center rounded-lg bg-green-600 text-white p-2 hover:bg-green-700 disabled:opacity-50"
                            {{ $isApproved ? 'disabled' : '' }}
                            title="Approve">
                            <i data-lucide="check" class="h-4 w-4"></i>
                          </button>
                        </form>

                        {{-- Reject (Red) --}}
                        <form method="post" action="{{ route('admin.faculty.reject', $u->id) }}">
                          @csrf
                          <button type="submit"
                            class="inline-flex items-center justify-center rounded-lg bg-red-600 text-white p-2 hover:bg-red-700 disabled:opacity-50"
                            {{ $isRejected ? 'disabled' : '' }}
                            title="Reject">
                            <i data-lucide="x" class="h-4 w-4"></i>
                          </button>
                        </form>
                      </div>
                    </td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="4" class="px-4 py-8 text-center text-slate-500">
                      No records found.
                    </td>
                  </tr>
                @endforelse

              </tbody>
            </table>
          </div>

          {{-- Footer / pagination --}}
          @php
            $current = method_exists($rows, 'currentPage') ? $rows->currentPage() : 1;
            $last    = method_exists($rows, 'lastPage')    ? $rows->lastPage()    : 1;
            $prevUrl = $current > 1 ? $rows->url($current - 1) : null;
            $nextUrl = $current < $last ? $rows->url($current + 1) : null;
            $start   = max(1, $current - 1);
            $end     = min($last, $current + 1);
          @endphp

          <div class="flex items-center justify-end gap-1 px-4 py-3 border-t border-slate-200 bg-slate-50">
            {{-- Prev --}}
            @if($prevUrl)
              <a href="{{ $prevUrl }}"
                 class="h-9 w-9 inline-flex items-center justify-center rounded-lg border border-slate-200 bg-white hover:bg-slate-50">
                <i data-lucide="chevron-left" class="h-4 w-4"></i>
              </a>
            @else
              <button disabled
                 class="h-9 w-9 inline-flex items-center justify-center rounded-lg border border-slate-200 bg-white opacity-50">
                <i data-lucide="chevron-left" class="h-4 w-4"></i>
              </button>
            @endif

            {{-- Page numbers (current ±1) --}}
            @for ($p = $start; $p <= $end; $p++)
              @if ($p === $current)
                <span class="h-9 w-9 inline-flex items-center justify-center rounded-lg bg-slate-900 text-white">{{ $p }}</span>
              @else
                <a href="{{ $rows->url($p) }}"
                   class="h-9 w-9 inline-flex items-center justify-center rounded-lg border border-slate-200 bg-white hover:bg-slate-50">{{ $p }}</a>
              @endif
            @endfor

            {{-- Next --}}
            @if($nextUrl)
              <a href="{{ $nextUrl }}"
                 class="h-9 w-9 inline-flex items-center justify-center rounded-lg border border-slate-200 bg-white hover:bg-slate-50">
                <i data-lucide="chevron-right" class="h-4 w-4"></i>
              </a>
            @else
              <button disabled
                 class="h-9 w-9 inline-flex items-center justify-center rounded-lg border border-slate-200 bg-white opacity-50">
                <i data-lucide="chevron-right" class="h-4 w-4"></i>
              </button>
            @endif
          </div>
        </div>
      </div>
    </section>
  </main>

  {{-- Shared footer --}}
  @include('partials.admin-footer')

  <script src="https://unpkg.com/lucide@latest"></script>
  <script> lucide.createIcons(); </script>
</body>
</html>
