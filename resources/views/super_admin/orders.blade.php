@extends('layouts.super_admin.master')

@section('content')

<style>
    /* Font khusus ID */
    .text-id-order { font-family: 'Consolas', monospace; font-weight: 600; color: #4e73df; font-size: 0.9rem; }
    
    /* Tombol Aksi Bulat */
    .btn-action-circle {
        width: 32px; height: 32px; border-radius: 50%; display: inline-flex;
        align-items: center; justify-content: center; transition: 0.2s;
        border: 1px solid #e2e8f0; background: #fff;
    }
    .btn-action-circle:hover { background: #f1f5f9; transform: translateY(-2px); }
</style>

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <div>
        <h1 class="h3 mb-1 text-gray-800" style="font-weight: 700;">Daftar Transaksi Masuk</h1>
        <p class="mb-0 text-muted small">Kelola status pembayaran dan riwayat pesanan pelanggan.</p>
    </div>
    <button class="btn btn-sm btn-success shadow-sm btn-sm" onclick="window.print()" style="border-radius: 50px; padding: 0.5rem 1.5rem;">
        <i class="fas fa-print fa-sm text-white-50 mr-2"></i> Cetak Laporan
    </button>
</div>

<div class="card shadow mb-4 border-0">
    <div class="card-header py-3 bg-transparent border-bottom-0">
        <h6 class="m-0 font-weight-bold text-primary">Riwayat Pesanan</h6>
    </div>
    
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th class="pl-4">ID Order</th>
                        <th>Pemesan</th>
                        <th>Produk</th>
                        <th>Tanggal</th>
                        <th>Total</th>
                        <th class="text-center">Bukti</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $o)
                    <tr>
                        <td class="pl-4 align-middle">
                            <span class="text-id-order">#ORD-{{ $o->ID }}</span>
                        </td>

                        <td class="align-middle">
                            <div class="font-weight-bold text-gray-800">{{ $o->nama_pemesan }}</div>
                        </td>

                        <td class="align-middle text-muted small">
                            {{ Str::limit($o->Nama_Produk, 30) }}
                        </td>

                        <td class="align-middle small text-muted">
                            {{ \Carbon\Carbon::parse($o->Tanggal_Pesan)->format('d M Y, H:i') }}
                        </td>

                        <td class="align-middle font-weight-bold text-gray-700">
                            Rp {{ number_format($o->Total_Harga, 0, ',', '.') }}
                        </td>

                        <td class="align-middle text-center">
                            @if(!empty($o->bukti_bayar))
                                <a href="{{ asset('assets/uploads/' . $o->bukti_bayar) }}" target="_blank" 
                                   class="btn-action-circle shadow-sm text-info" title="Lihat Bukti">
                                    <i class="fas fa-image"></i>
                                </a>
                            @else
                                <span class="badge badge-light border text-muted px-2 py-1">None</span>
                            @endif
                        </td>

                        <td class="align-middle text-center">
                            @php $st = $o->Status_Pesanan; @endphp
                            
                            @if($st == 'Paid' || $st == 'Selesai')
                                <span class="badge badge-success shadow-sm px-3 py-2 rounded-pill">Paid/Selesai</span>
                            @elseif($st == 'Pending')
                                <span class="badge badge-warning shadow-sm px-3 py-2 rounded-pill text-white">Pending</span>
                            @elseif($st == 'Failed' || $st == 'Dibatalkan')
                                <span class="badge badge-danger shadow-sm px-3 py-2 rounded-pill">Failed</span>
                            @elseif($st == 'Refunded')
                                <span class="badge badge-info shadow-sm px-3 py-2 rounded-pill">Refunded</span>
                            @elseif($st == 'Dispute')
                                <span class="badge badge-dark shadow-sm px-3 py-2 rounded-pill">Dispute</span>
                            @else
                                <span class="badge badge-secondary shadow-sm px-3 py-2 rounded-pill">{{ $st }}</span>
                            @endif
                        </td>

                        <td class="align-middle text-center">
                            <button class="btn btn-info btn-sm btn-update shadow-sm mr-1" 
                                style="border-radius: 50px; padding: 0.25rem 0.8rem;"
                                data-id="{{ $o->ID }}"
                                data-status="{{ $o->Status_Pesanan }}"
                                data-toggle="modal" data-target="#modalUpdateStatus">
                                <i class="fas fa-edit"></i>
                            </button>

                            <form action="{{ route('super_admin.orders.destroy', $o->ID) }}" method="POST" class="d-inline form-hapus">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn btn-danger btn-sm btn-hapus shadow-sm" style="border-radius: 50px; padding: 0.25rem 0.8rem;">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="modalUpdateStatus" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <form method="POST" action="{{ route('super_admin.orders.update') }}" class="modal-content border-0 shadow-lg">
            @csrf
            
            <div class="modal-header border-bottom-0 pb-0">
                <h5 class="modal-title font-weight-bold text-gray-800">Update Status Pesanan</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            
            <div class="modal-body pt-3">
                <input type="hidden" name="id" id="order-id">
                
                <div class="alert alert-info border-0 shadow-sm small mb-3" style="background: rgba(54, 185, 204, 0.1); color: #2c9faf; border-radius: 10px;">
                    <i class="fas fa-info-circle mr-1"></i> 
                    <b>Catatan:</b> Cek bukti pembayaran sebelum mengubah status menjadi <b>Paid</b>.
                </div>
                
                <div class="form-group">
                    <label class="small font-weight-bold text-muted">Pilih Status Baru</label>
                    <select name="status_pesanan" id="order-status" class="form-control">
                        <option value="Pending">Pending (Menunggu Bayar)</option>
                        <option value="Paid">Paid (Lunas)</option>
                        <option value="Selesai">Selesai (Completed)</option>
                        <option disabled>──────────</option>
                        <option value="Failed">Failed (Gagal)</option>
                        <option value="Dispute">Dispute (Komplain)</option>
                        <option value="Refunded">Refunded (Dikembalikan)</option>
                    </select>
                </div>
            </div>  
            
            <div class="modal-footer border-top-0 pt-0">
                <button type="button" class="btn btn-secondary" data-dismiss="modal" style="border-radius: 50px;">Batal</button>
                <button type="submit" class="btn btn-success shadow-sm" style="border-radius: 50px;">Simpan Perubahan</button>
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
        // 1. LOGIC MODAL UPDATE
        $('body').on('click', '.btn-update', function() {
            var id = $(this).data('id');
            var status = $(this).data('status');
            
            $('#order-id').val(id);
            $('#order-status').val(status);
        });

        // 2. LOGIC HAPUS
        $('body').on('click', '.btn-hapus', function(e) {
            e.preventDefault();
            var form = $(this).closest('form');
            Swal.fire({
                title: 'Hapus Transaksi?',
                text: "Data akan dihapus permanen.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#858796',
                confirmButtonText: 'Ya, Hapus',
                backdrop: `rgba(0,0,123,0.1)`
            }).then((result) => {
                if (result.isConfirmed) form.submit();
            });
        });

        // 3. NOTIFIKASI
        var flashData = $('#flash-data');
        if(flashData.data('success')) {
            Swal.fire({ title: 'Berhasil!', text: flashData.data('success'), icon: 'success', timer: 1500, showConfirmButton: false, backdrop: `rgba(0,0,123,0.1)` });
        }
        if(flashData.data('error')) {
            Swal.fire({ title: 'Gagal!', text: flashData.data('error'), icon: 'error' });
        }
    });
</script>
@endsection