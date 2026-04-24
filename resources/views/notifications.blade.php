<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifications | Connect Studio</title>
    <script src="https://cdn.tailwindcss.com"></script>
  <link href="{{ asset('app.css') }}" rel='stylesheet'>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <style>
        /* Strict Design Language: Zero Border Radius & No Shadows */
        * {
            border-radius: 0 !important;
            box-shadow: none !important;
        }

        body {
            background-color: #ffffff;
            color: #0f172a;
            font-family: 'Inter', sans-serif;
            overflow-x: hidden;
        }

        /* Custom Scroll for cleaner look */
        .hide-scroll::-webkit-scrollbar {
            display: none;
        }

        .notif-item {
            border-bottom: 1px solid #f1f5f9;
            display: flex; /* Force flex for JS rendering */
        }

        .notif-item:hover {
            background-color: #f8fafc;
        }

        .unread-marker {
            width: 8px;
            height: 8px;
            background-color: #2563eb;
            display: inline-block;
        }

        .filter-tab {
            position: relative;
            transition: all 0.3s;
            color: #64748b;
        }

        .filter-tab.active {
            color: #000;
            font-weight: 800;
        }

        .filter-tab.active::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 3px;
            background: #000;
        }

        @media (max-width: 640px) {
            .action-btns {
                width: 100%;
                margin-top: 10px;
                justify-content: flex-start;
            }
        }

        /* Header spacing adjustment */
        .main-content {
            padding-top: 5rem;
        }
    </style>
</head>

<body class="flex min-h-screen bg-white">

    <x-dashboard-sidebar />
        <x-dashboard-header />


    <div class="flex-1 flex flex-col h-screen overflow-hidden">
        

        <div class="main-content flex flex-col h-full overflow-hidden">
            <div class="border-b border-slate-100 bg-white">
                <div class="px-6 py-8 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                    <div>
                        <h1 class="text-3xl font-black uppercase tracking-tighter">Notifications</h1>
                        <p class="text-[10px] font-bold text-slate-400 tracking-widest uppercase mt-1">
                            Updates / Messages / Alerts
                        </p>
                    </div>

                    <div class="w-full md:w-96 relative">
                        <input type="text" id="notifSearch" placeholder="FILTER BY KEYWORD..."
                            class="w-full border-2 border-slate-900 px-4 py-3 text-xs font-bold tracking-widest uppercase outline-none focus:bg-slate-50 transition-all">
                    </div>
                </div>

                <div class="px-6 flex gap-6 overflow-x-auto hide-scroll border-t border-slate-100">
                    <button onclick="changeFilter('all', this)" class="filter-tab active py-4 text-[11px] uppercase tracking-widest whitespace-nowrap">All Feed</button>
                    <button onclick="changeFilter('message', this)" class="filter-tab py-4 text-[11px] uppercase tracking-widest whitespace-nowrap">Messages</button>
                    <button onclick="changeFilter('request', this)" class="filter-tab py-4 text-[11px] uppercase tracking-widest whitespace-nowrap">Follow Req</button>
                    <button onclick="changeFilter('comment', this)" class="filter-tab py-4 text-[11px] uppercase tracking-widest whitespace-nowrap">Comments</button>
                    <button onclick="changeFilter('report', this)" class="filter-tab py-4 text-[11px] uppercase tracking-widest whitespace-nowrap text-red-600">Reports</button>
                </div>
            </div>

            <main class="flex-1 overflow-y-auto p-4 md:p-10 bg-slate-50/30">
                <div class="max-w-full mx-auto border border-slate-100 bg-white" id="notifWrapper">
                    </div>
            </main>
        </div>
    </div>

    <script>
        const notifications = [
            { id: 1, type: 'request', user: 'Zain_Malik', text: 'sent you a follow request', time: '2m ago', isUnread: true },
            { id: 2, type: 'message', user: 'Admin_Support', text: 'replied to your ticket regarding "API Access"', time: '15m ago', isUnread: true },
            { id: 3, type: 'comment', user: 'Sara_Khan', text: 'commented on your "Sword Collection" post', time: '1h ago', isUnread: false },
            { id: 4, type: 'report', user: 'System_Bot', text: 'Alert: Login detected from a new device (Windows/Lahore)', time: '3h ago', isUnread: false },
            { id: 5, type: 'request', user: 'Hamza_Pro', text: 'wants to join your development team', time: 'Yesterday', isUnread: false },
            { id: 6, type: 'message', user: 'Customer_09', text: 'sent a query about Calligraphy custom orders', time: 'Yesterday', isUnread: false }
        ];

        function renderFeed(type = 'all') {
            const wrapper = document.getElementById('notifWrapper');
            
            // Kill existing animations to prevent opacity glitch
            gsap.killTweensOf(".notif-item");
            
            wrapper.innerHTML = '';

            const filtered = type === 'all' ? notifications : notifications.filter(n => n.type === type);

            if (filtered.length === 0) {
                wrapper.innerHTML = `<div class="p-20 text-center text-slate-300 font-bold uppercase text-xs tracking-widest">No ${type} notifications found.</div>`;
                return;
            }

            filtered.forEach(n => {
                const row = `
                    <div class="notif-item p-4 md:p-6 flex flex-wrap md:flex-nowrap items-center justify-between group" 
                         style="opacity: 0; transform: translateX(-20px);" data-type="${n.type}">
                        <div class="flex items-center gap-5">
                            <div class="relative">
                                <img src="https://api.dicebear.com/7.x/initials/svg?seed=${n.user}" class="w-12 h-12 bg-slate-900 border border-slate-200">
                                ${n.isUnread ? '<div class="absolute -top-1 -right-1 unread-marker border-2 border-white"></div>' : ''}
                            </div>
                            <div>
                                <h4 class="text-sm font-black uppercase tracking-tighter text-slate-900">${n.user}</h4>
                                <p class="text-xs text-slate-500 font-medium">${n.text}</p>
                                <p class="text-[9px] font-black text-slate-400 uppercase mt-2 tracking-widest">${n.time} // <span class="text-blue-600">${n.type}</span></p>
                            </div>
                        </div>

                        <div class="action-btns flex items-center gap-3 opacity-0 group-hover:opacity-100 transition-opacity">
                            ${n.type === 'request' ? `
                                <button class="bg-black text-white px-5 py-2 text-[10px] font-black uppercase hover:bg-slate-800 transition-all">Accept</button>
                                <button class="border border-slate-200 px-5 py-2 text-[10px] font-black uppercase hover:bg-red-50 hover:text-red-600 transition-all">Decline</button>
                            ` : `
                                <button class="bg-slate-100 px-5 py-2 text-[10px] font-black uppercase hover:bg-slate-200 transition-all">View</button>
                            `}
                            <button onclick="this.closest('.notif-item').remove()" class="p-2 text-slate-300 hover:text-red-500">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12" stroke-width="3"></path></svg>
                            </button>
                        </div>
                    </div>
                `;
                wrapper.insertAdjacentHTML('beforeend', row);
            });

            // Fresh Animation with Glitch Fix
            gsap.to(".notif-item", {
                opacity: 1,
                x: 0,
                duration: 0.4,
                stagger: 0.05,
                ease: "power2.out",
                overwrite: "auto",
                clearProps: "opacity,transform" // Removes inline styles after animation
            });
        }

        function changeFilter(type, btn) {
            document.querySelectorAll('.filter-tab').forEach(t => t.classList.remove('active'));
            btn.classList.add('active');
            renderFeed(type);
        }

        // Search Interaction
        document.getElementById('notifSearch').addEventListener('input', (e) => {
            const val = e.target.value.toLowerCase();
            document.querySelectorAll('.notif-item').forEach(item => {
                const content = item.innerText.toLowerCase();
                item.style.display = content.includes(val) ? 'flex' : 'none';
            });
        });

        // Initialize on load
        window.onload = () => renderFeed('all');
    </script>
  <script src="{{ asset('app.js') }}"></script>

</body>
</html>