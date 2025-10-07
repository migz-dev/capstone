/* =========================================================
   Fast partial transitions between /login and /register
   - Same-origin fetch + DOM swap of #page-root only
   - View Transitions when available; graceful fallback
   - In-memory cache + hover prefetch
   ========================================================= */
(() => {
  const prefersReduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  const canVT = !!document.startViewTransition && !prefersReduced;
  const isAuthPath = p => p === '/login' || p === '/register';
  const cache = new Map();                  // href -> { html, t }
  const CACHE_TTL = 30_000;                 // 30s is plenty for these pages
  let inflight = null;                      // AbortController for current fetch

  const $root = () => document.getElementById('page-root');
  const headerHeight = () => (document.querySelector('header.sticky')?.getBoundingClientRect().height || 0);

  const readCached = (href) => {
    const hit = cache.get(href);
    if (!hit) return null;
    if (Date.now() - hit.t > CACHE_TTL) { cache.delete(href); return null; }
    return hit.html;
    };
  const writeCache = (href, html) => cache.set(href, { html, t: Date.now() });

  const prefetch = (href) => {
    try {
      const url = new URL(href, location.href);
      if (!isAuthPath(url.pathname)) return;
      if (readCached(url.href)) return;
      fetch(url.href, { credentials: 'same-origin', mode: 'same-origin' })
        .then(r => r.ok ? r.text() : '')
        .then(html => { if (html) writeCache(url.href, html); })
        .catch(() => {});
    } catch {}
  };

  // Hover prefetch
  document.addEventListener('mouseenter', (e) => {
    const a = e.target.closest('a[href]');
    if (!a) return;
    prefetch(a.getAttribute('href') || '');
  }, true);

  // Core swap: parse new doc, replace #page-root, update <title>, run any inline scripts inside #page-root
  const swapContent = (nextHTML, url) => {
    const parser = new DOMParser();
    const doc = parser.parseFromString(nextHTML, 'text/html');

    const nextRoot = doc.getElementById('page-root');
    const currRoot = $root();
    if (!nextRoot || !currRoot) { location.href = url; return; }

    // Update title (and optional meta you care about)
    const newTitle = doc.querySelector('title')?.textContent ?? document.title;
    if (newTitle) document.title = newTitle;

    // View Transition or fallback
    const performSwap = () => {
      // Replace content
      currRoot.replaceWith(nextRoot);

      // Re-run any inline scripts present in the new #page-root
      nextRoot.querySelectorAll('script').forEach((oldS) => {
        const s = document.createElement('script');
        if (oldS.src) { s.src = oldS.src; s.defer = oldS.defer; s.async = oldS.async; }
        else { s.textContent = oldS.textContent; }
        // Copy type/attrs
        if (oldS.type) s.type = oldS.type;
        oldS.replaceWith(s);
      });

      // Accessibility & scroll restore (offset by sticky header)
      const firstFocusable = nextRoot.querySelector('[autofocus], input, button, [tabindex]:not([tabindex="-1"])');
      if (firstFocusable) { firstFocusable.focus({ preventScroll: true }); }
      window.scrollTo({ top: 0, left: 0 });
      // If there was a hash, scroll to it after paint
      if (url.hash) {
        setTimeout(() => {
          const id = url.hash.slice(1);
          const target = document.getElementById(id);
          if (target) {
            const top = window.scrollY + target.getBoundingClientRect().top - headerHeight() - 8;
            window.scrollTo({ top, behavior: prefersReduced ? 'auto' : 'smooth' });
            target.setAttribute('tabindex', '-1');
            target.focus({ preventScroll: true });
          }
        }, 0);
      }

      // Let app know we swapped (if you need to re-init icons/handlers)
      document.dispatchEvent(new CustomEvent('partial:navigated', { detail: { url: url.href } }));
    };

    if (canVT) {
      document.startViewTransition(performSwap);
    } else {
      const root = currRoot;
      root.style.transition = 'opacity .14s ease, transform .14s ease';
      root.style.opacity = '0';
      root.style.transform = 'translateY(6px)';
      setTimeout(() => {
        performSwap();
      }, 120);
    }
  };

  const shouldIntercept = (from, to) => isAuthPath(from.pathname) && isAuthPath(to.pathname);

  document.addEventListener('click', (e) => {
    if (e.defaultPrevented || e.button !== 0) return;
    const a = e.target.closest('a[href]');
    if (!a) return;

    if (a.hasAttribute('data-no-transition')) return;
    if (e.metaKey || e.ctrlKey || e.shiftKey || e.altKey) return;
    if (a.target && a.target !== '_self') return;
    if (a.hasAttribute('download')) return;

    let url;
    try { url = new URL(a.getAttribute('href'), location.href); } catch { return; }
    if (url.origin !== location.origin) return;

    // Ignore true in-page anchors
    if (url.pathname === location.pathname && url.hash) return;

    if (!shouldIntercept(new URL(location.href), url)) return;

    e.preventDefault();

    // Abort any previous fetch
    if (inflight) inflight.abort();
    inflight = new AbortController();

    const cached = readCached(url.href);
    if (cached) {
      history.pushState(null, '', url.href);
      swapContent(cached, url);
      return;
    }

    fetch(url.href, { signal: inflight.signal, credentials: 'same-origin', mode: 'same-origin' })
      .then(r => r.ok ? r.text() : Promise.reject(new Error('Bad status')))
      .then(html => {
        writeCache(url.href, html);
        history.pushState(null, '', url.href);
        swapContent(html, url);
      })
      .catch(() => { location.href = url.href; }); // hard navigate on failures
  });

  // Handle back/forward with our cache
  window.addEventListener('popstate', () => {
    const url = new URL(location.href);
    if (!shouldIntercept(url, url)) return; // only handle auth pages
    const cached = readCached(url.href);
    if (cached) swapContent(cached, url);
    else {
      fetch(url.href, { credentials: 'same-origin', mode: 'same-origin' })
        .then(r => r.ok ? r.text() : '')
        .then(html => html ? swapContent(html, url) : location.reload());
    }
  });
})();
