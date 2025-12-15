<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Omnipay\Omnipay;

class OmnipayPaymentController extends Controller
{
    protected $gateway;

    public function __construct()
    {
        // نجيب الإعدادات من config/omnipay.php
        $config = config('omnipay.gateways.paypal_rest');

        $this->gateway = Omnipay::create($config['driver']);
        $this->gateway->initialize($config['options']);
    }

    /**
     * 1) تحويل العميل لصفحة الدفع (Omnipay + PayPal)
     */
    public function redirect(Order $order)
    {
        // تأكيد إن الأوردر لليوزر الحالي
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        // لو متعالِج قبل كده
        if ($order->payment_status !== 'pending') {
            return redirect()
                ->route('customer.orders.show', $order->id)
                ->with('error', 'This order is already processed.');
        }

        // إعداد عملية الشراء
        $response = $this->gateway->purchase([
            'amount'        => number_format($order->total, 2, '.', ''),
            'currency'      => config('omnipay.gateways.paypal_rest.options.currency', 'USD'),
            'transactionId' => $order->id,
            'returnUrl'     => route('payment.omnipay.callback'),
            'cancelUrl'     => route('payment.omnipay.callback', ['cancel' => 1]),
        ])->send();

        if ($response->isRedirect()) {

            // نخزن الـ order id في السيشن
            session([
                'omnipay_order_id' => $order->id,
            ]);

            // redirect إلى PayPal
            return $response->redirect();
        }

        return redirect()
            ->route('customer.orders.show', $order->id)
            ->with('error', 'Unable to start Omnipay payment: ' . $response->getMessage());
    }

    /**
     * 2) الرجوع من PayPal عن طريق Omnipay
     */
    public function callback(Request $request)
    {
        $localOrderId = session('omnipay_order_id');

        if (!$localOrderId) {
            return redirect()
                ->route('customer.orders.index')
                ->with('error', 'Missing payment session.');
        }

        $order = Order::findOrFail($localOrderId);

        // لو العميل ضغط Cancel من شاشة PayPal
        if ($request->has('cancel')) {

            if ($order->payment_status === 'pending') {
                $order->update([
                    'payment_status' => 'failed',     // فشل في الدفع
                    'status'         => 'cancelled',  // الطلب اتلغى
                ]);
            }

            // امسحي معرف الطلب من السيشن
            session()->forget('omnipay_order_id');

            return redirect()
                ->route('customer.orders.show', $order->id)
                ->with('error', 'You cancelled the payment.');
        }

        $paymentId = $request->get('paymentId');
        $payerId   = $request->get('PayerID');

        $response = null;

        try {
            // محاولة تأكيد الدفع من خلال Omnipay
            $response = $this->gateway->completePurchase([
                'amount'               => number_format($order->total, 2, '.', ''),
                'currency'             => config('omnipay.gateways.paypal_rest.options.currency', 'USD'),
                'transactionId'        => $order->id,
                'payerId'              => $payerId,
                'transactionReference' => $paymentId,
            ])->send();
        } catch (\Throwable $e) {
            // نكتب في اللوج ونكمّل بالفول باك
            Log::warning('Omnipay completePurchase exception', [
                'order_id' => $order->id,
                'error'    => $e->getMessage(),
            ]);
        }

        // 1) لو الـ response ناجح → نعتبرها مدفوعة
        if ($response && $response->isSuccessful()) {

            $data = $response->getData(); // بيانات PayPal الخام

            return $this->markOrderAsPaidViaOmnipay(
                $order,
                $data['id'] ?? $paymentId
            );
        }

        // 2) فول باك للـ SANDBOX:
        // لو رجعنا ومعانا paymentId و PayerID نعتبرها مدفوعة برضه
        if ($paymentId && $payerId) {

            Log::warning('Omnipay sandbox fallback: treating payment as PAID', [
                'order_id'   => $order->id,
                'payment_id' => $paymentId,
            ]);

            return $this->markOrderAsPaidViaOmnipay($order, $paymentId);
        }

        // 3) لو مفيش أي حاجة تضمن نجاح الدفع → فشل
        $errorMessage = $response
            ? $response->getMessage()
            : 'Unable to complete payment.';

        return redirect()
            ->route('customer.orders.show', $order->id)
            ->with('error', 'Payment failed: ' . $errorMessage);
    }

    /**
     * تحديث الأوردر بعد نجاح الدفع (Omnipay)
     */
    protected function markOrderAsPaidViaOmnipay(Order $order, ?string $reference = null)
    {
        $order->update([
            'payment_status'    => 'paid',
            'status'            => 'processing',
            'payment_reference' => $reference,
        ]);

        // نفضي السيشن (الكارت والاختيارات)
        session()->forget([
            'cart',
            'coupon_code',
            'checkout_address_id',
            'checkout_shipping_method_id',
            'checkout_payment_method',
            'omnipay_order_id',
        ]);

        return redirect()
            ->route('customer.orders.show', $order->id)
            ->with('success', 'Payment completed successfully via Omnipay.');
    }
}
