<?php
namespace App\Http\Controllers;
 
use App\Exports\RevenueExport;
use App\Exports\StockReportExport;
use App\Models\ServiceOrder;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
 
class ExportController extends Controller
{
    // ──────────────────────────────────────────────────────────
    // PDF — Laporan Pendapatan (akses: superadmin)
    // ──────────────────────────────────────────────────────────
    public function revenuePdf(Request $request)
    {
        $month = $request->get('month', now()->format('m'));
        $year  = $request->get('year',  now()->format('Y'));
 
        $orders = ServiceOrder::with(['user', 'items'])
            ->where('status', 'done')
            ->whereYear('completed_at', $year)
            ->whereMonth('completed_at', $month)
            ->latest('completed_at')
            ->get();
 
        $totalRevenue    = $orders->sum('grand_total');
        $totalServiceFee = $orders->sum('service_fee');
        $totalItemsCost  = $orders->sum('total_items_cost');
        $periodLabel     = Carbon::create($year, $month)->translatedFormat('F Y');
 
        $pdf = Pdf::loadView('exports.revenue-pdf', compact(
            'orders', 'totalRevenue', 'totalServiceFee', 'totalItemsCost', 'periodLabel', 'month', 'year'
        ))->setPaper('a4', 'portrait');
 
        return $pdf->download("laporan-pendapatan-{$year}-{$month}.pdf");
    }
 
    // ──────────────────────────────────────────────────────────
    // Excel — Laporan Pendapatan (akses: superadmin)
    // ──────────────────────────────────────────────────────────
    public function revenueExcel(Request $request)
    {
        $month = $request->get('month', now()->format('m'));
        $year  = $request->get('year',  now()->format('Y'));
 
        return Excel::download(
            new RevenueExport($month, $year),
            "laporan-pendapatan-{$year}-{$month}.xlsx"
        );
    }
 
    // ──────────────────────────────────────────────────────────
    // Excel — Laporan Stok Barang (akses: manager / superadmin)
    // ──────────────────────────────────────────────────────────
    public function stockExcel()
    {
        return Excel::download(
            new StockReportExport(),
            'laporan-stok-' . now()->format('Y-m-d') . '.xlsx'
        );
    }
}