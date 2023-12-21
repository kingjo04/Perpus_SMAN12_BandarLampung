<?php
session_start();

// Cek jika pengguna tidak login atau bukan admin
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
    header('Location: login.php');
    exit();
}
include 'koneksi.php';

// Ubah query sesuai kebutuhan
$queryPengembalian = "SELECT * FROM pengembalian WHERE status = 'belum dikembalikan'";
$resultPengembalian = mysqli_query($conn, $queryPengembalian);

// Inisialisasi nomor urut
$no = 1;

while ($rowPengembalian = mysqli_fetch_assoc($resultPengembalian)) {
    $rowColor = $no % 2 === 0 ? '#9EDDFF' : '#F4F4F4';

    echo "<tr style='background-color: $rowColor;'>
            <td style='text-align: center;'>$no</td>
            <td style='text-align: center;'>{$rowPengembalian['nama']}</td>
            <td style='text-align: center;'>{$rowPengembalian['judul']}</td>
            <td style='text-align: center;'>{$rowPengembalian['tanggal']}</td>
            <td style='text-align: center;'>{$rowPengembalian['tenggat']}</td>
            <td style='text-align: center;'>{$rowPengembalian['jumlah']}</td>
            <td style='text-align: center; padding: 10px;'>
                <form action='update_denda_status.php' method='post'>
                    <input type='hidden' name='id_pengembalian' value='{$rowPengembalian['id']}'>
                    <button type='submit' class='btn btn-success btn-sm'>Validasi</button>
                </form>
            </td>
          </tr>";

    $no++; // Increment nomor urut
}

mysqli_close($conn);
?>
