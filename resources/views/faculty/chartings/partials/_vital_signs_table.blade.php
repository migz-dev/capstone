<table class="min-w-full text-[13px]">
  <thead class="text-slate-500">
    <tr>
      <th class="text-left py-2 pr-3">Taken at</th>
      <th class="text-left py-2 pr-3">T (°C)</th>
      <th class="text-left py-2 pr-3">P (bpm)</th>
      <th class="text-left py-2 pr-3">R (cpm)</th>
      <th class="text-left py-2 pr-3">BP</th>
      <th class="text-left py-2 pr-3">SpO₂</th>
      <th class="text-left py-2 pr-3">Pain</th>
      <th class="text-right py-2 pl-3">Actions</th>
    </tr>
  </thead>
  <tbody>
    @forelse(($vitals ?? []) as $v)
      @php
        $t = isset($v->temp_c) ? rtrim(rtrim(number_format((float)$v->temp_c, 1), '0'), '.') : '—';
        $p = $v->pulse_bpm ?? '—';
        $r = $v->resp_rate ?? '—';
        $bp = ($v->bp_systolic && $v->bp_diastolic) ? ($v->bp_systolic.'/'.$v->bp_diastolic) : '—';
        $o2 = isset($v->spo2) ? ($v->spo2.'%') : '—';
        $pain = $v->pain_scale ?? '—';
        $taken = $v->taken_at?->format('Y-m-d H:i') ?? '—';
        $unit = $v->encounter->unit ?? '—';
        $desc = "T {$t}, P {$p}, R {$r}, BP {$bp}, SpO₂ {$o2}, Pain {$pain}/10.";
      @endphp
      <tr class="border-t border-slate-100">
        <td class="py-3 pr-3">{{ $taken }}</td>
        <td class="py-3 pr-3">{{ $t }}</td>
        <td class="py-3 pr-3">{{ $p }}</td>
        <td class="py-3 pr-3">{{ $r }}</td>
        <td class="py-3 pr-3">{{ $bp }}</td>
        <td class="py-3 pr-3">{{ $o2 }}</td>
        <td class="py-3 pr-3">{{ $pain }}</td>
        <td class="py-3 pl-3">
          <div class="flex items-center justify-end gap-2">
            <a href="#"
               class="js-preview inline-flex items-center h-8 px-2.5 rounded-lg bg-slate-100"
               data-title="Vital Signs — {{ $taken }}"
               data-unit="{{ $unit }}"
               data-type="Vital Signs"
               data-status="Recorded"
               data-desc="{{ $desc }}"
               data-edit="{{ route('faculty.chartings.vital_signs.edit', $v) }}"
               data-open="{{ route('faculty.chartings.vital_signs.show', $v) }}">
              <i data-lucide="eye" class="h-4 w-4 mr-1"></i> Preview
            </a>
            <a href="{{ route('faculty.chartings.vital_signs.edit', $v) }}" class="text-emerald-700 hover:underline">Edit</a>
            <a href="{{ route('faculty.chartings.vital_signs.show', $v) }}" class="text-slate-700 hover:underline">Open</a>
          </div>
        </td>
      </tr>
    @empty
      <tr>
        <td colspan="8" class="py-6 text-center text-slate-500">No vitals yet.</td>
      </tr>
    @endforelse
  </tbody>
</table>
