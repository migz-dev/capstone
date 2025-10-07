<section class="flex-1 px-6 lg:px-8 py-10">
  <!-- Header / utilities -->
  <div class="flex items-center justify-between">
    <div class="flex items-center gap-3">
      <span class="inline-flex items-center justify-center h-8 w-8 rounded-xl bg-green-50 text-green-600">
        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true">
          <path d="M12 3l7 4v5c0 5-3.5 9-7 9s-7-4-7-9V7l7-4Z" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
          <path d="M9.5 12.5l1.75 1.75L15 10.5" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
      </span>
      <div>
        <h1 class="text-2xl font-bold">Admin Dashboard</h1>
        <p class="text-[13px] text-slate-500 mt-0.5">Welcome, Administrator</p>
      </div>
    </div>
  </div>

  <!-- Stat cards -->
  <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3 mt-6">
    <div class="rounded-2xl border border-slate-200 bg-white p-5">
      <div class="text-[13px] font-medium text-slate-700">Total Students</div>
      <div class="mt-2 text-3xl font-bold">0</div>
      <p class="mt-1 text-[12px] text-slate-500">Across all programs</p>
    </div>

    <div class="rounded-2xl border border-slate-200 bg-white p-5">
      <div class="text-[13px] font-medium text-slate-700">Total Faculty</div>
      <div class="mt-2 text-3xl font-bold">0</div>
      <p class="mt-1 text-[12px] text-slate-500">Active teaching staff</p>
    </div>

    <div class="rounded-2xl border border-slate-200 bg-white p-5">
      <div class="text-[13px] font-medium text-slate-700">Active Offerings</div>
      <div class="mt-2 text-3xl font-bold">0</div>
      <p class="mt-1 text-[12px] text-slate-500">This term</p>
    </div>

    <div class="rounded-2xl border border-slate-200 bg-white p-5">
      <div class="text-[13px] font-medium text-slate-700">Pending Approvals</div>
      <div class="mt-2 text-3xl font-bold">0</div>
      <p class="mt-1 text-[12px] text-slate-500">Faculty/Announcements/RLE</p>
    </div>

    <div class="rounded-2xl border border-slate-200 bg-white p-5">
      <div class="text-[13px] font-medium text-slate-700">Storage Used</div>
      <div class="mt-2 text-3xl font-bold">0 GB</div>
      <p class="mt-1 text-[12px] text-slate-500">Resources repository</p>
    </div>

    <div class="rounded-2xl border border-slate-200 bg-white p-5">
      <div class="text-[13px] font-medium text-slate-700">System Health</div>
      <div class="mt-2 text-3xl font-bold">OK</div>
      <p class="mt-1 text-[12px] text-slate-500">Queues & jobs status</p>
    </div>
  </div>

  <!-- Quick actions -->
  <div class="mt-8">
    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
      <a href="#" class="rounded-2xl border border-slate-200 bg-white p-6 hover:bg-slate-50 transition">
        <div class="flex items-center gap-3">
          <span class="inline-flex h-9 w-9 items-center justify-center rounded-xl bg-slate-100 text-slate-700">
            <i data-lucide="users" class="h-5 w-5"></i>
          </span>
          <div>
            <div class="text-sm font-semibold text-slate-800">Manage Faculty</div>
            <p class="text-[12px] text-slate-500">Approve, assign courses</p>
          </div>
        </div>
      </a>

      <a href="#" class="rounded-2xl border border-slate-200 bg-white p-6 hover:bg-slate-50 transition">
        <div class="flex items-center gap-3">
          <span class="inline-flex h-9 w-9 items-center justify-center rounded-xl bg-slate-100 text-slate-700">
            <i data-lucide="graduation-cap" class="h-5 w-5"></i>
          </span>
          <div>
            <div class="text-sm font-semibold text-slate-800">Manage Students</div>
            <p class="text-[12px] text-slate-500">Enroll, sections, status</p>
          </div>
        </div>
      </a>

      <a href="#" class="rounded-2xl border border-slate-200 bg-white p-6 hover:bg-slate-50 transition">
        <div class="flex items-center gap-3">
          <span class="inline-flex h-9 w-9 items-center justify-center rounded-xl bg-slate-100 text-slate-700">
            <i data-lucide="book-open" class="h-5 w-5"></i>
          </span>
          <div>
            <div class="text-sm font-semibold text-slate-800">Course Offerings</div>
            <p class="text-[12px] text-slate-500">Create, assign, archive</p>
          </div>
        </div>
      </a>

      <a href="#" class="rounded-2xl border border-slate-200 bg-white p-6 hover:bg-slate-50 transition">
        <div class="flex items-center gap-3">
          <span class="inline-flex h-9 w-9 items-center justify-center rounded-xl bg-slate-100 text-slate-700">
            <i data-lucide="stethoscope" class="h-5 w-5"></i>
          </span>
          <div>
            <div class="text-sm font-semibold text-slate-800">RLE Duties</div>
            <p class="text-[12px] text-slate-500">Schedule & notifications</p>
          </div>
        </div>
      </a>

      <a href="#" class="rounded-2xl border border-slate-200 bg-white p-6 hover:bg-slate-50 transition">
        <div class="flex items-center gap-3">
          <span class="inline-flex h-9 w-9 items-center justify-center rounded-xl bg-slate-100 text-slate-700">
            <i data-lucide="megaphone" class="h-5 w-5"></i>
          </span>
          <div>
            <div class="text-sm font-semibold text-slate-800">Announcements</div>
            <p class="text-[12px] text-slate-500">Post to students & faculty</p>
          </div>
        </div>
      </a>

      <a href="#" class="rounded-2xl border border-slate-200 bg-white p-6 hover:bg-slate-50 transition">
        <div class="flex items-center gap-3">
          <span class="inline-flex h-9 w-9 items-center justify-center rounded-xl bg-slate-100 text-slate-700">
            <i data-lucide="folder-open" class="h-5 w-5"></i>
          </span>
          <div>
            <div class="text-sm font-semibold text-slate-800">Learning Resources</div>
            <p class="text-[12px] text-slate-500">Upload PDFs, PPTs, videos</p>
          </div>
        </div>
      </a>
    </div>
  </div>
  </div>
</section>
