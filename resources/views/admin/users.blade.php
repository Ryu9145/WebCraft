@extends('layouts.admin.master')

@section('content')

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">User Management (Moderasi)</h1>
</div>

<div class="alert alert-info border-left-info shadow-sm" role="alert">
    <i class="fas fa-info-circle mr-1"></i>
    Halaman ini khusus untuk memverifikasi penjual baru atau memblokir user yang melanggar aturan.
</div>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Daftar User (Customer)</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover" id="dataTable" width="100%" cellspacing="0">
                <thead class="bg-primary text-white">
                    <tr>
                        <th width="5%">No</th>
                        <th>Info User</th>
                        <th>Email</th>
                        <th>Status Akun</th>
                        <th>Bergabung</th>
                        <th width="20%">Aksi Moderasi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $index => $u)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>
                            <div class="d-flex align-items-center">
                                <img class="rounded-circle mr-2" src="https://ui-avatars.com/api/?name={{ $u->username }}&size=30&background=random" width="30">
                                <div class="font-weight-bold">{{ $u->username }}</div>
                            </div>
                        </td>
                        <td>{{ $u->email }}</td>
                        <td>
                            @if($u->status == 'active')
                                <span class="badge badge-success px-2 py-1">Active</span>
                            @elseif($u->status == 'suspended')
                                <span class="badge badge-danger px-2 py-1">Suspended</span>
                            @else
                                <span class="badge badge-warning px-2 py-1">Menunggu Verifikasi</span>
                            @endif
                        </td>
                        <td class="small">
                            {{ $u->created_at ? $u->created_at->format('d/m/Y') : '-' }}
                        </td>
                        <td>
                            @if($u->status == 'pending')
                                <button class="btn btn-sm btn-success btn-aksi" 
                                    data-href="{{ route('admin.users.status', ['id' => $u->id, 'status' => 'active']) }}"
                                    data-pesan="Verifikasi user ini agar bisa berjualan?">
                                    <i class="fas fa-check"></i> Verifikasi
                                </button>

                            @elseif($u->status == 'active')
                                <button class="btn btn-sm btn-danger btn-aksi" 
                                    data-href="{{ route('admin.users.status', ['id' => $u->id, 'status' => 'suspended']) }}"
                                    data-pesan="Suspend user ini? Mereka tidak akan bisa login.">
                                    <i class="fas fa-ban"></i> Suspend
                                </button>

                            @elseif($u->status == 'suspended')
                                <button class="btn btn-sm btn-outline-secondary btn-aksi" 
                                    data-href="{{ route('admin.users.status', ['id' => $u->id, 'status' => 'active']) }}"
                                    data-pesan="Aktifkan kembali user ini?">
                                    <i class="fas fa-undo"></i> Buka Suspend
                                </button>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center">Tidak ada data user.</td>
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
        
        // 1. LOGIC SWEETALERT KONFIRMASI
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
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Lakukan'
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