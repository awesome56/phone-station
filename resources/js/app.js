import.meta.glob(['../images/**', '../fonts/**']);

document.addEventListener('DOMContentLoaded', () => {
    const menuToggle = document.getElementById('menu-toggle');
    const mobileMenu = document.getElementById('mobile-menu');

    if (menuToggle && mobileMenu) {
        menuToggle.addEventListener('click', () => {
            const open = mobileMenu.classList.toggle('hidden');
            menuToggle.setAttribute('aria-expanded', String(!open));
        });
    }

    document.querySelectorAll('[data-add-to-cart]').forEach((form) => {
        form.addEventListener('submit', () => {
            const button = form.querySelector('[type="submit"]');
            if (!button) return;

            const label = button.dataset.label ?? button.textContent;
            button.textContent = 'ADDED ✓';
            button.disabled = true;
            button.classList.add('!bg-stock');

            setTimeout(() => {
                button.textContent = label;
                button.disabled = false;
                button.classList.remove('!bg-stock');
            }, 1400);
        });
    });
});
