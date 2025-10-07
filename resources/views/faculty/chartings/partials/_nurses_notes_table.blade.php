@php use Illuminate\Support\Str; @endphp

<table class="min-w-full text-[13px]">
  <thead class="text-slate-500">
    <tr>
      <th class="text-left py-2 pr-3">Date/Time</th>
      <th class="text-left py-2 pr-3">Format</th>
      <th class="text-left py-2 pr-3">Summary</th>
      <th class="text-left py-2 pr-3">CI</th>
      <th class="text-right py-2 pl-3">Actions</th>
    </tr>
  </thead>

  <tbody class="align-top">
    @forelse(($notes ?? []) as $n)
      @php
        /** @var \App\Models\NursesNote $n */
        $at   = optional($n->noted_at ?? $n->created_at)->format('Y-m-d H:i');
        $fmt  = strtoupper((string)($n->format ?? ''));
        $sum  = (string)($n->summary ?? Str::limit(strip_tags($n->body ?? ''), 120));
        $unit = (string)($n->unit ?? '');
        $ci   = $n->faculty->display_name ?? 'You';

        $chip = match (strtolower($fmt)) {
          'dar'    => 'bg-emerald-50 text-emerald-700 ring-emerald-100',
          'soapie' => 'bg-indigo-50 text-indigo-700 ring-indigo-100',
          'pie'    => 'bg-amber-50 text-amber-700 ring-amber-100',
          default  => 'bg-slate-100 text-slate-700 ring-slate-200',
        };
      @endphp
      <tr class="border-t border-slate-100">
        <td class="py-3 pr-3">{{ $at ?: '—' }}</td>

        <td class="py-3 pr-3">
          <span class="inline-flex px-2 py-0.5 rounded-lg ring-1 {{ $chip }}">
            {{ $fmt ?: '—' }}
          </span>
        </td>

        <td class="py-3 pr-3">
          {{ $sum !== '' ? $sum : '—' }}
          @if($unit !== '')
            <span class="ml-2 text-[11px] text-slate-500">· {{ $unit }}</span>
          @endif
        </td>

        <td class="py-3 pr-3">{{ $ci }}</td>

        <td class="py-3 pl-3">
          <div class="flex items-center justify-end gap-1.5">
            {{-- Preview (opens drawer in hub) --}}
            <a href="#"
               class="js-preview inline-flex h-8 w-8 items-center justify-center rounded-lg bg-slate-100 hover:bg-slate-200"
               title="Preview" aria-label="Preview"
               data-title="Nurse’s Notes — {{ $fmt ?: '—' }}"
               data-unit="{{ $unit ?: '—' }}"
               data-type="Nurse’s Notes"
               data-status="Private"
               data-desc="{{ 'Format: '.($fmt ?: '—').'. '.($sum ?: '—') }}"
               data-edit="{{ route('faculty.chartings.nurses_notes.edit', $n) }}"
               data-open="{{ route('faculty.chartings.nurses_notes.show', $n) }}">
              <i data-lucide="eye" class="h-4 w-4"></i>
              <span class="sr-only">Preview</span>
            </a>

            {{-- Edit --}}
            <a href="{{ route('faculty.chartings.nurses_notes.edit', $n) }}"
               class="inline-flex h-8 w-8 items-center justify-center rounded-lg text-emerald-700 hover:bg-emerald-50"
               title="Edit" aria-label="Edit">
              <i data-lucide="pencil" class="h-4 w-4"></i>
              <span class="sr-only">Edit</span>
            </a>

            {{-- Open --}}
            <a href="{{ route('faculty.chartings.nurses_notes.show', $n) }}"
               class="inline-flex h-8 w-8 items-center justify-center rounded-lg text-slate-700 hover:bg-slate-100"
               title="Open" aria-label="Open">
              <i data-lucide="external-link" class="h-4 w-4"></i>
              <span class="sr-only">Open</span>
            </a>
          </div>
        </td>
      </tr>
    @empty
      <tr>
        <td colspan="5" class="py-6 text-center text-slate-500">No nurse’s notes yet.</td>
      </tr>
    @endforelse
  </tbody>
</table>
