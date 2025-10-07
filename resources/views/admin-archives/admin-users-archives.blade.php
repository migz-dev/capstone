{{-- resources/views/admin/admin-archives/admin-users-archives.blade.php --}}
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <title>Admin • Archived Users · NurSync</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('CON_LOGO.ico') }}">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('CON_LOGO.ico') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/admin-users-archives.js'])

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', ui-sans-serif, system-ui, sans-serif;
        }
    </style>
</head>

<body class="min-h-screen bg-slate-50">

    <main class="min-h-screen flex">
        {{-- Sidebar (keep "users" active group) --}}
        @include('partials.admin-sidebar', ['active' => 'users'])

        {{-- Main --}}
        <section class="flex-1 min-w-0">
            {{-- Header --}}
            <header class="sticky top-0 z-30 bg-white/80 backdrop-blur border-b border-slate-200">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 h-14 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div
                            class="inline-flex h-8 w-8 items-center justify-center rounded-xl bg-slate-900 text-white shadow-sm">
                            <i data-lucide="archive" class="h-4 w-4"></i>
                        </div>
                        <div>
                            <h1 class="text-[15px] sm:text-[16px] font-semibold leading-tight">Archived Users</h1>
                            <p class="text-[12px] text-slate-500 -mt-0.5">Restorable accounts and records.</p>
                        </div>
                    </div>

                    {{-- Primary actions --}}
                    <div class="flex items-center gap-2">
                        {{-- Back to Users (static UI for now; swap to route later) --}}
                        <a href="{{ route('admin.users.index') }}"
                            class="inline-flex items-center gap-2 rounded-xl bg-slate-700 text-white px-3 py-2 text-[13px] font-medium shadow hover:bg-slate-800 active:scale-[.99]">
                            <i data-lucide="users" class="h-4 w-4"></i>
                            <span>Back to Users</span>
                        </a>

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

                            {{-- Status fixed to Archived (disabled to indicate page context) --}}
                            <select class="rounded-xl border-slate-200 text-sm py-2.5 px-3 bg-slate-50 text-slate-500"
                                disabled>
                                <option selected>Archived</option>
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
                                    <th class="px-4 py-3">Archived</th>
                                    <th class="px-4 py-3 text-right">Actions</th>
                                </tr>
                            </thead>

                            <tbody class="divide-y divide-slate-200">
                                @forelse ($archivedUsersPage as $u)
                                    @php
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

                                        {{-- Archived date --}}
                                        {{-- Archived date --}}
                                        <td class="px-4 py-3 text-slate-700">
                                            @php
                                                $archived = $u->archived_at ?? null;
                                                $fallback = $u->created_at ?? null;
                                            @endphp

                                            @if ($archived)
                                                {{ \Illuminate\Support\Carbon::parse($archived)->format('M d, Y') }}
                                            @elseif ($fallback)
                                                {{ \Illuminate\Support\Carbon::parse($fallback)->format('M d, Y') }}
                                            @else
                                                —
                                            @endif
                                        </td>


                                        {{-- Actions --}}
<td class="px-4 py-3">
  <div class="flex items-center justify-end gap-1.5">

    {{-- VIEW --}}
    <button type="button"
      class="inline-flex items-center justify-center rounded-lg bg-blue-600 text-white p-2 hover:bg-blue-700"
      data-modal-target="modalRead"
      data-user-id="{{ $u->id }}"
      data-show-url="{{ route('admin.users.show', $u->id) }}">
      <i data-lucide="eye" class="h-4 w-4"></i>
    </button>

    {{-- RESTORE --}}
    <button type="button"
      class="inline-flex items-center justify-center rounded-lg bg-emerald-600 text-white p-2 hover:bg-emerald-700"
      data-modal-target="modalRestore"
      data-user-id="{{ $u->id }}"
      data-restore-url="{{ route('admin.users.restore', $u->id) }}">
      <i data-lucide="rotate-ccw" class="h-4 w-4"></i>
    </button>

    {{-- DELETE --}}
<button type="button"
  class="inline-flex items-center justify-center rounded-lg bg-rose-600 text-white p-2 hover:bg-rose-700"
  data-modal-target="modalDelete"
  data-user-id="{{ $u->id }}"
  data-destroy-url="{{ route('admin.users.destroy.post', $u->id) }}">
  <i data-lucide="trash-2" class="h-4 w-4"></i>
</button>

  </div>
</td>

                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-4 py-8 text-center text-slate-500">No archived users
                                            found.</td>
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
                                <option>Restore selected</option>
                                <option>Delete selected</option>
                            </select>
                            <button
                                class="inline-flex items-center gap-2 rounded-xl bg-slate-900 text-white px-3 py-2 text-[13px] font-medium hover:bg-black/90">
                                <i data-lucide="play" class="h-4 w-4"></i> Apply
                            </button>
                        </div>

                        @php
                            $cur = $archivedUsersPage->currentPage();
                            $last = max(1, $archivedUsersPage->lastPage());
                            $window = 3;
                            $half = intdiv($window - 1, 2);
                            $from = max(1, $cur - $half);
                            $to = min($last, $from + $window - 1);
                            $from = max(1, $to - $window + 1);
                        @endphp

                        <nav class="flex items-center gap-1">
                            {{-- Prev --}}
                            @if ($archivedUsersPage->onFirstPage())
                                <button
                                    class="h-9 w-9 inline-flex items-center justify-center rounded-lg border border-slate-200 bg-white opacity-50 cursor-not-allowed">
                                    <i data-lucide="chevron-left" class="h-4 w-4"></i>
                                </button>
                            @else
                                <a href="{{ $archivedUsersPage->previousPageUrl() }}"
                                    class="h-9 w-9 inline-flex items-center justify-center rounded-lg border border-slate-200 bg-white hover:bg-slate-50">
                                    <i data-lucide="chevron-left" class="h-4 w-4"></i>
                                </a>
                            @endif

                            {{-- Numbers --}}
                            @for ($i = $from; $i <= $to; $i++)
                                @if ($i === $cur)
                                    <span
                                        class="h-9 w-9 inline-flex items-center justify-center rounded-lg bg-slate-900 text-white">{{ $i }}</span>
                                @else
                                    <a href="{{ $archivedUsersPage->url($i) }}"
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
                                <a href="{{ $archivedUsersPage->nextPageUrl() }}"
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

    {{-- Push archive modals --}}
    @push('modals')
        @include('admin-archives._modals')
    @endpush

    @stack('modals')


    {{-- Footer (shared) --}}
    @include('partials.admin-footer')

    {{-- Icons + lightweight modal toggles --}}
    <script src="https://unpkg.com/lucide@latest"></script>
    <script>
        lucide.createIcons();

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
            modal.addEventListener('click', (e) => { if (e.target === modal) modal.classList.add('hidden'); });
        });
    </script>
    <script>
        // resources/js/admin-users-archives.js
// Restore & Delete actions on the Archived Users page

// If you bundle SweetAlert2 with Vite, uncomment the line below.
// import Swal from 'sweetalert2';

document.addEventListener('DOMContentLoaded', () => {
  const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

  // keep context between opening modal and confirming
  const state = { row: null, id: null, restoreUrl: null, destroyUrl: null };

  // helpers
  const setFromBtn = (btn, kind) => {
    state.row = btn.closest('tr') ?? null;
    state.id = btn.dataset.userId || state.row?.dataset.rowId || null;
    if (kind === 'restore') state.restoreUrl = btn.dataset.restoreUrl || null;
    if (kind === 'delete')  state.destroyUrl  = btn.dataset.destroyUrl  || null;
  };

  const hideModal = (id) => document.getElementById(id)?.classList.add('hidden');

  const toast = (icon, title, text) => {
    if (window.Swal) {
      window.Swal.fire({ icon, title, text, timer: 1500, showConfirmButton: false });
    } else {
      alert(`${title}${text ? '\n' + text : ''}`);
    }
  };

  const tryJson = async (res) => {
    try { return await res.json(); } catch { return null; }
  };

  const api = (url, method) => fetch(url, {
    method,
    headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' },
    credentials: 'same-origin',
  });

  // Event delegation: stash row + URLs when opening modals
  document.addEventListener('click', (e) => {
    const restoreBtn = e.target.closest('[data-modal-target="modalRestore"]');
    if (restoreBtn) setFromBtn(restoreBtn, 'restore');

    const deleteBtn = e.target.closest('[data-modal-target="modalDelete"]');
    if (deleteBtn) setFromBtn(deleteBtn, 'delete');
  });

  // Confirm Restore
  const restoreConfirm = document.getElementById('restoreConfirmBtn');
  restoreConfirm?.addEventListener('click', async () => {
    if (!state.restoreUrl) return;

    restoreConfirm.disabled = true;
    try {
      const res = await api(state.restoreUrl, 'POST');
      if (!res.ok) {
        const payload = await tryJson(res);
        throw new Error(payload?.message || 'Failed to restore user.');
      }

      hideModal('modalRestore');
      (document.querySelector(`tr[data-row-id="${CSS.escape(state.id ?? '')}"]`) || state.row)?.remove();
      toast('success', 'Restored', 'The user has been restored.');
    } catch (err) {
      toast('error', 'Restore failed', err?.message || 'Something went wrong.');
    } finally {
      restoreConfirm.disabled = false;
      state.row = state.id = state.restoreUrl = null;
    }
  });

  // Confirm Delete
  const deleteConfirm = document.getElementById('deleteConfirmBtn');
  deleteConfirm?.addEventListener('click', async () => {
    if (!state.destroyUrl) return;

    deleteConfirm.disabled = true;
    try {
      const res = await api(state.destroyUrl, 'DELETE');
      if (!res.ok) {
        const payload = await tryJson(res);
        throw new Error(payload?.message || 'Failed to delete user.');
      }

      hideModal('modalDelete');
      (document.querySelector(`tr[data-row-id="${CSS.escape(state.id ?? '')}"]`) || state.row)?.remove();
      toast('success', 'Deleted', 'The user has been permanently deleted.');
    } catch (err) {
      toast('error', 'Delete failed', err?.message || 'Something went wrong.');
    } finally {
      deleteConfirm.disabled = false;
      state.row = state.id = state.destroyUrl = null;
    }
  });
});
  </script>
    
</body>

</html>