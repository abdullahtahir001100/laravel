<!DOCTYPE html>
<html lang="en" class="bg-black text-white">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Create Product | Inventory System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/animejs/3.2.1/anime.min.js"></script>
    
    <style>
        input, textarea, select {
            background: transparent !important;
            border: 1px solid rgba(255, 255, 255, 0.2) !important;
            outline: none !important;
            border-radius: 0 !important;
        }
        input:focus, textarea:focus {
            border-color: #fff !important;
        }
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #333; }
        .border-thin { border: 1px solid rgba(255, 255, 255, 0.1); }
    </style>
</head>
<body class="antialiased overflow-x-hidden custom-scrollbar">

    <nav class="flex justify-between items-center px-8 py-6 border-b border-white/10 uppercase tracking-widest text-[10px]">
        <div class="font-bold text-lg tracking-tighter">New_Entry.exe</div>
        <a href="#" class="hover:line-through">Back to Dashboard</a>
    </nav>

    <main class="container mx-auto px-4 py-12">
        <form id="product-form" action="/save-product" method="POST" enctype="multipart/form-data" class="space-y-0">
            @csrf
            <input type="hidden" name="id" id="product-id">
            
            <div class="grid grid-cols-12 border-t border-x border-white/10 form-section">
                <div class="col-span-12 lg:col-span-4 p-8 border-b lg:border-b-0 lg:border-r border-white/10">
                    <h2 class="uppercase tracking-widest text-xs font-bold text-zinc-500">01. General Info</h2>
                </div>
                <div class="col-span-12 lg:col-span-8 p-8 border-b border-white/10 space-y-6">
                    <div>
                        <label class="block uppercase text-[10px] mb-2 tracking-widest text-zinc-400">Product Name</label>
                        <input type="text" name="name" class="w-full p-4 text-sm" placeholder="E.G. ULTRA-SONIC HEADPHONES">
                    </div>
                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <label class="block uppercase text-[10px] mb-2 tracking-widest text-zinc-400">Base Price ($)</label>
                            <input type="number" name="base_price" step="0.01" class="w-full p-4 text-sm">
                        </div>
                        <div>
                            <label class="block uppercase text-[10px] mb-2 tracking-widest text-zinc-400">Category</label>
                            <input type="text" name="category" class="w-full p-4 text-sm">
                        </div>
                    </div>
                    <div>
                        <label class="block uppercase text-[10px] mb-2 tracking-widest text-zinc-400">Status</label>
                        <select name="status" class="w-full p-4 text-sm uppercase">
                            <option value="active">Active</option>
                            <option value="draft">Draft</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-12 border-x border-white/10 form-section">
                <div class="col-span-12 lg:col-span-4 p-8 border-b lg:border-b-0 lg:border-r border-white/10">
                    <h2 class="uppercase tracking-widest text-xs font-bold text-zinc-500">02. Media Assets</h2>
                    <p class="text-[9px] text-zinc-600 mt-4 uppercase">Select multiple images for the gallery.</p>
                </div>
                <div class="col-span-12 lg:col-span-8 p-8 border-b border-white/10">
                    <div id="drop-zone" class="border-2 border-dashed border-white/10 p-12 text-center group hover:border-white/40 transition-colors cursor-pointer mb-6">
<input type="file" multiple name="images[]" id="file-upload" class="hidden" accept="image/*">

                        <label for="file-upload" class="cursor-pointer uppercase text-[10px] tracking-[0.3em]">
                            Click to upload production images
                        </label>
                    </div>
                    <div id="preview-grid" class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-5 gap-4">
                        </div>
                </div>
            </div>

            <div class="grid grid-cols-12 border-x border-white/10 form-section">
                <div class="col-span-12 lg:col-span-4 p-8 border-b lg:border-b-0 lg:border-r border-white/10">
                    <h2 class="uppercase tracking-widest text-xs font-bold text-zinc-500">03. Variants</h2>
                    <p class="text-[9px] text-zinc-600 mt-4 uppercase">Define sizes, colors, or technical versions.</p>
                </div>
                <div class="col-span-12 lg:col-span-8 p-8 border-b border-white/10">
                    <div id="variants-container" class="space-y-4">
                        <div class="variant-row grid grid-cols-4 gap-2">
                            <input type="text" placeholder="NAME" name="variants[0][name]" class="p-3 text-[10px]">
                            <input type="text" placeholder="VALUE" name="variants[0][value]" class="p-3 text-[10px]">
                            <input type="number" placeholder="PRICE" name="variants[0][price]" class="p-3 text-[10px]">
                            <input type="number" placeholder="STOCK" name="variants[0][stock]" class="p-3 text-[10px]">
                        </div>
                    </div>
                    <button type="button" id="add-variant" class="mt-6 text-[10px] uppercase border border-white/20 px-6 py-2 hover:bg-white hover:text-black transition-all">
                        + Add Variant Row
                    </button>
                </div>
            </div>

            <div class="grid grid-cols-12 border-x border-b border-white/10 form-section">
                <div class="col-span-12 lg:col-span-4 p-8 border-b lg:border-b-0 lg:border-r border-white/10">
                    <h2 class="uppercase tracking-widest text-xs font-bold text-zinc-500">04. Description</h2>
                </div>
                <div class="col-span-12 lg:col-span-8 p-8">
                    <textarea name="description" rows="5" class="w-full p-4 text-sm" placeholder="TECHNICAL SPECIFICATIONS..."></textarea>
                </div>
            </div>

            <div class="flex justify-end pt-12">
                <button type="submit" id="submit-btn" class="bg-white text-black px-16 py-6 uppercase text-xs font-bold tracking-[0.4em] hover:bg-zinc-200 transition-colors">
                    Save Product
                </button>
            </div>
        </form>
    </main>

    <script>
        // 1. GSAP Entrance Animations
        window.addEventListener('DOMContentLoaded', () => {
            const tl = gsap.timeline({ defaults: { ease: "expo.out" } });
            
            tl.from("nav", { y: -20, opacity: 0, duration: 1 })
              .from(".form-section", { 
                opacity: 0, 
                y: 30, 
                stagger: 0.2, 
                duration: 1.2 
              }, "-=0.5")
              .from("#submit-btn", { scaleX: 0, opacity: 0, duration: 0.8 }, "-=0.5");
        });

        // 2. Image Preview Logic
        const fileUpload = document.querySelector('#file-upload');
        const previewGrid = document.querySelector('#preview-grid');

        fileUpload.addEventListener('change', function() {
            const files = Array.from(this.files);

            files.forEach(file => {
                if (file.type.startsWith('image/')) {
                    const reader = new FileReader();

                    reader.onload = (e) => {
                        const wrapper = document.createElement('div');
                        wrapper.className = "relative aspect-square border-thin opacity-0 group overflow-hidden";
                        
                        wrapper.innerHTML = `
                            <img src="${e.target.result}" class="w-full h-full object-cover grayscale hover:grayscale-0 transition-all duration-500">
                            <button type="button" class="remove-img absolute top-1 right-1 bg-black/80 text-white text-[8px] px-2 py-1 border-thin opacity-0 group-hover:opacity-100 transition-opacity">
                                REMOVE
                            </button>
                        `;

                        previewGrid.appendChild(wrapper);

                        // Animation for new image
                        gsap.to(wrapper, {
                            opacity: 1,
                            scale: [0.8, 1],
                            duration: 0.6,
                            ease: "back.out(1.7)"
                        });

                        // Remove logic
                        wrapper.querySelector('.remove-img').addEventListener('click', () => {
                            gsap.to(wrapper, {
                                scale: 0.5,
                                opacity: 0,
                                duration: 0.3,
                                onComplete: () => wrapper.remove()
                            });
                        });
                    };
                    reader.readAsDataURL(file);
                }
            });
        });

        // 3. Dynamic Variants with Anime.js
        const container = document.querySelector('#variants-container');
        const addBtn = document.querySelector('#add-variant');
        let index = document.querySelectorAll('.variant-row').length;
        addBtn.addEventListener('click', () => {
            const newRow = document.createElement('div');
            newRow.className = "variant-row grid grid-cols-4 gap-2 opacity-0";
            newRow.innerHTML = `
                <input type="text" name="variants[${index}][name]" placeholder="NAME" class="p-3 text-[10px]">
                <input type="text" name="variants[${index}][value]" placeholder="VALUE" class="p-3 text-[10px]">
                <input type="number" name="variants[${index}][price]" placeholder="PRICE" class="p-3 text-[10px]">
                <input type="number" name="variants[${index}][stock]" placeholder="STOCK" class="p-3 text-[10px]">
            `;
            container.appendChild(newRow);

            anime({
                targets: newRow,
                opacity: [0, 1],
                translateX: [-20, 0],
                duration: 600,
                easing: 'easeOutExpo'
            });
        });

        // 4. Submit Button Interaction (Anime.js)
        // Helper to safely inject text into inputs
        function escapeHtml(unsafe) {
            return String(unsafe === undefined || unsafe === null ? '' : unsafe)
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#039;');
        }

        // Load product data when `id` query param is present
        (function loadProductFromUrl() {
            function getQueryParam(name) { return new URLSearchParams(window.location.search).get(name); }
            const pid = getQueryParam('id');
            const pidInput = document.getElementById('product-id');
            if (!pid) return;

            fetch('/get-product?id=' + encodeURIComponent(pid))
                .then(res => res.json())
                .then(p => {
                    if (!p || p.status === false) return;
                    pidInput.value = p.id;
                    document.querySelector('input[name="name"]').value = p.name ?? '';
                    document.querySelector('textarea[name="description"]').value = p.description ?? '';
                    document.querySelector('input[name="base_price"]').value = p.base_price ?? '';
                    document.querySelector('input[name="category"]').value = p.category ?? '';
                    if (p.status) document.querySelector('select[name="status"]').value = p.status;

                    // render variants
                    container.innerHTML = '';
                    index = 0;
                    if (Array.isArray(p.variants) && p.variants.length) {
                        p.variants.forEach((v, i) => {
                            const row = document.createElement('div');
                            row.className = 'variant-row grid grid-cols-4 gap-2';
                            row.innerHTML = `
                                <input type="text" name="variants[${i}][name]" value="${escapeHtml(v.variant_name)}" placeholder="NAME" class="p-3 text-[10px]">
                                <input type="text" name="variants[${i}][value]" value="${escapeHtml(v.variant_value)}" placeholder="VALUE" class="p-3 text-[10px]">
                                <input type="number" name="variants[${i}][price]" value="${v.price ?? ''}" placeholder="PRICE" class="p-3 text-[10px]">
                                <input type="number" name="variants[${i}][stock]" value="${v.stock ?? ''}" placeholder="STOCK" class="p-3 text-[10px]">
                            `;
                            container.appendChild(row);
                            index++;
                        });
                    } else {
                        container.innerHTML = `<div class="variant-row grid grid-cols-4 gap-2">
                            <input type="text" placeholder="NAME" name="variants[0][name]" class="p-3 text-[10px]">
                            <input type="text" placeholder="VALUE" name="variants[0][value]" class="p-3 text-[10px]">
                            <input type="number" placeholder="PRICE" name="variants[0][price]" class="p-3 text-[10px]">
                            <input type="number" placeholder="STOCK" name="variants[0][stock]" class="p-3 text-[10px]">
                        </div>`;
                        index = 1;
                    }

                    // render existing images (read-only previews)
                    previewGrid.innerHTML = '';
                    if (Array.isArray(p.images)) {
                        p.images.forEach(img => {
                            const wrapper = document.createElement('div');
                            wrapper.className = 'relative aspect-square border-thin opacity-1 group overflow-hidden';
                            wrapper.innerHTML = `
                                <img src="/storage/${img.image_path}" class="w-full h-full object-cover">
                                <button type="button" class="remove-img absolute top-1 right-1 bg-black/80 text-white text-[8px] px-2 py-1 border-thin">REMOVE</button>
                            `;
                            previewGrid.appendChild(wrapper);
                            wrapper.querySelector('.remove-img').addEventListener('click', () => wrapper.remove());
                        });
                    }
                })
                .catch(err => { console.error('Failed loading product', err); });
        })();
        const submitBtn = document.querySelector('#submit-btn');
        submitBtn.addEventListener('mouseenter', () => {
            anime({
                targets: submitBtn,
                paddingLeft: '5rem',
                paddingRight: '5rem',
                duration: 400,
                easing: 'easeOutQuad'
            });
        });

        submitBtn.addEventListener('mouseleave', () => {
            anime({
                targets: submitBtn,
                paddingLeft: '4rem',
                paddingRight: '4rem',
                duration: 400,
                easing: 'easeOutQuad'
            });
        });
     const form = document.querySelector('#product-form');

form.addEventListener('submit', (e) => {
    e.preventDefault();

    // button animation
    anime({
        targets: submitBtn,
        scale: [1, 0.95, 1],
        duration: 300,
        easing: 'easeInOutQuad'
    });

   
    fetch('/save-product', {
        method: 'POST',
      headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
    },
        body: new FormData(form)
    })
    .then(response => response.json())
    .then(data => {

        if (data.status) {
            anime({
                targets: submitBtn,
                backgroundColor: '#4CAF50',
                color: '#fff',
                duration: 500
            });
            alert(data.message);
        } else {
            anime({
                targets: submitBtn,
                backgroundColor: '#E53935',
                color: '#fff',
                duration: 500
            });
            alert(data.message);
        }

    })
    .catch(() => {
        anime({
            targets: submitBtn,
            backgroundColor: '#E53935',
            duration: 500
        });
        alert("Network Error");
    });
});

    </script>
</body>
</html>