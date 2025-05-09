<?php

namespace App\Exports;

use App\Models\Order;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class OrderHistoryExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    protected $query;

    public function __construct($query)
    {
        $this->query = $query;
    }

    public function collection()
    {
        return $this->query->get();
    }

    public function headings(): array
    {
        return [
            'Order #',
            'Customer',
            'Date',
            'Items',
            'Subtotal',
            'Tax',
            'Total',
            'Payment Method',
            'Status'
        ];
    }

    public function map($order): array
    {
        return [
            $order->order_number,
            $order->customer->name,
            $order->created_at->format('M d, Y h:i A'),
            $order->items->count(),
            number_format($order->subtotal, 2),
            number_format($order->tax, 2),
            number_format($order->total, 2),
            ucfirst($order->payment_method),
            ucfirst($order->status)
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
            'A1:I1' => [
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'F8F9FA']
                ]
            ]
        ];
    }
} 