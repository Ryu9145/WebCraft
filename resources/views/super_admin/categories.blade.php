@extends('layouts.super_admin.master')

@section('content')

<div class="d-sm-flex align-items-center justify-content-between mb-4 sticky-dashboard-header">
    <h1 class="h3 mb-0 text-gray-800">Master Kategori</h1>
    <button class="btn btn-sm btn-primary shadow-sm" id="btnTambah" data-toggle="modal" data-target="#modalKategori">
        <i class="fas fa-plus fa-sm text-white-50 mr-2"></i> Tambah Kategori
    </button>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Daftar Kategori Produk</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" width="100%" cellspacing="0">
                        <thead class="bg-light">
                            <tr>
                                <th width="10%">Urutan</th>
                                <th>Nama Kategori</th>
                                <th>Slug (URL)</th>
                                <th width="20%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($categories as $c)
                            <tr>
                                <td class="text-center font-weight-bold">{{ $c->urutan }}</td>
                                <td>
                                    <i class="fas fa-folder text-warning mr-2"></i>
                                    {{ $c->nama_kategori }}
                                </td>
                                <td class="small text-muted">/{{ $c->slug }}</td>
                                <td>
                                    <button class="btn btn-info btn-sm btn-edit" 
                                        data-id="{{ $c->id }}"
                                        data-nama="{{ $c->nama_kategori }}"
                                        data-urutan="{{ $c->urutan }}"
                                        data-toggle="modal" data-target="#modalKategori">
                                        <i class="fas fa-pen"></i> Edit
                                    </button>
                                    
                                    <form action="{{ route('super_admin.categories.destroy', $c->id) }}" method="POST" class="d-inline form-hapus">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-danger btn-sm btn-hapus">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center py-3">Belum ada kategori.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-info">Informasi</h6>
            </div>
            <div class="card-body">
                <p>Kategori digunakan untuk mengelompokkan produk agar mudah dicari oleh User.</p>
                <p class="mb-0"><strong>Tips:</strong> Gunakan angka urutan kecil (1, 2, 3) untuk kategori yang paling populer agar muncul di posisi paling atas/kiri menu.</p>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalKategori" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <form method="POST" action="{{ route('super_admin.categories.store') }}" id="formKategori" class="modal-content">
            @csrf
            <div id="method-spoofing"></div>

            <div class="modal-header">
                <h5 class="modal-title font-weight-bold" id="modalTitle">Tambah Kategori</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="id" id="cat-id">
                
                <div class="form-group">
                    <label>Nama Kategori</label>
                    <input type="text" name="nama_kategori" id="cat-nama" class="form-control" placeholder="Contoh: Web Design" required>
                </div>
                
                <div class="form-group">
                    <label>Urutan Tampil</label>
                    <input type="number" name="urutan" id="cat-urutan" class="form-control" placeholder="1" required>
                    <small class="text-muted">Semakin kecil angka, semakin prioritas.</small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
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
            $('#formKategori').attr('action', "{{ route('super_admin.categories.store') }}"); // Set Action Store
            $('#method-spoofing').html(''); // Hapus method PUT (jika ada sisa edit)
            
            $('#cat-id').val('');
            $('#cat-nama').val('');
            $('#cat-urutan').val('');
        });

        // 2. LOGIC MODAL EDIT (Isi Form)
        $('body').on('click', '.btn-edit', function() {
            $('#modalTitle').text('Edit Kategori');
            $('#formKategori').attr('action', "{{ route('super_admin.categories.update') }}"); // Set Action Update
            
            // Masukkan data ke input
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
                confirmButtonText: 'Ya, Hapus'
            }).then((result) => {
                if (result.isConfirmed) form.submit();
            });
        });

        // 4. NOTIFIKASI
        var flashData = $('#flash-data');
        if(flashData.data('success')) {
            Swal.fire({ title: 'Berhasil!', text: flashData.data('success'), icon: 'success', timer: 1500, showConfirmButton: false });
        }
    });
</script>

@endsection