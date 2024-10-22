/* WA Chat WP Plugin - Custom Script */

document.addEventListener('DOMContentLoaded', function() {
    var chatButton = document.getElementById('wa-chat-button');
    
    if (chatButton) {
        // Tambahkan event listener untuk animasi muncul saat scroll
        window.addEventListener('scroll', function() {
            if (window.scrollY > 200) {
                chatButton.style.transform = 'scale(1)';
                chatButton.style.opacity = '1';
            } else {
                chatButton.style.transform = 'scale(0)';
                chatButton.style.opacity = '0';
            }
        });

        // Inisialisasi gaya awal tombol
        chatButton.style.transition = 'transform 0.3s ease, opacity 0.3s ease';
        chatButton.style.transform = 'scale(0)';
        chatButton.style.opacity = '0';
    }
});
