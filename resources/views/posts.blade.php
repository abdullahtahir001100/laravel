<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connect | Ultimate Feed</title>
    <script src="https://cdn.tailwindcss.com"></script>
  <link href="{{ asset('app.css') }}" rel='stylesheet'>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/animejs/3.2.1/anime.min.js"></script>
    <style>
        body { background-color: #f0f2f5; font-family: sans-serif; height: 100vh; overflow: hidden; }
        .rounded-custom { border-radius: 5px !important; }
        .hide-scrollbar::-webkit-scrollbar { display: none; }
        .modal-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.6); z-index: 100; align-items: center; justify-content: center; backdrop-filter: blur(2px); }
        .post-card { transition: border-color 0.3s; }
        .active-post-border { border-color: #0062ff !important; border-width: 2px; }
        .mobile-comment-box { display: none; }
        
        /* Mobile vs Desktop logic */
        @media (max-width: 1024px) {
            #sidebar-comments { display: none !important; }
            body { overflow-y: auto; height: auto; }
        }
      main {
  padding-top: 5rem !important;
  /* Standard way to hide scrollbar for Firefox */
  scrollbar-width: none; 
  /* Standard way to hide scrollbar for IE/Edge */
  -ms-overflow-style: none; 
}

/* Chrome, Safari, and newer Edge */
main::-webkit-scrollbar {
  display: none;
}
    </style>
</head>


    
<body>
  
<x-dashboard-header />
<div class="flex  mx-auto h-full lg:h-screen">
    <x-dashboard-sidebar />
    <main id="main-feed" class="flex-1 overflow-y-auto p-4 md:p-8 hide-scrollbar space-y-6">
        <div id="posts-list" class="space-y-6"></div>
    </main>

    <aside id="sidebar-comments" class="hidden lg:flex flex-col w-[450px] bg-white border-l border-slate-200">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h3 class="font-bold text-slate-900">Post Comments</h3>
                <p id="current-post-label" class="text-[10px] text-blue-600 font-extrabold uppercase mt-1">Viewing Post #1</p>
            </div>
            <div class="w-2.5 h-2.5 bg-green-500 rounded-full animate-pulse"></div>
        </div>
        <div id="comment-scroll-area" class="flex-1 overflow-y-auto p-5 space-y-5 hide-scrollbar bg-slate-50/30"></div>
        <div class="p-5 border-t border-slate-100">
            <form onsubmit="postComment(event)" class="relative">
                <input type="text" id="comment-input" placeholder="Join the discussion..." class="w-full pl-4 pr-12 py-3.5 bg-white border border-slate-200 rounded-custom text-sm focus:outline-none focus:border-blue-500 shadow-sm transition-all">
                <button type="submit" class="absolute right-3 top-1/2 -translate-y-1/2 text-blue-600 hover:scale-110 transition-transform">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.429A1 1 0 009 15.571V11a1 1 0 112 0v4.571a1 1 0 00.725.962l5 1.428a1 1 0 001.17-1.408l-7-14z"></path></svg>
                </button>
            </form>
        </div>
    </aside>
</div>

<div id="likes-modal" class="modal-overlay">
    <div class="bg-white rounded-custom w-full max-w-sm p-5 border border-slate-200 m-4 shadow-2xl">
        <div class="flex items-center justify-between mb-4 border-b pb-3">
            <h4 class="font-bold text-slate-800 text-sm">Post Likes</h4>
            <button onclick="closeModals()" class="p-1 hover:bg-slate-100 rounded-custom"><svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
        </div>
        <div class="space-y-4 max-h-80 overflow-y-auto hide-scrollbar">
            <?php for($k=1; $k<=8; $k++): ?>
            <div class="flex items-center justify-between group">
                <div class="flex items-center gap-3">
                    <img src="https://api.dicebear.com/7.x/avataaars/svg?seed=like<?php echo $k; ?>" class="w-9 h-9 rounded-custom border">
                    <span class="text-xs font-bold text-slate-800">User_Alpha_<?php echo $k; ?></span>
                </div>
                <button class="text-[10px] font-bold text-blue-600 bg-blue-50 px-3 py-1.5 rounded-custom hover:bg-blue-600 hover:text-white transition-all">Follow</button>
            </div>
            <?php endfor; ?>
        </div>
    </div>
</div>

<div id="share-modal" class="modal-overlay">
    <div class="bg-white rounded-custom w-full max-w-md p-6 m-4 shadow-2xl border border-slate-200">
        <div class="flex items-center justify-between mb-4">
            <h4 class="font-bold text-slate-800">Share Post</h4>
            <button onclick="closeModals()" class="p-1 hover:bg-slate-100 rounded-custom"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
        </div>
        <input type="text" value="https://connect.app/post/7842" readonly class="w-full border border-slate-200 rounded-custom p-3 text-sm mb-4 bg-slate-50 outline-none font-mono">
        <div class="grid grid-cols-3 gap-2 mb-4">
            <button class="bg-slate-100 py-3 rounded-custom text-[10px] font-bold uppercase tracking-wider hover:bg-blue-50">WhatsApp</button>
            <button class="bg-slate-100 py-3 rounded-custom text-[10px] font-bold uppercase tracking-wider hover:bg-blue-50">Facebook</button>
            <button class="bg-slate-100 py-3 rounded-custom text-[10px] font-bold uppercase tracking-wider hover:bg-blue-50" onclick="alert('Copied!')">Copy Link</button>
        </div>
        <button onclick="closeModals()" class="w-full bg-slate-900 text-white py-3 rounded-custom font-bold text-sm uppercase tracking-widest">Close</button>
    </div>
</div>

<script>
    const CSRF_TOKEN = '{{ csrf_token() }}';
    const postsList = document.getElementById('posts-list');
    const sideComments = document.getElementById('comment-scroll-area');
    const commentInput = document.getElementById('comment-input');
    const currentPostLabel = document.getElementById('current-post-label');
    const headerSearchInput = document.getElementById('header-content-search');
    let activePostId = null;
    let currentTag = '';
    let items = [];

    async function loadPosts() {
        const params = new URLSearchParams({ scope: 'posts' });
        if (currentTag) params.set('tag', currentTag);

        const response = await fetch('/api/feed?' + params.toString(), {
            headers: { Accept: 'application/json' }
        });

        if (!response.ok) return;
        const data = await response.json();
        items = data.data || [];
        renderPosts();
        if (items[0]) setActivePost(items[0].id);
    }

    function postTemplate(item) {
        return `
            <div class="post-card bg-white border border-slate-200 rounded-custom relative" id="post-${item.id}" data-post-id="${item.id}">
                <div class="p-4 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <img src="${item.authorAvatarUrl || 'https://ui-avatars.com/api/?name=' + encodeURIComponent(item.authorName)}" class="w-10 h-10 rounded-custom border cursor-pointer" onclick="window.location.href='${item.authorProfileUrl}'">
                        <div>
                            <h4 class="text-sm font-bold text-slate-900">${item.authorName}</h4>
                            <p class="text-[10px] text-slate-400 font-bold uppercase">${item.publishedAt || 'Just now'}</p>
                        </div>
                    </div>
                    <div class="relative">
                        <button onclick="togglePostMenu(${item.id})" class="p-1 hover:bg-slate-100 rounded-custom">
                            <svg class="w-5 h-5 text-slate-400" fill="currentColor" viewBox="0 0 20 20"><path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"></path></svg>
                        </button>
                        <div id="menu-${item.id}" class="hidden absolute right-0 mt-2 w-48 bg-white border border-slate-200 rounded-custom p-2 z-20 shadow-xl">
                            <button onclick="markNotInterested(${item.id})" class="w-full text-left p-2 text-sm text-red-500 hover:bg-red-50 rounded-custom flex items-center gap-2 font-medium">Not Interested</button>
                        </div>
                    </div>
                </div>

                <div class="px-4 pb-3 text-sm text-slate-700">${item.description || item.title || ''}</div>
                <img src="${item.mediaUrl || 'https://picsum.photos/800/500'}" class="w-full aspect-video object-cover bg-slate-100 border-y border-slate-50">

                <div class="px-4 pt-2 text-[11px] text-slate-500">Tags: ${(item.tags || []).join(', ') || '-'}</div>
                <div class="p-3 flex items-center justify-between">
                    <div class="flex items-center gap-6">
                        <button onclick="toggleLike(${item.id})" class="group flex items-center gap-1.5 ${item.likedByMe ? 'text-red-500' : 'text-slate-500'}">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                            <span id="likes-${item.id}" class="text-xs font-bold">${item.likesCount}</span>
                        </button>
                        <button onclick="handleCommentClick(${item.id})" class="flex items-center gap-1.5 text-slate-500 hover:text-blue-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                            <span class="text-xs font-bold">${item.commentsCount}</span>
                        </button>
                        <button onclick="openShareModal(${item.id})" class="flex items-center gap-1.5 text-slate-500 hover:text-emerald-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"></path></svg>
                            <span class="text-xs font-bold">Share</span>
                        </button>
                    </div>
                </div>

                <div class="mobile-comment-box hidden lg:hidden border-t border-slate-100 p-4 bg-slate-50" id="mobile-comments-${item.id}">
                    <div class="space-y-3 mb-4" id="mobile-list-${item.id}"></div>
                    <form onsubmit="postMobileComment(event, ${item.id})" class="flex gap-2">
                        <input type="text" id="mobile-comment-input-${item.id}" placeholder="Write a comment..." class="flex-1 bg-white border border-slate-200 rounded-custom px-3 py-2 text-xs outline-none">
                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-custom text-xs font-bold">Post</button>
                    </form>
                </div>
            </div>
        `;
    }

    function renderPosts() {
        if (!items || items.length === 0) {
            postsList.innerHTML = `
                <div class="bg-white rounded-custom border border-slate-200 p-12 text-center">
                    <div class="mb-4">
                        <svg class="w-16 h-16 mx-auto text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    </div>
                    <h3 class="text-lg font-bold text-slate-900 mb-2">No Posts Yet</h3>
                    <p class="text-sm text-slate-500 mb-4">When you or your friends post images, they'll show up here.</p>
                    <a href="/facebook" class="text-xs font-semibold text-blue-600 hover:text-blue-700">Go to Main Feed</a>
                </div>
            `;
            document.getElementById('sidebar-comments').classList.add('hidden');
            return;
        }
        postsList.innerHTML = items.map(postTemplate).join('');
        if (items[0]) {
            setActivePost(items[0].id);
        }
    }

    async function toggleLike(itemId) {
        const response = await fetch(`/api/content-items/${itemId}/like`, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': CSRF_TOKEN, Accept: 'application/json' }
        });
        if (!response.ok) return;
        const data = await response.json();
        const item = items.find((entry) => entry.id === itemId);
        if (!item) return;
        item.likedByMe = data.liked;
        item.likesCount = data.likesCount;
        renderPosts();
    }

    async function setActivePost(itemId) {
        activePostId = itemId;
        document.querySelectorAll('.post-card').forEach((card) => card.classList.remove('active-post-border'));
        document.getElementById(`post-${itemId}`)?.classList.add('active-post-border');
        currentPostLabel.textContent = `Viewing Post #${itemId}`;

        const response = await fetch(`/api/content-items/${itemId}/comments`, { headers: { Accept: 'application/json' } });
        if (!response.ok) return;
        const data = await response.json();
        const comments = data.comments || data.data || [];
        const commentMarkup = comments.map((comment) => `
            <div class="flex gap-2 items-start">
                <img src="${comment.user.avatarUrl || 'https://ui-avatars.com/api/?name=' + encodeURIComponent(comment.user.displayName)}" class="w-7 h-7 rounded-custom">
                <div class="bg-white lg:bg-slate-100/50 p-2 rounded-custom flex-1 border border-slate-100">
                    <p class="text-[10px] font-bold">${comment.user.displayName}</p>
                    <p class="text-[11px] text-slate-600 mt-0.5">${comment.body}</p>
                </div>
            </div>
        `).join('');
        sideComments.innerHTML = commentMarkup;
        const mobileList = document.getElementById(`mobile-list-${itemId}`);
        if (mobileList) mobileList.innerHTML = commentMarkup;
    }

    async function postComment(e) {
        e.preventDefault();
        if (!activePostId) return;
        const body = commentInput.value.trim();
        if (!body) return;

        const response = await fetch(`/api/content-items/${activePostId}/comments`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': CSRF_TOKEN,
                Accept: 'application/json'
            },
            body: JSON.stringify({ body })
        });

        if (!response.ok) return;
        commentInput.value = '';
        await setActivePost(activePostId);
        await loadPosts();
    }

    async function markNotInterested(itemId) {
        const response = await fetch(`/api/content-items/${itemId}/not-interested`, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': CSRF_TOKEN, Accept: 'application/json' }
        });

        if (!response.ok) return;
        items = items.filter((entry) => entry.id !== itemId);
        renderPosts();
        if (items[0]) setActivePost(items[0].id);
    }

    async function postMobileComment(event, itemId) {
        event.preventDefault();
        const input = document.getElementById(`mobile-comment-input-${itemId}`);
        const body = input?.value.trim();
        if (!body) return;

        const response = await fetch(`/api/content-items/${itemId}/comments`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': CSRF_TOKEN,
                Accept: 'application/json'
            },
            body: JSON.stringify({ body })
        });

        if (!response.ok) return;
        input.value = '';
        await setActivePost(itemId);
        await loadPosts();
    }

    function handleCommentClick(itemId) {
        if (window.innerWidth <= 1024) {
            const commentBox = document.getElementById(`mobile-comments-${itemId}`);
            const shouldOpen = commentBox?.classList.contains('hidden');

            document.querySelectorAll('.mobile-comment-box').forEach((entry) => {
                entry.classList.add('hidden');
            });

            if (shouldOpen) {
                commentBox?.classList.remove('hidden');
            }
        }

        setActivePost(itemId);
    }

    function openShareModal(itemId) {
        const input = document.getElementById('share-link');
        if (input) input.value = `${window.location.origin}/posts?post=${itemId}`;
        document.getElementById('share-modal').style.display = 'flex';
    }

    function togglePostMenu(id) {
        const menu = document.getElementById(`menu-${id}`);
        document.querySelectorAll('[id^="menu-"]').forEach((entry) => {
            if (entry.id !== `menu-${id}`) entry.classList.add('hidden');
        });
        menu?.classList.toggle('hidden');
    }

    function closeModals() {
        document.querySelectorAll('.modal-overlay').forEach((m) => m.style.display = 'none');
    }

    headerSearchInput?.addEventListener('change', async (e) => {
        currentTag = (e.target.value || '').trim();
        await loadPosts();
    });

    loadPosts();
</script>
  <script src="{{ asset('app.js') }}"></script>

</body>
</html>