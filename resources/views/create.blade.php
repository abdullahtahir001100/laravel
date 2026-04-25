<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Studio | Create Content</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <link href="{{ asset('app.css') }}" rel='stylesheet'>
    <style>
        body { background-color: #f0f2f5; color: #0f172a; font-family: 'Inter', sans-serif; }
        .rounded-custom { border-radius: 5px !important; }
        .hide-scroll::-webkit-scrollbar { display: none; }
        
        .main-content { padding-top: 4rem; }
        
        .input-field {
            border: 1px solid #e2e8f0;
            border-radius: 5px;
            padding: 12px;
            width: 100%;
            font-weight: 500;
            font-size: 13px;
            outline: none;
            background: #ffffff;
            color: #0f172a;
        }
        .input-field:focus {
            background-color: #ffffff;
            border-color: #93c5fd;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.12);
        }

        .type-btn {
            border: 1px solid #e2e8f0;
            border-radius: 5px;
            background: #ffffff;
            padding: 15px;
            flex: 1;
            text-align: center;
            font-weight: 700;
            font-size: 11px;
            transition: all 0.3s;
        }
        .type-btn.active {
            background: #eff6ff;
            color: #1d4ed8;
            border-color: #bfdbfe;
        }

        .create-toggle-btn {
            border: 1px solid #cbd5e1;
            background: #ffffff;
            color: #334155;
            border-radius: 5px;
            padding: 12px 20px;
            font-size: 11px;
            font-weight: 800;
            text-transform: uppercase;
            transition: all 0.2s ease;
        }

        .create-toggle-btn.active-view {
            background: #1d4ed8;
            color: #ffffff;
            border-color: #1d4ed8;
        }

        .preview-box {
            border: 2px dashed #e2e8f0;
            border-radius: 5px;
            aspect-ratio: 16/9;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f8fafc;
        }

        .gallery-menu {
            position: absolute;
            right: 8px;
            top: 0px;
            width: 170px;
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 5px;
            padding: 8px;
            z-index: 20;
        }

        .fullscreen-modal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.95);
            z-index: 1000;
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.3s, visibility 0.3s;
        }

        .fullscreen-modal.active {
            opacity: 1;
            visibility: visible;
        }

        .fullscreen-modal-content {
            position: relative;
            width: 90%;
            height: 90%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .fullscreen-modal-media {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
        }

        .fullscreen-close-btn {
            position: absolute;
            top: 20px;
            right: 30px;
            color: #ffffff;
            font-size: 40px;
            font-weight: bold;
            cursor: pointer;
            z-index: 1001;
            background: rgba(0, 0, 0, 0.5);
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background 0.2s;
        }

        .fullscreen-close-btn:hover {
            background: rgba(0, 0, 0, 0.8);
        }

        .fullscreen-title {
            position: absolute;
            bottom: 20px;
            left: 30px;
            color: #ffffff;
            font-size: 14px;
            font-weight: 600;
            z-index: 1001;
            max-width: 70%;
            text-overflow: ellipsis;
            overflow: hidden;
            white-space: nowrap;
        }
    </style>
</head>
<body class="flex min-h-screen bg-[#f0f2f5]">

    <!-- Full-screen Media Viewer Modal -->
    <div id="fullscreenModal" class="fullscreen-modal">
        <div class="fullscreen-modal-content">
            <div id="fullscreenMediaContainer"></div>
            <div class="fullscreen-close-btn" onclick="closeFullscreenViewer()">×</div>
            <div class="fullscreen-title" id="fullscreenTitle"></div>
        </div>
    </div>

    <x-dashboard-sidebar />
        <x-dashboard-header />

    <div class="flex-1 flex flex-col h-screen overflow-hidden">

        <div class="main-content flex flex-col h-full overflow-hidden">
            <div class=" bg-white px-6 py-6">
                <div class="flex justify-end">
                    <div class="flex gap-2 w-full md:w-auto">
                        <button onclick="switchView('create', this)" class="create-toggle-btn">Create New</button>
                        <button onclick="switchView('history', this)" class="create-toggle-btn active-view">Your Gallery</button>
                    </div>
                </div>
            </div>

            <main class="flex-1 overflow-y-auto p-6 md:p-10 bg-[#f8fafc]">
                
                <div id="createSection" class="hidden max-w-6xl mx-auto grid grid-cols-1 lg:grid-cols-2 gap-10">
                    
                    <div class="space-y-6 bg-white border border-slate-200 rounded-custom p-5 md:p-6">
                        <div>
                            <label class="text-xs font-bold mb-3 block text-blue-600">Step 1: Select Type</label>
                            <div class="grid grid-cols-3 gap-2">
                                    <button type="button" onclick="setContentType('post', this)" class="type-btn active">Post</button>
                                    <button type="button" onclick="setContentType('reel', this)" class="type-btn">Reel</button>
                                    <button type="button" onclick="setContentType('live', this)" class="type-btn">Live</button>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="text-xs font-bold mb-2 block text-slate-600">Title</label>
                                    <input id="contentTitle" type="text" placeholder="Enter title..." class="input-field">
                            </div>
                            <div>
                                <label class="text-xs font-bold mb-2 block text-slate-600">Subtitle</label>
                                    <input id="contentSubtitle" type="text" placeholder="Enter subtitle..." class="input-field">
                            </div>
                        </div>

                        <div>
                            <label class="text-xs font-bold mb-2 block text-slate-600">Description</label>
                                <textarea id="contentDescription" rows="4" placeholder="Write something..." class="input-field resize-none"></textarea>
                        </div>

                        <div>
                            <label class="text-xs font-bold mb-2 block text-slate-600">Tags (Comma separated)</label>
                                <input id="contentTags" type="text" placeholder="web, design, connect..." class="input-field">
                        </div>

                        <div>
                            <label class="text-xs font-bold mb-3 block text-slate-600">Visibility</label>
                            <div class="flex gap-6">
                                <label class="flex items-center gap-2 cursor-pointer font-semibold text-sm text-slate-700">
                                        <input type="radio" name="privacy" value="public" checked class="w-4 h-4 accent-blue-600"> Public
                                </label>
                                <label class="flex items-center gap-2 cursor-pointer font-semibold text-sm text-slate-700">
                                    <input type="radio" name="privacy" value="private" class="w-4 h-4 accent-blue-600"> Private
                                </label>
                            </div>
                        </div>

                            <button id="publishContentBtn" type="button" class="w-full bg-blue-600 text-white py-3.5 rounded-custom font-semibold text-sm hover:bg-blue-700 transition-all">Publish Content</button>
                            <p id="createStatus" class="text-xs font-semibold text-slate-500"></p>
                    </div>

                    <div class="space-y-6 bg-white border border-slate-200 rounded-custom p-5 md:p-6">
                        <label class="text-xs font-bold block text-blue-600">Step 2: Upload Media</label>
                        <input id="mediaInput" type="file" class="hidden" accept="image/*,video/*">
                        <div id="uploadZone" class="preview-box group cursor-pointer hover:border-blue-400 transition-all">
                            <div class="text-center">
                                <p class="text-sm font-bold text-slate-700">Click to Upload <span id="mediaTypeLabel">Image/Video</span></p>
                                <p class="text-xs text-slate-400 mt-2 font-semibold">Max Size: 1GB</p>
                            </div>
                        </div>
                        
                        <div id="uploadProgressContainer" class="hidden space-y-2">
                            <div class="flex items-center justify-between">
                                <p class="text-xs font-semibold text-slate-600">Uploading...</p>
                                <span id="uploadPercentage" class="text-xs font-bold text-blue-600">0%</span>
                            </div>
                            <div class="w-full bg-slate-200 rounded-full h-2 overflow-hidden">
                                <div id="uploadProgressBar" class="bg-blue-600 h-full w-0 transition-all duration-300 ease-out" style="width: 0%"></div>
                            </div>
                            <p id="uploadFileSize" class="text-xs text-slate-500 font-semibold"></p>
                        </div>
                        
                        <div id="mediaRequirement" class="p-4 bg-amber-50 border border-amber-200 rounded-custom">
                            <p class="text-xs font-semibold text-amber-700">Requirement: Post supports Image and Video. Reels are Video only. Videos up to 1 hour supported.</p>
                        </div>
                    </div>
                </div>

                <div id="historySection" class="max-w-7xl mx-auto">
                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4" id="galleryGrid">
                        </div>
                </div>

            </main>
        </div>
    </div>

    <script>
        const CSRF_TOKEN = '{{ csrf_token() }}';
        let currentType = 'post';
        let historyData = [];

        const uploadZone = document.getElementById('uploadZone');
        const mediaInput = document.getElementById('mediaInput');
        const contentTitle = document.getElementById('contentTitle');
        const contentSubtitle = document.getElementById('contentSubtitle');
        const contentDescription = document.getElementById('contentDescription');
        const contentTags = document.getElementById('contentTags');
        const publishContentBtn = document.getElementById('publishContentBtn');
        const createStatus = document.getElementById('createStatus');

        function openFullscreenViewer(mediaUrl, mediaType, title) {
            const modal = document.getElementById('fullscreenModal');
            const container = document.getElementById('fullscreenMediaContainer');
            const titleEl = document.getElementById('fullscreenTitle');
            
            container.innerHTML = '';
            
            if (mediaType === 'video') {
                const video = document.createElement('video');
                video.src = mediaUrl;
                video.controls = true;
                video.autoplay = true;
                video.classList.add('fullscreen-modal-media');
                container.appendChild(video);
            } else {
                const img = document.createElement('img');
                img.src = mediaUrl;
                img.alt = title;
                img.classList.add('fullscreen-modal-media');
                container.appendChild(img);
            }
            
            titleEl.textContent = title;
            modal.classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        function closeFullscreenViewer() {
            const modal = document.getElementById('fullscreenModal');
            const container = document.getElementById('fullscreenMediaContainer');
            
            // Stop video if playing
            const video = container.querySelector('video');
            if (video) {
                video.pause();
                video.src = '';
            }
            
            modal.classList.remove('active');
            document.body.style.overflow = 'auto';
        }

        function setStatus(message, tone = 'muted') {
            if (!createStatus) return;
            const colors = {
                muted: 'text-slate-500',
                ok: 'text-emerald-600',
                warn: 'text-amber-600',
                danger: 'text-red-600'
            };

            createStatus.textContent = message;
            createStatus.className = 'text-xs font-semibold ' + (colors[tone] || colors.muted);
        }

        function openMediaPicker() {
            if (!mediaInput) return;
            mediaInput.value = '';
            mediaInput.click();
        }

        function renderMediaPreview(file) {
            if (!uploadZone || !file) return;

            const isVideo = file.type.startsWith('video/');
            const isAllowed = currentType === 'reel' || currentType === 'live' ? isVideo : true;

            if (!isAllowed) {
                setStatus('This content type only accepts video media.', 'warn');
                mediaInput.value = '';
                return;
            }

            const objectUrl = URL.createObjectURL(file);
            uploadZone.dataset.previewUrl = objectUrl;
            uploadZone.dataset.previewType = isVideo ? 'video' : 'image';

            uploadZone.innerHTML = isVideo
                ? `<video controls class="w-full h-full object-cover rounded-custom bg-black"><source src="${objectUrl}" type="${file.type}"></video>`
                : `<img src="${objectUrl}" alt="Upload preview" class="w-full h-full object-cover rounded-custom">`;

            const mediaEl = uploadZone.querySelector(isVideo ? 'video' : 'img');
            mediaEl?.addEventListener('load', () => URL.revokeObjectURL(objectUrl), { once: true });
            mediaEl?.addEventListener('loadeddata', () => URL.revokeObjectURL(objectUrl), { once: true });
            setStatus('Media preview ready.', 'ok');
        }

        function getVisibility() {
            return document.querySelector('input[name="privacy"]:checked')?.value || 'public';
        }

        function getPlaceholderMedia(type) {
            if (type === 'reel') return 'https://picsum.photos/300/500?random=' + Date.now();
            if (type === 'live') return 'https://picsum.photos/400/400?random=' + Date.now();
            return 'https://picsum.photos/400/300?random=' + Date.now();
        }

        function normalizeItem(item) {
            return {
                id: item.id,
                type: item.type,
                title: item.title,
                img: item.mediaUrl || getPlaceholderMedia(item.type),
                mediaType: item.mediaType || (item.mediaUrl && item.mediaUrl.match(/\.(mp4|webm|mov)$/i) ? 'video' : 'image'),
                visibility: item.visibility,
                publishedAt: item.publishedAt || 'Just now'
            };
        }

        async function loadHistory() {
            try {
                const response = await fetch('/api/content-items', { headers: { Accept: 'application/json' } });
                if (!response.ok) throw new Error('Failed to load content');

                const data = await response.json();
                historyData = (data.items || []).map(normalizeItem);
                renderHistory();
                setStatus(historyData.length ? 'Loaded saved content from DB.' : 'No content yet. Publish your first item.', 'muted');
            } catch (error) {
                setStatus('Could not load content from server.', 'danger');
            }
        }

        async function publishContent() {
            const title = contentTitle?.value.trim();
            if (!title) {
                setStatus('Title is required.', 'warn');
                return;
            }

            const formData = new FormData();
            formData.append('content_type', currentType);
            formData.append('title', title);
            formData.append('subtitle', contentSubtitle?.value.trim() || '');
            formData.append('description', contentDescription?.value.trim() || '');
            formData.append('tags', contentTags?.value.trim() || '');
            formData.append('visibility', getVisibility());

            if (mediaInput?.files?.[0]) {
                formData.append('media', mediaInput.files[0]);
            }

            publishContentBtn.disabled = true;
            publishContentBtn.textContent = 'Publishing...';
            
            // Show progress container if file exists
            const progressContainer = document.getElementById('uploadProgressContainer');
            const progressBar = document.getElementById('uploadProgressBar');
            const progressPercentage = document.getElementById('uploadPercentage');
            const fileSize = document.getElementById('uploadFileSize');
            
            if (mediaInput?.files?.[0]) {
                progressContainer.classList.remove('hidden');
                const fileSizeMB = (mediaInput.files[0].size / (1024 * 1024)).toFixed(2);
                fileSize.textContent = `${fileSizeMB}MB`;
                progressBar.style.width = '0%';
                progressPercentage.textContent = '0%';
            }

            return new Promise((resolve) => {
                const xhr = new XMLHttpRequest();
                
                // Track upload progress
                xhr.upload.addEventListener('progress', (e) => {
                    if (e.lengthComputable) {
                        const percentComplete = Math.round((e.loaded / e.total) * 100);
                        progressBar.style.width = percentComplete + '%';
                        progressPercentage.textContent = percentComplete + '%';
                        setStatus(`Uploading... ${percentComplete}%`, 'muted');
                    }
                });
                
                xhr.addEventListener('load', () => {
                    if (xhr.status >= 200 && xhr.status < 300) {
                        try {
                            const data = JSON.parse(xhr.responseText);
                            historyData.unshift(normalizeItem(data.item));
                            renderHistory();
                            switchView('history', document.querySelector('.create-toggle-btn.active-view') || document.querySelector('.create-toggle-btn'));
                            contentTitle.value = '';
                            contentSubtitle.value = '';
                            contentDescription.value = '';
                            contentTags.value = '';
                            mediaInput.value = '';
                            uploadZone.innerHTML = `
                                <div class="text-center">
                                    <p class="text-sm font-bold text-slate-700">Click to Upload <span id="mediaTypeLabel">Image/Video</span></p>
                                    <p class="text-xs text-slate-400 mt-2 font-semibold">Max Size: 1GB</p>
                                </div>
                            `;
                            progressContainer.classList.add('hidden');
                            setStatus('Content published and stored in DB.', 'ok');
                        } catch (e) {
                            setStatus('Content uploaded but response parsing failed.', 'warn');
                        }
                    } else {
                        try {
                            const errorData = JSON.parse(xhr.responseText);
                            setStatus(errorData?.message || 'Upload failed with status ' + xhr.status, 'danger');
                        } catch {
                            setStatus('Upload failed: ' + xhr.statusText, 'danger');
                        }
                    }
                    publishContentBtn.disabled = false;
                    publishContentBtn.textContent = 'Publish Content';
                    resolve();
                });
                
                xhr.addEventListener('error', () => {
                    setStatus('Network error during upload.', 'danger');
                    publishContentBtn.disabled = false;
                    publishContentBtn.textContent = 'Publish Content';
                    progressContainer.classList.add('hidden');
                    resolve();
                });
                
                xhr.addEventListener('abort', () => {
                    setStatus('Upload cancelled.', 'warn');
                    publishContentBtn.disabled = false;
                    publishContentBtn.textContent = 'Publish Content';
                    progressContainer.classList.add('hidden');
                    resolve();
                });
                
                xhr.open('POST', '/api/content-items');
                xhr.setRequestHeader('X-CSRF-TOKEN', CSRF_TOKEN);
                xhr.setRequestHeader('Accept', 'application/json');
                xhr.send(formData);
            });
        }

        function setContentType(type, btn) {
            currentType = type;
            document.querySelectorAll('.type-btn').forEach(b => b.classList.remove('active'));
            btn.classList.add('active');

            const label = document.getElementById('mediaTypeLabel');
            const req = document.getElementById('mediaRequirement');
            if (mediaInput) {
                mediaInput.accept = type === 'reel' ? 'video/*' : (type === 'live' ? 'video/*' : 'image/*,video/*');
            }

            if(type === 'reel') {
                label.innerText = 'Video Only';
                req.innerHTML = `<p class="text-[10px] font-bold uppercase text-blue-700">Reels: Vertical (9:16) videos recommended.</p>`;
            } else if(type === 'live') {
                label.innerText = 'Live Stream';
                req.innerHTML = `<p class="text-[10px] font-bold uppercase text-red-700">Live: Real-time video streaming.</p>`;
            } else {
                label.innerText = 'Image/Video';
                req.innerHTML = `<p class="text-[10px] font-bold uppercase text-yellow-700">Post: Landscape or Square supports both media types.</p>`;
            }
        }

        function switchView(view, btn) {
            const create = document.getElementById('createSection');
            const history = document.getElementById('historySection');
            document.querySelectorAll('.create-toggle-btn').forEach((b) => b.classList.remove('active-view'));
            btn.classList.add('active-view');
            
            if(view === 'create') {
                create.classList.remove('hidden');
                history.classList.add('hidden');
            } else {
                create.classList.add('hidden');
                history.classList.remove('hidden');
                renderHistory();
            }
        }

        function toggleGalleryMenu(itemId) {
            event?.stopPropagation();
            const allMenus = document.querySelectorAll('.gallery-menu');
            allMenus.forEach((menu) => {
                if (menu.id !== `menu-${itemId}`) menu.classList.add('hidden');
            });

            const target = document.getElementById(`menu-${itemId}`);
            if (!target) return;
            target.classList.toggle('hidden');
        }

        function setGalleryVisibility(itemId, visibility) {
            event?.stopPropagation();
            const item = historyData.find((entry) => entry.id === itemId);
            if (!item) return;

            fetch(`/api/content-items/${itemId}/visibility`, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': CSRF_TOKEN,
                    Accept: 'application/json'
                },
                body: JSON.stringify({ visibility })
            }).then(async (response) => {
                if (!response.ok) throw new Error('Failed to update visibility');
                item.visibility = visibility;
                renderHistory();
                setStatus('Visibility saved to DB.', 'ok');
            }).catch(() => {
                setStatus('Could not update visibility.', 'danger');
            });
        }

        function deleteGalleryItem(itemId) {
            event?.stopPropagation();
            fetch(`/api/content-items/${itemId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': CSRF_TOKEN,
                    Accept: 'application/json'
                }
            }).then((response) => {
                if (!response.ok) throw new Error('Delete failed');
                historyData = historyData.filter((entry) => entry.id !== itemId);
                renderHistory();
                setStatus('Content removed from DB.', 'ok');
            }).catch(() => {
                setStatus('Could not delete content.', 'danger');
            });
        }

        function renderHistory() {
            const grid = document.getElementById('galleryGrid');
            grid.innerHTML = '';
            if (historyData.length === 0) {
                grid.innerHTML = `<p class="text-center text-slate-500 col-span-full">No content published yet. Start by creating and publishing your first item!</p>`;
                return;
            }

            
            historyData.forEach(item => {
                let mediaMarkup = '';
                let playButtonOverlay = '';
        
                if (item.mediaType === 'video') {
                    // For videos, show a placeholder with play button
                    mediaMarkup = `<div class="w-full h-full bg-gradient-to-br from-slate-800 to-slate-900 flex items-center justify-center"><svg class="w-16 h-16 text-white opacity-80" fill="currentColor" viewBox="0 0 20 20"><path d="M6.3 2.841A1.5 1.5 0 004 4.11V15.89a1.5 1.5 0 002.3 1.269l9.344-5.89a1.5 1.5 0 000-2.538L6.3 2.84z"></path></svg></div>`;
                    playButtonOverlay = `<div class="absolute inset-0 bg-black/10 group-hover:bg-black/20 transition-all duration-500"></div>`;
                } else {
                    // For images, show actual image
                    mediaMarkup = `<img src="${item.img}" class="w-full h-full object-cover grayscale group-hover:grayscale-0 transition-all duration-500" alt="${item.title}">`;
                }

                const card = `
                    <div class="border border-slate-200 bg-white rounded-custom group overflow-hidden cursor-pointer hover:shadow-lg transition-shadow" onclick="openFullscreenViewer('${item.img}', '${item.mediaType}', '${item.title.replace(/'/g, "\\'")}')">
                        <div class="relative aspect-[4/5] bg-slate-100 overflow-hidden">
                            ${mediaMarkup}
                                            ${playButtonOverlay}
                            <span class="absolute top-2 left-2 bg-blue-600 text-white text-[10px] font-semibold px-2 py-1 rounded-custom">${item.type}</span>
                            <span class="absolute top-2 left-[46px] bg-slate-900/80 text-white text-[10px] font-semibold px-2 py-1 rounded-custom">${item.visibility}</span>
                            <div class="absolute top-2 right-2">
                                <button onclick="event.stopPropagation(); toggleGalleryMenu(${item.id})" class="w-7 h-7 text-slate-600 hover:text-blue-600">...</button>
                             <div id="menu-${item.id}" class="gallery-menu hidden absolute right-0 mt-2 w-48 bg-white border border-slate-200 p-3 rounded-5 z-50 shadow-sm">
    <div class="mb-3">
        <p class="text-[10px] font-black uppercase tracking-[0.15em] text-slate-400">Visibility</p>
    </div>

    <div class="relative bg-slate-100 p-1 flex items-center rounded-5 h-9 overflow-hidden">
        <div id="pill-${item.id}" 
             class="absolute top-1 bottom-1 w-[47%] bg-white rounded-5 shadow-sm transition-all duration-300 ease-out"
             style="left: ${item.visibility === 'public' ? '4px' : '49%'}; 
                    border: 1px solid ${item.visibility === 'public' ? '#e2e8f0' : '#f1f5f9'};">
        </div>

        <button onclick="event.stopPropagation(); setGalleryVisibility(${item.id}, 'public')" 
                class="relative z-10 flex-1 flex items-center justify-center gap-1.5 text-[11px] font-bold transition-colors ${item.visibility === 'public' ? 'text-blue-600' : 'text-slate-500'}">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path d="M12 21a9.004 9.004 0 008.716-6.747M12 21a9.004 9.004 0 01-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3"></path>
            </svg>
            Public
        </button>

        <button onclick="event.stopPropagation(); setGalleryVisibility(${item.id}, 'private')" 
                class="relative z-10 flex-1 flex items-center justify-center gap-1.5 text-[11px] font-bold transition-colors ${item.visibility === 'private' ? 'text-slate-900' : 'text-slate-500'}">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
            </svg>
            Private
        </button>
    </div>

    <div class="mt-3 pt-2 border-t border-slate-50">
        <button onclick="event.stopPropagation(); deleteGalleryItem(${item.id})" 
                class="w-full flex items-center justify-between px-2 py-2 rounded-5 text-red-500 hover:bg-red-50 transition-all group">
            <span class="text-[11px] font-black uppercase tracking-tight">Delete Asset</span>
            <svg class="w-4 h-4 opacity-50 group-hover:opacity-100" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
            </svg>
        </button>
    </div>
</div>
                            </div>
                        </div>
                        <div class="p-3 border-t border-slate-100">
                            <h4 class="text-xs font-bold truncate text-slate-800">${item.title}</h4>
                            <p class="text-[10px] text-slate-500 font-semibold mt-1">${item.publishedAt || 'Just now'}</p>
                        </div>
                    </div>
                `;
                grid.insertAdjacentHTML('beforeend', card);
            });

            gsap.from("#galleryGrid > div", {
                opacity: 0,
                y: 20,
                stagger: 0.05,
                duration: 0.4,
                overwrite: "auto",
                clearProps: "all"
            });
        }

        uploadZone?.addEventListener('click', openMediaPicker);
        mediaInput?.addEventListener('change', (e) => {
            const file = e.target.files && e.target.files[0];
            if (!file) return;
            renderMediaPreview(file);
        });

        publishContentBtn?.addEventListener('click', publishContent);

        window.addEventListener('click', (e) => {
            if (!e.target.closest('.gallery-menu') && !e.target.closest('button[onclick^="toggleGalleryMenu"]')) {
                document.querySelectorAll('.gallery-menu').forEach((menu) => menu.classList.add('hidden'));
            }
            
            // Close fullscreen modal when clicking outside the media container
            const modal = document.getElementById('fullscreenModal');
            if (modal && e.target === modal) {
                closeFullscreenViewer();
            }
        });

        // Close fullscreen viewer with Escape key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                const modal = document.getElementById('fullscreenModal');
                if (modal && modal.classList.contains('active')) {
                    closeFullscreenViewer();
                }
            }
        });

        renderHistory();
        loadHistory();
    </script>
  <script src="{{ asset('app.js') }}"></script>

</body>
</html>