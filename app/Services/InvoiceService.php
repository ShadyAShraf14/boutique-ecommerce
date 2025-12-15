<?php

namespace App\Services;

use App\Models\Invoice;
use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class InvoiceService
{
    /**
     * ✅ Idempotent:
     * - لو فيه فاتورة موجودة للأوردر => يرجعها (مش بيعمل واحدة جديدة)
     * - لو الـ pdf ناقص/اتمسح => يعيد توليده لنفس الفاتورة
     */
    public function getOrCreateForOrder(Order $order, array $meta = []): Invoice
    {
        return DB::transaction(function () use ($order, $meta) {

            // Lock order row لتفادي double creation لو حصل callback مرتين
            $order = Order::whereKey($order->id)->lockForUpdate()->firstOrFail();

            // لو فيه فاتورة بالفعل (أي نسخة) رجّع آخر واحدة
            $existing = Invoice::where('order_id', $order->id)
                ->orderByDesc('version')
                ->first();

            if ($existing) {
                // لو pdf مش موجود لأي سبب => regenerate
                if (!$existing->pdf_path || !Storage::disk('public')->exists($existing->pdf_path)) {
                    $existing = $this->generateAndStorePdf($order, $existing);
                }
                return $existing;
            }

            // إنشاء أول فاتورة Version 1
            $invoice = new Invoice();
            $invoice->order_id = $order->id;
            $invoice->version  = 1;
            $invoice->invoice_number = $this->generateInvoiceNumber();
            $invoice->issued_at = now();

            // Snapshot بسيط + meta (تقدر تزود براحتك)
            $invoice->snapshot = [
                'order_id'  => $order->id,
                'total'     => (string) $order->total,
                'currency'  => config('paypal.currency', 'USD'),
                'user_id'   => $order->user_id,
            ];
            $invoice->meta = $meta;

            $invoice->save();

            // توليد PDF وتخزينه
            $invoice = $this->generateAndStorePdf($order, $invoice);

            return $invoice;
        });
    }

    protected function generateInvoiceNumber(): string
    {
        // مثال: INV-2025-000045
        $year = now()->format('Y');

        $last = Invoice::whereYear('created_at', now()->year)
            ->orderByDesc('id')
            ->value('invoice_number');

        $nextSeq = 1;

        if ($last && preg_match('/INV-\d{4}-(\d+)/', $last, $m)) {
            $nextSeq = intval($m[1]) + 1;
        }

        return 'INV-' . $year . '-' . str_pad((string) $nextSeq, 6, '0', STR_PAD_LEFT);
    }

    protected function generateAndStorePdf(Order $order, Invoice $invoice): Invoice
    {
        // حمّل العلاقات المطلوبة للفاتورة
        $order->load([
            'user',
            'items.product',
            'address.country',
            'address.state',
            'address.city',
            'shippingMethod',
        ]);

        // ✅ View الفاتورة (غيّر المسار لو اسمك مختلف)
        $pdf = Pdf::loadView('pdf.invoice', [
            'order'   => $order,
            'invoice' => $invoice,
        ]);

        $fileName = $invoice->invoice_number . '.pdf';
        $path = 'invoices/' . $fileName;

        Storage::disk('public')->put($path, $pdf->output());

        $invoice->pdf_path = $path;
        $invoice->save();

        return $invoice;
    }
}
