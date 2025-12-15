@extends('layouts.admin.master')

@section('content')

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800" style="font-weight: 700;">Dashboard Overview</h1>
    </div>

    <div class="row">

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Pendapatan Masuk</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">Rp {{ number_format($totalIncome, 0, ',', '.') }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-wallet fa-2x text-gray-300"></i> </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">User Terdaftar</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($totalUsers) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
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
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Produk Aktif</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($totalProducts) }}</div>
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
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Perlu Diproses</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($pendingOrders) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-bell fa-2x text-gray-300"></i> </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Aktivitas Pesanan Terbaru</h6>
                    
                    <div class="dropdown no-arrow">
                        <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in" aria-labelledby="dropdownMenuLink">
                            <div class="dropdown-header">Opsi:</div>
                            <a class="dropdown-item" href="#">Lihat Semua</a>
                            <a class="dropdown-item" href="#">Export Excel</a>
                        </div>
                    </div>
                </div>
                
                <div class="card-body p-0"> <div class="table-responsive">
                        <table class="table table-hover mb-0" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th class="pl-4">ID Order</th>
                                    <th>Produk</th>
                                    <th>Tanggal</th>
                                    <th class="text-center">Status</th>
                                    <th>Total</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentOrders as $row)
                                <tr>
                                    <td class="pl-4 font-weight-bold text-primary">#{{ $row->ID }}</td>
                                    <td>
                                        <div class="font-weight-bold text-gray-800">{{ $row->Nama_Produk }}</div>
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($row->Tanggal_Pesan)->translatedFormat('d M Y') }}</td>
                                    <td class="text-center">
                                        @if($row->Status_Pesanan == 'Selesai' || $row->Status_Pesanan == 'Paid') 
                                            <span class="badge badge-success">Selesai</span>
                                        @elseif($row->Status_Pesanan == 'Pending') 
                                            <span class="badge badge-warning">Pending</span>
                                        @elseif($row->Status_Pesanan == 'Failed') 
                                            <span class="badge badge-danger">Gagal</span>
                                        @else 
                                            <span class="badge badge-secondary">{{ $row->Status_Pesanan }}</span>
                                        @endif
                                    </td>
                                    <td class="font-weight-bold">Rp {{ number_format($row->Total_Harga, 0, ',', '.') }}</td>
                                    <td class="text-center">
                                        <a href="#" class="btn btn-sm btn-info shadow-sm" title="Lihat Detail">
                                            <i class="fas fa-eye fa-xs"></i>
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5 text-muted">
                                        <img src="{{ asset('assets/img/undraw_empty.svg') }}" style="width: 100px; opacity: 0.5; margin-bottom: 10px;">
                                        <p>Belum ada aktivitas pesanan.</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer text-center bg-transparent border-0 py-3">
                    <a href="#" class="text-primary font-weight-bold small" style="text-decoration: none;">
                        Lihat Semua Pesanan <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

@endsection