<!-- Topbar -->
<nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

    <!-- Sidebar Toggle (Topbar) -->
    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
        <i class="fa fa-bars"></i>
    </button>

    <!-- Topbar Search -->
    <form class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search">
        <div class="input-group">
            <input type="text" class="form-control bg-light border-0 small" placeholder="Search for..."
                   aria-label="Search" aria-describedby="basic-addon2">
            <div class="input-group-append">
                <button class="btn btn-primary" type="button">
                    <i class="fas fa-search fa-sm"></i>
                </button>
            </div>
        </div>
    </form>

    <!-- Topbar Navbar -->
    <ul class="navbar-nav ml-auto">

        @php
            $admin = auth()->user();

            $unreadNotifications = $admin
                ? $admin->unreadNotifications()->latest()->take(5)->get()
                : collect();

            $unreadCount = $admin
                ? $admin->unreadNotifications()->count()
                : 0;
        @endphp

        <!-- Nav Item - Alerts (notifications) -->
        <li class="nav-item dropdown no-arrow mx-1">
            <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button"
               data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-bell fa-fw"></i>

                @if($unreadCount > 0)
                    <span id="notif-count" class="badge badge-danger badge-counter">
                        {{ $unreadCount > 9 ? '9+' : $unreadCount }}
                    </span>
                @endif
            </a>

            <div id="alertsDropdownMenu"
                 class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in"
                 aria-labelledby="alertsDropdown">

                <h6 class="dropdown-header">
                    Notifications
                </h6>

                <div id="notif-list">
                    @forelse($unreadNotifications as $notification)
                        @php
                            $data = (array) $notification->data;

                            // حالة خاصة: لو إشعار "طلب مدفوع" عندك بنفس المفاتيح القديمة
                            $isPaidOrder =
                                (($data['type'] ?? null) === 'admin_new_paid_order')
                                || (($data['event'] ?? null) === 'admin_new_paid_order');

                            // مفاتيح شائعة للعنوان والرسالة
                            $title = $data['title'] ?? $data['subject'] ?? $data['name'] ?? null;
                            $msg   = $data['message'] ?? $data['body'] ?? $data['text'] ?? $data['content'] ?? null;

                            // لو مفيش title/msg: هات أول قيمة نصية من الداتا
                            if (!$title && !$msg) {
                                foreach ($data as $v) {
                                    if (is_string($v) && trim($v) !== '') {
                                        $msg = $v;
                                        break;
                                    }
                                }
                            }

                            // fallback أخير: اسم كلاس الإشعار بدل كلمة Notification
                            $fallbackText = class_basename($notification->type);

                            // نص السطر الرئيسي
                            $line1 = $title
                                ?? ($msg ? \Illuminate\Support\Str::limit($msg, 80) : $fallbackText);

                            // سطر إضافي لو عندنا title + msg
                            $line2 = ($msg && $title)
                                ? \Illuminate\Support\Str::limit($msg, 120)
                                : null;
                        @endphp

                        <a class="dropdown-item d-flex align-items-center"
                           href="{{ route('dashboard.notifications.index') }}">

                            <div class="mr-3">
                                <div class="icon-circle bg-primary">
                                    <i class="fas fa-shopping-bag text-white"></i>
                                </div>
                            </div>

                            <div style="min-width: 0;">
                                <div class="small text-gray-500">
                                    {{ $notification->created_at->diffForHumans() }}
                                </div>

                                @if($isPaidOrder)
                                    <span class="font-weight-bold">
                                        New paid order #{{ $data['order_id'] ?? $data['order'] ?? '' }}
                                    </span>

                                    <div class="small text-gray-700">
                                        Customer: {{ $data['customer_name'] ?? $data['customer'] ?? 'Customer' }}
                                        – Total: ${{ number_format($data['order_total'] ?? $data['total'] ?? 0, 2) }}
                                    </div>
                                @else
                                    <span class="font-weight-bold">
                                        {{ $line1 }}
                                    </span>

                                    @if($line2)
                                        <div class="small text-gray-700">
                                            {{ $line2 }}
                                        </div>
                                    @endif
                                @endif
                            </div>

                        </a>
                    @empty
                        <div class="dropdown-item text-center small text-gray-500 no-notifications-placeholder">
                            No new notifications
                        </div>
                    @endforelse
                </div>

                <a class="dropdown-item text-center small text-gray-500"
                   href="{{ route('dashboard.notifications.index') }}">
                    View all notifications
                </a>
            </div>
        </li>

        <div class="topbar-divider d-none d-sm-block"></div>

        @auth
            <!-- Nav Item - User Information -->
            <li class="nav-item dropdown no-arrow">
                <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                   data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <span class="mr-2 d-none d-lg-inline text-gray-600 small">
                        {{ auth()->user()->name }}
                    </span>

                    @php
                        $avatar = auth()->user()->avatar
                            ? asset('storage/'.auth()->user()->avatar)
                            : url('assets/img/undraw_profile.svg');
                    @endphp

                    <img class="img-profile rounded-circle" src="{{ $avatar }}">
                </a>

                <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                     aria-labelledby="userDropdown">

                    <a class="dropdown-item" href="{{ route('admin.account.edit') }}">
                        <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                        Account settings
                    </a>

                    <div class="dropdown-divider"></div>

                    <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                        <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                        Logout
                    </a>
                </div>
            </li>
        @endauth

    </ul>

</nav>
<!-- End of Topbar -->
