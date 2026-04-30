<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connect | Edit Content</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="{{ asset('app.css') }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background: var(--bg-main); color: var(--text-main); }
        .rounded-custom { border-radius: 5px !important; }
        .soft-card { background: var(--bg-card); backdrop-filter: blur(10px); border: 1px solid var(--border-main); box-shadow: 0 10px 30px rgba(15, 23, 42, 0.05); }
        .preview-media { max-height: 300px; width: 100%; object-fit: contain; background: #000; border-radius: 5px; }
    </style>
</head>
<body class="h-screen overflow-hidden">
    <x-dashboard-header />

    <div class="flex pt-16 h-screen overflow-hidden">
        <x-dashboard-sidebar />

        <main class="flex-1 h-full overflow-y-auto p-4 md:p-8 scrollbar-hide">
            <div class="max-w-2xl mx-auto">
                <div class="mb-8 flex items-center gap-4">
                    <a href="{{ url()->previous() }}" class="p-2 hover:bg-slate-100 rounded-custom transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    </a>
                    <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Edit {{ ucfirst($contentItem->content_type) }}</h1>
                </div>

                <div class="soft-card p-8 rounded-custom opacity-0" id="edit-form-card">
                    <div class="mb-8">
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-3">Media Preview</p>
                        @if($contentItem->media_path)
                            @if($contentItem->media_type === 'video')
                                <video class="preview-media" controls>
                                    <source src="{{ asset('storage/' . $contentItem->media_path) }}" type="video/mp4">
                                </video>
                            @else
                                <img src="{{ asset('storage/' . $contentItem->media_path) }}" class="preview-media" alt="">
                            @endif
                        @else
                            <div class="preview-media flex items-center justify-center bg-slate-100 text-slate-400">No media attached</div>
                        @endif
                    </div>

                    <form action="{{ route('admin.content.update', $contentItem) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-6">
                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Title</label>
                            <input type="text" name="title" value="{{ old('title', $contentItem->title) }}" class="w-full bg-slate-50 border border-slate-100 p-3 rounded-custom outline-none focus:border-blue-400 text-sm font-semibold">
                            @error('title') <p class="text-red-500 text-[10px] mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="mb-6">
                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Description / Body</label>
                            <textarea name="description" rows="5" class="w-full bg-slate-50 border border-slate-100 p-3 rounded-custom outline-none focus:border-blue-400 text-sm font-semibold">{{ old('description', $contentItem->description) }}</textarea>
                            @error('description') <p class="text-red-500 text-[10px] mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="mb-10">
                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Visibility</label>
                            <select name="visibility" class="w-full bg-slate-50 border border-slate-100 p-3 rounded-custom outline-none focus:border-blue-400 text-sm font-semibold">
                                <option value="public" {{ old('visibility', $contentItem->visibility) === 'public' ? 'selected' : '' }}>Public</option>
                                <option value="private" {{ old('visibility', $contentItem->visibility) === 'private' ? 'selected' : '' }}>Private</option>
                            </select>
                            @error('visibility') <p class="text-red-500 text-[10px] mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="flex items-center gap-4">
                            <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-custom transition-colors shadow-lg shadow-blue-200">Update Content</button>
                            <a href="{{ url()->previous() }}" class="px-6 py-3 border border-slate-200 text-slate-500 font-bold rounded-custom hover:bg-slate-50 transition-colors">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            gsap.to('#edit-form-card', { opacity: 1, y: 0, duration: 0.8, ease: "power3.out" });
        });
    </script>
    <script src="{{ asset('app.js') }}"></script>
</body>
</html>
