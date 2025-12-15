@extends('Backend.inc.master')

@section('content')
<div class="container-fluid mt-4">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="mb-0">Notifications</h5>

        @if($notifications->count())
            <form action="{{ route('dashboard.notifications.readAll') }}" method="POST">
                @csrf
                <button class="btn btn-outline-secondary btn-sm">
                    Mark all as read
                </button>
            </form>
        @endif
    </div>

    @include('Backend.inc.flash')

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <table class="table table-sm mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Message</th>
                        <th width="160">Type</th>
                        <th width="160">Created at</th>
                        <th width="120">Status</th>
                        <th width="120">Actions</th>
                    </tr>
                </thead>

                {{-- مهم جداً: الـ id ده عشان الـ JS يقدر يضيف صفوف جديدة لايف --}}
                <tbody id="notifications-table-body">
                @forelse($notifications as $notification)
                    @php
                        $data = $notification->data;
                        $isUnread = is_null($notification->read_at);
                    @endphp
                    <tr @if($isUnread) style="background-color:#f9fcff;" @endif>
                        <td>
                            @if(($data['type'] ?? null) === 'admin_new_paid_order')
                                New paid order #{{ $data['order_id'] ?? '' }}
                                from {{ $data['customer_name'] ?? 'Customer' }}
                                – Total:
                                ${{ number_format($data['order_total'] ?? 0, 2) }}
                            @else
                                {{ $data['type'] ?? 'Notification' }}
                            @endif
                        </td>
                        <td class="text-muted">
                            {{ $data['type'] ?? '—' }}
                        </td>
                        <td>
                            {{ $notification->created_at->format('Y-m-d H:i') }}
                        </td>
                        <td>
                            @if($isUnread)
                                <span class="badge bg-warning text-dark">Unread</span>
                            @else
                                <span class="badge bg-success">Read</span>
                            @endif
                        </td>
                        <td>
                            @if($isUnread)
                                <form action="{{ route('dashboard.notifications.read', $notification->id) }}"
                                      method="POST" class="d-inline">
                                    @csrf
                                    <button class="btn btn-link btn-sm p-0">
                                        Mark as read
                                    </button>
                                </form>
                            @else
                                <span class="text-muted small">—</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted py-3">
                            No notifications yet.
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        @if($notifications->hasPages())
            <div class="card-footer">
                {{ $notifications->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
