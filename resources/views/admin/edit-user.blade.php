<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connect | Edit User</title>
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
            <div class="max-w-2xl mx-auto">
                <div class="mb-8 flex items-center gap-4">
                    <a href="{{ route('admin.users') }}" class="p-2 hover:bg-slate-100 rounded-custom transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    </a>
                    <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Edit User</h1>
                </div>

                <div class="soft-card p-8 rounded-custom opacity-0" id="edit-form-card">
                    <form action="{{ route('admin.users.update', $user) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-2 gap-6 mb-6">
                            <div>
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">First Name</label>
                                <input type="text" name="first_name" value="{{ old('first_name', $user->first_name) }}" class="w-full bg-slate-50 border border-slate-100 p-3 rounded-custom outline-none focus:border-blue-400 text-sm font-semibold">
                                @error('first_name') <p class="text-red-500 text-[10px] mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Last Name</label>
                                <input type="text" name="last_name" value="{{ old('last_name', $user->last_name) }}" class="w-full bg-slate-50 border border-slate-100 p-3 rounded-custom outline-none focus:border-blue-400 text-sm font-semibold">
                                @error('last_name') <p class="text-red-500 text-[10px] mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div class="mb-6">
                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Display Name</label>
                            <input type="text" name="display_name" value="{{ old('display_name', $user->display_name) }}" class="w-full bg-slate-50 border border-slate-100 p-3 rounded-custom outline-none focus:border-blue-400 text-sm font-semibold">
                            @error('display_name') <p class="text-red-500 text-[10px] mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="mb-6">
                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Username</label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm font-bold">@</span>
                                <input type="text" name="username" value="{{ old('username', $user->username) }}" class="w-full bg-slate-50 border border-slate-100 pl-8 pr-3 py-3 rounded-custom outline-none focus:border-blue-400 text-sm font-semibold">
                            </div>
                            @error('username') <p class="text-red-500 text-[10px] mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="mb-10">
                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Email Address</label>
                            <input type="email" name="email" value="{{ old('email', $user->email) }}" class="w-full bg-slate-50 border border-slate-100 p-3 rounded-custom outline-none focus:border-blue-400 text-sm font-semibold">
                            @error('email') <p class="text-red-500 text-[10px] mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="flex items-center gap-4">
                            <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-custom transition-colors shadow-lg shadow-blue-200">Save Changes</button>
                            <a href="{{ route('admin.users') }}" class="px-6 py-3 border border-slate-200 text-slate-500 font-bold rounded-custom hover:bg-slate-50 transition-colors">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            gsap.to('#edit-form-card', { opacity: 1, y: 0, duration: 0.8, ease: "power3.out" });
        });
    </script>
    <script src="{{ asset('app.js') }}"></script>
</body>
</html>
