<?php
include 'koneksi.php';

session_start();

// Cek jika pengguna tidak login atau bukan admin
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil data dari form
    $id = $_POST['id'];
    $namasiswa = $_POST['namasiswa'];
    $nis = $_POST['nis'];
    $kelas = $_POST['kelas'];
    $no_hp = $_POST['no_hp'];
    $alamat = $_POST['alamat'];
    $username = $_POST['username'];
    $password = $_POST['password']; // Pastikan melakukan hashing password jika diperlukan

    // Query untuk mengupdate data siswa
    $queryUpdateSiswa = "UPDATE siswa SET 
                         namasiswa = '$namasiswa', 
                         nis = '$nis', 
                         kelas = '$kelas', 
                         no_hp = '$no_hp', 
                         alamat = '$alamat', 
                         password = '$password' 
                         WHERE id = '$id'";

    // Eksekusi query update siswa
    if (mysqli_query($conn, $queryUpdateSiswa)) {
        // Jika update siswa berhasil, update no_hp di tabel denda
        $queryUpdateDenda = "UPDATE denda SET no_hp = '$no_hp' WHERE nama = (SELECT nama FROM siswa WHERE username = '$username')";
        if (mysqli_query($conn, $queryUpdateDenda)) {
            // Redirect jika kedua update berhasil
            header('Location: pengaturan.php?status=success');
            exit();
        } else {
            echo "Error saat memperbarui nomor telepon di tabel denda: " . mysqli_error($conn);
        }
    } else {
        echo "Error saat memperbarui data siswa: " . mysqli_error($conn);
    }
}

// Jika metode request bukan POST, tampilkan pesan error atau redirect
else {
    header('Location: pengaturan.php?status=error');
    exit();
}
?>
