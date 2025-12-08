// public/assets/js/custom-script.js

$(document).ready(function() {
    $('.btn-logout').on('click', function(e) {
        e.preventDefault();
        Swal.fire({
            title: 'Logout?',
            text: "Sesi Anda akan diakhiri.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            confirmButtonText: 'Ya, Keluar!'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('logout-form').submit();
            }
        });
    });
});