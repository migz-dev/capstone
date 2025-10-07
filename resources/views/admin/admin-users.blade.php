{{-- resources/views/admin/admin-users.blade.php --}}
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <title>Admin • Users · NurSync</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('CON_LOGO.ico') }}">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('CON_LOGO.ico') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
        {{-- Sidebar --}}
        @include('partials.admin-sidebar', ['active' => 'users'])

        {{-- Main --}}
        <section class="flex-1 min-w-0">
            {{-- Header --}}
            <header class="sticky top-0 z-30 bg-white/80 backdrop-blur border-b border-slate-200">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 h-14 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div
                            class="inline-flex h-8 w-8 items-center justify-center rounded-xl bg-slate-900 text-white shadow-sm">
                            <i data-lucide="users" class="h-4 w-4"></i>
                        </div>
                        <div>
                            <h1 class="text-[15px] sm:text-[16px] font-semibold leading-tight">Users</h1>
                            <p class="text-[12px] text-slate-500 -mt-0.5">Manage student, faculty, and admin accounts.
                            </p>
                        </div>
                    </div>

                    {{-- Primary actions --}}
                    <div class="flex items-center gap-2">
                        {{-- Create button --}}
                        <button type="button"
                            class="inline-flex items-center gap-2 rounded-xl bg-green-600 text-white px-3 py-2 text-[13px] font-medium shadow hover:bg-green-700 active:scale-[.99]"
                            data-modal-target="modalCreate">
                            <i data-lucide="plus" class="h-4 w-4"></i>
                            <span>Create</span>
                        </button>

                        {{-- Archives button (static UI for now) --}}
                        <a href="{{ route('admin.users.archives') }}"
                            class="inline-flex items-center gap-2 rounded-xl bg-slate-700 text-white px-3 py-2 text-[13px] font-medium shadow hover:bg-slate-800 active:scale-[.99]">
                            <i data-lucide="archive" class="h-4 w-4"></i>
                            <span>Archives</span>
                        </a>

                    </div>

                </div>
            </header>

            {{-- Content --}}
            <div class="max-w-7xl mx-auto px-4 sm:px-6 py-6 space-y-6">

                {{-- Filters / tools --}}
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-4">
                    <div class="flex flex-col sm:flex-row gap-3 sm:items-center sm:justify-between">
                        <div class="flex items-center gap-3 w-full sm:w-auto">
                            <div class="relative flex-1 sm:w-64">
                                <i data-lucide="search" class="absolute left-3 top-2.5 h-4 w-4 text-slate-400"></i>
                                <input type="text" placeholder="Search name, email..."
                                    class="w-full rounded-xl border-slate-200 pl-9 pr-3 py-2.5 text-sm focus:ring-2 focus:ring-slate-300">
                            </div>

                            <select
                                class="rounded-xl border-slate-200 text-sm py-2.5 px-3 focus:ring-2 focus:ring-slate-300">
                                <option value="">All roles</option>
                                <option>Student</option>
                                <option>Faculty</option>
                                <option>Admin</option>
                            </select>

                            <select
                                class="rounded-xl border-slate-200 text-sm py-2.5 px-3 focus:ring-2 focus:ring-slate-300">
                                <option value="">All status</option>
                                <option>Active</option>
                                <option>Inactive</option>
                                <option>Archived</option>
                            </select>
                        </div>

                        <div class="flex items-center gap-2">
                            <button
                                class="inline-flex items-center gap-2 rounded-xl bg-white border border-slate-200 px-3 py-2 text-[13px] hover:bg-slate-50">
                                <i data-lucide="download" class="h-4 w-4"></i>
                                Export
                            </button>
                            <button
                                class="inline-flex items-center gap-2 rounded-xl bg-white border border-slate-200 px-3 py-2 text-[13px] hover:bg-slate-50">
                                <i data-lucide="settings-2" class="h-4 w-4"></i>
                                Columns
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Table --}}
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm">
                            <thead class="bg-slate-50 border-b border-slate-200">
                                <tr class="text-left text-slate-600">
                                    <th class="px-4 py-3 w-10">
                                        <input type="checkbox" class="rounded border-slate-300">
                                    </th>
                                    <th class="px-4 py-3">Name</th>
                                    <th class="px-4 py-3">Email</th>
                                    <th class="px-4 py-3">Role</th>
                                    <th class="px-4 py-3">Status</th>
                                    <th class="px-4 py-3">Created</th>
                                    <th class="px-4 py-3 text-right">Actions</th>
                                </tr>
                            </thead>

                            <tbody class="divide-y divide-slate-200">
                                @forelse ($usersPage as $u)
                                    @php
                                        $isActive = strtolower($u->status) === 'active';
                                        $pill = $roleStyles[$u->role] ?? $roleStyles['Student'];
                                    @endphp
                                    <tr class="hover:bg-slate-50">
                                        <td class="px-4 py-3">
                                            <input type="checkbox" class="rounded border-slate-300">
                                        </td>

                                        {{-- Name + avatar --}}
                                        <td class="px-4 py-3">
                                            <div class="flex items-center gap-3">
                                                @if ($u->avatar_url)
                                                    <img src="{{ $u->avatar_url }}" alt="{{ $u->name }}"
                                                        class="h-9 w-9 rounded-xl object-cover">
                                                @else
                                                    <div
                                                        class="h-9 w-9 rounded-xl bg-slate-100 flex items-center justify-center">
                                                        <i data-lucide="user" class="h-4 w-4 text-slate-500"></i>
                                                    </div>
                                                @endif
                                                <div>
                                                    <div class="font-medium text-slate-900">{{ $u->name }}</div>
                                                    <div class="text-[12px] text-slate-500">ID: {{ $u->id }}</div>
                                                </div>
                                            </div>
                                        </td>

                                        {{-- Email --}}
                                        <td class="px-4 py-3 text-slate-700">
                                            {{ $u->email ?? '—' }}
                                        </td>

                                        {{-- Role pill --}}
                                        <td class="px-4 py-3">
                                            <span
                                                class="inline-flex items-center gap-1.5 rounded-lg {{ $pill['bg'] }} {{ $pill['text'] }} px-2 py-1 text-[12px] font-medium">
                                                <i data-lucide="{{ $pill['icon'] }}" class="h-3.5 w-3.5"></i> {{ $u->role }}
                                            </span>
                                        </td>

                                        {{-- Status pill --}}
                                        <td class="px-4 py-3">
                                            @if ($isActive)
                                                <span
                                                    class="inline-flex items-center gap-1.5 rounded-lg bg-emerald-50 text-emerald-700 px-2 py-1 text-[12px] font-medium">
                                                    <span class="h-2 w-2 rounded-full bg-emerald-500"></span> Active
                                                </span>
                                            @elseif (strtolower($u->status) === 'pending')
                                                <span
                                                    class="inline-flex items-center gap-1.5 rounded-lg bg-amber-50 text-amber-700 px-2 py-1 text-[12px] font-medium">
                                                    <span class="h-2 w-2 rounded-full bg-amber-500"></span> Pending
                                                </span>
                                            @elseif (strtolower($u->status) === 'rejected')
                                                <span
                                                    class="inline-flex items-center gap-1.5 rounded-lg bg-rose-50 text-rose-700 px-2 py-1 text-[12px] font-medium">
                                                    <span class="h-2 w-2 rounded-full bg-rose-500"></span> Rejected
                                                </span>
                                            @else
                                                <span
                                                    class="inline-flex items-center gap-1.5 rounded-lg bg-slate-100 text-slate-600 px-2 py-1 text-[12px] font-medium">
                                                    <span class="h-2 w-2 rounded-full bg-slate-400"></span> {{ $u->status }}
                                                </span>
                                            @endif
                                        </td>

                                        {{-- Created date --}}
                                        <td class="px-4 py-3 text-slate-700">
                                            {{ \Illuminate\Support\Carbon::parse($u->created_at)->format('M d, Y') }}
                                        </td>

                                        {{-- Actions --}}
                                        <td class="px-4 py-3">
                                            <div class="flex items-center justify-end gap-1.5">
                                                <button type="button"
                                                    class="inline-flex items-center justify-center rounded-lg bg-blue-600 text-white p-2 hover:bg-blue-700"
                                                    data-modal-target="modalRead">
                                                    <i data-lucide="eye" class="h-4 w-4"></i>
                                                </button>
                                                <button type="button"
                                                    class="inline-flex items-center justify-center rounded-lg bg-yellow-400 text-slate-900 p-2 hover:brightness-95"
                                                    data-modal-target="modalUpdate">
                                                    <i data-lucide="pencil" class="h-4 w-4"></i>
                                                </button>
                                                <button type="button"
                                                    class="inline-flex items-center justify-center rounded-lg bg-orange-500 text-white p-2 hover:bg-orange-600"
                                                    data-modal-target="modalArchive" data-user-id="{{ $u->id }}"
                                                    data-user-name="{{ $u->name }}">
                                                    <i data-lucide="archive" class="h-4 w-4"></i>
                                                </button>

                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-4 py-8 text-center text-slate-500">No users found.</td>
                                    </tr>
                                @endforelse
                            </tbody>

                        </table>
                    </div>

                    {{-- Footer actions / pagination --}}
                    <div
                        class="flex flex-col sm:flex-row items-center justify-between gap-3 px-4 py-3 border-t border-slate-200 bg-slate-50">
                        <div class="flex items-center gap-2">
                            <select class="rounded-xl border-slate-200 text-sm py-2 px-2.5">
                                <option>Bulk action</option>
                                <option>Archive selected</option>
                                <option>Set active</option>
                                <option>Set inactive</option>
                            </select>
                            <button
                                class="inline-flex items-center gap-2 rounded-xl bg-slate-900 text-white px-3 py-2 text-[13px] font-medium hover:bg-black/90">
                                <i data-lucide="play" class="h-4 w-4"></i> Apply
                            </button>
                        </div>

                        @php
                            $cur = $usersPage->currentPage();
                            $last = max(1, $usersPage->lastPage());
                            $window = 3;
                            $half = intdiv($window - 1, 2);
                            $from = max(1, $cur - $half);
                            $to = min($last, $from + $window - 1);
                            $from = max(1, $to - $window + 1);
                        @endphp

                        <nav class="flex items-center gap-1">
                            {{-- Prev --}}
                            @if ($usersPage->onFirstPage())
                                <button
                                    class="h-9 w-9 inline-flex items-center justify-center rounded-lg border border-slate-200 bg-white opacity-50 cursor-not-allowed">
                                    <i data-lucide="chevron-left" class="h-4 w-4"></i>
                                </button>
                            @else
                                <a href="{{ $usersPage->previousPageUrl() }}"
                                    class="h-9 w-9 inline-flex items-center justify-center rounded-lg border border-slate-200 bg-white hover:bg-slate-50">
                                    <i data-lucide="chevron-left" class="h-4 w-4"></i>
                                </a>
                            @endif

                            {{-- Numbered buttons (window of 3) --}}
                            @for ($i = $from; $i <= $to; $i++)
                                @if ($i === $cur)
                                    <span
                                        class="h-9 w-9 inline-flex items-center justify-center rounded-lg bg-slate-900 text-white">{{ $i }}</span>
                                @else
                                    <a href="{{ $usersPage->url($i) }}"
                                        class="h-9 w-9 inline-flex items-center justify-center rounded-lg border border-slate-200 bg-white hover:bg-slate-50">
                                        {{ $i }}
                                    </a>
                                @endif
                            @endfor

                            {{-- Next --}}
                            @if ($cur >= $last)
                                <button
                                    class="h-9 w-9 inline-flex items-center justify-center rounded-lg border border-slate-200 bg-white opacity-50 cursor-not-allowed">
                                    <i data-lucide="chevron-right" class="h-4 w-4"></i>
                                </button>
                            @else
                                <a href="{{ $usersPage->nextPageUrl() }}"
                                    class="h-9 w-9 inline-flex items-center justify-center rounded-lg border border-slate-200 bg-white hover:bg-slate-50">
                                    <i data-lucide="chevron-right" class="h-4 w-4"></i>
                                </a>
                            @endif
                        </nav>
                    </div>
                </div>
            </div>

            {{-- Footer --}}
        </section>
    </main>

    {{-- Push modal partials to the "modals" stack --}}
    @push('modals')
        @include('admin.users._modals')
    @endpush

    {{-- Render the stack here IF your layout doesn’t already do it. Safe to keep. --}}
    @stack('modals')

    {{-- Footer (shared) --}}
    @include('partials.admin-footer')

    {{-- Icons + lightweight modal toggles --}}
    <script src="https://unpkg.com/lucide@latest"></script>
    <script>
        lucide.createIcons();
        // very tiny modal toggler for the static UI
        document.querySelectorAll('[data-modal-target]').forEach(btn => {
            btn.addEventListener('click', () => {
                const id = btn.getAttribute('data-modal-target');
                document.getElementById(id)?.classList.remove('hidden');
                lucide.createIcons();
            });
        });
        document.querySelectorAll('[data-modal-close]').forEach(btn => {
            btn.addEventListener('click', () => btn.closest('.fixed.inset-0')?.classList.add('hidden'));
        });
        document.querySelectorAll('.fixed.inset-0').forEach(modal => {
            modal.addEventListener('click', (e) => {
                if (e.target === modal) modal.classList.add('hidden');
            });
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
  const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
  const modalArchive = document.getElementById('modalArchive');
  const archiveBtn   = document.getElementById('archiveConfirmBtn');

  let currentArchiveId = null;
  let currentArchiveRow = null;

  // When any "open archive modal" button is clicked, stash the user id
  document.querySelectorAll('[data-modal-target="modalArchive"]').forEach(btn => {
    btn.addEventListener('click', () => {
      currentArchiveId = btn.getAttribute('data-user-id');
      // remember the row so we can remove it on success
      currentArchiveRow = btn.closest('tr');
    });
  });

  // Confirm archive → POST to route, then SweetAlert + remove row
  archiveBtn?.addEventListener('click', async () => {
    if (!currentArchiveId) return;

    try {
      const res = await fetch("{{ route('admin.users.archive', ['id' => 'REPLACE_ID']) }}".replace('REPLACE_ID', currentArchiveId), {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': csrf,
          'Accept': 'application/json',
        },
        body: JSON.stringify({}),
      });

      if (!res.ok) {
        const err = await res.json().catch(() => ({}));
        throw new Error(err.message || 'Failed to archive user.');
      }

      // hide modal
      modalArchive.classList.add('hidden');

      // optimistic UI: remove row
      if (currentArchiveRow) currentArchiveRow.remove();

      // success alert
      Swal.fire({
        icon: 'success',
        title: 'Archived',
        text: 'The user has been archived.',
        timer: 1500,
        showConfirmButton: false
      });

      // reset
      currentArchiveId = null;
      currentArchiveRow = null;

    } catch (e) {
      Swal.fire({
        icon: 'error',
        title: 'Archive failed',
        text: e.message || 'Something went wrong.'
      });
    }
  });
</script>

</body>

</html>