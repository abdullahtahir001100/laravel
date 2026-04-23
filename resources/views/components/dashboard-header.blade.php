<header
  class="h-16 bg-white border-b border-slate-200 fixed top-0 w-full z-40 flex items-center justify-between px-4 md:px-6">

  <div class="flex items-center gap-3">
    <button id="mobile-sidebar-toggle"
      class="lg:hidden p-2 rounded-custom border border-slate-200 text-slate-600 hover:text-blue-600 hover:border-blue-200 transition-colors"
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

  <div class="relative" id="user-menu-container">
    <button id="user-menu-button"
      class="flex items-center gap-2 hover:bg-slate-50 p-1 rounded-custom transition-all border border-transparent hover:border-slate-100">
      <div class="w-9 h-9 bg-slate-200 rounded-custom overflow-hidden">
        <img src="https://api.dicebear.com/7.x/avataaars/svg?seed=Sheri" alt="User avatar">
      </div>
      <svg class="w-4 h-4 text-slate-400 transition-transform duration-200" id="chevron-icon"
        fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path d="M19 9l-7 7-7-7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        </path>
      </svg>
    </button>

    <div id="user-dropdown"
      class="hidden absolute right-0 mt-3 w-64 bg-white rounded-custom shadow-2xl border border-slate-100 p-2 transform origin-top-right transition-all">
      <div class="p-3 border-b border-slate-50 mb-2">
        <p class="text-sm font-bold text-slate-900">Sheri</p>
        <p class="text-xs text-slate-500">itssheriofficial@mail.com</p>
      </div>

      <button
        class="w-full text-left p-2 text-sm hover:bg-slate-50 rounded-custom flex items-center gap-3 text-slate-700">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-4 h-4">
          <path
            d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4">
          </path>
        </svg>
        User Settings
      </button>

      <button
        class="w-full text-left p-2 text-sm hover:bg-slate-50 rounded-custom flex items-center gap-3 text-slate-700">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-4 h-4">
          <path d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
        Add Account
      </button>

      <hr class="my-2 border-slate-50">

      <button
        class="w-full text-left p-2 text-sm text-red-500 hover:bg-red-50 rounded-custom flex items-center gap-3">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-4 h-4">
          <path
            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
          </path>
        </svg>
        Logout
      </button>
    </div>
  </div>
</header>
