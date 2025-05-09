<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Sales Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h1 {
            margin: 0;
            color: #333;
            font-size: 24px;
        }
        .header p {
            margin: 5px 0;
            color: #666;
        }
        .filters {
            margin-bottom: 20px;
            padding: 10px;
            background-color: #f8f9fa;
            border-radius: 4px;
        }
        .filters p {
            margin: 5px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f8f9fa;
            font-weight: bold;
        }
        tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 11px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Sales Report</h1>
        <p>Generated on: {{ now()->format('F d, Y h:i A') }}</p>
    </div>

    <div class="filters">
        <p><strong>Date Range:</strong> {{ ucfirst($dateRange) }} ({{ $startDate->format('M d, Y') }} - {{ $endDate->format('M d, Y') }})</p>
        <p><strong>Product Category:</strong> {{ ucfirst($productCategory) }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Category</th>
                <th class="text-end">Orders</th>
                <th class="text-end">Quantity</th>
                <th class="text-end">Total Sales</th>
                <th class="text-end">% of Sales</th>
            </tr>
        </thead>
        <tbody>
            @forelse($salesByCategory as $item)
                <tr>
                    <td>{{ $item->category_name }}</td>
                    <td class="text-end">{{ number_format($item->total_orders) }}</td>
                    <td class="text-end">{{ number_format($item->total_quantity) }}</td>
                    <td class="text-end">{{ number_format($item->total_sales, 2) }}</td>
                    <td class="text-end">{{ $item->percentage }}%</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center">No sales data found for this period.</td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr>
                <td><strong>Total</strong></td>
                <td class="text-end"><strong>{{ number_format($totalOrders) }}</strong></td>
                <td class="text-end"><strong>{{ number_format($totalQuantity) }}</strong></td>
                <td class="text-end"><strong>{{ number_format($totalSales, 2) }}</strong></td>
                <td class="text-end"><strong>100%</strong></td>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        <p>© {{ date('Y') }} GearUp. All rights reserved.</p>
    </div>
</body>
</html> 