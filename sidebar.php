<div class="container-fluid">
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-content">
            <img id="logo" class="logo-image" src="assets/dashboard/logo.png" alt="logosma">
            <p style="color: #618264; font-weight:bold;">SMA NEGERI 12</p>
            <p style="color: #618264; font-weight:bold;">BANDAR LAMPUNG</p>
        </div>

        <div class="navigation-box" data-href="dashboard.php" onclick="redirectTo('dashboard.php')">
            <img src="assets/dashboard/Speedometer.png" alt="Dashboard">
            <p>Dashboard</p>
        </div>

        <div class="navigation-box" data-href="buku.php" onclick="redirectTo('buku.php')">
            <img src="assets/dashboard/Book Shelf.png" alt="Buku">
            <p>Buku</p>
        </div>

        <div class="navigation-box" data-href="peminjaman.php" onclick="redirectTo('peminjaman.php')">
            <img src="assets/dashboard/Return Book (1).png" alt="Peminjaman">
            <p>Peminjaman</p>
        </div>

        <div class="navigation-box" data-href="pengembalian.php" onclick="redirectTo('pengembalian.php')">
            <img src="assets/dashboard/Return Book.png" alt="Pengembalian">
            <p>Pengembalian</p>
        </div>

        <div class="navigation-box" data-href="denda.php" onclick="redirectTo('denda.php')">
            <img src="assets/dashboard/Stack of Coins.png" alt="Denda">
            <p>Denda</p>
        </div>

        <div class="navigation-box" data-href="pengaturan.php" onclick="redirectTo('pengaturan.php')">
            <img src="assets/dashboard/Gear.png" alt="Pengaturan">
            <p>Pengaturan</p>
        </div>
    </div>


</div>

<!-- ... (your HTML code) ... -->

<script>
function redirectTo(page) {
    window.location.href = page;
}
</script>

<script>
document.addEventListener("DOMContentLoaded", function() {
    // Ambil nama halaman saat ini dari URL
    var currentPage = window.location.pathname.split('/').pop();

    // List elemen navigasi
    var navigationBoxes = document.querySelectorAll('.navigation-box');

    // Loop melalui setiap elemen navigasi
    navigationBoxes.forEach(function(box) {
        // Ambil href dari elemen navigasi
        var pageHref = box.getAttribute('data-href');

        // Periksa jika halaman saat ini adalah riwayatkembali.php dan navigasi adalah pengembalian.php
        if (currentPage === 'riwayatkembali.php' && pageHref === 'pengembalian.php') {
            box.classList.add('active');
        }
        // Periksa jika halaman saat ini adalah riwayatdenda.php dan navigasi adalah denda.php
        else if (currentPage === 'riwayatdenda.php' && pageHref === 'denda.php') {
            box.classList.add('active');
        }
        else if (currentPage === 'riwayatpeminjaman.php' && pageHref === 'peminjaman.php') {
            box.classList.add('active');
        }
        // Jika tidak, cek kesamaan dengan halaman saat ini
        else if (currentPage === pageHref) {
            box.classList.add('active');
        }
    });
});

function redirectTo(page) {
    window.location.href = page;
}
</script>