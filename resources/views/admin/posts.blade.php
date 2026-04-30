<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connect | Manage Posts</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="{{ asset('app.css') }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background: var(--bg-main); color: var(--text-main); }
        .rounded-custom { border-radius: 5px !important; }
        .soft-card { background: var(--bg-card); backdrop-filter: blur(10px); border: 1px solid var(--border-main); box-shadow: 0 10px 30px rgba(15, 23, 42, 0.05); }
        .post-media-preview { aspect-ratio: 16/9; object-fit: cover; }
    </style>
</head>
<body class="h-screen overflow-hidden">
    <x-dashboard-header />

    <div class="flex pt-16 h-screen overflow-hidden">
        <x-dashboard-sidebar />

        <main class="flex-1 h-full overflow-y-auto p-4 md:p-8 scrollbar-hide">
            <div class="max-w-7xl mx-auto">
                <div class="mb-8">
                    <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Post Audit</h1>
                    <p class="text-slate-500 mt-2">Review and manage user posts across the platform.</p>
                </div>

                @if(session('success'))
                <div class="mb-6 p-4 bg-emerald-50 border border-emerald-100 text-emerald-700 rounded-custom text-sm font-bold animate-pulse">
                    {{ session('success') }}
                </div>
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($posts as $post)
                    <div class="soft-card rounded-custom overflow-hidden post-card opacity-0">
                        @if($post->media_path)
                            @if($post->media_type === 'video')
                                <video class="post-media-preview w-full bg-black" muted loop onmouseover="this.play()" onmouseout="this.pause()">
                                    <source src="{{ asset('storage/' . $post->media_path) }}" type="video/mp4">
                                </video>
                            @else
                                <img src="{{ asset('storage/' . $post->media_path) }}" class="post-media-preview w-full bg-slate-100" alt="">
                            @endif
                        @else
                            <div class="post-media-preview w-full bg-slate-100 flex items-center justify-center text-slate-400">
                                <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            </div>
                        @endif

                        <div class="p-4">
                            <div class="flex items-center gap-2 mb-3">
                                <img src="{{ $post->user->avatar_path ? asset('storage/' . $post->user->avatar_path) : 'https://api.dicebear.com/7.x/avataaars/svg?seed=' . urlencode($post->user->display_name) }}" class="w-6 h-6 rounded-full" alt="">
                                <span class="text-xs font-bold text-slate-700">{{ $post->user->display_name }}</span>
                                <span class="text-[10px] text-slate-400 ml-auto">{{ $post->created_at->diffForHumans() }}</span>
                            </div>
                            <h3 class="text-sm font-bold text-slate-900 mb-1 truncate">{{ $post->title }}</h3>
                            <p class="text-xs text-slate-500 line-clamp-2 mb-4">{{ $post->description }}</p>
                            
                            <div class="flex items-center justify-between pt-4 border-t border-slate-100">
                                <span class="px-2 py-1 bg-slate-100 text-[10px] font-bold text-slate-500 rounded-full uppercase">{{ $post->visibility }}</span>
                                <div class="flex items-center gap-1">
                                    <a href="{{ route('admin.content.edit', $post) }}" class="p-2 text-blue-600 hover:bg-blue-50 rounded-custom transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    </a>
                                    <form action="{{ route('admin.content.destroy', $post) }}" method="POST" onsubmit="return confirm('Delete this post permanently?')">
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
                    {{ $posts->links() }}
                </div>
            </div>
        </main>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            gsap.to('.post-card', { 
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
