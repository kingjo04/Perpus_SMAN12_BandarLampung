<?php

session_start();

// Cek jika pengguna tidak login atau bukan admin
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
    header('Location: login.php');
    exit();
}
include 'koneksi.php'; // Sertakan file koneksi database Anda

// Periksa apakah form telah disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil data dari form
    $id = $_POST['id'];
    $nomor_buku = $_POST['nomor'];
    $judul_buku = $_POST['judul'];
    $penerbit = $_POST['penerbit'];
    $pengarang = $_POST['pengarang'];
    $kategori = $_POST['kategori'];
    $tahun = $_POST['tahun'];
    $jumlah = $_POST['jumlah'];

    // Sanitasi input untuk mencegah SQL injection
    $id = mysqli_real_escape_string($conn, $id);
    $nomor_buku = mysqli_real_escape_string($conn, $nomor_buku);
    $judul_buku = mysqli_real_escape_string($conn, $judul_buku);
    $penerbit = mysqli_real_escape_string($conn, $penerbit);
    $pengarang = mysqli_real_escape_string($conn, $pengarang);
    $kategori = mysqli_real_escape_string($conn, $kategori);
    $tahun = mysqli_real_escape_string($conn, $tahun);
    $jumlah = mysqli_real_escape_string($conn, $jumlah);

    // Inisialisasi variabel untuk file sampul
    $namaFileSampul = null;

    // Cek apakah file sampul diupload
    if (isset($_FILES['sampul']) && $_FILES['sampul']['error'] == 0) {
        $namaFileSampul = $_FILES['sampul']['name'];
        $path = "../user/uploads/" . $namaFileSampul; // Ganti dengan path direktori upload Anda
        move_uploaded_file($_FILES['sampul']['tmp_name'], $path);
    }

    // Query update data buku
    $query = "UPDATE buku SET nomor_buku = '$nomor_buku', judul_buku = '$judul_buku', penerbit = '$penerbit', pengarang = '$pengarang', kategori = '$kategori', tahun = '$tahun', jumlah = '$jumlah'". ($namaFileSampul ? ", sampul = '$namaFileSampul'" : "") ." WHERE id = $id";

    if (mysqli_query($conn, $query)) {
        echo '<script>window.location.href = "buku.php";</script>';
        header('Location: buku.php'); // Redirect ke halaman buku.php
    } else {
        echo "Gagal memperbarui data buku: " . mysqli_error($conn);
    }
} else {
    echo "Invalid request method.";
}


?>