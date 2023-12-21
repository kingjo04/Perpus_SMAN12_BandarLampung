<?php

session_start();

// Cek jika pengguna tidak login atau bukan admin
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
    header('Location: login.php');
    exit();
}

date_default_timezone_set('Asia/Jakarta');
session_start();

// Cek jika pengguna tidak login atau bukan admin
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
    header('Location: login.php');
    exit();
}

include 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil ID pengembalian dari formulir
    $id_pengembalian = $_POST['id_pengembalian'];

    // Cari data pengembalian berdasarkan ID
    $queryPengembalian = "SELECT * FROM pengembalian WHERE id = $id_pengembalian";
    $resultPengembalian = mysqli_query($conn, $queryPengembalian);

    if (!$resultPengembalian) {
        die("Query failed: " . mysqli_error($conn));
    }

    // Periksa apakah data pengembalian ditemukan
    if (mysqli_num_rows($resultPengembalian) == 1) {
        $rowPengembalian = mysqli_fetch_assoc($resultPengembalian);
        $tanggalSekarang = date('Y-m-d'); // Menggunakan tanggal saat ini
        
        // Perbarui status pengembalian menjadi 'dikembalikan'
        $queryUpdatePengembalian = "UPDATE pengembalian SET status = 'dikembalikan' WHERE id = $id_pengembalian";
        if (!mysqli_query($conn, $queryUpdatePengembalian)) {
            die("Update pengembalian failed: " . mysqli_error($conn));
        }

        // Cek apakah pengembalian terlambat atau tepat waktu
        $statusKembali = ($tanggalSekarang > $rowPengembalian['tenggat']) ? 'terlambat' : 'tepat waktu';

        // Insert data ke riwayat_kembali dengan status terlambat atau tepat waktu
        $queryInsertRiwayatKembali = "INSERT INTO riwayat_kembali (nama, judul, tanggal, tenggat, kembali, jumlah, status) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmtInsertRiwayatKembali = mysqli_prepare($conn, $queryInsertRiwayatKembali);

        mysqli_stmt_bind_param($stmtInsertRiwayatKembali, 'sssssis', 
            $rowPengembalian['nama'], 
            $rowPengembalian['judul'], 
            $rowPengembalian['tanggal'], 
            $rowPengembalian['tenggat'], 
            $tanggalSekarang, 
            $rowPengembalian['jumlah'], 
            $statusKembali);

        if (!mysqli_stmt_execute($stmtInsertRiwayatKembali)) {
            die("Insert riwayat_kembali failed: " . mysqli_error($conn));
        }

        // Perbarui status denda menjadi "lunas" di tabel "denda"
        $queryUpdateDenda = "UPDATE denda SET status = 'lunas' WHERE nama = '{$rowPengembalian['nama']}' AND judul = '{$rowPengembalian['judul']}'";
        $resultUpdateDenda = mysqli_query($conn, $queryUpdateDenda);

        if (!$resultUpdateDenda) {
            die("Query failed: " . mysqli_error($conn));
        }
        

        // Update status_pengembalian di tabel peminjaman berdasarkan nama, judul, tanggal, dan tenggat
        $queryUpdatePeminjaman = "UPDATE peminjaman SET status_pengembalian = 'dikembalikan' WHERE 
            nama = '{$rowPengembalian['nama']}' AND 
            judul = '{$rowPengembalian['judul']}' AND 
            tanggal = '{$rowPengembalian['tanggal']}' AND 
            tenggat = '{$rowPengembalian['tenggat']}'";

        $resultUpdatePeminjaman = mysqli_query($conn, $queryUpdatePeminjaman);



        if (!$resultUpdatePeminjaman) {
            die("Query failed: " . mysqli_error($conn));
        }

        // Ambil jumlah buku yang dikembalikan
    $jumlahDikembalikan = $rowPengembalian['jumlah'];

    // Update stok buku di tabel buku
    $queryUpdateStokBuku = "UPDATE buku SET jumlah = jumlah + $jumlahDikembalikan WHERE judul_buku = '{$rowPengembalian['judul']}'";
    if (!mysqli_query($conn, $queryUpdateStokBuku)) {
        die("Update stok buku failed: " . mysqli_error($conn));
    }

        // Redirect kembali ke halaman "Pengembalian" atau halaman lain yang sesuai
        header('Location: pengembalian.php');
        exit();
    } else {
        // Data pengembalian tidak ditemukan
        echo "Data pengembalian tidak ditemukan.";
    }
}

// Tutup koneksi database
mysqli_close($conn);
?>