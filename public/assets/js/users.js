/**
 * Script khusus untuk Halaman Kelola Pengguna
 */

$(document).ready(function() {
    
    // ==================================================
    // 1. LOGIC ISI MODAL EDIT
    // ==================================================
    // Menggunakan 'body' delegation agar tetap jalan meski tabel direfresh (jika pakai ajax)
    $('body').on('click', '.btn-edit', function() {
        // Ambil data dari atribut data-* tombol
        let id = $(this).data('id');
        let username = $(this).data('username');
        let role = $(this).data('role');
        let status = $(this).data('status');

        // Masukkan ke dalam input form modal
        $('#edit-id').val(id);
        $('#edit-username').val(username);
        $('#edit-role').val(role);
        $('#edit-status').val(status);
    });

    // ==================================================
    // 2. LOGIC KONFIRMASI HAPUS (SweetAlert)
    // ==================================================
    $('body').on('click', '.btn-hapus', function(e) {
        e.preventDefault(); // Mencegah form submit langsung
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
                form.submit(); // Submit form jika user klik Ya
            }
        });
    });

});