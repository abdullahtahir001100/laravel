<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connect | Admin Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="{{ asset('app.css') }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/animejs/3.2.1/anime.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background: var(--bg-main); color: var(--text-main); }
        .rounded-custom { border-radius: 5px !important; }
        .soft-card { background: var(--bg-card); backdrop-filter: blur(10px); border: 1px solid var(--border-main); box-shadow: 0 10px 30px rgba(15, 23, 42, 0.05); }
        .admin-stat-card { transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
        .admin-stat-card:hover { transform: translateY(-5px); border-color: #3b82f6; }
        .user-swiper { padding-bottom: 40px !important; }
        .swiper-pagination-bullet-active { background: #3b82f6 !important; }
    </style>
</head>
<body class="h-screen overflow-hidden">
    <x-dashboard-header />

    <div class="flex pt-16 h-screen overflow-hidden">
        <x-dashboard-sidebar />

        <main class="flex-1 h-full overflow-y-auto p-4 md:p-8 scrollbar-hide">
            <div class="max-w-7xl mx-auto">
                <div class="mb-8" id="admin-welcome">
                    <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Super Admin Controls</h1>
                    <p class="text-slate-500 mt-2">Welcome back, Abdullah. System status: <span class="text-emerald-500 font-bold">Operational</span></p>
                </div>

                <!-- Stats Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10" id="stats-grid">
                    <div class="soft-card p-6 rounded-custom admin-stat-card opacity-0">
                        <div class="flex items-center gap-4">
                            <div class="p-3 bg-blue-50 text-blue-600 rounded-custom">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                            </div>
                            <div>
                                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Total Users</p>
                                <h3 class="text-2xl font-extrabold text-slate-900">{{ $stats['users'] }}</h3>
                            </div>
                        </div>
                    </div>

                    <div class="soft-card p-6 rounded-custom admin-stat-card opacity-0">
                        <div class="flex items-center gap-4">
                            <div class="p-3 bg-indigo-50 text-indigo-600 rounded-custom">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"></path></svg>
                            </div>
                            <div>
                                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Total Posts</p>
                                <h3 class="text-2xl font-extrabold text-slate-900">{{ $stats['posts'] }}</h3>
                            </div>
                        </div>
                    </div>

                    <div class="soft-card p-6 rounded-custom admin-stat-card opacity-0">
                        <div class="flex items-center gap-4">
                            <div class="p-3 bg-fuchsia-50 text-fuchsia-600 rounded-custom">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M7 4V20M17 4V20M3 8h4m10 0h4M3 12h18M3 16h4m10 0h4M4 20h16a1 1 0 001-1V5a1 1 0 00-1-1H4a1 1 0 00-1 1v14a1 1 0 001 1z"></path></svg>
                            </div>
                            <div>
                                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Reels</p>
                                <h3 class="text-2xl font-extrabold text-slate-900">{{ $stats['reels'] }}</h3>
                            </div>
                        </div>
                    </div>

                    <div class="soft-card p-6 rounded-custom admin-stat-card opacity-0">
                        <div class="flex items-center gap-4">
                            <div class="p-3 bg-red-50 text-red-600 rounded-custom">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                            </div>
                            <div>
                                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Live Streams</p>
                                <h3 class="text-2xl font-extrabold text-slate-900">{{ $stats['lives'] }}</h3>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mb-10 opacity-0 admin-section">
                    <h3 class="text-lg font-bold text-slate-900 mb-6 flex items-center gap-2">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        Recently Joined Users
                    </h3>
                    <div class="swiper user-swiper rounded-custom">
                        <div class="swiper-wrapper">
                            @php $recentUsers = \App\Models\User::latest()->take(10)->get(); @endphp
                            @foreach($recentUsers as $user)
                            <div class="swiper-slide">
                                <div class="soft-card p-4 flex flex-col items-center text-center">
                                    <img src="{{ $user->avatar_path ? asset('storage/' . $user->avatar_path) : 'https://api.dicebear.com/7.x/avataaars/svg?seed=' . urlencode($user->display_name) }}" class="w-16 h-16 rounded-full border-2 border-slate-100 mb-3" alt="">
                                    <h4 class="text-sm font-bold text-slate-900 truncate w-full">{{ $user->display_name }}</h4>
                                    <p class="text-[10px] text-slate-400 font-bold uppercase mt-1">{{ $user->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        <div class="swiper-pagination"></div>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                    <div class="soft-card p-6 rounded-custom opacity-0 admin-section">
                        <h3 class="text-lg font-bold text-slate-900 mb-6 flex items-center gap-2">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                            Quick Actions
                        </h3>
                        <div class="grid grid-cols-2 gap-4">
                            <a href="{{ route('admin.users') }}" class="flex flex-col items-center justify-center p-6 bg-slate-50 rounded-custom hover:bg-blue-50 transition-colors group">
                                <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center shadow-sm mb-3 group-hover:scale-110 transition-transform">
                                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                                </div>
                                <span class="text-sm font-bold text-slate-700">Manage Users</span>
                            </a>
                            <a href="{{ route('admin.posts') }}" class="flex flex-col items-center justify-center p-6 bg-slate-50 rounded-custom hover:bg-indigo-50 transition-colors group">
                                <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center shadow-sm mb-3 group-hover:scale-110 transition-transform">
                                    <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"></path></svg>
                                </div>
                                <span class="text-sm font-bold text-slate-700">Audit Content</span>
                            </a>
                        </div>
                    </div>

                    <div class="soft-card p-6 rounded-custom opacity-0 admin-section">
                        <h3 class="text-lg font-bold text-slate-900 mb-6 flex items-center gap-2">
                            <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            System Overview
                        </h3>
                        <div class="space-y-4">
                            <div class="flex items-center justify-between p-4 bg-slate-50 rounded-custom">
                                <div class="flex items-center gap-3">
                                    <div class="w-2 h-2 rounded-full bg-emerald-500"></div>
                                    <span class="text-sm font-semibold text-slate-700">Database Connection</span>
                                </div>
                                <span class="text-xs font-bold text-emerald-600">ACTIVE</span>
                            </div>
                            <div class="flex items-center justify-between p-4 bg-slate-50 rounded-custom">
                                <div class="flex items-center gap-3">
                                    <div class="w-2 h-2 rounded-full bg-emerald-500"></div>
                                    <span class="text-sm font-semibold text-slate-700">Storage System</span>
                                </div>
                                <span class="text-xs font-bold text-emerald-600">STABLE</span>
                            </div>
                            <div class="flex items-center justify-between p-4 bg-slate-50 rounded-custom">
                                <div class="flex items-center gap-3">
                                    <div class="w-2 h-2 rounded-full bg-blue-500"></div>
                                    <span class="text-sm font-semibold text-slate-700">API Latency</span>
                                </div>
                                <span class="text-xs font-bold text-blue-600">42ms</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Entrance Animations
            gsap.from('#admin-welcome', { y: -20, opacity: 0, duration: 0.8, ease: "power3.out" });
            gsap.to('.admin-stat-card', { 
                opacity: 1, 
                y: 0, 
                stagger: 0.1, 
                duration: 0.8, 
                delay: 0.2, 
                ease: "back.out(1.7)" 
            });
            gsap.to('.admin-section', { 
                opacity: 1, 
                y: 0, 
                stagger: 0.2, 
                duration: 0.8, 
                delay: 0.6, 
                ease: "power3.out" 
            });

            // Initialize Swiper
            new Swiper('.user-swiper', {
                slidesPerView: 2,
                spaceBetween: 20,
                pagination: {
                    el: '.swiper-pagination',
                    clickable: true,
                },
                breakpoints: {
                    640: { slidesPerView: 3 },
                    768: { slidesPerView: 4 },
                    1024: { slidesPerView: 5 },
                    1280: { slidesPerView: 6 },
                }
            });
        });
    </script>
    <script src="{{ asset('app.js') }}"></script>
</body>
</html>
