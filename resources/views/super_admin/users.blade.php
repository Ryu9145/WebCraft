@extends('layouts.super_admin.master')

@section('content')

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Daftar Pengguna</h1>
    <button class="btn btn-sm btn-primary shadow-sm" data-toggle="modal" data-target="#modalTambah">
        <i class="fas fa-user-plus fa-sm text-white-50 mr-2"></i> Tambah User Baru
    </button>
</div>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Data User & Admin</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped" id="dataTable" width="100%" cellspacing="0">
                <thead class="table-primary">
                    <tr>
                        <th>No</th>
                        <th>User Info</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $index => $u)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>
                            <div class="d-flex align-items-center">
                                <img class="rounded-circle mr-2" src="https://ui-avatars.com/api/?name={{ $u->username }}&size=30&background=random" width="30">
                                <div>
                                    <div class="font-weight-bold small">{{ $u->username }}</div>
                                    <div class="small text-gray-500">{{ $u->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            @if($u->role == 'superadmin') 
                                <span class="badge badge-danger">Super Admin</span>
                            @elseif($u->role == 'admin') 
                                <span class="badge badge-success">Admin</span>
                            @else 
                                <span class="badge badge-secondary">User</span>
                            @endif
                        </td>
                        <td>
                            @if($u->status == 'active') 
                                <span class="badge badge-success">Active</span>
                            @elseif($u->status == 'suspended') 
                                <span class="badge badge-danger">Suspended</span>
                            @else 
                                <span class="badge badge-secondary">{{ ucfirst($u->status) }}</span>
                            @endif
                        </td>
                        <td>
                            <button class="btn btn-warning btn-sm btn-edit" 
                                data-id="{{ $u->id }}"
                                data-username="{{ $u->username }}"
                                data-role="{{ $u->role }}"
                                data-status="{{ $u->status }}"
                                data-toggle="modal" data-target="#modalEdit">
                                <i class="fas fa-pen"></i>
                            </button>
                            
                            <form action="{{ route('super_admin.users.destroy', $u->id) }}" method="POST" class="d-inline form-hapus">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn btn-danger btn-sm btn-hapus">
                                    <i class="fas fa-trash"></i>
                                </button>
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
        <form method="POST" action="{{ route('super_admin.users.store') }}" class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title">Tambah User Baru</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="form-group"><label>Username</label><input type="text" name="username" class="form-control" required></div>
                <div class="form-group"><label>Email</label><input type="email" name="email" class="form-control" required></div>
                <div class="form-group"><label>Password</label><input type="password" name="password" class="form-control" required></div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Role</label>
                            <select name="role" class="form-control">
                                <option value="user">User</option>
                                <option value="admin">Admin</option>
                                <option value="superadmin">Super Admin</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Status</label>
                            <select name="status" class="form-control">
                                <option value="active">Active</option>
                                <option value="suspended">Suspended</option>
                            </select>
                        </div>
                    </div>
                </div>
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
        <form method="POST" action="{{ route('super_admin.users.update') }}" class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title">Edit User</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="id" id="edit-id">
                
                <div class="form-group"><label>Username</label><input type="text" name="username" id="edit-username" class="form-control" required></div>
                
                <div class="form-group">
                    <label>Password Baru <small class="text-muted">(Kosongkan jika tidak ingin ubah)</small></label>
                    <input type="password" name="password" class="form-control" placeholder="******">
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Role</label>
                            <select name="role" id="edit-role" class="form-control">
                                <option value="user">User</option>
                                <option value="admin">Admin</option>
                                <option value="superadmin">Super Admin</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Status</label>
                            <select name="status" id="edit-status" class="form-control">
                                <option value="active">Active</option>
                                <option value="suspended">Suspended</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-success">Update Data</button>
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
                confirmButtonText: 'Ya, Hapus'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });

        // 3. LOGIC NOTIFIKASI (MENGAMBIL DARI ATRIBUT HTML)
        // Ini cara paling aman agar Javascript tidak error sintaks
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
                showConfirmButton: false
            });
        }

        if (errorMessage) {
            Swal.fire({
                title: 'Gagal!',
                text: errorMessage,
                icon: 'error'
            });
        }

        if (validationMessage) {
            Swal.fire({
                title: 'Error Validasi',
                text: validationMessage,
                icon: 'error'
            });
        }
    });
</script>

@endsection