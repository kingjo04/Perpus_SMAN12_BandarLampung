<?php
session_start();

// Cek jika pengguna tidak login atau bukan admin
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
    header('Location: login.php');
    exit();
}

include 'koneksi.php'; // Pastikan file koneksi.php sudah benar


$id = $_GET['id']; // Mendapatkan ID dari URL

// Query untuk menghapus data buku
$query = "DELETE FROM buku WHERE id = $id";
$result = mysqli_query($conn, $query);

if($result){
    // Redirect kembali ke buku.php atau tampilkan pesan sukses
    header('Location: buku.php');
} else {
    echo "Gagal menghapus data";
}
?>