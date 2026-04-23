<!DOCTYPE html>
<html lang="en" class="bg-black text-white">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Authentication | System.Access</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/animejs/3.2.1/anime.min.js"></script>
    
    <style>
        input {
            background: transparent !important;
            border: 1px solid rgba(255, 255, 255, 0.1) !important;
            outline: none !important;
            border-radius: 0 !important;
            transition: border-color 0.3s ease;
        }
        input:focus {
            border-color: #fff !important;
        }
        .tab-btn.active {
            background: white;
            color: black;
        }
        .form-content {
            display: none;
        }
        .form-content.active {
            display: block;
        }
    </style>
</head>
<body class="antialiased flex items-center justify-center min-h-screen p-4">

    <div id="auth-box" class="w-full max-w-md border border-white/10 opacity-0 scale-95">
        
        <div class="flex border-b border-white/10">
            <button onclick="switchTab('login')" id="tab-login" class="tab-btn active flex-1 py-4 text-[10px] uppercase tracking-[0.3em] font-bold border-r border-white/10 transition-all">
                Login
            </button>
            <button onclick="switchTab('register')" id="tab-register" class="tab-btn flex-1 py-4 text-[10px] uppercase tracking-[0.3em] font-bold border-r border-white/10 transition-all">
                Register
            </button>
            <button onclick="switchTab('forget')" id="tab-forget" class="tab-btn flex-1 py-4 text-[10px] uppercase tracking-[0.3em] font-bold transition-all">
                Reset
            </button>
        </div>

        <div class="p-8">
            <div id="form-login" class="form-content active">
                <h2 class="text-2xl font-light uppercase tracking-tighter mb-8">System Login</h2>
                <form class="space-y-6" action="/user/login" method="POST">
                     @csrf
                     <div>
                        <label class="block text-[9px] uppercase tracking-widest text-zinc-500 mb-2">Email Address</label>
                        <input type="email" name="email" class="w-full p-4 text-xs" placeholder="EMAIL@DOMAIN.COM">
                    </div>
                    <div>
                        <label class="block text-[9px] uppercase tracking-widest text-zinc-500 mb-2">Access Key</label>
                        <input type="password" name="password" class="w-full p-4 text-xs" placeholder="••••••••">
                    </div>
                    <button type="submit" class="auth-action w-full bg-white text-black py-4 text-[10px] uppercase font-bold tracking-[0.4em] mt-4">
                        Authorize
                    </button>
                </form>
            </div>

            <div id="form-register" class="form-content">
                <h2 class="text-2xl font-light uppercase tracking-tighter mb-8">Create Identity</h2>
                <form class="space-y-4" action="/api/user/" method="POST">
                    <div>
                        <label class="block text-[9px] uppercase tracking-widest text-zinc-500 mb-2">Full Name</label>
                        <input type="text" class="w-full p-4 text-xs" name='name' placeholder="USER NAME">
                    </div>
                    <div>
                        <label class="block text-[9px] uppercase tracking-widest text-zinc-500 mb-2">Email Address</label>
                        <input type="email" class="w-full p-4 text-xs" name='email' placeholder="EMAIL@DOMAIN.COM">
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[9px] uppercase tracking-widest text-zinc-500 mb-2">Key</label>
                            <input type="password" class="w-full p-4 text-xs" name='password' placeholder="••••••••">
                        </div>
                        <div>
                            <label class="block text-[9px] uppercase tracking-widest text-zinc-500 mb-2">Confirm</label>
                            <input type="password" class="w-full p-4 text-xs" name='password_confirmation' placeholder="••••••••">
                        </div>
                    </div>
                    <button type="submit" class="auth-action w-full bg-white text-black py-4 text-[10px] uppercase font-bold tracking-[0.4em] mt-6">
                        Register
                    </button>
                </form>
            </div>

            <div id="form-forget" class="form-content">
                <h2 class="text-2xl font-light uppercase tracking-tighter mb-8">Reset Protocol</h2>
                <p class="text-[10px] text-zinc-500 uppercase mb-6 tracking-widest leading-loose">Enter your identity to receive a temporary access link.</p>
                <form class="space-y-6" action="/user/reset" method="POST">
                    <div>
                        <label class="block text-[9px] uppercase tracking-widest text-zinc-500 mb-2">Registered Email</label>
                        <input type="email" name="email" class="w-full p-4 text-xs" placeholder="RECOVERY@SYSTEM.COM">
                    </div>
                     
                     <div>
                        <label class="block text-[9px] uppercase tracking-widest text-zinc-500 mb-2">New Access Key</label>
                        <input type="password" name="password" class="w-full p-4 text-xs" placeholder="••••••••">
                    <button type="submit" class="auth-action w-full bg-white text-black py-4 text-[10px] uppercase font-bold tracking-[0.4em]">
                        Send Reset Link
                    </button>
                </form>
            </div>
        </div>

        <div class="p-4 border-t border-white/10 text-center">
            <p class="text-[8px] uppercase tracking-[0.5em] text-zinc-600">Secure Terminal // v2.4.0</p>
        </div>
    </div>

    <script>
        // Initial GSAP Reveal
        window.addEventListener('DOMContentLoaded', () => {
            gsap.to("#auth-box", {
                opacity: 1,
                scale: 1,
                duration: 1.2,
                ease: "expo.out"
            });
        });

        // Tab Switching Logic with Anime.js
        function switchTab(type) {
            // Remove active class from all buttons
            document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
            // Add to clicked
            document.getElementById('tab-' + type).classList.add('active');

            // Hide all forms
            const currentForm = document.querySelector('.form-content.active');
            const targetForm = document.getElementById('form-' + type);

            if (currentForm === targetForm) return;

            // Animate Out Current
            anime({
                targets: currentForm,
                opacity: 0,
                translateX: -20,
                duration: 300,
                easing: 'easeInQuad',
                complete: () => {
                    currentForm.classList.remove('active');
                    targetForm.classList.add('active');
                    
                    // Animate In Target
                    anime({
                        targets: targetForm,
                        opacity: [0, 1],
                        translateX: [20, 0],
                        duration: 500,
                        easing: 'easeOutExpo'
                    });
                }
            });
        }

        // Button Micro-interactions (Anime.js)
        document.querySelectorAll('.auth-action').forEach(btn => {
            btn.addEventListener('mouseenter', () => {
                anime({
                    targets: btn,
                    letterSpacing: ['0.4em', '0.6em'],
                    backgroundColor: '#e5e5e5',
                    duration: 300,
                    easing: 'easeOutQuad'
                });
            });

            btn.addEventListener('mouseleave', () => {
                anime({
                    targets: btn,
                    letterSpacing: ['0.6em', '0.4em'],
                    backgroundColor: '#ffffff',
                    duration: 300,
                    easing: 'easeOutQuad'
                });
            });
        });
    </script>
</body>
</html>