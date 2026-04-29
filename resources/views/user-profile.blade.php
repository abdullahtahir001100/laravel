<!DOCTYPE html>
<html lang="en">

@php
    $profileUser = $profileUser ?? auth()->user();
    $isOwnProfile = $isOwnProfile ?? (auth()->check() && auth()->id() === $profileUser?->id);
    $profileFullName = trim(($profileUser?->first_name ?? '') . ' ' . ($profileUser?->last_name ?? '')) ?: 'User';
    $profileDisplayName = $profileUser?->display_name ?: $profileFullName;
    $profileCoverUrl = $profileUser?->cover_photo_path ? asset('storage/' . $profileUser->cover_photo_path) : 'https://images.unsplash.com/photo-1519389950473-47ba0277781c?q=80&w=1800';
    $profileAvatarUrl = $profileUser?->avatar_path ? asset('storage/' . $profileUser->avatar_path) : 'https://ui-avatars.com/api/?name=' . urlencode($profileDisplayName) . '&background=1665d8&color=fff&size=260';
@endphp

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $profileDisplayName }} | Profile</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <style>
        :root {
            --brand: #1665d8;
        }
        #profile-preview{
        border-radius: 9999px !important;
        }

        body {
            background: var(--bg-main);
            color: var(--text-main);
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
        }

        .theme-dark body {
            background: var(--bg-main) !important;
        }

        .rounded-custom {
            border-radius: 5px !important;
        }

        .card-base {
            background: var(--bg-card);
            border: 1px solid var(--border-main);
            border-radius: 5px;
        }

        .section-tab.active {
            color: var(--brand);
            border-bottom: 3px solid var(--brand);
            background: rgba(37, 99, 235, 0.1);
        }

        .hide-scroll::-webkit-scrollbar {
            display: none;
        }

        .soft-scroll {
            scrollbar-width: thin;
            scrollbar-color: #cbd5e1 transparent;
            scroll-behavior: smooth;
        }

        .soft-scroll::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        .soft-scroll::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 999px;
        }

        .reel-slide {
            width: 180px !important;
        }
        #edit-cover-btn{
        z-index: 20;
        }
    </style>
</head>

<body class="overflow-x-hidden" data-own-profile="{{ $isOwnProfile ? '1' : '0' }}" data-user-id="{{ $profileUser?->id ?? '' }}">
    <x-dashboard-header />
    <div class="flex">
        <x-dashboard-sidebar />
        <div class="flex-1 w-full min-h-screen pt-16 overflow-y-auto">
       

        <section class="max-w-[1600px] mx-auto px-4 md:px-8 pt-4">
            <div class="relative rounded-custom overflow-hidden border border-slate-200 bg-white group">
                <img id="cover-preview" src="{{ $profileCoverUrl }}" alt="Cover"
                    class="w-full h-[230px] md:h-[360px] object-cover cursor-pointer">
                @if($isOwnProfile)
                    <input id="cover-input" type="file" accept="image/*" class="hidden">
                    <button id="edit-cover-floating"
                        class="absolute left-4 bottom-4 px-3 py-2 bg-slate-900/70 text-white text-xs font-semibold rounded-custom border border-white/20 hover:bg-slate-900/80">
                        Change Cover
                    </button>
                    <button id="edit-cover-btn"
                        class="absolute right-4 bottom-4 px-4 py-2 bg-white text-slate-900 text-sm font-semibold rounded-custom border border-slate-200 hover:bg-slate-50 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M3 9a2 2 0 012-2h.93a2 2 0 001.66-.89l.82-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.66.89l.82 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                            <path d="M12 11a3 3 0 100 6 3 3 0 000-6z"></path>
                        </svg>
                        Edit Cover Photo
                    </button>
                @endif
            </div>

            <div class="relative z-10 -mt-14 md:-mt-20 px-2 md:px-6 pb-3">
                <div class="flex flex-col lg:flex-row lg:items-end gap-5">
                    <div class="relative w-fit">
                        <img id="profile-preview" src="{{ $profileAvatarUrl }}" alt="Profile"
                            class="w-28 h-28 md:w-40 md:h-40 object-cover border-4 border-white shadow-lg rounded-custom bg-slate-100">
                        @if($isOwnProfile)
                            <input id="profile-input" type="file" accept="image/*" class="hidden">
                            <button id="edit-profile-image-btn"
                                class="absolute -right-2 bottom-2 w-10 h-10 rounded-custom border border-slate-200 bg-white flex items-center justify-center hover:bg-slate-50">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path d="M3 9a2 2 0 012-2h.93a2 2 0 001.66-.89l.82-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.66.89l.82 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                                    <path d="M12 11a3 3 0 100 6 3 3 0 000-6z"></path>
                                </svg>
                            </button>
                        @endif
                    </div>

                    <div class="flex-1">
                        <h2 id="profile-name" class="text-3xl md:text-4xl font-extrabold">{{ $profileDisplayName }}</h2>
                        <p class="text-slate-500 mt-1">{{ $profileUser?->email ?? '' }}</p>
                    </div>

                    <div class="flex flex-wrap gap-2 lg:mb-2">
                        <button class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-custom">Add to Story</button>
                        @if($isOwnProfile)
                            <a href="{{ route('settings.index') }}" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-800 text-sm font-semibold rounded-custom border border-slate-200">Edit Profile</a>
                        @else
                            <button class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-800 text-sm font-semibold rounded-custom border border-slate-200">Follow</button>
                        @endif
                        <div class="relative">
                            <button id="profile-menu-toggle"
                                class="w-10 h-10 rounded-custom bg-slate-100 border border-slate-200 hover:bg-slate-200 flex items-center justify-center">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M6 10a2 2 0 11-4 0 2 2 0 014 0zm6 0a2 2 0 11-4 0 2 2 0 014 0zm6 0a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                            </button>
                            <div id="profile-menu" class="hidden absolute right-0 top-12 w-48 card-base shadow-lg p-2 z-30">
                                <button class="w-full px-3 py-2 text-left text-sm hover:bg-slate-50 rounded-custom">Not Interested</button>
                                <button class="w-full px-3 py-2 text-left text-sm hover:bg-slate-50 rounded-custom">Report Profile</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <nav class="card-base mt-1 px-2 overflow-x-auto hide-scroll">
                <div class="flex min-w-max">
                    <button data-target="posts-section" class="section-tab active px-6 py-4 text-sm font-bold text-slate-700 whitespace-nowrap">Posts</button>
                    <button data-target="about-section" class="section-tab px-6 py-4 text-sm font-bold text-slate-600 whitespace-nowrap">About</button>
                    <button data-target="friends-section" class="section-tab px-6 py-4 text-sm font-bold text-slate-600 whitespace-nowrap">Friends</button>
                    <button data-target="photos-section" class="section-tab px-6 py-4 text-sm font-bold text-slate-600 whitespace-nowrap">Photos</button>
                    <button data-target="videos-section" class="section-tab px-6 py-4 text-sm font-bold text-slate-600 whitespace-nowrap">Videos</button>
                    <button data-target="reels-section" class="section-tab px-6 py-4 text-sm font-bold text-slate-600 whitespace-nowrap">Reels</button>
                </div>
            </nav>
        </section>

        <main class="max-w-[1600px] mx-auto px-4 md:px-8 py-6 grid grid-cols-1 xl:grid-cols-12 gap-6">
            <aside class="xl:col-span-4 space-y-6 xl:sticky xl:top-20 self-start max-h-[calc(100vh-6rem)] overflow-y-auto soft-scroll pr-1">
                <section id="about-section" class="card-base p-5">
                    <h3 class="font-bold text-xl mb-4">About</h3>
                    <div class="space-y-3 text-sm text-slate-700">
                        <p>{{ $profileUser?->headline ?: 'Founder and instructor at InkByHand Calligraphy.' }}</p>
                        <p>{{ $profileUser?->about ?: 'Studied at Superior College, Lahore.' }}</p>
                        <p>{{ $profileUser?->bio ?: 'Lives in Shahdara, Lahore.' }}</p>
                    </div>
                </section>

                <section id="friends-section" class="card-base p-5">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="font-bold text-xl">Friends</h3>
                        <button id="see-all-friends-btn" class="text-sm text-blue-600 font-semibold">See All</button>
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div class="p-2 border border-slate-200 rounded-custom">
                            <img src="https://ui-avatars.com/api/?name=Zain" class="w-full h-24 object-cover rounded-custom" alt="Zain">
                            <p class="text-sm font-semibold mt-2">Zain Ahmed</p>
                        </div>
                        <div class="p-2 border border-slate-200 rounded-custom">
                            <img src="https://ui-avatars.com/api/?name=Sara" class="w-full h-24 object-cover rounded-custom" alt="Sara">
                            <p class="text-sm font-semibold mt-2">Sara Khan</p>
                        </div>
                    </div>
                </section>

                <section id="photos-section" class="card-base p-5">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="font-bold text-xl">Photos</h3>
                        <button id="see-all-photos-btn" class="text-sm text-blue-600 font-semibold">See All</button>
                    </div>
                    <div class="grid grid-cols-3 gap-2">
                        <img src="https://picsum.photos/300/300?sig=91" class="w-full h-24 object-cover rounded-custom" alt="Photo 1">
                        <img src="https://picsum.photos/300/300?sig=92" class="w-full h-24 object-cover rounded-custom" alt="Photo 2">
                        <img src="https://picsum.photos/300/300?sig=93" class="w-full h-24 object-cover rounded-custom" alt="Photo 3">
                        <img src="https://picsum.photos/300/300?sig=94" class="w-full h-24 object-cover rounded-custom" alt="Photo 4">
                        <img src="https://picsum.photos/300/300?sig=95" class="w-full h-24 object-cover rounded-custom" alt="Photo 5">
                        <img src="https://picsum.photos/300/300?sig=96" class="w-full h-24 object-cover rounded-custom" alt="Photo 6">
                    </div>
                </section>

                <section id="videos-section" class="card-base p-5">
                    <h3 class="font-bold text-xl mb-4">Videos</h3>
                    <div class="space-y-3">
                        <div class="p-3 border border-slate-200 rounded-custom">
                            <p class="font-semibold text-sm">Calligraphy Basics Session</p>
                            <p class="text-xs text-slate-500 mt-1">14.2K views</p>
                        </div>
                        <div class="p-3 border border-slate-200 rounded-custom">
                            <p class="font-semibold text-sm">Brush Pen Practice Routine</p>
                            <p class="text-xs text-slate-500 mt-1">8.8K views</p>
                        </div>
                    </div>
                </section>
            </aside>

            <section class="xl:col-span-8 space-y-6">
                <section id="reels-section" class="card-base p-5 overflow-hidden">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-bold">Reels</h3>
                        <div class="flex items-center gap-2">
                            <button class="reels-prev w-8 h-8 rounded-custom border border-slate-200 hover:bg-slate-50">&#8249;</button>
                            <button class="reels-next w-8 h-8 rounded-custom border border-slate-200 hover:bg-slate-50">&#8250;</button>
                        </div>
                    </div>
                    <div class="swiper myReels">
                        <div id="reels-wrapper" class="swiper-wrapper"></div>
                    </div>
                </section>

                <section id="posts-section" class="space-y-4">
                    <div class="card-base p-4">
                        <h3 class="text-lg font-bold mb-3">Create Post</h3>
                        <textarea id="new-post-text" rows="3" placeholder="Write something for your followers..."
                            class="w-full border border-slate-200 rounded-custom p-3 outline-none focus:border-blue-300 text-sm"></textarea>
                        <div class="mt-3 flex justify-end">
                            <button id="add-post-btn" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-custom text-sm font-semibold">Add Post</button>
                        </div>
                    </div>

                    <div id="posts-feed" class="space-y-4"></div>
                </section>
            </section>
        </main>
    </div>
    </div>

    <div id="share-popup" class="hidden fixed inset-0 z-50 bg-slate-900/35 p-4">
        <div class="h-full w-full flex items-center justify-center">
            <div class="card-base w-full max-w-md shadow-xl">
                <div class="p-4 border-b border-slate-200 flex items-center justify-between">
                    <h4 class="font-bold">Share Post</h4>
                    <button id="close-share" class="w-8 h-8 rounded-custom border border-slate-200 hover:bg-slate-50">X</button>
                </div>
                <div class="p-4 space-y-3">
                    <input id="share-link" type="text" readonly class="w-full border border-slate-200 rounded-custom p-2.5 text-sm">
                    <div class="grid grid-cols-3 gap-2 text-sm">
                        <button class="share-link-btn border border-slate-200 rounded-custom py-2" data-net="WhatsApp">WhatsApp</button>
                        <button class="share-link-btn border border-slate-200 rounded-custom py-2" data-net="Facebook">Facebook</button>
                        <button class="share-link-btn border border-slate-200 rounded-custom py-2" data-net="LinkedIn">LinkedIn</button>
                        <button class="share-link-btn border border-slate-200 rounded-custom py-2" data-net="X">X</button>
                        <button class="share-link-btn border border-slate-200 rounded-custom py-2" data-net="Email">Email</button>
                        <button id="copy-share-link" class="bg-blue-600 text-white rounded-custom py-2">Copy Link</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="likes-popup" class="hidden fixed inset-0 z-50 bg-slate-900/35 p-4">
        <div class="h-full w-full flex items-center justify-center">
            <div class="card-base w-full max-w-sm shadow-xl">
                <div class="p-4 border-b border-slate-200 flex items-center justify-between">
                    <h4 class="font-bold">Likes</h4>
                    <button id="close-likes" class="w-8 h-8 rounded-custom border border-slate-200 hover:bg-slate-50">X</button>
                </div>
                <div id="likes-list" class="p-3 max-h-72 overflow-y-auto space-y-2"></div>
            </div>
        </div>
    </div>

    <div id="photos-popup" class="hidden fixed inset-0 z-50 bg-slate-900/35 p-4">
        <div class="h-full w-full flex items-center justify-center">
            <div class="card-base w-full max-w-5xl shadow-xl">
                <div class="p-4 border-b border-slate-200 flex items-center justify-between">
                    <h4 class="font-bold">All Photos</h4>
                    <button id="close-photos" class="w-8 h-8 rounded-custom border border-slate-200 hover:bg-slate-50">X</button>
                </div>
                <div id="photos-scroll-area" class="p-4 max-h-[70vh] overflow-y-auto soft-scroll">
                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3">
                        <img src="https://picsum.photos/500/500?sig=201" class="w-full h-44 object-cover rounded-custom" alt="Gallery Photo 1">
                        <img src="https://picsum.photos/500/500?sig=202" class="w-full h-44 object-cover rounded-custom" alt="Gallery Photo 2">
                        <img src="https://picsum.photos/500/500?sig=203" class="w-full h-44 object-cover rounded-custom" alt="Gallery Photo 3">
                        <img src="https://picsum.photos/500/500?sig=204" class="w-full h-44 object-cover rounded-custom" alt="Gallery Photo 4">
                        <img src="https://picsum.photos/500/500?sig=205" class="w-full h-44 object-cover rounded-custom" alt="Gallery Photo 5">
                        <img src="https://picsum.photos/500/500?sig=206" class="w-full h-44 object-cover rounded-custom" alt="Gallery Photo 6">
                        <img src="https://picsum.photos/500/500?sig=207" class="w-full h-44 object-cover rounded-custom" alt="Gallery Photo 7">
                        <img src="https://picsum.photos/500/500?sig=208" class="w-full h-44 object-cover rounded-custom" alt="Gallery Photo 8">
                        <img src="https://picsum.photos/500/500?sig=209" class="w-full h-44 object-cover rounded-custom" alt="Gallery Photo 9">
                        <img src="https://picsum.photos/500/500?sig=210" class="w-full h-44 object-cover rounded-custom" alt="Gallery Photo 10">
                        <img src="https://picsum.photos/500/500?sig=211" class="w-full h-44 object-cover rounded-custom" alt="Gallery Photo 11">
                        <img src="https://picsum.photos/500/500?sig=212" class="w-full h-44 object-cover rounded-custom" alt="Gallery Photo 12">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="friends-popup" class="hidden fixed inset-0 z-50 bg-slate-900/35 p-4">
        <div class="h-full w-full flex items-center justify-center">
            <div class="card-base w-full max-w-xl shadow-xl">
                <div class="p-4 border-b border-slate-200 flex items-center justify-between">
                    <h4 class="font-bold">All Friends</h4>
                    <button id="close-friends" class="w-8 h-8 rounded-custom border border-slate-200 hover:bg-slate-50">X</button>
                </div>
                <div class="p-4 max-h-[65vh] overflow-y-auto soft-scroll space-y-2">
                    <div class="flex items-center justify-between p-2 border border-slate-200 rounded-custom"><span class="text-sm font-semibold">Zain Ahmed</span><button class="text-xs text-blue-600">View</button></div>
                    <div class="flex items-center justify-between p-2 border border-slate-200 rounded-custom"><span class="text-sm font-semibold">Sara Khan</span><button class="text-xs text-blue-600">View</button></div>
                    <div class="flex items-center justify-between p-2 border border-slate-200 rounded-custom"><span class="text-sm font-semibold">Ali Hassan</span><button class="text-xs text-blue-600">View</button></div>
                    <div class="flex items-center justify-between p-2 border border-slate-200 rounded-custom"><span class="text-sm font-semibold">Noor Fatima</span><button class="text-xs text-blue-600">View</button></div>
                    <div class="flex items-center justify-between p-2 border border-slate-200 rounded-custom"><span class="text-sm font-semibold">Hina Ashfaq</span><button class="text-xs text-blue-600">View</button></div>
                    <div class="flex items-center justify-between p-2 border border-slate-200 rounded-custom"><span class="text-sm font-semibold">Faraz Ahmed</span><button class="text-xs text-blue-600">View</button></div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script>
        const isOwnProfile = document.body.dataset.ownProfile === '1';
        const CSRF_TOKEN = '{{ csrf_token() }}';
        const profileMenuToggle = document.getElementById('profile-menu-toggle');
        const profileMenu = document.getElementById('profile-menu');
        const coverInput = document.getElementById('cover-input');
        const profileInput = document.getElementById('profile-input');
        const coverPreview = document.getElementById('cover-preview');
        const profilePreview = document.getElementById('profile-preview');
        const profileName = document.getElementById('profile-name');
        const reelsWrapper = document.getElementById('reels-wrapper');
        const postsFeed = document.getElementById('posts-feed');
        const addPostBtn = document.getElementById('add-post-btn');
        const newPostText = document.getElementById('new-post-text');
        const sharePopup = document.getElementById('share-popup');
        const likesPopup = document.getElementById('likes-popup');
        const photosPopup = document.getElementById('photos-popup');
        const friendsPopup = document.getElementById('friends-popup');
        const likesList = document.getElementById('likes-list');
        const shareLink = document.getElementById('share-link');
        const sectionTabs = document.querySelectorAll('.section-tab');

        const reelsData = [
            { img: 'https://picsum.photos/300/600?sig=21', views: '12K views' },
            { img: 'https://picsum.photos/300/600?sig=22', views: '8.2K views' },
            { img: 'https://picsum.photos/300/600?sig=23', views: '4.9K views' },
            { img: 'https://picsum.photos/300/600?sig=24', views: '15K views' },
            { img: 'https://picsum.photos/300/600?sig=25', views: '9.1K views' },
            { img: 'https://picsum.photos/300/600?sig=26', views: '7.6K views' },
            { img: 'https://picsum.photos/300/600?sig=27', views: '11.4K views' },
            { img: 'https://picsum.photos/300/600?sig=28', views: '6.8K views' },
            { img: 'https://picsum.photos/300/600?sig=29', views: '10.3K views' }
        ];

        const posts = [
            {
                id: 1,
                author: 'InkByHand Calligraphy',
                time: '2h ago',
                text: 'New lettering workshop schedule is now open for this weekend.',
                image: 'https://images.unsplash.com/photo-1455390582262-044cdead277a?q=80&w=1200',
                likes: ['Zain Ahmed', 'Sara Khan', 'Ali Raza'],
                likedByYou: false,
                commentsOpen: false,
                showAllComments: false,
                comments: [
                    { name: 'Zain Ahmed', text: 'Great update. I am joining.' },
                    { name: 'Sara Khan', text: 'The design is very clean.' },
                    { name: 'Ali Raza', text: 'Please share timings too.' },
                    { name: 'Noor Fatima', text: 'I want to attend this class.' }
                ]
            },
            {
                id: 2,
                author: 'InkByHand Calligraphy',
                time: 'Yesterday',
                text: 'Studio wall update completed. We also added new light setup for videos.',
                image: 'https://images.unsplash.com/photo-1505691938895-1758d7feb511?q=80&w=1200',
                likes: ['Hina Ashfaq', 'Faraz Ahmed'],
                likedByYou: false,
                commentsOpen: false,
                showAllComments: false,
                comments: [
                    { name: 'Hina Ashfaq', text: 'Looks premium and bright.' },
                    { name: 'Faraz Ahmed', text: 'Very professional setup now.' },
                    { name: 'Mina Qureshi', text: 'Can we get a tour reel?' }
                ]
            }
        ];

        function fileToPreview(input, imageElement) {
            const file = input.files && input.files[0];
            if (!file) return;
            if (!file.type || !file.type.startsWith('image/')) return;

            const previewUrl = URL.createObjectURL(file);
            imageElement.src = previewUrl;
            imageElement.onload = function() {
                URL.revokeObjectURL(previewUrl);
            };

            return file;
        }

        async function uploadProfileMedia(file, type) {
            if (!file) return;

            const formData = new FormData();
            if (type === 'profile') formData.append('profile_image', file);
            if (type === 'cover') formData.append('cover_photo', file);

            const response = await fetch('/api/profile/media', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': CSRF_TOKEN,
                    Accept: 'application/json'
                },
                body: formData
            });

            if (!response.ok) {
                throw new Error('Upload failed');
            }

            return response.json();
        }

        async function hydrateProfileFromApi() {
            if (!isOwnProfile) return;

            const response = await fetch('/api/settings/read', {
                method: 'GET',
                headers: { Accept: 'application/json' }
            });

            if (!response.ok) return;

            const data = await response.json();
            if (data?.profile?.avatarUrl) profilePreview.src = data.profile.avatarUrl;
            if (data?.profile?.coverPhotoUrl) coverPreview.src = data.profile.coverPhotoUrl;
            if (data?.profile?.displayName) profileName.textContent = data.profile.displayName;
        }

        function openImagePicker(input) {
            // Reset value so selecting the same file still triggers change event.
            input.value = '';
            input.click();
        }

        function renderReels() {
            reelsWrapper.innerHTML = reelsData.map((item) => `
                <div class="swiper-slide reel-slide h-72 rounded-custom overflow-hidden relative border border-slate-200">
                    <img src="${item.img}" alt="Reel" class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-950/70 to-transparent"></div>
                    <div class="absolute bottom-3 left-3 text-white text-xs font-semibold">${item.views}</div>
                </div>
            `).join('');
        }

        function makePostCard(post) {
            const visibleComments = post.showAllComments ? post.comments : post.comments.slice(0, 2);
            return `
                <article class="card-base overflow-hidden" data-post-id="${post.id}">
                    <div class="p-4 flex items-start justify-between">
                        <div class="flex items-center gap-3">
                            <img src="https://ui-avatars.com/api/?name=InkByHand" class="w-11 h-11 rounded-custom" alt="Avatar">
                            <div>
                                <h4 class="text-sm font-bold">${post.author}</h4>
                                <p class="text-xs text-slate-500">${post.time}</p>
                            </div>
                        </div>
                        <div class="relative">
                            <button class="post-more-btn w-8 h-8 rounded-custom border border-slate-200 hover:bg-slate-50">...</button>
                            <div class="post-menu hidden absolute right-0 top-10 w-44 card-base shadow-lg p-2 z-20">
                                <button class="remove-post-btn w-full text-left px-3 py-2 text-sm hover:bg-slate-50 rounded-custom">Not Interested</button>
                            </div>
                        </div>
                    </div>

                    <div class="px-4 pb-3 text-sm text-slate-700">${post.text}</div>
                    <img src="${post.image}" alt="Post image" class="w-full h-auto border-y border-slate-200">

                    <div class="px-4 py-3 border-b border-slate-200 flex items-center justify-between text-xs text-slate-500">
                        <button class="open-likes-btn hover:text-blue-600">${post.likes.length} likes</button>
                        <button class="toggle-comments-btn hover:text-blue-600">${post.comments.length} comments</button>
                    </div>

                    <div class="p-2 grid grid-cols-3 gap-2 border-b border-slate-200">
                        <button class="like-btn ${post.likedByYou ? 'text-blue-600' : 'text-slate-700'} py-2 rounded-custom hover:bg-slate-50 text-sm font-semibold">Like</button>
                        <button class="toggle-comments-btn py-2 rounded-custom hover:bg-slate-50 text-sm font-semibold">Comment</button>
                        <button class="open-share-btn py-2 rounded-custom hover:bg-slate-50 text-sm font-semibold">Share</button>
                    </div>

                    <div class="comments-wrap ${post.commentsOpen ? '' : 'hidden'} p-4 bg-slate-50 border-t border-slate-200">
                        <div class="space-y-2 mb-3">
                            ${visibleComments.map(c => `
                                <div class="p-2.5 rounded-custom border border-slate-200 bg-white">
                                    <p class="text-xs font-bold text-slate-700">${c.name}</p>
                                    <p class="text-sm text-slate-700">${c.text}</p>
                                </div>
                            `).join('')}
                        </div>
                        <button class="toggle-all-comments-btn text-xs text-blue-600 font-semibold mb-3">${post.showAllComments ? 'Hide comments' : 'Show all comments'}</button>
                        <div class="flex gap-2">
                            <input type="text" class="new-comment-input w-full border border-slate-200 rounded-custom px-3 py-2 text-sm outline-none focus:border-blue-300" placeholder="Write a comment...">
                            <button class="post-comment-btn px-4 bg-blue-600 hover:bg-blue-700 text-white rounded-custom text-sm">Post</button>
                        </div>
                    </div>
                </article>
            `;
        }

        function renderPosts() {
            postsFeed.innerHTML = posts.map(makePostCard).join('');
        }

        function openLikes(post) {
            likesList.innerHTML = post.likes.map(name => `
                <div class="flex items-center justify-between px-2 py-2 border border-slate-200 rounded-custom">
                    <div class="flex items-center gap-2">
                        <img src="https://ui-avatars.com/api/?name=${encodeURIComponent(name)}" class="w-8 h-8 rounded-custom" alt="${name}">
                        <span class="text-sm">${name}</span>
                    </div>
                    <button class="text-xs px-2 py-1 border border-slate-200 rounded-custom">View</button>
                </div>
            `).join('');
            likesPopup.classList.remove('hidden');
        }

        function openShare(postId) {
            shareLink.value = `${window.location.origin}/profile/post/${postId}`;
            sharePopup.classList.remove('hidden');
        }

        if (isOwnProfile) {
            document.getElementById('edit-cover-btn')?.addEventListener('click', () => openImagePicker(coverInput));
            document.getElementById('edit-cover-floating')?.addEventListener('click', () => openImagePicker(coverInput));
            coverPreview?.addEventListener('click', () => openImagePicker(coverInput));
            document.getElementById('edit-profile-image-btn')?.addEventListener('click', () => openImagePicker(profileInput));
            coverInput?.addEventListener('change', async () => {
                const file = fileToPreview(coverInput, coverPreview);
                if (!file) return;

                try {
                    const res = await uploadProfileMedia(file, 'cover');
                    if (res?.coverPhotoUrl) coverPreview.src = res.coverPhotoUrl;
                } catch (err) {
                    alert('Cover photo upload failed.');
                }
            });

            profileInput?.addEventListener('change', async () => {
                const file = fileToPreview(profileInput, profilePreview);
                if (!file) return;

                try {
                    const res = await uploadProfileMedia(file, 'profile');
                    if (res?.avatarUrl) profilePreview.src = res.avatarUrl;
                } catch (err) {
                    alert('Profile image upload failed.');
                }
            });
        }

        document.getElementById('see-all-photos-btn').addEventListener('click', () => {
            photosPopup.classList.remove('hidden');
        });
        document.getElementById('see-all-friends-btn').addEventListener('click', () => {
            friendsPopup.classList.remove('hidden');
        });
        document.getElementById('close-photos').addEventListener('click', () => photosPopup.classList.add('hidden'));
        document.getElementById('close-friends').addEventListener('click', () => friendsPopup.classList.add('hidden'));

        profileMenuToggle.addEventListener('click', (e) => {
            e.stopPropagation();
            profileMenu.classList.toggle('hidden');
        });

        sectionTabs.forEach((tab) => {
            tab.addEventListener('click', () => {
                sectionTabs.forEach(t => t.classList.remove('active'));
                tab.classList.add('active');
                const section = document.getElementById(tab.dataset.target);
                if (section) section.scrollIntoView({ behavior: 'smooth', block: 'start' });
            });
        });

        addPostBtn.addEventListener('click', () => {
            const text = newPostText.value.trim();
            if (!text) return;

            posts.unshift({
                id: Date.now(),
                author: 'InkByHand Calligraphy',
                time: 'Just now',
                text: text,
                image: 'https://picsum.photos/1200/700?random=' + Date.now(),
                likes: [],
                likedByYou: false,
                commentsOpen: true,
                showAllComments: true,
                comments: []
            });

            newPostText.value = '';
            renderPosts();
            document.getElementById('posts-section').scrollIntoView({ behavior: 'smooth', block: 'start' });
        });

        postsFeed.addEventListener('click', (e) => {
            const postCard = e.target.closest('[data-post-id]');
            if (!postCard) return;
            const postId = Number(postCard.dataset.postId);
            const post = posts.find(p => p.id === postId);
            if (!post) return;

            if (e.target.closest('.post-more-btn')) {
                postCard.querySelector('.post-menu').classList.toggle('hidden');
                return;
            }

            if (e.target.closest('.remove-post-btn')) {
                const idx = posts.findIndex(p => p.id === postId);
                if (idx > -1) posts.splice(idx, 1);
                renderPosts();
                return;
            }

            if (e.target.closest('.like-btn')) {
                post.likedByYou = !post.likedByYou;
                if (post.likedByYou) {
                    if (!post.likes.includes('You')) post.likes.unshift('You');
                } else {
                    post.likes = post.likes.filter(n => n !== 'You');
                }
                renderPosts();
                return;
            }

            if (e.target.closest('.toggle-comments-btn')) {
                post.commentsOpen = !post.commentsOpen;
                renderPosts();
                return;
            }

            if (e.target.closest('.toggle-all-comments-btn')) {
                post.showAllComments = !post.showAllComments;
                post.commentsOpen = true;
                renderPosts();
                return;
            }

            if (e.target.closest('.post-comment-btn')) {
                const input = postCard.querySelector('.new-comment-input');
                const value = input.value.trim();
                if (!value) return;
                post.comments.push({ name: 'You', text: value });
                post.commentsOpen = true;
                post.showAllComments = true;
                renderPosts();
                return;
            }

            if (e.target.closest('.open-likes-btn')) {
                openLikes(post);
                return;
            }

            if (e.target.closest('.open-share-btn')) {
                openShare(postId);
            }
        });

        document.getElementById('close-share').addEventListener('click', () => sharePopup.classList.add('hidden'));
        document.getElementById('close-likes').addEventListener('click', () => likesPopup.classList.add('hidden'));

        document.getElementById('copy-share-link').addEventListener('click', async () => {
            try {
                await navigator.clipboard.writeText(shareLink.value);
            } catch (err) {
                shareLink.select();
                document.execCommand('copy');
            }
            sharePopup.classList.add('hidden');
        });

        document.querySelectorAll('.share-link-btn').forEach((btn) => {
            btn.addEventListener('click', () => {
                sharePopup.classList.add('hidden');
            });
        });

        window.addEventListener('click', (e) => {
            if (!e.target.closest('#profile-menu') && !e.target.closest('#profile-menu-toggle')) {
                profileMenu.classList.add('hidden');
            }
            if (e.target.id === 'share-popup') sharePopup.classList.add('hidden');
            if (e.target.id === 'likes-popup') likesPopup.classList.add('hidden');
            if (e.target.id === 'photos-popup') photosPopup.classList.add('hidden');
            if (e.target.id === 'friends-popup') friendsPopup.classList.add('hidden');
            if (!e.target.closest('.post-menu') && !e.target.closest('.post-more-btn')) {
                document.querySelectorAll('.post-menu').forEach((m) => m.classList.add('hidden'));
            }
        });

        renderReels();
        renderPosts();
        hydrateProfileFromApi();

        new Swiper('.myReels', {
            slidesPerView: 'auto',
            spaceBetween: 12,
            navigation: {
                nextEl: '.reels-next',
                prevEl: '.reels-prev'
            },
            breakpoints: {
                0: { spaceBetween: 10 },
                768: { spaceBetween: 12 },
                1024: { spaceBetween: 14 }
            }
        });
    </script>
</body>

</html>