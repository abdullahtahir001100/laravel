<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connect | Professional Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root { --brand-blue: #0062ff; --bg-main: #ffffff; --border-clr: #f1f5f9; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: var(--bg-main); color: #0f172a; }
        
        .rounded-custom { border-radius: 5px !important; }
        .border-base { border: 1px solid #f1f5f9; }
        
        /* Sidebar Link Styling */
        .nav-link { 
            display: flex; align-items: center; gap: 12px; padding: 12px; 
            font-size: 14px; font-weight: 500; color: #64748b; 
            transition: all 0.2s ease;
        }
        .nav-link:hover { color: var(--brand-blue); background: #f8faff; }
        .nav-link.active { color: var(--brand-blue); background: #f0f7ff; border-right: 3px solid var(--brand-blue); border-radius: 0 5px 5px 0; }
        .nav-link svg { width: 20px; height: 20px; stroke-width: 2; }

        .no-scrollbar::-webkit-scrollbar { display: none; }
        
        #user-dropdown { 
            display: none; opacity: 0; transform: translateY(-10px); 
            z-index: 50; border: 1px solid #f1f5f9; 
        }
    </style>
</head>
<body class="overflow-hidden">

    <header class="h-16 bg-white border-b border-base fixed top-0 w-full z-40 flex items-center justify-between px-6">
        <div class="flex items-center gap-2">
            <svg width="32" height="32" viewBox="0 0 32 32" fill="none">
                <rect width="32" height="32" rx="5" fill="#0062ff"/>
                <path d="M10 16L14 20L22 12" stroke="white" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            <span class="font-bold text-xl tracking-tight">Connect</span>
        </div>

        <div class="hidden md:flex bg-slate-50 px-4 py-2 rounded-custom w-96 border border-slate-100">
            <input type="text" placeholder="Search content..." class="bg-transparent w-full outline-none text-sm">
        </div>

        <div class="relative">
            <button onclick="toggleUserMenu()" class="flex items-center gap-2 hover:bg-slate-50 p-1 rounded-custom transition-all border border-transparent hover:border-slate-100">
                <div class="w-9 h-9 bg-slate-200 rounded-custom overflow-hidden">
                    <img src="https://api.dicebear.com/7.x/avataaars/svg?seed=Sheri" alt="User">
                </div>
                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 9l-7 7-7-7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg>
            </button>

            <div id="user-dropdown" class="absolute right-0 mt-3 w-64 bg-white rounded-custom shadow-2xl p-2">
                <div class="p-3 border-b border-slate-50 mb-2">
                    <p class="text-sm font-bold text-slate-900">Sheri</p>
                    <p class="text-xs text-slate-500">itssheriofficial@mail.com</p>
                </div>
                <button class="w-full text-left p-2 text-sm hover:bg-slate-50 rounded-custom flex items-center gap-3">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-4 h-4"><path d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path></svg>
                    User Settings
                </button>
                <button class="w-full text-left p-2 text-sm hover:bg-slate-50 rounded-custom flex items-center gap-3">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-4 h-4"><path d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Add Account
                </button>
                <hr class="my-2 border-slate-50">
                <button class="w-full text-left p-2 text-sm text-red-500 hover:bg-red-50 rounded-custom flex items-center gap-3">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-4 h-4"><path d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                    Logout
                </button>
            </div>
        </div>
    </header>

    <div class="flex pt-16 h-screen">
        
        <nav class="w-64 border-r border-base bg-white flex flex-col justify-between py-6">
            <div class="px-4 space-y-1">
                <a href="#" class="nav-link active rounded-custom">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                    Home Feed
                </a>
                <a href="#" class="nav-link rounded-custom">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                    My Posts
                </a>
                <a href="#" class="nav-link rounded-custom">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                    Reels
                </a>
            </div>

            <div class="px-4 border-t border-slate-50 pt-4 mb-6">
                <a href="#" class="nav-link rounded-custom">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    Settings
                </a>
            </div>
        </nav>

        <main id="main-feed" class="flex-1 overflow-y-auto p-6 no-scrollbar bg-[#fafafa]">
            <div class="max-w-xl mx-auto space-y-6">
                
                <div class="bg-white p-4 border border-slate-200 rounded-custom">
                    <div class="flex gap-4">
                        <div class="w-10 h-10 bg-slate-100 rounded-custom"></div>
                        <input type="text" placeholder="Share an update..." class="flex-1 bg-slate-50 p-3 rounded-custom outline-none border border-transparent focus:border-blue-100 text-sm">
                    </div>
                </div>

                <div class="bg-white border border-slate-200 rounded-custom overflow-hidden">
                    <div class="p-4 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 bg-blue-50 rounded-custom flex items-center justify-center">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                            </div>
                            <div>
                                <h4 class="font-bold text-sm">Sheri Official</h4>
                                <p class="text-[10px] text-slate-400 font-semibold uppercase tracking-wider">Just Now</p>
                            </div>
                        </div>
                        <div class="relative">
                            <button onclick="togglePostOptions(this)" class="p-2 hover:bg-slate-50 rounded-custom transition-all">
                                <svg class="w-5 h-5 text-slate-400" fill="currentColor" viewBox="0 0 20 20"><path d="M6 10a2 2 0 11-4 0 2 2 0 014 0zM12 10a2 2 0 11-4 0 2 2 0 014 0zM18 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                            </button>
                            <div class="post-menu hidden absolute right-0 mt-2 w-48 bg-white shadow-xl border border-slate-100 rounded-custom p-2 z-10">
                                <button class="w-full text-left p-2 text-sm hover:bg-red-50 text-red-500 rounded-custom flex items-center gap-2 font-medium">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636"></path></svg>
                                    Not Interested
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <div class="px-4 pb-3">
                        <p class="text-sm text-slate-600">This is a production-ready social media UI. Clean, fast, and responsive.</p>
                    </div>

                    <div class="bg-slate-50 h-80 w-full flex items-center justify-center border-y border-slate-100">
                        <svg class="w-12 h-12 text-slate-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    </div>

                    <div class="p-3 flex items-center justify-between">
                        <div class="flex gap-4">
                            <button onclick="animateLike(this)" class="flex items-center gap-2 text-sm font-bold text-slate-500 hover:text-blue-600 transition-all">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                                Like
                            </button>
                            <button class="flex items-center gap-2 text-sm font-bold text-slate-500 hover:text-blue-600 transition-all">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                                Comment
                            </button>
                        </div>
                        <button class="flex items-center gap-2 text-sm font-bold text-slate-500 hover:text-blue-600 transition-all">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"></path></svg>
                            Share
                        </button>
                    </div>
                </div>

            </div>
        </main>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/animejs/3.2.1/anime.min.js"></script>
    <script src="https://cdn.jsdelivr.net/gh/studio-freight/lenis@1.0.19/bundled/lenis.min.js"></script>

    <script>
        const lenis = new Lenis({ wrapper: document.getElementById('main-feed') });
        function raf(time) { lenis.raf(time); requestAnimationFrame(raf); }
        requestAnimationFrame(raf);

        let isMenuOpen = false;
        function toggleUserMenu() {
            const menu = document.getElementById('user-dropdown');
            if(!isMenuOpen) {
                menu.style.display = 'block';
                gsap.to(menu, { opacity: 1, y: 0, duration: 0.3, ease: "power2.out" });
            } else {
                gsap.to(menu, { opacity: 0, y: -10, duration: 0.2, onComplete: () => menu.style.display = 'none' });
            }
            isMenuOpen = !isMenuOpen;
        }

        function togglePostOptions(btn) {
            const menu = btn.nextElementSibling;
            const isHidden = menu.classList.contains('hidden');
            document.querySelectorAll('.post-menu').forEach(m => m.classList.add('hidden'));
            if(isHidden) menu.classList.remove('hidden');
        }

        function animateLike(el) {
            const svg = el.querySelector('svg');
            anime({
                targets: svg,
                scale: [1, 1.6, 1],
                duration: 400,
                easing: 'spring(1, 80, 10, 0)'
            });
            el.classList.toggle('text-blue-600');
            svg.setAttribute('fill', el.classList.contains('text-blue-600') ? 'currentColor' : 'none');
        }

        window.onclick = (e) => {
            if(!e.target.closest('#user-dropdown') && !e.target.closest('button')) {
                if(isMenuOpen) toggleUserMenu();
            }
            if(!e.target.closest('.post-menu') && !e.target.closest('button')) {
                document.querySelectorAll('.post-menu').forEach(m => m.classList.add('hidden'));
            }
        };
    </script>
</body>
</html>