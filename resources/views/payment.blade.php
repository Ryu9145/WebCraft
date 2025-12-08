@extends('layouts.master')

@section('content')

<script type="text/javascript"
        src="https://app.sandbox.midtrans.com/snap/snap.js"
        data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}">
</script>

<div class="d-flex align-items-center justify-content-center py-5" style="min-height: 80vh;">
    <div class="card border-0 shadow-lg rounded-4 p-4 text-center" style="max-width: 500px; width: 100%;">
        
        <div class="mb-4">
            <div class="bg-primary bg-opacity-10 text-primary d-inline-flex align-items-center justify-content-center rounded-circle" style="width: 80px; height: 80px;">
                <i class="fa-solid fa-wallet fs-1"></i>
            </div>
        </div>

        <h4 class="fw-bold mb-2">Selesaikan Pembayaran</h4>
        <p class="text-muted small">ID Pesanan: <span class="fw-bold text-dark">{{ $order->kode_order }}</span></p>
        
        <div class="py-4 my-3 border-top border-bottom">
            <small class="text-muted d-block mb-1">Total Tagihan</small>
            <h1 class="text-primary fw-bold m-0">Rp {{ number_format($order->Total_Harga, 0, ',', '.') }}</h1>
        </div>

        <button id="pay-button" class="btn btn-primary btn-lg w-100 rounded-pill fw-bold mb-3 shadow-sm">
            <i class="fa-regular fa-credit-card me-2"></i> Pilih Metode Pembayaran
        </button>
        
        <a href="{{ route('home') }}" class="text-decoration-none text-muted small">
            <i class="fa-solid fa-arrow-left me-1"></i> Batal / Kembali ke Home
        </a>

    </div>
</div>

@endsection

@section('scripts')
<script type="text/javascript">
    var payButton = document.getElementById('pay-button');
    
    payButton.addEventListener('click', function () {
        // Trigger Snap Popup dengan Token dari Controller
        window.snap.pay('{{ $order->snap_token }}', {
            
            onSuccess: function(result){
                /* Jika sukses, redirect ke halaman sukses */
                window.location.href = "{{ route('payment.success') }}?order_id={{ $order->kode_order }}";
            },
            onPending: function(result){
                /* Menunggu pembayaran (misal: transfer ATM) */
                Swal.fire({
                    icon: 'info',
                    title: 'Menunggu Pembayaran',
                    text: 'Silakan selesaikan pembayaran Anda.',
                    confirmButtonColor: '#3b82f6'
                }).then(() => {
                    window.location.href = "{{ route('dashboard') }}";
                });
            },
            onError: function(result){
                /* Pembayaran gagal */
                Swal.fire({
                    icon: 'error',
                    title: 'Pembayaran Gagal',
                    text: 'Terjadi kesalahan saat memproses pembayaran.',
                });
            },
            onClose: function(){
                /* User tutup popup tanpa bayar */
                Swal.fire({
                    icon: 'warning',
                    title: 'Dibatalkan',
                    text: 'Anda menutup popup tanpa menyelesaikan pembayaran.',
                });
            }
        });
    });
</script>
@endsection