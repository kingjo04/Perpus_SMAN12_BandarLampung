<?php

session_start();

// Cek jika pengguna tidak login atau bukan admin
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
    header('Location: login.php');
    exit();
}

// search_riwayat_denda.php
include 'koneksi.php';

$searchTerm = mysqli_real_escape_string($conn, $_GET['term']);

$query = "SELECT * FROM denda WHERE (nama LIKE '%$searchTerm%' OR judul LIKE '%$searchTerm%') AND status = 'lunas'" ;
$result = mysqli_query($conn, $query);

$output = "";
$no = 1;
while ($row = mysqli_fetch_assoc($result)) {
    $rowColor = ($no % 2 == 0) ? '#9EDDFF' : '#F4F4F4';

    // Menentukan warna teks untuk status
    $statusColor = ($row['status'] == 'lunas') ? '#00ff00' : '#ff0000'; // Hijau untuk lunas, Merah untuk belum lunas

    $output .= "<tr style='background-color: {$rowColor};'>";
    $output .= "<td style='text-align: center;'>{$no}</td>";
    $output .= "<td style='text-align: center;'>{$row['nama']}</td>";
    $output .= "<td style='text-align: center;'>{$row['judul']}</td>";
    $output .= "<td style='text-align: center;'>{$row['start']}</td>";
    $output .= "<td style='text-align: center;'>{$row['tenggat']}</td>";
    $output .= "<td style='text-align: center;'>{$row['total']}</td>";

    // Menggunakan warna yang telah ditentukan untuk kolom status
    $output .= "<td style='text-align: center; color: {$statusColor};'>{$row['status']}</td>";
    
    $output .= "</tr>";
    $no++;
}



echo $output;
mysqli_close($conn);



?>