import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'reverb',
    key: import.meta.env.VITE_REVERB_APP_KEY,
    wsHost: '127.0.0.1',
    wsPort: 8080,
    forceTLS: false,
    disableStats: true
});

window.escapeHtml = function (value) {
    return String(value)
        .replaceAll('&', '&amp;')
        .replaceAll('<', '&lt;')
        .replaceAll('>', '&gt;')
        .replaceAll('"', '&quot;')
        .replaceAll("'", '&#039;');
};

window.renderIncomingMessage = function (message) {
    const time = message.time || new Date(message.created_at || Date.now()).toLocaleTimeString([], {
        hour: '2-digit',
        minute: '2-digit'
    });
    const initials = (message.sender_name || 'User')
        .split(' ')
        .filter(Boolean)
        .map((part) => part[0])
        .join('')
        .slice(0, 2)
        .toUpperCase();

    return `
        <div class="flex items-end gap-3 max-w-[75%] animate-fade-in">
            <div class="w-8 h-8 bg-blue-600 rounded-full flex-shrink-0 flex items-center justify-center text-white text-xs font-bold">${window.escapeHtml(initials || 'U')}</div>
            <div>
                <div class="msg-bubble-them p-4 text-sm shadow-sm leading-relaxed">
                    ${window.escapeHtml(message.body || '')}
                </div>
                <span class="text-[10px] text-gray-400 mt-1 ml-1 font-medium">${window.escapeHtml(time)}</span>
            </div>
        </div>
    `;
};

window.renderOutgoingMessage = function (message) {
    const time = message.time || new Date(message.created_at || Date.now()).toLocaleTimeString([], {
        hour: '2-digit',
        minute: '2-digit'
    });
    const tickIcon = message.status === 'read' ? 'check-check' : 'check';
    const tickClass = message.status === 'read'
        ? 'text-blue-500'
        : message.status === 'delivered'
            ? 'text-gray-500'
            : 'text-gray-300';

    return `
        <div class="flex flex-row-reverse items-end gap-3 max-w-[75%] ml-auto group animate-fade-in" data-message-id="${window.escapeHtml(message.id || '')}">
            <div class="flex flex-col items-end">
                <div class="msg-bubble-me p-4 text-sm shadow-md leading-relaxed">
                    ${window.escapeHtml(message.body || '')}
                </div>
                <div class="flex items-center gap-1 mt-1 mr-1">
                    <span class="text-[10px] text-gray-400 font-medium">${window.escapeHtml(time)}</span>
                    <i data-lucide="${tickIcon}" class="w-3 h-3 ${tickClass}"></i>
                </div>
            </div>
        </div>
    `;
};

const chatState = window.chatState || {};
const currentUserId = Number(chatState.authUserId || 0);
const currentChannel = chatState.channel || null;

if (currentChannel) {
    window.Echo.channel(currentChannel)
        .listen('.MessageSent', (event) => {
            const payload = event.message || event;

            if (!payload || Number(payload.sender_id) === currentUserId) {
                return;
            }

            const chatBox = document.getElementById('message-container');
            if (!chatBox) {
                return;
            }

            chatBox.insertAdjacentHTML('beforeend', window.renderIncomingMessage(payload));
            chatBox.scrollTo({ top: chatBox.scrollHeight, behavior: 'smooth' });
            if (window.lucide) {
                lucide.createIcons();
            }

            if (Number(chatState.activeFriendId || 0) === Number(payload.sender_id) && chatState.markReadUrl) {
                fetch(chatState.markReadUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    },
                    body: JSON.stringify({ sender_id: payload.sender_id })
                }).catch(() => {});
            }
        });
}
