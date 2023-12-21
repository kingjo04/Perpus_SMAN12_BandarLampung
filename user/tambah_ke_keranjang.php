<?php
date_default_timezone_set('Asia/Jakarta');

session_start();

// Cek jika pengguna tidak login atau bukan siswa
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'siswa') {
    header("Location: ../admin/login.php"); // Jalur relatif ke halaman login
    exit();
}

include 'config.php';

$username = $_SESSION['username'];
$id_buku = $_POST['id_buku'];

// Ambil data buku dari tabel buku
$queryBuku = $conn->query("SELECT * FROM buku WHERE id = '$id_buku'");
$buku = $queryBuku->fetch_assoc();

// Siapkan data untuk tabel keranjang_pinjam
$tgl_pinjam = date('Y-m-d'); // Mengambil tanggal hari ini
$tgl_kembali = date('Y-m-d', strtotime('+7 days')); // Mengambil tanggal hari ini ditambah 7 hari

// Cek jumlah buku yang tersedia
$jumlah_tersedia = $buku['jumlah'];

if ($jumlah_tersedia > 0) {
    // Cek apakah buku sudah ada di keranjang_pinjam
    $queryCekKeranjang = "SELECT * FROM keranjang_pinjam WHERE username = '$username' AND judul_buku = '{$buku['judul_buku']}'";
    $resultCek = $conn->query($queryCekKeranjang);

    if ($resultCek->num_rows > 0) {
        // Buku sudah ada, update jumlahnya
        $dataKeranjang = $resultCek->fetch_assoc();
        $jumlahBaru = $dataKeranjang['jumlah'] + 1;
        $queryUpdateKeranjang = "UPDATE keranjang_pinjam SET jumlah = '$jumlahBaru' WHERE username = '$username' AND judul_buku = '{$buku['judul_buku']}'";
        $conn->query($queryUpdateKeranjang);
    } else {
        // Buku belum ada, tambahkan ke keranjang
        // Ambil nomor telepon (no_hp) dari tabel siswa berdasarkan username
        $queryNomorTelepon = $conn->query("SELECT no_hp FROM siswa WHERE username = '$username'");
        $nomorTelepon = $queryNomorTelepon->fetch_assoc()['no_hp'];

        $queryTambahKeranjang = "INSERT INTO keranjang_pinjam (username, nomor_buku, judul_buku, tgl_pinjam, tgl_kembali, jumlah, sampul, no_hp) VALUES ('$username', '{$buku['nomor_buku']}', '{$buku['judul_buku']}', '$tgl_pinjam', '$tgl_kembali', '1', '{$buku['sampul']}', '$nomorTelepon')";
        $conn->query($queryTambahKeranjang);
    }
} else {
    // Buku tidak tersedia, berikan peringatan atau tindakan lainnya
    $_SESSION['pesan'] = "Maaf, buku ini tidak tersedia.";
}

// Redirect kembali ke halaman dashboard atau keranjang pinjam
header('Location: keranjangpinjam.php');
?>