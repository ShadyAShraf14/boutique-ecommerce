<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminNotificationController extends Controller
{
    /**
     * عرض قائمة التنبيهات
     */
    public function index(Request $request)
    {
        $user = $request->user('admin'); // أو $request->user() لو شغال كويس عندك

        $notifications = $user->notifications()
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('Backend.pages.notifications.index', compact('notifications'));
    }

    /**
     * تعليم تنبيه واحد كمقروء
     */
    public function markAsRead(Request $request, string $id)
    {
        $user = $request->user('admin');

        $notification = $user->notifications()->where('id', $id)->firstOrFail();

        if (is_null($notification->read_at)) {
            $notification->markAsRead();
        }

        return back()->with('success', 'Notification marked as read.');
    }

    /**
     * تعليم كل التنبيهات كمقروءة
     */
    public function markAllAsRead(Request $request)
    {
        $user = $request->user('admin');

        $user->unreadNotifications->markAsRead();

        return back()->with('success', 'All notifications marked as read.');
    }
}
