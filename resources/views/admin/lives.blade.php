<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connect | Manage Lives</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="{{ asset('app.css') }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background: var(--bg-main); color: var(--text-main); }
        .rounded-custom { border-radius: 5px !important; }
        .soft-card { background: var(--bg-card); backdrop-filter: blur(10px); border: 1px solid var(--border-main); box-shadow: 0 10px 30px rgba(15, 23, 42, 0.05); }
        .live-media-preview { aspect-ratio: 16/9; object-fit: cover; }
    </style>
</head>
<body class="h-screen overflow-hidden">
    <x-dashboard-header />

    <div class="flex pt-16 h-screen overflow-hidden">
        <x-dashboard-sidebar />

        <main class="flex-1 h-full overflow-y-auto p-4 md:p-8 scrollbar-hide">
            <div class="max-w-7xl mx-auto">
                <div class="mb-8">
                    <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Live Session Audit</h1>
                    <p class="text-slate-500 mt-2">Monitor and manage ongoing and past live stream sessions.</p>
                </div>

                @if(session('success'))
                <div class="mb-6 p-4 bg-emerald-50 border border-emerald-100 text-emerald-700 rounded-custom text-sm font-bold animate-pulse">
                    {{ session('success') }}
                </div>
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($lives as $live)
                    <div class="soft-card rounded-custom overflow-hidden live-card opacity-0">
                        <div class="relative">
                            @if($live->media_path)
                                <video class="live-media-preview w-full bg-black" muted loop onmouseover="this.play()" onmouseout="this.pause()">
                                    <source src="{{ asset('storage/' . $live->media_path) }}" type="video/mp4">
                                </video>
                            @else
                                <div class="live-media-preview w-full bg-slate-900 flex items-center justify-center">
                                    <div class="flex flex-col items-center gap-2">
                                        <div class="w-12 h-12 bg-red-500/20 text-red-500 rounded-full flex items-center justify-center animate-pulse">
                                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/></svg>
                                        </div>
                                        <span class="text-xs font-bold text-white uppercase tracking-widest">Live Stream</span>
                                    </div>
                                </div>
                            @endif
                            <div class="absolute top-4 left-4 z-10">
                                <span class="px-2 py-1 bg-red-600 text-white text-[10px] font-black rounded-custom shadow-lg">LIVE</span>
                            </div>
                        </div>

                        <div class="p-4">
                            <div class="flex items-center gap-3 mb-4">
                                <img src="{{ $live->user->avatar_path ? asset('storage/' . $live->user->avatar_path) : 'https://api.dicebear.com/7.x/avataaars/svg?seed=' . urlencode($live->user->display_name) }}" class="w-8 h-8 rounded-custom border border-slate-200" alt="">
                                <div>
                                    <h3 class="text-sm font-bold text-slate-900">{{ $live->user->display_name }}</h3>
                                    <p class="text-[10px] text-slate-400 font-bold uppercase">{{ $live->created_at->format('M d, Y @ H:i') }}</p>
                                </div>
                            </div>
                            
                            <h4 class="text-sm font-bold text-slate-800 mb-2 truncate">{{ $live->title }}</h4>

                            <div class="flex items-center justify-between pt-4 border-t border-slate-100 mt-4">
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                    <span class="text-xs font-bold text-slate-500">{{ max(0, $live->likes_count ?? 0) }} Viewers</span>
                                </div>
                                <div class="flex items-center gap-1">
                                    <a href="{{ route('admin.content.edit', $live) }}" class="p-2 text-blue-600 hover:bg-blue-50 rounded-custom transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    </a>
                                    <form action="{{ route('admin.content.destroy', $live) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 text-red-500 hover:bg-red-50 rounded-custom transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <div class="mt-8">
                    {{ $lives->links() }}
                </div>
            </div>
        </main>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            gsap.to('.live-card', { 
                opacity: 1, 
                y: 0, 
                stagger: 0.1, 
                duration: 0.8, 
                ease: "power3.out" 
            });
        });
    </script>
    <script src="{{ asset('app.js') }}"></script>
</body>
</html>
