<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice {{ $order->order_number }}</title>
    <style>
        body {
            font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
            color: #333;
            font-size: 14px;
            line-height: 1.5;
        }
        .invoice-box {
            max-width: 800px;
            margin: auto;
            padding: 30px;
            border: 1px solid #eee;
            box-shadow: 0 0 10px rgba(0, 0, 0, .15);
        }
        .invoice-box table {
            width: 100%;
            line-height: inherit;
            text-align: left;
            border-collapse: collapse;
        }
        .invoice-box table td {
            padding: 5px;
            vertical-align: top;
        }
        .invoice-box table tr td:nth-child(2) {
            text-align: right;
        }
        .invoice-box table tr.top table td {
            padding-bottom: 20px;
        }
        .invoice-box table tr.top table td.title {
            font-size: 35px;
            line-height: 45px;
            color: #800020;
            font-weight: bold;
        }
        .invoice-box table tr.information table td {
            padding-bottom: 40px;
        }
        .invoice-box table tr.heading td {
            background: #eee;
            border-bottom: 1px solid #ddd;
            font-weight: bold;
        }
        .invoice-box table tr.details td {
            padding-bottom: 20px;
        }
        .invoice-box table tr.item td {
            border-bottom: 1px solid #eee;
        }
        .invoice-box table tr.item.last td {
            border-bottom: none;
        }
        .invoice-box table tr.total td:nth-child(2) {
            border-top: 2px solid #eee;
            font-weight: bold;
        }
        .text-right {
            text-align: right !important;
        }
        .text-center {
            text-align: center !important;
        }
    </style>
</head>
<body>
    <div class="invoice-box">
        <table cellpadding="0" cellspacing="0">
            <tr class="top">
                <td colspan="4">
                    <table>
                        <tr>
                            <td class="title">
                                ALPHA DIGITAL
                            </td>
                            <td>
                                Invoice #: {{ $order->order_number }}<br>
                                Created: {{ $order->created_at->format('F d, Y') }}<br>
                                Status: {{ ucfirst($order->status) }}
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>

            <tr class="information">
                <td colspan="4">
                    <table>
                        <tr>
                            <td>
                                <strong>ALPHA DIGITAL</strong><br>
                                123 Fashion Street<br>
                                Mumbai, MH 400001<br>
                                support@alphadigital.com
                            </td>
                            <td>
                                <strong>Billed To:</strong><br>
                                @if($address)
                                    {{ $address->first_name }} {{ $address->last_name }}<br>
                                    {{ $address->address_1 }}<br>
                                    @if($address->address_2){{ $address->address_2 }}<br>@endif
                                    {{ $address->city }}, {{ $address->province }} {{ $address->postal_code }}<br>
                                    {{ $address->country }}<br>
                                    Phone: {{ $address->phone }}
                                @else
                                    {{ $order->customer->name }}<br>
                                    {{ $order->customer->email }}
                                @endif
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>

            <tr class="heading">
                <td>Item</td>
                <td class="text-center">Price</td>
                <td class="text-center">Quantity</td>
                <td class="text-right">Total</td>
            </tr>

            @foreach($order->items as $item)
            <tr class="item {{ $loop->last ? 'last' : '' }}">
                <td>{{ $item->product ? $item->product->name : 'Unknown Product' }}</td>
                <td class="text-center">Rs. {{ number_format($item->price, 2) }}</td>
                <td class="text-center">{{ $item->quantity }}</td>
                <td class="text-right">Rs. {{ number_format($item->price * $item->quantity, 2) }}</td>
            </tr>
            @endforeach

            <tr class="total">
                <td></td>
                <td></td>
                <td></td>
                <td class="text-right">
                   Total: Rs. {{ number_format($order->total_amount, 2) }}
                </td>
            </tr>
        </table>
    </div>
</body>
</html>
