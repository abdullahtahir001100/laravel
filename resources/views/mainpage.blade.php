<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Connect | Dynamic Main Feed</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="{{ asset('app.css') }}" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <style>
    :root {
      --brand-blue: #0062ff;
      --bg-main: #f6f8fc;
      --border-clr: #e5eef9;
    }

    body {
      font-family: 'Plus Jakarta Sans', sans-serif;
      background:
        radial-gradient(circle at top left, rgba(0, 98, 255, 0.08), transparent 30%),
        linear-gradient(180deg, #ffffff 0%, #f6f8fc 100%);
      color: #0f172a;
    }

    .rounded-custom { border-radius: 5px !important; }
    .soft-card {
      background: rgba(255, 255, 255, 0.92);
      backdrop-filter: blur(10px);
      border: 1px solid rgba(226, 232, 240, 0.85);
      box-shadow: 0 10px 30px rgba(15, 23, 42, 0.05);
    }
    .comment-row { border-left: 2px solid #dbeafe; padding-left: 10px; }
    .modal-shell { display: none; opacity: 0; }
    .scrollbar-hide::-webkit-scrollbar { display: none; }
    .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
    .feed-media { aspect-ratio: 16 / 10; object-fit: cover; }
    .post-badge { letter-spacing: 0.12em; }
  </style>
</head>
<body class="h-screen overflow-hidden">
  <x-dashboard-header />

  <div id="sidebar-backdrop" class="fixed inset-0 bg-slate-900/30 z-20 hidden lg:hidden"></div>

  <div class="flex pt-16 h-screen overflow-hidden">
    <x-dashboard-sidebar />

    <main id="main-feed" class="flex-1 h-full overflow-y-auto p-4 md:p-6 scrollbar-hide">
      <div class="max-w-7xl mx-auto grid grid-cols-1 xl:grid-cols-[minmax(0,1fr)_340px] gap-6">
        <section class="space-y-6 min-w-0">
          <div class="soft-card p-4 rounded-custom">
            <div class="flex items-center justify-between mb-3">
              <div>
                <h3 class="text-sm font-bold text-slate-900">Search Users</h3>
                <p class="text-xs text-slate-500">Find people, then quick add or follow immediately.</p>
              </div>
              <a href="/users" class="text-xs font-semibold text-blue-600 hover:text-blue-700">Open directory</a>
            </div>
            <input id="main-user-search" type="text" placeholder="Search users by name, username, or email"
              class="w-full bg-slate-50 p-3 rounded-custom outline-none border border-transparent focus:border-blue-200 text-sm">
            <div id="user-search-results" class="mt-3 space-y-2"></div>
          </div>

          <div id="feed-status" class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-400 px-1">Loading feed...</div>
          <div id="main-feed-list" class="space-y-5"></div>
        </section>

        <aside class="space-y-4">
          <div class="soft-card rounded-custom p-4">
            <div class="flex items-start justify-between mb-4">
              <div>
                <h3 class="text-base font-bold text-slate-900">Suggested Users</h3>
                <p class="text-xs text-slate-500 mt-1">Quick add people from your network.</p>
              </div>
              <button onclick="loadSuggestions()" class="text-xs font-semibold text-blue-600 hover:text-blue-700">Refresh</button>
            </div>
            <div id="suggested-users" class="space-y-2.5"></div>
          </div>

          <div class="soft-card rounded-custom p-4">
            <div class="flex items-center justify-between mb-3">
              <h4 class="text-sm font-bold text-slate-800">Quick Add</h4>
              <button onclick="loadSuggestions()" class="text-xs text-blue-600 font-semibold hover:text-blue-700">Sync</button>
            </div>
            <div id="quick-add-users" class="space-y-2"></div>
          </div>

          <div class="soft-card rounded-custom p-4 shadow-sm">
            <div class="flex items-center justify-between mb-3">
              <h4 class="text-sm font-bold text-slate-800">Daily Pulse</h4>
              <button class="text-xs font-semibold text-blue-600 hover:text-blue-700">View</button>
            </div>
            <p class="text-xs text-slate-500">Your network is active today. Join trending conversations now.</p>
            <div class="grid grid-cols-3 gap-2 mt-3">
              <div class="border border-slate-200 rounded-custom p-2 text-center bg-white"><p class="text-sm font-bold text-slate-800">18</p><p class="text-[11px] text-slate-500">New Posts</p></div>
              <div class="border border-slate-200 rounded-custom p-2 text-center bg-white"><p class="text-sm font-bold text-slate-800">7</p><p class="text-[11px] text-slate-500">Mentions</p></div>
              <div class="border border-slate-200 rounded-custom p-2 text-center bg-white"><p class="text-sm font-bold text-slate-800">3</p><p class="text-[11px] text-slate-500">Invites</p></div>
            </div>
          </div>
        </aside>
      </div>
    </main>
  </div>

  <div id="likes-modal" class="modal-shell fixed inset-0 z-50 bg-slate-900/45 p-4">
    <div class="h-full w-full flex items-center justify-center">
      <div class="bg-white rounded-custom w-full max-w-sm p-4 border border-slate-200 max-h-[calc(100vh-2rem)] overflow-hidden">
        <div class="flex items-center justify-between mb-3">
          <h4 class="font-bold text-slate-800">Liked By</h4>
          <button onclick="closeModal('likes-modal')" class="p-1 rounded-custom hover:bg-slate-100">
            <svg class="w-5 h-5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg>
          </button>
        </div>
        <ul id="likes-list" class="space-y-2 text-sm text-slate-700 overflow-y-auto max-h-[60vh]"></ul>
      </div>
    </div>
  </div>

  <div id="comments-modal" class="modal-shell fixed inset-0 z-50 bg-slate-900/45 p-4">
    <div class="h-full w-full flex items-center justify-center">
      <div class="bg-white rounded-custom w-full max-w-xl p-4 border border-slate-200 flex flex-col max-h-[calc(100vh-2rem)] overflow-hidden">
        <div class="flex items-center justify-between mb-3">
          <div>
            <h4 class="font-bold text-slate-800">Comments</h4>
            <p id="comments-title" class="text-xs text-slate-500"></p>
          </div>
          <button onclick="closeModal('comments-modal')" class="p-1 rounded-custom hover:bg-slate-100">
            <svg class="w-5 h-5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg>
          </button>
        </div>
        <div id="comments-list" class="space-y-3 overflow-y-auto flex-1 pr-1"></div>
        <div class="mt-4 flex gap-2">
          <input id="comment-input" type="text" placeholder="Write a comment..." class="flex-1 bg-slate-50 border border-slate-200 rounded-custom px-3 py-2 text-sm outline-none focus:border-blue-200">
          <button onclick="submitComment()" class="px-4 py-2 text-sm bg-blue-600 text-white rounded-custom hover:bg-blue-700 transition-colors">Post</button>
        </div>
      </div>
    </div>
  </div>

  <div id="share-modal" class="modal-shell fixed inset-0 z-50 bg-slate-900/45 p-4">
    <div class="h-full w-full flex items-center justify-center">
      <div class="bg-white rounded-custom w-full max-w-md p-4 border border-slate-200 max-h-[calc(100vh-2rem)] overflow-hidden">
        <div class="flex items-center justify-between mb-3">
          <h4 class="font-bold text-slate-800">Share Post</h4>
          <button onclick="closeModal('share-modal')" class="p-1 rounded-custom hover:bg-slate-100">
            <svg class="w-5 h-5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg>
          </button>
        </div>
        <input id="share-link" type="text" value="" class="w-full border border-slate-200 rounded-custom p-3 text-sm outline-none focus:border-blue-200 mb-3">
        <div class="grid grid-cols-3 gap-2 mb-3">
          <button class="text-xs bg-slate-100 rounded-custom py-2 font-semibold">WhatsApp</button>
          <button class="text-xs bg-slate-100 rounded-custom py-2 font-semibold">Facebook</button>
          <button class="text-xs bg-slate-100 rounded-custom py-2 font-semibold">Email</button>
        </div>
        <button onclick="copyShareLink()" class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 rounded-custom text-sm font-semibold">Copy Link</button>
      </div>
    </div>
  </div>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/animejs/3.2.1/anime.min.js"></script>
  <script src="https://cdn.jsdelivr.net/gh/studio-freight/lenis@1.0.19/bundled/lenis.min.js"></script>
  <script>
    const CSRF_TOKEN = '{{ csrf_token() }}';
    const state = { feed: [], suggestions: [], comments: [], activeItem: null, feedById: new Map() };
    let searchTimer = null;

    const escapeHtml = (value) => String(value ?? '')
      .replace(/&/g, '&amp;')
      .replace(/</g, '&lt;')
      .replace(/>/g, '&gt;')
      .replace(/\"/g, '&quot;')
      .replace(/'/g, '&#039;');

    function closeModal(id) {
      const modal = document.getElementById(id);
      if (!modal) return;
      gsap.to(modal, { opacity: 0, duration: 0.15, onComplete: () => { modal.style.display = 'none'; } });
    }

    function openModal(id) {
      const modal = document.getElementById(id);
      if (!modal) return;
      modal.style.display = 'block';
      gsap.fromTo(modal, { opacity: 0 }, { opacity: 1, duration: 0.15 });
    }

    function followButtonClass(status) {
      if (status === 'requested') return 'border-amber-200 bg-amber-50 text-amber-700';
      if (status === 'accepted') return 'border-emerald-200 bg-emerald-50 text-emerald-700';
      return 'border-slate-300 bg-white text-slate-700';
    }

    function renderFollowButton(user) {
      const status = user.followStatus || 'none';
      const followId = user.followId || null;
      if (user.isFriend) {
        return `<button onclick="window.location.href='/messages'" class="text-xs px-2.5 py-1.5 rounded-custom border border-blue-300 bg-blue-50 text-blue-700 transition-colors hover:bg-blue-100">Message</button>`;
      }
      if (status === 'requested' && followId) {
        return `<button data-user-id="${user.id}" data-follow-id="${followId}" data-follow-status="${status}" onclick="cancelFollow(${user.id}, ${followId}, this)" class="text-xs px-2.5 py-1.5 rounded-custom border transition-colors ${followButtonClass(status)}">Cancel</button>`;
      }
      return `<button data-user-id="${user.id}" data-follow-id="${followId}" data-follow-status="${status}" onclick="toggleFollow(${user.id}, this)" class="text-xs px-2.5 py-1.5 rounded-custom border transition-colors ${followButtonClass(status)}">${escapeHtml(user.followLabel || 'Follow')}</button>`;
    }

    function renderUserRow(user, compact = false) {
      const avatar = user.avatarUrl || `https://api.dicebear.com/7.x/avataaars/svg?seed=${encodeURIComponent(user.displayName || user.id)}`;
      const headline = user.headline || user.username || 'Suggested connection';
      return `
        <div class="border border-slate-200 bg-white rounded-custom p-2.5 hover:border-blue-200 transition-colors">
          <div class="flex items-center justify-between gap-2">
            <a href="${user.profileUrl}" class="flex items-center gap-3 min-w-0">
              <img class="w-11 h-11 rounded-custom bg-slate-100 ring-2 ring-slate-100 object-cover" src="${avatar}" alt="${escapeHtml(user.displayName)}">
              <div class="min-w-0">
                <p class="text-sm font-semibold text-slate-800 truncate">${escapeHtml(user.displayName)}</p>
                <p class="text-xs text-slate-500 truncate">${escapeHtml(headline)}</p>
                ${compact ? '' : '<p class="text-[11px] text-slate-400">Quick add</p>'}
              </div>
            </a>
            ${renderFollowButton(user)}
          </div>
        </div>`;
    }

    function renderFeedItem(item) {
      const media = item.mediaUrl || 'https://via.placeholder.com/1200x675?text=No+Media';
      const isVideo = (item.mediaType || '').toLowerCase().includes('video') || ['reel', 'live'].includes(item.type);
      const badge = item.type === 'reel' ? 'Reel' : item.type === 'live' ? 'Live' : 'Post';
      const tagList = Array.isArray(item.tags) ? item.tags.slice(0, 3).map(tag => `<span class="px-2 py-1 rounded-full bg-slate-100 text-[11px] font-semibold text-slate-600">#${escapeHtml(tag)}</span>`).join('') : '';
      const mediaMarkup = isVideo
        ? `<video class="feed-media w-full bg-black" autoplay muted loop playsinline><source src="${media}" type="video/mp4"></video>`
        : `<img class="feed-media w-full bg-slate-100" src="${media}" alt="${escapeHtml(item.title || item.authorName)}">`;
      const likesCount = Number(item.likesCount || 0);
      const commentsCount = Number(item.commentsCount || 0);
      const followLabel = item.authorFollowLabel || 'Follow';
      const followStatus = item.authorFollowStatus || 'none';
      const followId = item.authorFollowId;
      const isFriend = item.isFriend;

      let followButtonHtml = '';
      if (isFriend) {
        followButtonHtml = `<button onclick="window.location.href='/messages'" class="text-xs px-2.5 py-1.5 rounded-custom border border-blue-300 bg-blue-50 text-blue-700 transition-colors hover:bg-blue-100">Message</button>`;
      } else if (followStatus === 'requested' && followId) {
        followButtonHtml = `<button data-user-id="${item.userId}" data-follow-id="${followId}" data-follow-status="${followStatus}" onclick="cancelFollow(${item.userId}, ${followId}, this)" class="text-xs px-2.5 py-1.5 rounded-custom border transition-colors ${followButtonClass(followStatus)}">Cancel</button>`;
      } else {
        followButtonHtml = `<button data-user-id="${item.userId}" data-follow-id="${followId}" data-follow-status="${followStatus}" onclick="toggleFollow(${item.userId}, this)" class="text-xs px-2.5 py-1.5 rounded-custom border transition-colors ${followButtonClass(followStatus)}">${escapeHtml(followLabel)}</button>`;
      }

      return `
        <article class="soft-card post-card bg-white border border-slate-200 rounded-custom overflow-hidden" data-item-id="${item.id}" data-user-id="${item.userId}">
          <div class="p-4 flex items-center justify-between gap-3">
            <a href="${item.authorProfileUrl}" class="flex items-center gap-3 min-w-0">
              <img class="w-10 h-10 rounded-custom object-cover bg-slate-100" src="${item.authorAvatarUrl || 'https://via.placeholder.com/48'}" alt="${escapeHtml(item.authorName)}">
              <div class="min-w-0">
                <h4 class="font-bold text-sm text-slate-900 truncate">${escapeHtml(item.authorName)}</h4>
                <p class="text-[10px] text-slate-400 font-semibold uppercase tracking-wider">${escapeHtml(item.publishedAt || 'Just now')}</p>
              </div>
            </a>
            <div class="flex items-center gap-2">
              <span class="px-2 py-1 rounded-full bg-slate-100 text-[10px] font-bold uppercase text-slate-500 post-badge">${badge}</span>
              ${followButtonHtml}
            </div>
          </div>

          <div class="px-4 pb-3">
            <p class="text-sm text-slate-600 leading-6">${escapeHtml(item.description || item.title || '')}</p>
            ${item.subtitle ? `<p class="text-xs text-slate-400 mt-1">${escapeHtml(item.subtitle)}</p>` : ''}
            ${tagList ? `<div class="flex flex-wrap gap-2 mt-3">${tagList}</div>` : ''}
          </div>

          ${mediaMarkup}

          <div class="px-4 pt-3 flex items-center justify-between gap-3">
            <button onclick="openLikesModal(${item.id})" class="text-xs font-semibold text-slate-500 hover:text-blue-600 transition-colors">
              <span class="likes-count" data-likes-count="${item.id}">${likesCount}</span> people liked this
            </button>
            <span class="text-[11px] text-slate-400" data-comments-count="${item.id}">${commentsCount} comments</span>
          </div>

          <div class="p-3 flex items-center justify-between border-b border-slate-100">
            <div class="flex gap-4 flex-wrap">
              <button onclick="toggleLike(${item.id}, this)" data-liked="${item.likedByMe ? '1' : '0'}" class="like-btn flex items-center gap-2 text-sm font-bold transition-all ${item.likedByMe ? 'text-blue-600' : 'text-slate-500 hover:text-blue-600'}">
                <svg class="w-5 h-5" fill="${item.likedByMe ? 'currentColor' : 'none'}" stroke="currentColor" viewBox="0 0 24 24"><path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                <span>Like</span>
              </button>
              <button onclick="openCommentsModal(${item.id})" class="flex items-center gap-2 text-sm font-bold text-slate-500 hover:text-blue-600 transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                Comment
              </button>
            </div>
            <button onclick="openShareModal(${item.id})" class="flex items-center gap-2 text-sm font-bold text-slate-500 hover:text-blue-600 transition-all">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"></path></svg>
              Share
            </button>
          </div>

          <div class="px-4 py-3 bg-slate-50/70 flex items-center justify-between gap-2 flex-wrap">
            <button onclick="toggleNotInterested(${item.id}, this)" class="text-xs font-semibold text-slate-500 hover:text-slate-700">Not interested</button>
            <span class="text-xs text-slate-400">${badge} from ${escapeHtml(item.authorName)}</span>
          </div>
        </article>`;
    }

    function renderFeed(items) {
      const container = document.getElementById('main-feed-list');
      const status = document.getElementById('feed-status');
      if (!container) return;

      if (!items.length) {
        container.innerHTML = `<div class="soft-card rounded-custom p-8 text-center"><h3 class="text-lg font-bold text-slate-900">No content yet</h3><p class="text-sm text-slate-500 mt-2">When posts, reels, or live streams exist, they will show here automatically.</p></div>`;
        if (status) status.textContent = 'Feed ready';
        return;
      }

      container.innerHTML = items.map(renderFeedItem).join('');
      if (status) status.textContent = `${items.length} live items loaded`;
    }

    function renderUsers(targetId, users) {
      const container = document.getElementById(targetId);
      if (!container) return;
      if (!users.length) {
        container.innerHTML = '<div class="text-sm text-slate-500 py-3">No users found.</div>';
        return;
      }
      container.innerHTML = users.map(user => renderUserRow(user, targetId === 'user-search-results')).join('');
    }

    async function apiFetch(url, options = {}) {
      const response = await fetch(url, { headers: { Accept: 'application/json', ...(options.headers || {}) }, ...options });
      if (!response.ok) throw new Error(`Request failed: ${response.status}`);
      return response.json();
    }

    async function loadFeed() {
      const data = await apiFetch('/api/feed?scope=main');
      const items = data.data || [];
      state.feed = items;
      state.feedById = new Map(items.map(item => [item.id, item]));
      renderFeed(items);
    }

    async function loadSuggestions() {
      const data = await apiFetch('/api/users/suggestions');
      const users = data.users || [];
      state.suggestions = users;
      renderUsers('suggested-users', users);
      renderUsers('quick-add-users', users.slice(0, 4));
      if (!document.getElementById('main-user-search')?.value.trim()) {
        renderUsers('user-search-results', users.slice(0, 4));
      }
    }

    function scheduleSearch(query) {
      clearTimeout(searchTimer);
      searchTimer = setTimeout(() => {
        if (!query.trim()) {
          renderUsers('user-search-results', state.suggestions.slice(0, 4));
          return;
        }
        apiFetch(`/api/users/search?q=${encodeURIComponent(query)}`)
          .then((data) => renderUsers('user-search-results', data.users || []))
          .catch(() => renderUsers('user-search-results', []));
      }, 220);
    }

    function updateFollowButtons(userId, status, label, followId) {
      document.querySelectorAll(`button[data-user-id="${userId}"]`).forEach((button) => {
        button.dataset.followStatus = status;
        button.dataset.followId = followId || '';
        button.textContent = label;
        button.className = `text-xs px-2.5 py-1.5 rounded-custom border transition-colors ${followButtonClass(status)}`;
      });
    }

    async function cancelFollow(userId, followId, button) {
      try {
        const response = await fetch(`/api/follows/${followId}/cancel`, {
          method: 'DELETE',
          headers: { 'X-CSRF-TOKEN': CSRF_TOKEN },
        });

        if (response.ok) {
          updateFollowButtons(userId, 'none', 'Follow', null);
        }
      } catch (error) {
        console.error('Cancel follow failed', error);
      }
    }

    async function toggleFollow(userId, button) {
      const currentStatus = button?.dataset?.followStatus || 'none';
      try {
        const data = await apiFetch(`/api/users/${userId}/follow`, {
          method: 'POST',
          headers: { 'X-CSRF-TOKEN': CSRF_TOKEN, 'Content-Type': 'application/json' },
          body: JSON.stringify({})
        });
        updateFollowButtons(userId, data.status || 'none', data.buttonLabel || 'Follow', data.followId || null);
      } catch (error) {
        console.error('Follow toggle failed', error);
        updateFollowButtons(userId, currentStatus, button?.textContent || 'Follow');
      }
    }

    async function toggleLike(itemId, button) {
      try {
        const data = await apiFetch(`/api/content-items/${itemId}/like`, {
          method: 'POST',
          headers: { 'X-CSRF-TOKEN': CSRF_TOKEN, 'Content-Type': 'application/json' },
          body: JSON.stringify({})
        });
        const likeCount = document.querySelector(`[data-likes-count="${itemId}"]`);
        if (likeCount) likeCount.textContent = String(data.likesCount ?? 0);
        button.dataset.liked = data.liked ? '1' : '0';
        button.classList.toggle('text-blue-600', !!data.liked);
        button.classList.toggle('text-slate-500', !data.liked);
        const icon = button.querySelector('svg');
        if (icon) icon.setAttribute('fill', data.liked ? 'currentColor' : 'none');
      } catch (error) {
        console.error('Like toggle failed', error);
      }
    }

    async function toggleNotInterested(itemId, button) {
      try {
        await apiFetch(`/api/content-items/${itemId}/not-interested`, {
          method: 'POST',
          headers: { 'X-CSRF-TOKEN': CSRF_TOKEN, 'Content-Type': 'application/json' },
          body: JSON.stringify({})
        });
        button.closest('.post-card')?.remove();
      } catch (error) {
        console.error('Not interested failed', error);
      }
    }

    async function openLikesModal(itemId) {
      const item = state.feedById.get(itemId);
      const list = document.getElementById('likes-list');
      if (!list) return;
      const likes = item?.recentLikes || [];
      if (!likes.length) {
        list.innerHTML = '<li class="text-slate-500">No likes yet.</li>';
      } else {
        list.innerHTML = likes.map((user) => `
          <li class="flex items-center gap-3 p-2 rounded-custom hover:bg-slate-50">
            <img src="${user.avatarUrl || 'https://via.placeholder.com/40'}" class="w-9 h-9 rounded-custom object-cover" alt="${escapeHtml(user.displayName)}">
            <a href="${user.profileUrl}" class="font-semibold text-slate-700 hover:text-blue-600">${escapeHtml(user.displayName)}</a>
          </li>`).join('');
      }
      openModal('likes-modal');
    }

    async function openCommentsModal(itemId) {
      state.activeItem = itemId;
      const item = state.feedById.get(itemId);
      document.getElementById('comments-title').textContent = item ? `${item.authorName} · ${item.title || item.subtitle || item.type}` : '';
      await loadComments(itemId);
      openModal('comments-modal');
    }

    async function loadComments(itemId) {
      const data = await apiFetch(`/api/content-items/${itemId}/comments`);
      state.comments = data.comments || [];
      const list = document.getElementById('comments-list');
      if (!list) return;
      if (!state.comments.length) {
        list.innerHTML = '<div class="text-sm text-slate-500">No comments yet.</div>';
        return;
      }
      list.innerHTML = state.comments.map((comment) => `
        <div class="comment-row text-sm text-slate-700">
          <div class="flex items-center gap-2 mb-1">
            <a href="${comment.user?.profileUrl || '#'}" class="font-semibold text-slate-900 hover:text-blue-600">${escapeHtml(comment.user?.displayName || 'User')}</a>
            <span class="text-[11px] text-slate-400">${escapeHtml(comment.createdAt || '')}</span>
          </div>
          <div>${escapeHtml(comment.body || '')}</div>
        </div>`).join('');
    }

    async function submitComment() {
      const input = document.getElementById('comment-input');
      const body = input?.value.trim();
      if (!body || !state.activeItem) return;

      const payload = await apiFetch(`/api/content-items/${state.activeItem}/comments`, {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': CSRF_TOKEN, 'Content-Type': 'application/json' },
        body: JSON.stringify({ body })
      });

      input.value = '';
      const item = state.feedById.get(state.activeItem);
      if (item) {
        item.commentsCount = Number(payload.commentsCount ?? (item.commentsCount || 0) + 1);
        const count = document.querySelector(`[data-comments-count="${state.activeItem}"]`);
        if (count) count.textContent = `${item.commentsCount} comments`;
      }
      await loadComments(state.activeItem);
    }

    function openShareModal(itemId) {
      state.activeItem = itemId;
      const linkInput = document.getElementById('share-link');
      if (linkInput) linkInput.value = `${window.location.origin}/facebook?item=${itemId}`;
      openModal('share-modal');
    }

    function copyShareLink() {
      const input = document.getElementById('share-link');
      if (!input) return;
      navigator.clipboard.writeText(input.value || window.location.href);
    }

    document.getElementById('main-user-search')?.addEventListener('input', (event) => {
      scheduleSearch(event.target.value || '');
    });

    document.addEventListener('click', (event) => {
      if (event.target.classList?.contains('modal-shell')) {
        closeModal(event.target.id);
      }
    });

    window.addEventListener('load', async () => {
      try {
        await Promise.all([loadFeed(), loadSuggestions()]);
      } catch (error) {
        console.error('Failed to load main page content', error);
        const status = document.getElementById('feed-status');
        if (status) status.textContent = 'Failed to load content';
      }
    });
  </script>
  <script src="{{ asset('app.js') }}"></script>
</body>
</html>
