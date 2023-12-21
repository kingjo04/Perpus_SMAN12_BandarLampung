<?php

session_start();

// Cek jika pengguna tidak login atau bukan siswa
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'siswa') {
    header("Location: ../admin/login.php"); // Jalur relatif ke halaman login
    exit();
}

include 'config.php';

$username = $_SESSION['username'];

if (isset($_POST['bukuDipilih']) && isset($_POST['tanggal_pinjam']) && isset($_POST['tanggal_kembali'])) {
    $bukuTidakCukup = false; // Inisialisasi variabel untuk peringatan buku tidak cukup

    $tanggal_pinjam = $_POST['tanggal_pinjam'];
    $tanggal_kembali = $_POST['tanggal_kembali'];

    // Ambil nomor telepon (no_hp) dari tabel siswa berdasarkan username
    $queryNomorTelepon = $conn->query("SELECT no_hp FROM siswa WHERE username = '$username'");
    $nomorTelepon = $queryNomorTelepon->fetch_assoc()['no_hp'];

    foreach ($_POST['bukuDipilih'] as $key => $idKeranjang) {
        // Ambil data buku dari keranjang pinjam
        $queryKeranjang = "SELECT * FROM keranjang_pinjam WHERE id = '$idKeranjang'";
        $resultKeranjang = $conn->query($queryKeranjang);
        $buku = $resultKeranjang->fetch_assoc();

        $nama = $username;
        $nomor = $buku['nomor_buku'];
        $judul = $buku['judul_buku'];
        $tanggal = $tanggal_pinjam[$key]; // Mengambil tanggal pinjam sesuai indeks
        $tenggat = $tanggal_kembali[$key]; // Mengambil tanggal kembali sesuai indeks
        $jumlah = $buku['jumlah'];

        // Cek jumlah buku yang tersedia di tabel buku
        $queryCekJumlahBuku = "SELECT jumlah FROM buku WHERE nomor_buku = '$nomor'";
        $resultJumlahBuku = $conn->query($queryCekJumlahBuku);
        $dataJumlahBuku = $resultJumlahBuku->fetch_assoc();
        $jumlahBukuTersedia = $dataJumlahBuku['jumlah'];

        if ($jumlahBukuTersedia >= $jumlah) {
            // Mengurangi jumlah buku di tabel buku
            $queryUpdateBuku = "UPDATE buku SET jumlah = jumlah - '$jumlah' WHERE nomor_buku = '$nomor'";
            $conn->query($queryUpdateBuku);

            // Menambahkan data ke tabel peminjaman
            // Menambahkan data ke tabel peminjaman dengan status 'menunggu divalidasi'
        $queryTambahPeminjaman = "INSERT INTO peminjaman (nama, nomor_buku, judul, tanggal, tenggat, jumlah, no_hp, status) VALUES ('$nama', '$nomor', '$judul', '$tanggal', '$tenggat', '$jumlah', '$nomorTelepon', 'menunggu divalidasi')";
        $conn->query($queryTambahPeminjaman);

            // Hapus item dari keranjang berdasarkan ID
            $queryHapusItem = "DELETE FROM keranjang_pinjam WHERE id = '$idKeranjang'";
            $conn->query($queryHapusItem);
        } else {
            // Buku tidak cukup, set flag untuk peringatan
            $bukuTidakCukup = true;
        }
    }

    // Jika ada buku yang tidak cukup, tampilkan peringatan
    if ($bukuTidakCukup) {
        $_SESSION['pesan'] = "Maaf, salah satu atau beberapa buku tidak cukup.";
    }
}

// Redirect kembali ke halaman keranjang atau konfirmasi
header('Location: keranjangpinjam.php');
?>