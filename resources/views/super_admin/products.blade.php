@extends('layouts.super_admin.master')

@section('content')

<style>
    .img-thumbnail-product { width: 60px; height: 60px; object-fit: cover; border-radius: 8px; border: 1px solid #ddd; }
</style>

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Daftar Produk Template</h1>
    <button class="btn btn-sm btn-primary shadow-sm" data-toggle="modal" data-target="#modalTambah">
        <i class="fas fa-plus fa-sm text-white-50 mr-2"></i> Tambah Produk
    </button>
</div>

<div class="card shadow mb-4">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                <thead class="bg-light">
                    <tr>
                        <th>Cover</th>
                        <th>Info Produk</th>
                        <th>Link Repo</th>
                        <th>Kategori</th>
                        <th>Harga</th>
                        <th>Status</th>
                        <th>Featured</th>
                        <th width="12%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($products as $p)
                    <tr>
                        <td class="text-center">
                            <img src="{{ asset('assets/uploads/' . ($p->gambar ?? 'default.jpg')) }}" class="img-thumbnail-product">
                        </td>
                        <td>
                            <div class="font-weight-bold text-dark">{{ $p->nama_produk }}</div>
                            <div class="small text-muted text-truncate" style="max-width: 150px;">{{ $p->deskripsi }}</div>
                        </td>
                        <td class="text-center">
                            <a href="{{ $p->link_github }}" target="_blank" class="btn btn-sm btn-dark" title="Lihat Repo">
                                <i class="fa-brands fa-github"></i> View
                            </a>
                        </td>
                        <td><span class="badge badge-light border">{{ $p->kategori }}</span></td>
                        <td>Rp {{ number_format($p->harga, 0, ',', '.') }}</td>
                        <td>
                            @if($p->status == 'active')
                                <span class="badge badge-success">Active</span>
                            @elseif($p->status == 'rejected')
                                <span class="badge badge-danger">Rejected</span>
                            @else
                                <span class="badge badge-warning">Pending</span>
                                <div class="mt-1">
                                    <a href="{{ route('super_admin.products.status', ['id' => $p->id, 'action' => 'approve']) }}" class="text-success small"><i class="fa-solid fa-check"></i> Accept</a>
                                </div>
                            @endif
                        </td>
                        <td class="text-center">
                            <a href="{{ route('super_admin.products.status', ['id' => $p->id, 'action' => 'feature']) }}" class="{{ $p->is_featured ? 'text-warning' : 'text-gray-300' }}">
                                <i class="{{ $p->is_featured ? 'fa-solid' : 'fa-regular' }} fa-star fa-lg"></i>
                            </a>
                        </td>
                        <td>
                            <button class="btn btn-info btn-sm btn-edit" 
                                data-id="{{ $p->id }}"
                                data-nama="{{ $p->nama_produk }}"
                                data-kategori="{{ $p->kategori }}"
                                data-harga="{{ $p->harga }}"
                                data-deskripsi="{{ $p->deskripsi }}"
                                data-link="{{ $p->link_github }}"
                                data-status="{{ $p->status }}"
                                data-toggle="modal" data-target="#modalEdit">
                                <i class="fas fa-edit"></i>
                            </button>
                            
                            <form action="{{ route('super_admin.products.destroy', $p->id) }}" method="POST" class="d-inline form-hapus">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn btn-danger btn-sm btn-hapus"><i class="fas fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="modalTambah" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <form method="POST" action="{{ route('super_admin.products.store') }}" class="modal-content" enctype="multipart/form-data">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title font-weight-bold">Tambah Produk</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="form-group"><label>Nama Produk</label><input type="text" name="nama_produk" class="form-control" required></div>
                <div class="row">
                    <div class="col-6">
                        <div class="form-group"><label>Kategori</label>
                            <select name="kategori" class="form-control" required>
                                <option value="">Pilih...</option>
                                <option value="Portofolio">Portofolio</option>
                                <option value="Admin">Admin Dashboard</option>
                                <option value="Shop">Shop / Retail</option>
                                <option value="Company Profile">Company Profile</option>
                                <option value="E-Commerce">E-Commerce</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-6"><div class="form-group"><label>Harga (Rp)</label><input type="number" name="harga" class="form-control" required></div></div>
                </div>
                <div class="form-group"><label>Link GitHub</label><input type="url" name="link_github" class="form-control" required></div>
                <div class="form-group"><label>Deskripsi</label><textarea name="deskripsi" class="form-control" rows="3"></textarea></div>
                <div class="form-group"><label>Status</label>
                    <select name="status" class="form-control">
                        <option value="active">Active</option>
                        <option value="pending">Pending</option>
                    </select>
                </div>
                <div class="form-group"><label>Cover</label><input type="file" name="gambar" class="form-control-file" required></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="modalEdit" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <form method="POST" action="{{ route('super_admin.products.update') }}" class="modal-content" enctype="multipart/form-data">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title font-weight-bold">Edit Produk</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="id" id="edit-id">
                
                <div class="form-group"><label>Nama Produk</label><input type="text" name="nama_produk" id="edit-nama" class="form-control" required></div>
                
                <div class="row">
                    <div class="col-6">
                        <div class="form-group"><label>Kategori</label>
                            <select name="kategori" id="edit-kategori" class="form-control" required>
                                <option value="Portofolio">Portofolio</option>
                                <option value="Admin">Admin Dashboard</option>
                                <option value="Shop">Shop / Retail</option>
                                <option value="Company Profile">Company Profile</option>
                                <option value="E-Commerce">E-Commerce</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-6"><div class="form-group"><label>Harga (Rp)</label><input type="number" name="harga" id="edit-harga" class="form-control" required></div></div>
                </div>

                <div class="form-group"><label>Link GitHub</label><input type="url" name="link_github" id="edit-link" class="form-control" required></div>
                <div class="form-group"><label>Deskripsi</label><textarea name="deskripsi" id="edit-deskripsi" class="form-control" rows="3"></textarea></div>
                
                <div class="form-group"><label>Status</label>
                    <select name="status" id="edit-status" class="form-control">
                        <option value="active">Active</option>
                        <option value="pending">Pending</option>
                        <option value="rejected">Rejected</option>
                    </select>
                </div>
                
                <div class="form-group"><label>Ganti Cover</label><input type="file" name="gambar" class="form-control-file"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-success">Update</button>
            </div>
        </form>
    </div>
</div>

<div id="flash-data" 
     data-success="{{ session('success') }}" 
     data-error="{{ session('error') }}" 
     data-validation="{{ $errors->any() ? $errors->first() : '' }}"
     style="display: none;">
</div>

<script src="{{ asset('assets/vendor/jquery/jquery.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        
        // Modal Edit Filler
        $('body').on('click', '.btn-edit', function() {
            $('#edit-id').val($(this).data('id'));
            $('#edit-nama').val($(this).data('nama'));
            $('#edit-kategori').val($(this).data('kategori'));
            $('#edit-harga').val($(this).data('harga'));
            $('#edit-deskripsi').val($(this).data('deskripsi'));
            $('#edit-link').val($(this).data('link'));
            $('#edit-status').val($(this).data('status'));
        });

        // Hapus Confirm
        $('body').on('click', '.btn-hapus', function(e) {
            e.preventDefault();
            var form = $(this).closest('form');
            Swal.fire({
                title: 'Hapus Produk?',
                text: "Data yang dihapus tidak bisa dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                confirmButtonText: 'Ya, Hapus'
            }).then((result) => {
                if (result.isConfirmed) form.submit();
            });
        });

        // Flash Message Logic
        var flashData = $('#flash-data');
        if(flashData.data('success')) {
            Swal.fire({ title: 'Berhasil!', text: flashData.data('success'), icon: 'success', timer: 2000, showConfirmButton: false });
        }
        if(flashData.data('error')) {
            Swal.fire({ title: 'Gagal!', text: flashData.data('error'), icon: 'error' });
        }
        if(flashData.data('validation')) {
            Swal.fire({ title: 'Error Validasi', text: flashData.data('validation'), icon: 'error' });
        }
    });
</script>

@endsection