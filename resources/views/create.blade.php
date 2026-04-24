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
        * { border-radius: 0 !important; box-shadow: none !important; }
        body { background-color: #fff; color: #111; font-family: 'Inter', sans-serif; }
        .hide-scroll::-webkit-scrollbar { display: none; }
        
        .main-content { padding-top: 5rem; }
        
        .input-field {
            border: 2px solid #000;
            padding: 12px;
            width: 100%;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 12px;
            outline: none;
        }
        .input-field:focus { background-color: #f8fafc; }

        .type-btn {
            border: 1px solid #e2e8f0;
            padding: 15px;
            flex: 1;
            text-align: center;
            font-weight: 900;
            text-transform: uppercase;
            font-size: 11px;
            transition: all 0.3s;
        }
        .type-btn.active { background: #000; color: #fff; border-color: #000; }

        .preview-box {
            border: 2px dashed #e2e8f0;
            aspect-ratio: 16/9;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #fbfbfb;
        }
    </style>
</head>
<body class="flex min-h-screen bg-white">

    <x-dashboard-sidebar />
        <x-dashboard-header />

    <div class="flex-1 flex flex-col h-screen overflow-hidden">

        <div class="main-content flex flex-col h-full overflow-hidden">
            <div class="border-b border-black bg-white px-6 py-8">
                <div class="flex flex-col md:flex-row justify-between items-end gap-4">
                    <div>
                        <h1 class="text-4xl font-black uppercase tracking-tighter">Content Studio</h1>
                        <p class="text-[10px] font-bold text-slate-400 tracking-widest uppercase mt-1">Upload Posts, Reels & Manage Saved Items</p>
                    </div>
                    <div class="flex gap-2 w-full md:w-auto">
                        <button onclick="switchView('create', this)" class="bg-black text-white px-8 py-3 text-[11px] font-black uppercase active-view">Create New</button>
                        <button onclick="switchView('history', this)" class="border-2 border-black px-8 py-3 text-[11px] font-black uppercase">Your Gallery</button>
                    </div>
                </div>
            </div>

            <main class="flex-1 overflow-y-auto p-6 md:p-10 bg-slate-50/30">
                
                <div id="createSection" class="max-w-6xl mx-auto grid grid-cols-1 lg:grid-cols-2 gap-10">
                    
                    <div class="space-y-6">
                        <div>
                            <label class="text-[10px] font-black uppercase tracking-widest mb-3 block text-blue-600">Step 1: Select Type</label>
                            <div class="flex gap-0">
                                <button onclick="setContentType('post', this)" class="type-btn active">Post</button>
                                <button onclick="setContentType('reel', this)" class="type-btn">Reel</button>
                                <button onclick="setContentType('saved', this)" class="type-btn">Saved</button>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="text-[10px] font-black uppercase tracking-widest mb-2 block">Title</label>
                                <input type="text" placeholder="Enter title..." class="input-field">
                            </div>
                            <div>
                                <label class="text-[10px] font-black uppercase tracking-widest mb-2 block">Subtitle</label>
                                <input type="text" placeholder="Enter subtitle..." class="input-field">
                            </div>
                        </div>

                        <div>
                            <label class="text-[10px] font-black uppercase tracking-widest mb-2 block">Description</label>
                            <textarea rows="4" placeholder="Write something..." class="input-field resize-none"></textarea>
                        </div>

                        <div>
                            <label class="text-[10px] font-black uppercase tracking-widest mb-2 block">Tags (Comma separated)</label>
                            <input type="text" placeholder="web, design, connect..." class="input-field">
                        </div>

                        <div>
                            <label class="text-[10px] font-black uppercase tracking-widest mb-3 block">Visibility</label>
                            <div class="flex gap-6">
                                <label class="flex items-center gap-2 cursor-pointer font-bold text-xs uppercase">
                                    <input type="radio" name="privacy" value="public" checked class="w-4 h-4 accent-black"> Public
                                </label>
                                <label class="flex items-center gap-2 cursor-pointer font-bold text-xs uppercase">
                                    <input type="radio" name="privacy" value="private" class="w-4 h-4 accent-black"> Private
                                </label>
                            </div>
                        </div>

                        <button class="w-full bg-black text-white py-4 font-black uppercase tracking-widest text-sm hover:bg-zinc-800 transition-all">Publish Content</button>
                    </div>

                    <div class="space-y-6">
                        <label class="text-[10px] font-black uppercase tracking-widest block text-blue-600">Step 2: Upload Media</label>
                        <div id="uploadZone" class="preview-box group cursor-pointer hover:border-black transition-all">
                            <div class="text-center">
                                <p class="text-[11px] font-black uppercase tracking-tighter">Click to Upload <span id="mediaTypeLabel">Image/Video</span></p>
                                <p class="text-[9px] text-slate-400 mt-2 font-bold uppercase">Max Size: 50MB</p>
                            </div>
                        </div>
                        <div id="mediaRequirement" class="p-4 bg-yellow-50 border border-yellow-200">
                            <p class="text-[10px] font-bold uppercase text-yellow-700">Requirement: Post supports Image & Video. Reels are Video only.</p>
                        </div>
                    </div>
                </div>

                <div id="historySection" class="hidden max-w-7xl mx-auto">
                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4" id="galleryGrid">
                        </div>
                </div>

            </main>
        </div>
    </div>

    <script>
        let currentType = 'post';

        const historyData = [
            { id: 1, type: 'post', title: 'Modern UI Kit', img: 'https://picsum.photos/400/300?random=1' },
            { id: 2, type: 'reel', title: 'Coding Timelapse', img: 'https://picsum.photos/300/500?random=2' },
            { id: 3, type: 'saved', title: 'Reference Design', img: 'https://picsum.photos/400/400?random=3' },
            { id: 4, type: 'post', title: 'Calligraphy Art', img: 'https://picsum.photos/400/300?random=4' },
            { id: 5, type: 'reel', title: 'Studio Walkthrough', img: 'https://picsum.photos/300/500?random=5' },
        ];

        function setContentType(type, btn) {
            currentType = type;
            document.querySelectorAll('.type-btn').forEach(b => b.classList.remove('active'));
            btn.classList.add('active');

            const label = document.getElementById('mediaTypeLabel');
            const req = document.getElementById('mediaRequirement');

            if(type === 'reel') {
                label.innerText = 'Video Only';
                req.innerHTML = `<p class="text-[10px] font-bold uppercase text-blue-700">Reels: Vertical (9:16) videos recommended.</p>`;
            } else if(type === 'saved') {
                label.innerText = 'Bookmark Item';
                req.innerHTML = `<p class="text-[10px] font-bold uppercase text-slate-700">Saved: Add external or internal items to your collection.</p>`;
            } else {
                label.innerText = 'Image/Video';
                req.innerHTML = `<p class="text-[10px] font-bold uppercase text-yellow-700">Post: Landscape or Square supports both media types.</p>`;
            }
        }

        function switchView(view, btn) {
            const create = document.getElementById('createSection');
            const history = document.getElementById('historySection');
            
            if(view === 'create') {
                create.classList.remove('hidden');
                history.classList.add('hidden');
            } else {
                create.classList.add('hidden');
                history.classList.remove('hidden');
                renderHistory();
            }
        }

        function renderHistory() {
            const grid = document.getElementById('galleryGrid');
            grid.innerHTML = '';
            
            historyData.forEach(item => {
                const card = `
                    <div class="border border-slate-200 bg-white group overflow-hidden">
                        <div class="relative aspect-[4/5] bg-slate-100 overflow-hidden">
                            <img src="${item.img}" class="w-full h-full object-cover grayscale group-hover:grayscale-0 transition-all duration-500">
                            <span class="absolute top-2 left-2 bg-black text-white text-[8px] font-black uppercase px-2 py-1">${item.type}</span>
                        </div>
                        <div class="p-3 border-t border-slate-100">
                            <h4 class="text-[10px] font-black uppercase truncate">${item.title}</h4>
                            <p class="text-[8px] text-slate-400 font-bold uppercase mt-1">Uploaded 2 days ago</p>
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
    </script>
</body>
</html>