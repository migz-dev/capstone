{{-- resources/views/admin/admin-resources.blade.php --}}
<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
  <title>Admin • Procedures · NurSync</title>
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
    {{-- Sidebar --}}
    @include('partials.admin-sidebar', ['active' => 'resources'])

    <section class="flex-1 min-w-0">
      {{-- Header --}}
      <header class="sticky top-0 z-30 bg-white/80 backdrop-blur border-b border-slate-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 h-14 flex items-center justify-between">
          <div class="flex items-center gap-3">
            <div class="inline-flex h-8 w-8 items-center justify-center rounded-xl bg-slate-900 text-white shadow-sm">
              <i data-lucide="stethoscope" class="h-4 w-4"></i>
            </div>
            <div>
              <h1 class="text-[15px] sm:text-[16px] font-semibold leading-tight">Procedures Library</h1>
              <p class="text-[12px] text-slate-500 -mt-0.5">Manage and review procedures created by Clinical
                Instructors.</p>
            </div>
          </div>

          <div class="flex items-center gap-2">
            <a href="{{ route('admin.procedures.create') }}"
              class="inline-flex items-center gap-2 rounded-xl bg-green-600 text-white px-3 py-2 text-[13px] font-medium shadow hover:bg-green-700 active:scale-[.99]">
              <i data-lucide="plus" class="h-4 w-4"></i>
              <span>Create</span>
            </a>
          </div>
        </div>
      </header>

      <div class="max-w-7xl mx-auto px-4 sm:px-6 py-6 space-y-6">

        {{-- Flash --}}
        @if (session('ok'))
          <div class="rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">
            {{ session('ok') }}
          </div>
        @endif
        @if ($errors->any())
          <div class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
            {{ $errors->first() }}
          </div>
        @endif

        {{-- Filters --}}
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-4">
          <form method="GET" class="flex flex-col sm:flex-row gap-3 sm:items-center sm:justify-between">
            <div class="flex items-center gap-3 w-full sm:w-auto">
              <div class="relative flex-1 sm:w-72">
                <i data-lucide="search" class="absolute left-3 top-2.5 h-4 w-4 text-slate-400"></i>
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Search procedure name..."
                  class="w-full rounded-xl border-slate-200 pl-9 pr-3 py-2.5 text-sm focus:ring-2 focus:ring-slate-300">
              </div>

              <select name="status"
                class="rounded-xl border-slate-200 text-sm py-2.5 px-3 focus:ring-2 focus:ring-slate-300">
                <option value="">All statuses</option>
                <option value="draft" @selected(request('status') === 'draft')>Draft</option>
                <option value="published" @selected(request('status') === 'published')>Published</option>
              </select>

              <button class="rounded-xl border border-slate-200 bg-white text-[13px] px-3 py-2.5 hover:bg-slate-50">
                Filter
              </button>
            </div>
          </form>
        </div>

        {{-- Table --}}
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
          <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
              <thead class="bg-slate-50 border-b border-slate-200">
                <tr class="text-left text-slate-600">
                  <th class="px-4 py-3">Procedure Name</th>
                  <th class="px-4 py-3">Status</th>
                  <th class="px-4 py-3">Created</th>
                  <th class="px-4 py-3">Created By</th>
                  <th class="px-4 py-3 text-right">Actions</th>
                </tr>
              </thead>

              <tbody class="divide-y divide-slate-200">
                @forelse ($procedures as $p)
                  <tr class="hover:bg-slate-50">
                    <td class="px-4 py-3 font-medium text-slate-900">
                      {{ $p->title ?: '—' }}
                    </td>

                    <td class="px-4 py-3">
                      @if ($p->status === 'published')
                        <span
                          class="inline-flex items-center gap-1.5 rounded-lg bg-green-50 text-green-700 px-2 py-1 text-[12px] font-medium">
                          <i data-lucide="check-circle" class="h-3.5 w-3.5"></i> Published
                        </span>
                      @else
                        <span
                          class="inline-flex items-center gap-1.5 rounded-lg bg-yellow-50 text-yellow-700 px-2 py-1 text-[12px] font-medium">
                          <i data-lucide="clock" class="h-3.5 w-3.5"></i> Draft
                        </span>
                      @endif
                    </td>

                    <td class="px-4 py-3 text-slate-700">
                      {{ optional($p->created_at)->format('M d, Y') ?: '—' }}
                    </td>

                    {{-- CREATED BY (Admin or CI) --}}
                    <td class="px-4 py-3 text-slate-700">
                      @if ($p->created_by_admin)
                        {{ optional($p->adminCreator)->full_name ?? '—' }}
                      @elseif ($p->created_by)
                        {{ optional($p->author)->full_name ?? '—' }}
                      @else
                        —
                      @endif
                    </td>


                    <td class="px-4 py-3">
                      <div class="flex items-center justify-end gap-1.5">
                        {{-- View --}}
                        <a href="{{ route('admin.procedures.show', $p) }}"
                          class="inline-flex items-center justify-center rounded-lg bg-blue-600 text-white p-2 hover:bg-blue-700"
                          title="View">
                          <i data-lucide="eye" class="h-4 w-4"></i>
                        </a>

                        {{-- Edit --}}
                        <a href="{{ route('admin.procedures.edit', $p) }}"
                          class="inline-flex items-center justify-center rounded-lg bg-yellow-400 text-slate-900 p-2 hover:brightness-95"
                          title="Edit">
                          <i data-lucide="pencil" class="h-4 w-4"></i>
                        </a>

                        {{-- Delete --}}
                        <form method="POST" action="{{ route('admin.procedures.destroy', $p) }}"
                          onsubmit="return confirm('Delete this procedure permanently? This cannot be undone.');">
                          @csrf @method('DELETE')
                          <button type="submit"
                            class="inline-flex items-center justify-center rounded-lg bg-red-600 text-white p-2 hover:bg-red-700"
                            title="Delete">
                            <i data-lucide="trash-2" class="h-4 w-4"></i>
                          </button>
                        </form>
                      </div>
                    </td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="5" class="px-4 py-8 text-center text-slate-500">No procedures found.</td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>

          {{-- Pagination --}}
          @if ($procedures instanceof \Illuminate\Contracts\Pagination\Paginator)
            @php
              $cur = $procedures->currentPage();
              $last = max(1, $procedures->lastPage());
              $window = 3;
              $half = intdiv($window - 1, 2);
              $from = max(1, $cur - $half);
              $to = min($last, $from + $window - 1);
              $from = max(1, $to - $window + 1);
            @endphp

            <div class="flex items-center justify-end px-4 py-3 border-t border-slate-200 bg-slate-50">
              <nav class="flex items-center gap-1">
                @if ($procedures->onFirstPage())
                  <button
                    class="h-9 w-9 inline-flex items-center justify-center rounded-lg border border-slate-200 bg-white opacity-50 cursor-not-allowed">
                    <i data-lucide="chevron-left" class="h-4 w-4"></i>
                  </button>
                @else
                  <a href="{{ $procedures->previousPageUrl() }}"
                    class="h-9 w-9 inline-flex items-center justify-center rounded-lg border border-slate-200 bg-white hover:bg-slate-50">
                    <i data-lucide="chevron-left" class="h-4 w-4"></i>
                  </a>
                @endif

                @for ($i = $from; $i <= $to; $i++)
                  @if ($i === $cur)
                    <span
                      class="h-9 w-9 inline-flex items-center justify-center rounded-lg bg-slate-900 text-white">{{ $i }}</span>
                  @else
                    <a href="{{ $procedures->url($i) }}"
                      class="h-9 w-9 inline-flex items-center justify-center rounded-lg border border-slate-200 bg-white hover:bg-slate-50">
                      {{ $i }}
                    </a>
                  @endif
                @endfor

                @if ($cur >= $last)
                  <button
                    class="h-9 w-9 inline-flex items-center justify-center rounded-lg border border-slate-200 bg-white opacity-50 cursor-not-allowed">
                    <i data-lucide="chevron-right" class="h-4 w-4"></i>
                  </button>
                @else
                  <a href="{{ $procedures->nextPageUrl() }}"
                    class="h-9 w-9 inline-flex items-center justify-center rounded-lg border border-slate-200 bg-white hover:bg-slate-50">
                    <i data-lucide="chevron-right" class="h-4 w-4"></i>
                  </a>
                @endif
              </nav>
            </div>
          @endif
        </div>
      </div>
    </section>
  </main>

  @include('partials.admin-footer')

  <script src="https://unpkg.com/lucide@latest"></script>
  <script> lucide.createIcons(); </script>
</body>

</html>