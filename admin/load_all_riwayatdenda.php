<?php
session_start();

// Cek jika pengguna tidak login atau bukan admin
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
    header('Location: login.php');
    exit();
}
include 'koneksi.php';

// Ubah query sesuai kebutuhan
$queryriwayat_denda = "SELECT * FROM denda WHERE status = 'lunas'";
$resultriwayat_denda = mysqli_query($conn, $queryriwayat_denda);

// Inisialisasi nomor urut
$no = 1;

while ($rowriwayat_denda = mysqli_fetch_assoc($resultriwayat_denda)) {
    $rowColor = $no % 2 === 0 ? '#9EDDFF' : '#F4F4F4';

    // Menentukan warna teks untuk status
    $statusColor = ($rowriwayat_denda['status'] == 'lunas') ? '#00ff00' : '#ff0000'; // Hijau untuk lunas, Merah untuk belum lunas

    echo "<tr style='background-color: $rowColor;'>
            <td style='text-align: center;'>$no</td>
            <td style='text-align: center;'>{$rowriwayat_denda['nama']}</td>
            <td style='text-align: center;'>{$rowriwayat_denda['judul']}</td>
            <td style='text-align: center;'>{$rowriwayat_denda['start']}</td>
            <td style='text-align: center;'>{$rowriwayat_denda['tenggat']}</td>
            <td style='text-align: center;'>{$rowriwayat_denda['total']}</td>
            
            // Menggunakan warna yang telah ditentukan untuk kolom status
            <td style='text-align: center; color: $statusColor;'>{$rowriwayat_denda['status']}</td>
            
          </tr>";

    $no++; // Increment nomor urut
}


mysqli_close($conn);
?>
