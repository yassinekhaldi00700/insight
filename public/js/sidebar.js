// Sidebar Shared JavaScript Functions
function initializeSidebar() {
  // Theme Toggle
  const themeToggle = document.getElementById('themeToggle');
  const body = document.body;
  const lightIcon = document.getElementById('lightIcon');
  const darkIcon = document.getElementById('darkIcon');

  if (!themeToggle || !lightIcon || !darkIcon) {
    console.warn('Theme toggle elements not found');
    return;
  }

  // Check for saved theme preference
  const savedTheme = localStorage.getItem('theme');
  if (savedTheme === 'dark') {
    body.classList.add('dark-theme');
    lightIcon.style.display = 'none';
    darkIcon.style.display = 'inline';
  } else {
    body.classList.remove('dark-theme');
    lightIcon.style.display = 'inline';
    darkIcon.style.display = 'none';
  }

  themeToggle.addEventListener('click', () => {
    body.classList.toggle('dark-theme');
    
    if (body.classList.contains('dark-theme')) {
      lightIcon.style.display = 'none';
      darkIcon.style.display = 'inline';
      localStorage.setItem('theme', 'dark');
    } else {
      lightIcon.style.display = 'inline';
      darkIcon.style.display = 'none';
      localStorage.setItem('theme', 'light');
    }
  });

  // Language Toggle
  const languageToggle = document.getElementById('languageToggle');
  const currentLangSpan = document.getElementById('currentLang');

  if (!languageToggle || !currentLangSpan) {
    console.warn('Language toggle elements not found');
    return;
  }

  let currentLanguage = localStorage.getItem('language') || 'en';

  // Set initial language
  setLanguage(currentLanguage);

  languageToggle.addEventListener('click', function() {
    currentLanguage = currentLanguage === 'en' ? 'fr' : 'en';
    setLanguage(currentLanguage);
    localStorage.setItem('language', currentLanguage);
  });

  function setLanguage(lang) {
    const elements = document.querySelectorAll('[data-en][data-fr]');
    
    elements.forEach(element => {
      if (lang === 'fr' && element.getAttribute('data-fr')) {
        element.textContent = element.getAttribute('data-fr');
      } else if (element.getAttribute('data-en')) {
        element.textContent = element.getAttribute('data-en');
      }
    });

    // Update option elements
    const options = document.querySelectorAll('option[data-en][data-fr]');
    options.forEach(option => {
      if (lang === 'fr' && option.getAttribute('data-fr')) {
        option.textContent = option.getAttribute('data-fr');
      } else if (option.getAttribute('data-en')) {
        option.textContent = option.getAttribute('data-en');
      }
    });

    // Update placeholders
    const placeholders = document.querySelectorAll('[data-placeholder-en][data-placeholder-fr]');
    placeholders.forEach(element => {
      if (lang === 'fr' && element.getAttribute('data-placeholder-fr')) {
        element.placeholder = element.getAttribute('data-placeholder-fr');
      } else if (element.getAttribute('data-placeholder-en')) {
        element.placeholder = element.getAttribute('data-placeholder-en');
      }
    });

    currentLangSpan.textContent = lang.toUpperCase();
  }

  // Expose public API
  window.SidebarAPI = {
    setLanguage: setLanguage,
    getCurrentLanguage: function() {
      return localStorage.getItem('language') || 'en';
    },
    getCurrentTheme: function() {
      return localStorage.getItem('theme') || 'light';
    }
  };
}

// Mobile off-canvas sidebar
function initializeSidebarDrawer() {
  const toggle = document.getElementById('sidebarToggle');
  const backdrop = document.getElementById('sidebarBackdrop');
  const sidebar = document.getElementById('appSidebar');
  if (!toggle || !sidebar) return;

  function setOpen(open) {
    document.body.classList.toggle('sidebar-open', open);
    toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
    toggle.setAttribute('aria-label', open ? 'Close menu' : 'Open menu');
  }

  toggle.addEventListener('click', function() {
    setOpen(!document.body.classList.contains('sidebar-open'));
  });
  if (backdrop) backdrop.addEventListener('click', function() { setOpen(false); });
  document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape' && document.body.classList.contains('sidebar-open')) {
      setOpen(false);
      toggle.focus();
    }
  });
  // Close the drawer when navigating from it
  sidebar.querySelectorAll('a.sidebar-menu-item').forEach(function(link) {
    link.addEventListener('click', function() { setOpen(false); });
  });
  window.addEventListener('resize', function() {
    if (window.innerWidth >= 992) setOpen(false);
  });
}

// Initialize sidebar when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
  initializeSidebarDrawer();
  initializeSidebar();
});
