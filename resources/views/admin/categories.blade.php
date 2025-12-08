@extends('layouts.admin.master')

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
                                <th>Slug</th>
                                <th width="20%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($categories as $c)
                            <tr>
                                <td class="text-center font-weight-bold">{{ $c->urutan }}</td>
                                <td>{{ $c->nama_kategori }}</td>
                                <td class="small text-muted">/{{ $c->slug }}</td>
                                <td>
                                    <button class="btn btn-info btn-sm btn-edit" 
                                        data-id="{{ $c->id }}"
                                        data-nama="{{ $c->nama_kategori }}"
                                        data-urutan="{{ $c->urutan }}"
                                        data-toggle="modal" data-target="#modalKategori">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    
                                    <form action="{{ route('admin.categories.destroy', $c->id) }}" method="POST" class="d-inline form-hapus">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-danger btn-sm btn-hapus"><i class="fas fa-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="4" class="text-center">Belum ada kategori.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card shadow mb-4">
            <div class="card-header py-3"><h6 class="m-0 font-weight-bold text-info">Informasi</h6></div>
            <div class="card-body">
                <p>Kategori digunakan untuk mengelompokkan produk di halaman utama.</p>
                <p><strong>Tips:</strong> Gunakan angka urutan kecil (1, 2, 3) agar muncul paling awal.</p>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalKategori" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <form method="POST" action="{{ route('admin.categories.store') }}" id="formKategori" class="modal-content">
            @csrf
            <div id="method-spoofing"></div> <div class="modal-header">
                <h5 class="modal-title font-weight-bold" id="modalTitle">Tambah Kategori</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="id" id="cat-id">
                <div class="form-group">
                    <label>Nama Kategori</label>
                    <input type="text" name="nama_kategori" id="cat-nama" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Urutan</label>
                    <input type="number" name="urutan" id="cat-urutan" class="form-control" required>
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
    });
</script>

@endsection