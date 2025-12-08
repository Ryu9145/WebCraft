@extends('layouts.admin.master')

@section('content')

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Daftar Transaksi Masuk</h1>
    <button class="btn btn-sm btn-success shadow-sm" onclick="window.print()">
        <i class="fas fa-print fa-sm text-white-50 mr-2"></i> Cetak Laporan
    </button>
</div>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Riwayat Pesanan</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped" id="dataTable" width="100%" cellspacing="0">
                <thead class="bg-light">
                    <tr>
                        <th>ID</th>
                        <th>Pemesan</th>
                        <th>Produk</th>
                        <th>Tanggal</th>
                        <th>Total</th>
                        <th>Bukti</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $o)
                    <tr>
                        <td><span class="font-weight-bold text-primary">#ORD-{{ $o->ID }}</span></td>
                        <td><div class="font-weight-bold">{{ $o->nama_pemesan }}</div></td>
                        <td>{{ $o->Nama_Produk }}</td>
                        <td class="small">{{ \Carbon\Carbon::parse($o->Tanggal_Pesan)->format('d M Y') }}</td>
                        <td class="font-weight-bold">Rp {{ number_format($o->Total_Harga, 0, ',', '.') }}</td>
                        <td class="text-center">
                            @if(!empty($o->bukti_bayar))
                                <a href="{{ asset('assets/uploads/' . $o->bukti_bayar) }}" target="_blank" class="btn btn-sm btn-info btn-circle">
                                    <i class="fas fa-image"></i>
                                </a>
                            @else
                                <span class="text-muted small">-</span>
                            @endif
                        </td>
                        <td>
                            @php $st = $o->Status_Pesanan; @endphp
                            @if($st == 'Paid' || $st == 'Selesai') <span class="badge badge-success">{{ $st }}</span>
                            @elseif($st == 'Pending') <span class="badge badge-warning">Pending</span>
                            @elseif($st == 'Failed') <span class="badge badge-danger">Failed</span>
                            @elseif($st == 'Refunded') <span class="badge badge-dark">Refunded</span>
                            @else <span class="badge badge-secondary">{{ $st }}</span>
                            @endif
                        </td>
                        <td>
                            <button class="btn btn-primary btn-sm btn-update" 
                                data-id="{{ $o->ID }}"
                                data-status="{{ $o->Status_Pesanan }}"
                                data-toggle="modal" data-target="#modalUpdateStatus">
                                <i class="fas fa-edit"></i> Proses
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="modalUpdateStatus" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <form method="POST" action="{{ route('admin.orders.update') }}" class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title font-weight-bold">Update Status Pesanan</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="id" id="order-id">
                <div class="alert alert-info small">
                    <b>Catatan:</b> Silakan cek bukti pembayaran. Jika status <b>Refunded</b> diperlukan, hubungi Super Admin.
                </div>
                <div class="form-group">
                    <label>Pilih Status Baru</label>
                    <select name="status_pesanan" id="order-status" class="form-control">
                        <option value="Pending">Pending (Menunggu Bayar)</option>
                        <option value="Paid">Paid (Lunas)</option>
                        <option value="Selesai">Selesai (Completed)</option>
                        <option disabled>──────────</option>
                        <option value="Failed">Failed (Gagal)</option>
                        <option value="Dispute">Dispute (Komplain User)</option>
                        </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-success">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

<div id="flash-data" 
     data-success="{{ session('success') }}" 
     data-error="{{ session('error') }}" 
     style="display: none;">
</div>

<script src="{{ asset('assets/vendor/jquery/jquery.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    $(document).ready(function() {
        // Modal Logic
        $('body').on('click', '.btn-update', function() {
            $('#order-id').val($(this).data('id'));
            $('#order-status').val($(this).data('status'));
        });

        // Notifikasi
        var flashData = $('#flash-data');
        if(flashData.data('success')) {
            Swal.fire({ title: 'Berhasil!', text: flashData.data('success'), icon: 'success', timer: 1500, showConfirmButton: false });
        }
        if(flashData.data('error')) {
            Swal.fire({ title: 'Akses Ditolak!', text: flashData.data('error'), icon: 'error' });
        }
    });
</script>

@endsection