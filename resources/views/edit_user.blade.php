<!DOCTYPE html>
<html lang="en" class="bg-black text-white">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Identity | System.Settings</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/animejs/3.2.1/anime.min.js"></script>
    
    <style>
        input, textarea {
            background: transparent !important;
            border: 1px solid rgba(255, 255, 255, 0.1) !important;
            outline: none !important;
            border-radius: 0 !important;
        }
        input:focus {
            border-color: #fff !important;
        }
        .border-thin { border: 1px solid rgba(255, 255, 255, 0.1); }
    </style>
</head>
<body class="antialiased min-h-screen flex items-center justify-center p-6">

    <main id="edit-box" class="w-full max-w-2xl border-thin opacity-0 scale-95">
        <div class="p-6 border-b border-white/10 flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-light uppercase tracking-tighter">Edit Identity</h1>
                <p class="text-[9px] text-zinc-500 uppercase tracking-[0.3em] mt-1">Update system credentials</p>
            </div>
            <a href="/user" class="text-[10px] uppercase tracking-widest hover:line-through text-zinc-400">Cancel</a>
        </div>

        <form class="p-8 space-y-8" action="/user/update/{{ $myuser->id }}" method="POST" enctype="multipart/form-data">
            @csrf
           
            <div class="flex items-center gap-8">
                <div class="relative group">
                    <div id="avatar-preview" class="w-24 h-24 bg-zinc-900 border-thin overflow-hidden">
                        <img src="https://api.dicebear.com/7.x/initials/svg?seed=Sheri" class="w-full h-full object-cover grayscale" id="profile-img">
                    </div>
                    <input type="file" id="avatar-upload" class="hidden" accept="image/*">
                    <label for="avatar-upload" class="absolute inset-0 bg-black/60 flex items-center justify-center opacity-0 group-hover:opacity-100 cursor-pointer transition-opacity text-[8px] uppercase tracking-widest font-bold">
                        Update
                    </label>
                </div>
                <div>
                    <h3 class="text-xs uppercase tracking-widest mb-1">Avatar Protocol</h3>
                    <p class="text-[9px] text-zinc-600 uppercase">JPG, PNG OR WEBP. MAX 2MB.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label class="block text-[9px] uppercase tracking-widest text-zinc-500">Public Name</label>
                    <input type="text" class="w-full p-4 text-xs" name="name" value="{{ $myuser->name }}">
                </div>
                <div class="space-y-2">
                    <label class="block text-[9px] uppercase tracking-widest text-zinc-500">Email Address</label>
                    <input type="email" class="w-full p-4 text-xs" name="email" value="{{ $myuser->email }}">
                </div>
            </div>

            <div class="border-t border-white/5 pt-8">
                <h3 class="text-[10px] uppercase tracking-[0.3em] text-zinc-600 mb-6">Security Update (Optional)</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="block text-[9px] uppercase tracking-widest text-zinc-500">New Access Key</label>
                        <input type="password" class="w-full p-4 text-xs" name="password" placeholder="{{$myuser->password}}">
                    </div>
                    <div class="space-y-2">
                        <label class="block text-[9px] uppercase tracking-widest text-zinc-500">Confirm Key</label>
                        <input type="password" class="w-full p-4 text-xs" name="password_confirmation" placeholder="••••••••">
                    </div>
                </div>
            </div>

            <div class="pt-4">
                <button type="submit" id="update-btn" class="w-full bg-white text-black py-5 text-[10px] uppercase font-bold tracking-[0.5em] transition-all">
                    Update Protocol
                </button>
            </div>
        </form>

        <div class="p-4 border-t border-white/10 text-center bg-zinc-900/30">
            <p class="text-[8px] uppercase tracking-[0.4em] text-zinc-700">Last login: 18.04.2026 // IP: 192.168.1.1</p>
        </div>
    </main>

    <script>
        // GSAP Entrance
        window.addEventListener('DOMContentLoaded', () => {
            gsap.to("#edit-box", {
                opacity: 1,
                scale: 1,
                duration: 1,
                ease: "expo.out"
            });
        });

        // Image Preview Logic
        const avatarUpload = document.querySelector('#avatar-upload');
        const profileImg = document.querySelector('#profile-img');

        avatarUpload.addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    profileImg.src = e.target.result;
                    anime({
                        targets: '#avatar-preview',
                        scale: [0.9, 1],
                        opacity: [0.5, 1],
                        duration: 600,
                        easing: 'easeOutExpo'
                    });
                };
                reader.readAsDataURL(file);
            }
        });

        // Button Micro-interactions
        const updateBtn = document.querySelector('#update-btn');
        updateBtn.addEventListener('mouseenter', () => {
            anime({
                targets: updateBtn,
                letterSpacing: '0.7em',
                backgroundColor: '#f0f0f0',
                duration: 400
            });
        });

        updateBtn.addEventListener('mouseleave', () => {
            anime({
                targets: updateBtn,
                letterSpacing: '0.5em',
                backgroundColor: '#ffffff',
                duration: 400
            });
        });
    </script>
</body>
</html>