{{-- resources/views/faculty/create.blade.php (STATIC UI ONLY) --}}
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
  <title>New Chart · NurSync — CI</title>

  @vite(['resources/css/app.css','resources/js/app.js'])
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
  <style> body { font-family: 'Poppins', ui-sans-serif, system-ui, sans-serif; } </style>
</head>
<body class="min-h-screen bg-slate-50">

  <main class="min-h-screen flex">
    {{-- Sidebar (same include to keep layout consistent) --}}
    @include('partials.faculty-sidebar', ['active' => 'chartings'])

    {{-- Main --}}
    <section class="flex-1 px-6 md:px-8 py-8 md:py-10">

      {{-- Header --}}
      <div class="flex items-center justify-between gap-4">
        <div class="flex items-center gap-3">
          <a href="{{ url('/faculty/chartings') }}"
             class="hidden md:inline-flex h-9 w-9 items-center justify-center rounded-xl border border-slate-200 bg-white hover:bg-slate-50">
            <i data-lucide="arrow-left" class="h-4 w-4"></i>
          </a>
          <span class="inline-flex items-center justify-center h-9 w-9 rounded-xl bg-emerald-50 text-emerald-700 ring-1 ring-emerald-100">
            <i data-lucide="square-pen" class="h-5 w-5"></i>
          </span>
          <div>
            <h1 class="text-2xl font-bold leading-tight">New Chart</h1>
            <p class="text-[13px] text-slate-500 mt-0.5">Design a charting template for CI-led documentation and simulations.</p>
          </div>
        </div>

        <div class="flex items-center gap-2">
          <span id="saveBadge" class="hidden md:inline-flex items-center gap-1.5 rounded-full bg-slate-100 text-slate-700 text-[12px] px-2 py-1">
            <i data-lucide="check" class="h-3.5 w-3.5"></i> Saved
          </span>
          <a href="{{ url('/faculty/chartings') }}"
             class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-[13px] hover:bg-slate-50">Cancel</a>
          <button class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-[13px] hover:bg-slate-50">Save Draft</button>
          <button class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 text-white px-4 py-2 text-[13px] font-semibold shadow-sm hover:bg-emerald-700">
            <i data-lucide="rocket" class="h-4 w-4"></i> Publish
          </button>
        </div>
      </div>

      {{-- Stepper --}}
      <div class="mt-6 rounded-2xl border border-slate-200 bg-white p-4">
        <ol class="grid grid-cols-1 gap-3 sm:grid-cols-4">
          <li class="step active">
            <div class="flex items-center gap-3">
              <span class="step-dot">1</span>
              <div>
                <div class="text-[13px] font-semibold">Basics</div>
                <div class="text-[12px] text-slate-500">Type, unit, title</div>
              </div>
            </div>
          </li>
          <li class="step">
            <div class="flex items-center gap-3">
              <span class="step-dot">2</span>
              <div>
                <div class="text-[13px] font-semibold">Fields & Options</div>
                <div class="text-[12px] text-slate-500">Per chart type</div>
              </div>
            </div>
          </li>
          <li class="step">
            <div class="flex items-center gap-3">
              <span class="step-dot">3</span>
              <div>
                <div class="text-[13px] font-semibold">Instructions</div>
                <div class="text-[12px] text-slate-500">Teaching notes</div>
              </div>
            </div>
          </li>
          <li class="step">
            <div class="flex items-center gap-3">
              <span class="step-dot">4</span>
              <div>
                <div class="text-[13px] font-semibold">Review</div>
                <div class="text-[12px] text-slate-500">Summary & publish</div>
              </div>
            </div>
          </li>
        </ol>
      </div>

      {{-- Content grid: Form (left) + Live Preview (right) --}}
      <div class="mt-6 grid gap-6 lg:grid-cols-3">
        <div class="lg:col-span-2 space-y-6">

          {{-- STEP 1: BASICS --}}
          <section id="step1" class="rounded-2xl border border-slate-200 bg-white p-5">
            <div class="flex items-center justify-between">
              <h2 class="text-base font-semibold">Basics</h2>
              <span class="text-[12px] text-slate-500">Step 1 of 4</span>
            </div>

            <div class="mt-4 grid gap-4 sm:grid-cols-2">
              <div>
                <label class="form-label">Chart Type</label>
                <select id="fType" class="form-input">
                  <option value="Vitals">Vitals</option>
                  <option value="MAR">MAR</option>
                  <option value="I&O">Intake &amp; Output</option>
                  <option value="Treatment">Treatments / Procedures</option>
                  <option value="NotesNeuro">Nurse’s Notes / Neuro</option>
                  <option value="NCP">Nursing Care Plan</option>
                </select>
              </div>

              <div>
                <label class="form-label">Unit</label>
                <select id="fUnit" class="form-input">
                  <option>MS</option><option>Surgery</option><option>Medicine</option>
                  <option>ER</option><option>OR</option><option>OB</option><option>DR</option>
                  <option>PEDIA</option><option>CHN</option><option>PSYCH</option><option>GERIA</option><option>ORTHO</option>
                </select>
              </div>

              <div class="sm:col-span-2">
                <label class="form-label">Title</label>
                <input id="fTitle" type="text" class="form-input" placeholder="e.g., Vital Signs Flow Sheet" value="Vital Signs Flow Sheet">
              </div>

              <div class="sm:col-span-2">
                <label class="form-label">Objective (1–2 lines)</label>
                <input id="fObjective" type="text" class="form-input" placeholder="What is this chart for?">
              </div>

              <div>
                <label class="form-label">Visibility</label>
                <div class="flex gap-2">
                  <label class="chip"><input type="radio" name="visibility" value="private" checked> Private</label>
                  <label class="chip"><input type="radio" name="visibility" value="program"> Program</label>
                  <label class="chip opacity-60"><input type="radio" name="visibility" value="global" disabled> Global</label>
                </div>
              </div>

              <div>
                <label class="form-label">Tags</label>
                <input id="fTags" type="text" class="form-input" placeholder="monitoring, q4h, baseline">
              </div>
            </div>

            <div class="mt-5 flex items-center justify-end gap-2">
              <button class="btn-secondary" data-next="2">Next: Fields & Options</button>
            </div>
          </section>

          {{-- STEP 2: FIELDS & OPTIONS --}}
          <section id="step2" class="rounded-2xl border border-slate-200 bg-white p-5 hidden">
            <div class="flex items-center justify-between">
              <h2 class="text-base font-semibold">Fields & Options</h2>
              <span class="text-[12px] text-slate-500">Step 2 of 4</span>
            </div>

            {{-- VITALS --}}
            <div data-type-panel="Vitals" class="mt-4 space-y-4">
              <div class="grid gap-4 sm:grid-cols-2">
                <div class="rounded-xl border border-slate-200 p-4">
                  <div class="text-[13px] font-semibold">Included measurements</div>
                  <div class="mt-3 grid grid-cols-2 gap-2 text-[13px]">
                    <label class="switch"><input type="checkbox" data-k="include.temp_c" checked> Temperature (°C)</label>
                    <label class="switch"><input type="checkbox" data-k="include.pulse_bpm" checked> Pulse (bpm)</label>
                    <label class="switch"><input type="checkbox" data-k="include.resp_cpm" checked> Respiration (cpm)</label>
                    <label class="switch"><input type="checkbox" data-k="include.bp_systolic" checked> BP Systolic</label>
                    <label class="switch"><input type="checkbox" data-k="include.bp_diastolic" checked> BP Diastolic</label>
                    <label class="switch"><input type="checkbox" data-k="include.spo2" checked> SpO₂</label>
                    <label class="switch"><input type="checkbox" data-k="include.pain_score" checked> Pain score</label>
                  </div>
                </div>
                <div class="rounded-xl border border-slate-200 p-4">
                  <div class="text-[13px] font-semibold">Defaults</div>
                  <div class="mt-3 grid gap-3 text-[13px]">
                    <label class="flex items-center justify-between gap-3">
                      <span>Frequency</span>
                      <select class="form-input h-9 w-40" data-k="frequency">
                        <option value="q4h" selected>q4h</option>
                        <option value="q6h">q6h</option>
                        <option value="q8h">q8h</option>
                        <option value="q12h">q12h</option>
                        <option value="PRN">PRN</option>
                      </select>
                    </label>
                    <label class="flex items-center justify-between gap-3">
                      <span>Pain scale</span>
                      <select class="form-input h-9 w-40" data-k="pain_scale">
                        <option value="NRS" selected>NRS</option>
                        <option value="WongBaker">Wong-Baker</option>
                        <option value="FLACC">FLACC</option>
                      </select>
                    </label>
                    <label class="flex items-center justify-between gap-3">
                      <span>Reassess pain (minutes)</span>
                      <input type="number" class="form-input h-9 w-40" min="5" step="5" value="30" data-k="reassess_after_minutes">
                    </label>
                  </div>
                </div>
              </div>
            </div>

            {{-- MAR --}}
            <div data-type-panel="MAR" class="mt-4 space-y-4 hidden">
              <div class="grid gap-4 sm:grid-cols-2">
                <div class="rounded-xl border border-slate-200 p-4">
                  <div class="text-[13px] font-semibold">Order fields</div>
                  <div class="mt-3 grid grid-cols-2 gap-2 text-[13px]">
                    <label class="switch"><input type="checkbox" data-array="order_fields" value="med_name" checked> Medication</label>
                    <label class="switch"><input type="checkbox" data-array="order_fields" value="formulation" checked> Formulation</label>
                    <label class="switch"><input type="checkbox" data-array="order_fields" value="dose" checked> Dose</label>
                    <label class="switch"><input type="checkbox" data-array="order_fields" value="route" checked> Route</label>
                    <label class="switch"><input type="checkbox" data-array="order_fields" value="frequency" checked> Frequency</label>
                    <label class="switch"><input type="checkbox" data-array="order_fields" value="start_at" checked> Start</label>
                    <label class="switch"><input type="checkbox" data-array="order_fields" value="stop_at" checked> Stop</label>
                  </div>
                </div>
                <div class="rounded-xl border border-slate-200 p-4">
                  <div class="text-[13px] font-semibold">Administration fields</div>
                  <div class="mt-3 grid grid-cols-2 gap-2 text-[13px]">
                    <label class="switch"><input type="checkbox" data-array="admin_fields" value="scheduled_time" checked> Scheduled time</label>
                    <label class="switch"><input type="checkbox" data-array="admin_fields" value="administered_time" checked> Given time</label>
                    <label class="switch"><input type="checkbox" data-array="admin_fields" value="dose_given" checked> Dose given</label>
                    <label class="switch"><input type="checkbox" data-array="admin_fields" value="site" checked> Site</label>
                    <label class="switch"><input type="checkbox" data-array="admin_fields" value="pre_checks" checked> Pre-checks</label>
                    <label class="switch"><input type="checkbox" data-array="admin_fields" value="post_effect" checked> Post-effect</label>
                    <label class="switch"><input type="checkbox" data-array="admin_fields" value="prn_reason" checked> PRN reason</label>
                    <label class="switch"><input type="checkbox" data-array="admin_fields" value="withheld_reason" checked> Withheld reason</label>
                  </div>
                </div>
              </div>
              <div class="rounded-xl border border-slate-200 p-4">
                <div class="grid gap-3 sm:grid-cols-3 text-[13px]">
                  <label class="switch"><input type="checkbox" data-k="require_cosign" checked> Require co-sign</label>
                  <label class="switch"><input type="checkbox" data-k="six_rights_check" checked> Show “6 rights” checklist</label>
                  <label class="switch"><input type="checkbox" data-k="high_alert_double_check"> Double-check high-alert meds</label>
                </div>
              </div>
            </div>

            {{-- I&O --}}
            <div data-type-panel="I&O" class="mt-4 space-y-4 hidden">
              <div class="rounded-xl border border-slate-200 p-4">
                <div class="text-[13px] font-semibold">Categories</div>
                <div class="mt-3 grid grid-cols-3 gap-2 text-[13px]">
                  <label class="switch"><input type="checkbox" data-array="categories" value="oral" checked> Oral</label>
                  <label class="switch"><input type="checkbox" data-array="categories" value="iv" checked> IV</label>
                  <label class="switch"><input type="checkbox" data-array="categories" value="tube" checked> Tube</label>
                  <label class="switch"><input type="checkbox" data-array="categories" value="urine" checked> Urine</label>
                  <label class="switch"><input type="checkbox" data-array="categories" value="drain" checked> Drain</label>
                  <label class="switch"><input type="checkbox" data-array="categories" value="emesis" checked> Emesis</label>
                  <label class="switch"><input type="checkbox" data-array="categories" value="stool" checked> Stool</label>
                </div>
              </div>
              <div class="rounded-xl border border-slate-200 p-4">
                <div class="grid gap-3 sm:grid-cols-2 text-[13px]">
                  <label class="switch"><input type="checkbox" data-k="show_running_balance" checked> Show running balance</label>
                  <label class="switch"><input type="checkbox" data-k="show_24h_total" checked> Show 24h total</label>
                </div>
              </div>
            </div>

            {{-- TREATMENT --}}
            <div data-type-panel="Treatment" class="mt-4 space-y-4 hidden">
              <div class="rounded-xl border border-slate-200 p-4">
                <div class="text-[13px] font-semibold">Procedure fields</div>
                <div class="mt-3 grid grid-cols-2 gap-2 text-[13px]">
                  <label class="switch"><input type="checkbox" data-array="fields" value="start_time" checked> Start time</label>
                  <label class="switch"><input type="checkbox" data-array="fields" value="end_time" checked> End time</label>
                  <label class="switch"><input type="checkbox" data-array="fields" value="site" checked> Site</label>
                  <label class="switch"><input type="checkbox" data-array="fields" value="device" checked> Device</label>
                  <label class="switch"><input type="checkbox" data-array="fields" value="tolerance" checked> Tolerance</label>
                  <label class="switch"><input type="checkbox" data-array="fields" value="notes" checked> Notes</label>
                </div>
              </div>
              <div class="rounded-xl border border-slate-200 p-4">
                <div class="grid gap-3 sm:grid-cols-2 text-[13px]">
                  <label class="switch"><input type="checkbox" data-k="require_verify" checked> CI verification required</label>
                  <label class="switch"><input type="checkbox" data-k="allow_student_perform" > Allow student perform (CI still charts)</label>
                </div>
              </div>
            </div>

            {{-- NOTES / NEURO --}}
            <div data-type-panel="NotesNeuro" class="mt-4 space-y-4 hidden">
              <div class="rounded-xl border border-slate-200 p-4">
                <div class="text-[13px] font-semibold">Format</div>
                <div class="mt-3 flex flex-wrap gap-2">
                  <label class="chip"><input type="radio" name="notes_fmt" value="Narrative" data-k="format" checked> Narrative</label>
                  <label class="chip"><input type="radio" name="notes_fmt" value="SOAP" data-k="format"> SOAP</label>
                  <label class="chip"><input type="radio" name="notes_fmt" value="SOAPIER" data-k="format"> SOAPIER</label>
                </div>
              </div>
              <div class="rounded-xl border border-slate-200 p-4">
                <div class="text-[13px] font-semibold">Neurological components</div>
                <div class="mt-3 grid grid-cols-2 gap-2 text-[13px]">
                  <label class="switch"><input type="checkbox" data-k="include_gcs" checked> Include GCS</label>
                  <label class="switch"><input type="checkbox" data-k="include_pupils" checked> Pupillary response</label>
                  <label class="switch"><input type="checkbox" data-k="include_motor" checked> Limb strength</label>
                  <label class="switch"><input type="checkbox" data-k="alert_drop2" checked> Alert on ≥2 GCS drop</label>
                </div>
              </div>
            </div>

            {{-- NCP --}}
            <div data-type-panel="NCP" class="mt-4 space-y-4 hidden">
              <div class="rounded-xl border border-slate-200 p-4">
                <div class="text-[13px] font-semibold">Plan configuration</div>
                <div class="mt-3 grid gap-3 text-[13px]">
                  <label class="switch"><input type="checkbox" data-k="require_goal" checked> Require goal text</label>
                  <label class="switch"><input type="checkbox" data-k="allow_interventions" checked> Allow interventions list</label>
                </div>
              </div>
              <div class="rounded-xl border border-slate-200 p-4">
                <div class="text-[13px] font-semibold">Statuses</div>
                <div class="mt-3 flex flex-wrap gap-2">
                  <label class="chip"><input type="checkbox" data-array="statuses" value="ongoing" checked> ongoing</label>
                  <label class="chip"><input type="checkbox" data-array="statuses" value="met" checked> met</label>
                  <label class="chip"><input type="checkbox" data-array="statuses" value="partially_met" checked> partially_met</label>
                  <label class="chip"><input type="checkbox" data-array="statuses" value="discontinued" checked> discontinued</label>
                </div>
              </div>
            </div>

            <div class="mt-5 flex items-center justify-between">
              <button class="btn-tertiary" data-prev="1"><i data-lucide="arrow-left" class="h-4 w-4 mr-1"></i> Back</button>
              <button class="btn-secondary" data-next="3">Next: Instructions</button>
            </div>
          </section>

          {{-- STEP 3: INSTRUCTIONS --}}
          <section id="step3" class="rounded-2xl border border-slate-200 bg-white p-5 hidden">
            <div class="flex items-center justify-between">
              <h2 class="text-base font-semibold">Instructions & Simulation Aids (optional)</h2>
              <span class="text-[12px] text-slate-500">Step 3 of 4</span>
            </div>

            <div class="mt-4 grid gap-4">
              <div>
                <label class="form-label">Teaching notes</label>
                <textarea id="fInstructions" rows="8" class="form-input min-h-[160px]"
                  placeholder="Give steps, safety checks, escalation criteria, and tips for students…"></textarea>
              </div>

              <div class="rounded-xl border border-slate-200 p-4">
                <div class="flex items-center justify-between">
                  <div class="text-[13px] font-semibold">Checklist (demo)</div>
                  <button class="rounded-lg border border-slate-200 px-2 py-1 text-[12px] hover:bg-slate-50" type="button" disabled>
                    <i data-lucide="plus" class="h-3.5 w-3.5"></i> Add step
                  </button>
                </div>
                <ul class="mt-3 space-y-2 text-[13px] text-slate-600">
                  <li>• Verify patient identity and allergies.</li>
                  <li>• Explain procedure to patient/family.</li>
                  <li>• Document findings and actions promptly.</li>
                </ul>
              </div>

              <div>
                <label class="form-label">Version note (for history)</label>
                <input id="fNote" type="text" class="form-input" placeholder="e.g., Added pain reassessment rule">
              </div>
            </div>

            <div class="mt-5 flex items-center justify-between">
              <button class="btn-tertiary" data-prev="2"><i data-lucide="arrow-left" class="h-4 w-4 mr-1"></i> Back</button>
              <button class="btn-secondary" data-next="4">Next: Review</button>
            </div>
          </section>

          {{-- STEP 4: REVIEW --}}
          <section id="step4" class="rounded-2xl border border-slate-200 bg-white p-5 hidden">
            <div class="flex items-center justify-between">
              <h2 class="text-base font-semibold">Review & Publish</h2>
              <span class="text-[12px] text-slate-500">Step 4 of 4</span>
            </div>

            <div class="mt-4 grid gap-4">
              <div class="rounded-xl border border-slate-200 p-4">
                <div class="text-[13px] font-semibold">Summary</div>
                <dl class="mt-3 grid gap-3 text-[13px] sm:grid-cols-2">
                  <div><dt class="text-slate-500">Title</dt><dd id="rvTitle" class="font-medium">—</dd></div>
                  <div><dt class="text-slate-500">Type</dt><dd id="rvType" class="font-medium">—</dd></div>
                  <div><dt class="text-slate-500">Unit</dt><dd id="rvUnit" class="font-medium">—</dd></div>
                  <div><dt class="text-slate-500">Visibility</dt><dd id="rvVis" class="font-medium">—</dd></div>
                  <div class="sm:col-span-2"><dt class="text-slate-500">Objective</dt><dd id="rvObjective" class="font-medium">—</dd></div>
                  <div class="sm:col-span-2"><dt class="text-slate-500">Tags</dt><dd id="rvTags" class="font-medium">—</dd></div>
                </dl>
              </div>

              <div class="rounded-xl border border-slate-200 p-4">
                <div class="text-[13px] font-semibold">Fields JSON (preview)</div>
                <pre id="rvJson" class="mt-3 whitespace-pre-wrap text-[12px] bg-slate-50 rounded-lg p-3 border border-slate-200 overflow-x-auto">{}</pre>
              </div>

              <div class="rounded-xl border border-slate-200 p-4">
                <div class="text-[13px] font-semibold">Instructions</div>
                <p id="rvInstructions" class="mt-2 text-[13px] text-slate-700">—</p>
              </div>
            </div>

            <div class="mt-5 flex items-center justify-between">
              <button class="btn-tertiary" data-prev="3"><i data-lucide="arrow-left" class="h-4 w-4 mr-1"></i> Back</button>
              <div class="flex gap-2">
                <button class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-[13px] hover:bg-slate-50">Save Draft</button>
                <button class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 text-white px-4 py-2 text-[13px] font-semibold shadow-sm hover:bg-emerald-700">
                  <i data-lucide="rocket" class="h-4 w-4"></i> Publish
                </button>
              </div>
            </div>
          </section>

        </div>

        {{-- LIVE PREVIEW CARD --}}
        <aside class="lg:col-span-1">
          <div class="sticky top-6 rounded-2xl border border-slate-200 bg-white p-5">
            <div class="flex items-start gap-3">
              <span id="pvIconWrap" class="h-9 w-9 rounded-xl bg-emerald-50 ring-1 ring-emerald-100 grid place-items-center text-emerald-700">
                <i id="pvIcon" data-lucide="activity" class="h-5 w-5"></i>
              </span>
              <div class="flex-1">
                <div class="flex items-start justify-between gap-2">
                  <h3 id="pvTitle" class="text-sm font-semibold text-slate-900">Vital Signs Flow Sheet</h3>
                  <span class="text-[11px] px-2 py-0.5 rounded-full bg-slate-100 text-slate-700 font-semibold">Draft</span>
                </div>
                <p id="pvObjective" class="mt-1 text-[12px] text-slate-600">—</p>
                <div class="mt-2 flex flex-wrap items-center gap-2 text-[11px]">
                  <span id="pvUnit" class="px-2 py-0.5 rounded-full bg-slate-100 text-slate-700 font-semibold">MS</span>
                  <span id="pvType" class="px-2 py-0.5 rounded-full bg-sky-100 text-sky-700 font-semibold">Vitals</span>
                </div>
                <div class="mt-3 text-[11px] text-slate-500">
                  <span id="pvFieldsHint">Defaults configured…</span>
                </div>
              </div>
            </div>
            <hr class="my-4 border-slate-200">
            <div class="flex items-center justify-between">
              <span class="text-[12px] text-slate-500">Autosave</span>
              <span id="autosave" class="inline-flex items-center gap-1.5 rounded-full bg-slate-100 text-slate-700 text-[12px] px-2 py-1">
                <i data-lucide="dot" class="h-3 w-3"></i> Idle
              </span>
            </div>
          </div>
        </aside>
      </div>
    </section>
  </main>

  {{-- Footer (optional, matches list page) --}}
  @include('partials.faculty-footer')

  {{-- Helpers --}}
  <script src="https://unpkg.com/lucide@latest"></script>
  <script>
    // ---------- tiny helpers / styles ----------
    const $ = (q,ctx=document)=>ctx.querySelector(q);
    const $$ = (q,ctx=document)=>Array.from(ctx.querySelectorAll(q));

    function setActiveStep(n){
      $$('.step').forEach((el,i)=>{
        el.classList.remove('active','done');
        if(i < n-1) el.classList.add('done');
        if(i === n-1) el.classList.add('active');
      });
      ['step1','step2','step3','step4'].forEach((id,i)=>{
        const show = (i+1)===n;
        const sec = $('#'+id); if(!sec) return;
        sec.classList.toggle('hidden', !show);
      });
      lucide.createIcons();
    }

    function updatePreview(){
      const type = $('#fType').value;
      const unit = $('#fUnit').value;
      const title = $('#fTitle').value || `${type} Template`;
      const objective = $('#fObjective').value || '—';

      $('#pvTitle').textContent = title;
      $('#pvObjective').textContent = objective;
      $('#pvUnit').textContent = unit;
      $('#pvType').textContent = type;

      const iconMap = { 'Vitals':'activity','MAR':'pill','I&O':'droplets','Treatment':'heart-pulse','NotesNeuro':'brain','NCP':'clipboard-list' };
      const icon = iconMap[type] || 'clipboard-list';
      $('#pvIcon').setAttribute('data-lucide', icon);
      lucide.createIcons();

      // hint
      const hintByType = {
        'Vitals':'Measures: T/HR/RR/BP/SpO₂/Pain',
        'MAR':'Orders + administrations with co-sign',
        'I&O':'Categories: oral/iv/tube/urine/drain…',
        'Treatment':'Procedure start/end, site, device',
        'NotesNeuro':'Narrative/SOAP + GCS options',
        'NCP':'Diagnosis, goals, interventions'
      };
      $('#pvFieldsHint').textContent = hintByType[type] || '';
    }

    // Build a lightweight "fields_json" from visible panel inputs
    function buildFieldsJson(){
      const type = $('#fType').value;
      const panel = document.querySelector(`[data-type-panel="${CSS.escape(type)}"]`);
      const obj = {};

      // simple key-value switches/selects on this panel
      $$('[data-k]', panel).forEach(inp=>{
        let val;
        if (inp.type==='checkbox') val = inp.checked;
        else if (inp.type==='radio') { if (!inp.checked) return; val = inp.value; }
        else val = inp.value;
        // dot-path support, e.g., include.temp_c
        const path = inp.dataset.k.split('.');
        let t = obj;
        for(let i=0;i<path.length-1;i++){
          t[path[i]] = t[path[i]] || {};
          t = t[path[i]];
        }
        t[path[path.length-1]] = val;
      });

      // arrays like order_fields, admin_fields, categories, statuses, fields…
      const arrays = {};
      $$('[data-array]', panel).forEach(inp=>{
        const key = inp.dataset.array;
        arrays[key] = arrays[key] || [];
        if (inp.checked) arrays[key].push(inp.value);
      });
      Object.assign(obj, arrays);

      // minimal defaults for each type (so preview shows something)
      const defaults = {
        'Vitals': { frequency: 'q4h', pain_scale:'NRS', reassess_after_minutes: 30,
          include: {temp_c:true,pulse_bpm:true,resp_cpm:true,bp_systolic:true,bp_diastolic:true,spo2:true,pain_score:true} },
        'MAR': { order_fields:['med_name','formulation','dose','route','frequency','start_at','stop_at'],
          admin_fields:['scheduled_time','administered_time','dose_given','site','pre_checks','post_effect','prn_reason','withheld_reason'],
          require_cosign:true, six_rights_check:true },
        'I&O': { categories:['oral','iv','tube','urine','drain','emesis','stool'], show_running_balance:true },
        'Treatment': { fields:['start_time','end_time','site','device','tolerance','notes'], require_verify:true },
        'NotesNeuro': { format:'Narrative', include_gcs:true },
        'NCP': { statuses:['ongoing','met','partially_met','discontinued'], require_goal:true }
      };
      // merge defaults where panel produced nothing (static demo)
      return JSON.stringify(Object.assign({}, defaults[type]||{}, obj), null, 2);
    }

    function refreshReview(){
      $('#rvTitle').textContent = $('#fTitle').value || '—';
      $('#rvType').textContent = $('#fType').value;
      $('#rvUnit').textContent = $('#fUnit').value;
      const vis = document.querySelector('input[name="visibility"]:checked')?.value || 'private';
      $('#rvVis').textContent = vis;
      $('#rvObjective').textContent = $('#fObjective').value || '—';
      $('#rvTags').textContent = $('#fTags').value || '—';
      $('#rvInstructions').textContent = $('#fInstructions').value || '—';
      $('#rvJson').textContent = buildFieldsJson();
    }

    function showTypePanel(){
      const type = $('#fType').value;
      $$('[data-type-panel]').forEach(p=>{
        p.classList.toggle('hidden', p.getAttribute('data-type-panel') !== type);
      });
    }

    function markDirty(){
      const el = $('#autosave');
      el.textContent = 'Unsaved changes…';
      el.classList.remove('bg-slate-100','text-slate-700');
      el.classList.add('bg-amber-100','text-amber-800');
      $('#saveBadge').classList.add('hidden');
    }
    function markSaved(){
      const el = $('#autosave');
      el.textContent = 'Saved';
      el.classList.remove('bg-amber-100','text-amber-800');
      el.classList.add('bg-slate-100','text-slate-700');
      $('#saveBadge').classList.remove('hidden');
    }

    // ---------- events ----------
    document.addEventListener('click', (e)=>{
      const next = e.target.closest('[data-next]'); if(next){ setActiveStep(+next.dataset.next); if(+next.dataset.next===4) refreshReview(); return; }
      const prev = e.target.closest('[data-prev]'); if(prev){ setActiveStep(+prev.dataset.prev); return; }
    });

    // mark dirty on any input change & update preview
    document.addEventListener('input', (e)=>{
      if(e.target.closest('section')) { markDirty(); updatePreview(); if($('#step4') && !$('#step4').classList.contains('hidden')) refreshReview(); }
      if(e.target.id==='fType'){ showTypePanel(); }
    });

    // fake autosave timer (static)
    let t=null; document.addEventListener('input', ()=>{ clearTimeout(t); t=setTimeout(()=>markSaved(), 800); });

    // init
    lucide.createIcons();
    setActiveStep(1);
    showTypePanel();
    updatePreview();

    // ---------- tiny css-in-js helpers ----------
    // (Using Tailwind utility classes defined below)
  </script>

  <style>
    /* Stepper */
    .step .step-dot{
      @apply inline-flex h-7 w-7 items-center justify-center rounded-full border border-slate-200 text-[12px] text-slate-600;
    }
    .step.active .step-dot{
      @apply bg-emerald-600 text-white border-emerald-600;
    }
    .step.done .step-dot{
      @apply bg-emerald-100 text-emerald-700 border-emerald-200;
    }
    /* Inputs */
    .form-label{ @apply block text-[12px] font-medium text-slate-600 mb-1; }
    .form-input{ @apply w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-[13px] text-slate-700 outline-none focus:ring-2 focus:ring-emerald-200; }
    .switch{ @apply inline-flex items-center gap-2; }
    .chip{ @apply inline-flex items-center gap-2 rounded-full border border-slate-200 bg-white px-3 py-1 text-[12px] hover:bg-slate-50 cursor-pointer; }
    .btn-secondary{ @apply rounded-xl border border-slate-200 bg-white px-4 py-2 text-[13px] hover:bg-slate-50; }
    .btn-tertiary{ @apply rounded-xl border border-slate-200 bg-white px-3 py-2 text-[13px] hover:bg-slate-50; }
  </style>
</body>
</html>
