{{-- resources/views/partials/sidebar.blade.php --}}
@php
  use Illuminate\Support\Facades\Route;

  /* ---------- Safe route helper (fallback to URL if named route is missing) ---------- */
  $r = function (string $name, string $fallback) {
    return Route::has($name) ? route($name) : url($fallback);
  };

  /* ---------- Active tab (derive if not passed) ---------- */
  $active = $active ?? '';
  if ($active === '') {
    if (request()->routeIs('student.dashboard') || request()->routeIs('student.home')) {
      $active = 'dashboard';
    } elseif (request()->routeIs('student.return-demo')) {
      $active = 'rd';
    } elseif (request()->routeIs('student.procedures*')) {
      $active = 'procedures';
    } elseif (request()->routeIs('student.feedback*')) {
      $active = 'feedback';
    }
  }

  /* ---------- Link styles ---------- */
  $activeLink   = 'flex items-center rounded-2xl px-4 py-3 text-white bg-[#009b56]';
  $inactiveLink = 'flex items-center gap-3 rounded-xl px-3 py-2 text-slate-700 hover:bg-slate-100';

  /* ---------- User display (works in design mode) ---------- */
  $u            = auth()->user();
  $avatarUrl    = $u?->avatar_url ?? null;     // accessor from User model
  $initials     = $u?->initials   ?? 'MC';     // accessor fallback
  $displayName  = $u?->display_name  ?? 'Miguel Caluya';
  $displayEmail = $u?->display_email ?? 'mscaluya7818ant@student…';

  /* ---------- URLs ---------- */
  $dashboardUrl = $r('student.dashboard', '/student/dashboard');
  $settingsUrl  = $r('student.settings',  '/student/settings');
  $rdUrl        = $r('student.return-demo', '/student/return-demo');
  $guidesUrl    = $r('student.procedures',  '/student/procedures');
  $feedbackUrl  = $r('student.feedback',    '/student/feedback');
@endphp

<aside class="w-[280px] bg-white border-r border-slate-200/70 flex flex-col">
  <div class="p-4 flex-1">
    <!-- Brand row -->
    <div class="flex items-center gap-2 mb-4">
      <svg class="h-5 w-5 text-slate-700" viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true">
        <path d="m15 19-7-7 7-7" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" />
      </svg>
      <div class="text-[17px] font-semibold text-slate-900">NurSync — Nurse Assistance</div>
    </div>

    <!-- Profile -->
    <div class="flex items-center gap-3 p-3 rounded-2xl border border-slate-200/70">
      @if($avatarUrl)
        <img src="{{ $avatarUrl }}" alt="Profile photo" class="h-10 w-10 rounded-full object-cover" />
      @else
        <div class="h-10 w-10 rounded-full bg-slate-100 flex items-center justify-center text-[12px] font-semibold text-slate-700">
          {{ $initials }}
        </div>
      @endif
      <div class="min-w-0">
        <div class="text-[13px] font-semibold text-slate-800 leading-tight truncate">{{ $displayName }}</div>
        <div class="text-[11px] text-slate-500 truncate">{{ $displayEmail }}</div>
      </div>
    </div>

    <div class="-mx-4 my-3 h-px bg-slate-200/70"></div>

    <!-- Settings / Logout -->
    <div class="flex justify-center text-[13px] text-slate-600 mt-3">
      <div class="flex items-center gap-6">
        <a href="{{ $settingsUrl }}" class="flex items-center gap-2 hover:text-slate-900">
          <i data-lucide="user-cog" class="h-4 w-4"></i>
          <span>Settings</span>
        </a>

        @auth
          <form method="POST" action="{{ route('logout') }}" class="contents">
            @csrf
            <button type="submit" class="flex items-center gap-2 hover:text-slate-900">
              <i data-lucide="log-out" class="h-4 w-4"></i>
              <span>Log out</span>
            </button>
          </form>
        @endauth
      </div>
    </div>

    <div class="-mx-4 my-3 h-px bg-slate-200/70"></div>

    <!-- Nav (Student = VIEW ONLY) -->
    <nav class="mt-4 space-y-2">
      <!-- Dashboard -->
      <a href="{{ $dashboardUrl }}"
         class="{{ $active === 'dashboard' ? $activeLink : $inactiveLink }}"
         aria-current="{{ $active === 'dashboard' ? 'page' : 'false' }}">
        <span class="flex items-center gap-3">
          <i data-lucide="layout-dashboard" class="h-5 w-5 {{ $active === 'dashboard' ? 'text-white' : 'text-gray-500' }}"></i>
          <span class="text-[14px]">Dashboard</span>
        </span>
      </a>

      <!-- Return Demos -->
      <a href="{{ $rdUrl }}"
         class="{{ $active === 'rd' ? $activeLink : $inactiveLink }}"
         aria-current="{{ $active === 'rd' ? 'page' : 'false' }}">
        <span class="flex items-center gap-3">
          <i data-lucide="clipboard-list" class="h-5 w-5 {{ $active === 'rd' ? 'text-white' : 'text-gray-500' }}"></i>
          <span class="text-[14px]">Return Demos</span>
        </span>
      </a>

      <!-- Procedures Library -->
      <a href="{{ $guidesUrl }}"
         class="{{ $active === 'procedures' ? $activeLink : $inactiveLink }}"
         aria-current="{{ $active === 'procedures' ? 'page' : 'false' }}">
        <span class="flex items-center gap-3">
          <i data-lucide="library" class="h-5 w-5 {{ $active === 'procedures' ? 'text-white' : 'text-gray-500' }}"></i>
          <span class="text-[14px]">Procedures Library</span>
        </span>
      </a>
    </nav>
  </div>
</aside>
