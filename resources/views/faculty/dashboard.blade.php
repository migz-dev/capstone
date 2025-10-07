<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
  <title>Faculty Dashboard · NurSync</title>

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
    <!-- Sidebar (static) -->
    @include('partials.faculty-sidebar', ['active' => 'dashboard']) {{-- or sections/modules/etc. --}}

    <!-- Main content -->
    <section class="flex-1 px-8 py-10">
      <!-- Title -->
      <div class="flex items-center gap-3">
        <span class="inline-flex items-center justify-center h-8 w-8 rounded-xl bg-green-50 text-green-600">
          <!-- Clipboard-check icon -->
          <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor">
            <path d="M9 5h6a2 2 0 0 1 2 2v0a2 2 0 0 1-2 2H9a2 2 0 0 1-2-2v0a2 2 0 0 1 2-2Z" stroke-width="1.5" />
            <path d="M19 7v9a3 3 0 0 1-3 3H8a3 3 0 0 1-3-3V7" stroke-width="1.5" stroke-linecap="round" />
            <path d="M9 14l2 2 4-4" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
          </svg>
        </span>
        <h1 class="text-2xl font-bold">Faculty Dashboard</h1>
      </div>
      <p class="text-[13px] text-slate-500 mt-1">Overview of your teaching, duties, and actions</p>

      <!-- Stat cards -->
      <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4 mt-6">
        <div class="rounded-2xl border border-slate-200 bg-white p-5">
          <div class="text-[13px] font-medium text-slate-700">Active Sections</div>
          <div class="mt-2 text-3xl font-bold">3</div>
          <p class="mt-1 text-[12px] text-slate-500">Currently assigned to you</p>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-5">
          <div class="text-[13px] font-medium text-slate-700">RLE Duties Today</div>
          <div class="mt-2 text-3xl font-bold">1</div>
          <p class="mt-1 text-[12px] text-slate-500">Scheduled for today</p>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-5">
          <div class="text-[13px] font-medium text-slate-700">Pending Approvals</div>
          <div class="mt-2 text-3xl font-bold">2</div>
          <p class="mt-1 text-[12px] text-slate-500">Awaiting your action</p>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-5">
          <div class="text-[13px] font-medium text-slate-700">Unread Announcements</div>
          <div class="mt-2 text-3xl font-bold">5</div>
          <p class="mt-1 text-[12px] text-slate-500">Across your sections</p>
        </div>
      </div>

      <!-- Quick actions -->
      <div class="mt-8 rounded-2xl border border-slate-200 bg-white p-5">
        <div class="flex items-center justify-between">
          <h2 class="text-[15px] font-semibold text-slate-800">Quick Actions</h2>
        </div>
        <div class="mt-4 grid gap-3 sm:grid-cols-2 lg:grid-cols-4">
          <a href="#"
            class="rounded-xl border border-slate-200 px-4 py-3 text-[13px] font-medium hover:bg-slate-50 flex items-center gap-2">
            <i data-lucide="folder-plus" class="h-4 w-4"></i>
            Create Section
          </a>
          <a href="#"
            class="rounded-xl border border-slate-200 px-4 py-3 text-[13px] font-medium hover:bg-slate-50 flex items-center gap-2">
            <i data-lucide="hospital" class="h-4 w-4"></i>
            Schedule RLE Duty
          </a>
          <a href="#"
            class="rounded-xl border border-slate-200 px-4 py-3 text-[13px] font-medium hover:bg-slate-50 flex items-center gap-2">
            <i data-lucide="upload" class="h-4 w-4"></i>
            Upload Module
          </a>
          <a href="#"
            class="rounded-xl border border-slate-200 px-4 py-3 text-[13px] font-medium hover:bg-slate-50 flex items-center gap-2">
            <i data-lucide="megaphone" class="h-4 w-4"></i>
            Post Announcement
          </a>
        </div>
      </div>

      <!-- My Sections -->
      <div class="mt-8">
        <div class="flex items-center justify-between">
          <h2 class="text-[15px] font-semibold text-slate-800">My Sections</h2>
          <a href="#" class="text-[12px] text-slate-600 hover:text-slate-800">View all</a>
        </div>

        <div class="mt-4 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
          <!-- Card 1 -->
          <a href="#" class="block rounded-2xl border border-slate-200 bg-white p-5 hover:bg-slate-50">
            <div class="flex items-start justify-between">
              <div class="flex items-center gap-2">
                <span class="inline-flex h-8 w-8 items-center justify-center rounded-xl bg-slate-100 text-slate-600">
                  <i data-lucide="book-open" class="h-4 w-4"></i>
                </span>
                <div>
                  <div class="text-[13px] font-semibold text-slate-800">NCMA216</div>
                  <div class="text-[12px] text-slate-500">BSN 2-Y2-7</div>
                </div>
              </div>
              <span class="rounded-full border border-slate-200 px-2.5 py-1 text-[11px] text-slate-600">45
                students</span>
            </div>
            <div class="mt-3 text-[12px] text-slate-500 line-clamp-2">
              Medical-Surgical Nursing 1 — Fundamentals and core competencies.
            </div>
            <div class="mt-4 flex items-center gap-4 text-[11px] text-slate-500">
              <div class="flex items-center gap-1">
                <i data-lucide="calendar" class="h-3.5 w-3.5"></i>
                <span>MWF 7:00–9:00</span>
              </div>
              <div class="flex items-center gap-1">
                <i data-lucide="map-pin" class="h-3.5 w-3.5"></i>
                <span>Room 203</span>
              </div>
            </div>
          </a>

          <!-- Card 2 -->
          <a href="#" class="block rounded-2xl border border-slate-200 bg-white p-5 hover:bg-slate-50">
            <div class="flex items-start justify-between">
              <div class="flex items-center gap-2">
                <span class="inline-flex h-8 w-8 items-center justify-center rounded-xl bg-slate-100 text-slate-600">
                  <i data-lucide="book-open" class="h-4 w-4"></i>
                </span>
                <div>
                  <div class="text-[13px] font-semibold text-slate-800">NCMB312</div>
                  <div class="text-[12px] text-slate-500">BSN 3-Y2-5</div>
                </div>
              </div>
              <span class="rounded-full border border-slate-200 px-2.5 py-1 text-[11px] text-slate-600">38
                students</span>
            </div>
            <div class="mt-3 text-[12px] text-slate-500 line-clamp-2">
              Maternal and Child Health Nursing — perinatal care and pediatrics.
            </div>
            <div class="mt-4 flex items-center gap-4 text-[11px] text-slate-500">
              <div class="flex items-center gap-1">
                <i data-lucide="calendar" class="h-3.5 w-3.5"></i>
                <span>TTh 9:00–11:00</span>
              </div>
              <div class="flex items-center gap-1">
                <i data-lucide="map-pin" class="h-3.5 w-3.5"></i>
                <span>Room 310</span>
              </div>
            </div>
          </a>

          <!-- Card 3 -->
          <a href="#" class="block rounded-2xl border border-slate-200 bg-white p-5 hover:bg-slate-50">
            <div class="flex items-start justify-between">
              <div class="flex items-center gap-2">
                <span class="inline-flex h-8 w-8 items-center justify-center rounded-xl bg-slate-100 text-slate-600">
                  <i data-lucide="book-open" class="h-4 w-4"></i>
                </span>
                <div>
                  <div class="text-[13px] font-semibold text-slate-800">CHNN211</div>
                  <div class="text-[12px] text-slate-500">BSN 2-Y1-3</div>
                </div>
              </div>
              <span class="rounded-full border border-slate-200 px-2.5 py-1 text-[11px] text-slate-600">40
                students</span>
            </div>
            <div class="mt-3 text-[12px] text-slate-500 line-clamp-2">
              Community Health Nursing — programs and primary care.
            </div>
            <div class="mt-4 flex items-center gap-4 text-[11px] text-slate-500">
              <div class="flex items-center gap-1">
                <i data-lucide="calendar" class="h-3.5 w-3.5"></i>
                <span>Sat 8:00–12:00</span>
              </div>
              <div class="flex items-center gap-1">
                <i data-lucide="map-pin" class="h-3.5 w-3.5"></i>
                <span>Room 115</span>
              </div>
            </div>
          </a>
        </div>

        <!-- Empty state (uncomment if you prefer) -->
        <!--
        <div class="mt-4 rounded-2xl border border-slate-200 bg-white p-12 text-center">
          <div class="mx-auto h-12 w-12 rounded-full border border-slate-300 text-slate-400 flex items-center justify-center">
            <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor">
              <path d="M12 3 2 9l10 6 10-6-10-6Z" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
              <path d="M2 12v6c0 .6.4 1.1 1 1.4l9 4.6 9-4.6c.6-.3 1-.8 1-1.4v-6" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
          </div>
          <h3 class="mt-3 text-[15px] font-semibold text-slate-800">No sections yet</h3>
          <p class="mt-1 text-[13px] text-slate-500">Create a section or get assigned by the admin to start teaching.</p>
          <div class="mt-5">
            <a href="#" class="inline-block rounded-full border border-slate-300 px-4 py-2 text-[13px] font-medium hover:bg-slate-50">Create Section</a>
          </div>
        </div>
        -->
      </div>

      <!-- Recent Activity & Upcoming -->
      <div class="mt-8 grid gap-4 lg:grid-cols-2">
        <div class="rounded-2xl border border-slate-200 bg-white p-5">
          <h2 class="text-[15px] font-semibold text-slate-800">Recent Activity</h2>
          <ul class="mt-3 space-y-3">
            <li class="flex items-start gap-3">
              <span
                class="mt-0.5 inline-flex h-6 w-6 items-center justify-center rounded-lg bg-slate-100 text-slate-600">
                <i data-lucide="bell" class="h-4 w-4"></i>
              </span>
              <div class="text-[13px]">
                <div class="text-slate-800">You posted an announcement to NCMA216.</div>
                <div class="text-slate-500">2 hours ago</div>
              </div>
            </li>
            <li class="flex items-start gap-3">
              <span
                class="mt-0.5 inline-flex h-6 w-6 items-center justify-center rounded-lg bg-slate-100 text-slate-600">
                <i data-lucide="check-circle-2" class="h-4 w-4"></i>
              </span>
              <div class="text-[13px]">
                <div class="text-slate-800">Graded Return Demo for BSN 3-Y2-5.</div>
                <div class="text-slate-500">Yesterday</div>
              </div>
            </li>
            <li class="flex items-start gap-3">
              <span
                class="mt-0.5 inline-flex h-6 w-6 items-center justify-center rounded-lg bg-slate-100 text-slate-600">
                <i data-lucide="file-plus-2" class="h-4 w-4"></i>
              </span>
              <div class="text-[13px]">
                <div class="text-slate-800">Uploaded a new module to CHNN211.</div>
                <div class="text-slate-500">2 days ago</div>
              </div>
            </li>
          </ul>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-5">
          <h2 class="text-[15px] font-semibold text-slate-800">Upcoming RLE & Didactics</h2>
          <div class="mt-3 space-y-3">
            <div class="flex items-center justify-between rounded-xl border border-slate-200 p-3">
              <div class="flex items-center gap-3">
                <span class="inline-flex h-8 w-8 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600">
                  <i data-lucide="hospital" class="h-4 w-4"></i>
                </span>
                <div>
                  <div class="text-[13px] font-medium text-slate-800">RLE Duty — Antipolo District Hospital</div>
                  <div class="text-[12px] text-slate-500">Sep 30 • 6:00–14:00 • Ward 3B</div>
                </div>
              </div>
              <a href="#" class="text-[12px] text-slate-600 hover:text-slate-800">View</a>
            </div>

            <div class="flex items-center justify-between rounded-xl border border-slate-200 p-3">
              <div class="flex items-center gap-3">
                <span class="inline-flex h-8 w-8 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600">
                  <i data-lucide="calendar-days" class="h-4 w-4"></i>
                </span>
                <div>
                  <div class="text-[13px] font-medium text-slate-800">Didactic — NCMB312 Midterms Review</div>
                  <div class="text-[12px] text-slate-500">Oct 02 • 9:00–11:00 • Room 310</div>
                </div>
              </div>
              <a href="#" class="text-[12px] text-slate-600 hover:text-slate-800">View</a>
            </div>
          </div>
        </div>
      </div>


    </section>


  </main>
  @include('partials.faculty-footer')

  <!-- Lucide icons -->
  <script src="https://unpkg.com/lucide@latest"></script>
  <script> lucide.createIcons(); </script>
</body>

</html>