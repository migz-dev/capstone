{{-- resources/views/admin/admin-settings.blade.php --}}
<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
  <title>Admin • Settings · NurSync</title>
      <link rel="icon" type="image/x-icon" href="{{ asset('CON_LOGO.ico') }}">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('CON_LOGO.ico') }}">
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
    {{-- Sidebar (optional highlight) --}}
    @include('partials.admin-sidebar', ['active' => 'settings'])

    {{-- Main --}}
    <section class="flex-1 min-w-0">
      {{-- Header --}}
      <header class="sticky top-0 z-30 bg-white/80 backdrop-blur border-b border-slate-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 h-14 flex items-center justify-between">
          <div class="flex items-center gap-3">
            <div class="inline-flex h-8 w-8 items-center justify-center rounded-xl bg-slate-900 text-white shadow-sm">
              <i data-lucide="settings" class="h-4 w-4"></i>
            </div>
            <div>
              <h1 class="text-[15px] sm:text-[16px] font-semibold leading-tight">Admin Settings</h1>
              <p class="text-[12px] text-slate-500 -mt-0.5">Update your profile, security, and notification preferences.
              </p>
            </div>
          </div>

          <div class="flex items-center gap-2">
            <button type="button"
              class="inline-flex items-center gap-2 rounded-xl bg-slate-100 text-slate-700 px-3 py-2 text-[13px] hover:bg-slate-200"
              data-modal-target="modalDiscard">
              <i data-lucide="rotate-ccw" class="h-4 w-4"></i>
              <span>Discard</span>
            </button>
            <button type="button"
              class="inline-flex items-center gap-2 rounded-xl bg-green-600 text-white px-3 py-2 text-[13px] font-medium shadow hover:bg-green-700"
              data-modal-target="modalSave">
              <i data-lucide="save" class="h-4 w-4"></i>
              <span>Save Changes</span>
            </button>
          </div>
        </div>
      </header>

      {{-- Content --}}
      <div class="max-w-7xl mx-auto px-4 sm:px-6 py-6 space-y-6">

        {{-- Profile --}}
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5">
          <div class="flex items-start gap-4">
            {{-- Avatar (image if available, otherwise initials) --}}
            <div class="relative">
              @if (!empty($avatarUrl))
                <img src="{{ $avatarUrl }}" alt="{{ $displayName }}" class="h-16 w-16 rounded-2xl object-cover">
              @else
                <div class="h-16 w-16 rounded-2xl bg-slate-100 flex items-center justify-center">
                  <span class="text-slate-600 font-semibold">{{ $initials }}</span>
                </div>
              @endif

              {{-- Change avatar trigger (uses your existing modal) --}}
              <button type="button"
                class="absolute -bottom-2 -right-2 h-8 w-8 rounded-xl bg-white border border-slate-200 shadow inline-flex items-center justify-center hover:bg-slate-50"
                data-modal-target="modalAvatar">
                <i data-lucide="camera" class="h-4 w-4"></i>
              </button>
            </div>

            {{-- Profile fields (bound to display vars) --}}
            <div class="flex-1 grid grid-cols-1 sm:grid-cols-2 gap-3">
              <div>
                <label class="text-[12px] text-slate-600">Full name</label>
                <input class="mt-1 w-full rounded-xl border-slate-200 px-3 py-2.5 text-sm" value="{{ $displayName }}"
                  placeholder="Full name">
              </div>

              <div>
                <label class="text-[12px] text-slate-600">Display name</label>
                <input class="mt-1 w-full rounded-xl border-slate-200 px-3 py-2.5 text-sm" placeholder="Optional">
              </div>

              <div>
                <label class="text-[12px] text-slate-600">Email</label>
                <div class="relative">
                  <input type="email" value="{{ $displayEmail }}"
                    class="mt-1 w-full rounded-xl border-slate-200 px-3 py-2.5 text-sm bg-slate-50 text-slate-600 cursor-not-allowed"
                    disabled>
                  <i data-lucide="lock" class="absolute right-3 top-1/2 -translate-y-1/2 h-4 w-4 text-slate-400"></i>
                </div>
                <p class="mt-1 text-[11px] text-slate-500">Email is managed by the system and cannot be changed.</p>
              </div>

              <div>
                <label class="text-[12px] text-slate-600">Phone (optional)</label>
                <input class="mt-1 w-full rounded-xl border-slate-200 px-3 py-2.5 text-sm"
                  placeholder="+63 9xx xxx xxxx">
              </div>
            </div>
          </div>
        </div>


        {{-- Security --}}
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5 space-y-4">
          <div class="flex items-center gap-3">
            <div class="h-9 w-9 rounded-xl bg-indigo-100 text-indigo-700 inline-flex items-center justify-center">
              <i data-lucide="shield" class="h-5 w-5"></i>
            </div>
            <div>
              <h2 class="text-[14px] font-semibold">Security</h2>
              <p class="text-[12px] text-slate-500 -mt-0.5">Manage password and 2FA for your account.</p>
            </div>
          </div>

          <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
            <div class="sm:col-span-1">
              <label class="text-[12px] text-slate-600">Current password</label>
              <input type="password" class="mt-1 w-full rounded-xl border-slate-200 px-3 py-2.5 text-sm"
                placeholder="••••••••">
            </div>
            <div>
              <label class="text-[12px] text-slate-600">New password</label>
              <input type="password" class="mt-1 w-full rounded-xl border-slate-200 px-3 py-2.5 text-sm"
                placeholder="••••••••">
            </div>
            <div>
              <label class="text-[12px] text-slate-600">Confirm new password</label>
              <input type="password" class="mt-1 w-full rounded-xl border-slate-200 px-3 py-2.5 text-sm"
                placeholder="••••••••">
            </div>
          </div>
        </div>
        {{-- Sessions --}}
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5">
          <div class="flex items-center gap-3 mb-4">
            <div class="h-9 w-9 rounded-xl bg-sky-100 text-sky-700 inline-flex items-center justify-center">
              <i data-lucide="monitor" class="h-5 w-5"></i>
            </div>
            <div>
              <h2 class="text-[14px] font-semibold">Active Sessions</h2>
              <p class="text-[12px] text-slate-500 -mt-0.5">Sign out devices you don’t recognize.</p>
            </div>
          </div>

          <div class="divide-y divide-slate-200 text-sm">
            {{-- Session row --}}
            <div class="py-3 flex items-center gap-3">
              <i data-lucide="laptop" class="h-4 w-4 text-slate-500"></i>
              <div class="flex-1">
                <div class="font-medium">Chrome • Windows</div>
                <div class="text-[12px] text-slate-500">Quezon City • Last active 2 mins ago • This device</div>
              </div>
              <button
                class="inline-flex items-center justify-center rounded-lg bg-orange-500 text-white p-2 hover:bg-orange-600"
                title="Sign out">
                <i data-lucide="log-out" class="h-4 w-4"></i>
              </button>
            </div>

            <div class="py-3 flex items-center gap-3">
              <i data-lucide="smartphone" class="h-4 w-4 text-slate-500"></i>
              <div class="flex-1">
                <div class="font-medium">Safari • iOS</div>
                <div class="text-[12px] text-slate-500">Makati • Last active 3 days ago</div>
              </div>
              <button
                class="inline-flex items-center justify-center rounded-lg bg-orange-500 text-white p-2 hover:bg-orange-600"
                title="Sign out">
                <i data-lucide="log-out" class="h-4 w-4"></i>
              </button>
            </div>
          </div>

          <div class="mt-4">
            <button
              class="inline-flex items-center gap-2 rounded-xl bg-white border border-slate-200 px-3 py-2 text-[13px] hover:bg-slate-50">
              <i data-lucide="shield-off" class="h-4 w-4"></i> Sign out of all other sessions
            </button>
          </div>
        </div>

        {{-- Turn Over Admin (static UI) --}}
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5 space-y-4">
          <div class="flex items-center gap-3">
            <div class="h-9 w-9 rounded-xl bg-violet-100 text-violet-700 inline-flex items-center justify-center">
              <i data-lucide="arrow-right-left" class="h-5 w-5"></i>
            </div>
            <div>
              <h2 class="text-[14px] font-semibold">Turn Over Admin Role</h2>
              <p class="text-[12px] text-slate-500 -mt-0.5">
                Transfer the Admin role to a Clinical Instructor. Your account will be downgraded to CI after a
                successful turnover.
              </p>
            </div>
          </div>

          <div class="space-y-4" id="turnoverStatic">
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
              <!-- Target CI (static) -->
              <div class="sm:col-span-2">
                <label class="text-[12px] text-slate-600">Clinical Instructor</label>
                <div class="relative">
                  <select class="mt-1 w-full rounded-xl border-slate-200 px-3 py-2.5 text-sm pr-9">
                    <option value="" selected disabled>Select CI…</option>
                    <option value="CI-10021">Joseph S. Alipio — CI-10021</option>
                    <option value="CI-10022">Amparo M. Angeles — CI-10022</option>
                    <option value="CI-10023">Rose Anne P. Santos — CI-10023</option>
                  </select>
                  <i data-lucide="chevron-down"
                    class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 h-4 w-4 text-slate-400"></i>
                </div>
                <p class="mt-1 text-[11px] text-slate-500">
                  Pick the faculty member (CI) who will receive the Admin role.
                </p>
              </div>

              <!-- Security confirmation (static) -->
              <div>
                <label class="text-[12px] text-slate-600">Your password</label>
                <input type="password" class="mt-1 w-full rounded-xl border-slate-200 px-3 py-2.5 text-sm"
                  placeholder="••••••••">
                <p class="mt-1 text-[11px] text-slate-500">We’ll verify ownership before turnover.</p>
              </div>
            </div>

            <div class="rounded-xl border border-amber-200 bg-amber-50 p-3 text-[13px] text-amber-800">
              <div class="flex items-start gap-2">
                <i data-lucide="alert-circle" class="h-4 w-4 mt-0.5"></i>
                <div>
                  <div class="font-medium">Important</div>
                  <ul class="list-disc pl-5 space-y-1 mt-1">
                    <li>New Admin will have full control immediately after confirmation.</li>
                    <li>Your account will switch to <span class="font-semibold">Clinical Instructor</span>.</li>
                    <li>This action is logged and requires your password.</li>
                  </ul>
                </div>
              </div>
            </div>

            <div class="flex items-center justify-end">
              <button type="button"
                class="inline-flex items-center gap-2 rounded-xl bg-violet-600 text-white px-3 py-2 text-[13px] font-medium shadow hover:bg-violet-700"
                data-modal-target="modalTurnoverConfirm">
                <i data-lucide="shield-check" class="h-4 w-4"></i>
                <span>Review & Confirm Turnover</span>
              </button>
            </div>
          </div>
        </div>

        {{-- Add Another Admin (static UI) --}}
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5 space-y-4">
          <div class="flex items-center gap-3">
            <div class="h-9 w-9 rounded-xl bg-green-100 text-green-700 inline-flex items-center justify-center">
              <i data-lucide="user-plus" class="h-5 w-5"></i>
            </div>
            <div>
              <h2 class="text-[14px] font-semibold">Add Another Admin</h2>
              <p class="text-[12px] text-slate-500 -mt-0.5">
                Assign administrative access to another faculty member. They will share the same privileges as you.
              </p>
            </div>
          </div>

          <div class="space-y-4" id="addAdminStatic">
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
              <!-- Select Faculty (static) -->
              <div class="sm:col-span-2">
                <label class="text-[12px] text-slate-600">Faculty Member</label>
                <div class="relative">
                  <select class="mt-1 w-full rounded-xl border-slate-200 px-3 py-2.5 text-sm pr-9">
                    <option value="" selected disabled>Select faculty…</option>
                    <option value="CI-10024">Miguel Caluya — CI-10024</option>
                    <option value="CI-10025">Alexa Domingo — CI-10025</option>
                    <option value="CI-10026">Diane Taboco Patani — CI-10026</option>
                  </select>
                  <i data-lucide="chevron-down"
                    class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 h-4 w-4 text-slate-400"></i>
                </div>
                <p class="mt-1 text-[11px] text-slate-500">Choose a faculty member to promote as another Admin.</p>
              </div>

              <!-- Security confirmation (static) -->
              <div>
                <label class="text-[12px] text-slate-600">Your password</label>
                <input type="password" class="mt-1 w-full rounded-xl border-slate-200 px-3 py-2.5 text-sm"
                  placeholder="••••••••">
                <p class="mt-1 text-[11px] text-slate-500">We’ll verify before adding another Admin.</p>
              </div>
            </div>

            <div class="rounded-xl border border-amber-200 bg-amber-50 p-3 text-[13px] text-amber-800">
              <div class="flex items-start gap-2">
                <i data-lucide="alert-circle" class="h-4 w-4 mt-0.5"></i>
                <div>
                  <div class="font-medium">Note</div>
                  <ul class="list-disc pl-5 space-y-1 mt-1">
                    <li>Additional Admins have the same full control as you.</li>
                    <li>Limit this privilege to trusted faculty members.</li>
                    <li>This action will be logged by the system.</li>
                  </ul>
                </div>
              </div>
            </div>

            <div class="flex items-center justify-end">
              <button type="button"
                class="inline-flex items-center gap-2 rounded-xl bg-green-600 text-white px-3 py-2 text-[13px] font-medium shadow hover:bg-green-700"
                data-modal-target="modalAddAdminConfirm">
                <i data-lucide="check-circle" class="h-4 w-4"></i>
                <span>Review & Confirm Add</span>
              </button>
            </div>
          </div>
        </div>

        {{-- Change School Year / Term --}}
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5 space-y-4">
          <div class="flex items-center gap-3">
            <div class="h-9 w-9 rounded-xl bg-sky-100 text-sky-700 inline-flex items-center justify-center">
              <i data-lucide="calendar" class="h-5 w-5"></i>
            </div>
            <div>
              <h2 class="text-[14px] font-semibold">Change School Year / Term</h2>
              <p class="text-[12px] text-slate-500 -mt-0.5">
                Update the current academic year and semester. This will be used later for registration revalidation.
              </p>
            </div>
          </div>

          <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
            <div>
              <label class="text-[12px] text-slate-600">School Year</label>
              <div class="relative">
                <select class="mt-1 w-full rounded-xl border-slate-200 px-3 py-2.5 text-sm pr-9">
                  <option selected disabled>Select year</option>
                  <option>2025–2026</option>
                  <option>2026–2027</option>
                </select>
                <i data-lucide="chevron-down"
                  class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 h-4 w-4 text-slate-400"></i>
              </div>
            </div>

            <div>
              <label class="text-[12px] text-slate-600">Semester / Term</label>
              <div class="relative">
                <select class="mt-1 w-full rounded-xl border-slate-200 px-3 py-2.5 text-sm pr-9">
                  <option selected disabled>Select term</option>
                  <option>1st Semester</option>
                  <option>2nd Semester</option>
                  <option>Summer Term</option>
                </select>
                <i data-lucide="chevron-down"
                  class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 h-4 w-4 text-slate-400"></i>
              </div>
            </div>

            <div class="flex items-end">
              <button type="button"
                class="w-full inline-flex items-center gap-2 rounded-xl bg-sky-600 text-white px-3 py-2 text-[13px] font-medium shadow hover:bg-sky-700"
                data-modal-target="modalSchoolYearConfirm">
                <i data-lucide="refresh-ccw" class="h-4 w-4"></i>
                <span>Apply</span>
              </button>
            </div>
          </div>
        </div>

        {{-- Danger zone --}}
        <div class="bg-white rounded-2xl border border-rose-200 shadow-sm p-5">
          <div class="flex items-center gap-3">
            <div class="h-9 w-9 rounded-xl bg-rose-100 text-rose-700 inline-flex items-center justify-center">
              <i data-lucide="alert-triangle" class="h-5 w-5"></i>
            </div>
            <div class="flex-1">
              <h2 class="text-[14px] font-semibold text-rose-700">Danger zone</h2>
              <p class="text-[12px] text-rose-600 -mt-0.5">Irreversible or sensitive actions.</p>
            </div>
            <button
              class="inline-flex items-center gap-2 rounded-xl bg-rose-600 text-white px-3 py-2 text-[13px] hover:bg-rose-700"
              data-modal-target="modalReset2FA">
              <i data-lucide="key-round" class="h-4 w-4"></i> Reset 2FA
            </button>
          </div>
        </div>



      </div>
    </section>
  </main>

  {{-- Shared footer --}}
  @include('partials.admin-footer')

  {{-- ===== Modals (static only) ===== --}}
  {{-- Save --}}
  <div id="modalSave" class="hidden fixed inset-0 z-50">
    <div class="absolute inset-0 bg-black/40"></div>
    <div class="relative mx-auto mt-24 w-full max-w-md">
      <div class="bg-white rounded-2xl shadow-xl border border-slate-200 p-5">
        <div class="flex items-center gap-3">
          <div class="h-9 w-9 rounded-xl bg-green-100 text-green-700 inline-flex items-center justify-center">
            <i data-lucide="check-circle" class="h-5 w-5"></i>
          </div>
          <div>
            <h3 class="text-[15px] font-semibold">Save changes?</h3>
            <p class="text-[13px] text-slate-600">Your profile and preferences will be updated.</p>
          </div>
          <button class="ml-auto p-2 rounded-lg hover:bg-slate-100" data-modal-close>
            <i data-lucide="x" class="h-5 w-5"></i>
          </button>
        </div>
        <div class="mt-5 flex items-center justify-end gap-2">
          <button class="px-3 py-2 rounded-xl text-[13px] border border-slate-200 bg-white hover:bg-slate-50"
            data-modal-close>Cancel</button>
          <button
            class="px-3 py-2 rounded-xl text-[13px] bg-green-600 text-white hover:bg-green-700 inline-flex items-center gap-2">
            <i data-lucide="save" class="h-4 w-4"></i> Save
          </button>
        </div>
      </div>
    </div>
  </div>

  {{-- Discard --}}
  <div id="modalDiscard" class="hidden fixed inset-0 z-50">
    <div class="absolute inset-0 bg-black/40"></div>
    <div class="relative mx-auto mt-24 w-full max-w-md">
      <div class="bg-white rounded-2xl shadow-xl border border-slate-200 p-5">
        <div class="flex items-center gap-3">
          <div class="h-9 w-9 rounded-xl bg-slate-100 text-slate-700 inline-flex items-center justify-center">
            <i data-lucide="rotate-ccw" class="h-5 w-5"></i>
          </div>
          <div>
            <h3 class="text-[15px] font-semibold">Discard unsaved changes?</h3>
            <p class="text-[13px] text-slate-600">This will revert the fields in this page.</p>
          </div>
          <button class="ml-auto p-2 rounded-lg hover:bg-slate-100" data-modal-close>
            <i data-lucide="x" class="h-5 w-5"></i>
          </button>
        </div>
        <div class="mt-5 flex items-center justify-end gap-2">
          <button class="px-3 py-2 rounded-xl text-[13px] border border-slate-200 bg-white hover:bg-slate-50"
            data-modal-close>Cancel</button>
          <button
            class="px-3 py-2 rounded-xl text-[13px] bg-slate-900 text-white hover:bg-black/90 inline-flex items-center gap-2"
            data-modal-close>
            <i data-lucide="check" class="h-4 w-4"></i> Discard
          </button>
        </div>
      </div>
    </div>
  </div>

  {{-- Avatar (functional) --}}
{{-- Avatar (functional, no nested forms) --}}
<div id="modalAvatar" class="hidden fixed inset-0 z-50">
  <div class="absolute inset-0 bg-black/40"></div>
  <div class="relative mx-auto mt-24 w-full max-w-md">
    <div class="bg-white rounded-2xl shadow-xl border border-slate-200 p-5">
      <div class="flex items-center justify-between">
        <h3 class="text-[15px] font-semibold">Change avatar</h3>
        <button class="p-2 rounded-lg hover:bg-slate-100" data-modal-close>
          <i data-lucide="x" class="h-5 w-5"></i>
        </button>
      </div>

      {{-- Upload form --}}
      <form method="POST"
            action="{{ route('admin.settings.avatar.upload') }}"
            enctype="multipart/form-data"
            id="avatarUploadForm"
            class="mt-4 space-y-3 text-sm">
        @csrf

        {{-- Preview --}}
        <div class="flex items-center gap-3">
          <div class="h-12 w-12 rounded-xl bg-slate-100 overflow-hidden flex items-center justify-center">
            @if (!empty($avatarUrl))
              <img id="avatarPreview" src="{{ $avatarUrl }}" class="h-full w-full object-cover" alt="Avatar preview">
            @else
              <img id="avatarPreview" src="" class="hidden h-full w-full object-cover" alt="Avatar preview">
              <i id="avatarPreviewIcon" data-lucide="user" class="h-5 w-5 text-slate-400"></i>
            @endif
          </div>
          <div class="min-w-0">
            <div class="text-[13px] font-medium text-slate-800 leading-tight truncate">{{ $displayName ?? 'Admin' }}</div>
            <div class="text-[11px] text-slate-500 truncate">PNG/JPG up to 2MB. Square images look best.</div>
          </div>
        </div>

        {{-- File input --}}
        <input type="file" name="avatar" accept="image/*"
               class="w-full rounded-xl border-slate-200 px-3 py-2.5 text-sm" required>

        @error('avatar')
          <p class="text-[12px] text-rose-600">{{ $message }}</p>
        @enderror

        <div class="mt-4 flex items-center justify-end gap-2">
          <button type="button"
                  class="px-3 py-2 rounded-xl text-[13px] border border-slate-200 bg-white hover:bg-slate-50"
                  data-modal-close>Cancel</button>
          <button type="submit"
                  class="px-3 py-2 rounded-xl text-[13px] bg-green-600 text-white hover:bg-green-700 inline-flex items-center gap-2">
            <i data-lucide="check" class="h-4 w-4"></i> Upload
          </button>
        </div>
      </form>

      {{-- Separate "Remove" form (NOT nested) --}}
      @if (!empty($avatarUrl))
        <form method="POST" action="{{ route('admin.settings.avatar.remove') }}"
              class="mt-3 flex justify-end"
              onsubmit="return confirm('Remove current photo?');">
          @csrf @method('DELETE')
          <button type="submit" class="text-[12px] text-slate-500 hover:text-rose-600">
            Remove current photo
          </button>
        </form>
      @endif
    </div>
  </div>
</div>



  {{-- 2FA --}}
  <div id="modal2FA" class="hidden fixed inset-0 z-50">
    <div class="absolute inset-0 bg-black/40"></div>
    <div class="relative mx-auto mt-24 w-full max-w-md">
      <div class="bg-white rounded-2xl shadow-xl border border-slate-200 p-5">
        <div class="flex items-center justify-between">
          <h3 class="text-[15px] font-semibold">Configure 2FA</h3>
          <button class="p-2 rounded-lg hover:bg-slate-100" data-modal-close><i data-lucide="x"
              class="h-5 w-5"></i></button>
        </div>
        <ol class="mt-4 text-sm list-decimal pl-5 space-y-2">
          <li>Install an authenticator app (Authy, Google Authenticator).</li>
          <li>Scan the QR code or enter the provided key.</li>
          <li>Enter the 6-digit code to verify.</li>
        </ol>
        <div
          class="mt-4 h-28 rounded-xl bg-slate-100 border border-dashed border-slate-300 flex items-center justify-center text-slate-500 text-sm">
          QR code placeholder
        </div>
        <div class="mt-4">
          <input class="w-full rounded-xl border-slate-200 px-3 py-2.5 text-sm" placeholder="Enter 6-digit code">
        </div>
        <div class="mt-5 flex items-center justify-end gap-2">
          <button class="px-3 py-2 rounded-xl text-[13px] border border-slate-200 bg-white hover:bg-slate-50"
            data-modal-close>Cancel</button>
          <button
            class="px-3 py-2 rounded-xl text-[13px] bg-slate-900 text-white hover:bg-black/90 inline-flex items-center gap-2">
            <i data-lucide="lock" class="h-4 w-4"></i> Enable 2FA
          </button>
        </div>
      </div>
    </div>
  </div>





  {{-- Reset 2FA (danger) --}}
  <div id="modalReset2FA" class="hidden fixed inset-0 z-50">
    <div class="absolute inset-0 bg-black/40"></div>
    <div class="relative mx-auto mt-24 w-full max-w-md">
      <div class="bg-white rounded-2xl shadow-xl border border-rose-200 p-5">
        <div class="flex items-center gap-3">
          <div class="h-9 w-9 rounded-xl bg-rose-100 text-rose-700 inline-flex items-center justify-center">
            <i data-lucide="alert-triangle" class="h-5 w-5"></i>
          </div>
          <div>
            <h3 class="text-[15px] font-semibold">Reset 2FA?</h3>
            <p class="text-[13px] text-slate-600">This will remove 2FA from this account.</p>
          </div>
          <button class="ml-auto p-2 rounded-lg hover:bg-slate-100" data-modal-close>
            <i data-lucide="x" class="h-5 w-5"></i>
          </button>
        </div>
        <div class="mt-5 flex items-center justify-end gap-2">
          <button class="px-3 py-2 rounded-xl text-[13px] border border-slate-200 bg-white hover:bg-slate-50"
            data-modal-close>Cancel</button>
          <button
            class="px-3 py-2 rounded-xl text-[13px] bg-rose-600 text-white hover:bg-rose-700 inline-flex items-center gap-2">
            <i data-lucide="key-round" class="h-4 w-4"></i> Reset 2FA
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
{{-- Tiny preview script --}}
<script>
  (function () {
    const form = document.getElementById('avatarUploadForm');
    if (!form) return;
    const input = form.querySelector('input[type="file"][name="avatar"]');
    const img   = document.getElementById('avatarPreview');
    const icon  = document.getElementById('avatarPreviewIcon');
    input?.addEventListener('change', (e) => {
      const file = e.target.files?.[0];
      if (!file) return;
      const url = URL.createObjectURL(file);
      if (img) { img.src = url; img.classList.remove('hidden'); }
      if (icon) icon.classList.add('hidden');
    });
  })();
</script>
</body>

</html>