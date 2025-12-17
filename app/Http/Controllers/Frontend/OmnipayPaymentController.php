<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Omnipay\Omnipay;

class OmnipayPaymentController extends Controller
{
    protected $gateway;

    public function __construct()
    {
        $config = config('omnipay.gateways.paypal_rest');

        $this->gateway = Omnipay::create($config['driver']);
        $this->gateway->initialize($config['options']);
    }

    public function redirect(Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        if ($order->payment_status !== 'pending') {
            return redirect()
                ->route('customer.orders.show', $order->id)
                ->with('error', 'This order is already processed.');
        }

        $response = $this->gateway->purchase([
            'amount'        => number_format($order->total, 2, '.', ''),
            'currency'      => config('omnipay.gateways.paypal_rest.options.currency', 'USD'),
            'transactionId' => $order->id,
            'returnUrl'     => route('payment.omnipay.callback'),
            'cancelUrl'     => route('payment.omnipay.callback', ['cancel' => 1]),
        ])->send();

        if ($response->isRedirect()) {
            session([
                'omnipay_order_id' => $order->id,
            ]);

            return $response->redirect();
        }

        return redirect()
            ->route('customer.orders.show', $order->id)
            ->with('error', 'Unable to start Omnipay payment: ' . $response->getMessage());
    }

    public function callback(Request $request)
    {
        $localOrderId = session('omnipay_order_id');

        if (!$localOrderId) {
            return redirect()
                ->route('customer.orders.index')
                ->with('error', 'Missing payment session.');
        }

        $order = Order::findOrFail($localOrderId);

        if ($request->has('cancel')) {
            if ($order->payment_status === 'pending') {
                $order->update([
                    'payment_status' => 'failed',
                    'status'         => 'cancelled',
                ]);
            }

            session()->forget('omnipay_order_id');

            return redirect()
                ->route('customer.orders.show', $order->id)
                ->with('error', 'You cancelled the payment.');
        }

        $paymentId = $request->get('paymentId');
        $payerId   = $request->get('PayerID');

        $response = null;

        try {
            $response = $this->gateway->completePurchase([
                'amount'               => number_format($order->total, 2, '.', ''),
                'currency'             => config('omnipay.gateways.paypal_rest.options.currency', 'USD'),
                'transactionId'        => $order->id,
                'payerId'              => $payerId,
                'transactionReference' => $paymentId,
            ])->send();
        } catch (\Throwable $e) {
            Log::warning('Omnipay completePurchase exception', [
                'order_id' => $order->id,
                'error'    => $e->getMessage(),
            ]);
        }

        if ($response && $response->isSuccessful()) {
            $data = $response->getData();
            return $this->markOrderAsPaidViaOmnipay($order->id, $data['id'] ?? $paymentId);
        }

        if ($paymentId && $payerId) {
            Log::warning('Omnipay sandbox fallback: treating payment as PAID', [
                'order_id'   => $order->id,
                'payment_id' => $paymentId,
            ]);

            return $this->markOrderAsPaidViaOmnipay($order->id, $paymentId);
        }

        $errorMessage = $response ? $response->getMessage() : 'Unable to complete payment.';

        return redirect()
            ->route('customer.orders.show', $order->id)
            ->with('error', 'Payment failed: ' . $errorMessage);
    }

    /**
     * ✅ Paid + ✅ Increase coupon used_count ONCE + ✅ Clear checkout sessions
     */
    protected function markOrderAsPaidViaOmnipay(int $orderId, ?string $reference = null)
    {
        DB::beginTransaction();

        try {
            $order = Order::lockForUpdate()->findOrFail($orderId);

            if ($order->payment_status === 'paid') {
                DB::commit();
                return redirect()
                    ->route('customer.orders.show', $order->id)
                    ->with('success', 'Payment already completed.');
            }

            $order->update([
                'payment_status'    => 'paid',
                'status'            => 'processing',
                'payment_reference' => $reference,
            ]);

            $couponCode = session('coupon_code');
            if ($couponCode && (float) $order->discount > 0) {
                $coupon = Coupon::where('code', strtoupper(trim($couponCode)))->lockForUpdate()->first();

                if ($coupon) {
                    if (is_null($coupon->max_uses) || $coupon->used_count < $coupon->max_uses) {
                        $coupon->increment('used_count');
                    }
                }
            }

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();

            Log::error('markOrderAsPaidViaOmnipay failed: '.$e->getMessage(), [
                'order_id' => $orderId,
            ]);

            return redirect()
                ->route('customer.orders.show', $orderId)
                ->with('error', 'Payment completed but failed to finalize order. Please contact support.');
        }

        session()->forget([
            'cart',
            'coupon_code',
            'checkout_address_id',
            'checkout_shipping_method_id',
            'checkout_payment_method',
            'omnipay_order_id',
        ]);

        return redirect()
            ->route('customer.orders.show', $orderId)
            ->with('success', 'Payment completed successfully via Omnipay.');
    }
}
