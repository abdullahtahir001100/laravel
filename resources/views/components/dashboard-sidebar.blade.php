<style>
  /* Ensuring the custom radius is exactly 5px as requested */
  .rounded-custom {
    border-radius: 5px !important;
  }
  
  /* Standardizing nav-link look */
  .nav-link {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.625rem 0.75rem;
    font-size: 0.875rem;
    font-weight: 500;
    color: #475569; /* slate-600 */
    transition: all 0.2s;
  }

  .nav-link:hover {
    background-color: #f8fafc; /* slate-50 */
    color: #1e293b; /* slate-900 */
  }

  .nav-link.active {
    background-color: #eff6ff; /* blue-50 */
    color: #1d4ed8; /* blue-700 */
  }

  .nav-link svg {
    width: 1.25rem;
    height: 1.25rem;
  }
</style>

<aside id="sidebar" class="fixed lg:sticky top-16 left-0 h-[calc(100vh-4rem)] lg:h-[calc(100vh-4rem)] w-72 lg:w-64 border-r border-slate-200 bg-white z-30 -translate-x-full lg:translate-x-0 transition-transform shadow-none self-start">
    <div class="h-full flex flex-col justify-between py-5">
        <div class="px-4 overflow-y-auto">
            <div class="mb-5 px-1">
                <p class="text-[10px] uppercase tracking-[0.35em] text-slate-400 mb-2">Navigation</p>
                <h2 class="text-base font-bold text-slate-900 leading-tight">Connect Menu</h2>
            </div>

            <div class="space-y-1">
                <a href="/" class="nav-link active rounded-custom">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                    <span class="flex-1">Home Feed</span>
                </a>

                <a href="/Posts" class="nav-link rounded-custom">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10l4 4v10a2 2 0 01-2 2z"></path><path d="M14 2v6h6"></path><path d="M8 13h8M8 17h8"></path></svg>
                    <span class="flex-1">Posts</span>
                </a>

                <a href="/discover" class="nav-link rounded-custom">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                    <span class="flex-1">Live</span>
                    <span class="text-[10px] px-2 py-0.5 rounded-full bg-red-50 text-red-600">Live</span>
                </a>

                <a href="/reels" class="nav-link rounded-custom">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M7 4V20M17 4V20M3 8h4m10 0h4M3 12h18M3 16h4m10 0h4M4 20h16a1 1 0 001-1V5a1 1 0 00-1-1H4a1 1 0 00-1 1v14a1 1 0 001 1z"></path></svg>
                    <span class="flex-1">Reels</span>
                </a>

                <a href="/messages" class="nav-link rounded-custom">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
                    <span class="flex-1">Messages</span>
                </a>

                <a href="/notifications" class="nav-link rounded-custom">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                    <span class="flex-1">Notifications</span>
                </a>

                <a href="/create" class="nav-link rounded-custom text-blue-600 font-bold bg-blue-50/50">
                    <svg fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M12 4v16m8-8H4"></path></svg>
                    <span class="flex-1">Create Post</span>
                </a>
            </div>

            <div class="mt-5 pt-4 border-t border-slate-100">
                <p class="text-[10px] uppercase tracking-[0.35em] text-slate-400 mb-3 px-1">Discover</p>
                <div class="space-y-1">
                    <a href="/users" class="nav-link rounded-custom">
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        <span class="flex-1">Search Users</span>
                    </a>
                    <a href="/users" class="nav-link rounded-custom">
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        <span class="flex-1">User Directory</span>
                    </a>
                </div>
            </div>
        </div>

        <div class="px-4 pt-4 border-t border-slate-100">
            <a href="#profile" class="flex items-center gap-3 rounded-custom border border-slate-200 bg-slate-50 px-3 py-3 hover:border-blue-200 transition-colors">
                <img src="https://api.dicebear.com/7.x/avataaars/svg?seed=Sheri" alt="Sheri" class="w-10 h-10 rounded-custom bg-white border border-slate-200">
                <div class="min-w-0">
                    <p class="text-sm font-semibold text-slate-900 truncate">Sheri Official</p>
                    <p class="text-xs text-slate-500 truncate">View Profile</p>
                </div>
            </a>

            <a href="/settings" class="mt-3 nav-link rounded-custom justify-between">
                <span class="flex items-center gap-3 min-w-0">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    <span>Settings</span>
                </span>
                <span class="text-[10px] px-2 py-0.5 rounded-full bg-slate-100 text-slate-500">Ctrl+,</span>
            </a>
        </div>
    </div>
</aside>