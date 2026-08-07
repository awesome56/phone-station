{{--
    Tailwind v4 browser CDN + theme + utilities.
    Used on the shared host where `npm run build` is unavailable.
    For local dev you can keep using this; it renders identically to the compiled build.
--}}
<script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
<style type="text/tailwindcss">
@import 'tailwindcss';

@source '../../vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php';
@source '../../storage/framework/views/*.php';

@theme {
    --font-sans: 'Poppins', ui-sans-serif, system-ui, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji',
        'Segoe UI Symbol', 'Noto Color Emoji';

    --color-brand: #0156ff;
    --color-brand-dark: #0045d6;
    --color-ink: #020203;
    --color-mist: #f5f7ff;
    --color-ash: #a2a6b0;
    --color-faint: #a3a3a3;
    --color-muted: #666666;
    --color-slategray: #838383;
    --color-gold: #e9a426;
    --color-star-off: #cacdd8;
    --color-stock: #78a962;
    --color-avail: #c94d3f;
    --color-zip: #272560;
    --color-sale: #ffb800;
    --color-line: #ececf0;
    --color-field: #dee1e6;
}

@layer base {
    html {
        scroll-behavior: smooth;
    }

    body {
        @apply bg-white text-ink font-sans antialiased;
        text-rendering: optimizeLegibility;
    }

    ::selection {
        @apply bg-brand text-white;
    }
}

@layer utilities {
    .btn-brand {
        @apply inline-block bg-brand text-white font-semibold text-sm;
        padding: 0.9rem 2.6rem;
        transition: background-color 0.25s ease, transform 0.25s ease;
    }

    .btn-brand:hover {
        @apply bg-brand-dark;
    }

    .btn-brand:disabled {
        @apply bg-ash cursor-not-allowed;
    }

    .btn-dark {
        @apply inline-block bg-ink text-white font-semibold text-sm;
        padding: 0.9rem 2.6rem;
        transition: background-color 0.25s ease;
    }

    .btn-dark:hover {
        @apply bg-brand;
    }

    .btn-outline {
        @apply inline-block border border-field text-ash font-semibold text-sm;
        padding: 0.9rem 2.6rem;
        transition: color 0.25s ease, border-color 0.25s ease;
    }

    .btn-outline:hover {
        @apply border-brand text-brand;
    }

    .input-box {
        @apply w-full bg-white border border-field text-ink text-sm outline-none;
        padding: 0.85rem 1rem;
        transition: border-color 0.25s ease;
    }

    .input-box:focus {
        border-color: var(--color-brand);
    }

    .input-box::placeholder {
        @apply text-faint;
    }

    .input-box select,
    .input-box option {
        @apply text-ink;
    }

    .qty-box {
        @apply flex items-center justify-center bg-mist text-ink font-semibold text-sm;
        min-width: 3.25rem;
        height: 2.75rem;
    }

    .qty-btn {
        @apply flex items-center justify-center text-ink w-9 h-9 hover:text-brand transition-colors duration-200;
    }

    .breadcrumb {
        @apply font-light text-xs text-faint;
    }

    .breadcrumb a {
        @apply text-faint hover:text-brand transition-colors duration-200;
    }

    .breadcrumb .sep {
        @apply mx-2 text-faint;
    }
}

@keyframes fade-up {
    from {
        opacity: 0;
        transform: translateY(1.5rem);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
</style>
<script>
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
</script>
