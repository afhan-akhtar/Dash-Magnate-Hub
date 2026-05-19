<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.js-listing-guide-toggle').forEach(function (button) {
            const targetSelector = button.getAttribute('data-bs-target');
            const target = targetSelector ? document.querySelector(targetSelector) : null;
            const label = button.querySelector('.js-listing-guide-toggle-label');

            if (!target || !label) {
                return;
            }

            target.addEventListener('show.bs.collapse', function () {
                label.textContent = button.dataset.labelHide || 'Hide';
                button.setAttribute('aria-expanded', 'true');
            });

            target.addEventListener('hide.bs.collapse', function () {
                label.textContent = button.dataset.labelShow || 'Guide';
                button.setAttribute('aria-expanded', 'false');
            });
        });
    });
</script>
