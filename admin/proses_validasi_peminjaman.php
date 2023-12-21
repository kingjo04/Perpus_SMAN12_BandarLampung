<?php
date_default_timezone_set('Asia/Jakarta');
include 'koneksi.php';

if (isset($_POST['id'])) {
    $idPeminjaman = mysqli_real_escape_string($conn, $_POST['id']);
    $tanggalSekarang = date('Y-m-d'); // Mendapatkan tanggal saat ini

    // Fetch data peminjaman termasuk nama peminjam
$queryPeminjaman = "SELECT nama, judul, jumlah, tenggat, jumlah, no_hp FROM peminjaman WHERE id = ?";
$stmtPeminjaman = mysqli_prepare($conn, $queryPeminjaman);
mysqli_stmt_bind_param($stmtPeminjaman, 'i', $idPeminjaman);
mysqli_stmt_execute($stmtPeminjaman);
$resultPeminjaman = mysqli_stmt_get_result($stmtPeminjaman);

if (!$resultPeminjaman) {
    die("Error fetching data: " . mysqli_error($conn));
}

$dataPeminjaman = mysqli_fetch_assoc($resultPeminjaman);

    // Cek kesesuaian judul dan jumlah stok buku
    $judulBuku = $dataPeminjaman['judul'];
    $jumlahDipinjam = $dataPeminjaman['jumlah'];
    $queryStok = "SELECT jumlah FROM buku WHERE judul_buku = ?";
    $stmtStok = mysqli_prepare($conn, $queryStok);
    mysqli_stmt_bind_param($stmtStok, 's', $judulBuku);
    mysqli_stmt_execute($stmtStok);
    $resultStok = mysqli_stmt_get_result($stmtStok);

    if ($resultStok) {
        $dataStok = mysqli_fetch_assoc($resultStok);
        if ($dataStok['jumlah'] >= $jumlahDipinjam) {
            // Proses pengembalian jika stok mencukupi

            // Insert data ke pengembalian
            $queryInsertPengembalian = "INSERT INTO pengembalian (nama, judul, tanggal, tenggat, jumlah, no_hp, status) VALUES (?, ?, ?, ?, ?, ?, ?)";
            $statusPengembalian = "belum dikembalikan";
            $stmtInsertPengembalian = mysqli_prepare($conn, $queryInsertPengembalian);
            mysqli_stmt_bind_param($stmtInsertPengembalian, 'ssssiss', $dataPeminjaman['nama'], $dataPeminjaman['judul'], $tanggalSekarang, $dataPeminjaman['tenggat'], $dataPeminjaman['jumlah'], $dataPeminjaman['no_hp'], $statusPengembalian);

            if (!mysqli_stmt_execute($stmtInsertPengembalian)) {
                die("Error inserting data: " . mysqli_error($conn));
            }

            // Update status di peminjaman
            $queryUpdatePeminjaman = "UPDATE peminjaman SET status = 'divalidasi', status_pengembalian = 'belum dikembalikan', tanggal = ? WHERE id = ?";
            $stmtUpdatePeminjaman = mysqli_prepare($conn, $queryUpdatePeminjaman);
            mysqli_stmt_bind_param($stmtUpdatePeminjaman, 'si', $tanggalSekarang, $idPeminjaman);

            if (!mysqli_stmt_execute($stmtUpdatePeminjaman)) {
                die("Error updating data: " . mysqli_error($conn));
            }

            mysqli_close($conn);
            echo '<script>window.location.href = "peminjaman.php";</script>';
        } else {
            echo "<script>alert('Stok buku tidak mencukupi'); window.location.href = 'peminjaman.php';</script>";
        }
    } else {
        die("Error checking stock: " . mysqli_error($conn));
    }
}
?>
