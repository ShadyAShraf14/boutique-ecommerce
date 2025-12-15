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

    // Helper: يبعته POST AJAX
    function postAjax(form, onSuccess) {
        const url   = form.getAttribute('action');
        const token = form.querySelector('input[name=_token]').value;

        const formData = new FormData(form);

        fetch(url, {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': token,
                'Accept': 'application/json',
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (onSuccess) onSuccess(data);
            // ممكن هنا تعمل Toast بسيط إن المنتج اتضاف
            // console.log(data.message);
        })
        .catch(err => {
            console.error('AJAX error:', err);
        });
    }

    // ADD TO CART
    document.querySelectorAll('.js-add-to-cart').forEach(function (form) {
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
    document.querySelectorAll('.js-add-to-wishlist').forEach(function (form) {
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

</body>

</html>
