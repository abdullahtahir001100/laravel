<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connect | Manage Reels</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="{{ asset('app.css') }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background: var(--bg-main); color: var(--text-main); }
        .rounded-custom { border-radius: 5px !important; }
        .soft-card { background: var(--bg-card); backdrop-filter: blur(10px); border: 1px solid var(--border-main); box-shadow: 0 10px 30px rgba(15, 23, 42, 0.05); }
        .reel-media-preview { aspect-ratio: 9/16; object-fit: cover; }
    </style>
</head>
<body class="h-screen overflow-hidden">
    <x-dashboard-header />

    <div class="flex pt-16 h-screen overflow-hidden">
        <x-dashboard-sidebar />

        <main class="flex-1 h-full overflow-y-auto p-4 md:p-8 scrollbar-hide">
            <div class="max-w-7xl mx-auto">
                <div class="mb-8">
                    <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Reel Audit</h1>
                    <p class="text-slate-500 mt-2">Manage vertical video content and reel engagement.</p>
                </div>

                @if(session('success'))
                <div class="mb-6 p-4 bg-emerald-50 border border-emerald-100 text-emerald-700 rounded-custom text-sm font-bold animate-pulse">
                    {{ session('success') }}
                </div>
                @endif

                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6">
                    @foreach($reels as $reel)
                    <div class="soft-card rounded-custom overflow-hidden reel-card opacity-0">
                        <div class="relative">
                            @if($reel->media_path)
                                <video class="reel-media-preview w-full bg-black" muted loop onmouseover="this.play()" onmouseout="this.pause()">
                                    <source src="{{ asset('storage/' . $reel->media_path) }}" type="video/mp4">
                                </video>
                            @else
                                <div class="reel-media-preview w-full bg-slate-100 flex items-center justify-center">
                                    <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                                </div>
                            @endif
                            <div class="absolute bottom-0 left-0 w-full p-3 bg-gradient-to-t from-black/60 to-transparent">
                                <div class="flex items-center gap-2">
                                    <img src="{{ $reel->user->avatar_path ? asset('storage/' . $reel->user->avatar_path) : 'https://api.dicebear.com/7.x/avataaars/svg?seed=' . urlencode($reel->user->display_name) }}" class="w-5 h-5 rounded-full border border-white" alt="">
                                    <span class="text-[10px] font-bold text-white truncate">{{ $reel->user->display_name }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="p-3">
                            <h3 class="text-[11px] font-bold text-slate-900 mb-1 truncate">{{ $reel->title }}</h3>
                            <div class="flex items-center justify-between mt-3">
                                <span class="text-[9px] font-bold text-slate-400">{{ $reel->created_at->format('M d') }}</span>
                                <div class="flex items-center gap-1">
                                    <a href="{{ route('admin.content.edit', $reel) }}" class="p-1.5 text-blue-600 hover:bg-blue-50 rounded-custom transition-colors">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    </a>
                                    <form action="{{ route('admin.content.destroy', $reel) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 text-red-500 hover:bg-red-50 rounded-custom transition-colors">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <div class="mt-8">
                    {{ $reels->links() }}
                </div>
            </div>
        </main>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            gsap.to('.reel-card', { 
                opacity: 1, 
                scale: 1, 
                stagger: 0.05, 
                duration: 0.5, 
                ease: "power2.out" 
            });
        });
    </script>
    <script src="{{ asset('app.js') }}"></script>
</body>
</html>
