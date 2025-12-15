@extends('layouts.super_admin.master')

@section('content')

<style>
    /* 1. Tombol Edit (Biru Konsisten) */
    .btn-circle-edit {
        width: 32px; height: 32px;
        border-radius: 50%;
        display: inline-flex; align-items: center; justify-content: center;
        background-color: #36b9cc; /* Biru Info */
        color: #fff;
        border: none;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        transition: transform 0.2s;
    }
    /* Hapus efek perubahan warna saat hover, hanya efek tekan sedikit */
    .btn-circle-edit:hover {
        background-color: #36b9cc; /* Tetap Biru */
        color: #fff;
        transform: scale(1.05); /* Sedikit membesar agar responsif tapi warna tetap */
        text-decoration: none;
    }

    /* 2. Tombol Hapus (Merah Konsisten) */
    .btn-circle-delete {
        width: 32px; height: 32px;
        border-radius: 50%;
        display: inline-flex; align-items: center; justify-content: center;
        background-color: #e74a3b; /* Merah Danger */
        color: #fff;
        border: none;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        transition: transform 0.2s;
    }
    /* Hapus efek perubahan warna saat hover */
    .btn-circle-delete:hover {
        background-color: #e74a3b; /* Tetap Merah */
        color: #fff;
        transform: scale(1.05);
    }
</style>

<div class="d-sm-flex align-items-center justify-content-between mb-4 sticky-dashboard-header">
    <div>
        <h1 class="h3 mb-1 text-gray-800" style="font-weight: 700;">Master Kategori</h1>
        <p class="mb-0 text-muted small">Kelola kategori produk agar lebih terorganisir.</p>
    </div>
    <button class="btn btn-primary shadow-sm btn-sm" id="btnTambah" data-toggle="modal" data-target="#modalKategori" style="border-radius: 50px; padding: 0.5rem 1.5rem;">
        <i class="fas fa-plus fa-sm text-white mr-2"></i> Tambah Kategori
    </button>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card mb-4 border-0 shadow-sm">
            <div class="card-header py-3 bg-transparent border-bottom-0">
                <h6 class="m-0 font-weight-bold text-primary">Daftar Kategori Produk</h6>
            </div>
            
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th class="pl-4" width="10%">Urutan</th>
                                <th>Nama Kategori</th>
                                <th>Slug (URL)</th>
                                <th class="text-center" width="20%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($categories as $c)
                            <tr>
                                <td class="pl-4 align-middle text-center">
                                    <span class="badge badge-light border shadow-sm px-2 py-1" style="font-size: 0.9rem;">
                                        {{ $c->urutan }}
                                    </span>
                                </td>

                                <td class="align-middle">
                                    <span class="font-weight-bold text-gray-800">
                                        <i class="fas fa-folder text-warning mr-2"></i> {{ $c->nama_kategori }}
                                    </span>
                                </td>

                                <td class="align-middle small text-muted">
                                    /{{ $c->slug }}
                                </td>

                                <td class="align-middle text-center">
                                    <div style="position: relative; z-index: 50; display: flex; justify-content: center; gap: 8px;">
                                        
                                        <button class="btn btn-info btn-sm btn-edit shadow-sm mr-1" 
                                            style="border-radius: 50px; padding: 0.25rem 0.8rem;"
                                            data-id="{{ $c->id }}"
                                            data-nama="{{ $c->nama_kategori }}"
                                            data-urutan="{{ $c->urutan }}"
                                            data-toggle="modal" data-target="#modalKategori">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        
                                        <form action="{{ route('admin.categories.destroy', $c->id) }}" method="POST" class="d-inline form-hapus">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn btn-danger btn-sm btn-hapus shadow-sm" 
                                                style="border-radius: 50px; padding: 0.25rem 0.8rem;">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>

                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center py-5">
                                    <img src="{{ asset('assets/img/undraw_empty.svg') }}" style="width: 100px; opacity: 0.5; margin-bottom: 10px;">
                                    <p class="text-muted mb-0">Belum ada kategori ditambahkan.</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card border-0 shadow-sm" style="background: rgba(54, 185, 204, 0.05); border-left: 4px solid #36b9cc !important;">
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <div class="icon-circle bg-info text-white mr-3" style="width: 35px; height: 35px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-lightbulb"></i>
                    </div>
                    <h6 class="m-0 font-weight-bold text-info">Informasi</h6>
                </div>
                <p class="small text-muted mb-2">
                    Kategori digunakan untuk mengelompokkan produk agar mudah dicari oleh User.
                </p>
                <div class="alert alert-light border small text-muted mb-0" style="border-radius: 10px;">
                    <strong>Tips:</strong> Gunakan angka urutan kecil (1, 2, 3) untuk kategori yang paling populer agar muncul di posisi paling atas.
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalKategori" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <form method="POST" action="{{ route('super_admin.categories.store') }}" id="formKategori" class="modal-content border-0 shadow-lg">
            @csrf
            <div id="method-spoofing"></div>

            <div class="modal-header border-bottom-0 pb-0">
                <h5 class="modal-title font-weight-bold text-gray-800" id="modalTitle">Tambah Kategori</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            
            <div class="modal-body pt-3">
                <input type="hidden" name="id" id="cat-id">
                
                <div class="form-group">
                    <label class="small font-weight-bold text-muted">Nama Kategori</label>
                    <input type="text" name="nama_kategori" id="cat-nama" class="form-control" placeholder="Contoh: Web Design" required>
                </div>
                
                <div class="form-group">
                    <label class="small font-weight-bold text-muted">Urutan Tampil</label>
                    <input type="number" name="urutan" id="cat-urutan" class="form-control" placeholder="1" required>
                    <small class="text-muted">Semakin kecil angka, semakin prioritas.</small>
                </div>
            </div>
            
            <div class="modal-footer border-top-0 pt-0">
                <button type="button" class="btn btn-secondary" data-dismiss="modal" style="border-radius: 50px;">Batal</button>
                <button type="submit" class="btn btn-primary shadow-sm" style="border-radius: 50px;">Simpan</button>
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
    document.addEventListener("DOMContentLoaded", function() {
        
        // 1. LOGIC MODAL TAMBAH (Reset Form)
        $('#btnTambah').on('click', function() {
            $('#modalTitle').text('Tambah Kategori');
            $('#formKategori').attr('action', "{{ route('super_admin.categories.store') }}"); 
            $('#method-spoofing').html(''); 
            
            $('#cat-id').val('');
            $('#cat-nama').val('');
            $('#cat-urutan').val('');
        });

        // 2. LOGIC MODAL EDIT (Isi Form)
        $('body').on('click', '.btn-circle-edit', function() { // Selector disesuaikan dengan class baru
            $('#modalTitle').text('Edit Kategori');
            $('#formKategori').attr('action', "{{ route('super_admin.categories.update') }}"); 
            
            $('#cat-id').val($(this).data('id'));
            $('#cat-nama').val($(this).data('nama'));
            $('#cat-urutan').val($(this).data('urutan'));
        });

        // 3. LOGIC HAPUS
        $('body').on('click', '.btn-hapus', function(e) {
            e.preventDefault();
            var form = $(this).closest('form');
            Swal.fire({
                title: 'Hapus Kategori?',
                text: "Kategori akan hilang dari sistem.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                confirmButtonText: 'Ya, Hapus',
                backdrop: `rgba(0,0,123,0.1)`
            }).then((result) => {
                if (result.isConfirmed) form.submit();
            });
        });

        // 4. NOTIFIKASI
        var flashData = $('#flash-data');
        if(flashData.data('success')) {
            Swal.fire({ title: 'Berhasil!', text: flashData.data('success'), icon: 'success', timer: 1500, showConfirmButton: false, backdrop: `rgba(0,0,123,0.1)` });
        }
    });
</script>
@endsection