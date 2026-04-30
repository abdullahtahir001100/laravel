<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connect | Manage Users</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="{{ asset('app.css') }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background: var(--bg-main); color: var(--text-main); }
        .rounded-custom { border-radius: 5px !important; }
        .soft-card { background: var(--bg-card); backdrop-filter: blur(10px); border: 1px solid var(--border-main); box-shadow: 0 10px 30px rgba(15, 23, 42, 0.05); }
    </style>
</head>
<body class="h-screen overflow-hidden">
    <x-dashboard-header />

    <div class="flex pt-16 h-screen overflow-hidden">
        <x-dashboard-sidebar />

        <main class="flex-1 h-full overflow-y-auto p-4 md:p-8 scrollbar-hide">
            <div class="max-w-7xl mx-auto">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
                    <div>
                        <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">User Management</h1>
                        <p class="text-slate-500 mt-2">Manage accounts, permissions, and user data.</p>
                    </div>
                </div>

                @if(session('success'))
                <div class="mb-6 p-4 bg-emerald-50 border border-emerald-100 text-emerald-700 rounded-custom text-sm font-bold animate-pulse">
                    {{ session('success') }}
                </div>
                @endif

                @if(session('error'))
                <div class="mb-6 p-4 bg-red-50 border border-red-100 text-red-700 rounded-custom text-sm font-bold">
                    {{ session('error') }}
                </div>
                @endif

                <div class="soft-card overflow-hidden rounded-custom">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-50 border-b border-slate-100">
                                    <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-widest">User</th>
                                    <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-widest">Username</th>
                                    <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-widest">Email</th>
                                    <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-widest">Joined</th>
                                    <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-widest text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach($users as $user)
                                <tr class="hover:bg-slate-50/50 transition-colors user-row opacity-0">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <img src="{{ $user->avatar_path ? asset('storage/' . $user->avatar_path) : 'https://api.dicebear.com/7.x/avataaars/svg?seed=' . urlencode($user->display_name) }}" class="w-10 h-10 rounded-custom object-cover bg-slate-100 border border-slate-200" alt="">
                                            <div>
                                                <p class="text-sm font-bold text-slate-900">{{ $user->display_name }}</p>
                                                <p class="text-xs text-slate-500">{{ $user->first_name }} {{ $user->last_name }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-slate-600 font-medium">@ {{ $user->username }}</td>
                                    <td class="px-6 py-4 text-sm text-slate-600">{{ $user->email }}</td>
                                    <td class="px-6 py-4 text-sm text-slate-500">{{ $user->created_at->format('M d, Y') }}</td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="flex items-center justify-end gap-2">
                                            <a href="{{ route('admin.users.edit', $user) }}" class="p-2 text-blue-600 hover:bg-blue-50 rounded-custom transition-colors">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                            </a>
                                            <form action="{{ route('admin.users.destroy', $user) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this user? This action cannot be undone.')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="p-2 text-red-500 hover:bg-red-50 rounded-custom transition-colors">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="mt-8">
                    {{ $users->links() }}
                </div>
            </div>
        </main>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            gsap.to('.user-row', { 
                opacity: 1, 
                x: 0, 
                stagger: 0.05, 
                duration: 0.6, 
                ease: "power2.out" 
            });
        });
    </script>
    <script src="{{ asset('app.js') }}"></script>
</body>
</html>
