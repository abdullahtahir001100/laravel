<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Directory | SocialApp</title>
    <script src="https://cdn.tailwindcss.com"></script>
  <link href="{{ asset('app.css') }}" rel='stylesheet'>
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/animejs/3.2.1/anime.min.js"></script>
    
    <style>
        .rounded-custom { border-radius: 5px !important; }
        .no-shadow { box-shadow: none !important; }
        .hide-scrollbar::-webkit-scrollbar { display: none; }
        
        /* Transition utility */
        .view-container { display: none; opacity: 0; }
        .view-active { display: block; }
        
        /* Table Row Hover */
        tr:hover td { background-color: #f8fafc; }
        main{padding-top: 5rem !important;}
    </style>
</head>
<body class="bg-[#f0f2f5] flex">

  <x-dashboard-header />
   <x-dashboard-sidebar />
    <main class="flex-1 h-screen overflow-y-auto p-6 hide-scrollbar">
        
        <div class="max-w-6xl mx-auto mb-8">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div>
                    <h1 class="text-2xl font-bold text-slate-900">User Directory</h1>
                    <p class="text-slate-500 text-sm">Manage and connect with your community</p>
                </div>

                <div class="flex flex-wrap items-center gap-3">
                    <div class="flex bg-white border border-slate-200 p-1 rounded-custom">
                        <button onclick="switchView('grid')" class="view-btn p-2 text-slate-500 hover:text-blue-600 rounded-custom transition-colors" id="btn-grid">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                        </button>
                        <button onclick="switchView('table')" class="view-btn p-2 text-slate-500 hover:text-blue-600 rounded-custom transition-colors" id="btn-table">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path></svg>
                        </button>
                        <button onclick="switchView('line')" class="view-btn p-2 text-slate-500 hover:text-blue-600 rounded-custom transition-colors" id="btn-line">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M4 6h16M4 12h16M4 18h7"></path></svg>
                        </button>
                    </div>

                    <input type="text" id="userSearch" placeholder="Search users..." 
                        class="pl-4 pr-3 py-2 border border-slate-200 rounded-custom focus:ring-1 focus:ring-blue-500 outline-none text-sm bg-white w-64">
                </div>
            </div>
        </div>

        <div id="view-grid" class="view-container view-active max-w-6xl mx-auto">
            <div id="users-grid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4"></div>
        </div>

        <div id="view-table" class="view-container max-w-6xl mx-auto">
            <div class="bg-white border border-slate-200 rounded-custom overflow-hidden">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-slate-50 border-b border-slate-200">
                        <tr>
                            <th class="px-6 py-3 text-xs font-bold text-slate-500 uppercase">User</th>
                            <th class="px-6 py-3 text-xs font-bold text-slate-500 uppercase">Email</th>
                            <th class="px-6 py-3 text-xs font-bold text-slate-500 uppercase">Status</th>
                            <th class="px-6 py-3 text-xs font-bold text-slate-500 uppercase text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody id="users-table-body"></tbody>
                </table>
            </div>
        </div>

        <div id="view-line" class="view-container max-w-6xl mx-auto">
            <div id="users-line" class="space-y-3"></div>
        </div>

    </main>

    <script>
        const CSRF_TOKEN = '{{ csrf_token() }}';
        const searchInput = document.getElementById('userSearch');
        const usersGrid = document.getElementById('users-grid');
        const usersTableBody = document.getElementById('users-table-body');
        const usersLine = document.getElementById('users-line');
        let usersData = [];

        gsap.to('.view-active', { opacity: 1, duration: 0.5 });

        function switchView(viewType) {
            const allViews = document.querySelectorAll('.view-container');
            const targetView = document.getElementById(`view-${viewType}`);
            const buttons = document.querySelectorAll('.view-btn');

            // 1. Anime.js for button click feedback
            anime({
                targets: `#btn-${viewType}`,
                scale: [0.9, 1],
                duration: 400,
                easing: 'easeOutElastic(1, .6)'
            });

            // Update button styles
            buttons.forEach(btn => btn.classList.replace('bg-blue-50', 'text-slate-500'));
            buttons.forEach(btn => btn.classList.remove('text-blue-600', 'bg-blue-50'));
            document.getElementById(`btn-${viewType}`).classList.add('bg-blue-50', 'text-blue-600');

            // 2. GSAP for View Switching (Fade out current, Fade in next)
            const currentActive = document.querySelector('.view-active');
            
            if (currentActive === targetView) return;

            gsap.to(currentActive, {
                opacity: 0,
                y: 10,
                duration: 0.2,
                onComplete: () => {
                    currentActive.classList.remove('view-active');
                    targetView.classList.add('view-active');
                    gsap.fromTo(targetView, 
                        { opacity: 0, y: -10 }, 
                        { opacity: 1, y: 0, duration: 0.3, ease: "power2.out" }
                    );
                }
            });
        }

        async function loadUsers(query = '') {
            const response = await fetch('/api/users/search?q=' + encodeURIComponent(query), {
                headers: { Accept: 'application/json' }
            });

            if (!response.ok) return;
            const data = await response.json();
            usersData = data.users || [];
            renderUsers();
        }

        function avatarFor(user) {
            return user.avatarUrl || ('https://ui-avatars.com/api/?name=' + encodeURIComponent(user.displayName));
        }

        function renderUsers() {
            usersGrid.innerHTML = usersData.map((user) => `
                <div class="user-card bg-white border border-slate-200 p-4 rounded-custom flex items-center justify-between gap-4">
                    <a href="${user.profileUrl}" class="flex items-center gap-4 min-w-0">
                        <img src="${avatarFor(user)}" class="w-12 h-12 rounded-custom bg-slate-50">
                        <div class="truncate">
                            <h3 class="text-sm font-bold text-slate-900 truncate">${user.displayName}</h3>
                            <p class="text-xs text-slate-500">${user.email || ''}</p>
                        </div>
                    </a>
                    <button onclick="followUser(${user.id})" class="bg-slate-900 text-white px-3 py-1.5 rounded-custom text-xs font-medium hover:bg-blue-600 transition-colors">Follow</button>
                </div>
            `).join('');

            usersTableBody.innerHTML = usersData.map((user) => `
                <tr class="border-b border-slate-100 last:border-0 transition-colors">
                    <td class="px-6 py-4 flex items-center gap-3">
                        <img src="${avatarFor(user)}" class="w-8 h-8 rounded-custom">
                        <span class="text-sm font-semibold text-slate-900">${user.displayName}</span>
                    </td>
                    <td class="px-6 py-4 text-sm text-slate-600">${user.email || ''}</td>
                    <td class="px-6 py-4"><span class="px-2 py-1 text-[10px] font-bold rounded-custom bg-blue-50 text-blue-600">Active</span></td>
                    <td class="px-6 py-4 text-right space-x-2">
                        <a href="${user.profileUrl}" class="text-blue-600 font-bold text-xs">Profile</a>
                        <button onclick="followUser(${user.id})" class="text-slate-900 font-bold text-xs">Follow</button>
                    </td>
                </tr>
            `).join('');

            usersLine.innerHTML = usersData.map((user) => `
                <div class="bg-white border border-slate-200 p-3 rounded-custom flex items-center justify-between group hover:border-blue-400 transition-colors">
                    <a href="${user.profileUrl}" class="flex items-center gap-4">
                        <img src="${avatarFor(user)}" class="w-10 h-10 rounded-custom bg-slate-50">
                        <div>
                            <h3 class="text-sm font-bold text-slate-900">${user.displayName}</h3>
                            <p class="text-xs text-slate-500">${user.email || ''}</p>
                        </div>
                    </a>
                    <button onclick="followUser(${user.id})" class="bg-slate-900 text-white px-4 py-1.5 rounded-custom text-xs font-medium hover:bg-blue-600 transition-colors">Follow</button>
                </div>
            `).join('');
        }

        async function followUser(userId) {
            const response = await fetch('/api/users/' + userId + '/follow', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': CSRF_TOKEN,
                    Accept: 'application/json'
                }
            });

            if (response.ok) {
                alert('Follow request sent');
            }
        }

        searchInput?.addEventListener('input', (e) => {
            loadUsers(e.target.value || '');
        });

        document.getElementById('btn-grid').classList.add('bg-blue-50', 'text-blue-600');
        loadUsers('');
    </script>
  <script src="{{ asset('app.js') }}"></script>

</body>
</html>