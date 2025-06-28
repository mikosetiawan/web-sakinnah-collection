<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\User;
use App\Models\Barang;
use App\Models\Jasa;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\TransactionReportExport;
use Barryvdh\DomPDF\Facade\PDF;
use Illuminate\Support\Facades\Gate;

class ReportController extends Controller
{

    public function index()
    {
        $transactions = Transaction::with(['user', 'items.barang', 'items.jasa'])->paginate(10);
        $users = User::count();
        $barangs = Barang::count();
        $jasas = Jasa::count();
        $totalRevenue = Transaction::where('status', 'completed')->sum('total_price');
        
        // Data for chart (transactions per month)
        $chartData = Transaction::selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, COUNT(*) as count, SUM(total_price) as revenue')
            ->where('status', 'completed')
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->map(function ($item) {
                return [
                    'month' => $item->month,
                    'count' => $item->count,
                    'revenue' => $item->revenue,
                ];
            });

        return view('admin.reports.index', compact('transactions', 'users', 'barangs', 'jasas', 'totalRevenue', 'chartData'));
    }

    public function print()
    {
        $transactions = Transaction::with(['user', 'items.barang', 'items.jasa'])->get();
        $users = User::count();
        $barangs = Barang::count();
        $jasas = Jasa::count();
        $totalRevenue = Transaction::where('status', 'completed')->sum('total_price');

        return view('admin.reports.print', compact('transactions', 'users', 'barangs', 'jasas', 'totalRevenue'));
    }

    public function exportExcel()
    {
        return Excel::download(new TransactionReportExport, 'transaction_report_' . date('Y-m-d') . '.xlsx');
    }

    public function exportPDF()
    {
        $transactions = Transaction::with(['user', 'items.barang', 'items.jasa'])->get();
        $users = User::count();
        $barangs = Barang::count();
        $jasas = Jasa::count();
        $totalRevenue = Transaction::where('status', 'completed')->sum('total_price');

        $pdf = PDF::loadView('admin.reports.pdf', compact('transactions', 'users', 'barangs', 'jasas', 'totalRevenue'));
        return $pdf->download('transaction_report_' . date('Y-m-d') . '.pdf');
    }
}