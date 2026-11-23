<script>
    // Daftarkan service worker supaya KonekSI bisa di-install sebagai aplikasi (PWA).
    if ('serviceWorker' in navigator) {
        window.addEventListener('load', () => navigator.serviceWorker.register('/sw.js').catch(() => {}));
    }
</script>
