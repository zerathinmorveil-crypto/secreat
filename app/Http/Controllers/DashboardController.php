<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Member;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Data fallback untuk view
        $stats = [
            'total_transaksi' => Transaction::count(),
            'transaksi_proses' => Transaction::where('status', 'proses')->count(),
            'total_customer' => Customer::where('status', 'aktif')->count(),
        ];

        return view('dashboard', compact('stats'));
    }

    public function getDashboardData()
    {
        $now = Carbon::now();
        $startOfMonth = $now->copy()->startOfMonth();
        $startOfWeek = $now->copy()->startOfWeek();

        return response()->json([
            'stats' => [
                'total_transaksi' => Transaction::count(),
                'transaksi_selesai' => Transaction::where('status', 'selesai')->count(),
                'transaksi_proses' => Transaction::where('status', 'proses')->count(),
                'total_customer' => Customer::where('status', 'aktif')->count(),
                'total_member' => Member::where('status', 'aktif')->count(),
                'pendapatan_bulan' => number_format(
                    Transaction::whereBetween('tanggal_masuk', [$startOfMonth, $now])
                        ->where('status_bayar', 'lunas')
                        ->sum('total'), 0, ',', '.'
                ),
            ],
            'transaksi_aktif' => Transaction::with(['customer', 'service'])
                ->whereIn('status', ['proses', 'siap_ambil'])
                ->latest('tanggal_masuk')
                ->limit(5)
                ->get(),
            'revenue_chart' => Transaction::selectRaw('DATE(tanggal_masuk) as date, SUM(total) as revenue')
                ->whereBetween('tanggal_masuk', [$startOfWeek, $now])
                ->where('status_bayar', 'lunas')
                ->groupBy('date')
                ->orderBy('date')
                ->pluck('revenue', 'date')
                ->toArray(),
        ]);
    }
}