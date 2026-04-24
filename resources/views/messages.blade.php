<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Messages | Studio Pro</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap" rel="stylesheet">
    <style>
        * { border-radius: 5px !important; font-family: 'Inter', sans-serif; }
        body { background-color: #f0f2f5; color: #1c1e21; overflow: hidden; }
        
        .main-content {  height: 100vh; }
        
        /* Message Bubbles */
        .msg-inbox { background: #fff; border-right: 1px solid #dddfe2; }
        .msg-bubble-me { background: #0084ff; color: #white; border-bottom-right-radius: 2px !important; }
        .msg-bubble-them { background: #e4e6eb; color: #050505; border-bottom-left-radius: 2px !important; }
        
        /* Chat List Scroll */
        .custom-scroll::-webkit-scrollbar { width: 4px; }
        .custom-scroll::-webkit-scrollbar-thumb { background: #bcc0c4; border-radius: 10px; }

        .chat-item { transition: background 0.2s; cursor: pointer; }
        .chat-item.active { background: #e7f3ff; }
        .chat-item:hover:not(.active) { background: #f2f2f2; }

        /* Responsive Logic */
        @media (max-width: 768px) {
            .msg-inbox { width: 100%; position: absolute; z-index: 20; height: 100%; transition: transform 0.3s ease; }
            .msg-inbox.hidden-mobile { transform: translateX(-100%); }
            .chat-view { width: 100%; }
        }
    </style>
</head>
<body class="flex">

    

    <div class="flex-1 flex flex-col h-screen overflow-hidden">
       

        <div class="main-content flex h-full overflow-hidden">
            
            <aside id="inbox-panel" class="msg-inbox w-full md:w-[360px] flex flex-col flex-shrink-0">
                <div class="p-4 border-b border-gray-200">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-2xl font-extrabold tracking-tight">Messages</h2>
                        <button class="p-2 hover:bg-gray-100 rounded-full">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" stroke-width="2"></path></svg>
                        </button>
                    </div>
                    <div class="relative">
                        <input type="text" placeholder="Search Messenger" class="w-full bg-gray-100 border-none p-2 pl-8 text-sm outline-none">
                        <svg class="w-4 h-4 absolute left-2 top-2.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" stroke-width="2"></path></svg>
                    </div>
                </div>

                <div class="flex-1 overflow-y-auto custom-scroll" id="chat-list">
                    <div onclick="openChat('InkByHand Store', this)" class="chat-item active p-3 flex items-center gap-3 mx-2 mt-2">
                        <div class="relative">
                            <div class="w-12 h-12 bg-blue-500 rounded-full flex items-center justify-center text-white font-bold">IB</div>
                            <div class="absolute bottom-0 right-0 w-3 h-3 bg-green-500 border-2 border-white rounded-full"></div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex justify-between items-baseline">
                                <h4 class="text-sm font-bold truncate">InkByHand Store</h4>
                                <span class="text-[10px] text-gray-500">10m</span>
                            </div>
                            <p class="text-xs text-gray-500 truncate font-semibold">Bhai, calligraphy wall art ka rate kya hai?</p>
                        </div>
                    </div>

                    <div onclick="openChat('Ahmad Javeed', this)" class="chat-item p-3 flex items-center gap-3 mx-2">
                        <div class="w-12 h-12 bg-gray-300 rounded-full flex items-center justify-center text-gray-600 font-bold">AJ</div>
                        <div class="flex-1 min-w-0">
                            <div class="flex justify-between items-baseline">
                                <h4 class="text-sm font-bold truncate">Ahmad Javeed</h4>
                                <span class="text-[10px] text-gray-500">2h</span>
                            </div>
                            <p class="text-xs text-gray-500 truncate">Payment sent for the Viking Sword.</p>
                        </div>
                    </div>
                </div>
            </aside>

            <section class="chat-view flex-1 bg-white flex flex-col relative">
                
                <header class="h-[72px] border-b border-gray-200 px-4 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <button onclick="toggleMobileInbox()" class="md:hidden p-2">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M15 19l-7-7 7-7" stroke-width="2"></path></svg>
                        </button>
                        <div>
                            <h3 id="active-user-name" class="font-bold text-sm">InkByHand Store</h3>
                            <p class="text-[10px] text-green-500 font-bold uppercase">Active Now</p>
                        </div>
                    </div>
                    <div class="flex gap-4 text-blue-600">
                        <button class="hover:bg-gray-100 p-2"><svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 2V3z"></path></svg></button>
                        <button class="hover:bg-gray-100 p-2"><svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M10 12a2 2 0 100-4 2 2 0 000 4z"></path><path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"></path></svg></button>
                    </div>
                </header>

                <div class="flex-1 overflow-y-auto p-4 space-y-4 custom-scroll bg-gray-50" id="message-container">
                    <div class="flex items-end gap-2 max-w-[80%]">
                        <div class="w-7 h-7 bg-blue-500 rounded-full flex-shrink-0"></div>
                        <div class="msg-bubble-them p-3 text-sm shadow-sm">
                            Assalam o Alaikum bro! Calligraphy store check kiya, zabardast kaam hai.
                        </div>
                    </div>

                    <div class="flex flex-row-reverse items-end gap-2">
                        <div class="msg-bubble-me p-3 text-sm text-white shadow-md">
                            Walaikum Assalam! Shukriya bhai. Kya cheez pasand aayi aapko?
                        </div>
                    </div>

                    <div class="flex items-end gap-2 max-w-[80%]">
                        <div class="w-7 h-7 bg-blue-500 rounded-full flex-shrink-0"></div>
                        <div class="msg-bubble-them p-3 text-sm shadow-sm">
                            Woh jo custom knife set hai, uska kya scene hai? Delivery mil jayegi Lahore mein?
                        </div>
                    </div>
                </div>

                <footer class="p-4 bg-white border-t border-gray-200">
                    <div class="flex items-center gap-3">
                        <button class="text-blue-600 hover:bg-gray-100 p-2"><svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path d="M10 18a8 8 0 100-16 8 8 0 000 16zM7 9a1 1 0 100-2 1 1 0 000 2zm7-1a1 1 0 11-2 0 1 1 0 012 0zm-7.535 5.596a1 1 0 001.415 0 2.978 2.978 0 014.24 0 1 1 0 001.415-1.414 4.978 4.978 0 00-7.07 0 1 1 0 000 1.414z"></path></svg></button>
                        <input type="text" id="main-input" placeholder="Aa" class="flex-1 bg-gray-100 border-none p-3 rounded-full outline-none text-sm focus:bg-gray-200 transition-all">
                        <button onclick="sendMessage()" class="text-blue-600 p-2"><svg class="w-6 h-6 transform rotate-90" fill="currentColor" viewBox="0 0 20 20"><path d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.429A1 1 0 009 15.571V11a1 1 0 112 0v4.571a1 1 0 00.725.962l5 1.428a1 1 0 001.17-1.408l-7-14z"></path></svg></button>
                    </div>
                </footer>
            </section>
        </div>
    </div>

<script>
    // GSAP for smooth entrance
    window.onload = () => {
        gsap.from(".chat-item", { opacity: 0, x: -20, stagger: 0.1, duration: 0.5 });
        gsap.from(".msg-bubble-them, .msg-bubble-me", { opacity: 0, scale: 0.8, stagger: 0.05, duration: 0.3 });
    }

    function openChat(name, el) {
        document.querySelectorAll('.chat-item').forEach(item => item.classList.remove('active'));
        el.classList.add('active');
        document.getElementById('active-user-name').innerText = name;
        
        // Animating chat switch
        gsap.from("#message-container", { opacity: 0, y: 10, duration: 0.3 });

        // Mobile handling
        if(window.innerWidth < 768) {
            toggleMobileInbox();
        }
    }

    function sendMessage() {
        const input = document.getElementById('main-input');
        const container = document.getElementById('message-container');
        
        if(input.value.trim() !== "") {
            const msg = `
                <div class="flex flex-row-reverse items-end gap-2 opacity-0 transform translate-y-4" id="new-msg">
                    <div class="msg-bubble-me p-3 text-sm text-white shadow-md">
                        ${input.value}
                    </div>
                </div>
            `;
            container.insertAdjacentHTML('beforeend', msg);
            
            // GSAP for new message
            gsap.to("#new-msg", { opacity: 1, y: 0, duration: 0.3 });
            document.getElementById('new-msg').removeAttribute('id');
            
            input.value = "";
            container.scrollTop = container.scrollHeight;
        }
    }

    function toggleMobileInbox() {
        const panel = document.getElementById('inbox-panel');
        panel.classList.toggle('hidden-mobile');
    }

    // Enter key support
    document.getElementById('main-input').addEventListener('keypress', (e) => {
        if(e.key === 'Enter') sendMessage();
    });
</script>
</body>
</html>