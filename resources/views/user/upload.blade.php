@extends('layouts.dashboard.master')

@section('content')

<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h1 class="h3 mb-1 text-gray-800" style="font-weight: 700;">Daftar Produk Anda</h1>
        <p class="mb-0 text-muted small">Kelola produk digital yang Anda jual di marketplace.</p>
    </div>
    
    <button class="d-none d-sm-inline-block btn btn-sm btn-success shadow-sm" data-toggle="modal" data-target="#modalTambah" style="border-radius: 50px; padding: 0.5rem 1.5rem;">
        <i class="fas fa-plus fa-sm text-white mr-2"></i> Upload Produk
    </button>
</div>

<div class="card mb-4 border-0"> <div class="card-body p-0"> <div class="table-responsive">
            <table class="table table-hover mb-0" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th class="pl-4" style="width: 80px;">Cover</th>
                        <th>Info Produk</th>
                        <th class="text-center">Repo</th>
                        <th>Harga</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Featured</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $p)
                        @php
                            $gambarPath = asset('assets/uploads/' . $p->gambar);
                            if(empty($p->gambar)) $gambarPath = 'https://placehold.co/60x60?text=No+Img';
                        @endphp
                        <tr>
                            <td class="pl-4 py-3 align-middle">
                                <img src="{{ $gambarPath }}" class="img-thumbnail-product" alt="Cover">
                            </td>
                            <td class="align-middle">
                                <div class="font-weight-bold text-dark mb-1" style="font-size: 0.95rem;">{{ $p->nama_produk }}</div>
                                <span class="badge badge-secondary" style="font-size: 0.65rem; font-weight: 500; background: #f1f5f9; color: #64748b; border: 1px solid #e2e8f0;">
                                    <i class="fas fa-tag mr-1"></i> {{ $p->kategori }}
                                </span>
                            </td>
                            <td class="text-center align-middle">
                                <a href="{{ $p->link_github }}" target="_blank" class="repo-link" title="Lihat Repository">
                                    <i class="fa-brands fa-github fa-lg"></i>
                                </a>
                            </td>
                            <td class="align-middle font-weight-bold text-dark">
                                Rp {{ number_format($p->harga, 0, ',', '.') }}
                            </td>
                            <td class="text-center align-middle">
                                @if($p->status == 'active')
                                    <span class="badge badge-success shadow-sm">
                                        <i class="fas fa-check-circle mr-1"></i> Active
                                    </span>
                                @elseif($p->status == 'rejected')
                                    <span class="badge badge-danger shadow-sm">
                                        <i class="fas fa-times-circle mr-1"></i> Rejected
                                    </span>
                                @else
                                    <span class="badge badge-warning shadow-sm text-white">
                                        <i class="fas fa-clock mr-1"></i> Pending
                                    </span>
                                @endif
                            </td>
                            <td class="text-center align-middle">
                                @if($p->is_featured)
                                    <i class="fa-solid fa-star text-warning fa-lg" style="filter: drop-shadow(0 2px 4px rgba(246, 194, 62, 0.4));"></i>
                                @else
                                    <i class="fa-regular fa-star text-gray-300"></i>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <img src="{{ asset('img/undraw_empty.svg') }}" style="width: 120px; opacity: 0.6; margin-bottom: 15px;">
                                <p class="text-muted mb-0 font-weight-bold">Belum ada produk.</p>
                                <small class="text-gray-500">Mulai upload karya terbaikmu sekarang!</small>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="modalTambah" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document"> <form method="POST" action="{{ route('user.upload.store') }}" class="modal-content" enctype="multipart/form-data">
            @csrf
            
            <div class="modal-header">
                <h5 class="modal-title font-weight-bold text-dark">
                    <i class="fas fa-cloud-upload-alt mr-2 text-primary"></i> Upload Produk
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            
            <div class="modal-body">
                <div class="form-group mb-3">
                    <label class="small font-weight-bold text-muted">Nama Produk</label>
                    <input type="text" name="nama_produk" class="form-control" placeholder="Contoh: Modern E-Commerce UI Kit" required>
                </div>
                
                <div class="row">
                    <div class="col-6">
                        <div class="form-group mb-3">
                            <label class="small font-weight-bold text-muted">Kategori</label>
                            <select name="kategori" class="form-control custom-select" required>
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
                        <div class="form-group mb-3">
                            <label class="small font-weight-bold text-muted">Harga (IDR)</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-light border-right-0" style="border-radius: 10px 0 0 10px;">Rp</span>
                                </div>
                                <input type="number" name="harga" class="form-control border-left-0" placeholder="0" style="border-radius: 0 10px 10px 0;" required>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group mb-3">
                    <label class="small font-weight-bold text-muted">Link Repository (Github/Gitlab)</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text bg-light" style="border-radius: 10px 0 0 10px;"><i class="fa-brands fa-github"></i></span>
                        </div>
                        <input type="url" name="link_github" class="form-control" placeholder="https://github.com/username/repo" style="border-radius: 0 10px 10px 0;" required>
                    </div>
                </div>

                <div class="form-group mb-3">
                    <label class="small font-weight-bold text-muted">Deskripsi Singkat</label>
                    <textarea name="deskripsi" class="form-control" rows="3" placeholder="Jelaskan fitur utama produk Anda..."></textarea>
                </div>
                
                <div class="form-group mb-0">
                    <label class="small font-weight-bold text-muted">Cover Image</label>
                    <div class="custom-file-container">
                        <input type="file" name="gambar" class="form-control-file" accept="image/*" required>
                        <small class="text-muted mt-1 d-block">*Format: JPG, PNG. Max: 2MB. Rasio disarankan 1:1.</small>
                    </div>
                </div>
            </div>
            
            <div class="modal-footer bg-light border-0" style="border-radius: 0 0 20px 20px;">
                <button type="button" class="btn btn-secondary" data-dismiss="modal" style="border-radius: 50px; padding: 0.5rem 1.5rem;">Batal</button>
                <button type="submit" class="btn btn-primary shadow-sm" style="border-radius: 50px; padding: 0.5rem 1.5rem;">
                    <i class="fas fa-paper-plane mr-2"></i> Upload Sekarang
                </button>
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
            timer: 2500,
            showConfirmButton: false,
            background: '#fff',
            backdrop: `rgba(0,0,123,0.1)`
        });
    });
</script>
@endif

@endsection 