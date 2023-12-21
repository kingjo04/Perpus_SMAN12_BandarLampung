<?php

session_start();

// Cek jika pengguna tidak login atau bukan admin
// Cek jika pengguna tidak login atau bukan siswa
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'siswa') {
    header("Location: ../admin/login.php"); // Jalur relatif ke halaman login
    exit();
}
include 'config.php';

if (isset($_POST['id'])) {
    $id = $_POST['id'];
    // Lakukan query penghapusan buku berdasarkan ID
    $queryHapusBuku = "DELETE FROM keranjang_pinjam WHERE id = '$id'";
    $conn->query($queryHapusBuku);
}
?>