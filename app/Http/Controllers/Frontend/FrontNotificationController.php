<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FrontNotificationController extends Controller
{
    protected function user()
    {
        return auth()->guard('web')->user();
    }

    public function index()
    {
        $user = $this->user();
        if (!$user) {
            return redirect()->route('login');
        }

        $notifications = $user->notifications()
            ->latest()
            ->paginate(15);

        return view('Frontend.notifications.index', compact('notifications'));
    }

    public function open($id)
    {
        $user = $this->user();
        if (!$user) {
            return redirect()->route('login');
        }

        $notification = $user->notifications()
            ->where('id', $id)
            ->firstOrFail();

        if (is_null($notification->read_at)) {
            $notification->markAsRead();
        }

        $data = $notification->data ?? [];

        // لو إشعار دفع أوردر
        if (
            ($data['type'] ?? null) === 'customer_order_paid'
            && !empty($data['order_id'])
        ) {
            return redirect()
                ->route('customer.orders.show', $data['order_id']);
        }

        return redirect()->back();
    }

public function markAllAsRead(Request $request)
{
    $user = $this->user();
    if (!$user) {
        return $request->expectsJson()
            ? response()->json(['message' => 'Unauthenticated'], 401)
            : redirect()->route('login');
    }

    $user->unreadNotifications->markAsRead();

    if ($request->expectsJson()) {
        return response()->json([
            'message' => 'All notifications marked as read',
            'unread_count' => 0,
        ]);
    }

    return back()->with('success', 'All notifications marked as read.');
}
}
