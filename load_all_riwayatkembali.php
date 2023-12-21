<?php
session_start();

// Cek jika pengguna tidak login atau bukan admin
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
    header('Location: login.php');
    exit();
}
include 'koneksi.php';

// Ubah query sesuai kebutuhan
$queryriwayat_kembali = "SELECT * FROM riwayat_kembali";
$resultriwayat_kembali = mysqli_query($conn, $queryriwayat_kembali);

// Inisialisasi nomor urut
$no = 1;

while ($rowriwayat_kembali = mysqli_fetch_assoc($resultriwayat_kembali)) {
    $rowColor = $no % 2 === 0 ? '#9EDDFF' : '#F4F4F4';

    // Set color for status
    $statusColor = '';
    if ($rowriwayat_kembali['status'] == 'tepat waktu') {
        $statusColor = 'green';
    } elseif ($rowriwayat_kembali['status'] == 'terlambat') {
        $statusColor = 'red';
    }

    echo "<tr style='background-color: $rowColor;'>
            <td style='text-align: center;'>$no</td>
            <td style='text-align: center;'>{$rowriwayat_kembali['nama']}</td>
            <td style='text-align: center;'>{$rowriwayat_kembali['judul']}</td>
            <td style='text-align: center;'>{$rowriwayat_kembali['tanggal']}</td>
            <td style='text-align: center;'>{$rowriwayat_kembali['tenggat']}</td>
            <td style='text-align: center;'>{$rowriwayat_kembali['kembali']}</td>
            <td style='text-align: center;'>{$rowriwayat_kembali['jumlah']}</td>
            <td style='text-align: center; color: $statusColor;'>{$rowriwayat_kembali['status']}</td>
          </tr>";

    $no++; // Increment nomor urut
}

mysqli_close($conn);
?>
