@extends('layouts.dashboard.master')

@section('content')

<style>
    .img-thumbnail-product { width: 60px; height: 60px; object-fit: cover; border-radius: 8px; border: 1px solid #ddd; }
</style>

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Daftar Produk Anda</h1>
    <button class="btn btn-sm btn-primary shadow-sm" data-toggle="modal" data-target="#modalTambah">
        <i class="fas fa-plus fa-sm text-white-50 mr-2"></i> Upload Produk Baru
    </button>
</div>

<div class="card shadow mb-4">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                <thead class="bg-light">
                    <tr>
                        <th>Cover</th>
                        <th>Produk</th>
                        <th>Repo</th>
                        <th>Harga</th>
                        <th>Status</th>
                        <th>Featured</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $p)
                        @php
                            // Cek gambar
                            $gambarPath = asset('assets/uploads/' . $p->gambar);
                            if(empty($p->gambar)) $gambarPath = 'https://placehold.co/60x60';
                        @endphp
                        <tr>
                            <td class="text-center">
                                <img src="{{ $gambarPath }}" class="img-thumbnail-product">
                            </td>
                            <td>
                                <div class="font-weight-bold text-dark">{{ $p->nama_produk }}</div>
                                <div class="small text-muted">{{ $p->kategori }}</div>
                            </td>
                            <td class="text-center">
                                <a href="{{ $p->link_github }}" target="_blank" class="text-dark">
                                    <i class="fa-brands fa-github fa-lg"></i>
                                </a>
                            </td>
                            <td>Rp {{ number_format($p->harga, 0, ',', '.') }}</td>
                            <td>
                                @if($p->status == 'active')
                                    <span class="badge badge-success">Active</span>
                                @elseif($p->status == 'rejected')
                                    <span class="badge badge-danger">Rejected</span>
                                @else
                                    <span class="badge badge-warning">Pending</span>
                                    <div class="small text-muted mt-1">Menunggu Review</div>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($p->is_featured)
                                    <i class="fa-solid fa-star text-warning"></i>
                                @else
                                    <i class="fa-regular fa-star text-gray-300"></i>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4">Belum ada produk yang diupload.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="modalTambah" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <form method="POST" action="{{ route('user.upload.store') }}" class="modal-content" enctype="multipart/form-data">
            @csrf <div class="modal-header">
                <h5 class="modal-title font-weight-bold">Upload Produk</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            
            <div class="modal-body">
                <div class="form-group">
                    <label>Nama Produk</label>
                    <input type="text" name="nama_produk" class="form-control" required>
                </div>
                
                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <label>Kategori</label>
                            <select name="kategori" class="form-control" required>
                                <option value="">Pilih...</option>
                                <option value="Portofolio">Portofolio</option>
                                <option value="Admin">Admin</option>
                                <option value="Shop">Shop</option>
                                <option value="Company Profile">Company Profile</option>
                                <option value="E-Commerce">E-Commerce</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label>Harga (Rp)</label>
                            <input type="number" name="harga" class="form-control" required>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label><i class="fa-brands fa-github"></i> Link Github</label>
                    <input type="url" name="link_github" class="form-control" placeholder="https://github.com/..." required>
                </div>

                <div class="form-group">
                    <label>Deskripsi</label>
                    <textarea name="deskripsi" class="form-control" rows="2"></textarea>
                </div>
                
                <div class="form-group">
                    <label>Cover (Gambar)</label>
                    <input type="file" name="gambar" class="form-control-file" accept="image/*" required>
                </div>
            </div>
            
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary">Upload Sekarang</button>
            </div>
        </form>
    </div>
</div>

@if(session('success'))
<script>
    document.addEventListener("DOMContentLoaded", function() {
        Swal.fire({
            title: 'Berhasil!',
            text: "{{ session('success') }}",
            icon: 'success',
            timer: 2000,
            showConfirmButton: false
        });
    });
</script>
@endif

@endsection