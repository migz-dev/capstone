{{-- resources/views/partials/faculty-sidebar.blade.php --}}

@php
  /** Detect active item (allow override via $active) */
  $active = $active ?? '';
  if ($active === '') {
    if (request()->routeIs('faculty.dashboard'))
      $active = 'dashboard';
    elseif (request()->routeIs('faculty.skills.*'))
      $active = 'skills';
    elseif (request()->routeIs('faculty.rd.create'))
      $active = 'rd_create';
    elseif (request()->routeIs('faculty.rd.manage') || request()->routeIs('faculty.rd.manage.*'))
      $active = 'rd_manage';
    elseif (request()->routeIs('faculty.rd.scoring') || request()->routeIs('faculty.rd.scoring.*'))
      $active = 'rd_scoring';
    elseif (request()->routeIs('faculty.reports.*'))
      $active = 'reports';
    elseif (request()->routeIs('faculty.announcements.*'))
      $active = 'announcements';
    elseif (request()->routeIs('faculty.modules.*'))
      $active = 'modules';
    elseif (request()->routeIs('faculty.notifications'))
      $active = 'notifications';
    elseif (request()->routeIs('faculty.settings'))
      $active = 'settings';
    /* ✅ NEW: Drug Guide */
    elseif (request()->routeIs('faculty.drug_guide.*'))
      $active = 'drug_guide';
  }

  /** Link styles */
  $activeLink = 'flex items-center rounded-2xl px-4 py-3 text-white bg-[#009b56]';
  $inactiveLink = 'flex items-center gap-3 rounded-xl px-3 py-2 text-slate-700 hover:bg-slate-100';

  /** User (faculty guard) */
  $u = auth('faculty')->user();
  $initials = $u?->name ? strtoupper(mb_substr($u->name, 0, 1)) : 'F';
  $displayName = $u->name ?? 'Faculty User';
  $displayEmail = $u->email ?? 'faculty@sys.test.ph';

  /** Graceful route helper */
  $r = fn($name, $fallback) => \Illuminate\Support\Facades\Route::has($name) ? route($name) : url($fallback);
@endphp


<aside class="w-[280px] bg-white border-r border-slate-200 flex flex-col">
  <div class="p-4 flex-1">
    <!-- Brand -->
    <div class="flex items-center gap-2 mb-4">
      <img src="/assets/images/CON_LOGO.png" class="h-5 w-5 rounded-md" alt="NurSync">
      <div class="text-[17px] font-semibold text-slate-900">NurSync • CI</div>
    </div>

    <!-- Profile -->
    <!-- Profile -->
    <div class="flex items-center gap-3 p-3 rounded-2xl border border-slate-200">
      @php
        /** @var \App\Models\Faculty|null $u */
        $u = auth('faculty')->user();
        $avatar = $u?->avatar_url;         // from Faculty accessor
        $initials = $u?->initials ?? 'F';  // from Faculty accessor
        $displayName = $u?->name ?: 'Faculty User';
        $displayEmail = $u?->email ?: 'faculty@sys.test.ph';
      @endphp

      @if($avatar)
        <img src="{{ $avatar }}" alt="Profile" class="h-10 w-10 rounded-full object-cover border border-slate-200">
      @else
        <div class="h-10 w-10 rounded-full bg-slate-100 flex items-center justify-center
                        text-[12px] font-semibold text-slate-700 border border-slate-200">
          {{ $initials }}
        </div>
      @endif

      <div class="min-w-0">
        <div class="text-[13px] font-semibold text-slate-800 leading-tight truncate" title="{{ $displayName }}">
          {{ $displayName }}
        </div>
        <div class="text-[11px] text-slate-500 truncate" title="{{ $displayEmail }}">
          {{ $displayEmail }}
        </div>
      </div>
    </div>


    <div class="-mx-4 my-3 h-px bg-slate-200/70"></div>

    <!-- Quick settings / logout -->
    <div class="flex justify-center text-[13px] text-slate-600 mt-3">
      <div class="flex items-center gap-6">
        <a href="{{ $r('faculty.settings', '/faculty/settings') }}"
          class="flex items-center gap-2 hover:text-slate-900">
          <i data-lucide="user-cog" class="h-4 w-4"></i>
          <span>Settings</span>
        </a>
        <form method="POST" action="{{ $r('faculty.logout', '/faculty/logout') }}" class="contents">
          @csrf
          <button type="submit" class="flex items-center gap-2 hover:text-slate-900">
            <i data-lucide="log-out" class="h-4 w-4"></i>
            <span>Log out</span>
          </button>
        </form>
      </div>
    </div>

    <div class="-mx-4 my-3 h-px bg-slate-200/70"></div>

    <!-- Nav -->
    <nav class="mt-4 space-y-2">
      <div class="px-2 py-1 text-slate-500 font-medium">Main</div>

      <!-- Dashboard -->
      <a href="{{ $r('faculty.dashboard', '/faculty/dashboard') }}"
        class="{{ $active === 'dashboard' ? $activeLink : $inactiveLink }}"
        aria-current="{{ $active === 'dashboard' ? 'page' : 'false' }}">
        <span class="flex items-center gap-3">
          <i data-lucide="layout-dashboard"
            class="h-5 w-5 {{ $active === 'dashboard' ? 'text-white' : 'text-gray-500' }}"></i>
          <span class="text-[14px]">Dashboard</span>
        </span>
      </a>

      <div class="px-2 py-1 mt-3 text-slate-500 font-medium">Nurse Assistance</div>

      <!-- Procedure Library -->
      <a href="{{ $r('faculty.procedures.index', '/faculty/procedures') }}"
        class="{{ $active === 'procedures' ? $activeLink : $inactiveLink }}"
        aria-current="{{ $active === 'procedures' ? 'page' : 'false' }}">
        <span class="flex items-center gap-3">
          <i data-lucide="stethoscope"
            class="h-5 w-5 {{ $active === 'procedures' ? 'text-white' : 'text-gray-500' }}"></i>
          <span class="text-[14px]">Procedure Library</span>
        </span>
      </a>

      <!-- ✅ Drug Guide -->
      <a href="{{ $r('faculty.drug_guide.index', '/faculty/drug-guide') }}"
        class="{{ $active === 'drug_guide' ? $activeLink : $inactiveLink }}"
        aria-current="{{ $active === 'drug_guide' ? 'page' : 'false' }}">
        <span class="flex items-center gap-3">
          <i data-lucide="pill" class="h-5 w-5 {{ $active === 'drug_guide' ? 'text-white' : 'text-gray-500' }}"></i>
          <span class="text-[14px]">Drug Guide</span>
        </span>
      </a>

      <!-- ✅ Diseases Library -->
      <a href="{{ $r('faculty.diseases.index', '/faculty/diseases') }}"
        class="{{ $active === 'diseases' ? $activeLink : $inactiveLink }}"
        aria-current="{{ $active === 'diseases' ? 'page' : 'false' }}">
        <span class="flex items-center gap-3">
          <i data-lucide="activity-square"
            class="h-5 w-5 {{ $active === 'diseases' ? 'text-white' : 'text-gray-500' }}"></i>
          <span class="text-[14px]">Diseases</span>
        </span>
      </a>
      <!-- Simulation Assist -->
      <a href="{{ $r('faculty.simulation.index', '/faculty/simulation-assist') }}"
        class="{{ $active === 'simulation' ? $activeLink : $inactiveLink }}"
        aria-current="{{ $active === 'simulation' ? 'page' : 'false' }}">
        <span class="flex items-center gap-3">
          <i data-lucide="activity" class="h-5 w-5 {{ $active === 'simulation' ? 'text-white' : 'text-gray-500' }}"></i>
          <span class="text-[14px]">Simulation Assist</span>
        </span>
      </a>
      <!-- Chartings -->
      <a href="{{ $r('faculty.chartings.index', '/faculty/chartings') }}"
        class="{{ $active === 'chartings' ? $activeLink : $inactiveLink }}"
        aria-current="{{ $active === 'chartings' ? 'page' : 'false' }}">
        <span class="flex items-center gap-3">
          <i data-lucide="clipboard-list"
            class="h-5 w-5 {{ $active === 'chartings' ? 'text-white' : 'text-gray-500' }}"></i>
          <span class="text-[14px]">Chartings</span>
        </span>
      </a>
    </nav>

</aside>