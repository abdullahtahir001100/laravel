<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connect | Auth</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;800&display=swap" rel="stylesheet">
    <style>
        :root { --brand-blue: #0062ff; --brand-dark: #0f172a; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #ffffff; overflow-x: hidden; }
        .rounded-custom { border-radius: 5px !important; }
        .auth-grid { display: grid; grid-template-columns: 1.2fr 1fr; min-height: 100vh; }
        @media (max-width: 1024px) { .auth-grid { grid-template-columns: 1fr; } .visual-side { display: none; } }
        .visual-side { background: var(--brand-dark); position: relative; overflow: hidden; }
        .hidden-section { display: none; opacity: 0; }
        .error-text { color: #ef4444; font-size: 0.75rem; margin-top: 4px; }
    </style>
</head>
<body>

<div id="smooth-wrapper">
    <div id="smooth-content" class="auth-grid">
        
        <div class="visual-side flex items-center justify-center p-12 text-white">
            <div class="relative z-10 max-w-lg">
                <div class="mb-8 h-12 w-12 bg-blue-600 rounded-custom"></div>
                <h1 class="text-5xl font-extrabold mb-6 leading-tight">Connect with the <span class="text-blue-500">global</span> community.</h1>
                <p class="text-slate-400 text-lg leading-relaxed">Experience a faster, more secure way to stay in touch.</p>
            </div>
            <div id="hero-shape" class="absolute bottom-[-10%] right-[-10%] w-96 h-96 bg-blue-600 opacity-20 rounded-full blur-3xl"></div>
        </div>

        <div class="flex flex-col justify-center items-center p-8 bg-white">
            <div id="form-container" class="w-full max-w-md">
                
                <div id="login-section" class="auth-section">
                    <h2 class="text-3xl font-bold text-slate-900 mb-2">Welcome Back</h2>
                    <p class="text-slate-500 mb-8">Please enter details to sign in.</p>
                    
                    <form action="{{ route('login.post') }}" method="POST" class="space-y-4">
                        @csrf
                        <div class="input-group">
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Email</label>
                            <input type="email" name="email" value="{{ old('email') }}" class="w-full p-3 border rounded-custom outline-none focus:border-blue-500" required>
                            @error('email') <p class="error-text">{{ $message }}</p> @enderror
                        </div>
                        <div class="input-group">
                            <div class="flex justify-between">
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Password</label>
                                <button type="button" onclick="toggleSection('forgot')" class="text-sm text-blue-600 font-medium">Forgot?</button>
                            </div>
                            <input type="password" name="password" class="w-full p-3 border rounded-custom outline-none focus:border-blue-500" required>
                        </div>
                        <button type="submit" class="w-full bg-[#0062ff] text-white font-bold py-3 rounded-custom mt-4">Sign In</button>
                    </form>
                    <p class="mt-8 text-center text-slate-600 text-sm">
                        Don't have an account? <button onclick="toggleSection('register')" class="text-blue-600 font-bold">Sign up</button>
                    </p>
                </div>

                <div id="register-section" class="auth-section hidden-section">
                    <h2 class="text-3xl font-bold text-slate-900 mb-2">Create Account</h2>
                    <p class="text-slate-500 mb-6">Join thousands of users.</p>
                    <form action="{{ route('register.post') }}" method="POST">
                        @csrf
                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <input type="text" name="first_name" placeholder="First Name" class="p-3 border rounded-custom outline-none" required>
                            <input type="text" name="last_name" placeholder="Last Name" class="p-3 border rounded-custom outline-none" required>
                        </div>
                        <input type="email" name="email" placeholder="Email" class="w-full p-3 border rounded-custom mb-4 outline-none" required>
                        <input type="password" name="password" placeholder="Password (min 8)" class="w-full p-3 border rounded-custom mb-6 outline-none" required>
                        <button type="submit" class="w-full bg-slate-900 text-white font-bold py-3 rounded-custom">Register</button>
                    </form>
                    <button onclick="toggleSection('login')" class="w-full mt-4 text-slate-500 text-sm font-medium">Back to Login</button>
                </div>

                <div id="forgot-section" class="auth-section hidden-section">
                    <h2 class="text-3xl font-bold text-slate-900 mb-2">Reset Password</h2>
                    <p class="text-slate-500 mb-6">We'll send a recovery link.</p>
                    <form action="#">
                        <input type="email" placeholder="Email Address" class="w-full p-3 border rounded-custom mb-6 outline-none">
                        <button type="button" class="w-full bg-blue-600 text-white font-bold py-3 rounded-custom">Send Link</button>
                    </form>
                    <button onclick="toggleSection('login')" class="w-full mt-4 text-slate-500 text-sm font-medium">Back to Login</button>
                </div>

            </div>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/animejs/3.2.1/anime.min.js"></script>

<script>
    function toggleSection(targetId) {
        const sections = document.querySelectorAll('.auth-section');
        const current = Array.from(sections).find(s => s.style.display !== 'none' && !s.classList.contains('hidden-section'));
        const next = document.getElementById(`${targetId}-section`);

        gsap.to(current, {
            opacity: 0, x: -20, duration: 0.3,
            onComplete: () => {
                current.style.display = 'none';
                current.classList.add('hidden-section');
                next.style.display = 'block';
                gsap.fromTo(next, { opacity: 0, x: 20 }, { opacity: 1, x: 0, duration: 0.4, ease: "back.out(1.7)" });
            }
        });
    }

    // Visual Background Anim
    gsap.to("#hero-shape", { duration: 8, x: 100, y: -50, repeat: -1, yoyo: true, ease: "sine.inOut" });
</script>
</body>
</html>