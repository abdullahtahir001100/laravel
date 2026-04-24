<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Connect | Professional Dashboard</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="{{ asset('app.css') }}" rel='stylesheet'>
  <link
    href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap"
    rel="stylesheet">
  <style>
    :root {
      --brand-blue: #0062ff;
      --bg-main: #ffffff;
      --border-clr: #f1f5f9;
    }

    body {
      font-family: 'Plus Jakarta Sans', sans-serif;
      background-color: var(--bg-main);
      color: #0f172a;
    }

    .rounded-custom {
      border-radius: 5px !important;
    }

    .border-base {
      border: 1px solid #f1f5f9;
    }

    .nav-link {
      display: flex;
      align-items: center;
      gap: 12px;
      padding: 12px;
      font-size: 14px;
      font-weight: 500;
      color: #64748b;
      transition: all 0.2s ease;
    }

    .nav-link:hover {
      color: var(--brand-blue);
      background: #f8faff;
    }

    .nav-link.active {
      color: var(--brand-blue);
      background: #f0f7ff;
      border-right: 3px solid var(--brand-blue);
      border-radius: 0 5px 5px 0;
    }

    .nav-link svg {
      width: 20px;
      height: 20px;
      stroke-width: 2;
    }

    .no-scrollbar::-webkit-scrollbar {
      display: none;
    }

    #user-dropdown {

      transform: translateY(-10px);
      z-index: 50;
      border: 1px solid #f1f5f9;
    }

    .modal-shell {
      display: none;
      opacity: 0;
    }

    .comment-row {
      border-left: 2px solid #dbeafe;
      padding-left: 10px;
    }
  </style>
</head>

<body class="h-screen overflow-hidden">

  <x-dashboard-header />

  <div id="sidebar-backdrop" class="fixed inset-0 bg-slate-900/30 z-20 hidden lg:hidden"></div>

  <div class="flex pt-16 h-screen overflow-hidden">
    <x-dashboard-sidebar />

    <main id="main-feed" class="flex-1 h-full overflow-y-auto p-6 bg-[#fafafa]">
      <div class="max-w-6xl mx-auto grid grid-cols-1 xl:grid-cols-[minmax(0,1fr)_320px] gap-6">
        <section class="space-y-6">


          <div id="users-search-section"
            class="bg-white p-4 border border-slate-200 rounded-custom">
            <div class="flex items-center justify-between mb-3">
              <h3 class="text-sm font-bold text-slate-800">Search Users</h3>
              <a href="/users" class="text-xs font-semibold text-blue-600 hover:text-blue-700">Open
                Directory</a>
            </div>
            <input id="main-user-search" type="text"
              placeholder="Search users by name or role..."
              class="w-full bg-slate-50 p-3 rounded-custom outline-none border border-transparent focus:border-blue-100 text-sm">
            <p class="mt-2 text-xs text-slate-500">Type a name like Hina, Faraz, Noor, Ali, or Sara.
            </p>
            <div id="user-search-results" class="mt-3 space-y-2"></div>
          </div>

          <article id="discover-nav"
            class="post-card bg-white border border-slate-200 rounded-custom overflow-hidden"
            data-like-users='["Zain Ali", "Areeba Noor", "Hassan Raza", "Mina Qureshi"]'>
            <div class="p-4 flex items-center justify-between">
              <div class="flex items-center gap-3">
                <div class="w-9 h-9 bg-blue-50 rounded-custom flex items-center justify-center">
                  <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z">
                    </path>
                  </svg>
                </div>
                <div>
                  <h4 class="font-bold text-sm">Sheri Official</h4>
                  <p class="text-[10px] text-slate-400 font-semibold uppercase tracking-wider">Just
                    Now</p>
                </div>
              </div>
              <div class="relative">
                <button onclick="togglePostOptions(this)"
                  class="p-2 hover:bg-slate-50 rounded-custom transition-all">
                  <svg class="w-5 h-5 text-slate-400" fill="currentColor" viewBox="0 0 20 20">
                    <path
                      d="M6 10a2 2 0 11-4 0 2 2 0 014 0zM12 10a2 2 0 11-4 0 2 2 0 014 0zM18 10a2 2 0 11-4 0 2 2 0 014 0z">
                    </path>
                  </svg>
                </button>
                <div
                  class="post-menu hidden absolute right-0 mt-2 w-48 bg-white shadow-xl border border-slate-100 rounded-custom p-2 z-10">
                  <button
                    class="w-full text-left p-2 text-sm hover:bg-red-50 text-red-500 rounded-custom flex items-center gap-2 font-medium">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636"></path>
                    </svg>
                    Not Interested
                  </button>
                </div>
              </div>
            </div>

            <div class="px-4 pb-3">
              <p class="text-sm text-slate-600">This is a production-ready social media UI. Clean,
                fast, and responsive.</p>
            </div>

            <div
              class="bg-slate-50 h-80 w-full flex items-center justify-center border-y border-slate-100">
              <svg class="w-12 h-12 text-slate-200" fill="none" stroke="currentColor"
                viewBox="0 0 24 24">
                <path
                  d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                </path>
              </svg>
            </div>

            <div class="px-4 pt-3">
              <button onclick="openLikesModal(this)"
                class="text-xs font-semibold text-slate-500 hover:text-blue-600 transition-colors">
                <span class="likes-count">4</span> people liked this
              </button>
            </div>

            <div class="p-3 flex items-center justify-between border-b border-slate-100">
              <div class="flex gap-4">
                <button onclick="animateLike(this)"
                  class="like-btn flex items-center gap-2 text-sm font-bold text-slate-500 hover:text-blue-600 transition-all">
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path
                      d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z">
                    </path>
                  </svg>
                  Like
                </button>
                <button onclick="togglePostComments(this)"
                  class="flex items-center gap-2 text-sm font-bold text-slate-500 hover:text-blue-600 transition-all">
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path
                      d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z">
                    </path>
                  </svg>
                  Comment
                </button>
              </div>
              <button onclick="openShareModal()"
                class="flex items-center gap-2 text-sm font-bold text-slate-500 hover:text-blue-600 transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path
                    d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z">
                  </path>
                </svg>
                Share
              </button>
            </div>

            <div class="comments-block hidden px-4 pb-4 pt-3 bg-slate-50/70" data-open="false">
              <h5 class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Comments
              </h5>
              <div class="comments-list space-y-3">
                <div class="comment-row text-sm text-slate-700"><span
                    class="font-semibold">Areeba:</span> Loved this layout.</div>
                <div class="comment-row text-sm text-slate-700"><span
                    class="font-semibold">Hamza:</span> Smooth animations are on point.</div>
                <div class="comment-row text-sm text-slate-700 extra-comment hidden"><span
                    class="font-semibold">Mina:</span> Header responsiveness is great.</div>
                <div class="comment-row text-sm text-slate-700 extra-comment hidden"><span
                    class="font-semibold">Rizwan:</span> Sidebar feels polished on mobile.</div>
              </div>
              <button onclick="toggleComments(this)"
                class="mt-3 text-xs font-semibold text-blue-600 hover:text-blue-700">Show all
                comments</button>
              <div class="mt-3 flex gap-2">
                <input type="text" placeholder="Write a comment..."
                  class="comment-input flex-1 bg-white border border-slate-200 rounded-custom px-3 py-2 text-sm outline-none focus:border-blue-200">
                <button onclick="addComment(this)"
                  class="px-3 py-2 text-sm bg-blue-600 text-white rounded-custom hover:bg-blue-700 transition-colors">Post</button>
              </div>
            </div>
          </article>

          <article
            class="post-card bg-white border border-slate-200 rounded-custom overflow-hidden"
            data-like-users='["Usman Jutt", "Iqra Zahid", "Nimra Afzal"]'>
            <div class="p-4 flex items-center justify-between">
              <div class="flex items-center gap-3">
                <div class="w-9 h-9 bg-amber-50 rounded-custom flex items-center justify-center">
                  <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path
                      d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z">
                    </path>
                  </svg>
                </div>
                <div>
                  <h4 class="font-bold text-sm">Creative Pulse</h4>
                  <p class="text-[10px] text-slate-400 font-semibold uppercase tracking-wider">7
                    min ago</p>
                </div>
              </div>
              <div class="relative">
                <button onclick="togglePostOptions(this)"
                  class="p-2 hover:bg-slate-50 rounded-custom transition-all">
                  <svg class="w-5 h-5 text-slate-400" fill="currentColor" viewBox="0 0 20 20">
                    <path
                      d="M6 10a2 2 0 11-4 0 2 2 0 014 0zM12 10a2 2 0 11-4 0 2 2 0 014 0zM18 10a2 2 0 11-4 0 2 2 0 014 0z">
                    </path>
                  </svg>
                </button>
                <div
                  class="post-menu hidden absolute right-0 mt-2 w-48 bg-white shadow-xl border border-slate-100 rounded-custom p-2 z-10">
                  <button
                    class="w-full text-left p-2 text-sm hover:bg-red-50 text-red-500 rounded-custom flex items-center gap-2 font-medium">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                      viewBox="0 0 24 24">
                      <path d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636"></path>
                    </svg>
                    Not Interested
                  </button>
                </div>
              </div>
            </div>

            <div class="px-4 pb-3">
              <p class="text-sm text-slate-600">Early preview of the video campaign. Feedback in
                comments please.</p>
            </div>

            <div
              class="bg-slate-100 h-64 w-full flex items-center justify-center border-y border-slate-100">
              <svg class="w-12 h-12 text-slate-300" fill="none" stroke="currentColor"
                viewBox="0 0 24 24">
                <path
                  d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.868v4.264a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z">
                </path>
                <path d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
              </svg>
            </div>

            <div class="px-4 pt-3">
              <button onclick="openLikesModal(this)"
                class="text-xs font-semibold text-slate-500 hover:text-blue-600 transition-colors">
                <span class="likes-count">3</span> people liked this
              </button>
            </div>

            <div class="p-3 flex items-center justify-between border-b border-slate-100">
              <div class="flex gap-4">
                <button onclick="animateLike(this)"
                  class="like-btn flex items-center gap-2 text-sm font-bold text-slate-500 hover:text-blue-600 transition-all">
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path
                      d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z">
                    </path>
                  </svg>
                  Like
                </button>
                <button onclick="togglePostComments(this)"
                  class="flex items-center gap-2 text-sm font-bold text-slate-500 hover:text-blue-600 transition-all">
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path
                      d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z">
                    </path>
                  </svg>
                  Comment
                </button>
              </div>
              <button onclick="openShareModal()"
                class="flex items-center gap-2 text-sm font-bold text-slate-500 hover:text-blue-600 transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path
                    d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z">
                  </path>
                </svg>
                Share
              </button>
            </div>

            <div class="comments-block hidden px-4 pb-4 pt-3 bg-slate-50/70" data-open="false">
              <h5 class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Comments
              </h5>
              <div class="comments-list space-y-3">
                <div class="comment-row text-sm text-slate-700"><span
                    class="font-semibold">Usman:</span> Color grading looks clean.</div>
                <div class="comment-row text-sm text-slate-700"><span
                    class="font-semibold">Iqra:</span> Upload full reel soon.</div>
                <div class="comment-row text-sm text-slate-700 extra-comment hidden"><span
                    class="font-semibold">Tahir:</span> Great shot composition.</div>
              </div>
              <button onclick="toggleComments(this)"
                class="mt-3 text-xs font-semibold text-blue-600 hover:text-blue-700">Show all
                comments</button>
              <div class="mt-3 flex gap-2">
                <input type="text" placeholder="Write a comment..."
                  class="comment-input flex-1 bg-white border border-slate-200 rounded-custom px-3 py-2 text-sm outline-none focus:border-blue-200">
                <button onclick="addComment(this)"
                  class="px-3 py-2 text-sm bg-blue-600 text-white rounded-custom hover:bg-blue-700 transition-colors">Post</button>
              </div>
            </div>
          </article>
        </section>

        <aside class="hidden xl:block">
          <div class="sticky top-0 space-y-4">
            <div id="daily-pulse-card"
              class="bg-white border border-slate-200 rounded-custom p-4 shadow-sm">
              <div class="flex items-center justify-between mb-3">
                <h3 class="text-sm font-bold text-slate-800">Daily Pulse</h3>
                <button
                  class="text-xs font-semibold text-blue-600 hover:text-blue-700">View</button>
              </div>
              <p class="text-xs text-slate-500">Your network is active today. Join trending
                conversations now.</p>
              <div class="grid grid-cols-3 gap-2 mt-3">
                <div class="border border-slate-200 rounded-custom p-2 text-center">
                  <p class="text-sm font-bold text-slate-800">18</p>
                  <p class="text-[11px] text-slate-500">New Posts</p>
                </div>
                <div class="border border-slate-200 rounded-custom p-2 text-center">
                  <p class="text-sm font-bold text-slate-800">7</p>
                  <p class="text-[11px] text-slate-500">Mentions</p>
                </div>
                <div class="border border-slate-200 rounded-custom p-2 text-center">
                  <p class="text-sm font-bold text-slate-800">3</p>
                  <p class="text-[11px] text-slate-500">Invites</p>
                </div>
              </div>
            </div>

            <div id="suggested-users-card"
              class="bg-gradient-to-b from-slate-50 to-white border border-slate-200 rounded-custom p-4 shadow-sm">
              <div class="flex items-start justify-between mb-4">
                <div>
                  <h3 class="text-base font-bold text-slate-900 leading-tight">Suggested Users</h3>
                  <p class="text-xs text-slate-500 mt-1">People you may know from your network</p>
                </div>
                <button onclick="window.location.href='/users'" class="text-xs font-semibold text-blue-600 hover:text-blue-700">See
                  all</button>
              </div>

              <div class="space-y-2.5">
                <div
                  class="group border border-slate-200 bg-white rounded-custom p-2.5 hover:border-blue-200 transition-colors">
                  <div class="flex items-center justify-between gap-2">
                    <div class="flex items-center gap-3 min-w-0">
                      <img class="w-11 h-11 rounded-custom bg-slate-100 ring-2 ring-slate-100"
                        src="https://api.dicebear.com/7.x/avataaars/svg?seed=Hina" alt="Hina">
                      <div class="min-w-0">
                        <p class="text-sm font-semibold text-slate-800 truncate">Hina Ashfaq</p>
                        <p class="text-xs text-slate-500">UI Designer</p>
                        <p class="text-[11px] text-slate-400">12 mutuals</p>
                      </div>
                    </div>
                    <button
                      class="text-xs px-2.5 py-1.5 rounded-custom border border-slate-300 text-slate-700 hover:border-blue-500 hover:text-blue-600 transition-colors">Follow</button>
                  </div>
                </div>

                <div
                  class="group border border-slate-200 bg-white rounded-custom p-2.5 hover:border-blue-200 transition-colors">
                  <div class="flex items-center justify-between gap-2">
                    <div class="flex items-center gap-3 min-w-0">
                      <img class="w-11 h-11 rounded-custom bg-slate-100 ring-2 ring-slate-100"
                        src="https://api.dicebear.com/7.x/avataaars/svg?seed=Faraz"
                        alt="Faraz">
                      <div class="min-w-0">
                        <p class="text-sm font-semibold text-slate-800 truncate">Faraz Ahmed</p>
                        <p class="text-xs text-slate-500">Laravel Dev</p>
                        <p class="text-[11px] text-slate-400">8 mutuals</p>
                      </div>
                    </div>
                    <button
                      class="text-xs px-2.5 py-1.5 rounded-custom border border-slate-300 text-slate-700 hover:border-blue-500 hover:text-blue-600 transition-colors">Follow</button>
                  </div>
                </div>

                <div
                  class="group border border-slate-200 bg-white rounded-custom p-2.5 hover:border-blue-200 transition-colors">
                  <div class="flex items-center justify-between gap-2">
                    <div class="flex items-center gap-3 min-w-0">
                      <img class="w-11 h-11 rounded-custom bg-slate-100 ring-2 ring-slate-100"
                        src="https://api.dicebear.com/7.x/avataaars/svg?seed=Noor" alt="Noor">
                      <div class="min-w-0">
                        <p class="text-sm font-semibold text-slate-800 truncate">Noor Fatima</p>
                        <p class="text-xs text-slate-500">Content Creator</p>
                        <p class="text-[11px] text-slate-400">5 mutuals</p>
                      </div>
                    </div>
                    <button
                      class="text-xs px-2.5 py-1.5 rounded-custom border border-slate-300 text-slate-700 hover:border-blue-500 hover:text-blue-600 transition-colors">Follow</button>
                  </div>
                </div>
              </div>
            </div>

            <div class="bg-white border border-slate-200 rounded-custom p-4 shadow-sm">
              <div class="flex items-center justify-between mb-3">
                <h4 class="text-sm font-bold text-slate-800">Quick Add</h4>
                <button  onclick="window.location.href='/users'" class="text-xs text-blue-600 font-semibold hover:text-blue-700">Add
                  More</button>
              </div>
              <div class="space-y-2">
                <button
                  class="w-full flex items-center justify-between rounded-custom px-3 py-2.5 text-sm border border-slate-200 hover:border-blue-300 hover:bg-blue-50/40 transition-colors">
                  <span class="text-slate-700 font-medium">Ali Hassan</span>
                  <span class="text-blue-600 font-semibold">+ Add</span>
                </button>
                <button
                  class="w-full flex items-center justify-between rounded-custom px-3 py-2.5 text-sm border border-slate-200 hover:border-blue-300 hover:bg-blue-50/40 transition-colors">
                  <span class="text-slate-700 font-medium">Sara Khan</span>
                  <span class="text-blue-600 font-semibold">+ Add</span>
                </button>
              </div>
            </div>
          </div>
        </aside>
      </div>
    </main>
  </div>

  <div id="likes-modal" class="modal-shell fixed inset-0 z-50 bg-slate-900/45 p-4">
    <div class="h-full w-full flex items-center justify-center">
      <div class="modal-panel bg-white rounded-custom w-full max-w-sm p-4 border border-slate-200">
        <div class="flex items-center justify-between mb-3">
          <h4 class="font-bold text-slate-800">Liked By</h4>
          <button onclick="closeModal('likes-modal')"
            class="p-1 rounded-custom hover:bg-slate-100">
            <svg class="w-5 h-5 text-slate-500" fill="none" stroke="currentColor"
              viewBox="0 0 24 24">
              <path d="M6 18L18 6M6 6l12 12" stroke-width="2" stroke-linecap="round"
                stroke-linejoin="round"></path>
            </svg>
          </button>
        </div>
        <ul id="likes-list" class="space-y-2 text-sm text-slate-700"></ul>
      </div>
    </div>
  </div>

  <div id="share-modal" class="modal-shell fixed inset-0 z-50 bg-slate-900/45 p-4">
    <div class="h-full w-full flex items-center justify-center">
      <div class="modal-panel bg-white rounded-custom w-full max-w-md p-4 border border-slate-200">
        <div class="flex items-center justify-between mb-3">
          <h4 class="font-bold text-slate-800">Share Post</h4>
          <button onclick="closeModal('share-modal')"
            class="p-1 rounded-custom hover:bg-slate-100">
            <svg class="w-5 h-5 text-slate-500" fill="none" stroke="currentColor"
              viewBox="0 0 24 24">
              <path d="M6 18L18 6M6 6l12 12" stroke-width="2" stroke-linecap="round"
                stroke-linejoin="round"></path>
            </svg>
          </button>
        </div>
        <input id="share-link" type="text" value="https://connect.app/post/7842"
          class="w-full border border-slate-200 rounded-custom p-3 text-sm outline-none focus:border-blue-200 mb-3">
        <div class="grid grid-cols-3 gap-2 mb-3">
          <button class="text-xs bg-slate-100 rounded-custom py-2 font-semibold">WhatsApp</button>
          <button class="text-xs bg-slate-100 rounded-custom py-2 font-semibold">Facebook</button>
          <button class="text-xs bg-slate-100 rounded-custom py-2 font-semibold">Email</button>
        </div>
        <button onclick="sharePost()"
          class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 rounded-custom text-sm font-semibold">Copy
          Link</button>
      </div>
    </div>
  </div>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/animejs/3.2.1/anime.min.js"></script>
  <script src="https://cdn.jsdelivr.net/gh/studio-freight/lenis@1.0.19/bundled/lenis.min.js"></script>
  <script src="{{ asset('app.js') }}"></script>

</body>

</html>
