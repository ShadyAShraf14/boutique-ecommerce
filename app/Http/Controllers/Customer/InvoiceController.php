<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class InvoiceController extends Controller
{
    public function download(Invoice $invoice)
    {
        // 🔐 تأمين: الفاتورة لازم تكون بتاعة أوردر المستخدم
        if ($invoice->order->user_id !== Auth::id()) {
            abort(403);
        }

        // ❌ لو مفيش ملف
        if (!$invoice->pdf_path || !Storage::disk('public')->exists($invoice->pdf_path)) {
            abort(404, 'Invoice file not found.');
        }

        return Storage::disk('public')->download(
            $invoice->pdf_path,
            $invoice->invoice_number . '.pdf'
        );
    }
}
