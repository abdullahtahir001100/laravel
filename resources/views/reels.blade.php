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
    
    <?php 
    // Sample Videos Array (W3C standard test videos)
    $videos = [
        "https://www.w3schools.com/html/mov_bbb.mp4",
        "https://media.w3.org/2010/05/sintel/trailer.mp4",
        
    ];
    for($i=1; $i<=5; $i++): 
        $vid_src = $videos[$i % 2]; // Alternate between the 2 videos
    ?>
    <div class="reel-video-card" id="reel-<?php echo $i; ?>">
        <div class="video-wrapper md:rounded-custom md:border md:border-white/10 shadow-2xl">
            
            <video class="w-full h-full object-cover reel-video" loop muted playsinline>
                <source src="<?php echo $vid_src; ?>" type="video/mp4">
            </video>

            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-black/20 pointer-events-none"></div>

            <div class="absolute top-6 left-4 z-10 flex items-center gap-3 w-full">
                <h2 class="text-xl font-bold tracking-tight shadow-black drop-shadow-md">Reels</h2>
            </div>

            <div class="absolute bottom-6 left-4 z-10 w-[70%]">
                <div class="flex items-center gap-3 mb-3">
                    <img src="https://api.dicebear.com/7.x/avataaars/svg?seed=user<?php echo $i; ?>" class="w-10 h-10 rounded-full border-2 border-white">
                    <span class="text-sm font-bold shadow-black drop-shadow-md">Creator_Ali_<?php echo $i; ?></span>
                    <button class="bg-transparent border border-white text-[10px] font-bold uppercase px-3 py-1 rounded-custom hover:bg-white hover:text-black transition-colors">Follow</button>
                </div>
                <p class="text-sm text-white/90 line-clamp-2 drop-shadow-md">Testing the new video feature in our Connect App! Scroll down for more. 🔥 #Coding #Reels</p>
            </div>

            <div class="absolute bottom-6 right-4 z-10 flex flex-col items-center gap-5">
                
                <button onclick="animateIcon(this, '#ef4444')" class="flex flex-col items-center group">
                    <div class="p-2.5 bg-black/20 rounded-full group-hover:bg-white/10 transition backdrop-blur-sm border border-white/10">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                    </div>
                    <span class="text-[11px] font-bold mt-1 drop-shadow-md">12K</span>
                </button>

                <button onclick="openModal('comment-modal')" class="flex flex-col items-center group">
                    <div class="p-2.5 bg-black/20 rounded-full group-hover:bg-white/10 transition backdrop-blur-sm border border-white/10">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                    </div>
                    <span class="text-[11px] font-bold mt-1 drop-shadow-md">482</span>
                </button>

                <button onclick="openModal('share-modal')" class="flex flex-col items-center group">
                    <div class="p-2.5 bg-black/20 rounded-full group-hover:bg-white/10 transition backdrop-blur-sm border border-white/10">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"></path></svg>
                    </div>
                </button>

                <div class="relative">
                    <button onclick="toggleMenu(<?php echo $i; ?>)" class="p-2.5 bg-black/20 rounded-full hover:bg-white/10 transition backdrop-blur-sm border border-white/10">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"></path></svg>
                    </button>
                    <div id="reel-menu-<?php echo $i; ?>" class="hidden absolute bottom-full right-0 mb-3 w-40 bg-white text-black rounded-custom shadow-xl p-1 z-20">
                        <button onclick="removeReel(<?php echo $i; ?>)" class="flex items-center gap-2 w-full text-left p-2 text-sm font-bold text-red-500 hover:bg-red-50 rounded-custom">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636" stroke-width="2"></path></svg>
                            Not Interested
                        </button>
                    </div>
                </div>

                <img src="https://api.dicebear.com/7.x/avataaars/svg?seed=music<?php echo $i; ?>" class="w-8 h-8 rounded-custom border border-white animate-spin mt-4" style="animation-duration: 3s;">
            </div>
        </div>
    </div>
    <?php endfor; ?>
</div>

<div id="comment-modal" class="modal-overlay">
    <div class="modal-content bg-white text-black w-full max-w-md rounded-t-2xl md:rounded-custom p-5 absolute bottom-0 md:relative flex flex-col h-[60vh] md:h-auto">
        <div class="w-12 h-1.5 bg-slate-200 mx-auto mb-4 rounded-full md:hidden"></div>
        <div class="flex justify-between mb-4 pb-3 border-b border-slate-100">
            <h4 class="font-bold text-lg">Comments</h4>
            <button onclick="closeModal('comment-modal')" class="text-slate-400 hover:bg-slate-100 p-1 rounded-full"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12" stroke-width="2"></path></svg></button>
        </div>
        
        <div class="flex-1 overflow-y-auto space-y-4 mb-4 pr-2">
            <?php for($j=1; $j<=6; $j++): ?>
            <div class="flex gap-3">
                <img src="https://api.dicebear.com/7.x/avataaars/svg?seed=com<?php echo $j; ?>" class="w-9 h-9 rounded-full bg-slate-100">
                <div class="bg-slate-50 p-3 rounded-custom flex-1">
                    <p class="text-xs font-bold mb-1">User_Commenter_<?php echo $j; ?></p>
                    <p class="text-sm text-slate-700">This video is amazing bro! Keep uploading. ✨</p>
                </div>
            </div>
            <?php endfor; ?>
        </div>

        <div class="flex gap-2 pt-3 border-t border-slate-100">
            <input type="text" placeholder="Write a comment..." class="flex-1 bg-slate-100 p-3 rounded-custom text-sm outline-none focus:ring-2 focus:ring-blue-500 transition-all">
            <button class="bg-blue-600 hover:bg-blue-700 text-white px-5 rounded-custom text-sm font-bold transition-colors">Post</button>
        </div>
    </div>
</div>

<div id="share-modal" class="modal-overlay">
    <div class="modal-content bg-white text-black w-full max-w-sm rounded-custom p-6">
        <div class="flex justify-between mb-5">
            <h4 class="font-bold text-lg">Share Reel</h4>
            <button onclick="closeModal('share-modal')" class="text-slate-400 hover:text-slate-800"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12" stroke-width="2"></path></svg></button>
        </div>
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

</script>

</body>
</html>