<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Post | Studio</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="{{ asset('app.css') }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --ink-900: #10243a;
            --ink-700: #2e4a66;
            --ink-500: #5f7892;
            --surface-0: #f4f8ff;
            --surface-1: #ffffff;
            --line-soft: #d9e5f3;
            --brand-600: #0f74ff;
            --brand-500: #2e87ff;
            --good-500: #0da271;
            --warn-500: #f59e0b;
        }

        body {
            font-family: 'Sora', sans-serif;
            color: var(--ink-900);
            background:
                radial-gradient(1200px 500px at -10% -10%, rgba(15, 116, 255, 0.16), transparent 70%),
                radial-gradient(700px 400px at 110% 20%, rgba(14, 165, 233, 0.14), transparent 70%),
                linear-gradient(180deg, #f8fbff 0%, #f2f7ff 100%);
        }

        * {
            border-radius: 5px !important;
            box-shadow: none !important;
        }

        *::before,
        *::after {
            box-shadow: none !important;
        }

        #create-bg-canvas {
            position: fixed;
            inset: 0;
            width: 100%;
            height: 100%;
            z-index: 0;
            pointer-events: none;
            opacity: 0.45;
        }

        .theme-dark body {
            background:
                radial-gradient(900px 500px at -10% -10%, rgba(37, 99, 235, 0.18), transparent 70%),
                radial-gradient(700px 420px at 100% 20%, rgba(6, 182, 212, 0.16), transparent 72%),
                #0a1525 !important;
            color: #e8f0ff;
        }

        .glass {
            background: rgba(255, 255, 255, 0.86);
            backdrop-filter: blur(14px);
            border: 1px solid rgba(255, 255, 255, 0.8);
        }

        .theme-dark .glass {
            background: rgba(17, 29, 48, 0.82);
            border-color: rgba(70, 92, 122, 0.35);
        }

        .rounded-custom { border-radius: 10px !important; }

        .pill-tab {
            border: 1px solid var(--line-soft);
            background: rgba(255, 255, 255, 0.8);
            color: var(--ink-700);
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 0.03em;
            padding: 9px 16px;
            border-radius: 999px;
            transition: all 0.2s ease;
        }

        .pill-tab.active {
            color: #ffffff;
            background: linear-gradient(120deg, var(--brand-600), #38bdf8);
            border-color: transparent;
        }

        .composer-grid {
            display: grid;
            grid-template-columns: minmax(0, 1fr);
            gap: 1rem;
        }

        @media (min-width: 1280px) {
            .composer-grid {
                grid-template-columns: minmax(0, 1fr) 380px;
            }
        }

        .editor-panel {
            border: 1px solid var(--line-soft);
            background: var(--surface-1);
            border-radius: 5px;
        }

        .theme-dark .editor-panel {
            background: #111b30;
            border-color: #243752;
        }

        .editor-input,
        .editor-select,
        .editor-textarea {
            width: 100%;
            border: 1px solid #d3deea;
            border-radius: 12px;
            background: #f9fbff;
            color: var(--ink-900);
            outline: none;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }

        .editor-input:focus,
        .editor-select:focus,
        .editor-textarea:focus {
            border-color: #60a5fa;
        }

        .theme-dark .editor-input,
        .theme-dark .editor-select,
        .theme-dark .editor-textarea {
            background: #15243b;
            border-color: #2b4263;
            color: #eaf2ff;
        }

        .editor-textarea {
            min-height: 180px;
            resize: vertical;
            padding: 16px;
            font-size: 15px;
            line-height: 1.7;
        }

        .media-dropzone {
            position: relative;
            overflow: hidden;
            border-radius: 18px;
            border: 2px dashed #bfd3ea;
            min-height: 220px;
            background:
                linear-gradient(145deg, rgba(255, 255, 255, 0.9), rgba(245, 250, 255, 0.95)),
                radial-gradient(200px 120px at 10% 0%, rgba(14, 165, 233, 0.16), transparent 65%);
            transition: border-color 0.2s ease, transform 0.2s ease, box-shadow 0.2s ease;
        }

        .media-dropzone.dragging {
            border-color: var(--brand-600);
            transform: scale(1.01);
        }

        .media-dropzone.has-file {
            border-style: solid;
            border-color: #9bc0ed;
        }

        .theme-dark .media-dropzone {
            background: linear-gradient(155deg, rgba(20, 37, 58, 0.95), rgba(16, 29, 46, 0.95));
            border-color: #3a5578;
        }

        .type-pill {
            border: 1px solid #c4d8ef;
            color: var(--ink-700);
            background: #ffffff;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 800;
            letter-spacing: 0.02em;
            padding: 8px 14px;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .type-pill.active {
            color: white;
            border-color: transparent;
            background: linear-gradient(120deg, #0f74ff, #22d3ee);
        }

        .theme-dark .type-pill {
            background: #182943;
            color: #9fbbdb;
            border-color: #325075;
        }

        .publish-btn {
            border-radius: 5px;
            background: linear-gradient(120deg, #0f74ff, #10b2f4);
            color: white;
            font-weight: 800;
            letter-spacing: 0.02em;
            transition: transform 0.2s ease, box-shadow 0.2s ease, opacity 0.2s ease;
        }

        .publish-btn:hover {
            transform: translateY(-1px);
        }

        .publish-btn:disabled {
            opacity: 0.7;
            cursor: not-allowed;
            transform: none;
        }

        .preview-card {
            border: 1px solid #d2deed;
            border-radius: 5px;
            overflow: hidden;
            background: white;
        }

        .theme-dark .preview-card {
            border-color: #2a4467;
            background: #101d30;
        }

        .kpi {
            border-radius: 5px;
            border: 1px solid #d6e3f2;
            padding: 12px;
            background: #f8fbff;
        }

        .theme-dark .kpi {
            border-color: #2b4363;
            background: #13243a;
        }

        .gallery-card {
            border: 1px solid #d3e1f0;
            border-radius: 5px;
            overflow: hidden;
            background: white;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .gallery-card:hover {
            transform: translateY(-2px);
        }

        .gallery-menu {
            position: absolute;
            top: 36px;
            right: 8px;
            min-width: 150px;
            border: 1px solid #cfdcec;
            background: #ffffff;
            z-index: 15;
        }

        .theme-dark .gallery-menu {
            border-color: #355173;
            background: #0f1c30;
        }

        .gallery-menu.hidden {
            display: none;
        }

        .gallery-menu button {
            display: block;
            width: 100%;
            text-align: left;
            padding: 8px 10px;
            font-size: 12px;
            font-weight: 700;
            color: #27415b;
        }

        .gallery-menu button:hover {
            background: #edf4ff;
        }

        .theme-dark .gallery-menu button {
            color: #c7ddf8;
        }

        .theme-dark .gallery-menu button:hover {
            background: #162844;
        }

        .gallery-menu button.gallery-action-danger {
            color: #dc2626;
        }

        .theme-dark .gallery-menu button.gallery-action-danger {
            color: #f87171;
        }

        .theme-dark .gallery-card {
            background: #101d30;
            border-color: #2d4668;
        }

        .scrollbar-hide::-webkit-scrollbar { display: none; }
        .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
        .text-muted { color: var(--ink-500); }
        .text-main { color: var(--ink-900); }
    </style>
</head>
<body class="min-h-screen overflow-hidden">
    <canvas id="create-bg-canvas" aria-hidden="true"></canvas>
    <x-dashboard-header />
    <x-dashboard-sidebar />

    <div class="pt-16 h-[100dvh] overflow-hidden relative z-10">
        <main class="h-full overflow-y-auto scrollbar-hide">
            <div class="mx-auto max-w-7xl px-4 md:px-8 py-6 md:py-8">
                <section class="glass rounded-[22px] px-5 md:px-7 py-5 md:py-6 mb-6">
                    <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                        <div>
                            <p class="text-[11px] font-bold tracking-[0.24em] uppercase text-blue-600">Creator Studio</p>
                            <h1 class="text-2xl md:text-3xl font-extrabold tracking-tight mt-2 text-main">Build a Post That Feels Premium</h1>
                            <p class="text-sm md:text-[15px] text-muted mt-2 max-w-2xl">Compose, style, and preview your content in one focused workflow. Fast publishing with a cleaner production-style control panel.</p>
                        </div>
                        <div class="flex items-center gap-2">
                            <button onclick="switchTab('create')" id="tab-create" class="pill-tab active">Compose</button>
                            <button onclick="switchTab('gallery')" id="tab-gallery" class="pill-tab">Library</button>
                        </div>
                    </div>
                </section>

                <section id="section-create" class="composer-grid">
                    <div class="space-y-4 md:space-y-5 min-w-0">
                        <article class="editor-panel p-4 md:p-6">
                            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3 mb-5">
                                <div class="flex items-center gap-3 min-w-0">
                                    <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-blue-600 to-cyan-400 flex items-center justify-center text-white font-black text-sm">
                                        {{ strtoupper(substr(auth()->user()->first_name ?? 'U', 0, 1)) }}
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-sm font-extrabold text-main truncate">{{ auth()->user()->display_name ?? 'User' }}</p>
                                        <p class="text-[11px] text-muted">Post creation session is active</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-3">
                                    <label class="text-[11px] font-bold uppercase tracking-[0.18em] text-muted">Visibility</label>
                                    <select id="post-privacy" class="editor-select px-3 py-2 text-xs font-bold">
                                        <option value="public">Public</option>
                                        <option value="private">Private</option>
                                    </select>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mb-3">
                                <div>
                                    <label class="text-[11px] font-bold uppercase tracking-[0.18em] text-muted block mb-2">Title</label>
                                    <input id="post-title" type="text" placeholder="Give this content a clear, strong title" class="editor-input px-3 py-2.5 text-sm font-semibold">
                                </div>
                                <div>
                                    <label class="text-[11px] font-bold uppercase tracking-[0.18em] text-muted block mb-2">Tags</label>
                                    <input id="post-tags" type="text" placeholder="design, workflow, update" class="editor-input px-3 py-2.5 text-sm font-semibold">
                                </div>
                            </div>

                            <div class="mb-2 mt-4 flex items-center justify-between">
                                <label class="text-[11px] font-bold uppercase tracking-[0.18em] text-muted">Story</label>
                                <p id="desc-counter" class="text-[11px] font-semibold text-slate-500">0 chars</p>
                            </div>
                            <textarea id="post-description" class="editor-textarea" placeholder="Write a compelling story for your audience. Keep it clear, useful, and intentional."></textarea>

                            <div id="media-dropzone" class="media-dropzone mt-4 cursor-pointer">
                                <input type="file" id="post-media" class="hidden" accept="image/*,video/*">
                                <div id="dropzone-empty" class="absolute inset-0 px-6 py-6 flex flex-col items-center justify-center text-center text-slate-500">
                                    <svg class="w-11 h-11 mb-3 opacity-60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.587-1.587a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    <p class="font-extrabold text-sm">Drop image/video here</p>
                                    <p class="text-xs mt-1">or click to upload from your device</p>
                                </div>
                                <div id="dropzone-preview" class="hidden absolute inset-0">
                                    <img id="preview-img" class="hidden w-full h-full object-cover" alt="Media preview">
                                    <video id="preview-video" class="hidden w-full h-full object-cover" controls></video>
                                    <button onclick="clearMedia(event)" class="absolute top-3 right-3 bg-black/60 text-white p-2 rounded-full hover:bg-black/75 transition-colors" aria-label="Remove media">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path></svg>
                                    </button>
                                </div>
                            </div>

                            <div class="mt-5">
                                <p class="text-[11px] font-bold uppercase tracking-[0.18em] text-muted mb-2">Format</p>
                                <div class="flex flex-wrap gap-2">
                                    <button type="button" onclick="setPostType('post')" class="type-pill active" data-type="post">Post</button>
                                    <button type="button" onclick="setPostType('reel')" class="type-pill" data-type="reel">Reel</button>
                                    <button type="button" onclick="setPostType('live')" class="type-pill" data-type="live">Live</button>
                                </div>
                            </div>

                            <div class="mt-6 flex gap-3">
                                <button id="btn-publish" onclick="handlePublish()" class="publish-btn flex-1 py-3.5 text-sm flex items-center justify-center gap-2">
                                    <span>Publish to Feed</span>
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" d="M5 12h14m-7-7l7 7-7 7"></path></svg>
                                </button>
                            </div>
                        </article>
                    </div>

                    <aside class="space-y-4">
                        <article class="preview-card">
                            <div class="px-4 py-3 border-b border-slate-100 dark:border-slate-700/60 flex items-center justify-between">
                                <p class="text-[11px] font-extrabold uppercase tracking-[0.18em] text-slate-500">Live Preview</p>
                                <span id="preview-type-badge" class="px-2 py-1 rounded-full text-[10px] font-black uppercase bg-blue-50 text-blue-600">Post</span>
                            </div>
                            <div class="p-4 flex items-start gap-3">
                                <div class="w-9 h-9 rounded-lg bg-gradient-to-br from-blue-600 to-cyan-400 text-white text-xs font-black flex items-center justify-center">
                                    {{ strtoupper(substr(auth()->user()->first_name ?? 'U', 0, 1)) }}
                                </div>
                                <div class="min-w-0">
                                    <p id="preview-author" class="text-sm font-extrabold truncate text-main">{{ auth()->user()->display_name ?? 'User' }}</p>
                                    <p class="text-[11px] text-slate-500">Just now • <span id="preview-privacy-label">Public</span></p>
                                </div>
                            </div>
                            <div class="px-4 pb-3">
                                <p id="preview-text" class="text-sm text-slate-600 leading-6 italic">Start typing to see preview...</p>
                                <div id="preview-tag-list" class="flex flex-wrap gap-1.5 mt-2"></div>
                            </div>
                            <div id="preview-media-box" class="mx-4 mb-4 rounded-xl overflow-hidden h-44 bg-slate-100 dark:bg-slate-800 flex items-center justify-center relative">
                                <img id="preview-media-img" class="hidden w-full h-full object-cover" alt="Live preview media">
                                <div id="preview-media-placeholder" class="text-center text-slate-400">
                                    <svg class="w-8 h-8 mx-auto mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.587-1.587a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    <p class="text-[11px] font-bold">Media area</p>
                                </div>
                            </div>
                        </article>

                        <article class="editor-panel p-4">
                            <h3 class="text-sm font-extrabold text-main">Quality Checklist</h3>
                            <div class="grid grid-cols-1 gap-2 mt-3">
                                <div class="kpi">
                                    <p class="text-[11px] uppercase font-extrabold tracking-[0.16em] text-slate-500">Title Strength</p>
                                    <p id="kpi-title" class="text-sm font-bold mt-1 text-slate-700">Add a clear title</p>
                                </div>
                                <div class="kpi">
                                    <p class="text-[11px] uppercase font-extrabold tracking-[0.16em] text-slate-500">Body Readiness</p>
                                    <p id="kpi-description" class="text-sm font-bold mt-1 text-slate-700">Start writing your post</p>
                                </div>
                                <div class="kpi">
                                    <p class="text-[11px] uppercase font-extrabold tracking-[0.16em] text-slate-500">Media Status</p>
                                    <p id="kpi-media" class="text-sm font-bold mt-1 text-slate-700">No media selected</p>
                                </div>
                            </div>
                        </article>
                    </aside>
                </section>

                <section id="section-gallery" class="hidden">
                    <div class="editor-panel p-4 md:p-5">
                        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3 mb-4">
                            <div>
                                <h3 class="text-lg font-extrabold text-main">Your Published Library</h3>
                                <p class="text-sm text-muted">Recent content snapshots from your feed.</p>
                            </div>
                            <button onclick="loadGallery()" class="pill-tab">Refresh</button>
                        </div>
                        <div id="gallery-grid" class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-3 md:gap-4">
                            <div class="col-span-full py-16 text-center text-slate-500 font-bold">Fetching your creative history...</div>
                        </div>
                    </div>
                </section>
            </div>
        </main>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/animejs/3.2.1/anime.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>
    <script>
        const CSRF_TOKEN = '{{ csrf_token() }}';
        let currentPostType = 'post';
        let historyItems = [];

        const userLabel = '{{ auth()->user()->display_name ?? "User" }}';

        const descInput = document.getElementById('post-description');
        const titleInput = document.getElementById('post-title');
        const tagsInput = document.getElementById('post-tags');
        const privacySelect = document.getElementById('post-privacy');
        const mediaInput = document.getElementById('post-media');
        const dropzone = document.getElementById('media-dropzone');

        const previewText = document.getElementById('preview-text');
        const previewTypeBadge = document.getElementById('preview-type-badge');
        const previewPrivacyLabel = document.getElementById('preview-privacy-label');
        const previewTags = document.getElementById('preview-tag-list');
        const previewMediaImg = document.getElementById('preview-media-img');
        const previewMediaPlaceholder = document.getElementById('preview-media-placeholder');

        const counter = document.getElementById('desc-counter');
        const kpiTitle = document.getElementById('kpi-title');
        const kpiDescription = document.getElementById('kpi-description');
        const kpiMedia = document.getElementById('kpi-media');

        function statusClass(text, good = false) {
            return `<span class="${good ? 'text-emerald-600' : 'text-amber-600'}">${text}</span>`;
        }

        function updatePreviewText() {
            const val = (descInput.value || '').trim();
            previewText.textContent = val || 'Start typing to see preview...';
            previewText.classList.toggle('italic', !val);
            counter.textContent = `${val.length} chars`;

            if (!val.length) {
                kpiDescription.innerHTML = statusClass('Start writing your post');
            } else if (val.length < 60) {
                kpiDescription.innerHTML = statusClass('Too short, add detail');
            } else {
                kpiDescription.innerHTML = '<span class="text-emerald-600">Looks good for publish</span>';
            }
        }

        function updatePreviewTitle() {
            const val = (titleInput.value || '').trim();
            document.getElementById('preview-author').textContent = val || userLabel;

            if (!val.length) {
                kpiTitle.innerHTML = statusClass('Add a clear title');
            } else if (val.length < 8) {
                kpiTitle.innerHTML = statusClass('Title is weak, expand it');
            } else {
                kpiTitle.innerHTML = '<span class="text-emerald-600">Strong title</span>';
            }
        }

        function updatePreviewTags() {
            const tags = (tagsInput.value || '')
                .split(',')
                .map((t) => t.trim())
                .filter((t) => t.length);

            previewTags.innerHTML = tags
                .slice(0, 8)
                .map((tag) => `<span class="px-2 py-1 rounded-full bg-blue-50 text-[10px] font-bold text-blue-600">#${tag}</span>`)
                .join('');
        }

        function updatePrivacy() {
            previewPrivacyLabel.textContent = privacySelect.value === 'public' ? 'Public' : 'Private';
        }

        function applyMediaPreview(file, result) {
            const isVideo = file.type.startsWith('video/');
            const pImg = document.getElementById('preview-img');
            const pVid = document.getElementById('preview-video');

            document.getElementById('dropzone-empty').classList.add('hidden');
            document.getElementById('dropzone-preview').classList.remove('hidden');
            dropzone.classList.add('has-file');

            if (isVideo) {
                pImg.classList.add('hidden');
                pVid.classList.remove('hidden');
                pVid.src = result;

                previewMediaImg.classList.add('hidden');
                previewMediaPlaceholder.classList.remove('hidden');
                previewMediaPlaceholder.innerHTML = '<svg class="w-8 h-8 mx-auto mb-1 text-blue-500" fill="currentColor" viewBox="0 0 20 20"><path d="M6.3 2.841A1.5 1.5 0 004 4.11V15.89a1.5 1.5 0 002.3 1.269l9.344-5.89a1.5 1.5 0 000-2.538L6.3 2.84z"></path></svg><p class="text-[11px] font-bold text-blue-500">Video selected</p>';
                kpiMedia.innerHTML = '<span class="text-emerald-600">Video attached</span>';
            } else {
                pVid.classList.add('hidden');
                pImg.classList.remove('hidden');
                pImg.src = result;

                previewMediaImg.classList.remove('hidden');
                previewMediaImg.src = result;
                previewMediaPlaceholder.classList.add('hidden');
                kpiMedia.innerHTML = '<span class="text-emerald-600">Image attached</span>';
            }
        }

        function clearMedia(event) {
            if (event && typeof event.stopPropagation === 'function') event.stopPropagation();

            mediaInput.value = '';
            document.getElementById('dropzone-empty').classList.remove('hidden');
            document.getElementById('dropzone-preview').classList.add('hidden');
            dropzone.classList.remove('has-file', 'dragging');

            const pImg = document.getElementById('preview-img');
            const pVid = document.getElementById('preview-video');
            pImg.src = '';
            pVid.src = '';
            pImg.classList.add('hidden');
            pVid.classList.add('hidden');

            previewMediaImg.src = '';
            previewMediaImg.classList.add('hidden');
            previewMediaPlaceholder.classList.remove('hidden');
            previewMediaPlaceholder.innerHTML = '<svg class="w-8 h-8 mx-auto mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.587-1.587a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg><p class="text-[11px] font-bold">Media area</p>';
            kpiMedia.innerHTML = statusClass('No media selected');
        }

        function setPostType(type) {
            currentPostType = type;
            document.querySelectorAll('.type-pill').forEach((pill) => {
                pill.classList.toggle('active', pill.dataset.type === type);
            });
            previewTypeBadge.textContent = type.charAt(0).toUpperCase() + type.slice(1);

            if (window.anime) {
                anime({
                    targets: '#preview-type-badge',
                    scale: [1, 1.08, 1],
                    duration: 320,
                    easing: 'easeOutQuad'
                });
            }
        }

        function switchTab(tab) {
            const create = document.getElementById('section-create');
            const gallery = document.getElementById('section-gallery');
            const btnCreate = document.getElementById('tab-create');
            const btnGallery = document.getElementById('tab-gallery');

            if (tab === 'create') {
                create.classList.remove('hidden');
                gallery.classList.add('hidden');
                btnCreate.classList.add('active');
                btnGallery.classList.remove('active');
            } else {
                create.classList.add('hidden');
                gallery.classList.remove('hidden');
                btnCreate.classList.remove('active');
                btnGallery.classList.add('active');
                loadGallery();
            }
        }

        async function handlePublish() {
            const btn = document.getElementById('btn-publish');
            const title = titleInput.value.trim();
            const description = descInput.value.trim();

            if (!title) {
                alert('Please add a title for your content.');
                return;
            }

            const formData = new FormData();
            formData.append('content_type', currentPostType);
            formData.append('title', title);
            formData.append('description', description);
            formData.append('tags', tagsInput.value.trim());
            formData.append('visibility', privacySelect.value);

            if (mediaInput.files[0]) {
                formData.append('media', mediaInput.files[0]);
            }

            btn.disabled = true;
            btn.innerHTML = '<svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg><span>Publishing...</span>';

            try {
                const response = await fetch('/api/content-items', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': CSRF_TOKEN,
                        Accept: 'application/json'
                    },
                    body: formData
                });

                if (response.ok) {
                    titleInput.value = '';
                    descInput.value = '';
                    tagsInput.value = '';
                    privacySelect.value = 'public';
                    clearMedia({ stopPropagation: () => {} });
                    setPostType('post');
                    updatePreviewTitle();
                    updatePreviewText();
                    updatePreviewTags();
                    updatePrivacy();
                    switchTab('gallery');
                } else {
                    const data = await response.json();
                    alert(data.message || 'Something went wrong while publishing.');
                }
            } catch (error) {
                alert('Network error. Please try again.');
            } finally {
                btn.disabled = false;
                btn.innerHTML = '<span>Publish to Feed</span><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" d="M5 12h14m-7-7l7 7-7 7"></path></svg>';
            }
        }

        function closeGalleryMenus() {
            document.querySelectorAll('.gallery-menu').forEach((menu) => menu.classList.add('hidden'));
        }

        function toggleGalleryMenu(itemId) {
            const menu = document.getElementById(`gallery-menu-${itemId}`);
            if (!menu) return;

            const isHidden = menu.classList.contains('hidden');
            closeGalleryMenus();
            if (isHidden) menu.classList.remove('hidden');
        }

        async function updateGalleryVisibility(itemId, nextVisibility) {
            try {
                const response = await fetch(`/api/content-items/${itemId}/visibility`, {
                    method: 'PATCH',
                    headers: {
                        'X-CSRF-TOKEN': CSRF_TOKEN,
                        'Content-Type': 'application/json',
                        Accept: 'application/json'
                    },
                    body: JSON.stringify({ visibility: nextVisibility })
                });

                if (!response.ok) {
                    const payload = await response.json().catch(() => ({}));
                    alert(payload.message || 'Failed to update visibility.');
                    return;
                }

                await loadGallery();
            } catch (error) {
                alert('Network error while updating visibility.');
            }
        }

        async function deleteGalleryItem(itemId) {
            const ok = window.confirm('Delete this content item? This action cannot be undone.');
            if (!ok) return;

            try {
                const response = await fetch(`/api/content-items/${itemId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': CSRF_TOKEN,
                        Accept: 'application/json'
                    }
                });

                if (!response.ok) {
                    const payload = await response.json().catch(() => ({}));
                    alert(payload.message || 'Failed to delete content.');
                    return;
                }

                historyItems = historyItems.filter((item) => Number(item.id) !== Number(itemId));
                await loadGallery();
            } catch (error) {
                alert('Network error while deleting content.');
            }
        }

        async function loadGallery() {
            const grid = document.getElementById('gallery-grid');
            try {
                const response = await fetch('/api/content-items', {
                    headers: { Accept: 'application/json' }
                });
                const data = await response.json();
                historyItems = data.items || [];

                if (!historyItems.length) {
                    grid.innerHTML = '<div class="col-span-full py-16 text-center text-slate-500 font-bold">You have not shared anything yet.</div>';
                    return;
                }

                grid.innerHTML = historyItems.map((item) => `
                    <article class="gallery-card group">
                        <div class="aspect-square bg-slate-100 dark:bg-slate-900 relative">
                            <button type="button" class="absolute top-2 right-2 w-7 h-7 border border-white/50 bg-white/90 text-slate-700 dark:bg-slate-800/95 dark:border-slate-600 dark:text-slate-100 flex items-center justify-center" data-gallery-menu-toggle="${item.id}" aria-label="Open card menu">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M12 5h.01M12 12h.01M12 19h.01"></path></svg>
                            </button>
                            <div id="gallery-menu-${item.id}" class="gallery-menu hidden">
                                <button type="button" data-gallery-action="visibility" data-item-id="${item.id}" data-current-visibility="${item.visibility || 'public'}">${(item.visibility || 'public') === 'public' ? 'Make Private' : 'Make Public'}</button>
                                <button type="button" data-gallery-action="delete" data-item-id="${item.id}" class="gallery-action-danger">Delete</button>
                            </div>
                            ${item.mediaUrl ? (
                                item.mediaUrl.match(/\.(mp4|webm|mov)$/i)
                                    ? `<video class="w-full h-full object-cover"><source src="${item.mediaUrl}"></video><div class="absolute inset-0 flex items-center justify-center bg-black/25"><svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 20 20"><path d="M6.3 2.841A1.5 1.5 0 004 4.11V15.89a1.5 1.5 0 002.3 1.269l9.344-5.89a1.5 1.5 0 000-2.538L6.3 2.84z"></path></svg></div>`
                                    : `<img src="${item.mediaUrl}" class="w-full h-full object-cover" alt="Gallery media">`
                            ) : `<div class="w-full h-full flex items-center justify-center text-slate-300"><svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.587-1.587a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg></div>`}
                            <span class="absolute top-2 left-2 px-2 py-1 rounded-full bg-black/55 text-white text-[9px] font-black uppercase">${item.type || 'post'}</span>
                            <span class="absolute bottom-2 left-2 px-2 py-1 bg-white/90 dark:bg-slate-800/95 border border-white/60 dark:border-slate-600 text-[9px] font-black uppercase text-slate-700 dark:text-slate-100">${item.visibility || 'public'}</span>
                        </div>
                        <div class="p-3">
                            <h4 class="text-xs font-bold truncate text-main">${item.title || 'Untitled'}</h4>
                            <p class="text-[10px] text-slate-500 mt-1 uppercase font-black tracking-[0.14em]">${item.publishedAt || 'Published'}</p>
                        </div>
                    </article>
                `).join('');

                gsap.from('#gallery-grid > article', {
                    opacity: 0,
                    y: 12,
                    stagger: 0.04,
                    duration: 0.3,
                    ease: 'power2.out'
                });
            } catch (error) {
                grid.innerHTML = '<div class="col-span-full py-16 text-center text-red-500 font-bold">Failed to load gallery.</div>';
            }
        }

        dropzone.addEventListener('click', () => mediaInput.click());

        dropzone.addEventListener('dragover', (event) => {
            event.preventDefault();
            dropzone.classList.add('dragging');
        });

        dropzone.addEventListener('dragleave', () => {
            dropzone.classList.remove('dragging');
        });

        dropzone.addEventListener('drop', (event) => {
            event.preventDefault();
            dropzone.classList.remove('dragging');
            const file = event.dataTransfer?.files?.[0];
            if (!file) return;

            mediaInput.files = event.dataTransfer.files;
            const reader = new FileReader();
            reader.onload = (e) => applyMediaPreview(file, e.target.result);
            reader.readAsDataURL(file);
        });

        mediaInput.addEventListener('change', (event) => {
            const file = event.target.files[0];
            if (!file) return;

            const reader = new FileReader();
            reader.onload = (e) => applyMediaPreview(file, e.target.result);
            reader.readAsDataURL(file);
        });

        document.addEventListener('click', async (event) => {
            const toggle = event.target.closest('[data-gallery-menu-toggle]');
            if (toggle) {
                event.stopPropagation();
                toggleGalleryMenu(toggle.dataset.galleryMenuToggle);
                return;
            }

            const actionBtn = event.target.closest('[data-gallery-action]');
            if (actionBtn) {
                event.stopPropagation();
                const itemId = actionBtn.dataset.itemId;
                const action = actionBtn.dataset.galleryAction;

                if (action === 'delete') {
                    await deleteGalleryItem(itemId);
                    closeGalleryMenus();
                    return;
                }

                if (action === 'visibility') {
                    const current = actionBtn.dataset.currentVisibility === 'private' ? 'private' : 'public';
                    const next = current === 'public' ? 'private' : 'public';
                    await updateGalleryVisibility(itemId, next);
                    closeGalleryMenus();
                    return;
                }
            }

            if (!event.target.closest('.gallery-menu')) {
                closeGalleryMenus();
            }
        });

        descInput.addEventListener('input', updatePreviewText);
        titleInput.addEventListener('input', updatePreviewTitle);
        tagsInput.addEventListener('input', updatePreviewTags);
        privacySelect.addEventListener('change', updatePrivacy);

        updatePreviewTitle();
        updatePreviewText();
        updatePreviewTags();
        updatePrivacy();
        setPostType('post');

        if (window.gsap) {
            gsap.from('section.glass', { opacity: 0, y: 16, duration: 0.45, ease: 'power2.out' });
            gsap.from('#section-create .editor-panel, #section-create .preview-card', {
                opacity: 0,
                y: 18,
                duration: 0.45,
                stagger: 0.08,
                ease: 'power2.out',
                delay: 0.1
            });
        }

        if (window.anime) {
            anime({
                targets: '.type-pill',
                translateY: [8, 0],
                opacity: [0, 1],
                delay: anime.stagger(60),
                duration: 400,
                easing: 'easeOutSine'
            });
        }

        function initThreeBackground() {
            if (!window.THREE) return;

            const canvas = document.getElementById('create-bg-canvas');
            if (!canvas) return;

            const renderer = new THREE.WebGLRenderer({ canvas, alpha: true, antialias: true });
            renderer.setPixelRatio(Math.min(window.devicePixelRatio || 1, 2));

            const scene = new THREE.Scene();
            const camera = new THREE.PerspectiveCamera(55, window.innerWidth / window.innerHeight, 0.1, 1000);
            camera.position.z = 120;

            const count = 320;
            const positions = new Float32Array(count * 3);
            for (let i = 0; i < count; i += 1) {
                const idx = i * 3;
                positions[idx] = (Math.random() - 0.5) * 240;
                positions[idx + 1] = (Math.random() - 0.5) * 160;
                positions[idx + 2] = (Math.random() - 0.5) * 140;
            }

            const geometry = new THREE.BufferGeometry();
            geometry.setAttribute('position', new THREE.BufferAttribute(positions, 3));

            const material = new THREE.PointsMaterial({
                color: 0x2e87ff,
                size: 1.35,
                transparent: true,
                opacity: 0.5
            });

            const points = new THREE.Points(geometry, material);
            scene.add(points);

            function resize() {
                const width = window.innerWidth;
                const height = window.innerHeight;
                renderer.setSize(width, height, false);
                camera.aspect = width / height;
                camera.updateProjectionMatrix();
            }

            function animate() {
                points.rotation.y += 0.00075;
                points.rotation.x += 0.0003;
                renderer.render(scene, camera);
                window.requestAnimationFrame(animate);
            }

            window.addEventListener('resize', resize);
            resize();
            animate();
        }

        initThreeBackground();
    </script>
    <script src="{{ asset('app.js') }}"></script>
</body>
</html>
