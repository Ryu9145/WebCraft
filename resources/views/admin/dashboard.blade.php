@extends('layouts.admin.master')

@section('content')

    <div class="row">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Pendapatan Masuk</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">Rp {{ number_format($totalIncome, 0, ',', '.') }}</div>
                        </div>
                        <div class="col-auto"><i class="fas fa-dollar-sign fa-2x text-gray-300"></i></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">User Terdaftar</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalUsers }}</div>
                        </div>
                        <div class="col-auto"><i class="fas fa-users fa-2x text-gray-300"></i></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Produk Aktif</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalProducts }}</div>
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
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Perlu Diproses</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $pendingOrders }}</div>
                        </div>
                        <div class="col-auto"><i class="fas fa-clipboard-list fa-2x text-gray-300"></i></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Aktivitas Pesanan Terbaru</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" width="100%" cellspacing="0">
                            <thead class="bg-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Produk</th>
                                    <th>Tanggal</th>
                                    <th>Status</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentOrders as $row)
                                <tr>
                                    <td>#{{ $row->ID }}</td>
                                    <td>{{ $row->Nama_Produk }}</td>
                                    <td>{{ \Carbon\Carbon::parse($row->Tanggal_Pesan)->format('d M Y') }}</td>
                                    <td>
                                        @if($row->Status_Pesanan == 'Selesai') 
                                            <span class="badge badge-success">Selesai</span>
                                        @elseif($row->Status_Pesanan == 'Pending') 
                                            <span class="badge badge-warning">Pending</span>
                                        @else 
                                            <span class="badge badge-secondary">{{ $row->Status_Pesanan }}</span>
                                        @endif
                                    </td>
                                    <td>Rp {{ number_format($row->Total_Harga, 0, ',', '.') }}</td>
                                </tr>
                                @empty
                                <tr><td colspan="5" class="text-center">Belum ada aktivitas.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="text-right mt-2">
                        <a href="#" class="btn btn-sm btn-primary">Lihat Detail Pesanan &rarr;</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection