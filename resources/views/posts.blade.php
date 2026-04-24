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
        
        /* Mobile vs Desktop logic */
        @media (max-width: 1024px) {
            #sidebar-comments { display: none !important; }
            .mobile-comment-box { display: block; }
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
        <?php for($i=1; $i<=10; $i++): ?>
        <div class="post-card bg-white border border-slate-200 rounded-custom relative" id="post-<?php echo $i; ?>" data-post-id="<?php echo $i; ?>">
            <div class="p-4 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <img src="https://api.dicebear.com/7.x/avataaars/svg?seed=user<?php echo $i; ?>" class="w-10 h-10 rounded-custom border">
                    <div>
                        <h4 class="text-sm font-bold text-slate-900">User Account <?php echo $i; ?></h4>
                        <p class="text-[10px] text-slate-400 font-bold uppercase">Posted Just Now</p>
                    </div>
                </div>
                <div class="relative">
                    <button onclick="togglePostMenu(<?php echo $i; ?>)" class="p-1 hover:bg-slate-100 rounded-custom">
                        <svg class="w-5 h-5 text-slate-400" fill="currentColor" viewBox="0 0 20 20"><path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"></path></svg>
                    </button>
                    <div id="menu-<?php echo $i; ?>" class="hidden absolute right-0 mt-2 w-48 bg-white border border-slate-200 rounded-custom p-2 z-20 shadow-xl">
                        <button onclick="removePost(<?php echo $i; ?>)" class="w-full text-left p-2 text-sm text-red-500 hover:bg-red-50 rounded-custom flex items-center gap-2 font-medium">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636"></path></svg>
                            Not Interested
                        </button>
                    </div>
                </div>
            </div>

            <div class="px-4 pb-3 text-sm text-slate-700">Checking out the final version of the Connect Feed! Looks clean with GSAP. 🔥</div>
            <img src="https://picsum.photos/800/500?random=<?php echo $i; ?>" class="w-full aspect-video object-cover bg-slate-100 border-y border-slate-50">

            <div class="p-3 flex items-center justify-between">
                <div class="flex items-center gap-6">
                    <button onclick="likeAnim(this)" class="group flex items-center gap-1.5">
                        <svg class="w-6 h-6 text-slate-500 group-hover:text-red-500 transition-colors" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                    </button>
                    <button onclick="toggleMobileComments(<?php echo $i; ?>)" class="flex items-center gap-1.5 text-slate-500 hover:text-blue-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                    </button>
                    <button onclick="openModal('share-modal')" class="text-slate-500 hover:text-green-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"></path></svg>
                    </button>
                </div>
                <button onclick="openModal('likes-modal')" class="text-[11px] font-bold text-slate-400 hover:text-slate-900 transition-colors uppercase">View All Likes</button>
            </div>

            <div id="mobile-comments-<?php echo $i; ?>" class="hidden lg:hidden border-t border-slate-100 p-4 bg-slate-50">
                <div class="space-y-3 mb-4" id="mobile-list-<?php echo $i; ?>">
                    </div>
                <div class="flex gap-2">
                    <input type="text" placeholder="Write a comment..." class="flex-1 bg-white border border-slate-200 rounded-custom px-3 py-2 text-xs outline-none">
                    <button class="bg-blue-600 text-white px-4 py-2 rounded-custom text-xs font-bold">Post</button>
                </div>
            </div>
        </div>
        <?php endfor; ?>
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
    let activePostId = 1;

    // 1. SCROLL OBSERVER (Desktop Side Panel Update)
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting && window.innerWidth > 1024) {
                updateActivePost(entry.target.getAttribute('data-post-id'));
            }
        });
    }, { root: document.getElementById('main-feed'), threshold: 0.6 });

    document.querySelectorAll('.post-card').forEach(post => observer.observe(post));

    function updateActivePost(id) {
        if(activePostId == id) return;
        activePostId = id;
        document.querySelectorAll('.post-card').forEach(p => p.classList.remove('active-post-border'));
        document.getElementById(`post-${id}`).classList.add('active-post-border');
        document.getElementById('current-post-label').innerText = `Viewing Post #${id}`;

        const area = document.getElementById('comment-scroll-area');
        gsap.to(area, { opacity: 0, duration: 0.2, onComplete: () => {
            area.innerHTML = mockComments(id);
            gsap.to(area, { opacity: 1, duration: 0.3 });
        }});
    }

    // 2. MOBILE COMMENT TOGGLE (Facebook Style)
    function toggleMobileComments(id) {
        const box = document.getElementById(`mobile-comments-${id}`);
        const list = document.getElementById(`mobile-list-${id}`);
        
        if(box.classList.contains('hidden')) {
            list.innerHTML = mockComments(id);
            box.classList.remove('hidden');
            gsap.from(box, { height: 0, opacity: 0, duration: 0.4, ease: "power2.out" });
        } else {
            gsap.to(box, { height: 0, opacity: 0, duration: 0.3, onComplete: () => box.classList.add('hidden') });
        }
    }

    function mockComments(id) {
        let html = '';
        for(let j=1; j<=3; j++) {
            html += `
                <div class="flex gap-2 items-start">
                    <img src="https://api.dicebear.com/7.x/avataaars/svg?seed=c${id}${j}" class="w-7 h-7 rounded-custom">
                    <div class="bg-white lg:bg-slate-100/50 p-2 rounded-custom flex-1 border border-slate-100">
                        <p class="text-[10px] font-bold">User_${id}_${j}</p>
                        <p class="text-[11px] text-slate-600 mt-0.5">Epic content for post #${id}!</p>
                    </div>
                </div>`;
        }
        return html;
    }

    // 3. ACTIONS
    function postComment(e) {
        e.preventDefault();
        const input = document.getElementById('comment-input');
        if(!input.value) return;
        const area = document.getElementById('comment-scroll-area');
        const newHtml = `<div class="flex gap-3 opacity-0"><div class="bg-white p-3 rounded-custom border border-blue-100 flex-1"><p class="text-[11px] font-bold">You</p><p class="text-xs text-slate-600">${input.value}</p></div></div>`;
        area.insertAdjacentHTML('afterbegin', newHtml);
        gsap.to(area.firstChild, { opacity: 1, duration: 0.5 });
        input.value = '';
    }

    function openModal(modalId) {
        const m = document.getElementById(modalId);
        m.style.display = 'flex';
        gsap.fromTo(m.querySelector('div'), { scale: 0.8, opacity: 0 }, { scale: 1, opacity: 1, duration: 0.4, ease: "back.out(1.7)" });
    }

    function closeModals() {
        document.querySelectorAll('.modal-overlay').forEach(m => m.style.display = 'none');
    }

    function likeAnim(btn) {
        btn.classList.toggle('text-red-500');
        anime({ targets: btn.querySelector('svg'), scale: [1, 1.5, 1], duration: 400 });
    }

    function togglePostMenu(id) {
        const menu = document.getElementById(`menu-${id}`);
        menu.classList.toggle('hidden');
        if(!menu.classList.contains('hidden')) gsap.from(menu, { opacity: 0, y: -10, duration: 0.2 });
    }

    function removePost(id) {
        gsap.to(`#post-${id}`, { opacity: 0, scale: 0.8, duration: 0.4, onComplete: () => document.getElementById(`post-${id}`).remove() });
    }

    // Initial Load
    if(window.innerWidth > 1024) document.getElementById('comment-scroll-area').innerHTML = mockComments(1);
</script>
  <script src="{{ asset('app.js') }}"></script>

</body>
</html>