<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connect | Professional Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --brand-blue: #0062ff;
            --brand-dark: #0f172a;
        }
        body { 
            font-family: 'Plus Jakarta Sans', sans-serif; 
            background-color: #ffffff; 
            overflow-x: hidden;
        }
        /* Strict 5px Border Radius */
        .rounded-custom { border-radius: 5px !important; }
        
        .input-group input {
            border: 1.5px solid #e2e8f0;
            transition: all 0.2s ease;
        }
        .input-group input:focus {
            border-color: var(--brand-blue);
            outline: none;
            background: #f8faff;
        }
        /* Split Screen logic */
        .auth-grid {
            display: grid;
            grid-template-columns: 1.2fr 1fr;
            min-height: 100vh;
        }
        @media (max-width: 1024px) {
            .auth-grid { grid-template-columns: 1fr; }
            .visual-side { display: none; }
        }
        .visual-side {
            background: var(--brand-dark);
            position: relative;
            overflow: hidden;
        }
        .hidden-section { display: none; opacity: 0; }
    </style>
</head>
<body>

<div id="smooth-wrapper">
    <div id="smooth-content" class="auth-grid">
        
        <div class="visual-side flex items-center justify-center p-12 text-white">
            <div class="relative z-10 max-w-lg">
                <div class="mb-8 h-12 w-12 bg-blue-600 rounded-custom"></div>
                <h1 class="text-5xl font-extrabold mb-6 leading-tight">Connect with the <span class="text-blue-500">global</span> community.</h1>
                <p class="text-slate-400 text-lg leading-relaxed">Experience a faster, more secure way to stay in touch. Built for creators and professionals alike.</p>
            </div>
            <div id="hero-shape" class="absolute bottom-[-10%] right-[-10%] w-96 h-96 bg-blue-600 opacity-20 rounded-full blur-3xl"></div>
        </div>

        <div class="flex flex-col justify-center items-center p-8 bg-white">
            <div id="form-container" class="w-full max-w-md">
                
                <div id="login-section" class="auth-section">
                    <h2 class="text-3xl font-bold text-slate-900 mb-2">Welcome Back</h2>
                    <p class="text-slate-500 mb-8">Please enter your details to sign in.</p>
                    
                    <div class="space-y-4">
                        <div class="input-group">
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Email</label>
                            <input type="email" placeholder="name@company.com" class="w-full p-3 rounded-custom">
                        </div>
                        <div class="input-group">
                            <div class="flex justify-between">
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Password</label>
                                <button onclick="toggleSection('forgot')" class="text-sm text-blue-600 font-medium">Forgot?</button>
                            </div>
                            <input type="password" placeholder="••••••••" class="w-full p-3 rounded-custom">
                        </div>
                        <button class="anime-btn w-full bg-[#0062ff] text-white font-bold py-3 rounded-custom mt-4">Sign In</button>
                    </div>
                    <p class="mt-8 text-center text-slate-600 text-sm">
                        Don't have an account? <button onclick="toggleSection('register')" class="text-blue-600 font-bold">Sign up for free</button>
                    </p>
                </div>

                <div id="register-section" class="auth-section hidden-section">
                    <h2 class="text-3xl font-bold text-slate-900 mb-2">Create Account</h2>
                    <p class="text-slate-500 mb-6">Join thousands of users worldwide.</p>
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <input type="text" placeholder="First Name" class="p-3 border rounded-custom">
                        <input type="text" placeholder="Last Name" class="p-3 border rounded-custom">
                    </div>
                    <input type="email" placeholder="Work Email" class="w-full p-3 border rounded-custom mb-4">
                    <input type="password" placeholder="Create Password" class="w-full p-3 border rounded-custom mb-6">
                    <button class="w-full bg-slate-900 text-white font-bold py-3 rounded-custom">Register</button>
                    <button onclick="toggleSection('login')" class="w-full mt-4 text-slate-500 text-sm font-medium">Back to Login</button>
                </div>

                <div id="forgot-section" class="auth-section hidden-section">
                    <h2 class="text-3xl font-bold text-slate-900 mb-2">Reset Password</h2>
                    <p class="text-slate-500 mb-6">Enter your email and we'll send a recovery link.</p>
                    <input type="email" placeholder="Email Address" class="w-full p-3 border rounded-custom mb-6">
                    <button class="w-full bg-blue-600 text-white font-bold py-3 rounded-custom">Send Link</button>
                    <button onclick="toggleSection('login')" class="w-full mt-4 text-slate-500 text-sm font-medium">Wait, I remember it!</button>
                </div>

            </div>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/animejs/3.2.1/anime.min.js"></script>
<script src="https://cdn.jsdelivr.net/gh/studio-freight/lenis@1.0.19/bundled/lenis.min.js"></script>

<script>
    // 1. Lenis Smooth Scroll
    const lenis = new Lenis();
    function raf(time) { lenis.raf(time); requestAnimationFrame(raf); }
    requestAnimationFrame(raf);

    // 2. GSAP Content Switcher
    function toggleSection(targetId) {
        const sections = document.querySelectorAll('.auth-section');
        const current = Array.from(sections).find(s => s.style.display !== 'none' && !s.classList.contains('hidden-section'));
        const next = document.getElementById(`${targetId}-section`);

        gsap.to(current, {
            opacity: 0,
            x: -20,
            duration: 0.3,
            onComplete: () => {
                current.style.display = 'none';
                current.classList.add('hidden-section');
                
                next.style.display = 'block';
                gsap.fromTo(next, 
                    { opacity: 0, x: 20 }, 
                    { opacity: 1, x: 0, duration: 0.4, ease: "back.out(1.7)" }
                );
            }
        });
    }

    // 3. Anime.js for Micro-interactions (Button pulse)
    document.querySelectorAll('button').forEach(btn => {
        btn.addEventListener('mousedown', () => {
            anime({
                targets: btn,
                scale: 0.96,
                duration: 100
            });
        });
        btn.addEventListener('mouseup', () => {
            anime({
                targets: btn,
                scale: 1,
                duration: 100
            });
        });
    });

    // 4. Background Animation for the Visual Side
    gsap.to("#hero-shape", {
        duration: 8,
        x: 100,
        y: -50,
        repeat: -1,
        yoyo: true,
        ease: "sine.inOut"
    });
</script>

</body>
</html>