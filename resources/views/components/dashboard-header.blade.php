<style>
  .theme-dark {
    color-scheme: dark;
    background: #0b1220;
    color: #e2e8f0;
  }

  .theme-dark body {
    background: radial-gradient(circle at top left, rgba(14, 165, 233, 0.12), transparent 35%), #0b1220 !important;
    color: #e2e8f0 !important;
  }

  .theme-dark .bg-white,
  .theme-dark [class*="bg-white"] {
    background-color: #111b30 !important;
  }

  .theme-dark .bg-slate-50,
  .theme-dark .bg-slate-100,
  .theme-dark [class*="bg-slate-50"],
  .theme-dark [class*="bg-slate-100"] {
    background-color: #18253f !important;
  }

  .theme-dark .text-slate-900,
  .theme-dark .text-slate-800,
  .theme-dark .text-slate-700,
  .theme-dark .text-slate-600,
  .theme-dark .text-slate-500,
  .theme-dark .text-slate-400 {
    color: #d1d5db !important;
  }

  .theme-dark .border-slate-50,
  .theme-dark .border-slate-100,
  .theme-dark .border-slate-200,
  .theme-dark .border-slate-300,
  .theme-dark [class*="border-slate-"] {
    border-color: #2a3a5b !important;
  }

  .app-header {
    transition: background-color 0.25s ease, border-color 0.25s ease;
  }

  .theme-switch {
    min-width: 2.75rem;
    height: 2.25rem;
  }

  #sidebar {
    will-change: transform;
  }

  .sidebar-hover-rail {
    position: fixed;
    top: 4rem;
    left: 0;
    width: 14px;
    height: calc(100vh - 4rem);
    z-index: 31;
    background: transparent;
  }

  .sidebar-open {
    transform: translateX(0) !important;
    box-shadow: 0 22px 44px rgba(15, 23, 42, 0.16);
  }
</style>

<header
  class="app-header h-16 bg-white border-b border-slate-200 fixed top-0 w-full z-40 flex items-center justify-between px-4 md:px-6">

  <div class="flex items-center gap-3">
    <button id="mobile-sidebar-toggle"
      class="p-2 rounded-custom border border-slate-200 text-slate-600 hover:text-blue-600 hover:border-blue-200 transition-colors"
      aria-label="Open sidebar">
      <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path d="M4 6h16M4 12h16M4 18h16" stroke-width="2" stroke-linecap="round"
          stroke-linejoin="round"></path>
      </svg>
    </button>
    <div class="flex items-center gap-2">
      <svg width="32" height="32" viewBox="0 0 32 32" fill="none">
        <rect width="32" height="32" rx="5" fill="#0062ff" />
        <path d="M10 16L14 20L22 12" stroke="white" stroke-width="3" stroke-linecap="round"
          stroke-linejoin="round" />
      </svg>
      <span class="font-bold text-xl tracking-tight">Connect</span>
    </div>
  </div>

  <div class="hidden md:block relative w-96">
    <div class="bg-slate-50 px-4 py-2 rounded-custom border border-slate-100 focus-within:border-blue-400 transition-all">
      <input id="header-content-search" type="text" placeholder="Search content..." autocomplete="off"
        class="bg-transparent w-full outline-none text-sm">
    </div>
    <div id="header-search-results"
      class="hidden absolute top-full left-0 mt-2 w-full bg-white border border-slate-200 rounded-custom shadow-xl p-2 z-50"></div>
  </div>

  <div class="flex items-center gap-2">
    <button id="theme-toggle"
      class="theme-switch inline-flex items-center justify-center gap-2 px-3 rounded-custom border border-slate-200 text-slate-600 hover:text-blue-600 hover:border-blue-200 transition-colors"
      type="button"
      aria-label="Toggle dark and light theme"
      title="Toggle theme">
      <svg id="theme-toggle-sun" class="w-4 h-4 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M12 3v2m0 14v2m9-9h-2M5 12H3m15.364 6.364l-1.414-1.414M7.05 7.05 5.636 5.636m12.728 0L16.95 7.05M7.05 16.95l-1.414 1.414M12 16a4 4 0 100-8 4 4 0 000 8z"></path>
      </svg>
      <svg id="theme-toggle-moon" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M20.354 15.354A9 9 0 018.646 3.646 9 9 0 1012 21a8.96 8.96 0 008.354-5.646z"></path>
      </svg>
    </button>

    <div class="relative" id="user-menu-container">
    <div id="user-menu-button" 
      class="flex items-center gap-2 hover:bg-slate-50 p-1 rounded-custom transition-all border border-transparent hover:border-slate-100">
      <div class="w-9 h-9 bg-slate-200 rounded-custom overflow-hidden">
      <a href="{{ auth()->check() ? route('user.profile', auth()->id()) : route('login') }}">
        <img src="{{ auth()->user()?->avatar_path ? asset('storage/' . auth()->user()->avatar_path) : 'https://api.dicebear.com/7.x/avataaars/svg?seed=' . urlencode(auth()->user()?->display_name ?? auth()->user()?->first_name ?? 'User') }}" alt="User avatar">
        </a>
      </div>
      <svg class="w-4 h-4 text-slate-400 transition-transform duration-200" id="chevron-icon"
        fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path d="M19 9l-7 7-7-7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        </path>
      </svg>
    </div>

    <div id="user-dropdown"
      class="hidden absolute right-0 mt-3 w-64 bg-white rounded-custom shadow-2xl border border-slate-100 p-2 transform origin-top-right transition-all">
      <div class="p-3 border-b border-slate-50 mb-2">
        <p class="text-sm font-bold text-slate-900">{{ auth()->user()?->display_name ?? trim((auth()->user()?->first_name ?? '') . ' ' . (auth()->user()?->last_name ?? '')) ?: 'User' }}</p>
        <p class="text-xs text-slate-500">{{ auth()->user()?->email ?? '' }}</p>
      </div>

      <button onclick="window.location.href='{{ route('settings.index') }}'"
        class="w-full text-left p-2 text-sm hover:bg-slate-50 rounded-custom flex items-center gap-3 text-slate-700">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-4 h-4">
          <path
            d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4">
          </path>
        </svg>
        User Settings
      </button>

      <button onclick="window.location.href='{{ route('facebook') }}'"
        class="w-full text-left p-2 text-sm hover:bg-slate-50 rounded-custom flex items-center gap-3 text-slate-700">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-4 h-4">
          <path d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
        Add Account
      </button>

      <hr class="my-2 border-slate-50">

      <button onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
        class="w-full text-left p-2 text-sm text-red-500 hover:bg-red-50 rounded-custom flex items-center gap-3">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-4 h-4">
          <path
            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
          </path>
        </svg>
        Logout
      </button>

      <form id="logout-form" method="POST" action="{{ route('logout') }}" class="hidden">
        @csrf
      </form>
    </div>
    </div>
  </div>
</header>

<script>
  function initDashboardUi() {
    const root = document.documentElement;
    const body = document.body;
    const sidebar = document.getElementById('sidebar');
    const sidebarToggle = document.getElementById('mobile-sidebar-toggle');
    const themeToggle = document.getElementById('theme-toggle');
    const sunIcon = document.getElementById('theme-toggle-sun');
    const moonIcon = document.getElementById('theme-toggle-moon');
    const menuContainer = document.getElementById('user-menu-container');
    const menuButton = document.getElementById('user-menu-button');
    const dropdown = document.getElementById('user-dropdown');

    const THEME_KEY = 'connect-theme';
    const SIDEBAR_KEY = 'connect-sidebar-pinned';

    const existingBackdrop = document.getElementById('sidebar-backdrop');
    const backdrop = existingBackdrop || (() => {
      const el = document.createElement('div');
      el.id = 'sidebar-backdrop';
      el.className = 'fixed inset-0 bg-slate-900/40 z-20 hidden';
      body.appendChild(el);
      return el;
    })();

    const rail = document.querySelector('.sidebar-hover-rail') || (() => {
      const el = document.createElement('div');
      el.className = 'sidebar-hover-rail';
      body.appendChild(el);
      return el;
    })();

    let isPinned = localStorage.getItem(SIDEBAR_KEY) === '1';
    let isHoverOpen = false;
    let hoverTimer = null;

    function setTheme(theme) {
      const isDark = theme === 'dark';
      root.classList.toggle('theme-dark', isDark);
      localStorage.setItem(THEME_KEY, isDark ? 'dark' : 'light');
      if (sunIcon && moonIcon) {
        sunIcon.classList.toggle('hidden', !isDark);
        moonIcon.classList.toggle('hidden', isDark);
      }
    }

    function getPreferredTheme() {
      const stored = localStorage.getItem(THEME_KEY);
      if (stored === 'dark' || stored === 'light') return stored;
      return window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
    }

    function openSidebar(mode) {
      if (!sidebar) return;
      sidebar.classList.add('sidebar-open');
      if (mode === 'hover') isHoverOpen = true;
      if (window.innerWidth < 1024 || isPinned) {
        backdrop.classList.remove('hidden');
      }
    }

    function closeSidebar(force) {
      if (!sidebar) return;
      if (!force && (isPinned || isHoverOpen)) return;
      sidebar.classList.remove('sidebar-open');
      backdrop.classList.add('hidden');
      isHoverOpen = false;
    }

    function syncSidebarState() {
      if (!sidebar) return;
      if (isPinned) {
        openSidebar('pinned');
      } else {
        closeSidebar(true);
      }
    }

    function toggleSidebarPin() {
      isPinned = !isPinned;
      localStorage.setItem(SIDEBAR_KEY, isPinned ? '1' : '0');
      if (isPinned) {
        isHoverOpen = false;
        openSidebar('pinned');
      } else {
        closeSidebar(true);
      }
    }

    themeToggle?.addEventListener('click', () => {
      const next = root.classList.contains('theme-dark') ? 'light' : 'dark';
      setTheme(next);
    });

    sidebarToggle?.addEventListener('click', toggleSidebarPin);
    backdrop.addEventListener('click', () => {
      isPinned = false;
      localStorage.setItem(SIDEBAR_KEY, '0');
      closeSidebar(true);
    });

    rail.addEventListener('mouseenter', () => {
      if (isPinned) return;
      clearTimeout(hoverTimer);
      openSidebar('hover');
    });

    sidebar?.addEventListener('mouseenter', () => {
      if (isPinned) return;
      clearTimeout(hoverTimer);
      openSidebar('hover');
    });

    sidebar?.addEventListener('mouseleave', () => {
      if (isPinned) return;
      hoverTimer = setTimeout(() => {
        isHoverOpen = false;
        closeSidebar(true);
      }, 120);
    });

    menuButton?.addEventListener('click', (event) => {
      event.stopPropagation();
      dropdown?.classList.toggle('hidden');
    });

    document.addEventListener('click', (event) => {
      if (menuContainer && !menuContainer.contains(event.target)) {
        dropdown?.classList.add('hidden');
      }
    });

    window.addEventListener('resize', () => {
      if (window.innerWidth < 1024 && !isPinned) {
        closeSidebar(true);
      }
      if (window.innerWidth >= 1024) {
        backdrop.classList.add('hidden');
      }
    });

    setTheme(getPreferredTheme());
    syncSidebarState();
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initDashboardUi, { once: true });
  } else {
    initDashboardUi();
  }
</script>
