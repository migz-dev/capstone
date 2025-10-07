<x-modal id="modalUpdate">
  <div class="flex items-center justify-between">
    <h3 class="text-[15px] font-semibold">Update user</h3>
    <button class="p-2 rounded-lg hover:bg-slate-100" data-modal-close>
      <i data-lucide="x" class="h-5 w-5"></i>
    </button>
  </div>
  <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 gap-3">
    <div>
      <label class="text-[12px] text-slate-600">Full name</label>
      <input class="mt-1 w-full rounded-xl border-slate-200 px-3 py-2.5 text-sm" value="Miguel Caluya">
    </div>
    <div>
      <label class="text-[12px] text-slate-600">Role</label>
      <select class="mt-1 w-full rounded-xl border-slate-200 px-3 py-2.5 text-sm">
        <option>Admin</option>
        <option>Faculty</option>
        <option>Student</option>
      </select>
    </div>
    <div class="sm:col-span-2">
      <label class="text-[12px] text-slate-600">Email</label>
      <input type="email" class="mt-1 w-full rounded-xl border-slate-200 px-3 py-2.5 text-sm" value="cmgarcia@sys.test.ph">
    </div>
    <div class="sm:col-span-2">
      <label class="text-[12px] text-slate-600">Reset password (optional)</label>
      <input type="password" class="mt-1 w-full rounded-xl border-slate-200 px-3 py-2.5 text-sm" placeholder="••••••••">
    </div>
  </div>
  <div class="mt-5 flex items-center justify-end gap-2">
    <button class="px-3 py-2 rounded-xl text-[13px] border border-slate-200 bg-white hover:bg-slate-50" data-modal-close>Cancel</button>
    <button class="px-3 py-2 rounded-xl text-[13px] bg-yellow-400 text-slate-900 hover:brightness-95 inline-flex items-center gap-2">
      <i data-lucide="save" class="h-4 w-4"></i> Save changes
    </button>
  </div>
</x-modal>
