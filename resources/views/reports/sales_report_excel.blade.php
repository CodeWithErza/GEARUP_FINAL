<table>
    <thead>
        <tr>
            <th colspan="5" style="font-size:18px; text-align:center;">Sales Report</th>
        </tr>
        <tr>
            <th colspan="5" style="text-align:center;">Generated on: {{ now()->format('F d, Y h:i A') }}</th>
        </tr>
        <tr>
            <th colspan="5" style="text-align:center;">Date Range: {{ ucfirst($dateRange) }} ({{ $startDate->format('M d, Y') }} - {{ $endDate->format('M d, Y') }})</th>
        </tr>
        <tr>
            <th colspan="5" style="text-align:center;">Product Category: {{ ucfirst($productCategory) }}</th>
        </tr>
        <tr></tr>
        <tr>
            <th>Category</th>
            <th>Orders</th>
            <th>Quantity</th>
            <th>Total Sales</th>
            <th>% of Sales</th>
        </tr>
    </thead>
    <tbody>
        @forelse($salesByCategory as $item)
            <tr>
                <td>{{ $item->category_name }}</td>
                <td>{{ $item->total_orders }}</td>
                <td>{{ $item->total_quantity }}</td>
                <td>{{ $item->total_sales }}</td>
                <td>{{ $item->percentage }}%</td>
            </tr>
        @empty
            <tr>
                <td colspan="5">No sales data found for this period.</td>
            </tr>
        @endforelse
    </tbody>
    <tfoot>
        <tr>
            <td><strong>Total</strong></td>
            <td><strong>{{ $totalOrders }}</strong></td>
            <td><strong>{{ $totalQuantity }}</strong></td>
            <td><strong>{{ $totalSales }}</strong></td>
            <td><strong>100%</strong></td>
        </tr>
    </tfoot>
</table> 