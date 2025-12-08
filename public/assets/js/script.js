// Function Add To Cart
function addToCart(idProduk) {
    
    // Mengambil CSRF Token dari meta tag di header (Nanti kita pasang di Layout utama)
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    /* KODE AJAX NANTI:
    fetch('/cart/add', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        },
        body: JSON.stringify({ id: idProduk })
    })
    .then(response => response.json())
    .then(data => {
        // Update badge logika disini
    });
    */
}