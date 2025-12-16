<!DOCTYPE html>
<html>

@include('Frontend.inc.head')

<body>
    <div class="page-holder">

        @include('Frontend.inc.navbar')

        @yield('content')

        @include('Frontend.inc.footer')

        @include('Frontend.inc.scripts')

        <!-- FontAwesome CSS - loading as last, so it doesn't block rendering-->
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css"
            integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {

            const cartCountEl     = document.querySelector('[data-cart-count]');
            const wishlistCountEl = document.querySelector('[data-wishlist-count]');

            // Helper: يبعته POST AJAX + يتعامل مع 401 Redirect
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

                    // ✅ لو Guest ومحمي بـ auth: السيرفر لازم يرجّع 401
                    if (res.status === 401) {
                        let data = null;
                        try { data = await res.json(); } catch (e) {}

                        const redirectUrl = (data && data.redirect) ? data.redirect : "{{ route('login') }}";
                        window.location.href = redirectUrl;
                        return;
                    }

                    // لو مش JSON لأي سبب (redirect/html) -> ودّيه login كحل آمن
                    const contentType = res.headers.get('content-type') || '';
                    if (!contentType.includes('application/json')) {
                        // غالباً ده Redirect HTML أو Error page
                        // نرجّعه لصفحة الدخول كحل آمن
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
                // لازم الزرار يكون type="button" عشان ما يعملش submit عادي
                const button = form.querySelector('button[type="button"]');
                if (!button) return;

                button.addEventListener('click', function (e) {
                    e.preventDefault();

                    postAjax(form, function (data) {
                        if (cartCountEl && typeof data.cart_count !== 'undefined') {
                            cartCountEl.textContent = data.cart_count;
                        }
                        // لو عندك Toast قديم سيبيه هنا زي ما هو
                        // if (data.message) console.log(data.message);
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
                        // لو عندك Toast قديم سيبيه هنا زي ما هو
                        // if (data.message) console.log(data.message);
                    });
                });
            });

        });
    </script>

</body>
</html>
