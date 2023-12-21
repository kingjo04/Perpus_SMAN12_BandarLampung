<?php

session_start();

// Cek jika pengguna tidak login atau bukan admin
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
    header('Location: login.php');
    exit();
}
include 'koneksi.php';

$searchTerm = mysqli_real_escape_string($conn, $_GET['term']);

$query = "SELECT * FROM riwayat_kembali WHERE (nama LIKE '%$searchTerm%' OR judul LIKE '%$searchTerm%')";
$result = mysqli_query($conn, $query);

$output = "";
$no = 1;
while ($row = mysqli_fetch_assoc($result)) {
    $rowColor = ($no % 2 == 0) ? '#9EDDFF' : '#F4F4F4';
    $output .= "<tr style='background-color: {$rowColor};'>";
    $output .= "<td style='text-align: center;'>{$no}</td>";
    $output .= "<td style='text-align: center;'>{$row['nama']}</td>";
    $output .= "<td style='text-align: center;'>{$row['judul']}</td>";
    $output .= "<td style='text-align: center;'>{$row['tanggal']}</td>";
    $output .= "<td style='text-align: center;'>{$row['tenggat']}</td>";
    $output .= "<td style='text-align: center;'>{$row['kembali']}</td>";
    $output .= "<td style='text-align: center;'>{$row['jumlah']}</td>";

    // Set color for status
    $statusColor = '';
    if ($row['status'] == 'tepat waktu') {
        $statusColor = 'green';
    } elseif ($row['status'] == 'terlambat') {
        $statusColor = 'red';
    }

    $output .= "<td style='text-align: center; color: $statusColor;'>{$row['status']}</td>";
    $output .= "</td>";
    $output .= "</tr>";
    $no++;
}

echo $output;
mysqli_close($conn);
?>
