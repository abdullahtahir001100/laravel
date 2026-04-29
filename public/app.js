
// let scrollTimeout;

// window.addEventListener("scroll", () => {
//   document.body.classList.add("scrolling");

//   clearTimeout(scrollTimeout);

//   scrollTimeout = setTimeout(() => {
//     document.body.classList.remove("scrolling");
//   }, 800); // hide after 0.8s idle
// });
//   const lenis = new Lenis({
//   duration: 1.2,
//   smooth: true
// });

// function raf(time) {
//   lenis.raf(time);
//   requestAnimationFrame(raf);
// }

// requestAnimationFrame(raf);
  // <script>

    const sidebar = document.getElementById('sidebar');
    const sidebarManagedByHeader = Boolean(window.__connectSidebarManagedByHeader);
    let sidebarBackdrop = document.getElementById('sidebar-backdrop');
    if (!sidebarManagedByHeader && !sidebarBackdrop && sidebar) {
      sidebarBackdrop = document.createElement('div');
      sidebarBackdrop.id = 'sidebar-backdrop';
      sidebarBackdrop.className = 'fixed inset-0 bg-slate-900/30 z-20 hidden lg:hidden';
      document.body.appendChild(sidebarBackdrop);
    }
    const mobileSidebarToggle = sidebarManagedByHeader ? null : document.getElementById('mobile-sidebar-toggle');
    const userMenuButton = document.getElementById('user-menu-button');
    const userDropdown = document.getElementById('user-dropdown');
    const chevronIcon = document.getElementById('chevron-icon');
    const headerSearchInput = document.getElementById('header-content-search');
    const headerSearchResults = document.getElementById('header-search-results');
    const mainUserSearch = document.getElementById('main-user-search');
    const userSearchResults = document.getElementById('user-search-results');

    // Debug logging
    console.log('DOM Elements loaded:', {
      headerSearchInput: !!headerSearchInput,
      headerSearchResults: !!headerSearchResults,
      mainUserSearch: !!mainUserSearch,
      userSearchResults: !!userSearchResults
    });

    let isMenuOpen = false;
    let sidebarOpen = false;
    let headerContentItems = [];
    let headerContentLoadedAt = 0;
    let directoryUsers = [];

    function escapeHtml(value) {
      return String(value || '')
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#39;');
    }

    function routeForContentType(type) {
      if (type === 'reel') return '/reels';
      if (type === 'live') return '/live';
      return '/facebook';
    }

    async function fetchHeaderContentItems(forceRefresh = false) {
      const now = Date.now();
      if (!forceRefresh && headerContentItems.length && (now - headerContentLoadedAt) < 60000) {
        console.log('Using cached items:', headerContentItems);
        return headerContentItems;
      }

      try {
        // First try to get user's own content for quick search
        console.log('Fetching user content from /api/content-items');
        const userResponse = await fetch('/api/content-items', {
          headers: { Accept: 'application/json' }
        });
        
        if (userResponse.ok) {
          const userData = await userResponse.json();
          const userItems = userData.items || [];
          console.log('User content items:', userItems);
          
          headerContentItems = userItems.map((item) => ({
            id: item.id,
            label: item.title || 'Untitled',
            description: item.description || '',
            tags: Array.isArray(item.tags) ? item.tags : (typeof item.tags === 'string' ? [item.tags] : []),
            contentType: item.type || 'post',
            target: routeForContentType(item.type || 'post')
          }));
          
          console.log('Transformed user items:', headerContentItems);
        }
        
        headerContentLoadedAt = now;
      } catch (error) {
        console.error('Failed to fetch content items:', error);
      }

      return headerContentItems;
    }

    async function renderHeaderSearchResults(query) {
      if (!headerSearchResults) {
        console.warn('headerSearchResults element not found');
        return;
      }

      const q = query.trim().toLowerCase();
      if (!q) {
        headerSearchResults.classList.add('hidden');
        headerSearchResults.innerHTML = '';
        return;
      }

      headerSearchResults.classList.remove('hidden');
      headerSearchResults.innerHTML = '<div class="px-3 py-2 text-sm text-slate-500">Searching...</div>';

      try {
        const items = await fetchHeaderContentItems();
        console.log('Search query:', q);
        console.log('Items available:', items.length);
        
        const matched = items.filter((item) => {
          const title = (item.label || '').toLowerCase();
          const description = (item.description || '').toLowerCase();
          const tagsArray = Array.isArray(item.tags) ? item.tags : [];
          const tagsText = tagsArray.length > 0 ? tagsArray.join(' ').toLowerCase() : '';
          const typeText = (item.contentType || '').toLowerCase();
          const matches = title.includes(q) || description.includes(q) || tagsText.includes(q) || typeText.includes(q);
          return matches;
        }).slice(0, 8);

        console.log('Matched results:', matched);

        if (!matched.length) {
          headerSearchResults.innerHTML = '<div class="px-3 py-2 text-sm text-slate-500">No matching content found</div>';
          return;
        }

        const html = matched.map((item) => {
          const tagsHtml = (item.tags && item.tags.length) ? `<div class="flex flex-wrap gap-1 mt-2">${item.tags.slice(0, 4).map((tag) => `<button class="tag-search-btn text-[11px] px-2 py-1 bg-blue-50 text-blue-600 rounded-sm hover:bg-blue-100 transition-colors" data-tag="${escapeHtml(tag)}" type="button">#${escapeHtml(tag)}</button>`).join('')}</div>` : '';
          return `<button data-target="${item.target}" data-item-id="${item.id}" data-content-type="${item.contentType}" class="header-result-btn w-full text-left px-3 py-2 rounded-custom hover:bg-slate-50 transition-colors block border-b border-slate-100" type="button">
            <p class="text-sm font-semibold text-slate-700">${escapeHtml(item.label)}</p>
            <p class="text-[11px] text-slate-500 capitalize">${escapeHtml(item.contentType)}</p>
            ${tagsHtml}
          </button>`;
        }).join('');
        
        headerSearchResults.innerHTML = html;
        console.log('Rendered HTML:', html);

        // Add click handlers for tag search buttons
        headerSearchResults.querySelectorAll('.tag-search-btn').forEach(btn => {
          btn.addEventListener('click', (e) => {
            e.preventDefault();
            e.stopPropagation();
            const tag = btn.dataset.tag;
            console.log('Tag clicked:', tag);
            if (tag && window.loadFeedByTag) {
              window.loadFeedByTag(tag);
              headerSearchInput.value = '';
              headerSearchResults.classList.add('hidden');
            }
          });
        });
      } catch (error) {
        console.error('Search error:', error);
        headerSearchResults.innerHTML = '<div class="px-3 py-2 text-sm text-red-500">Search failed</div>';
      }
    }

    function renderUserSearchResults(list, query = '') {
      const q = query.trim();
      if (!q) {
        userSearchResults.innerHTML =
          '<div class="text-sm text-slate-500 px-2 py-2">Start typing to search users</div>';
        return;
      }

      if (!list.length) {
        userSearchResults.innerHTML =
          '<div class="text-sm text-slate-500 px-2 py-2">No user found</div>';
        return;
      }

      userSearchResults.innerHTML = list.map((user) => (
        `<div class="flex items-center justify-between border border-slate-200 rounded-custom p-2.5">` +
        `<div class="flex items-center gap-3">` +
        `<img src="${user.avatarUrl || ('https://ui-avatars.com/api/?name=' + encodeURIComponent(user.displayName || 'User'))}" alt="${user.displayName || 'User'}" class="w-10 h-10 rounded-custom bg-slate-100">` +
        `<div><p class="text-sm font-semibold text-slate-800">${user.displayName || 'User'}</p><p class="text-xs text-slate-500">${user.email || ''}</p></div>` +
        `</div>` +
        `<a href="${user.profileUrl || '#'}" class="text-xs font-semibold text-blue-600 hover:text-blue-700">View Profile</a>` +
        `</div>`
      )).join('');
    }

    async function fetchDirectoryUsers(query) {
      const response = await fetch('/api/users/search?q=' + encodeURIComponent(query || ''), {
        headers: {
          Accept: 'application/json'
        }
      });

      if (!response.ok) return [];
      const data = await response.json();
      return data.users || [];
    }

    function togglePostOptions(btn) {
      const menu = btn.nextElementSibling;
      const isHidden = menu.classList.contains('hidden');
      document.querySelectorAll('.post-menu').forEach((m) => m.classList.add('hidden'));
      if (isHidden) menu.classList.remove('hidden');
    }

    function findScrollableAncestor(element) {
      let current = element instanceof Element ? element : null;

      while (current && current !== document.body) {
        const style = window.getComputedStyle(current);
        const canScrollY = /(auto|scroll)/.test(style.overflowY) && current.scrollHeight > current.clientHeight + 1;

        if (canScrollY) return current;
        current = current.parentElement;
      }

      return null;
    }

    window.addEventListener('wheel', (event) => {
      if (event.defaultPrevented || event.ctrlKey) return;
      if (event.target instanceof Element && event.target.closest('input, textarea, select, [contenteditable="true"]')) return;

      const scrollContainer = findScrollableAncestor(event.target) || document.getElementById('main-feed') || document.querySelector('main.overflow-y-auto');
      if (!scrollContainer || scrollContainer.scrollHeight <= scrollContainer.clientHeight + 1) return;

      const maxScrollTop = scrollContainer.scrollHeight - scrollContainer.clientHeight;
      const nextScrollTop = Math.max(0, Math.min(maxScrollTop, scrollContainer.scrollTop + event.deltaY));
      if (nextScrollTop === scrollContainer.scrollTop) return;

      event.preventDefault();
      scrollContainer.scrollTop = nextScrollTop;
    }, { passive: false });

    if (!sidebarManagedByHeader && sidebar) {
    function openSidebar() {
      if (window.innerWidth >= 1024) return;
      if (!sidebar || !sidebarBackdrop) return;
      sidebarBackdrop.classList.remove('hidden');
      sidebar.classList.remove('-translate-x-full');
      gsap.fromTo(sidebar, {
        x: -40,
        opacity: 0.6
      }, {
        x: 0,
        opacity: 1,
        duration: 0.3,
        ease: 'power2.out'
      });
      gsap.fromTo(sidebarBackdrop, {
        opacity: 0
      }, {
        opacity: 1,
        duration: 0.25
      });
      sidebarOpen = true;
    }

    function closeSidebar() {
      if (window.innerWidth >= 1024) return;
      if (!sidebar || !sidebarBackdrop) return;
      gsap.to(sidebar, {
        x: -40,
        opacity: 0.6,
        duration: 0.22,
        ease: 'power2.in',
        onComplete: () => {
          sidebar.classList.add('-translate-x-full');
          gsap.set(sidebar, {
            clearProps: 'all'
          });
        }
      });
      gsap.to(sidebarBackdrop, {
        opacity: 0,
        duration: 0.2,
        onComplete: () => sidebarBackdrop.classList.add('hidden')
      });
      sidebarOpen = false;
    }

    mobileSidebarToggle?.addEventListener('click', () => {
      if (sidebarOpen) closeSidebar();
      else openSidebar();
    });

    sidebarBackdrop?.addEventListener('click', closeSidebar);

    window.addEventListener('resize', () => {
      if (!sidebar || !sidebarBackdrop) return;
      if (window.innerWidth >= 1024) {
        sidebarBackdrop.classList.add('hidden');
        sidebar.classList.remove('-translate-x-full');
        gsap.set(sidebar, {
          clearProps: 'all'
        });
        sidebarOpen = false;
      } else if (!sidebarOpen) {
        sidebar.classList.add('-translate-x-full');
      }
    });

    }

    function animateLike(el) {
      const post = el.closest('.post-card');
      const svg = el.querySelector('svg');
      const likesCount = post.querySelector('.likes-count');
      const likedUsers = JSON.parse(post.dataset.likeUsers || '[]');
      const isActive = el.classList.toggle('text-blue-600');

      anime({
        targets: svg,
        scale: [1, 1.6, 1],
        duration: 420,
        easing: 'spring(1, 80, 10, 0)'
      });

      if (isActive && !likedUsers.includes('You')) likedUsers.unshift('You');
      if (!isActive) {
        const idx = likedUsers.indexOf('You');
        if (idx > -1) likedUsers.splice(idx, 1);
      }

      post.dataset.likeUsers = JSON.stringify(likedUsers);
      likesCount.textContent = likedUsers.length;
      svg.setAttribute('fill', isActive ? 'currentColor' : 'none');

      openLikesModal(post.querySelector('.likes-count').parentElement);
    }

    function openLikesModal(triggerBtn) {
      const likesModal = document.getElementById('likes-modal');
      const likesList = document.getElementById('likes-list');
      const post = triggerBtn.closest('.post-card');
      const likedUsers = JSON.parse(post.dataset.likeUsers || '[]');

      likesList.innerHTML = likedUsers.map((name) =>
        `<li class="flex items-center justify-between border border-slate-100 rounded-custom px-3 py-2"><span>${name}</span><button class="text-xs text-blue-600 font-semibold">View</button></li>`
        ).join('');
      showModal(likesModal);
    }

    function openShareModal() {
      showModal(document.getElementById('share-modal'));
    }

    function showModal(modal) {
      modal.style.display = 'block';
      const panel = modal.querySelector('.modal-panel');
      gsap.fromTo(modal, {
        opacity: 0
      }, {
        opacity: 1,
        duration: 0.2
      });
      gsap.fromTo(panel, {
        y: 20,
        opacity: 0
      }, {
        y: 0,
        opacity: 1,
        duration: 0.25,
        ease: 'power2.out'
      });
    }

    function closeModal(id) {
      const modal = document.getElementById(id);
      const panel = modal.querySelector('.modal-panel');
      gsap.to(panel, {
        y: 15,
        opacity: 0,
        duration: 0.18
      });
      gsap.to(modal, {
        opacity: 0,
        duration: 0.18,
        onComplete: () => {
          modal.style.display = 'none';
          gsap.set(panel, {
            clearProps: 'all'
          });
        }
      });
    }

    function togglePostComments(btn) {
      const post = btn.closest('.post-card');
      const commentsBlock = post.querySelector('.comments-block');
      const isOpen = commentsBlock.dataset.open === 'true';

      if (!isOpen) {
        commentsBlock.classList.remove('hidden');
        commentsBlock.dataset.open = 'true';
        commentsBlock.scrollIntoView({
          behavior: 'smooth',
          block: 'center'
        });
        gsap.fromTo(commentsBlock, {
          height: 0,
          opacity: 0
        }, {
          height: 'auto',
          opacity: 1,
          duration: 0.28,
          ease: 'power2.out'
        });
      } else {
        commentsBlock.dataset.open = 'false';
        gsap.to(commentsBlock, {
          height: 0,
          opacity: 0,
          duration: 0.24,
          ease: 'power2.in',
          onComplete: () => {
            commentsBlock.classList.add('hidden');
            gsap.set(commentsBlock, {
              clearProps: 'all'
            });
          }
        });
      }
    }

    function toggleComments(btn) {
      const commentsBlock = btn.closest('.comments-block');
      const hiddenComments = commentsBlock.querySelectorAll('.extra-comment.hidden');
      const visibleComments = commentsBlock.querySelectorAll('.extra-comment:not(.hidden)');

      if (hiddenComments.length) {
        hiddenComments.forEach((item, index) => {
          item.classList.remove('hidden');
          gsap.fromTo(item, {
            opacity: 0,
            y: -8
          }, {
            opacity: 1,
            y: 0,
            delay: index * 0.04,
            duration: 0.2
          });
        });
        btn.textContent = 'Show less';
      } else {
        visibleComments.forEach((item) => item.classList.add('hidden'));
        btn.textContent = 'Show all comments';
      }
    }

    function addComment(btn) {
      const wrapper = btn.closest('.comments-block');
      const input = wrapper.querySelector('.comment-input');
      const commentsList = wrapper.querySelector('.comments-list');
      const value = input.value.trim();

      if (!value) return;

      const newComment = document.createElement('div');
      newComment.className = 'comment-row text-sm text-slate-700';
      newComment.innerHTML = '<span class="font-semibold">You:</span> ' + value;
      commentsList.appendChild(newComment);
      input.value = '';

      gsap.fromTo(newComment, {
        opacity: 0,
        x: -8
      }, {
        opacity: 1,
        x: 0,
        duration: 0.24,
        ease: 'power2.out'
      });
    }

    function sharePost() {
      const shareLink = document.getElementById('share-link');
      shareLink.select();
      document.execCommand('copy');
      closeModal('share-modal');
    }

    userMenuButton?.addEventListener('click', (e) => {
      e.stopPropagation();
      toggleUserMenu();
    });

    headerSearchInput?.addEventListener('focus', async (e) => {
      // Pre-fetch content when search is focused
      await fetchHeaderContentItems(true);
      console.log('Search focused, items prefetched');
    });

    headerSearchInput?.addEventListener('input', async (e) => {
      const query = e.target.value || '';
      await renderHeaderSearchResults(query);
    });

    // Make loadFeedByTag globally available for header search (fallback)
    if (!window.loadFeedByTag) {
      window.loadFeedByTag = async function(tag) {
        window.location.href = `/facebook?tag=${encodeURIComponent(tag)}`;
      };
    }

    // Manual testing function - run in browser console: testTagSearch('calligraphy')
    window.testTagSearch = async function(query) {
      console.log('=== Testing tag search for:', query);
      const items = await fetchHeaderContentItems(true);
      console.log('All items:', items);
      
      const filtered = items.filter(item => {
        const text = (item.label + ' ' + item.description + ' ' + (item.tags || []).join(' ')).toLowerCase();
        return text.includes(query.toLowerCase());
      });
      console.log('Filtered results:', filtered);
      
      if (filtered.length) {
        console.log('✓ Search working! Found', filtered.length, 'items');
        return filtered;
      } else {
        console.log('✗ No results found for query:', query);
        return [];
      }
    };

    mainUserSearch?.addEventListener('input', async (e) => {
      const query = e.target.value || '';
      directoryUsers = await fetchDirectoryUsers(query);
      renderUserSearchResults(directoryUsers, query);
    });

    mainUserSearch?.addEventListener('keydown', async (e) => {
      if (e.key !== 'Enter') return;
      const q = (e.target.value || '').trim();
      if (!q) return;
      directoryUsers = await fetchDirectoryUsers(q);
      if (directoryUsers.length && directoryUsers[0].profileUrl) {
        window.location.href = directoryUsers[0].profileUrl;
      }
    });

    window.addEventListener('click', (e) => {
      if (!e.target.closest('#user-dropdown') && !e.target.closest('#user-menu-button')) {
        if (isMenuOpen) toggleUserMenu();
      }

      if (!e.target.closest('.post-menu') && !e.target.closest(
          'button[onclick^="togglePostOptions"]')) {
        document.querySelectorAll('.post-menu').forEach((m) => m.classList.add('hidden'));
      }

      if (e.target.id === 'likes-modal') closeModal('likes-modal');
      if (e.target.id === 'share-modal') closeModal('share-modal');

      if (!e.target.closest('#header-search-results') && !e.target.closest(
          '#header-content-search')) {
        headerSearchResults?.classList.add('hidden');
      }
    });

    gsap.fromTo('.post-card', {
      y: 20,
      opacity: 0
    }, {
      y: 0,
      opacity: 1,
      duration: 0.35,
      stagger: 0.08,
      ease: 'power2.out'
    });
    if (userSearchResults) renderUserSearchResults([], '');
