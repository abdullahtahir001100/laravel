<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Live Feed Pro | Studio Command</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <style>
        body { background-color: #0a0a0a; color: #ffffff; font-family: 'Inter', sans-serif; overflow: hidden; }
        
        .main-grid { display: grid; grid-template-columns: 1fr 380px; height: calc(100vh);  }

        /* Vertical Scroll Snapping */
        .feed-container { 
            background: #000; 
            height: 100%; 
            overflow-y: scroll; 
            scroll-snap-type: y mandatory; 
            scroll-behavior: smooth;
        }
        #chat-feed{
        max-height: 83vh !important;
        
        overflow-y: auto !important;
            scroll-behavior: smooth;

        }
        .feed-container::-webkit-scrollbar { display: none; }
        
        .stream-item { 
            height: 100%; 
            width: 100%; 
            scroll-snap-align: start; 
            position: relative; 
            display: flex; 
            align-items: center; 
            justify-content: center; 
            overflow: hidden;
            background: #000;
        }

        .live-badge { background: #ef4444; color: white; padding: 4px 8px; font-weight: 800; font-size: 10px; letter-spacing: 1px; }

        /* Floating Hearts */
        .heart-svg { position: absolute; pointer-events: none; fill: #ef4444; z-index: 50; filter: drop-shadow(0 2px 4px rgba(0,0,0,0.5)); }

        /* Custom Chat Scrollbar */
        .chat-scroll { overflow-y: auto; scroll-behavior: smooth; }
        .chat-scroll::-webkit-scrollbar { width: 5px; }
        .chat-scroll::-webkit-scrollbar-track { background: transparent; }
        .chat-scroll::-webkit-scrollbar-thumb { background: #3f3f46; border-radius: 5px; }
        .chat-scroll::-webkit-scrollbar-thumb:hover { background: #52525b; }

        /* Glass Flat */
        .glass-flat { background: rgba(0, 0, 0, 0.4); backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.1); }
        
        /* Chat Entry Animation */
        .comment-enter { animation: fadeUp 0.3s ease-out forwards; }
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body class="flex overflow-hidden">

 

    <div class="flex-1 flex flex-col h-screen">
       
        <div class="main-grid">
            
            <section class="feed-container" id="video-feed">
                
                <div class="stream-item group">
                    <video autoplay loop muted playsinline class="absolute w-full h-full object-cover z-0 opacity-90">
                        <source src="{{ asset('v1.mp4') }}" type="video/mp4">
                    </video>
                    
                    <div class="absolute top-6 left-6 flex items-center gap-3 z-20">
                        <div class="live-badge rounded-[5px] shadow-lg">LIVE</div>
                        <div class="glass-flat rounded-[5px] px-3 py-1.5 flex items-center gap-2 text-xs font-bold shadow-lg">
                            <svg class="w-2.5 h-2.5 text-red-500 animate-pulse" fill="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/></svg>
                            <span class="viewer-count">14.2K</span>
                        </div>
                    </div>

                    <div class="absolute right-6 bottom-28 flex flex-col items-center gap-6 z-20">
                        <div class="flex flex-col items-center gap-1 group/btn cursor-pointer" onclick="handleLike(this)">
                            <div class="w-12 h-12 glass-flat rounded-full flex items-center justify-center hover:bg-white hover:text-black transition-colors shadow-lg">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                            </div>
                            <span class="text-[11px] font-bold drop-shadow-md">2.4K</span>
                        </div>

                        <div class="flex flex-col items-center gap-1 group/btn cursor-pointer" onclick="toggleShare()">
                            <div class="w-12 h-12 glass-flat rounded-full flex items-center justify-center hover:bg-white hover:text-black transition-colors shadow-lg">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"></path></svg>
                            </div>
                            <span class="text-[11px] font-bold drop-shadow-md">850</span>
                        </div>
                    </div>

                    <div class="absolute bottom-0 left-0 w-full p-8 bg-gradient-to-t from-black via-black/80 to-transparent z-10">
                        <div class="flex items-end justify-between">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-full border-2 border-white overflow-hidden shadow-lg">
                                    <img src="https://via.placeholder.com/50" class="w-full h-full object-cover">
                                </div>
                                <div>
                                    <h2 class="text-base font-bold text-white drop-shadow-md">InkByHand Calligraphy</h2>
                                    <p class="text-xs text-zinc-300 mt-0.5">Masterclass Live Session #1</p>
                                </div>
                            </div>
                            <button class="bg-white text-black px-5 py-2 rounded-[5px] text-xs font-bold hover:bg-zinc-200 transition-colors shadow-lg">Follow</button>
                        </div>
                    </div>
                </div>

                <div class="stream-item group">
                    <video autoplay loop muted playsinline class="absolute w-full h-full object-cover z-0 opacity-90">
                        <source src="{{ asset('v1.mp4') }}" type="video/mp4">
                    </video>
                    
                    <div class="absolute top-6 left-6 flex items-center gap-3 z-20">
                        <div class="live-badge rounded-[5px] shadow-lg">LIVE</div>
                        <div class="glass-flat rounded-[5px] px-3 py-1.5 flex items-center gap-2 text-xs font-bold shadow-lg">
                            <svg class="w-2.5 h-2.5 text-red-500 animate-pulse" fill="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/></svg>
                            <span class="viewer-count">8.1K</span>
                        </div>
                    </div>

                    <div class="absolute right-6 bottom-28 flex flex-col items-center gap-6 z-20">
                        <div class="flex flex-col items-center gap-1 group/btn cursor-pointer" onclick="handleLike(this)">
                            <div class="w-12 h-12 glass-flat rounded-full flex items-center justify-center hover:bg-white hover:text-black transition-colors shadow-lg">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                            </div>
                            <span class="text-[11px] font-bold drop-shadow-md">1.1K</span>
                        </div>

                        <div class="flex flex-col items-center gap-1 group/btn cursor-pointer" onclick="toggleShare()">
                            <div class="w-12 h-12 glass-flat rounded-full flex items-center justify-center hover:bg-white hover:text-black transition-colors shadow-lg">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"></path></svg>
                            </div>
                            <span class="text-[11px] font-bold drop-shadow-md">320</span>
                        </div>
                    </div>

                    <div class="absolute bottom-0 left-0 w-full p-8 bg-gradient-to-t from-black via-black/80 to-transparent z-10">
                        <div class="flex items-end justify-between">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-full border-2 border-white overflow-hidden shadow-lg">
                                    <img src="https://via.placeholder.com/50" class="w-full h-full object-cover">
                                </div>
                                <div>
                                    <h2 class="text-base font-bold text-white drop-shadow-md">Viking Armory Live</h2>
                                    <p class="text-xs text-zinc-300 mt-0.5">Forging The Sword</p>
                                </div>
                            </div>
                            <button class="bg-white text-black px-5 py-2 rounded-[5px] text-xs font-bold hover:bg-zinc-200 transition-colors shadow-lg">Follow</button>
                        </div>
                    </div>
                </div>

            </section>

            <aside class="bg-[#121212] border-l border-zinc-800 flex flex-col shadow-[-5px_0_15px_rgba(0,0,0,0.5)] z-30">
                
                <div class="p-4 border-b border-zinc-800 bg-[#18181b] flex justify-between items-center shadow-sm">
                    <h3 class="text-sm font-bold text-zinc-100 flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-red-500 animate-pulse"></span>
                        Live Chat
                    </h3>
                </div>

                <div id="chat-feed" class="flex-1 overflow-y-auto chat-scroll p-4 space-y-4 bg-[#0f0f13]">
                    </div>

                <div class="p-4 bg-[#18181b] border-t border-zinc-800">
                    <div class="flex gap-2 items-center bg-[#27272a] rounded-[5px] border border-zinc-700 p-1">
                        <input id="chat-input" type="text" placeholder="Say something in live chat..." class="flex-1 bg-transparent border-none text-sm p-2 outline-none text-zinc-200 placeholder-zinc-500">
                        <button onclick="addComment('You', document.getElementById('chat-input').value)" class="bg-blue-600 text-white rounded-[5px] px-4 py-2 hover:bg-blue-500 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                        </button>
                    </div>
                </div>
            </aside>
        </div>
    </div>

<script>
    // --- CHAT LOGIC (CLEAN UI UPDATE) ---
    const users = [
        { name: "Ali_CS", color: "bg-blue-500" },
        { name: "Zain_99", color: "bg-emerald-500" },
        { name: "Sarah_Design", color: "bg-purple-500" },
        { name: "King_Dev", color: "bg-orange-500" },
        { name: "Eman_Art", color: "bg-pink-500" }
    ];
    const msgs = ["Bro design is smooth 🚀", "Zabardast kaam hai!", "Stream quality is OP", "Scrollbar is working perfectly", "Nice rounded corners 👌", "Full support!"];

    function addComment(userName, text, isMe = false) {
        if(!text) return;
        
        const feed = document.getElementById('chat-feed');
        const div = document.createElement('div');
        div.className = "comment-enter flex flex-col";
        
        let userColor = isMe ? "bg-red-500" : users.find(u => u.name === userName)?.color || "bg-zinc-600";

        // Clean Comment Layout
        div.innerHTML = `
            <div class="flex items-start gap-2.5 group">
                <div class="w-6 h-6 rounded-full flex-shrink-0 flex items-center justify-center text-[10px] font-bold text-white shadow-sm ${userColor}">
                    ${userName[0]}
                </div>
                <div class="flex-1 pb-1 border-b border-zinc-800/50 group-hover:border-zinc-700 transition-colors">
                    <div class="flex items-center gap-2">
                        <span class="text-[12px] font-bold text-zinc-300">${userName}</span>
                        <span class="text-[9px] text-zinc-600 font-medium">Just now</span>
                    </div>
                    <p class="text-[13px] text-zinc-100 mt-0.5 leading-snug break-words">${text}</p>
                </div>
            </div>
        `;
        
        feed.appendChild(div);
        
        // Auto scroll to bottom
        setTimeout(() => {
            feed.scrollTo({ top: feed.scrollHeight, behavior: 'smooth' });
        }, 50);

        document.getElementById('chat-input').value = "";
    }

    // Auto Chat Generation
    let chatInterval = setInterval(() => {
        const randomUser = users[Math.floor(Math.random() * users.length)].name;
        const randomMsg = msgs[Math.floor(Math.random() * msgs.length)];
        addComment(randomUser, randomMsg);
    }, 2500);


    // --- INTERACTIONS ---
    function handleLike(btn) {
        // Button Pop Animation
        gsap.to(btn, { scale: 1.2, duration: 0.1, yoyo: true, repeat: 1 });
        
        const container = btn.closest('.stream-item');
        
        // Floating Heart Animation
        const icon = document.createElementNS("http://www.w3.org/2000/svg", "svg");
        icon.setAttribute("viewBox", "0 0 24 24");
        icon.classList.add("heart-svg");
        icon.style.width = "45px";
        icon.style.height = "45px";
        icon.style.right = "80px";
        icon.style.bottom = "180px";
        icon.innerHTML = `<path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>`;
        
        container.appendChild(icon);

        gsap.to(icon, {
            y: -500,
            x: `random(-100, 100)`,
            opacity: 0,
            rotation: `random(-30, 30)`,
            scale: `random(0.8, 1.5)`,
            duration: 2.5,
            ease: "power2.out",
            onComplete: () => icon.remove()
        });

        // Add System alert to chat
        addComment("System", "❤️ Loved the stream!", true);
    }

    // Enter Key Support
    document.getElementById('chat-input').addEventListener('keypress', (e) => {
        if(e.key === 'Enter') addComment('You', e.target.value, true);
    });

    // Initial Comments
    window.onload = () => {
        addComment("Ali_CS", "Hello guys!");
        addComment("Sarah_Design", "Waiting for the stream to start properly.");
    };
</script>
</body>
</html>