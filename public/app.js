
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
    let sidebarBackdrop = document.getElementById('sidebar-backdrop');
    if (!sidebarBackdrop && sidebar) {
      sidebarBackdrop = document.createElement('div');
      sidebarBackdrop.id = 'sidebar-backdrop';
      sidebarBackdrop.className = 'fixed inset-0 bg-slate-900/30 z-20 hidden lg:hidden';
      document.body.appendChild(sidebarBackdrop);
    }
    const mobileSidebarToggle = document.getElementById('mobile-sidebar-toggle');
    const userMenuButton = document.getElementById('user-menu-button');
    const userDropdown = document.getElementById('user-dropdown');
    const chevronIcon = document.getElementById('chevron-icon');
    const headerSearchInput = document.getElementById('header-content-search');
    const headerSearchResults = document.getElementById('header-search-results');
    const mainUserSearch = document.getElementById('main-user-search');
    const userSearchResults = document.getElementById('user-search-results');

    let isMenuOpen = false;
    let sidebarOpen = false;

    const headerSearchItems = [{
        label: 'Home Feed',
        target: '#main-feed',
        type: 'Section'
      },
      {
        label: 'Discover',
        target: '#discover-nav',
        type: 'Section'
      },
      {
        label: 'Users Search',
        target: '#users-search-section',
        type: 'Section'
      },
      {
        label: 'Suggested Users',
        target: '#suggested-users-card',
        type: 'Section'
      },
      {
        label: 'Daily Pulse',
        target: '#daily-pulse-card',
        type: 'Section'
      },
      {
        label: 'User Directory Page',
        target: '/users',
        type: 'Page'
      }
    ];

    let directoryUsers = [];

    function toggleUserMenu() {
      const menu = document.getElementById('user-dropdown');
      if (!isMenuOpen) {
        menu.style.display = 'block';
        gsap.fromTo(menu, {
          opacity: 0,
          y: -10
        }, {
          opacity: 1,
          y: 0,
          duration: 0.25,
          ease: 'power2.out'
        });
      } else {
        gsap.to(menu, {
          opacity: 0,
          y: -10,
          duration: 0.2,
          onComplete: () => menu.style.display = 'none'
        });
      }
      isMenuOpen = !isMenuOpen;
      if (chevronIcon) chevronIcon.style.transform = isMenuOpen ? 'rotate(180deg)' : 'rotate(0deg)';
    }

    function renderHeaderSearchResults(query) {
      const q = query.trim().toLowerCase();
      if (!q) {
        headerSearchResults.classList.add('hidden');
        headerSearchResults.innerHTML = '';
        return;
      }

      const matched = headerSearchItems.filter((item) => item.label.toLowerCase().includes(q));
      if (!matched.length) {
        headerSearchResults.classList.remove('hidden');
        headerSearchResults.innerHTML =
          '<div class="px-3 py-2 text-sm text-slate-500">No content found</div>';
        return;
      }

      headerSearchResults.classList.remove('hidden');
      headerSearchResults.innerHTML = matched.map((item) => (
        `<button data-target="${item.target}" class="header-result-btn w-full text-left px-3 py-2 rounded-custom hover:bg-slate-50 transition-colors">` +
        `<p class="text-sm font-semibold text-slate-700">${item.label}</p>` +
        `<p class="text-[11px] text-slate-500">${item.type}</p>` +
        `</button>`
      )).join('');
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

    mobileSidebarToggle?.addEventListener('click', () => {
      if (sidebarOpen) closeSidebar();
      else openSidebar();
    });

    userMenuButton?.addEventListener('click', (e) => {
      e.stopPropagation();
      toggleUserMenu();
    });

    headerSearchInput?.addEventListener('input', (e) => {
      renderHeaderSearchResults(e.target.value || '');
    });

    headerSearchResults?.addEventListener('click', (e) => {
      const btn = e.target.closest('.header-result-btn');
      if (!btn) return;
      const target = btn.dataset.target;
      if (!target) return;

      if (target.startsWith('#')) {
        const el = document.querySelector(target);
        if (el) el.scrollIntoView({
          behavior: 'smooth',
          block: 'start'
        });
      } else {
        window.location.href = target;
      }

      headerSearchResults.classList.add('hidden');
      headerSearchInput.value = '';
    });

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
