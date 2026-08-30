<?php
/**
 * Hospital Call System - Footer Template
 * King Khalid Hospital, Hail
 */

if (!defined('HOSPITAL_CALL_SYSTEM')) {
    exit('Direct access not permitted');
}
?>

<!-- SweetAlert2 (local) -->
<script src="assets/vendor/sweetalert2/sweetalert2.all.min.js"></script>

<!-- Bootstrap JS (local) -->
<script src="assets/vendor/bootstrap/bootstrap.bundle.min.js" defer></script>

<!-- Core System Scripts -->
<script src="assets/js/data.js?v=3.2" defer></script>
<script src="assets/js/audio.js?v=5.1" defer></script>
<script src="assets/js/i18n.js?v=3.2" defer></script>
<script src="assets/js/app.js?v=3.2" defer></script>
<script src="assets/js/themes.js?v=3.2" defer></script>
<script src="assets/js/chatbot.js?v=3.2" defer></script>

<?php if (isset($pageScript)): ?>
<script src="assets/js/<?= htmlspecialchars($pageScript) ?>" defer></script>
<?php endif; ?>

<!-- Service Worker Registration for PWA -->
<script>
    if ('serviceWorker' in navigator) {
        window.addEventListener('load', () => {
            navigator.serviceWorker.register('sw.js').catch(() => {});
        });
    }
</script>

<!-- Initialize System -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof initSystem === 'function') {
            initSystem();
        }
        const yearElements = document.querySelectorAll('.current-year');
        yearElements.forEach(el => { el.textContent = new Date().getFullYear(); });
    });
</script>

</body>
</html>
