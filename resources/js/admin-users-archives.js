// resources/js/admin-users-archives.js
// Restore & Delete actions on the Archived Users page

// If you bundle SweetAlert2 with Vite, uncomment the line below.
// import Swal from 'sweetalert2';

document.addEventListener('DOMContentLoaded', () => {
  const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

  // keep context between opening modal and confirming
  const state = { row: null, id: null, restoreUrl: null, destroyUrl: null };

  // helpers
  const setFromBtn = (btn, kind) => {
    state.row = btn.closest('tr') ?? null;
    state.id = btn.dataset.userId || state.row?.dataset.rowId || null;
    if (kind === 'restore') state.restoreUrl = btn.dataset.restoreUrl || null;
    if (kind === 'delete')  state.destroyUrl  = btn.dataset.destroyUrl  || null;
  };

  const hideModal = (id) => document.getElementById(id)?.classList.add('hidden');

  const toast = (icon, title, text) => {
    if (window.Swal) {
      window.Swal.fire({ icon, title, text, timer: 1500, showConfirmButton: false });
    } else {
      alert(`${title}${text ? '\n' + text : ''}`);
    }
  };

  const tryJson = async (res) => {
    try { return await res.json(); } catch { return null; }
  };

  const api = (url, method) => fetch(url, {
    method,
    headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' },
    credentials: 'same-origin',
  });

  // Event delegation: stash row + URLs when opening modals
  document.addEventListener('click', (e) => {
    const restoreBtn = e.target.closest('[data-modal-target="modalRestore"]');
    if (restoreBtn) setFromBtn(restoreBtn, 'restore');

    const deleteBtn = e.target.closest('[data-modal-target="modalDelete"]');
    if (deleteBtn) setFromBtn(deleteBtn, 'delete');
  });

  // Confirm Restore
  const restoreConfirm = document.getElementById('restoreConfirmBtn');
  restoreConfirm?.addEventListener('click', async () => {
    if (!state.restoreUrl) return;

    restoreConfirm.disabled = true;
    try {
      const res = await api(state.restoreUrl, 'POST');
      if (!res.ok) {
        const payload = await tryJson(res);
        throw new Error(payload?.message || 'Failed to restore user.');
      }

      hideModal('modalRestore');
      (document.querySelector(`tr[data-row-id="${CSS.escape(state.id ?? '')}"]`) || state.row)?.remove();
      toast('success', 'Restored', 'The user has been restored.');
    } catch (err) {
      toast('error', 'Restore failed', err?.message || 'Something went wrong.');
    } finally {
      restoreConfirm.disabled = false;
      state.row = state.id = state.restoreUrl = null;
    }
  });

  // Confirm Delete
  const deleteConfirm = document.getElementById('deleteConfirmBtn');
  deleteConfirm?.addEventListener('click', async () => {
    if (!state.destroyUrl) return;

    deleteConfirm.disabled = true;
    try {
      const res = await api(state.destroyUrl, 'DELETE');
      if (!res.ok) {
        const payload = await tryJson(res);
        throw new Error(payload?.message || 'Failed to delete user.');
      }

      hideModal('modalDelete');
      (document.querySelector(`tr[data-row-id="${CSS.escape(state.id ?? '')}"]`) || state.row)?.remove();
      toast('success', 'Deleted', 'The user has been permanently deleted.');
    } catch (err) {
      toast('error', 'Delete failed', err?.message || 'Something went wrong.');
    } finally {
      deleteConfirm.disabled = false;
      state.row = state.id = state.destroyUrl = null;
    }
  });
});
