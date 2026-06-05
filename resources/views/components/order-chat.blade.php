@php
    $chatType = $type ?? 'customer_driver';

    $chatTitle = match($chatType) {
        'customer_merchant' => 'Chat Customer ↔ Merchant',
        'customer_driver' => 'Chat Customer ↔ Driver',
        'merchant_driver' => 'Chat Merchant ↔ Driver',
        default => 'Chat Order',
    };

    $chatIcon = match($chatType) {
        'customer_merchant' => '🏪',
        'customer_driver' => '🛵',
        'merchant_driver' => '🤝',
        default => '💬',
    };
@endphp

<div class="chat-card" data-order-id="{{ $order->id }}" data-chat-type="{{ $chatType }}">
    <div class="chat-head">
        <div>
            <h3>{{ $chatIcon }} {{ $chatTitle }}</h3>
            <p>Pesan khusus untuk order {{ $order->order_number ?? '#'.$order->id }}</p>
        </div>

        <span id="chatBadge{{ $order->id }}{{ $chatType }}" class="chat-badge">0</span>
    </div>

    <div id="chatMessages{{ $order->id }}{{ $chatType }}" class="chat-messages">
        <div class="chat-empty">Chat siap dibuka...</div>
    </div>

    <div id="chatPreviewBox{{ $order->id }}{{ $chatType }}" class="chat-preview-box" style="display:none;">
        <button type="button"
                id="chatPreviewRemove{{ $order->id }}{{ $chatType }}"
                class="chat-preview-remove">
            ×
        </button>

        <img id="chatPreviewImage{{ $order->id }}{{ $chatType }}" class="chat-preview-img">
    </div>

    <form id="chatForm{{ $order->id }}{{ $chatType }}" class="chat-form">
        @csrf

        <label class="chat-photo-btn">
            📷
            <input type="file"
                   id="chatImage{{ $order->id }}{{ $chatType }}"
                   accept="image/*"
                   hidden>
        </label>

        <input type="text"
               id="chatInput{{ $order->id }}{{ $chatType }}"
               placeholder="Tulis pesan..."
               autocomplete="off">

        <button type="submit">Kirim</button>
    </form>
</div>

<style>
.chat-card{background:white;border-radius:24px;padding:16px;box-shadow:0 10px 24px rgba(15,23,42,.08);margin-top:16px}
.chat-head{display:flex;justify-content:space-between;align-items:flex-start;gap:12px;margin-bottom:14px}
.chat-head h3{margin:0;color:var(--primary,var(--primary-color));font-size:20px;font-weight:900}
.chat-head p{margin:5px 0 0;color:#6b7280;font-size:13px}
.chat-badge{display:none;min-width:24px;height:24px;align-items:center;justify-content:center;background:#dc2626;color:white;border-radius:999px;font-size:12px;font-weight:900}
.chat-messages{height:280px;overflow-y:auto;background:rgba(15,23,42,.04);border-radius:18px;padding:12px;display:flex;flex-direction:column;gap:10px}
.chat-empty{text-align:center;color:#6b7280;font-weight:800;margin:auto}
.chat-row{display:flex}
.chat-row.me{justify-content:flex-end}
.chat-bubble{max-width:78%;padding:10px 12px;border-radius:16px;background:white;color:#111827;box-shadow:0 4px 12px rgba(15,23,42,.06)}
.chat-row.me .chat-bubble{background:linear-gradient(135deg,var(--primary,var(--primary-color)),var(--secondary,var(--secondary-color)));color:white}
.chat-name{font-size:11px;font-weight:900;opacity:.75;margin-bottom:4px;text-transform:uppercase}
.chat-status{font-size:9px;opacity:.5;margin-bottom:6px}
.chat-text{font-size:14px;line-height:1.45;word-break:break-word}
.chat-time{font-size:10px;opacity:.7;margin-top:5px;text-align:right}
.chat-form{display:flex;gap:8px;margin-top:12px}
.chat-form input{flex:1;border:none;outline:none;background:rgba(15,23,42,.05);border-radius:16px;padding:13px 14px;font-weight:700}
.chat-form button{border:none;cursor:pointer;padding:13px 16px;border-radius:16px;color:white;font-weight:900;background:linear-gradient(135deg,var(--primary,var(--primary-color)),var(--secondary,var(--secondary-color)))}
.chat-photo-btn{width:46px;height:46px;border-radius:16px;background:rgba(15,23,42,.06);display:flex;align-items:center;justify-content:center;cursor:pointer;font-size:20px;flex-shrink:0}
.chat-img{display:block;max-width:220px;max-height:220px;border-radius:14px;margin-top:8px;cursor:pointer;object-fit:cover}
.chat-preview-box{position:relative;width:max-content;max-width:220px;margin-top:12px;background:rgba(15,23,42,.05);border-radius:16px;padding:8px}
.chat-preview-img{display:block;max-width:200px;max-height:180px;border-radius:12px;object-fit:cover}
.chat-preview-remove{position:absolute;top:-8px;right:-8px;width:26px;height:26px;border:none;border-radius:50%;background:#dc2626;color:white;font-weight:900;cursor:pointer}
@media(max-width:640px){
    .chat-messages{height:260px}
    .chat-form{flex-direction:row}
    .chat-form button{width:auto}
}
</style>

<script>
(function(){
    const orderId = "{{ $order->id }}";
    const chatType = "{{ $chatType }}";
    const uniqueId = orderId + chatType;

    const box = document.getElementById('chatMessages' + uniqueId);
    const form = document.getElementById('chatForm' + uniqueId);
    const input = document.getElementById('chatInput' + uniqueId);
    const badge = document.getElementById('chatBadge' + uniqueId);
    const imageInput = document.getElementById('chatImage' + uniqueId);
    const previewBox = document.getElementById('chatPreviewBox' + uniqueId);
    const previewImage = document.getElementById('chatPreviewImage' + uniqueId);
    const previewRemove = document.getElementById('chatPreviewRemove' + uniqueId);

    let sending = false;
    let chatInterval = null;
    let unreadInterval = null;

    function escapeHtml(text){
        const div = document.createElement('div');
        div.innerText = text || '';
        return div.innerHTML;
    }

    function renderMessages(messages){
        if(!box) return;

        if(!messages || messages.length === 0){
            box.innerHTML = '<div class="chat-empty">Belum ada pesan.</div>';
            return;
        }

        box.innerHTML = messages.map(function(chat){
            const statusText = chat.is_online
                ? 'Online'
                : ('Terakhir aktif ' + (chat.last_seen_at ?? '-'));

            const onlineDot = chat.is_online ? '🟢' : '⚫';

            return `
                <div class="chat-row ${chat.is_me ? 'me' : ''}">
                    <div class="chat-bubble">
                        <div class="chat-name">
                            ${onlineDot} ${escapeHtml(chat.sender_name)}
                        </div>

                        <div class="chat-status">
                            ${escapeHtml(statusText)}
                        </div>

                        ${chat.message ? `
                            <div class="chat-text">
                                ${escapeHtml(chat.message)}
                            </div>
                        ` : ''}

                        ${chat.image ? `
                            <img src="${chat.image}"
                                 class="chat-img"
                                 onclick="window.open('${chat.image}','_blank')">
                        ` : ''}

                        <div class="chat-time">
                            ${escapeHtml(chat.time)}
                        </div>
                    </div>
                </div>
            `;
        }).join('');

        box.scrollTop = box.scrollHeight;
    }

    function updateUnreadBadge(count){
        if(!badge) return;

        count = parseInt(count || 0);

        if(count > 0){
            badge.style.display = 'flex';
            badge.innerText = count;
        }else{
            badge.style.display = 'none';
            badge.innerText = '';
        }
    }

    function loadMessages(markRead){
        const url = `/orders/${orderId}/chat/${chatType}` + (markRead ? '?mark_read=1' : '');

        fetch(url,{headers:{'X-Requested-With':'XMLHttpRequest'}})
            .then(res => res.json())
            .then(data => {
                renderMessages(data.messages);

                if(markRead){
                    updateUnreadBadge(0);

                    if(typeof window.refreshAllChatBadges === 'function'){
                        setTimeout(window.refreshAllChatBadges, 300);
                    }
                }
            })
            .catch(() => {
                if(box){
                    box.innerHTML = '<div class="chat-empty">Gagal memuat chat.</div>';
                }
            });
    }

    function loadUnread(){
        fetch(`/orders/${orderId}/chat/${chatType}/unread`,{
            headers:{'X-Requested-With':'XMLHttpRequest'}
        })
        .then(res => res.json())
        .then(data => updateUnreadBadge(data.count))
        .catch(() => {});
    }

    function clearPreview(){
        if(imageInput){
            imageInput.value = '';
        }

        if(previewBox){
            previewBox.style.display = 'none';
        }

        if(previewImage){
            previewImage.src = '';
        }
    }

    if(imageInput){
        imageInput.addEventListener('change', function(){
            const file = imageInput.files[0];

            if(!file){
                clearPreview();
                return;
            }

            if(!file.type.startsWith('image/')){
                alert('File harus gambar.');
                clearPreview();
                return;
            }

            const reader = new FileReader();

            reader.onload = function(e){
                previewImage.src = e.target.result;
                previewBox.style.display = 'block';
            };

            reader.readAsDataURL(file);
        });
    }

    if(previewRemove){
        previewRemove.addEventListener('click', clearPreview);
    }

    function sendMessage(message){
        if(sending) return;

        sending = true;

        const formData = new FormData();
        formData.append('message', message || '');

        if(imageInput && imageInput.files[0]){
            formData.append('image', imageInput.files[0]);
        }

        fetch(`/orders/${orderId}/chat/${chatType}/send`,{
            method:'POST',
            headers:{
                'X-CSRF-TOKEN':'{{ csrf_token() }}',
                'X-Requested-With':'XMLHttpRequest'
            },
            body:formData
        })
        .then(res => res.json())
        .then(() => {
            input.value = '';
            clearPreview();
            loadMessages(false);
        })
        .finally(() => sending = false);
    }

    if(form){
        form.onsubmit = function(e){
            e.preventDefault();

            const message = input.value.trim();
            const hasImage = imageInput && imageInput.files.length > 0;

            if(!message && !hasImage){
                return;
            }

            sendMessage(message);
        };
    }

    window.initChat = window.initChat || {};
    window.stopChat = window.stopChat || {};

    window.initChat[uniqueId] = function(){
        loadMessages(true);
        loadUnread();

        if(chatInterval) clearInterval(chatInterval);
        if(unreadInterval) clearInterval(unreadInterval);

        chatInterval = setInterval(function(){
            loadMessages(false);
        }, 2000);

        unreadInterval = setInterval(loadUnread, 2000);
    };

    window.stopChat[uniqueId] = function(){
        if(chatInterval){
            clearInterval(chatInterval);
            chatInterval = null;
        }

        if(unreadInterval){
            clearInterval(unreadInterval);
            unreadInterval = null;
        }
    };
})();
</script>