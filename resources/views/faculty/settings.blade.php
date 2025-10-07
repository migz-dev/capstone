<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
  <title>Account Settings · NurSync (CI)</title>
  @vite(['resources/css/app.css','resources/js/app.js'])
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
  <style>body{font-family:'Poppins', ui-sans-serif, system-ui, sans-serif}</style>
</head>

<body class="min-h-screen bg-slate-50">
  <main class="min-h-screen flex">
    {{-- Sidebar (highlight Settings) --}}
    @include('partials.faculty-sidebar', ['active' => 'settings'])

    {{-- Main content --}}
    <section class="flex-1">
      <div class="container mx-auto px-8 py-12">

        {{-- Alerts --}}
        @if(session('ok'))
          <div class="mb-4 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
            {{ session('ok') }}
          </div>
        @endif
        @if($errors->any())
          <div class="mb-4 rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-800">
            Please correct the errors below.
          </div>
        @endif

        {{-- Title --}}
        <div class="flex items-center gap-3">
          <span class="inline-flex items-center justify-center h-8 w-8 rounded-xl bg-slate-100 text-slate-700">
            <i data-lucide="sliders-horizontal" class="h-5 w-5"></i>
          </span>
          <h1 class="text-2xl font-bold">Account Settings</h1>
        </div>
        <p class="text-[13px] text-slate-500 mt-1">Manage your CI account info, avatar, and password.</p>

        {{-- Profile card --}}
        <div class="mt-6 rounded-2xl border border-slate-200/70 bg-white p-6">
          <div class="flex items-center gap-2">
            <span class="inline-flex h-6 w-6 items-center justify-center rounded-lg bg-slate-100 text-slate-700">
              <i data-lucide="user-round" class="h-4 w-4"></i>
            </span>
            <h2 class="text-[15px] font-semibold">Profile</h2>
          </div>

          @php
            /** @var \App\Models\Faculty|null $u */
            $u = auth('faculty')->user();
            $avatarUrl = $u?->avatar_url ?? null;   // add accessor on Faculty model
            $initials  = $u?->name ? strtoupper(mb_substr($u->name, 0, 1)) : 'F';
          @endphp

          <form class="mt-5 space-y-6" method="POST" action="{{ route('faculty.settings.profile') }}" enctype="multipart/form-data">
            @csrf

            {{-- Avatar --}}
            <div class="flex items-center gap-6">
              <div class="relative">
                @if($avatarUrl)
                  <img src="{{ $avatarUrl }}" alt="Profile photo"
                       class="h-[84px] w-[84px] rounded-full object-cover border border-slate-200">
                @else
                  <div class="h-[84px] w-[84px] rounded-full bg-slate-100 border border-slate-200
                              flex items-center justify-center text-slate-700 font-semibold">
                    {{ $initials }}
                  </div>
                @endif
              </div>

              <div class="space-y-2">
                <div class="text-[13px] text-slate-600">Profile Picture</div>
                <div class="flex items-center gap-3">
                  <label class="inline-flex cursor-pointer rounded-lg border border-slate-200 px-3 py-1.5 text-[13px] font-medium hover:bg-slate-50">
                    <input type="file" name="avatar" class="hidden" accept="image/*">
                    Upload new photo
                  </label>

                  @if($avatarUrl)
                    <button form="remove-avatar" type="submit" class="text-[13px] text-slate-600 hover:text-slate-900">
                      Remove
                    </button>
                  @endif
                </div>
                <p class="text-[12px] text-slate-400">Recommended: JPG/PNG/WebP, max 2MB.</p>
              </div>
            </div>

            {{-- Name & Email --}}
            <div class="grid gap-4 sm:grid-cols-2">
              <div class="sm:col-span-2">
                <label class="block text-[12px] text-slate-500 mb-1">Full Name</label>
                <input name="name" type="text" value="{{ old('name', $u?->name) }}"
                       class="w-full rounded-xl border border-slate-200 bg-slate-50/50 px-3 py-2 text-[14px] outline-none focus:border-slate-300">
                @error('name') <p class="mt-1 text-[12px] text-rose-600">{{ $message }}</p> @enderror
              </div>

              <div class="sm:col-span-2">
                <label class="block text-[12px] text-slate-500 mb-1">Email</label>
                <input type="email" value="{{ $u?->email }}" disabled
                       class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-[14px] text-slate-500">
                <p class="mt-1 text-[12px] text-slate-400">Email cannot be changed</p>
              </div>
            </div>

            <div>
              <button type="submit" class="w-full rounded-xl bg-black text-white py-2.5 text-[14px] font-semibold hover:bg-neutral-900">
                Update Profile
              </button>
            </div>
          </form>

          {{-- Separate DELETE form for removing avatar --}}
          <form id="remove-avatar" class="hidden" method="POST" action="{{ route('faculty.settings.avatar.remove') }}">
            @csrf
            @method('DELETE')
          </form>
        </div>

        {{-- Security card --}}
        <div class="mt-6 rounded-2xl border border-slate-200/70 bg-white p-6">
          <div class="flex items-center gap-2">
            <span class="inline-flex h-6 w-6 items-center justify-center rounded-lg bg-slate-100 text-slate-700">
              <i data-lucide="shield" class="h-4 w-4"></i>
            </span>
            <h2 class="text-[15px] font-semibold">Security</h2>
          </div>

          <form class="mt-5 grid gap-4" method="POST" action="{{ route('faculty.settings.password') }}">
            @csrf
            <div>
              <label class="block text-[12px] text-slate-500 mb-1">Current Password</label>
              <input name="current_password" type="password" placeholder="Enter your current password"
                     class="w-full rounded-xl border border-slate-200 px-3 py-2 text-[14px] outline-none focus:border-slate-300">
              @error('current_password') <p class="mt-1 text-[12px] text-rose-600">{{ $message }}</p> @enderror
            </div>
            <div>
              <label class="block text-[12px] text-slate-500 mb-1">New Password</label>
              <input name="password" type="password" placeholder="Enter new password"
                     class="w-full rounded-xl border border-slate-200 px-3 py-2 text-[14px] outline-none focus:border-slate-300">
              @error('password') <p class="mt-1 text-[12px] text-rose-600">{{ $message }}</p> @enderror
            </div>
            <div>
              <label class="block text-[12px] text-slate-500 mb-1">Confirm New Password</label>
              <input name="password_confirmation" type="password" placeholder="Confirm new password"
                     class="w-full rounded-xl border border-slate-200 px-3 py-2 text-[14px] outline-none focus:border-slate-300">
            </div>

            <div class="mt-1">
              <button type="submit" class="w-full rounded-xl bg-black text-white py-2.5 text-[14px] font-semibold hover:bg-neutral-900">
                Update Password
              </button>
            </div>
          </form>
        </div>

      </div>
    </section>
  </main>

  {{-- Lucide icons --}}
  <script src="https://unpkg.com/lucide@latest"></script>
  <script>lucide.createIcons();</script>
</body>
</html>
