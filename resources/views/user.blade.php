<!DOCTYPE html>
<html lang="en" class="bg-black text-white">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Directory | System.Log</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    
    <style>
        .border-thin { border: 1px solid rgba(255, 255, 255, 0.1); }
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #333; }
        tr { transition: background 0.3s ease; }
        tr:hover { background: rgba(255, 255, 255, 0.03); }
    </style>
</head>
<body class="antialiased overflow-x-hidden custom-scrollbar">

    <nav class="flex justify-between items-center px-8 py-6 border-b border-white/10 uppercase tracking-widest text-[10px]">
        <div class="font-bold text-lg tracking-tighter">User_Database.sys</div>
        <div class="flex gap-6">
            <span class="text-zinc-500">Total Records: 04</span>
            <a href="/user/create" class="hover:line-through text-white">+ Add New User</a>
        </div>
    </nav>

    <main class="container mx-auto px-4 py-12 max-w-6xl">
        
        <div class="mb-12 stagger-item">
            <h1 class="text-5xl font-light uppercase tracking-tighter mb-2">Registry</h1>
            <p class="text-[10px] text-zinc-500 uppercase tracking-[0.3em]">Authorized Personnel Access Only</p>
        </div>

        <div class="border-thin overflow-hidden">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-white/10 bg-zinc-900/50">
                        <th class="p-6 text-[10px] uppercase tracking-widest text-zinc-400 font-bold">ID</th>
                        <th class="p-6 text-[10px] uppercase tracking-widest text-zinc-400 font-bold">Full Name</th>
                        <th class="p-6 text-[10px] uppercase tracking-widest text-zinc-400 font-bold">Email Address</th>
                        <th class="p-6 text-[10px] uppercase tracking-widest text-zinc-400 font-bold">Role</th>
                        <th class="p-6 text-[10px] uppercase tracking-widest text-zinc-400 font-bold">Status</th>
                        <th class="p-6 text-[10px] uppercase tracking-widest text-zinc-400 font-bold text-right">Actions</th>
                    </tr>
                </thead>
                <tbody id="user-table-body">
                    
                  @foreach($myusers as $myuser)
                    <tr class="border-b border-white/10 table-row opacity-0">
                        <td class="p-6 text-xs font-mono text-zinc-500">#{{ $myuser->id }}</td>
                        <td class="p-6 text-sm uppercase tracking-tight">{{ $myuser->name }}</td>
                        <td class="p-6 text-sm text-zinc-400 italic">{{ $myuser->email }}</td>
                        <td class="p-6 text-[10px] uppercase tracking-widest">User</td>
                        <td class="p-6">
                            <span class="text-[9px] border border-green-500/50 text-green-500 px-2 py-0.5 uppercase">Active</span>
                        </td>
                        <td class="p-6 text-right space-x-4 text-sm text-zinc-400 italic">
                            <button class="text-[10px] uppercase hover:line-through" onclick="window.location.href='/user/destroy/{{ $myuser->id }}'">Delete</button>

                            <button class="text-[10px] uppercase hover:line-through" onclick="window.location.href='/user/edit/{{ $myuser->id }}'">Edit</button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="flex justify-between items-center mt-8 text-[10px] uppercase tracking-widest text-zinc-500 stagger-item">
            <div>Showing 1 to 3 of 24 entries</div>
            <div class="flex gap-4">
                <button class="hover:text-white transition-colors"><- Previous</button>
                <button class="hover:text-white transition-colors">Next -></button>
            </div>
        </div>
    </main>

    <script>
        window.addEventListener('DOMContentLoaded', () => {
            const tl = gsap.timeline({ defaults: { ease: "power4.out" } });

            tl.from("nav", { y: -20, opacity: 0, duration: 1 })
              .from(".stagger-item", { 
                  y: 20, 
                  opacity: 0, 
                  stagger: 0.2, 
                  duration: 1 
              }, "-=0.5")
              .to(".table-row", { 
                  opacity: 1, 
                  x: 0, 
                  stagger: 0.1, 
                  duration: 0.8,
                  startAt: { x: -20 }
              }, "-=0.8");
        });
    </script>
</body>
</html> 