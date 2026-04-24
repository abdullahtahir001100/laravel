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
    </style>
</head>
<body class="flex min-h-screen bg-[#f0f2f5]">

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
                                <button onclick="setContentType('post', this)" class="type-btn active">Post</button>
                                <button onclick="setContentType('reel', this)" class="type-btn">Reel</button>
                                <button onclick="setContentType('live', this)" class="type-btn">Live</button>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="text-xs font-bold mb-2 block text-slate-600">Title</label>
                                <input type="text" placeholder="Enter title..." class="input-field">
                            </div>
                            <div>
                                <label class="text-xs font-bold mb-2 block text-slate-600">Subtitle</label>
                                <input type="text" placeholder="Enter subtitle..." class="input-field">
                            </div>
                        </div>

                        <div>
                            <label class="text-xs font-bold mb-2 block text-slate-600">Description</label>
                            <textarea rows="4" placeholder="Write something..." class="input-field resize-none"></textarea>
                        </div>

                        <div>
                            <label class="text-xs font-bold mb-2 block text-slate-600">Tags (Comma separated)</label>
                            <input type="text" placeholder="web, design, connect..." class="input-field">
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

                        <button class="w-full bg-blue-600 text-white py-3.5 rounded-custom font-semibold text-sm hover:bg-blue-700 transition-all">Publish Content</button>
                    </div>

                    <div class="space-y-6 bg-white border border-slate-200 rounded-custom p-5 md:p-6">
                        <label class="text-xs font-bold block text-blue-600">Step 2: Upload Media</label>
                        <input id="mediaInput" type="file" class="hidden" accept="image/*,video/*">
                        <div id="uploadZone" class="preview-box group cursor-pointer hover:border-blue-400 transition-all">
                            <div class="text-center">
                                <p class="text-sm font-bold text-slate-700">Click to Upload <span id="mediaTypeLabel">Image/Video</span></p>
                                <p class="text-xs text-slate-400 mt-2 font-semibold">Max Size: 50MB</p>
                            </div>
                        </div>
                        <div id="mediaRequirement" class="p-4 bg-amber-50 border border-amber-200 rounded-custom">
                            <p class="text-xs font-semibold text-amber-700">Requirement: Post supports Image and Video. Reels are Video only.</p>
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
        let currentType = 'post';

        let historyData = [
            { id: 1, type: 'post', title: 'Modern UI Kit', img: 'https://picsum.photos/400/300?random=1', visibility: 'public' },
            { id: 2, type: 'reel', title: 'Coding Timelapse', img: 'https://picsum.photos/300/500?random=2', visibility: 'public' },
            { id: 3, type: 'live', title: 'Reference Design', img: 'https://picsum.photos/400/400?random=3', visibility: 'private' },
            { id: 4, type: 'post', title: 'Calligraphy Art', img: 'https://picsum.photos/400/300?random=4', visibility: 'public' },
            { id: 5, type: 'reel', title: 'Studio Walkthrough', img: 'https://picsum.photos/300/500?random=5', visibility: 'private' },
        ];

        const uploadZone = document.getElementById('uploadZone');
        const mediaInput = document.getElementById('mediaInput');

        function openMediaPicker() {
            if (!mediaInput) return;
            mediaInput.value = '';
            mediaInput.click();
        }

        function renderMediaPreview(file) {
            if (!uploadZone || !file) return;
            const objectUrl = URL.createObjectURL(file);
            const isVideo = file.type.startsWith('video/');

            uploadZone.innerHTML = isVideo
                ? `<video controls class="w-full h-full object-cover rounded-custom bg-black"><source src="${objectUrl}" type="${file.type}"></video>`
                : `<img src="${objectUrl}" alt="Upload preview" class="w-full h-full object-cover rounded-custom">`;

            const mediaEl = uploadZone.querySelector(isVideo ? 'video' : 'img');
            mediaEl?.addEventListener('load', () => URL.revokeObjectURL(objectUrl), { once: true });
            mediaEl?.addEventListener('loadeddata', () => URL.revokeObjectURL(objectUrl), { once: true });
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
            const allMenus = document.querySelectorAll('.gallery-menu');
            allMenus.forEach((menu) => {
                if (menu.id !== `menu-${itemId}`) menu.classList.add('hidden');
            });

            const target = document.getElementById(`menu-${itemId}`);
            if (!target) return;
            target.classList.toggle('hidden');
        }

        function setGalleryVisibility(itemId, visibility) {
            const item = historyData.find((entry) => entry.id === itemId);
            if (!item) return;
            item.visibility = visibility;
            renderHistory();
        }

        function deleteGalleryItem(itemId) {
            historyData = historyData.filter((entry) => entry.id !== itemId);
            renderHistory();
        }

        function renderHistory() {
            const grid = document.getElementById('galleryGrid');
            grid.innerHTML = '';
            
            historyData.forEach(item => {
                const card = `
                    <div class="border border-slate-200 bg-white rounded-custom group overflow-hidden">
                        <div class="relative aspect-[4/5] bg-slate-100 overflow-hidden">
                            <img src="${item.img}" class="w-full h-full object-cover grayscale group-hover:grayscale-0 transition-all duration-500">
                            <span class="absolute top-2 left-2 bg-blue-600 text-white text-[10px] font-semibold px-2 py-1 rounded-custom">${item.type}</span>
                            <span class="absolute top-2 left-[46px] bg-slate-900/80 text-white text-[10px] font-semibold px-2 py-1 rounded-custom">${item.visibility}</span>
                            <div class="absolute top-2 right-2">
                                <button onclick="toggleGalleryMenu(${item.id})" class="w-7 h-7 text-slate-600 hover:text-blue-600">...</button>
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

        <button onclick="setGalleryVisibility(${item.id}, 'public')" 
                class="relative z-10 flex-1 flex items-center justify-center gap-1.5 text-[11px] font-bold transition-colors ${item.visibility === 'public' ? 'text-blue-600' : 'text-slate-500'}">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path d="M12 21a9.004 9.004 0 008.716-6.747M12 21a9.004 9.004 0 01-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3"></path>
            </svg>
            Public
        </button>

        <button onclick="setGalleryVisibility(${item.id}, 'private')" 
                class="relative z-10 flex-1 flex items-center justify-center gap-1.5 text-[11px] font-bold transition-colors ${item.visibility === 'private' ? 'text-slate-900' : 'text-slate-500'}">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
            </svg>
            Private
        </button>
    </div>

    <div class="mt-3 pt-2 border-t border-slate-50">
        <button onclick="deleteGalleryItem(${item.id})" 
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
                            <p class="text-[10px] text-slate-500 font-semibold mt-1">Uploaded 2 days ago</p>
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

        window.addEventListener('click', (e) => {
            if (!e.target.closest('.gallery-menu') && !e.target.closest('button[onclick^="toggleGalleryMenu"]')) {
                document.querySelectorAll('.gallery-menu').forEach((menu) => menu.classList.add('hidden'));
            }
        });

        renderHistory();
    </script>
  <script src="{{ asset('app.js') }}"></script>

</body>
</html>