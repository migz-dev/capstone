{{-- resources/views/admin/admin-approvals.blade.php --}}
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
  <title>Admin • Approvals Queue · NurSync</title>
  
  @vite(['resources/css/app.css', 'resources/js/app.js'])
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
  <style> body { font-family: 'Poppins', ui-sans-serif, system-ui, sans-serif; } </style>
</head>
<body class="min-h-screen bg-slate-50">

  <main class="min-h-screen flex">
    {{-- Sidebar --}}
    @include('partials.admin-sidebar', ['active' => 'approvals'])

    {{-- Main --}}
    <section class="flex-1 min-w-0">
      {{-- Header --}}
      <header class="sticky top-0 z-30 bg-white/80 backdrop-blur border-b border-slate-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 h-14 flex items-center justify-between">
          <div class="flex items-center gap-3">
            <div class="inline-flex h-8 w-8 items-center justify-center rounded-xl bg-slate-900 text-white shadow-sm">
              <i data-lucide="inbox" class="h-4 w-4"></i>
            </div>
            <div>
              <h1 class="text-[15px] sm:text-[16px] font-semibold leading-tight">Approvals Queue</h1>
              <p class="text-[12px] text-slate-500 -mt-0.5">Review pending items across the system.</p>
            </div>
          </div>

          {{-- Primary action --}}
          <div class="flex items-center gap-2">
            <button type="button"
              class="inline-flex items-center gap-2 rounded-xl bg-green-600 text-white px-3 py-2 text-[13px] font-medium shadow hover:bg-green-700 active:scale-[.99]"
              data-modal-target="modalBulkApprove">
              <i data-lucide="check" class="h-4 w-4"></i>
              <span>Bulk Approve</span>
            </button>
          </div>
        </div>
      </header>

      {{-- Content --}}
      <div class="max-w-7xl mx-auto px-4 sm:px-6 py-6 space-y-6">

        {{-- Filters --}}
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-4">
          <div class="flex flex-col sm:flex-row gap-3 sm:items-center sm:justify-between">
            <div class="flex items-center gap-3 w-full sm:w-auto">
              <div class="relative flex-1 sm:w-72">
                <i data-lucide="search" class="absolute left-3 top-2.5 h-4 w-4 text-slate-400"></i>
                <input type="text" placeholder="Search name, title, type..."
                       class="w-full rounded-xl border-slate-200 pl-9 pr-3 py-2.5 text-sm focus:ring-2 focus:ring-slate-300">
              </div>

              <select class="rounded-xl border-slate-200 text-sm py-2.5 px-3 focus:ring-2 focus:ring-slate-300">
                <option value="">All types</option>
                <option>Faculty Registration</option>
                <option>Announcement</option>
                <option>Course Update</option>
                <option>RLE Schedule</option>
                <option>Return Demo</option>
                <option>Learning Resource</option>
              </select>

              <select class="rounded-xl border-slate-200 text-sm py-2.5 px-3 focus:ring-2 focus:ring-slate-300">
                <option value="">All status</option>
                <option selected>Pending</option>
                <option>Approved</option>
                <option>Rejected</option>
              </select>
            </div>

            <div class="flex items-center gap-2">
              <button class="inline-flex items-center gap-2 rounded-xl bg-white border border-slate-200 px-3 py-2 text-[13px] hover:bg-slate-50">
                <i data-lucide="download" class="h-4 w-4"></i>
                Export
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
                  <th class="px-4 py-3 w-10"><input type="checkbox" class="rounded border-slate-300"></th>
                  <th class="px-4 py-3">Item</th>
                  <th class="px-4 py-3">Type</th>
                  <th class="px-4 py-3">Submitted</th>
                  <th class="px-4 py-3">Requester</th>
                  <th class="px-4 py-3">Status</th>
                  <th class="px-4 py-3 text-right">Actions</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-slate-200">
                {{-- Row: Faculty Registration --}}
                <tr class="hover:bg-slate-50">
                  <td class="px-4 py-3"><input type="checkbox" class="rounded border-slate-300"></td>
                  <td class="px-4 py-3">
                    <div class="font-medium text-slate-900">Joseph S. Alipio — Faculty Registration</div>
                    <div class="text-[12px] text-slate-500">Nursing Department</div>
                  </td>
                  <td class="px-4 py-3">
                    <span class="inline-flex items-center gap-1.5 rounded-lg bg-purple-50 text-purple-700 px-2 py-1 text-[12px] font-medium">
                      <i data-lucide="graduation-cap" class="h-3.5 w-3.5"></i> Faculty Registration
                    </span>
                  </td>
                  <td class="px-4 py-3 text-slate-700">Sep 24, 2025</td>
                  <td class="px-4 py-3 text-slate-700">Self-submitted</td>
                  <td class="px-4 py-3">
                    <span class="inline-flex items-center gap-1.5 rounded-lg bg-amber-50 text-amber-700 px-2 py-1 text-[12px] font-medium">
                      <span class="h-2 w-2 rounded-full bg-amber-500"></span> Pending
                    </span>
                  </td>
                  <td class="px-4 py-3">
                    <div class="flex items-center justify-end gap-1.5">
                      <button type="button" class="inline-flex items-center justify-center rounded-lg bg-blue-600 text-white p-2 hover:bg-blue-700" data-modal-target="modalView">
                        <i data-lucide="eye" class="h-4 w-4"></i>
                      </button>
                      <button type="button" class="inline-flex items-center justify-center rounded-lg bg-green-600 text-white p-2 hover:bg-green-700" data-modal-target="modalApprove">
                        <i data-lucide="check" class="h-4 w-4"></i>
                      </button>
                      <button type="button" class="inline-flex items-center justify-center rounded-lg bg-red-600 text-white p-2 hover:bg-red-700" data-modal-target="modalReject">
                        <i data-lucide="x" class="h-4 w-4"></i>
                      </button>
                    </div>
                  </td>
                </tr>

                {{-- Row: Announcement --}}
                <tr class="hover:bg-slate-50">
                  <td class="px-4 py-3"><input type="checkbox" class="rounded border-slate-300"></td>
                  <td class="px-4 py-3">
                    <div class="font-medium text-slate-900">Campus Drill — Safety Notice</div>
                    <div class="text-[12px] text-slate-500">Scheduled: Oct 3, 2025</div>
                  </td>
                  <td class="px-4 py-3">
                    <span class="inline-flex items-center gap-1.5 rounded-lg bg-sky-50 text-sky-700 px-2 py-1 text-[12px] font-medium">
                      <i data-lucide="bell" class="h-3.5 w-3.5"></i> Announcement
                    </span>
                  </td>
                  <td class="px-4 py-3 text-slate-700">Sep 27, 2025</td>
                  <td class="px-4 py-3 text-slate-700">Prof. Garcia</td>
                  <td class="px-4 py-3">
                    <span class="inline-flex items-center gap-1.5 rounded-lg bg-amber-50 text-amber-700 px-2 py-1 text-[12px] font-medium">
                      <span class="h-2 w-2 rounded-full bg-amber-500"></span> Pending
                    </span>
                  </td>
                  <td class="px-4 py-3">
                    <div class="flex items-center justify-end gap-1.5">
                      <button type="button" class="inline-flex items-center justify-center rounded-lg bg-blue-600 text-white p-2 hover:bg-blue-700" data-modal-target="modalView">
                        <i data-lucide="eye" class="h-4 w-4"></i>
                      </button>
                      <button type="button" class="inline-flex items-center justify-center rounded-lg bg-green-600 text-white p-2 hover:bg-green-700" data-modal-target="modalApprove">
                        <i data-lucide="check" class="h-4 w-4"></i>
                      </button>
                      <button type="button" class="inline-flex items-center justify-center rounded-lg bg-red-600 text-white p-2 hover:bg-red-700" data-modal-target="modalReject">
                        <i data-lucide="x" class="h-4 w-4"></i>
                      </button>
                    </div>
                  </td>
                </tr>

                {{-- Row: Learning Resource --}}
                <tr class="hover:bg-slate-50">
                  <td class="px-4 py-3"><input type="checkbox" class="rounded border-slate-300"></td>
                  <td class="px-4 py-3">
                    <div class="font-medium text-slate-900">Vital Signs Skill Sheet (PDF)</div>
                    <div class="text-[12px] text-slate-500">Linked to: NURS101</div>
                  </td>
                  <td class="px-4 py-3">
                    <span class="inline-flex items-center gap-1.5 rounded-lg bg-indigo-50 text-indigo-700 px-2 py-1 text-[12px] font-medium">
                      <i data-lucide="folder-open" class="h-3.5 w-3.5"></i> Learning Resource
                    </span>
                  </td>
                  <td class="px-4 py-3 text-slate-700">Sep 26, 2025</td>
                  <td class="px-4 py-3 text-slate-700">Prof. Angeles</td>
                  <td class="px-4 py-3">
                    <span class="inline-flex items-center gap-1.5 rounded-lg bg-amber-50 text-amber-700 px-2 py-1 text-[12px] font-medium">
                      <span class="h-2 w-2 rounded-full bg-amber-500"></span> Pending
                    </span>
                  </td>
                  <td class="px-4 py-3">
                    <div class="flex items-center justify-end gap-1.5">
                      <button type="button" class="inline-flex items-center justify-center rounded-lg bg-blue-600 text-white p-2 hover:bg-blue-700" data-modal-target="modalView">
                        <i data-lucide="eye" class="h-4 w-4"></i>
                      </button>
                      <button type="button" class="inline-flex items-center justify-center rounded-lg bg-green-600 text-white p-2 hover:bg-green-700" data-modal-target="modalApprove">
                        <i data-lucide="check" class="h-4 w-4"></i>
                      </button>
                      <button type="button" class="inline-flex items-center justify-center rounded-lg bg-red-600 text-white p-2 hover:bg-red-700" data-modal-target="modalReject">
                        <i data-lucide="x" class="h-4 w-4"></i>
                      </button>
                    </div>
                  </td>
                </tr>

              </tbody>
            </table>
          </div>

          {{-- Footer / bulk actions + pagination --}}
          <div class="flex flex-col sm:flex-row items-center justify-between gap-3 px-4 py-3 border-t border-slate-200 bg-slate-50">
            <div class="flex items-center gap-2">
              <select class="rounded-xl border-slate-200 text-sm py-2 px-2.5">
                <option>Bulk action</option>
                <option>Approve selected</option>
                <option>Reject selected</option>
                <option>Archive selected</option>
              </select>
              <button class="inline-flex items-center gap-2 rounded-xl bg-slate-900 text-white px-3 py-2 text-[13px] font-medium hover:bg-black/90">
                <i data-lucide="play" class="h-4 w-4"></i> Apply
              </button>
            </div>

            <nav class="flex items-center gap-1">
              <button class="h-9 w-9 inline-flex items-center justify-center rounded-lg border border-slate-200 bg-white hover:bg-slate-50">
                <i data-lucide="chevron-left" class="h-4 w-4"></i>
              </button>
              <button class="h-9 w-9 inline-flex items-center justify-center rounded-lg bg-slate-900 text-white">1</button>
              <button class="h-9 w-9 inline-flex items-center justify-center rounded-lg border border-slate-200 bg-white hover:bg-slate-50">2</button>
              <button class="h-9 w-9 inline-flex items-center justify-center rounded-lg border border-slate-200 bg-white hover:bg-slate-50">3</button>
              <button class="h-9 w-9 inline-flex items-center justify-center rounded-lg border border-slate-200 bg-white hover:bg-slate-50">
                <i data-lucide="chevron-right" class="h-4 w-4"></i>
              </button>
            </nav>
          </div>
        </div>
      </div>
    </section>
  </main>

  {{-- Shared footer --}}
  @include('partials.admin-footer')

  {{-- ===== Modals (static UI only) ===== --}}
  {{-- Bulk Approve --}}
  <div id="modalBulkApprove" class="hidden fixed inset-0 z-50">
    <div class="absolute inset-0 bg-black/40"></div>
    <div class="relative mx-auto mt-24 w-full max-w-md">
      <div class="bg-white rounded-2xl shadow-xl border border-slate-200 p-5">
        <div class="flex items-center gap-3">
          <div class="h-9 w-9 rounded-xl bg-green-100 text-green-700 inline-flex items-center justify-center">
            <i data-lucide="check-circle" class="h-5 w-5"></i>
          </div>
          <div>
            <h3 class="text-[15px] font-semibold">Approve selected items?</h3>
            <p class="text-[13px] text-slate-600">This will mark all selected requests as approved.</p>
          </div>
          <button class="ml-auto p-2 rounded-lg hover:bg-slate-100" data-modal-close>
            <i data-lucide="x" class="h-5 w-5"></i>
          </button>
        </div>
        <div class="mt-5 flex items-center justify-end gap-2">
          <button class="px-3 py-2 rounded-xl text-[13px] border border-slate-200 bg-white hover:bg-slate-50" data-modal-close>Cancel</button>
          <button class="px-3 py-2 rounded-xl text-[13px] bg-green-600 text-white hover:bg-green-700 inline-flex items-center gap-2">
            <i data-lucide="check" class="h-4 w-4"></i> Approve
          </button>
        </div>
      </div>
    </div>
  </div>

  {{-- View --}}
  <div id="modalView" class="hidden fixed inset-0 z-50">
    <div class="absolute inset-0 bg-black/40"></div>
    <div class="relative mx-auto mt-24 w-full max-w-xl">
      <div class="bg-white rounded-2xl shadow-xl border border-slate-200 p-5">
        <div class="flex items-center justify-between">
          <h3 class="text-[15px] font-semibold">Request details</h3>
          <button class="p-2 rounded-lg hover:bg-slate-100" data-modal-close>
            <i data-lucide="x" class="h-5 w-5"></i>
          </button>
        </div>
        <dl class="mt-4 grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-3 text-sm">
          <div><dt class="text-slate-500 text-[12px]">Type</dt><dd class="font-medium">Faculty Registration</dd></div>
          <div><dt class="text-slate-500 text-[12px]">Submitted</dt><dd class="font-medium">Sep 24, 2025</dd></div>
          <div class="sm:col-span-2"><dt class="text-slate-500 text-[12px]">Title / Name</dt><dd class="font-medium">Joseph S. Alipio — Faculty Registration</dd></div>
          <div class="sm:col-span-2"><dt class="text-slate-500 text-[12px]">Notes</dt><dd class="font-medium">Attached credentials and department info.</dd></div>
        </dl>
        <div class="mt-5 flex items-center justify-end">
          <button class="px-3 py-2 rounded-xl text-[13px] bg-blue-600 text-white hover:bg-blue-700 inline-flex items-center gap-2" data-modal-close>
            <i data-lucide="check" class="h-4 w-4"></i> Close
          </button>
        </div>
      </div>
    </div>
  </div>

  {{-- Approve --}}
  <div id="modalApprove" class="hidden fixed inset-0 z-50">
    <div class="absolute inset-0 bg-black/40"></div>
    <div class="relative mx-auto mt-24 w-full max-w-md">
      <div class="bg-white rounded-2xl shadow-xl border border-slate-200 p-5">
        <div class="flex items-center gap-3">
          <div class="h-9 w-9 rounded-xl bg-green-100 text-green-700 inline-flex items-center justify-center">
            <i data-lucide="check" class="h-5 w-5"></i>
          </div>
          <div>
            <h3 class="text-[15px] font-semibold">Approve this request?</h3>
            <p class="text-[13px] text-slate-600">This action can be reversed by an admin.</p>
          </div>
          <button class="ml-auto p-2 rounded-lg hover:bg-slate-100" data-modal-close>
            <i data-lucide="x" class="h-5 w-5"></i>
          </button>
        </div>
        <div class="mt-5 flex items-center justify-end gap-2">
          <button class="px-3 py-2 rounded-xl text-[13px] border border-slate-200 bg-white hover:bg-slate-50" data-modal-close>Cancel</button>
          <button class="px-3 py-2 rounded-xl text-[13px] bg-green-600 text-white hover:bg-green-700 inline-flex items-center gap-2">
            <i data-lucide="check" class="h-4 w-4"></i> Approve
          </button>
        </div>
      </div>
    </div>
  </div>

  {{-- Reject --}}
  <div id="modalReject" class="hidden fixed inset-0 z-50">
    <div class="absolute inset-0 bg-black/40"></div>
    <div class="relative mx-auto mt-24 w-full max-w-md">
      <div class="bg-white rounded-2xl shadow-xl border border-slate-200 p-5">
        <div class="flex items-center gap-3">
          <div class="h-9 w-9 rounded-xl bg-red-100 text-red-700 inline-flex items-center justify-center">
            <i data-lucide="x" class="h-5 w-5"></i>
          </div>
          <div>
            <h3 class="text-[15px] font-semibold">Reject this request?</h3>
            <p class="text-[13px] text-slate-600">Optionally add a reason below.</p>
          </div>
          <button class="ml-auto p-2 rounded-lg hover:bg-slate-100" data-modal-close>
            <i data-lucide="x" class="h-5 w-5"></i>
          </button>
        </div>
        <div class="mt-4">
          <textarea rows="4" class="w-full rounded-xl border-slate-200 px-3 py-2.5 text-sm" placeholder="Reason for rejection (optional)"></textarea>
        </div>
        <div class="mt-5 flex items-center justify-end gap-2">
          <button class="px-3 py-2 rounded-xl text-[13px] border border-slate-200 bg-white hover:bg-slate-50" data-modal-close>Cancel</button>
          <button class="px-3 py-2 rounded-xl text-[13px] bg-red-600 text-white hover:bg-red-700 inline-flex items-center gap-2">
            <i data-lucide="x" class="h-4 w-4"></i> Reject
          </button>
        </div>
      </div>
    </div>
  </div>

  {{-- Icons + tiny modal toggles --}}
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
</body>
</html>
