@extends('layouts.super_admin.master')

@section('content')

<style>
    /* Gambar Produk */
    .img-product-admin {
        width: 50px; 
        height: 50px; 
        object-fit: cover; 
        border-radius: 12px; 
        border: 2px solid #fff; 
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        transition: transform 0.2s;
    }
    .img-product-admin:hover {
        transform: scale(1.5);
        z-index: 10;
        position: relative;
    }

    /* Tombol Link Bulat */
    .btn-circle-action {
        width: 35px; height: 35px;
        border-radius: 50%;
        display: inline-flex;
        align-items: center; justify-content: center;
        background: #f8fafc; color: #1e293b;
        border: 1px solid #e2e8f0;
        transition: 0.2s;
        text-decoration: none !important;
    }
    .btn-circle-action:hover {
        background: #0f172a; color: #fff; transform: translateY(-2px);
    }
</style>

<div class="d-sm-flex align-items-center justify-content-between mb-4 sticky-dashboard-header">
    <div>
        <h1 class="h3 mb-1 text-gray-800" style="font-weight: 700;">Daftar Produk Template</h1>
        <p class="mb-0 text-muted small">Kelola, moderasi, dan atur produk unggulan.</p>
    </div>
    <button class="btn btn-success shadow-sm btn-sm" data-toggle="modal" data-target="#modalTambah" style="border-radius: 50px; padding: 0.5rem 1.5rem;">
        <i class="fas fa-plus fa-sm text-white mr-2"></i> Tambah Produk
    </button>
</div>

<div class="card mb-4 border-0 shadow-sm">
    <div class="card-header py-3 bg-transparent border-bottom-0">
        <h6 class="m-0 font-weight-bold text-primary">Katalog Produk</h6>
    </div>
    
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th class="pl-4" width="80">Cover</th>
                        <th>Info Produk</th>
                        <th class="text-center">Link Repo</th>
                        <th>Kategori</th>
                        <th>Harga</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Featured</th>
                        <th class="text-center" width="150">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($products as $p)
                    <tr>
                        <td class="pl-4 align-middle">
                            <img src="{{ asset('assets/uploads/' . ($p->gambar ?? 'default.jpg')) }}" class="img-product-admin">
                        </td>

                        <td class="align-middle">
                            <div class="font-weight-bold text-gray-800">{{ $p->nama_produk }}</div>
                            <div class="small text-muted text-truncate" style="max-width: 150px;">{{ Str::limit($p->deskripsi, 40) }}</div>
                        </td>

                        <td class="align-middle text-center">
                            <a href="{{ $p->link_github }}" target="_blank" class="btn-circle-action shadow-sm" title="Lihat Repo">
                                <i class="fab fa-github"></i>
                            </a>
                        </td>

                        <td class="align-middle">
                            <span class="badge badge-secondary shadow-sm" style="background: #eef2f7 !important; color: #475569 !important;">
                                {{ $p->kategori }}
                            </span>
                        </td>

                        <td class="align-middle text-gray-700">
                            Rp {{ number_format($p->harga, 0, ',', '.') }}
                        </td>

                        <td class="align-middle text-center">
                            @if($p->status == 'active')
                                <span class="badge badge-success shadow-sm px-3 py-2">Active</span>
                            @elseif($p->status == 'rejected')
                                <span class="badge badge-danger shadow-sm px-3 py-2">Rejected</span>
                            @else
                                <div class="d-flex flex-column align-items-center">
                                    <span class="badge badge-warning shadow-sm px-3 py-1 mb-1 text-white">Pending</span>
                                </div>
                            @endif
                        </td>

                        <td class="align-middle text-center">
                            <a href="{{ route('super_admin.products.status', ['id' => $p->id, 'action' => 'feature']) }}" 
                               class="btn btn-link p-0 {{ $p->is_featured ? 'text-warning' : 'text-gray-300' }}" 
                               title="Set Featured" style="font-size: 1.2rem;">
                                <i class="{{ $p->is_featured ? 'fas' : 'far' }} fa-star"></i>
                            </a>
                        </td>

                        <td class="align-middle text-center">
                            <div style="position: relative; z-index: 50;">
                                <button class="btn btn-info btn-sm btn-edit shadow-sm mr-1" 
                                    style="border-radius: 50px; padding: 0.25rem 0.8rem;"
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
                                    <button type="button" class="btn btn-danger btn-sm btn-hapus shadow-sm" 
                                        style="border-radius: 50px; padding: 0.25rem 0.8rem;">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="modalTambah" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <form method="POST" action="{{ route('super_admin.products.store') }}" class="modal-content border-0 shadow-lg" enctype="multipart/form-data">
            @csrf
            <div class="modal-header border-bottom-0 pb-0">
                <h5 class="modal-title font-weight-bold text-gray-800">Tambah Produk</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            
            <div class="modal-body pt-3">
                <div class="form-group">
                    <label class="small font-weight-bold text-muted">Nama Produk</label>
                    <input type="text" name="nama_produk" class="form-control" required>
                </div>
                
                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <label class="small font-weight-bold text-muted">Kategori</label>
                            <select name="kategori" class="form-control" required>
                                <option value="" disabled selected>Pilih...</option>
                                <option value="Portofolio">Portofolio</option>
                                <option value="Admin">Admin Dashboard</option>
                                <option value="Shop">Shop / Retail</option>
                                <option value="Company Profile">Company Profile</option>
                                <option value="E-Commerce">E-Commerce</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label class="small font-weight-bold text-muted">Harga (Rp)</label>
                            <input type="number" name="harga" class="form-control" required>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="small font-weight-bold text-muted">Link Repository</label>
                    <input type="url" name="link_github" class="form-control" required>
                </div>

                <div class="form-group">
                    <label class="small font-weight-bold text-muted">Deskripsi</label>
                    <textarea name="deskripsi" class="form-control" rows="3"></textarea>
                </div>
                
                <div class="form-group">
                    <label class="small font-weight-bold text-muted">Status</label>
                    <select name="status" class="form-control">
                        <option value="active">Active</option>
                        <option value="pending">Pending</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label class="small font-weight-bold text-muted">Cover Image</label>
                    <input type="file" name="gambar" class="form-control-file" required>
                </div>
            </div>
            
            <div class="modal-footer border-top-0 pt-0">
                <button type="button" class="btn btn-secondary" data-dismiss="modal" style="border-radius: 50px;">Batal</button>
                <button type="submit" class="btn btn-primary shadow-sm" style="border-radius: 50px;">Simpan</button>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="modalEdit" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <form method="POST" action="{{ route('super_admin.products.update') }}" class="modal-content border-0 shadow-lg" enctype="multipart/form-data">
            @csrf
            <div class="modal-header border-bottom-0 pb-0">
                <h5 class="modal-title font-weight-bold text-gray-800">Edit Produk</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            
            <div class="modal-body pt-3">
                <input type="hidden" name="id" id="edit-id">
                
                <div class="form-group">
                    <label class="small font-weight-bold text-muted">Nama Produk</label>
                    <input type="text" name="nama_produk" id="edit-nama" class="form-control" required>
                </div>
                
                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <label class="small font-weight-bold text-muted">Kategori</label>
                            <select name="kategori" id="edit-kategori" class="form-control" required>
                                <option value="Portofolio">Portofolio</option>
                                <option value="Admin">Admin Dashboard</option>
                                <option value="Shop">Shop / Retail</option>
                                <option value="Company Profile">Company Profile</option>
                                <option value="E-Commerce">E-Commerce</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label class="small font-weight-bold text-muted">Harga</label>
                            <input type="number" name="harga" id="edit-harga" class="form-control" required>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="small font-weight-bold text-muted">Link Github</label>
                    <input type="url" name="link_github" id="edit-link" class="form-control" required>
                </div>

                <div class="form-group">
                    <label class="small font-weight-bold text-muted">Deskripsi</label>
                    <textarea name="deskripsi" id="edit-deskripsi" class="form-control" rows="3"></textarea>
                </div>
                
                <div class="form-group">
                    <label class="small font-weight-bold text-muted">Status</label>
                    <select name="status" id="edit-status" class="form-control">
                        <option value="active">Active</option>
                        <option value="pending">Pending</option>
                        <option value="rejected">Rejected</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label class="small font-weight-bold text-muted">Ganti Cover</label>
                    <input type="file" name="gambar" class="form-control-file">
                </div>
            </div>
            
            <div class="modal-footer border-top-0 pt-0">
                <button type="button" class="btn btn-secondary" data-dismiss="modal" style="border-radius: 50px;">Batal</button>
                <button type="submit" class="btn btn-success shadow-sm" style="border-radius: 50px;">Update Data</button>
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
                confirmButtonText: 'Ya, Hapus',
                backdrop: `rgba(0,0,123,0.1)` // Tambahan efek backdrop saja
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