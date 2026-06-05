<audio id="chatNotifSound" preload="auto">
    <source src="{{ asset('sounds/order.mp3') }}" type="audio/mpeg">
</audio>

<style>
#enableChatSoundBtn{
    display:none !important;
}
</style>

<script>
let chatSoundUnlocked = false;
let chatBadgeReady = false;
let lastChatUnreadTotal = 0;

function unlockChatSound()
{
    const audio = document.getElementById('chatNotifSound');

    if(audio){
        audio.volume = 1;

        audio.play()
            .then(function(){
                audio.pause();
                audio.currentTime = 0;
                chatSoundUnlocked = true;

                const btn = document.getElementById('enableChatSoundBtn');
                if(btn){
                    btn.style.display = 'none';
                }
            })
            .catch(function(){});
    }

    if('Notification' in window && Notification.permission !== 'granted'){
        Notification.requestPermission();
    }
}

function playChatSound()
{
    const audio = document.getElementById('chatNotifSound');

    if(!audio || !chatSoundUnlocked){
        return;
    }

    audio.currentTime = 0;
    audio.play().catch(function(){});

    if('vibrate' in navigator){
        navigator.vibrate([250,120,250]);
    }

    if('Notification' in window && Notification.permission === 'granted'){
        new Notification('💬 Chat Baru Masuk', {
            body: 'Ada pesan chat baru di JavaJek.',
            icon: "{{ asset('images/logo.png') }}",
            requireInteraction: false
        });
    }
}

function getTotalChatUnread()
{
    let total = 0;

    document.querySelectorAll('.chat-unread-badge, .chat-badge').forEach(function(badge){

        if(!badge) return;

        const style = window.getComputedStyle(badge);

        if(style.display === 'none' || style.visibility === 'hidden'){
            return;
        }

        const value = parseInt(badge.innerText || '0');

        if(!isNaN(value)){
            total += value;
        }
    });

    return total;
}

function watchChatBadgeSound()
{
    const total = getTotalChatUnread();

    if(chatBadgeReady && total > lastChatUnreadTotal){
        playChatSound();
    }

    lastChatUnreadTotal = total;
    chatBadgeReady = true;
}

document.addEventListener('DOMContentLoaded', function(){

    const autoUnlock = function(){

        if(!chatSoundUnlocked){
            unlockChatSound();
        }
    };

    document.addEventListener(
        'click',
        autoUnlock,
        { once:true }
    );

    document.addEventListener(
        'touchstart',
        autoUnlock,
        { once:true }
    );

    document.addEventListener(
        'keydown',
        autoUnlock,
        { once:true }
    );

    setInterval(watchChatBadgeSound,1000);
});
</script>