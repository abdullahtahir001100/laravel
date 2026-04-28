
@vite(['resources/js/app.js'])
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Messages | Studio Pro</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Inter', sans-serif; transition: all 0.2s ease-out; }
        body { background-color: #f0f2f5; color: #1c1e21; overflow: hidden; }

        .custom-scroll::-webkit-scrollbar { width: 6px; }
        .custom-scroll::-webkit-scrollbar-track { background: transparent; }
        .custom-scroll::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        .custom-scroll::-webkit-scrollbar-thumb:hover { background: #94a3b8; }

        /* Message Logic */
        .msg-bubble-me { 
            background: #0084ff; 
            color: #fff; 
            border-radius: 18px 18px 4px 18px !important; 
            box-shadow: 0 2px 4px rgba(0, 132, 255, 0.2);
        }
        .msg-bubble-them { 
            background: #e4e6eb; 
            color: #050505; 
            border-radius: 18px 18px 18px 4px !important; 
        }

        /* Sidebars */
        .info-sidebar { 
            width: 320px; 
            transform: translateX(100%); 
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1); 
        }
        .info-sidebar.open { transform: translateX(0); }

        /* Call Overlay */
        .call-active {
            backdrop-filter: blur(20px);
            background: rgba(15, 23, 42, 0.9);
        }

        /* Mobile Optimization */
        @media (max-width: 768px) {
            .inbox-panel { width: 100%; position: absolute; z-index: 30; }
            .inbox-panel.hidden-mobile { transform: translateX(-100%); }
            .info-sidebar { width: 100%; position: fixed; z-index: 50; }
        }
    </style>
</head>
<body class="flex h-screen w-full bg-white">
    @php
        $activeFriendName = $activeFriend['displayName'] ?? 'Select a friend';
        $activeFriendInitials = strtoupper(collect(explode(' ', $activeFriendName))
            ->filter()
            ->map(fn ($part) => mb_substr($part, 0, 1))
            ->take(2)
            ->implode(''));
        $activeFriendStatus = $activeFriend ? 'Online' : 'Waiting';
    @endphp
    <script>
        window.chatState = {
            authUserId: @json($authUserId),
            activeFriendId: @json($activeFriend['id'] ?? null),
            channel: @json($chatChannel),
            sendUrl: @json(route('messages.store')),
            markReadUrl: @json(route('messages.read')),
        };
    </script>

    <aside id="inbox-panel" class="inbox-panel w-[360px] flex flex-col border-r border-gray-200 bg-white transition-transform duration-300">
        <div class="p-4 space-y-4">
            <div class="flex justify-between items-center">
                <h1 class="text-2xl font-extrabold tracking-tight">Messages</h1>
                <div class="flex gap-2">
                    <button class="p-2 bg-gray-100 hover:bg-gray-200 rounded-full"><i data-lucide="settings" class="w-5 h-5 text-gray-600"></i></button>
                    <button class="p-2 bg-gray-100 hover:bg-gray-200 rounded-full"><i data-lucide="square-pen" class="w-5 h-5 text-gray-600"></i></button>
                </div>
            </div>
            <div class="relative group">
                <i data-lucide="search" class="w-4 h-4 absolute left-3 top-3 text-gray-400 group-focus-within:text-blue-500"></i>
                <input type="text" placeholder="Search Studio Messenger" class="w-full bg-gray-100 border-none py-2.5 pl-10 pr-4 rounded-full text-sm outline-none focus:ring-2 ring-blue-100">
            </div>
        </div>

        <div class="flex-1 overflow-y-auto custom-scroll px-2" id="chat-list">
            @forelse($friends as $friend)
                <div onclick="openChat({{ $friend['id'] }})" class="chat-item {{ ($activeFriend['id'] ?? null) === $friend['id'] ? 'active bg-blue-50' : 'hover:bg-gray-50' }} flex items-center gap-3 p-3 rounded-xl cursor-pointer">
                    <div class="relative">
                        <div class="w-14 h-14 {{ ($activeFriend['id'] ?? null) === $friend['id'] ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-500' }} rounded-full flex items-center justify-center font-bold text-lg">
                            {{ strtoupper(collect(explode(' ', $friend['displayName']))->filter()->map(fn ($part) => mb_substr($part, 0, 1))->take(2)->implode('')) }}
                        </div>
                        @if(($friend['unreadCount'] ?? 0) > 0)
                            <span class="absolute bottom-0 right-0 min-w-4 h-4 px-1 bg-blue-600 text-white text-[10px] font-bold rounded-full flex items-center justify-center border-2 border-white">{{ $friend['unreadCount'] }}</span>
                        @endif
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex justify-between items-center">
                            <h4 class="font-bold text-sm truncate {{ ($activeFriend['id'] ?? null) === $friend['id'] ? 'text-gray-900' : 'text-gray-800' }}">{{ $friend['displayName'] }}</h4>
                            <span class="text-[11px] {{ ($activeFriend['id'] ?? null) === $friend['id'] ? 'text-blue-600 font-bold' : 'text-gray-400' }}">{{ $friend['lastMessageAt'] ?? 'Now' }}</span>
                        </div>
                        <p class="text-xs {{ ($activeFriend['id'] ?? null) === $friend['id'] ? 'text-gray-600' : 'text-gray-400' }} truncate font-medium">{{ $friend['lastMessage'] ?? 'Tap to start a secure chat' }}</p>
                    </div>
                    <div class="w-2.5 h-2.5 {{ ($activeFriend['id'] ?? null) === $friend['id'] ? 'bg-blue-600' : 'bg-transparent' }} rounded-full"></div>
                </div>
            @empty
                <div class="p-4 text-sm text-gray-500">Only accepted friends appear here. Start by accepting a follow request.</div>
            @endforelse
        </div>
    </aside>

    <main class="flex-1 flex flex-col relative bg-white overflow-hidden">
        
        <header class="h-20 border-b border-gray-100 flex items-center justify-between px-6 z-10 bg-white/80 backdrop-blur-md">
            <div class="flex items-center gap-4">
                <button onclick="toggleMobileInbox()" class="md:hidden p-2 -ml-2 hover:bg-gray-100 rounded-full">
                    <i data-lucide="chevron-left"></i>
                </button>
                <div onclick="toggleInfoSidebar()" class="cursor-pointer group flex items-center gap-3">
                    <div id="header-avatar" class="w-10 h-10 bg-blue-600 rounded-full flex items-center justify-center text-white font-bold">{{ strtoupper(collect(explode(' ', $activeFriend['displayName'] ?? 'Chat'))->filter()->map(fn ($part) => mb_substr($part, 0, 1))->take(2)->implode('')) }}</div>
                    <div>
                        <h3 id="active-user-name" class="font-bold text-base leading-none">{{ $activeFriend['displayName'] ?? 'Select a friend' }}</h3>
                        <span class="text-[11px] text-green-500 font-bold uppercase tracking-wider">{{ $activeFriend ? 'Online' : 'Waiting' }}</span>
                    </div>
                    <i data-lucide="chevron-down" class="w-4 h-4 text-gray-400 group-hover:text-gray-600 transition-transform"></i>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <button onclick="startCall('voice')" class="p-2.5 text-blue-600 hover:bg-blue-50 rounded-full transition-colors">
                    <i data-lucide="phone" class="w-5 h-5"></i>
                </button>
                <button onclick="startCall('video')" class="p-2.5 text-blue-600 hover:bg-blue-50 rounded-full transition-colors">
                    <i data-lucide="video" class="w-5 h-5"></i>
                </button>
                <button onclick="toggleInfoSidebar()" class="p-2.5 text-blue-600 hover:bg-blue-50 rounded-full transition-colors">
                    <i data-lucide="info" class="w-5 h-5"></i>
                </button>
            </div>
        </header>

        <div id="message-container" class="flex-1 overflow-y-auto custom-scroll p-6 space-y-6 bg-gray-50/50">
            <div class="flex justify-center"><span class="text-[11px] font-bold text-gray-400 uppercase tracking-widest bg-white px-3 py-1 rounded-full shadow-sm">Today</span></div>

            @forelse($messages as $message)
                @if($message->sender_id === $authUserId)
                    @php
                        $tickIcon = $message->status === 'read' ? 'check-check' : 'check';
                        $tickClass = $message->status === 'read' ? 'text-blue-500' : ($message->status === 'delivered' ? 'text-gray-500' : 'text-gray-300');
                    @endphp
                    <div class="flex flex-row-reverse items-end gap-3 max-w-[75%] ml-auto group animate-fade-in" data-message-id="{{ $message->id }}">
                        <div class="flex flex-col items-end">
                            <div class="msg-bubble-me p-4 text-sm shadow-md leading-relaxed">
                                {{ $message->body }}
                            </div>
                            <div class="flex items-center gap-1 mt-1 mr-1">
                                <span class="text-[10px] text-gray-400 font-medium">{{ $message->created_at->format('g:i A') }}</span>
                                <i data-lucide="{{ $tickIcon }}" class="w-3 h-3 {{ $tickClass }}"></i>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="flex items-end gap-3 max-w-[75%] animate-fade-in" data-message-id="{{ $message->id }}">
                        <div class="w-8 h-8 bg-blue-600 rounded-full flex-shrink-0 flex items-center justify-center text-white text-xs font-bold">
                            {{ strtoupper(collect(explode(' ', $activeFriend['displayName'] ?? 'User'))->filter()->map(fn ($part) => mb_substr($part, 0, 1))->take(2)->implode('')) }}
                        </div>
                        <div>
                            <div class="msg-bubble-them p-4 text-sm shadow-sm leading-relaxed">
                                {{ $message->body }}
                            </div>
                            <span class="text-[10px] text-gray-400 mt-1 ml-1 font-medium">{{ $message->created_at->format('g:i A') }}</span>
                        </div>
                    </div>
                @endif
            @empty
                <div class="flex h-full items-center justify-center">
                    <div class="max-w-md rounded-2xl border border-dashed border-gray-200 bg-white p-6 text-center shadow-sm">
                        <h4 class="text-lg font-bold text-gray-900">No messages yet</h4>
                        <p class="mt-2 text-sm text-gray-500">Pick a friend from the left and start a secure conversation. Messages are stored in the database and only accepted friends appear here.</p>
                    </div>
                </div>
            @endforelse
        </div>

        <footer class="p-4 bg-white border-t border-gray-100 relative">
            <div id="file-preview" class="hidden flex gap-2 p-3 bg-gray-50 rounded-xl mb-3 border border-gray-200 overflow-x-auto"></div>
            
            <!-- Attachment Menu -->
            <div id="attachment-menu" class="hidden absolute bottom-20 left-4 bg-white border border-gray-100 shadow-xl rounded-xl p-2 flex-col gap-1 z-50">
                <button onclick="triggerFile('image/*')" class="flex items-center gap-3 p-2 hover:bg-gray-50 rounded-lg text-sm font-medium text-gray-700">
                    <div class="p-2 bg-blue-50 text-blue-600 rounded-lg"><i data-lucide="image" class="w-4 h-4"></i></div> Photos
                </button>
                <button onclick="triggerFile('video/*')" class="flex items-center gap-3 p-2 hover:bg-gray-50 rounded-lg text-sm font-medium text-gray-700">
                    <div class="p-2 bg-purple-50 text-purple-600 rounded-lg"><i data-lucide="video" class="w-4 h-4"></i></div> Videos
                </button>
                <button onclick="triggerFile('.pdf,.doc,.docx,.txt')" class="flex items-center gap-3 p-2 hover:bg-gray-50 rounded-lg text-sm font-medium text-gray-700">
                    <div class="p-2 bg-orange-50 text-orange-600 rounded-lg"><i data-lucide="file-text" class="w-4 h-4"></i></div> Documents
                </button>
            </div>

            <!-- Emoji Picker -->
            <div id="emoji-picker" class="hidden absolute bottom-20 right-20 bg-white border border-gray-100 shadow-xl rounded-xl p-3 grid grid-cols-6 gap-2 z-50">
                <!-- Simple static emojis for demo -->
                <button onclick="addEmoji('👍')" class="hover:bg-gray-100 p-2 rounded text-xl">👍</button>
                <button onclick="addEmoji('😂')" class="hover:bg-gray-100 p-2 rounded text-xl">😂</button>
                <button onclick="addEmoji('❤️')" class="hover:bg-gray-100 p-2 rounded text-xl">❤️</button>
                <button onclick="addEmoji('🔥')" class="hover:bg-gray-100 p-2 rounded text-xl">🔥</button>
                <button onclick="addEmoji('😊')" class="hover:bg-gray-100 p-2 rounded text-xl">😊</button>
                <button onclick="addEmoji('💯')" class="hover:bg-gray-100 p-2 rounded text-xl">💯</button>
                <button onclick="addEmoji('✨')" class="hover:bg-gray-100 p-2 rounded text-xl">✨</button>
                <button onclick="addEmoji('👏')" class="hover:bg-gray-100 p-2 rounded text-xl">👏</button>
                <button onclick="addEmoji('🎉')" class="hover:bg-gray-100 p-2 rounded text-xl">🎉</button>
                <button onclick="addEmoji('🙏')" class="hover:bg-gray-100 p-2 rounded text-xl">🙏</button>
                <button onclick="addEmoji('🤔')" class="hover:bg-gray-100 p-2 rounded text-xl">🤔</button>
                <button onclick="addEmoji('👀')" class="hover:bg-gray-100 p-2 rounded text-xl">👀</button>
            </div>

            <div class="flex items-center gap-2">
                <button onclick="toggleAttachmentMenu()" class="p-2.5 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-full transition-all">
                    <i data-lucide="plus-circle" class="w-6 h-6"></i>
                </button>
                <div class="flex-1 relative">
                    <input type="text" id="chat-input" placeholder="Type a message..." 
                        class="w-full bg-gray-100 border-none py-3 px-5 pr-12 rounded-2xl text-sm outline-none focus:bg-gray-200/50 transition-all">
                    <button onclick="toggleEmojiPicker()" class="absolute right-3 top-2 text-gray-400 hover:text-blue-600"><i data-lucide="smile" class="w-5 h-5"></i></button>
                </div>
                <button onclick="handleSend()" class="p-3 bg-blue-600 text-white hover:bg-blue-700 rounded-xl shadow-lg shadow-blue-200 transition-all">
                    <i data-lucide="send-horizontal" class="w-5 h-5"></i>
                </button>
            </div>
            <input type="file" id="hidden-file" class="hidden" multiple onchange="previewFiles(this)">
        </footer>

        <aside id="info-sidebar" class="info-sidebar absolute right-0 top-0 h-full bg-white border-l border-gray-100 flex flex-col z-40 shadow-2xl">
            <div class="p-6 border-b border-gray-50 flex flex-col items-center text-center">
                <button onclick="toggleInfoSidebar()" class="absolute top-4 left-4 p-2 hover:bg-gray-100 rounded-full"><i data-lucide="x" class="w-5 h-5"></i></button>
                <div class="w-24 h-24 bg-blue-600 rounded-full flex items-center justify-center text-white text-3xl font-bold mb-4 shadow-xl shadow-blue-100">IB</div>
                <h4 class="font-extrabold text-lg" id="side-name">InkByHand Store</h4>
                <p class="text-xs text-green-500 font-bold uppercase tracking-widest mt-1">Active Now</p>
            </div>

            <div class="flex-1 overflow-y-auto custom-scroll p-4 space-y-6">
                <div>
                    <h5 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-3">Shared Media</h5>
                    <div class="grid grid-cols-3 gap-2">
                        <div class="aspect-square bg-gray-100 rounded-lg hover:opacity-80 cursor-pointer"></div>
                        <div class="aspect-square bg-gray-100 rounded-lg hover:opacity-80 cursor-pointer"></div>
                        <div class="aspect-square bg-gray-100 rounded-lg hover:opacity-80 cursor-pointer"></div>
                    </div>
                </div>

                <div class="space-y-1">
                    <h5 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-3">Settings</h5>
                    <button onclick="toggleMute()" class="w-full flex items-center justify-between p-3 hover:bg-gray-50 rounded-xl transition-all">
                        <div class="flex items-center gap-3">
                            <i data-lucide="bell" id="mute-icon" class="w-5 h-5 text-gray-500"></i>
                            <span class="text-sm font-semibold" id="mute-text">Mute Notifications</span>
                        </div>
                        <div id="mute-toggle" class="w-10 h-5 bg-gray-200 rounded-full relative transition-colors"><div id="mute-toggle-thumb" class="absolute left-1 top-1 w-3 h-3 bg-white rounded-full transition-transform"></div></div>
                    </button>
                    <button onclick="confirmBlock()" class="w-full flex items-center gap-3 p-3 hover:bg-red-50 rounded-xl text-red-500 transition-colors">
                        <i data-lucide="slash" class="w-5 h-5"></i>
                        <span class="text-sm font-bold">Block Store</span>
                    </button>
                </div>
            </div>
        </aside>

    </main>

    <!-- Block Confirmation Modal -->
    <div id="block-modal" class="hidden fixed inset-0 z-[110] bg-black/40 backdrop-blur-sm flex items-center justify-center">
        <div class="bg-white rounded-2xl p-6 w-[90%] max-w-sm shadow-2xl scale-95 opacity-0 transition-all duration-200" id="block-modal-content">
            <h3 class="text-xl font-bold text-gray-900 mb-2">Block user?</h3>
            <p class="text-sm text-gray-500 mb-6">They won't be able to message you or find your profile.</p>
            <div class="flex gap-3">
                <button onclick="closeBlockModal()" class="flex-1 px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-xl transition-colors">Cancel</button>
                <button onclick="executeBlock()" class="flex-1 px-4 py-2.5 bg-red-500 hover:bg-red-600 text-white font-semibold rounded-xl shadow-lg shadow-red-200 transition-colors">Block</button>
            </div>
        </div>
    </div>

    <div id="call-screen" class="hidden fixed inset-0 z-[100] call-active flex flex-col items-center justify-center text-white">
        <div id="call-screen-content" class="w-full h-full flex flex-col items-center justify-center scale-95 opacity-0 transition-all duration-300">
            <div class="absolute top-16 flex flex-col items-center">
                <div class="w-32 h-32 bg-blue-600 rounded-full flex items-center justify-center text-4xl font-bold mb-6 animate-pulse border-4 border-blue-400/30" id="call-avatar">IB</div>
                <h2 id="call-name" class="text-3xl font-extrabold text-white shadow-sm">InkByHand Store</h2>
                <p id="call-status" class="text-blue-300 font-bold mt-2 uppercase tracking-widest text-sm">Calling...</p>
            </div>

            <div class="absolute bottom-20 flex gap-8 items-center">
                <button class="p-5 bg-white/10 hover:bg-white/20 rounded-full backdrop-blur-md transition-all">
                    <i data-lucide="mic-off" class="w-7 h-7 text-white"></i>
                </button>
                <button onclick="endCall()" class="p-6 bg-red-500 hover:bg-red-600 rounded-full shadow-2xl shadow-red-500/40 transition-all rotate-[135deg]">
                    <i data-lucide="phone" class="w-8 h-8 text-white fill-white"></i>
                </button>
                <button class="p-5 bg-white/10 hover:bg-white/20 rounded-full backdrop-blur-md transition-all">
                    <i data-lucide="volume-2" class="w-7 h-7 text-white"></i>
                </button>
            </div>
        </div>
    </div>

    <script>
        // Initialize Lucide Icons
        lucide.createIcons();

        // App State
        const activeChat = {
            name: @json($activeFriendName),
            initials: @json($activeFriendInitials),
            status: @json($activeFriendStatus)
        };

        // Utility: Toggle Sidebars
        function toggleInfoSidebar() {
            const sidebar = document.getElementById('info-sidebar');
            sidebar.classList.toggle('open');
        }

        function toggleMobileInbox() {
            document.getElementById('inbox-panel').classList.toggle('hidden-mobile');
        }

        // Feature: Send Message
        function handleSend() {
            const input = document.getElementById('chat-input');
            const container = document.getElementById('message-container');
            const preview = document.getElementById('file-preview');
            const message = input.value.trim();

            const hasText = message.length > 0;
            const hasFiles = !preview.classList.contains('hidden');
            const activeFriendId = Number(window.chatState?.activeFriendId || 0);

            if (!hasText || !activeFriendId) return;

            if (hasFiles) {
                alert('File attachments are not enabled yet. Please send a text message.');
                return;
            }

            fetch(window.chatState.sendUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    recipient_id: activeFriendId,
                    message: message
                })
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                const payload = data.message;
                if (!payload) {
                    return;
                }

                container.insertAdjacentHTML('beforeend', window.renderOutgoingMessage(payload));
                input.value = '';

                preview.innerHTML = '';
                preview.classList.add('hidden');
                document.getElementById('hidden-file').value = '';

                lucide.createIcons();
                container.scrollTo({ top: container.scrollHeight, behavior: 'smooth' });

                document.getElementById('emoji-picker').classList.add('hidden');
                document.getElementById('attachment-menu').classList.add('hidden');
            })
            .catch(error => {
                console.error('Error sending message:', error);
                alert('Failed to send message. Please try again.');
            });
        }

        // Feature: Calling Logic
        function startCall(type) {
            const callScreen = document.getElementById('call-screen');
            const callContent = document.getElementById('call-screen-content');
            
            document.getElementById('call-name').innerText = activeChat.name;
            document.getElementById('call-status').innerText = type === 'video' ? 'Starting Video...' : 'Calling...';
            document.getElementById('call-avatar').innerText = activeChat.initials;
            
            callScreen.classList.remove('hidden');
            setTimeout(() => {
                callContent.classList.remove('scale-95', 'opacity-0');
                callContent.classList.add('scale-100', 'opacity-100');
            }, 10);
        }

        function endCall() {
            const callScreen = document.getElementById('call-screen');
            const callContent = document.getElementById('call-screen-content');
            
            callContent.classList.remove('scale-100', 'opacity-100');
            callContent.classList.add('scale-95', 'opacity-0');
            
            setTimeout(() => {
                callScreen.classList.add('hidden');
            }, 300);
        }

        // Feature: Chat Selection
        function openChat(friendId) {
            const params = new URLSearchParams(window.location.search);
            params.set('friend_id', friendId);
            window.location.search = params.toString();
        }

        // File Selection Helper
        function toggleAttachmentMenu() {
            document.getElementById('emoji-picker').classList.add('hidden');
            document.getElementById('attachment-menu').classList.toggle('hidden');
        }

        function toggleEmojiPicker() {
            document.getElementById('attachment-menu').classList.add('hidden');
            document.getElementById('emoji-picker').classList.toggle('hidden');
        }

        function addEmoji(emoji) {
            const input = document.getElementById('chat-input');
            input.value += emoji;
            input.focus();
        }

        function triggerFile(acceptParams) { 
            const fileInput = document.getElementById('hidden-file');
            if (acceptParams) {
                fileInput.accept = acceptParams;
            } else {
                fileInput.removeAttribute('accept');
            }
            fileInput.click(); 
            document.getElementById('attachment-menu').classList.add('hidden');
        }
        
        function previewFiles(input) {
            const preview = document.getElementById('file-preview');
            preview.innerHTML = '';
            if (input.files.length > 0) {
                preview.classList.remove('hidden');
                Array.from(input.files).forEach(file => {
                    const isImage = file.type.startsWith('image/');
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        if (isImage) {
                            const img = document.createElement('img');
                            img.src = e.target.result;
                            img.className = "h-20 w-20 object-cover rounded-lg border border-gray-200";
                            preview.appendChild(img);
                        } else {
                            const div = document.createElement('div');
                            div.className = "h-20 px-4 bg-white border border-gray-200 rounded-lg flex items-center justify-center text-sm font-bold text-gray-500 flex-shrink-0";
                            div.innerHTML = `<i data-lucide="file" class="w-5 h-5 mr-2"></i> ${file.name}`;
                            preview.appendChild(div);
                            lucide.createIcons();
                        }
                    }
                    reader.readAsDataURL(file);
                });
            } else {
                preview.classList.add('hidden');
            }
        }

        // Mute / Block Settings
        let isMuted = false;
        function toggleMute() {
            isMuted = !isMuted;
            const toggle = document.getElementById('mute-toggle');
            const thumb = document.getElementById('mute-toggle-thumb');
            const icon = document.getElementById('mute-icon');
            const text = document.getElementById('mute-text');
            
            if (isMuted) {
                toggle.className = 'w-10 h-5 bg-blue-500 rounded-full relative transition-colors';
                thumb.className = 'absolute left-1 top-1 w-3 h-3 bg-white rounded-full transition-transform translate-x-5';
                icon.setAttribute('data-lucide', 'bell-off');
                icon.classList.add('text-blue-500');
                icon.classList.remove('text-gray-500');
                text.innerText = "Unmute Notifications";
            } else {
                toggle.className = 'w-10 h-5 bg-gray-200 rounded-full relative transition-colors';
                thumb.className = 'absolute left-1 top-1 w-3 h-3 bg-white rounded-full transition-transform translate-x-0';
                icon.setAttribute('data-lucide', 'bell');
                icon.classList.add('text-gray-500');
                icon.classList.remove('text-blue-500');
                text.innerText = "Mute Notifications";
            }
            lucide.createIcons();
        }

        function confirmBlock() {
            const modal = document.getElementById('block-modal');
            const content = document.getElementById('block-modal-content');
            modal.classList.remove('hidden');
            setTimeout(() => {
                content.classList.remove('scale-95', 'opacity-0');
                content.classList.add('scale-100', 'opacity-100');
            }, 10);
        }

        function closeBlockModal() {
            const modal = document.getElementById('block-modal');
            const content = document.getElementById('block-modal-content');
            content.classList.remove('scale-100', 'opacity-100');
            content.classList.add('scale-95', 'opacity-0');
            setTimeout(() => modal.classList.add('hidden'), 200);
        }

        function executeBlock() {
            closeBlockModal();
            setTimeout(() => {
                alert(`You blocked ${activeChat.name}. Redirecting to home...`);
                window.location.href = '/';
            }, 300);
        }

        // Enter key to send
        document.getElementById('chat-input').addEventListener('keypress', (e) => {
            if (e.key === 'Enter') handleSend();
        });

        if (window.chatState?.activeFriendId && window.chatState.channel) {
            document.querySelectorAll('.chat-item').forEach((item) => {
                if (String(item.getAttribute('onclick') || '').includes(String(window.chatState.activeFriendId))) {
                    item.classList.add('active', 'bg-blue-50');
                }
            });
        }
    </script>

</body>
</html>