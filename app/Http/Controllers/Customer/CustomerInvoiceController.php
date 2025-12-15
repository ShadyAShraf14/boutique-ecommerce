<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use Illuminate\Support\Facades\Storage;

class CustomerInvoiceController extends Controller
{
    private function authorizeInvoice(Invoice $invoice): void
    {
        $order = $invoice->order;

        if (!$order || $order->user_id !== auth()->id()) {
            abort(403);
        }

        if (!$invoice->pdf_path || !Storage::disk('public')->exists($invoice->pdf_path)) {
            abort(404, 'Invoice file not found.');
        }
    }

    // ✅ Download as attachment
    public function download(Invoice $invoice)
    {
        $this->authorizeInvoice($invoice);

        $name = ($invoice->invoice_number ?: 'invoice') . '.pdf';

        return Storage::disk('public')->download($invoice->pdf_path, $name);
    }

    // ✅ View inline in browser (no forced download)
    public function view(Invoice $invoice)
    {
        $this->authorizeInvoice($invoice);

        $name = ($invoice->invoice_number ?: 'invoice') . '.pdf';

        // response() بيبعت الهيدر Content-Disposition: inline
        return Storage::disk('public')->response(
            $invoice->pdf_path,
            $name,
            [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="'.$name.'"',
            ]
        );
    }
}
