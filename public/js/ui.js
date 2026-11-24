/* ==========================================================================
   UEMF Insights — shared UI enhancements (presentation only)
   1. Loading state on the clicked submit button once a form really submits.
   2. Client-side pagination for tables marked with data-paginate="N".
      Works alongside the pages' own filters: rows those filters hide with
      style.display = 'none' are skipped, and paging uses a CSS class only.
   ========================================================================== */
(function () {
  'use strict';

  /* ---------- 1. Submit loading state ---------------------------------- */
  document.addEventListener('submit', function (e) {
    var form = e.target;
    var btn = e.submitter || (form && form.querySelector('[type="submit"]'));
    // Run after every other submit handler so validation can still cancel it.
    setTimeout(function () {
      if (e.defaultPrevented || !btn || form.hasAttribute('data-no-loading')) return;
      // Never disable the button: its name/value (e.g. "save_quit") must be submitted.
      btn.classList.add('is-loading');
      btn.setAttribute('aria-busy', 'true');
    }, 0);
  });

  // Back/forward cache: clear any spinner left on the page.
  window.addEventListener('pageshow', function () {
    document.querySelectorAll('.is-loading').forEach(function (el) {
      el.classList.remove('is-loading');
      el.removeAttribute('aria-busy');
    });
  });

  /* ---------- 2. Table pager ------------------------------------------- */
  function isDataRow(row) {
    return row.cells.length > 1 && row.id !== 'emptyState';
  }

  function initPager(table) {
    if (table._uiPager || !table.tBodies[0]) return;
    var tbody = table.tBodies[0];
    var size = parseInt(table.getAttribute('data-paginate'), 10) || 10;
    var page = 1;
    var anchor = table.closest('.table-responsive') || table;

    var nav = document.createElement('nav');
    nav.className = 'table-pager';
    nav.setAttribute('aria-label', 'Pagination');
    nav.innerHTML = '<span class="table-pager__info" aria-live="polite"></span>' +
                    '<div class="table-pager__pages"></div>';
    anchor.insertAdjacentElement('afterend', nav);
    var info = nav.querySelector('.table-pager__info');
    var pages = nav.querySelector('.table-pager__pages');

    function button(label, target, opts) {
      var b = document.createElement('button');
      b.type = 'button';
      b.className = 'table-pager__btn';
      b.innerHTML = label;
      if (opts && opts.aria) b.setAttribute('aria-label', opts.aria);
      if (opts && opts.current) b.setAttribute('aria-current', 'page');
      if (opts && opts.disabled) b.disabled = true;
      b.addEventListener('click', function () { page = target; render(); });
      return b;
    }

    function render() {
      var rows = Array.prototype.filter.call(tbody.rows, isDataRow);
      rows.forEach(function (r) { r.classList.remove('is-paged-out'); });
      var visible = rows.filter(function (r) { return r.style.display !== 'none'; });
      var total = visible.length;
      var count = Math.max(1, Math.ceil(total / size));
      page = Math.min(Math.max(1, page), count);

      visible.forEach(function (r, i) {
        if (i < (page - 1) * size || i >= page * size) r.classList.add('is-paged-out');
      });

      nav.hidden = total <= size;
      var from = total ? (page - 1) * size + 1 : 0;
      var to = Math.min(page * size, total);
      info.textContent = from + '–' + to + ' / ' + total;

      pages.innerHTML = '';
      pages.appendChild(button('&lsaquo;', page - 1, { aria: 'Previous page', disabled: page === 1 }));
      for (var p = 1; p <= count; p++) {
        // Compact: first, last, and a window around the current page.
        if (p === 1 || p === count || Math.abs(p - page) <= 1) {
          pages.appendChild(button(String(p), p, { current: p === page }));
        } else if (Math.abs(p - page) === 2) {
          var gap = document.createElement('span');
          gap.className = 'table-pager__btn';
          gap.style.border = '0';
          gap.style.background = 'transparent';
          gap.style.display = 'inline-flex';
          gap.style.alignItems = 'center';
          gap.textContent = '…';
          pages.appendChild(gap);
        }
      }
      pages.appendChild(button('&rsaquo;', page + 1, { aria: 'Next page', disabled: page === count }));
    }

    var timer;
    new MutationObserver(function (mutations) {
      // A filter changing row visibility sends the user back to page 1.
      if (mutations.some(function (m) { return m.type === 'attributes'; })) page = 1;
      clearTimeout(timer);
      timer = setTimeout(render, 30);
    }).observe(tbody, { childList: true, subtree: true, attributes: true, attributeFilter: ['style'] });

    table._uiPager = { render: render };
    render();
  }

  function scan(root) {
    (root.querySelectorAll ? root : document).querySelectorAll('table[data-paginate]').forEach(initPager);
  }

  document.addEventListener('DOMContentLoaded', function () {
    scan(document);
    // Tables re-rendered by page scripts (e.g. Employees filter) are picked up too.
    new MutationObserver(function (mutations) {
      mutations.forEach(function (m) {
        m.addedNodes.forEach(function (n) {
          if (n.nodeType !== 1) return;
          if (n.matches && n.matches('table[data-paginate]')) initPager(n);
          else if (n.querySelector) scan(n);
        });
      });
    }).observe(document.body, { childList: true, subtree: true });
  });
})();
