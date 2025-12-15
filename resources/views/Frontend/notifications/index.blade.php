@extends('Frontend.inc.master')

@section('content')
<section class="py-5">
    <div class="container">

        <div class="d-flex align-items-center justify-content-between mb-4">
            <div>
                <h3 class="mb-1">My Notifications</h3>
                <p class="text-muted mb-0">Keep track of your latest updates</p>
            </div>

            @if($notifications->count())
                <form method="POST" action="{{ route('front.notifications.readAll') }}" class="m-0">
                    @csrf
                    <button class="btn btn-outline-dark btn-sm">
                        Mark all as read
                    </button>
                </form>
            @endif
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body p-0">

                @if($notifications->count() == 0)
                    <div class="p-4 text-center text-muted">
                        No notifications yet.
                    </div>
                @else
                    <div class="list-group list-group-flush">
                        @foreach($notifications as $n)
                            @php
                                $data = $n->data ?? [];
                                $isUnread = is_null($n->read_at);

                                $title = $data['message'] ?? ($data['type'] ?? 'Notification');

                                if (($data['type'] ?? null) === 'customer_order_paid') {
                                    $title = 'Your order #'.($data['order_id'] ?? '').' is paid';
                                }

                                // لينك يودّي للأوردر (اختياري) - احنا أصلاً بنعمل redirect في controller
                                $openUrl = route('front.notifications.open', $n->id);
                            @endphp

                            <a href="{{ $openUrl }}"
                               class="list-group-item list-group-item-action d-flex align-items-start gap-3 py-3
                               @if($isUnread) bg-light @endif">

                                {{-- Dot --}}
                                <span class="mt-2"
                                      style="width:10px;height:10px;border-radius:50%;
                                      background: {{ $isUnread ? '#0d6efd' : '#c7c7c7' }}; flex:0 0 10px;">
                                </span>

                                {{-- Content --}}
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-start gap-3">
                                        <div>
                                            <div class="fw-semibold text-dark" style="line-height:1.3;">
                                                {{ $title }}
                                            </div>

                                            <div class="text-muted small mt-1">
                                                {{ $n->created_at->format('Y-m-d H:i') }}
                                                <span class="mx-2">•</span>
                                                {{ $n->created_at->diffForHumans() }}
                                            </div>
                                        </div>

                                        {{-- Status badge --}}
                                        @if($isUnread)
                                            <span class="badge bg-warning text-dark mt-1">Unread</span>
                                        @else
                                            <span class="badge bg-success mt-1">Read</span>
                                        @endif
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                @endif

            </div>

            @if($notifications->hasPages())
                <div class="card-footer bg-white border-0 pt-0 pb-3">
                    <div class="px-3">
                        {{ $notifications->links() }}
                    </div>
                </div>
            @endif
        </div>

    </div>
</section>
@endsection
