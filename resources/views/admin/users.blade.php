@extends('layouts.admin.master')

@section('content')

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800" style="font-weight: 700;">User Management</h1>
</div>

<div class="card mb-4 border-0 shadow-sm" style="background: rgba(54, 185, 204, 0.1); border-left: 4px solid #36b9cc !important;">
    <div class="card-body py-3">
        <div class="d-flex align-items-center">
            <div class="mr-3">
                <div class="icon-circle bg-info text-white">
                    <i class="fas fa-info"></i>
                </div>
            </div>
            <div>
                <span class="font-weight-bold text-info">Info Moderasi:</span>
                <span class="small text-gray-700">Halaman ini khusus untuk memverifikasi penjual baru atau memblokir user yang melanggar aturan.</span>
            </div>
        </div>
    </div>
</div>

<div class="card mb-4 border-0">
    <div class="card-header py-3 bg-transparent border-bottom-0">
        <h6 class="m-0 font-weight-bold text-primary">Daftar User (Customer)</h6>
    </div>
    
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th class="pl-4" width="5%">No</th>
                        <th>Info User</th>
                        <th>Email</th>
                        <th class="text-center">Status Akun</th>
                        <th>Bergabung</th>
                        <th class="text-center" width="20%">Aksi Moderasi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $index => $u)
                    <tr>
                        <td class="pl-4 align-middle font-weight-bold">{{ $index + 1 }}</td>
                        <td class="align-middle">
                            <div class="d-flex align-items-center">
                                <img class="rounded-circle mr-3 shadow-sm" 
                                     style="border: 2px solid #fff;"
                                     src="https://ui-avatars.com/api/?name={{ $u->username }}&size=40&background=random&color=fff&bold=true" 
                                     width="40" height="40">
                                <div>
                                    <div class="font-weight-bold text-gray-800">{{ $u->username }}</div>
                                    <div class="small text-muted">ID: #{{ $u->id }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-gray-600">{{ $u->email }}</td>
                        
                        <td class="align-middle text-center">
                            @if($u->status == 'active')
                                <span class="badge badge-success shadow-sm px-3 py-2">
                                    <i class="fas fa-check-circle mr-1"></i> Active
                                </span>
                            @elseif($u->status == 'suspended')
                                <span class="badge badge-danger shadow-sm px-3 py-2">
                                    <i class="fas fa-ban mr-1"></i> Suspended
                                </span>
                            @else
                                <span class="badge badge-warning shadow-sm px-3 py-2 text-white">
                                    <i class="fas fa-clock mr-1"></i> Menunggu Verifikasi
                                </span>
                            @endif
                        </td>
                        
                        <td class="align-middle small">
                            <i class="far fa-calendar-alt mr-1 text-gray-400"></i>
                            {{ $u->created_at ? $u->created_at->format('d/m/Y') : '-' }}
                        </td>

                        <td class="align-middle text-center">
                            <div style="position: relative; z-index: 100;">
                                @if($u->status == 'pending')
                                    <button class="btn btn-sm btn-success btn-aksi shadow-sm" 
                                        style="border-radius: 50px;"
                                        data-href="{{ route('admin.users.status', ['id' => $u->id, 'status' => 'active']) }}"
                                        data-pesan="Verifikasi user ini agar bisa berjualan?">
                                        <i class="fas fa-check"></i> Verifikasi
                                    </button>

                                @elseif($u->status == 'active')
                                    <button class="btn btn-sm btn-danger btn-aksi shadow-sm" 
                                        style="border-radius: 50px;"
                                        data-href="{{ route('admin.users.status', ['id' => $u->id, 'status' => 'suspended']) }}"
                                        data-pesan="Suspend user ini? Mereka tidak akan bisa login.">
                                        <i class="fas fa-ban"></i> Suspend
                                    </button>

                                @elseif($u->status == 'suspended')
                                    <button class="btn btn-sm btn-secondary btn-aksi shadow-sm" 
                                        style="border-radius: 50px;"
                                        data-href="{{ route('admin.users.status', ['id' => $u->id, 'status' => 'active']) }}"
                                        data-pesan="Aktifkan kembali user ini?">
                                        <i class="fas fa-undo"></i> Buka Suspend
                                    </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5">
                            <img src="{{ asset('assets/img/undraw_empty.svg') }}" style="width: 120px; opacity: 0.5; margin-bottom: 15px;">
                            <p class="text-muted mb-0">Tidak ada data user.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
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
        
        // 1. LOGIC SWEETALERT KONFIRMASI (TIDAK DIUBAH, HANYA STYLE ALERT DIPERCHNTIK)
        $('body').on('click', '.btn-aksi', function(e) {
            e.preventDefault();
            const href = $(this).data('href');
            const pesan = $(this).data('pesan');

            Swal.fire({
                title: 'Konfirmasi',
                text: pesan,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#4e73df',
                cancelButtonColor: '#858796',
                confirmButtonText: 'Ya, Lakukan',
                cancelButtonText: 'Batal',
                background: '#fff',
                backdrop: `rgba(0,0,123,0.1)` // Efek blur belakang
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = href;
                }
            });
        });

        // 2. NOTIFIKASI FLASH DATA
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