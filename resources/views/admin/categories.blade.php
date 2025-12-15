@extends('layouts.admin.master')

@section('content')

<div class="d-sm-flex align-items-center justify-content-between mb-4 sticky-dashboard-header">
    <div>
        <h1 class="h3 mb-1 text-gray-800" style="font-weight: 700;">Master Kategori</h1>
        <p class="mb-0 text-muted small">Kelola kategori produk untuk pengelompokan yang lebih baik.</p>
    </div>
    <button class="btn btn-success shadow-sm btn-sm" id="btnTambah" data-toggle="modal" data-target="#modalKategori" style="border-radius: 50px; padding: 0.5rem 1.5rem;">
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
                                <th>Slug</th>
                                <th class="text-center" width="20%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($categories as $c)
                            <tr>
                                <td class="pl-4 align-middle">
                                    <span class="badge badge-light border shadow-sm px-2 py-1" style="font-size: 0.9rem;">
                                        {{ $c->urutan }}
                                    </span>
                                </td>
                                <td class="align-middle font-weight-bold text-gray-700">
                                    {{ $c->nama_kategori }}
                                </td>
                                <td class="align-middle small text-muted">
                                    <i class="fas fa-link mr-1"></i> /{{ $c->slug }}
                                </td>
                                <td class="align-middle text-center">
                                    <div style="position: relative; z-index: 50;">
                                        
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
                    Kategori digunakan untuk mengelompokkan produk di halaman utama.
                </p>
                <div class="alert alert-light border small text-muted mb-0">
                    <strong>Tips:</strong> Gunakan angka urutan kecil (1, 2, 3) agar muncul paling awal.
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalKategori" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <form method="POST" action="{{ route('admin.categories.store') }}" id="formKategori" class="modal-content border-0 shadow-lg">
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
                    <input type="text" name="nama_kategori" id="cat-nama" class="form-control" required>
                </div>
                
                <div class="form-group">
                    <label class="small font-weight-bold text-muted">Urutan</label>
                    <input type="number" name="urutan" id="cat-urutan" class="form-control" required>
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
    $(document).ready(function() {
        // Modal Tambah
        $('#btnTambah').on('click', function() {
            $('#modalTitle').text('Tambah Kategori');
            $('#formKategori').attr('action', "{{ route('admin.categories.store') }}");
            $('#method-spoofing').html(''); // Kosongkan method spoofing
            $('#cat-id').val('');
            $('#cat-nama').val('');
            $('#cat-urutan').val('');
        });

        // Modal Edit
        $('body').on('click', '.btn-edit', function() {
            $('#modalTitle').text('Edit Kategori');
            $('#formKategori').attr('action', "{{ route('admin.categories.update') }}");
            
            // Masukkan data
            $('#cat-id').val($(this).data('id'));
            $('#cat-nama').val($(this).data('nama'));
            $('#cat-urutan').val($(this).data('urutan'));
        });

        // Hapus
        $('body').on('click', '.btn-hapus', function(e) {
            e.preventDefault();
            var form = $(this).closest('form');
            Swal.fire({
                title: 'Hapus Kategori?',
                text: "Data akan dihapus permanen.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                confirmButtonText: 'Ya, Hapus'
            }).then((result) => {
                if (result.isConfirmed) form.submit();
            });
        });

        // Flash Message
        var flashData = $('#flash-data');
        if(flashData.data('success')) {
            Swal.fire({ title: 'Berhasil!', text: flashData.data('success'), icon: 'success', timer: 1500, showConfirmButton: false });
        }
        if(flashData.data('error')) {
            Swal.fire({ title: 'Gagal!', text: flashData.data('error'), icon: 'error' });
        }
    });
</script>

@endsection