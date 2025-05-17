<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Receipt #{{ $order->order_number }}</title>
    <style>
        /* Receipt-specific styles */
        @media print {
            body {
                font-family: 'Courier New', monospace;
                font-size: 10pt;
                line-height: 1.15;
                margin: 0;
                padding: 0;
            }
            .receipt {
                width: 80mm; /* Standard thermal receipt width */
                max-width: 80mm;
                margin: 0 auto;
                padding: 3mm;
            }
            .header, .footer {
                text-align: center;
                margin-bottom: 3mm;
            }
            .logo {
                max-width: 50mm;
                height: auto;
                margin: 0 auto 2mm;
                display: block;
            }
            .divider {
                border-top: 1px dashed #000;
                margin: 2mm 0;
            }
            .info {
                margin-bottom: 3mm;
            }
            .info p {
                margin: 0;
                padding: 0;
            }
            table {
                width: 100%;
                border-collapse: collapse;
            }
            .items th, .items td {
                text-align: left;
                padding: 1mm 0;
            }
            .items .price, .items .qty, .items .subtotal {
                text-align: right;
            }
            .summary {
                text-align: right;
                margin: 2mm 0;
            }
            .summary .label {
                text-align: right;
                padding-right: 10mm;
            }
            .summary .value {
                text-align: right;
                width: 20mm;
            }
            .thank-you {
                text-align: center;
                margin-top: 5mm;
            }
            .btn-print {
                display: none; /* Hide print button when printing */
            }
        }

        /* Screen styles (only visible before printing) */
        @media screen {
            body {
                font-family: Arial, sans-serif;
                line-height: 1.5;
                padding: 20px;
                background-color: #f5f5f5;
            }
            .receipt {
                width: 80mm;
                max-width: 80mm;
                margin: 0 auto;
                background-color: white;
                padding: 10mm;
                box-shadow: 0 3px 10px rgba(0,0,0,0.2);
            }
            .btn-print {
                display: block;
                margin: 20px auto;
                padding: 10px 20px;
                background-color: #4CAF50;
                color: white;
                border: none;
                border-radius: 4px;
                cursor: pointer;
                font-size: 16px;
            }
            .btn-print:hover {
                background-color: #45a049;
            }
            .header, .footer {
                text-align: center;
                margin-bottom: 15px;
            }
            .divider {
                border-top: 1px dashed #ccc;
                margin: 10px 0;
            }
            .logo {
                max-width: 60mm;
                height: auto;
                margin: 0 auto 10px;
                display: block;
            }
        }
    </style>
</head>
<body>
    <button class="btn-print" onclick="window.print()">Print Receipt</button>
    
    <div class="receipt">
        <div class="header">
            <img src="{{ asset('images/logo.png') }}" alt="GearUp Auto Parts Logo" class="logo">
            <h2>GearUp Auto Parts</h2>
            <p>Davao City, Philippines</p>
            <p>Tel: (123) 456-7890</p>
        </div>
        
        <div class="divider"></div>
        
        <div class="info">
            <p><strong>Order #:</strong> {{ $order->order_number }}</p>
            <p><strong>Date:</strong> {{ $order->created_at->format('F d, Y h:i A') }}</p>
            <p><strong>Customer:</strong> {{ $order->customer->name }}</p>
            @if($order->customer->phone)
            <p><strong>Phone:</strong> {{ $order->customer->phone }}</p>
            @endif
        </div>
        
        <div class="divider"></div>
        
        <table class="items">
            <thead>
                <tr>
                    <th width="50%">Item</th>
                    <th class="price">Price</th>
                    <th class="qty">Qty</th>
                    <th class="subtotal">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $item)
                <tr>
                    <td>{{ $item->product->name }}</td>
                    <td class="price">₱{{ number_format($item->price, 2) }}</td>
                    <td class="qty">{{ $item->quantity }}</td>
                    <td class="subtotal">₱{{ number_format($item->subtotal, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        
        <div class="divider"></div>
        
        <table class="summary">
            <tr>
                <td class="label">Subtotal:</td>
                <td class="value">₱{{ number_format($order->subtotal, 2) }}</td>
            </tr>
            <tr>
                <td class="label">Tax (12%):</td>
                <td class="value">₱{{ number_format($order->tax, 2) }}</td>
            </tr>
            @if($order->discount_amount > 0)
            <tr>
                <td class="label">
                    Discount:
                    @if($order->discount_percentage > 0)
                        ({{ $order->discount_percentage }}%)
                    @endif
                </td>
                <td class="value">₱{{ number_format($order->discount_amount, 2) }}</td>
            </tr>
            @endif
            <tr>
                <td class="label"><strong>Total:</strong></td>
                <td class="value"><strong>₱{{ number_format($order->total, 2) }}</strong></td>
            </tr>
        </table>
        
        <div class="divider"></div>
        
        <div class="payment">
            <p><strong>Payment Method:</strong> {{ ucfirst($order->payment_method) }}</p>
            <p><strong>Amount Received:</strong> ₱{{ number_format($order->amount_received, 2) }}</p>
            @if($order->payment_method === 'cash')
            <p><strong>Change:</strong> ₱{{ number_format($order->change, 2) }}</p>
            @endif
            @if($order->payment_reference)
            <p><strong>Reference:</strong> {{ $order->payment_reference }}</p>
            @endif
        </div>
        
        @if($order->notes)
        <div class="divider"></div>
        <div class="notes">
            <p><strong>Notes:</strong> {{ $order->notes }}</p>
        </div>
        @endif
        
        <div class="divider"></div>
        
        <div class="footer">
            <p>Thank you for your purchase!</p>
            <p>Please come again</p>
        </div>
    </div>
    
    <script>
        // Auto-print when the page loads
        window.onload = function() {
            // Small delay to ensure the page is fully rendered
            setTimeout(function() {
                window.print();
            }, 500);
        };
    </script>
</body>
</html> 