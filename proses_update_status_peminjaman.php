<?php
session_start();

// Cek jika pengguna tidak login atau bukan admin
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
    header('Location: login.php');
    exit();
}
include 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];

    // Mendapatkan informasi peminjaman
    $queryGetInfo = "SELECT judul, jumlah FROM peminjaman WHERE id = '$id'";
    $resultGetInfo = mysqli_query($conn, $queryGetInfo);
    if ($row = mysqli_fetch_assoc($resultGetInfo)) {
        $judulBuku = $row['judul'];
        $jumlah = $row['jumlah'];

        // Update status menjadi "Ditolak"
        $queryUpdateStatus = "UPDATE peminjaman SET status = 'ditolak' WHERE id = '$id'";
        mysqli_query($conn, $queryUpdateStatus);

        // Update jumlah stok buku di tabel buku berdasarkan judul buku
        $queryUpdateBuku = "UPDATE buku SET jumlah = jumlah + $jumlah WHERE judul_buku = '$judulBuku'";
        mysqli_query($conn, $queryUpdateBuku);
    }

    // Redirect atau lakukan tindakan lain setelah pembaruan
    header('Location: peminjaman.php');
    exit();
} else {
    // Request bukan POST, redirect ke halaman yang sesuai
    header('Location: peminjaman.php');
    exit();
}
?>
