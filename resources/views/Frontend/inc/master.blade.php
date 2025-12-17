<!DOCTYPE html>
<html lang="en">

@include('Frontend.inc.head')

<style>
    /* ✅ حل قصّ المنيو في بعض الثيمات */
    .page-holder,
    .header,
    .navbar,
    .navbar .container,
    .navbar-collapse {
        overflow: visible !important;
    }
</style>

<body>
    <div class="page-holder">

        @include('Frontend.inc.navbar')

        @yield('content')

        @include('Frontend.inc.footer')

        @include('Frontend.inc.scripts')

        <!-- FontAwesome CSS -->
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css"
              integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr"
              crossorigin="anonymous">
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {

            const cartCountEl     = document.querySelector('[data-cart-count]');
            const wishlistCountEl = document.querySelector('[data-wishlist-count]');

            async function postAjax(form, onSuccess) {
                const url = form.getAttribute('action');

                const tokenInput = form.querySelector('input[name=_token]');
                const token = tokenInput ? tokenInput.value : null;

                const formData = new FormData(form);

                try {
                    const res = await fetch(url, {
                        method: 'POST',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            ...(token ? { 'X-CSRF-TOKEN': token } : {}),
                            'Accept': 'application/json',
                        },
                        body: formData
                    });

                    if (res.status === 401) {
                        let data = null;
                        try { data = await res.json(); } catch (e) {}
                        const redirectUrl = (data && data.redirect) ? data.redirect : "{{ route('login') }}";
                        window.location.href = redirectUrl;
                        return;
                    }

                    const contentType = res.headers.get('content-type') || '';
                    if (!contentType.includes('application/json')) {
                        window.location.href = "{{ route('login') }}";
                        return;
                    }

                    const data = await res.json();
                    if (onSuccess) onSuccess(data);

                } catch (err) {
                    console.error('AJAX error:', err);
                }
            }

            // ADD TO CART
            document.querySelectorAll('form.js-add-to-cart').forEach(function (form) {
                const button = form.querySelector('button[type="button"]');
                if (!button) return;

                button.addEventListener('click', function (e) {
                    e.preventDefault();

                    postAjax(form, function (data) {
                        if (cartCountEl && typeof data.cart_count !== 'undefined') {
                            cartCountEl.textContent = data.cart_count;
                        }
                    });
                });
            });

            // ADD TO WISHLIST
            document.querySelectorAll('form.js-add-to-wishlist').forEach(function (form) {
                const button = form.querySelector('button[type="button"]');
                if (!button) return;

                button.addEventListener('click', function (e) {
                    e.preventDefault();

                    postAjax(form, function (data) {
                        if (wishlistCountEl && typeof data.wishlist_count !== 'undefined') {
                            wishlistCountEl.textContent = data.wishlist_count;
                        }
                    });
                });
            });

        });
    </script>
<style>
  /* ✅ نخلي المنيو فوق الكل */
  #customerDropMenu {
    z-index: 999999 !important;
    min-width: 220px;
  }
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const toggle = document.getElementById('customerDropToggle');
    const menu   = document.getElementById('customerDropMenu');

    if (!toggle || !menu) return;

    function placeMenu() {
        const rect = toggle.getBoundingClientRect();

        // نخليها ثابتة في الشاشة (مش جوه navbar) عشان الثيم ميأثرش
        menu.style.position = 'fixed';
        menu.style.top  = (rect.bottom + 8) + 'px';

        // انزليها تحت زر customer وخلّيها aligned يمين
        const width = menu.offsetWidth || 240;
        menu.style.left = (rect.right - width) + 'px';

        menu.style.right = 'auto';
        menu.style.zIndex = 999999;
    }

    // لما تتفتح
    toggle.addEventListener('shown.bs.dropdown', placeMenu);

    // وإعادة حساب مع scroll/resize
    window.addEventListener('scroll', function () {
        if (menu.classList.contains('show')) placeMenu();
    }, true);

    window.addEventListener('resize', function () {
        if (menu.classList.contains('show')) placeMenu();
    });
});
</script>

</body>
</html>
