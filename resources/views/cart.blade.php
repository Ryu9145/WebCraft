@extends('layouts.master')

@section('content')

<div class="container py-4">
    <div class="d-flex align-items-center mb-4">
        <a href="{{ route('home') }}" class="btn btn-white border rounded-circle shadow-sm me-3" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
            <i class="fa-solid fa-arrow-left text-secondary"></i>
        </a>
        <h3 class="mb-0 fw-bold">Keranjang Saya</h3>
    </div>

    <div id="flash-data" 
         data-success="{{ session('success') }}" 
         data-error="{{ session('error') }}"
         style="display: none;">
    </div>

    <form action="{{ route('checkout.process') }}" method="POST" id="formCheckout"> 
        @csrf
        <div class="row">
            
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm rounded-4 mb-4">
                    <div class="card-body p-4">
                        
                        <div class="form-check mb-4 border-bottom pb-3">
                            <input class="form-check-input shadow-none" type="checkbox" id="checkAll" style="transform: scale(1.2); cursor: pointer;">
                            <label class="form-check-label fw-bold ms-2" for="checkAll" style="cursor: pointer; user-select: none;">
                                Pilih Semua ({{ $cartItems->count() }} Item)
                            </label>
                        </div>

                        @forelse($cartItems as $item)
                            @php 
                                $p = $item->produk; 
                            @endphp

                            {{-- === PROTEKSI ANTI CRASH === --}}
                            {{-- Jika produk dihapus admin (null), lewati item ini --}}
                            @if(empty($p))
                                @continue
                            @endif
                            {{-- =========================== --}}

                            @php
                                $img = asset('assets/uploads/' . $p->gambar);
                                if(empty($p->gambar) || $p->gambar == 'default.jpg') {
                                    $img = 'https://placehold.co/80x80?text=No+Image';
                                }
                            @endphp

                            <div class="d-flex align-items-center mb-4 pb-3 border-bottom cart-item">
                                <div class="me-3">
                                    <input class="form-check-input item-check shadow-none" type="checkbox" 
                                           name="selected_items[]" 
                                           value="{{ $item->id }}"
                                           data-price="{{ $p->harga }}"
                                           style="transform: scale(1.2); cursor: pointer;">
                                </div>

                                <a href="{{ route('product.detail', $p->id) }}">
                                    <img src="{{ $img }}" class="rounded-3 border" width="90" height="90" style="object-fit: cover;">
                                </a>
                                
                                <div class="ms-3 flex-grow-1">
                                    <h6 class="mb-1 fw-bold">
                                        <a href="{{ route('product.detail', $p->id) }}" class="text-decoration-none text-dark">
                                            {{ $p->nama_produk }}
                                        </a>
                                    </h6>
                                    <span class="badge bg-light text-secondary border">{{ $p->kategori }}</span>
                                </div>
                                
                                <div class="text-end">
                                    <div class="fw-bold text-primary fs-5 mb-1">Rp {{ number_format($p->harga, 0, ',', '.') }}</div>
                                    
                                    <a href="javascript:void(0)" 
                                       class="text-danger small text-decoration-none fw-semibold btn-delete" 
                                       data-href="{{ route('cart.destroy', $item->id) }}">
                                        <i class="fa-solid fa-trash-can me-1"></i> Hapus
                                    </a>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-5">
                                <i class="fa-solid fa-cart-arrow-down fa-4x text-muted mb-3 opacity-50"></i>
                                <p class="text-muted fs-5">Keranjang belanjamu masih kosong.</p>
                                <a href="{{ route('home') }}" class="btn btn-primary rounded-pill px-4 mt-2">Mulai Belanja</a>
                            </div>
                        @endforelse

                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card border-0 shadow-sm rounded-4 sticky-top" style="top: 100px; z-index: 99;">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-4">Ringkasan Belanja</h5>
                        
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Total Harga</span>
                            <span class="fw-bold text-dark" id="totalDisplay">Rp 0</span>
                        </div>
                        
                        <hr class="my-3">

                        <div class="d-flex justify-content-between mb-4">
                            <span class="fw-bold fs-5">Total Tagihan</span>
                            <span class="fw-bold fs-5 text-primary" id="grandTotalDisplay">Rp 0</span>
                        </div>
                        
                        <button type="submit" class="btn btn-primary w-100 rounded-pill py-3 fw-bold shadow-sm" id="btnCheckout" disabled>
                            Checkout (<span id="countDisplay">0</span>)
                        </button>
                        
                        <a href="{{ route('home') }}" class="btn btn-link w-100 mt-3 text-decoration-none text-muted fw-semibold">
                            <i class="fa-solid fa-bag-shopping me-1"></i> Lanjut Belanja
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

@endsection

@section('scripts')
<script>
    // Bungkus dalam function anonim (IIFE) agar variabel aman
    (function($) {
        $(document).ready(function() {
            
            console.log("Cart Script Loaded Successfully!"); 

            // ==========================================
            // 1. LOGIKA HITUNG TOTAL & CHECKBOX
            // ==========================================
            function updateCartTotal() {
                let total = 0;
                let count = 0;
                
                $('.item-check:checked').each(function() {
                    let price = parseFloat($(this).data('price')) || 0;
                    total += price;
                    count++;
                });

                let formattedTotal = 'Rp ' + total.toLocaleString('id-ID');

                $('#totalDisplay').text(formattedTotal);
                $('#grandTotalDisplay').text(formattedTotal);
                $('#countDisplay').text(count);

                if (count > 0) {
                    $('#btnCheckout').prop('disabled', false);
                } else {
                    $('#btnCheckout').prop('disabled', true);
                }
            }

            // A. Saat Checkbox Item Diklik
            $(document).on('change', '.item-check', function() {
                updateCartTotal();
                
                // Logic Check All sync
                var allChecked = $('.item-check:checked').length === $('.item-check').length;
                $('#checkAll').prop('checked', allChecked);
            });

            // B. Saat Checkbox "Pilih Semua" Diklik
            $(document).on('click', '#checkAll', function() {
                $('.item-check').prop('checked', this.checked);
                updateCartTotal();
            });


            // ==========================================
            // 2. LOGIKA HAPUS ITEM (SWEETALERT)
            // ==========================================
            $(document).on('click', '.btn-delete', function(e) {
                e.preventDefault();
                
                var deleteUrl = $(this).data('href'); 

                Swal.fire({
                    title: 'Hapus Item?',
                    text: "Produk ini akan dihapus dari keranjang.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, Hapus',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = deleteUrl;
                    }
                });
            });


            // ==========================================
            // 3. NOTIFIKASI FLASH MESSAGE (PHP)
            // ==========================================
            var flashData = $('#flash-data');
            var successMsg = flashData.data('success');
            var errorMsg = flashData.data('error');

            if (successMsg) {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: successMsg,
                    showConfirmButton: false,
                    timer: 1500
                });
            }

            if (errorMsg) {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: errorMsg
                });
            }

        }); 
    })(jQuery); 
</script>
@endsection