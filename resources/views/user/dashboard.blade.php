@extends('layouts.dashboard.master')

@section('content')

    <div class="row">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Total Belanja</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">Rp {{ number_format($mySpending, 0, ',', '.') }}</div>
                        </div>
                        <div class="col-auto"><i class="fas fa-wallet fa-2x text-gray-300"></i></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Transaksi Saya</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $myOrders }}</div>
                        </div>
                        <div class="col-auto"><i class="fas fa-shopping-bag fa-2x text-gray-300"></i></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Produk Diupload</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $myProducts }}</div>
                        </div>
                        <div class="col-auto"><i class="fas fa-box-open fa-2x text-gray-300"></i></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Status Akun</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ ucfirst($accountStatus) }}</div>
                        </div>
                        <div class="col-auto"><i class="fas fa-user-check fa-2x text-gray-300"></i></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Riwayat Pesanan Terakhir</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead class="bg-light">
                        <tr>
                            <th>ID Order</th>
                            <th>Produk</th>
                            <th>Tanggal</th>
                            <th>Total</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentOrders as $order)
                        <tr>
                            <td>#{{ $order->ID }}</td>
                            <td>
                                {{ $order->Nama_Produk }}
                                @if($order->Status_Pesanan == 'Selesai')
                                    <br><a href="#" class="badge badge-success text-white"><i class="fas fa-download"></i> Download</a>
                                @endif
                            </td>
                            <td>{{ \Carbon\Carbon::parse($order->created_at)->format('Y-m-d') }}</td>
                            <td>Rp {{ number_format($order->Total_Harga, 0, ',', '.') }}</td>
                            <td>
                                @if($order->Status_Pesanan == 'Selesai' || $order->Status_Pesanan == 'Paid')
                                    <span class="badge badge-success">{{ $order->Status_Pesanan }}</span>
                                @elseif($order->Status_Pesanan == 'Pending')
                                    <span class="badge badge-warning">Pending</span>
                                @elseif($order->Status_Pesanan == 'Failed')
                                    <span class="badge badge-danger">Failed</span>
                                @else
                                    <span class="badge badge-secondary">{{ $order->Status_Pesanan }}</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-3">Belum ada transaksi.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection