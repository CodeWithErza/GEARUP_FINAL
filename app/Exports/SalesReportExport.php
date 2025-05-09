<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class SalesReportExport implements FromView
{
    protected $salesByCategory;
    protected $totalSales;
    protected $totalOrders;
    protected $totalQuantity;
    protected $dateRange;
    protected $productCategory;
    protected $startDate;
    protected $endDate;

    public function __construct($salesByCategory, $totalSales, $totalOrders, $totalQuantity, $dateRange, $productCategory, $startDate, $endDate)
    {
        $this->salesByCategory = $salesByCategory;
        $this->totalSales = $totalSales;
        $this->totalOrders = $totalOrders;
        $this->totalQuantity = $totalQuantity;
        $this->dateRange = $dateRange;
        $this->productCategory = $productCategory;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function view(): View
    {
        return view('reports.sales_report_excel', [
            'salesByCategory' => $this->salesByCategory,
            'totalSales' => $this->totalSales,
            'totalOrders' => $this->totalOrders,
            'totalQuantity' => $this->totalQuantity,
            'dateRange' => $this->dateRange,
            'productCategory' => $this->productCategory,
            'startDate' => $this->startDate,
            'endDate' => $this->endDate
        ]);
    }
} 