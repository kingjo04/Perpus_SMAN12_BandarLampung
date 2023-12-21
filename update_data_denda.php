<?php

// Atur zona waktu ke WIB (Waktu Indonesia Barat)
date_default_timezone_set('Asia/Jakarta');

include 'koneksi.php';

// Perbarui query untuk memilih hanya data dengan status 'belum dikembalikan' dan tenggat waktu kurang dari hari ini
$queryPengembalian = "SELECT * FROM pengembalian WHERE status = 'belum dikembalikan' AND tenggat < CURDATE()";
$resultPengembalian = mysqli_query($conn, $queryPengembalian);

if (!$resultPengembalian) {
    die("Query failed: " . mysqli_error($conn));
}

if (!$resultPengembalian) {
    die("Query failed: " . mysqli_error($conn));
}

while ($rowPengembalian = mysqli_fetch_assoc($resultPengembalian)) {
    // Ambil informasi jumlah buku dari tabel peminjaman
    $queryPeminjaman = "SELECT * FROM peminjaman WHERE nama = '{$rowPengembalian['nama']}' AND judul = '{$rowPengembalian['judul']}' AND tanggal = '{$rowPengembalian['tanggal']}' AND tenggat = '{$rowPengembalian['tenggat']}'";
    $resultPeminjaman = mysqli_query($conn, $queryPeminjaman);

    if (!$resultPeminjaman) {
        die("Query failed: " . mysqli_error($conn));
    }

    $rowPeminjaman = mysqli_fetch_assoc($resultPeminjaman);
    
    $tanggalTenggat = new DateTime($rowPengembalian['tenggat']);
    $tanggalSekarang = new DateTime(); // Ini akan menggunakan zona waktu yang diatur di atas
    
    $interval = $tanggalSekarang->diff($tanggalTenggat);
    $hariTerlambat = $interval->days;
    
    // Perhitungan denda berdasarkan jumlah buku
    $jumlahBuku = $rowPeminjaman['jumlah'];
    $jumlahDendaSeharusnya = $hariTerlambat * $jumlahBuku * 1000;

    // Cek apakah sudah ada data denda untuk pengembalian ini
    $queryCekDenda = "SELECT * FROM denda WHERE nama = '{$rowPengembalian['nama']}' AND judul = '{$rowPengembalian['judul']}' AND start = '{$rowPengembalian['tanggal']}' AND tenggat = '{$rowPengembalian['tenggat']}'";
    $resultCekDenda = mysqli_query($conn, $queryCekDenda);

    if (mysqli_num_rows($resultCekDenda) > 0) {
        $rowDenda = mysqli_fetch_assoc($resultCekDenda);
        if ($rowDenda['total'] != $jumlahDendaSeharusnya) {
            // Update jumlah denda jika tidak sesuai
            $queryUpdateDenda = "UPDATE denda SET total = '$jumlahDendaSeharusnya' WHERE nama = '{$rowPengembalian['nama']}' AND judul = '{$rowPengembalian['judul']}' AND start = '{$rowPengembalian['tanggal']}' AND tenggat = '{$rowPengembalian['tenggat']}'";
            mysqli_query($conn, $queryUpdateDenda);
        }
    } else {
        // Jika tidak ada data denda, masukkan data baru
        $queryInsertDenda = "INSERT INTO denda (nama, judul, start, tenggat, total, status, no_hp) VALUES ('{$rowPengembalian['nama']}', '{$rowPengembalian['judul']}', '{$rowPengembalian['tanggal']}', '{$rowPengembalian['tenggat']}', '$jumlahDendaSeharusnya', 'belum lunas', '{$rowPengembalian['no_hp']}')";
        mysqli_query($conn, $queryInsertDenda);
    }
}

mysqli_close($conn);
?>
