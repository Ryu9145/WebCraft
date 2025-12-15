@extends('layouts.super_admin.master')

@section('content')

<div class="d-sm-flex align-items-center justify-content-between mb-4 sticky-dashboard-header">
    <div>
        <h1 class="h3 mb-1 text-gray-800" style="font-weight: 700;">Daftar Pengguna</h1>
        <p class="mb-0 text-muted small">Kelola data User, Admin, dan Super Admin.</p>
    </div>
    <button class="btn btn-success shadow-sm btn-sm" data-toggle="modal" data-target="#modalTambah" style="border-radius: 50px; padding: 0.5rem 1.5rem;">
        <i class="fas fa-user-plus fa-sm text-white-50 mr-2"></i> Tambah User
    </button>
</div>

<div class="card mb-4 border-0 shadow-sm">
    <div class="card-header py-3 bg-transparent border-bottom-0">
        <h6 class="m-0 font-weight-bold text-primary">Data User & Admin</h6>
    </div>
    
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th class="pl-4" width="5%">No</th>
                        <th>User Info</th>
                        <th>Role</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $index => $u)
                    <tr>
                        <td class="pl-4 align-middle">{{ $index + 1 }}</td>
                        
                        <td class="align-middle">
                            <div class="d-flex align-items-center">
                                <img class="rounded-circle mr-3 shadow-sm" 
                                     src="https://ui-avatars.com/api/?name={{ $u->username }}&size=40&background=random&color=fff&bold=true" 
                                     width="40" height="40" style="border: 2px solid #fff;">
                                <div>
                                    <div class="font-weight-bold text-gray-800">{{ $u->username }}</div>
                                    <div class="small text-muted">{{ $u->email }}</div>
                                </div>
                            </div>
                        </td>

                        <td class="align-middle">
                            @if($u->role == 'superadmin') 
                                <span class="badge badge-primary shadow-sm px-3 py-2">
                                    <i class="fas fa-crown mr-1"></i> Super Admin
                                </span>
                            @elseif($u->role == 'admin') 
                                <span class="badge badge-primary shadow-sm px-3 py-2">
                                    <i class="fas fa-user-shield mr-1"></i> Admin
                                </span>
                            @else 
                                <span class="badge badge-secondary shadow-sm px-3 py-2">
                                    <i class="fas fa-user mr-1"></i> User
                                </span>
                            @endif
                        </td>

                        <td class="align-middle text-center">
                            @if($u->status == 'active') 
                                <span class="badge badge-success shadow-sm px-3 py-2">Active</span>
                            @elseif($u->status == 'suspended') 
                                <span class="badge badge-danger shadow-sm px-3 py-2">Suspended</span>
                            @else 
                                <span class="badge badge-warning shadow-sm px-3 py-2 text-white">{{ ucfirst($u->status) }}</span>
                            @endif
                        </td>

                        <td class="align-middle text-center">
                            <div style="position: relative; z-index: 50;">
                                <button class="btn btn-info btn-sm btn-edit shadow-sm mr-1" 
                                    style="border-radius: 50px; padding: 0.25rem 0.8rem;"
                                    data-id="{{ $u->id }}"
                                    data-username="{{ $u->username }}"
                                    data-role="{{ $u->role }}"
                                    data-status="{{ $u->status }}"
                                    data-toggle="modal" data-target="#modalEdit">
                                    <i class="fas fa-edit text-white"></i>
                                </button>
                                
                                <form action="{{ route('super_admin.users.destroy', $u->id) }}" method="POST" class="d-inline form-hapus">
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
        <form method="POST" action="{{ route('super_admin.users.store') }}" class="modal-content border-0 shadow-lg">
            @csrf
            <div class="modal-header border-bottom-0 pb-0">
                <h5 class="modal-title font-weight-bold text-gray-800">Tambah User Baru</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            
            <div class="modal-body pt-3">
                <div class="form-group">
                    <label class="small font-weight-bold text-muted">Username</label>
                    <input type="text" name="username" class="form-control" required>
                </div>
                <div class="form-group">
                    <label class="small font-weight-bold text-muted">Email</label>
                    <input type="email" name="email" class="form-control" required>
                </div>
                <div class="form-group">
                    <label class="small font-weight-bold text-muted">Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="small font-weight-bold text-muted">Role</label>
                            <select name="role" class="form-control">
                                <option value="user">User</option>
                                <option value="admin">Admin</option>
                                <option value="superadmin">Super Admin</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="small font-weight-bold text-muted">Status</label>
                            <select name="status" class="form-control">
                                <option value="active">Active</option>
                                <option value="suspended">Suspended</option>
                            </select>
                        </div>
                    </div>
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
        <form method="POST" action="{{ route('super_admin.users.update') }}" class="modal-content border-0 shadow-lg">
            @csrf
            <div class="modal-header border-bottom-0 pb-0">
                <h5 class="modal-title font-weight-bold text-gray-800">Edit User</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            
            <div class="modal-body pt-3">
                <input type="hidden" name="id" id="edit-id">
                
                <div class="form-group">
                    <label class="small font-weight-bold text-muted">Username</label>
                    <input type="text" name="username" id="edit-username" class="form-control" required>
                </div>
                
                <div class="form-group">
                    <label class="small font-weight-bold text-muted">Password Baru <span class="text-muted font-weight-normal">(Opsional)</span></label>
                    <input type="password" name="password" class="form-control" placeholder="******">
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="small font-weight-bold text-muted">Role</label>
                            <select name="role" id="edit-role" class="form-control">
                                <option value="user">User</option>
                                <option value="admin">Admin</option>
                                <option value="superadmin">Super Admin</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="small font-weight-bold text-muted">Status</label>
                            <select name="status" id="edit-status" class="form-control">
                                <option value="active">Active</option>
                                <option value="suspended">Suspended</option>
                            </select>
                        </div>
                    </div>
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
<script src="{{ asset('assets/js/pages/users.js') }}"></script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        
        // 1. ISI MODAL EDIT
        $('body').on('click', '.btn-edit', function() {
            var id = $(this).data('id');
            var username = $(this).data('username');
            var role = $(this).data('role');
            var status = $(this).data('status');

            $('#edit-id').val(id);
            $('#edit-username').val(username);
            $('#edit-role').val(role);
            $('#edit-status').val(status);
        });

        // 2. KONFIRMASI HAPUS
        $('body').on('click', '.btn-hapus', function(e) {
            e.preventDefault();
            var form = $(this).closest('form');
            
            Swal.fire({
                title: 'Hapus User?',
                text: "Data yang dihapus tidak bisa dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Hapus',
                backdrop: `rgba(0,0,123,0.1)` // Tambahan efek backdrop
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });

        // 3. LOGIC NOTIFIKASI
        var flashData = $('#flash-data');
        var successMessage = flashData.data('success');
        var errorMessage = flashData.data('error');
        var validationMessage = flashData.data('validation');

        if (successMessage) {
            Swal.fire({
                title: 'Berhasil!',
                text: successMessage,
                icon: 'success',
                timer: 2000,
                showConfirmButton: false,
                backdrop: `rgba(0,0,123,0.1)`
            });
        }

        if (errorMessage) {
            Swal.fire({ title: 'Gagal!', text: errorMessage, icon: 'error' });
        }

        if (validationMessage) {
            Swal.fire({ title: 'Error Validasi', text: validationMessage, icon: 'error' });
        }
    });
</script>

@endsection