<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" /><meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
  <title>New Intake & Output · NurSync — CI</title>
  @vite(['resources/css/app.css','resources/js/app.js'])
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
  <style> body{font-family:'Poppins',ui-sans-serif,system-ui,sans-serif;} </style>
</head>
<body class="min-h-screen bg-slate-50">
<main class="min-h-screen flex">
  @include('partials.faculty-sidebar', ['active'=>'chartings'])
  <section class="flex-1 px-6 md:px-8 py-8 md:py-10">
    <div class="flex items-center justify-between">
      <div class="flex items-center gap-3">
        <span class="inline-flex h-9 w-9 items-center justify-center rounded-xl bg-emerald-50 text-emerald-700 ring-1 ring-emerald-100">
          <i data-lucide="beaker" class="h-5 w-5"></i>
        </span>
        <div>
          <h1 class="text-2xl font-bold">New Intake & Output</h1>
          <p class="text-[13px] text-slate-500 mt-0.5">Track fluid balance for the encounter.</p>
        </div>
      </div>
      <a href="/faculty/chartings" class="text-[13px] px-3 h-9 inline-flex items-center rounded-lg bg-slate-100 hover:bg-slate-200">
        <i data-lucide="arrow-left" class="h-4 w-4 mr-1"></i> Back to Chartings
      </a>
    </div>

    <form action="#" method="POST" class="mt-6" id="ioForm">
      @csrf
      <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5 space-y-5">
        <div class="grid md:grid-cols-3 gap-4">
          <div>
            <label class="block text-[12px] text-slate-500 mb-1">Encounter</label>
            <select name="encounter_id" class="w-full rounded-xl border-slate-300 text-[14px]">
              <option value="">— Select —</option><option>MS Ward · Enc#1001</option>
            </select>
          </div>
          <div>
            <label class="block text-[12px] text-slate-500 mb-1">Logged at</label>
            <input type="datetime-local" name="logged_at" class="w-full rounded-xl border-slate-300 text-[14px]" />
          </div>
        </div>

        <div class="grid md:grid-cols-2 gap-4">
          <div class="rounded-xl border border-slate-200 p-4">
            <h3 class="text-[13px] font-semibold mb-3">Intake</h3>
            <div class="grid md:grid-cols-2 gap-3">
              <select name="type_intake" class="rounded-xl border-slate-300 text-[14px]">
                <option value="">Type</option><option>Oral</option><option>IVF</option><option>NGT/PEG</option><option>Tubes</option>
              </select>
              <input type="number" name="intake_ml" id="intake" class="rounded-xl border-slate-300 text-[14px]" placeholder="mL" />
            </div>
          </div>

          <div class="rounded-xl border border-slate-200 p-4">
            <h3 class="text-[13px] font-semibold mb-3">Output</h3>
            <div class="grid md:grid-cols-2 gap-3">
              <select name="type_output" class="rounded-xl border-slate-300 text-[14px]">
                <option value="">Type</option><option>Urine</option><option>Stool</option><option>Emesis</option><option>Drain</option>
              </select>
              <input type="number" name="output_ml" id="output" class="rounded-xl border-slate-300 text-[14px]" placeholder="mL" />
            </div>
          </div>
        </div>

        <div class="grid md:grid-cols-3 gap-4 items-end">
          <div>
            <label class="block text-[12px] text-slate-500 mb-1">Balance (auto)</label>
            <input type="text" name="balance_ml" id="balance" readonly class="w-full rounded-xl border-slate-300 text-[14px] bg-slate-50" placeholder="+0 mL" />
          </div>
          <div class="md:col-span-2">
            <label class="block text-[12px] text-slate-500 mb-1">Remarks (optional)</label>
            <input type="text" name="remarks" class="w-full rounded-xl border-slate-300 text-[14px]" />
          </div>
        </div>
      </div>

      <div class="mt-5 flex items-center gap-2">
        <button type="submit" class="inline-flex items-center h-10 px-4 rounded-xl bg-emerald-600 text-white text-[13px] font-semibold">
          <i data-lucide="save" class="h-4 w-4 mr-2"></i> Save I&O
        </button>
        <a href="/faculty/chartings" class="inline-flex items-center h-10 px-3 rounded-xl bg-slate-100 text-[13px]">Cancel</a>
      </div>
    </form>
  </section>
</main>

@include('partials.faculty-footer')
<script src="https://unpkg.com/lucide@latest"></script>
<script>
  lucide.createIcons();
  const intake=document.getElementById('intake'), output=document.getElementById('output'), balance=document.getElementById('balance');
  function calc(){ const i=+intake.value||0, o=+output.value||0, b=i-o; balance.value=(b>=0?'+':'')+b+' mL'; }
  intake?.addEventListener('input',calc); output?.addEventListener('input',calc); calc();
</script>
</body>
</html>
