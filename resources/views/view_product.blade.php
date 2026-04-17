<!DOCTYPE html>
<html lang="en" class="bg-black text-white">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Display | Noir Series</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/animejs/3.2.1/anime.min.js"></script>
    
    <style>
        /* Custom scrollbar for black theme */
        ::-webkit-scrollbar { width: 5px; }
        ::-webkit-scrollbar-track { background: #000; }
        ::-webkit-scrollbar-thumb { background: #333; }
        
        .border-thin { border: 1px solid rgba(255, 255, 255, 0.1); }
        .clip-text { -webkit-background-clip: text; color: transparent; }
    </style>
</head>
<body class="overflow-x-hidden antialiased">

    <nav class="flex justify-between items-center px-8 py-6 border-b border-white/10 uppercase tracking-widest text-xs">
        <div class="font-bold text-lg">Inventory.01</div>
        <div class="flex gap-8">
            <a href="#" class="hover:line-through">Collection</a>
            <a href="#" class="hover:line-through">Archive</a>
            <div class="relative group cursor-pointer">
                Cart (0)
            </div>
        </div>
    </nav>

    <main class="container mx-auto px-4 py-12 lg:py-24">
        <div class="grid grid-cols-12 gap-0 border-thin">
            
            <div class="col-span-12 lg:col-span-8 border-r border-white/10 p-4 lg:p-12 relative overflow-hidden group">
                <div class="aspect-video bg-zinc-900 border-thin relative overflow-hidden">
                    <div id="main-image" class="w-full h-full bg-cover bg-center grayscale hover:grayscale-0 transition-all duration-700" style="background-image: url('https://images.unsplash.com/photo-1505740420928-5e560c06d30e?q=80&w=2070&auto=format&fit=crop');"></div>
                    <div class="absolute top-4 left-4 bg-black px-3 py-1 text-[10px] uppercase border-thin">Main View</div>
                </div>
                
                <div class="grid grid-cols-4 gap-4 mt-4 gallery-stagger">
                    <div class="aspect-square bg-zinc-900 border-thin cursor-pointer hover:border-white/50 transition-colors"></div>
                    <div class="aspect-square bg-zinc-900 border-thin cursor-pointer hover:border-white/50 transition-colors"></div>
                    <div class="aspect-square bg-zinc-900 border-thin cursor-pointer hover:border-white/50 transition-colors"></div>
                    <div class="aspect-square bg-zinc-900 border-thin cursor-pointer hover:border-white/50 transition-colors"></div>
                </div>
            </div>

            <div class="col-span-12 lg:col-span-4 p-8 lg:p-12 flex flex-col justify-between stagger-info">
                <div>
                    <div class="flex justify-between items-start mb-6">
                        <span class="text-[10px] uppercase tracking-[0.2em] text-zinc-500">Category / Studio</span>
                        <span class="border border-green-500 text-green-500 text-[8px] px-2 py-0.5 uppercase">Status: Active</span>
                    </div>

                    <h1 class="text-4xl lg:text-6xl font-light uppercase tracking-tighter mb-4 leading-none" id="product-title">
                        Acoustic <br> Structural
                    </h1>
                    
                    <p class="text-sm text-zinc-400 mb-8 leading-relaxed max-w-sm">
                        High-fidelity audio architecture designed for absolute immersion. Features active noise cancellation and ergonomic industrial form.
                    </p>

                    <div class="mb-12">
                        <h3 class="text-[10px] uppercase mb-4 tracking-widest text-zinc-500">Available Variants</h3>
                        <div class="flex flex-col gap-2">
                            <div class="variant-item group flex justify-between p-4 border-thin cursor-pointer hover:bg-white hover:text-black transition-all">
                                <span class="uppercase text-xs tracking-widest">Matte Black</span>
                                <span class="text-xs">$299.00</span>
                            </div>
                            <div class="variant-item group flex justify-between p-4 border-thin cursor-pointer hover:bg-white hover:text-black transition-all">
                                <span class="uppercase text-xs tracking-widest">Industrial Grey</span>
                                <span class="text-xs">$315.00</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-auto">
                    <button id="add-to-cart" class="w-full bg-white text-black py-6 uppercase text-xs font-bold tracking-[0.3em] hover:bg-zinc-200 transition-colors">
                        Add to Registry
                    </button>
                    <p class="text-[9px] text-zinc-600 mt-4 text-center uppercase tracking-widest">Free Express Shipping Worldwide</p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 border-x border-b border-white/10 text-[10px] uppercase tracking-widest text-zinc-500">
            <div class="p-6 border-r border-white/10">Stock Available: 12 Units</div>
            <div class="p-6 border-r border-white/10">SKU: PROD-992-01</div>
            <div class="p-6">Last Updated: 17.04.2026</div>
        </div>
    </main>

    <script>
        // GSAP Entrance Animations
        window.addEventListener('DOMContentLoaded', () => {
            const tl = gsap.timeline({ defaults: { ease: "power4.out" } });

            tl.from("nav", { y: -50, opacity: 0, duration: 1 })
              .from(".container", { opacity: 0, duration: 1.5 }, "-=0.5")
              .from("#main-image", { scale: 1.2, duration: 2 }, "-=1")
              .from("#product-title", { x: 50, opacity: 0, duration: 1 }, "-=1.5")
              .from(".stagger-info > *", { 
                  y: 20, 
                  opacity: 0, 
                  stagger: 0.1 
              }, "-=1")
              .from(".gallery-stagger div", { 
                  scale: 0.8, 
                  opacity: 0, 
                  stagger: 0.05 
              }, "-=0.8");
        });

        // Anime.js Micro-interactions
        const btn = document.querySelector('#add-to-cart');
        
        btn.addEventListener('mouseenter', () => {
            anime({
                targets: btn,
                letterSpacing: ['0.3em', '0.5em'],
                duration: 400,
                easing: 'easeOutQuad'
            });
        });

        btn.addEventListener('mouseleave', () => {
            anime({
                targets: btn,
                letterSpacing: ['0.5em', '0.3em'],
                duration: 400,
                easing: 'easeOutQuad'
            });
        });

        btn.addEventListener('mousedown', () => {
            anime({
                targets: btn,
                scale: 0.98,
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

        // Variant Selection Feedback
        document.querySelectorAll('.variant-item').forEach(item => {
            item.addEventListener('click', () => {
                anime({
                    targets: item,
                    translateX: [0, 10, 0],
                    duration: 300,
                    easing: 'easeInOutQuad'
                });
            });
        });
    </script>
</body>
</html>