@extends('layouts.dashboard.master')

@section('content')

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800" style="font-weight: 700;">Dashboard</h1>
        <span class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm" style="border-radius: 50px; padding: 0.5rem 1.5rem;"><i class="fas fa-calendar fa-sm text-white-50 mr-2"></i> {{ date('d M Y') }}</span>
    </div>

    <div class="row">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Total Belanja</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">Rp {{ number_format($mySpending ?? 0, 0, ',', '.') }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-wallet fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Transaksi Saya</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $myOrders ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-shopping-bag fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Produk Diupload</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $myProducts ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-box-open fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Status Akun</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ ucfirst($accountStatus ?? 'Inactive') }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-check fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">Riwayat Pesanan Terakhir</h6>
            <a href="#" class="btn btn-sm btn-link text-primary">Lihat Semua</a>
        </div>
        <div class="card-body p-0"> <div class="table-responsive">
                <table class="table table-hover mb-0" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th class="pl-4">ID Order</th>
                            <th>Produk</th>
                            <th>Tanggal</th>
                            <th>Total</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentOrders ?? [] as $order)
                        <tr>
                            <td class="pl-4 font-weight-bold">#{{ $order->ID }}</td>
                            <td>
                                <span class="d-block text-dark font-weight-bold">{{ $order->Nama_Produk }}</span>
                                @if(isset($order->Status_Pesanan) && $order->Status_Pesanan == 'Selesai')
                                    <a href="#" class="text-xs text-success text-decoration-none mt-1 d-inline-block">
                                        <i class="fas fa-download mr-1"></i> Invoice
                                    </a>
                                @endif
                            </td>
                            <td>{{ \Carbon\Carbon::parse($order->created_at)->format('d M Y') }}</td>
                            <td class="font-weight-bold">Rp {{ number_format($order->Total_Harga, 0, ',', '.') }}</td>
                            <td>
                                @if($order->Status_Pesanan == 'Selesai' || $order->Status_Pesanan == 'Paid')
                                    <span class="badge badge-success bg-success text-white border-0">{{ $order->Status_Pesanan }}</span>
                                @elseif($order->Status_Pesanan == 'Pending')
                                    <span class="badge badge-warning bg-warning text-white border-0">Pending</span>
                                @elseif($order->Status_Pesanan == 'Failed')
                                    <span class="badge badge-danger bg-danger text-white border-0">Failed</span>
                                @else
                                    <span class="badge badge-secondary bg-secondary text-white border-0">{{ $order->Status_Pesanan }}</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-5">
                                <img src="{{ asset('img/undraw_empty.svg') }}" style="width: 100px; opacity: 0.5; margin-bottom: 10px;">
                                <p class="mb-0">Belum ada transaksi terbaru.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection