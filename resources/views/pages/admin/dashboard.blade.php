@extends('layouts.admin')

@section('title', 'Dashboard Statistik')

@section('content')
{{-- Inisialisasi AlpineJS untuk handle dropdown custom --}}
<div class="container mx-auto pb-10" 
     x-data="{ 
        openMonth: false, 
        openYear: false, 
        selectedMonth: '{{ \Carbon\Carbon::create()->month((int)date('n'))->translatedFormat('F') }}', 
        selectedYear: '{{ date('Y') }}',
        monthValue: {{ (int)date('n') }}
     }">

    {{-- Header & Filter Section --}}
    <div class="flex flex-col lg:flex-row lg:items-end justify-between mb-10 gap-6">
        <div>
            <div class="flex items-center gap-3 mb-2">
                <span class="px-3 py-1 bg-pink-100 text-[#EC4899] text-[10px] font-black rounded-full uppercase tracking-widest shadow-sm shadow-pink-100">Owner Panel</span>
                <h2 class="text-3xl font-black text-gray-800 tracking-tight">Ringkasan Bisnis</h2>
            </div>
            <p class="text-gray-500 text-sm">Analisis performa toko <span class="text-[#EC4899] font-bold">Funtasya Play</span> secara detail.</p>
        </div>
        
        {{-- Custom Filter --}}
        <div class="flex items-center gap-4">
            {{-- Dropdown Bulan --}}
            <div class="relative w-48">
                <button @click="openMonth = !openMonth" class="w-full flex items-center justify-between bg-white px-5 py-3 rounded-2xl shadow-sm border border-gray-100 hover:border-pink-200 transition-all">
                    <div class="flex items-center gap-3">
                        <svg class="w-4 h-4 text-pink-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        <span class="text-sm font-bold text-gray-700" x-text="selectedMonth"></span>
                    </div>
                    <svg class="w-4 h-4 text-gray-400 transition-transform" :class="openMonth ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </button>
                <div x-show="openMonth" @click.away="openMonth = false" x-transition class="absolute z-50 w-full mt-2 bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden max-h-60 overflow-y-auto">
                    @foreach(range(1, 12) as $m)
                        @php $name = \Carbon\Carbon::create()->month((int)$m)->translatedFormat('F'); @endphp
                        <button @click="selectedMonth = '{{ $name }}'; monthValue = {{ $m }}; openMonth = false; updateChart(monthValue, selectedYear)" class="w-full text-left px-5 py-3 text-sm font-semibold text-gray-600 hover:bg-pink-50 hover:text-[#EC4899] transition-colors">
                            {{ $name }}
                        </button>
                    @endforeach
                </div>
            </div>

            {{-- Dropdown Tahun --}}
            <div class="relative w-32">
                <button @click="openYear = !openYear" class="w-full flex items-center justify-between bg-white px-5 py-3 rounded-2xl shadow-sm border border-gray-100 hover:border-pink-200 transition-all">
                    <span class="text-sm font-bold text-gray-700" x-text="selectedYear"></span>
                    <svg class="w-4 h-4 text-gray-400 transition-transform" :class="openYear ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </button>
                <div x-show="openYear" @click.away="openYear = false" x-transition class="absolute z-50 w-full mt-2 bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
                    @php $currentYear = date('Y'); @endphp
                    @for ($i = $currentYear; $i >= $currentYear - 3; $i--)
                        <button @click="selectedYear = '{{ $i }}'; openYear = false; updateChart(monthValue, '{{ $i }}')" class="w-full text-left px-5 py-3 text-sm font-semibold text-gray-600 hover:bg-pink-50 hover:text-[#EC4899] transition-colors">
                            {{ $i }}
                        </button>
                    @endfor
                </div>
            </div>
        </div>
    </div>

    {{-- Statistik Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
        <div class="group bg-white p-7 rounded-[2.5rem] shadow-sm border border-gray-50 transition-all duration-300 hover:-translate-y-2 hover:shadow-emerald-200/30">
            <div class="flex items-center justify-between mb-6">
                <div class="w-14 h-14 bg-emerald-50 rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform">
                    <svg class="w-7 h-7 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <span class="text-[9px] font-black px-2.5 py-1.5 bg-emerald-100 text-emerald-600 rounded-xl uppercase">Lunas</span>
            </div>
            <p class="text-[11px] font-bold text-gray-400 uppercase tracking-widest">Total Pendapatan</p>
            <h3 class="text-2xl font-black text-gray-800 mt-2 tracking-tight">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h3>
        </div>

        <div class="group bg-white p-7 rounded-[2.5rem] shadow-sm border border-gray-50 transition-all duration-300 hover:-translate-y-2 hover:shadow-blue-200/30">
            <div class="flex items-center justify-between mb-6">
                <div class="w-14 h-14 bg-blue-50 rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform">
                    <svg class="w-7 h-7 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                </div>
            </div>
            <p class="text-[11px] font-bold text-gray-400 uppercase tracking-widest">Total Pesanan</p>
            <h3 class="text-2xl font-black text-gray-800 mt-2 tracking-tight">{{ $totalOrders }} <span class="text-sm font-medium text-gray-400">Transaksi</span></h3>
        </div>

        <div class="group bg-white p-7 rounded-[2.5rem] shadow-sm border border-gray-50 transition-all duration-300 hover:-translate-y-2 hover:shadow-purple-200/30">
            <div class="flex items-center justify-between mb-6">
                <div class="w-14 h-14 bg-purple-50 rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform">
                    <svg class="w-7 h-7 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                </div>
            </div>
            <p class="text-[11px] font-bold text-gray-400 uppercase tracking-widest">Total Pelanggan</p>
            <h3 class="text-2xl font-black text-gray-800 mt-2 tracking-tight">{{ $totalUsers }} <span class="text-sm font-medium text-gray-400">User</span></h3>
        </div>

        <div class="group bg-white p-7 rounded-[2.5rem] shadow-sm border border-gray-50 transition-all duration-300 hover:-translate-y-2 hover:shadow-pink-200/30">
            <div class="flex items-center justify-between mb-6">
                <div class="w-14 h-14 bg-pink-50 rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform">
                    <img src="{{ asset('images/Big Parcel.svg') }}" class="w-8 h-8 object-contain" alt="Produk">
                </div>
                <span class="text-[9px] font-black px-2.5 py-1.5 bg-pink-100 text-[#EC4899] rounded-xl uppercase">Aktif</span>
            </div>
            <p class="text-[11px] font-bold text-gray-400 uppercase tracking-widest">Katalog Produk</p>
            <h3 class="text-2xl font-black text-gray-800 mt-2 tracking-tight">{{ $totalProducts }} <span class="text-sm font-medium text-gray-400">Item</span></h3>
        </div>
    </div>

    {{-- Section Grafik --}}
    <div class="bg-white p-10 rounded-[3.5rem] shadow-[0_20px_50px_-20px_rgba(0,0,0,0.05)] border border-gray-50 relative">
        <h4 class="text-xl font-black text-gray-800">Tren Penjualan</h4>
        <div class="flex items-center gap-2 mt-1 mb-10">
            <span class="w-3 h-1 bg-[#EC4899] rounded-full"></span>
            <p class="text-xs text-gray-400 font-bold italic uppercase tracking-wider">Pendapatan Harian</p>
        </div>
        <div class="h-[400px]">
            <canvas id="salesChart"></canvas>
        </div>
    </div>
</div>

@push('addon-script')
{{-- AlpineJS & Chart.js --}}
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    const ctx = document.getElementById('salesChart').getContext('2d');
    let gradient = ctx.createLinearGradient(0, 0, 0, 400);
    gradient.addColorStop(0, 'rgba(236, 72, 153, 0.3)');
    gradient.addColorStop(1, 'rgba(236, 72, 153, 0.0)');

    let salesChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: {!! json_encode($months) !!},
            datasets: [{
                label: 'Pendapatan',
                data: {!! json_encode($totals) !!},
                borderColor: '#EC4899',
                backgroundColor: gradient,
                borderWidth: 5,
                fill: true,
                pointBackgroundColor: '#fff',
                pointBorderColor: '#EC4899',
                pointBorderWidth: 3,
                pointRadius: 6,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#1f2937',
                    padding: 15,
                    cornerRadius: 15,
                    callbacks: {
                        label: (ctx) => ' Rp ' + ctx.raw.toLocaleString('id-ID')
                    }
                }
            },
            scales: {
                y: {
                    grid: { color: '#f3f4f6', drawBorder: false },
                    ticks: {
                        color: '#9ca3af',
                        font: { size: 11, weight: 'bold' },
                        callback: (v) => 'Rp ' + v.toLocaleString('id-ID')
                    }
                },
                x: { grid: { display: false }, ticks: { color: '#9ca3af', font: { size: 11, weight: 'bold' } } }
            }
        }
    });

    function updateChart(m, y) {
        const canvas = document.getElementById('salesChart');
        canvas.style.opacity = '0.3';

        fetch(`/admin/dashboard/data?month=${m}&year=${y}`)
            .then(res => res.json())
            .then(data => {
                salesChart.data.labels = data.labels;
                salesChart.data.datasets[0].data = data.totals;
                salesChart.update();
                canvas.style.opacity = '1';
            });
    }
</script>
@endpush
@endsection