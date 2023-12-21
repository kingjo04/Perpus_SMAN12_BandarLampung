<?php
session_start();

// Cek jika pengguna tidak login atau bukan admin
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
    header('Location: login.php');
    exit();
}
include 'koneksi.php';

// Ubah query sesuai kebutuhan
$querypeminjaman = "SELECT * FROM peminjaman";
$resultpeminjaman = mysqli_query($conn, $querypeminjaman);

// Inisialisasi nomor urut
$no = 1;

while ($rowpeminjaman = mysqli_fetch_assoc($resultpeminjaman)) {
    $rowColor = $no % 2 === 0 ? '#9EDDFF' : '#F4F4F4';

    // Set color for status validation
    $statusColor = '';
    if ($rowpeminjaman['status'] == 'divalidasi') {
        $statusColor = 'green';
    } elseif ($rowpeminjaman['status'] == 'ditolak') {
        $statusColor = 'red';
    } elseif ($rowpeminjaman['status'] == 'menunggu divalidasi') {
        $statusColor = 'grey';
    }

    // Set color for status pengembalian
    $pengembalianColor = '';
    if ($rowpeminjaman['status_pengembalian'] == 'dikembalikan') {
        $pengembalianColor = 'green';
    } elseif ($rowpeminjaman['status_pengembalian'] == 'belum dikembalikan') {
        $pengembalianColor = 'red';
    }

    echo "<tr style='background-color: $rowColor;'>
            <td style='text-align: center;'>$no</td>
            <td style='text-align: center;'>{$rowpeminjaman['nama']}</td>
            <td style='text-align: center;'>{$rowpeminjaman['judul']}</td>
            <td style='text-align: center;'>{$rowpeminjaman['tanggal']}</td>
            <td style='text-align: center;'>{$rowpeminjaman['tenggat']}</td>
            
            <td style='text-align: center;'>{$rowpeminjaman['jumlah']}</td>
            <td style='text-align: center; color: $statusColor;'>{$rowpeminjaman['status']}</td>
            <td style='text-align: center; color: $pengembalianColor;'>{$rowpeminjaman['status_pengembalian']}</td>
            
          </tr>";

    $no++; // Increment nomor urut
}

mysqli_close($conn);
?>
