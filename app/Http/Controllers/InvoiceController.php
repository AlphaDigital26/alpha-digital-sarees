<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;

class InvoiceController extends Controller
{
    public function download(Order $order)
    {
        // Ensure only the owner can download the invoice
        if ($order->customer_id !== auth('customer')->id()) {
            abort(403, 'Unauthorized action.');
        }

        // Load relationships
        $order->load(['items.product', 'customer.addresses' => function ($q) {
            $q->where('is_default', true);
        }]);

        // Get the default address or first address
        $address = auth('customer')->user()->addresses()->where('is_default', true)->first() 
                   ?? auth('customer')->user()->addresses()->first();

        // Render PDF
        $pdf = Pdf::loadView('invoices.order', compact('order', 'address'));

        return $pdf->download('invoice_' . $order->order_number . '.pdf');
    }
}
