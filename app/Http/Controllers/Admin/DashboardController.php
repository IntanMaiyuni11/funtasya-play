<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order; // Sesuai permintaan Anda
use App\Models\User;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Menampilkan halaman utama Dashboard Super Admin.
     */
    public function index()
    {
        // Statistik Ringkas untuk Card
        $totalRevenue = Order::where('status', 'complete')->sum('total_price');
        $totalOrders = Order::count();
        $totalUsers = User::where('role', 'customer')->count();
        $totalProducts = Product::count();

        // Ambil data default untuk bulan dan tahun saat ini
        $month = (int)date('n');
        $year = (int)date('Y');

        $graphData = $this->getGraphData($month, $year);

        return view('pages.admin.dashboard', [
            'totalRevenue' => $totalRevenue,
            'totalOrders' => $totalOrders,
            'totalUsers' => $totalUsers,
            'totalProducts' => $totalProducts,
            'months' => $graphData['labels'], // Ini akan jadi tanggal 1, 2, 3...
            'totals' => $graphData['totals']
        ]);
    }

    /**
     * API untuk mengambil data grafik secara dinamis (AJAX).
     */
    public function getData(Request $request)
    {
        $month = $request->input('month', date('n'));
        $year = $request->input('year', date('Y'));

        $data = $this->getGraphData((int)$month, (int)$year);

        return response()->json($data);
    }

    /**
     * Helper untuk memproses data harian agar grafik tidak putus.
     */
    private function getGraphData($month, $year)
    {
        // Hitung jumlah hari dalam bulan terpilih
        $daysInMonth = Carbon::createFromDate($year, $month)->daysInMonth;
        
        $labels = [];
        $totals = [];

        // Ambil data penjualan per hari
        $sales = Order::where('status', 'complete')
            ->whereMonth('created_at', $month)
            ->whereYear('created_at', $year)
            ->select(
                DB::raw('DAY(created_at) as day'),
                DB::raw('SUM(total_price) as total')
            )
            ->groupBy('day')
            ->pluck('total', 'day');

        // Isi array untuk setiap tanggal di bulan tersebut
        for ($i = 1; $i <= $daysInMonth; $i++) {
            $labels[] = $i; // Menampilkan angka tanggal (1, 2, dst)
            $totals[] = $sales->get($i, 0); // Jika tgl tersebut tidak ada penjualan, isi 0
        }

        return [
            'labels' => $labels,
            'totals' => $totals
        ];
    }
}