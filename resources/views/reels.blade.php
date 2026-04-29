<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connect | Pro Reels</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/animejs/3.2.1/anime.min.js"></script>
    <style>
        body { background-color: #000; color: white; overflow: hidden; font-family: sans-serif; }
        .rounded-custom { border-radius: 8px !important; }
        
        /* Snap Scrolling Logic */
        .reels-container {
            height: 100vh;
            scroll-snap-type: y mandatory;
            overflow-y: scroll;
            scrollbar-width: none; /* Firefox */
        }
        .reels-container::-webkit-scrollbar { display: none; /* Chrome */ }
        
        .reel-video-card {
            height: 100vh;
            scroll-snap-align: start;
            position: relative;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .video-wrapper {
            height: 95vh;
            aspect-ratio: 9/16;
            background: #111;
            position: relative;
            overflow: hidden;
        }

        @media (max-width: 768px) {
            .video-wrapper { height: 100vh; width: 100vw; aspect-ratio: unset; border-radius: 0 !important; border: none; }
        }

        /* Modals */
        .modal-overlay { 
            display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.7); 
            z-index: 100; align-items: center; justify-content: center; backdrop-filter: blur(4px);
        }
    </style>
  <link href="{{ asset('app.css') }}" rel='stylesheet'>

</head>

<body>


<div class="fixed right-6 top-1/2 -translate-y-1/2 z-50 flex-col gap-4 hidden md:flex">

    <button onclick="navReel('up')" class="bg-white/10 hover:bg-white/30 backdrop-blur-md p-3 rounded-full transition-all border border-white/20">
        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M5 15l7-7 7 7"></path></svg>
    </button>
    <button onclick="navReel('down')" class="bg-white/10 hover:bg-white/30 backdrop-blur-md p-3 rounded-full transition-all border border-white/20">
        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M19 9l-7 7-7-7"></path></svg>
    </button>
</div>

<div class="reels-container" id="reels-container">
    <!-- Reels will be loaded here by JavaScript -->
</div>

<div id="comment-modal" class="modal-overlay">
    <div class="modal-content bg-white text-black w-full max-w-md rounded-t-2xl md:rounded-custom p-5 absolute bottom-0 md:relative flex flex-col h-[60vh] md:h-auto">
        <div class="w-12 h-1.5 bg-slate-200 mx-auto mb-4 rounded-full md:hidden"></div>
        <div class="flex justify-between mb-4 pb-3 border-b border-slate-100">
            <div>
                <h4 class="font-bold text-lg">Comments</h4>
                <p id="active-reel-label" class="text-[10px] uppercase tracking-widest text-slate-400 font-bold mt-1">Reel #1</p>
            </div>
            <button onclick="closeModal('comment-modal')" class="text-slate-400 hover:bg-slate-100 p-1 rounded-full"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12" stroke-width="2"></path></svg></button>
        </div>
        
        <div id="reel-comments-list" class="flex-1 overflow-y-auto space-y-4 mb-4 pr-2"></div>

        <div class="flex gap-2 pt-3 border-t border-slate-100">
            <input id="reel-comment-input" type="text" placeholder="Write a comment..." class="flex-1 bg-slate-100 p-3 rounded-custom text-sm outline-none focus:ring-2 focus:ring-blue-500 transition-all">
            <button onclick="postReelComment()" class="bg-blue-600 hover:bg-blue-700 text-white px-5 rounded-custom text-sm font-bold transition-colors">Post</button>
        </div>
    </div>
</div>

<div id="share-modal" class="modal-overlay">
    <div class="modal-content bg-white text-black w-full max-w-sm rounded-custom p-6">
        <div class="flex justify-between mb-5">
            <h4 class="font-bold text-lg">Share Reel</h4>
            <button onclick="closeModal('share-modal')" class="text-slate-400 hover:text-slate-800"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12" stroke-width="2"></path></svg></button>
        </div>
        <input id="share-link" type="text" value="https://connect.app/reel/1" readonly class="w-full border border-slate-200 rounded-custom p-3 text-sm mb-4 bg-slate-50 outline-none font-mono">
        <div class="grid grid-cols-4 gap-4 mb-6 text-center">
            <button class="flex flex-col items-center gap-2"><div class="w-12 h-12 bg-green-100 text-green-600 rounded-full flex items-center justify-center font-bold text-xl">W</div><span class="text-[10px] font-bold">WhatsApp</span></button>
            <button class="flex flex-col items-center gap-2"><div class="w-12 h-12 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center font-bold text-xl">F</div><span class="text-[10px] font-bold">Facebook</span></button>
            <button class="flex flex-col items-center gap-2"><div class="w-12 h-12 bg-sky-100 text-sky-600 rounded-full flex items-center justify-center font-bold text-xl">T</div><span class="text-[10px] font-bold">Twitter</span></button>
            <button class="flex flex-col items-center gap-2"><div class="w-12 h-12 bg-slate-100 text-slate-600 rounded-full flex items-center justify-center font-bold"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg></div><span class="text-[10px] font-bold">Copy</span></button>
        </div>
    </div>
</div>

<script>
    /* =========================================
       1. VIDEO CONTROLLER (Auto Play/Pause)
    ========================================= */
    const videos = document.querySelectorAll('.reel-video');
    
    // Intersection Observer to detect which video is on screen
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                // Video in view -> Play
                entry.target.play().catch(e => console.log("Autoplay blocked by browser. User must interact first."));
            } else {
                // Video out of view -> Pause & Reset
                entry.target.pause();
                entry.target.currentTime = 0; 
            }
        });
    }, { threshold: 0.6 }); // Trigger when 60% of video is visible

    videos.forEach(video => observer.observe(video));

    const CSRF_TOKEN = '{{ csrf_token() }}';
    let activeReelId = null;

    /* =========================================
       2. NAVIGATION CONTROLLER (Up/Down Buttons)
    ========================================= */
    function navReel(direction) {
        const container = document.getElementById('reels-container');
        const scrollAmount = window.innerHeight; // Scroll exactly one full screen height
        
        if (direction === 'up') {
            container.scrollBy({ top: -scrollAmount, behavior: 'smooth' });
        } else {
            container.scrollBy({ top: scrollAmount, behavior: 'smooth' });
        }
    }

    /* =========================================
       3. MODAL CONTROLLER (Clean Popup Logic)
    ========================================= */
    function openModal(modalId) {
        const modal = document.getElementById(modalId);
        const content = modal.querySelector('.modal-content');
        
        modal.style.display = 'flex';
        gsap.fromTo(modal, { opacity: 0 }, { opacity: 1, duration: 0.2 });
        gsap.fromTo(content, { y: 100, opacity: 0 }, { y: 0, opacity: 1, duration: 0.4, ease: "back.out(1.5)" });
    }

    function closeModal(modalId) {
        const modal = document.getElementById(modalId);
        const content = modal.querySelector('.modal-content');
        
        gsap.to(content, { y: 100, opacity: 0, duration: 0.3 });
        gsap.to(modal, { opacity: 0, duration: 0.3, onComplete: () => { modal.style.display = 'none'; }});
    }

    // Close Modals when clicking outside the content (on the overlay)
    document.querySelectorAll('.modal-overlay').forEach(overlay => {
        overlay.addEventListener('click', function(e) {
            if (e.target === this) { // Only close if clicking the dark background directly
                closeModal(this.id);
            }
        });
    });

    /* =========================================
       4. ACTION & ANIMATION CONTROLLER
    ========================================= */
    // Like/Action Animations
    function animateIcon(btn, color) {
        const svg = btn.querySelector('svg');
        const iconContainer = btn.querySelector('div');
        
        if (!btn.classList.contains('active')) {
            btn.classList.add('active');
            svg.style.fill = color;
            svg.style.color = color;
            iconContainer.classList.replace('bg-black/20', 'bg-white/20');
            
            anime({
                targets: svg,
                scale: [1, 1.5, 1],
                duration: 400,
                easing: 'spring(1, 80, 10, 0)'
            });
        } else {
            btn.classList.remove('active');
            svg.style.fill = 'none';
            svg.style.color = 'currentColor';
            iconContainer.classList.replace('bg-white/20', 'bg-black/20');
        }
    }

    // Three Dots Menu
    function toggleMenu(id) {
        const menu = document.getElementById(`reel-menu-${id}`);
        // Hide all other menus first
        document.querySelectorAll('[id^="reel-menu-"]').forEach(m => {
            if(m.id !== `reel-menu-${id}`) m.classList.add('hidden');
        });
        
        menu.classList.toggle('hidden');
        if (!menu.classList.contains('hidden')) {
            gsap.fromTo(menu, { opacity: 0, y: 10, scale: 0.95 }, { opacity: 1, y: 0, scale: 1, duration: 0.2 });
        }
    }

    // Not Interested (Remove Reel)
    function removeReel(id) {
        const reel = document.getElementById(`reel-${id}`);
        gsap.to(reel, { 
            opacity: 0, 
            scale: 0.8, 
            height: 0, 
            duration: 0.5, 
            ease: "power2.inOut",
            onComplete: () => reel.remove() 
        });
    }

    function openReelComments(itemId) {
        activeReelId = itemId;
        const label = document.getElementById('active-reel-label');
        if (label) label.textContent = `Reel #${itemId}`;
        openModal('comment-modal');
        loadReelComments(itemId);
    }

    async function loadReelComments(itemId) {
        const list = document.getElementById('reel-comments-list');
        if (!list) return;

        list.innerHTML = '<p class="text-sm text-slate-500">Loading comments...</p>';
        try {
            const response = await fetch(`/api/content-items/${itemId}/comments`, {
                headers: { Accept: 'application/json' }
            });
            const data = await response.json();
            const comments = data.data || data.comments || [];

            if (!comments.length) {
                list.innerHTML = '<p class="text-sm text-slate-500">No comments yet</p>';
                return;
            }

            list.innerHTML = comments.map((comment) => `
                <div class="flex gap-3">
                    <img src="${comment.user.avatarUrl || 'https://api.dicebear.com/7.x/avataaars/svg?seed=' + encodeURIComponent(comment.user.displayName || comment.user.name || 'user')}" class="w-9 h-9 rounded-full bg-slate-100">
                    <div class="bg-slate-50 p-3 rounded-custom flex-1">
                        <p class="text-xs font-bold mb-1">${comment.user.displayName || comment.user.name}</p>
                        <p class="text-sm text-slate-700">${comment.body}</p>
                    </div>
                </div>
            `).join('');
        } catch (error) {
            console.error('Error loading reel comments:', error);
            list.innerHTML = '<p class="text-sm text-red-500">Error loading comments</p>';
        }
    }

    async function postReelComment() {
        const input = document.getElementById('reel-comment-input');
        const body = input?.value.trim();
        if (!activeReelId || !body) return;

        try {
            const response = await fetch(`/api/content-items/${activeReelId}/comments`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': CSRF_TOKEN,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ body })
            });

            if (!response.ok) return;
            input.value = '';
            await loadReelComments(activeReelId);
            await loadReels();
        } catch (error) {
            console.error('Error posting reel comment:', error);
        }
    }

    // Load Reels from API
    async function loadReels() {
        try {
            const response = await fetch('/api/feed?scope=reels');
            const data = await response.json();
            const container = document.getElementById('reels-container');
            
            if (!data.data || data.data.length === 0) {
                container.innerHTML = '<div class="w-full h-full flex items-center justify-center"><p class="text-white text-center">No reels to show</p></div>';
                return;
            }

            container.innerHTML = data.data.map((item, index) => `
                <div class="reel-video-card" id="reel-${item.id}">
                    <div class="video-wrapper md:rounded-custom md:border md:border-white/10 shadow-2xl">
                        <video class="w-full h-full object-cover reel-video" loop muted playsinline>
                            <source src="${item.mediaUrl}" type="video/mp4">
                        </video>

                        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-black/20 pointer-events-none"></div>

                        <div class="absolute top-6 left-4 z-10 flex items-center gap-3 w-full">
                            <h2 class="text-xl font-bold tracking-tight shadow-black drop-shadow-md">Reels</h2>
                        </div>

                        <div class="absolute bottom-6 left-4 z-10 w-[70%]">
                            <div class="flex items-center gap-3 mb-3">
                                <img src="${item.authorAvatarUrl}" class="w-10 h-10 rounded-full border-2 border-white">
                                <span class="text-sm font-bold shadow-black drop-shadow-md cursor-pointer hover:text-blue-300" onclick="window.location.href='${item.authorProfileUrl}'">${item.authorName}</span>
                                ${item.isFriend ? 
                                  `<button onclick="window.location.href='/messages'" class="bg-blue-400 text-black text-[10px] font-bold uppercase px-3 py-1 rounded-custom hover:bg-blue-300 transition-colors">Message</button>` :
                                  (item.authorFollowStatus === 'requested' && item.authorFollowId ? 
                                    `<button data-user-id="${item.userId}" data-follow-id="${item.authorFollowId}" data-follow-status="requested" onclick="cancelReelFollow(${item.userId}, ${item.authorFollowId}, this)" class="bg-amber-400 text-black text-[10px] font-bold uppercase px-3 py-1 rounded-custom hover:bg-amber-300 transition-colors">Cancel</button>` :
                                    `<button data-user-id="${item.userId}" data-follow-id="${item.authorFollowId || ''}" data-follow-status="${item.authorFollowStatus || 'none'}" onclick="toggleReelFollow(${item.userId}, this)" class="bg-transparent border border-white text-[10px] font-bold uppercase px-3 py-1 rounded-custom hover:bg-white hover:text-black transition-colors">${item.authorFollowLabel || 'Follow'}</button>`)
                                }
                            </div>
                            <p class="text-sm text-white/90 line-clamp-2 drop-shadow-md">${item.description || item.title || ''}</p>
                        </div>

                        <div class="absolute bottom-6 right-4 z-10 flex flex-col items-center gap-5">
                            <button onclick="toggleReelLike(${item.id})" class="flex flex-col items-center group">
                                <div class="p-2.5 bg-black/20 rounded-full group-hover:bg-white/10 transition backdrop-blur-sm border border-white/10">
                                    <svg class="w-6 h-6 ${item.likedByMe ? 'fill-red-500' : ''}" fill="${item.likedByMe ? 'currentColor' : 'none'}" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                                </div>
                                <span class="text-[11px] font-bold mt-1 drop-shadow-md">${item.likesCount}</span>
                            </button>

                            <button onclick="openReelComments(${item.id})" class="flex flex-col items-center group">
                                <div class="p-2.5 bg-black/20 rounded-full group-hover:bg-white/10 transition backdrop-blur-sm border border-white/10">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                                </div>
                                <span class="text-[11px] font-bold mt-1 drop-shadow-md">${item.commentsCount}</span>
                            </button>

                            <button onclick="openReelShare(${item.id})" class="flex flex-col items-center group">
                                <div class="p-2.5 bg-black/20 rounded-full group-hover:bg-white/10 transition backdrop-blur-sm border border-white/10">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"></path></svg>
                                </div>
                            </button>

                            <div class="relative">
                                <button onclick="toggleReelMenu(${item.id})" class="p-2.5 bg-black/20 rounded-full hover:bg-white/10 transition backdrop-blur-sm border border-white/10">
                                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"></path></svg>
                                </button>
                                <div id="reel-menu-${item.id}" class="hidden absolute bottom-full right-0 mb-3 w-40 bg-white text-black rounded-custom shadow-xl p-1 z-20">
                                    <button onclick="markReelNotInterested(${item.id})" class="flex items-center gap-2 w-full text-left p-2 text-sm font-bold text-red-500 hover:bg-red-50 rounded-custom">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636" stroke-width="2"></path></svg>
                                        Not Interested
                                    </button>
                                </div>
                            </div>

                            <img src="${item.authorAvatarUrl}" class="w-8 h-8 rounded-custom border border-white animate-spin mt-4" style="animation-duration: 3s;">
                        </div>
                    </div>
                </div>
            `).join('');

            setupReelObservers();
        } catch (error) {
            console.error('Error loading reels:', error);
            document.getElementById('reels-container').innerHTML = '<div class="w-full h-full flex items-center justify-center"><p class="text-red-500 text-center">Error loading reels</p></div>';
        }
    }

    function setupReelObservers() {
        const videos = document.querySelectorAll('.reel-video');
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.play();
                } else {
                    entry.target.pause();
                }
            });
        }, { threshold: 0.6 });

        videos.forEach(video => observer.observe(video));
    }

    async function toggleReelFollow(userId, button) {
        const currentStatus = button?.dataset?.followStatus || 'none';
        try {
            const response = await fetch(`/api/users/${userId}/follow`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': CSRF_TOKEN,
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({})
            });
            const data = await response.json();
            
            if (response.ok) {
                button.dataset.followStatus = data.status || 'none';
                button.dataset.followId = data.followId || '';
                button.textContent = data.buttonLabel || 'Follow';
                
                // Change button styling based on status
                button.className = 'text-[10px] font-bold uppercase px-3 py-1 rounded-custom transition-colors ' +
                    (data.status === 'requested' ? 'bg-amber-400 text-black hover:bg-amber-300' : 
                     data.status === 'accepted' ? 'bg-emerald-400 text-black hover:bg-emerald-300' :
                     'bg-transparent border border-white text-white hover:bg-white hover:text-black');
            }
        } catch (error) {
            console.error('Follow toggle failed', error);
        }
    }

    async function cancelReelFollow(userId, followId, button) {
        try {
            const response = await fetch(`/api/follows/${followId}/cancel`, {
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': CSRF_TOKEN }
            });

            if (response.ok) {
                button.dataset.followStatus = 'none';
                button.dataset.followId = '';
                button.textContent = 'Follow';
                button.className = 'bg-transparent border border-white text-[10px] font-bold uppercase px-3 py-1 rounded-custom hover:bg-white hover:text-black transition-colors';
            }
        } catch (error) {
            console.error('Cancel follow failed', error);
        }
    }

    function toggleReelLike(itemId) {
        fetch(`/api/content-items/${itemId}/like`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': CSRF_TOKEN,
                'Content-Type': 'application/json'
            }
        }).then(() => loadReels()).catch(error => console.error('Error liking reel:', error));
    }

    function toggleReelMenu(itemId) {
        const menu = document.getElementById(`reel-menu-${itemId}`);
        menu.classList.toggle('hidden');
    }

    function openReelShare(itemId) {
        const shareInput = document.getElementById('share-link');
        if (shareInput) shareInput.value = `${window.location.origin}/reels?reel=${itemId}`;
        openModal('share-modal');
    }

    function markReelNotInterested(itemId) {
        fetch(`/api/content-items/${itemId}/not-interested`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': CSRF_TOKEN,
                'Content-Type': 'application/json'
            }
        }).then(() => loadReels()).catch(error => console.error('Error marking not interested:', error));
    }

    // Load reels on page load
    document.addEventListener('DOMContentLoaded', loadReels);

</script>

</body>
</html>