<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;

class OrderController extends Controller
{
    // قائمة طلبات العميل
    public function index()
    {
        $orders = Order::where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('Customer.orders.index', compact('orders'));
    }

    // عرض تفاصيل طلب واحد
public function show(Order $order)
{
    if ($order->user_id !== Auth::id()) {
        abort(403);
    }

    $order->load([
        'items.product',
        'address.country',
        'address.state',
        'address.city',
        'shippingMethod',
        'latestInvoice', // ✅ مهم
    ]);

    $canCancel = $this->canCancel($order);

    return view('Customer.orders.show', compact('order', 'canCancel'));
}

    // إلغاء الطلب
    public function cancel(Request $request, Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        if (! $this->canCancel($order)) {
            return redirect()
                ->route('customer.orders.show', $order)
                ->with('error', 'You can no longer cancel this order.');
        }

        $order->status = 'cancelled';
        $order->save();

        // هنا ممكن لاحقاً تزود لوجيك Refund لو حابب

        return redirect()
            ->route('customer.orders.show', $order)
            ->with('success', 'Order cancelled successfully.');
    }

    /**
     * منطق تحديد صلاحية إلغاء الطلب
     */
    protected function canCancel(Order $order): bool
    {
        // خلال آخر 5 أيام
        $withinFiveDays = $order->created_at->gte(now()->subDays(5));

        // في حالة من الحالات دي
        $statusAllowed = in_array($order->status, ['pending', 'processing']);

        return $withinFiveDays && $statusAllowed;
    }
}
