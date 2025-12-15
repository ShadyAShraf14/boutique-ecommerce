<!DOCTYPE html>
<html lang="en">

@include('Backend.inc.head')

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        {{-- Sidebar لازم يكون جوا الـ wrapper --}}
        @include('Backend.inc.sidebar')

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                {{-- Navbar لازم يكون جوا الـ content --}}
                @include('Backend.inc.navbar')

                {{-- صفحة الكونتنت --}}
                @yield('content')

            </div>
            <!-- End of Main Content -->

            @include('Backend.inc.footer')

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    @include('Backend.inc.logout')

    {{-- ✅ لازم تعريف المتغيرات قبل vite --}}
    @php
        $admin = auth('admin')->user(); // ✅ هنا التعديل
    @endphp

    <script>
        window.AuthUserId = {{ $admin?->id ?? 'null' }};
        window.NotificationsIndexUrl = "{{ route('dashboard.notifications.index') }}";
    </script>

    {{-- ✅ vite قبل scripts --}}
    @vite(['resources/js/app.js'])

    {{-- باقي سكربتات الثيم --}}
    @include('Backend.inc.scripts')

</body>
</html>
