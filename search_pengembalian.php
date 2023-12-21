<?php
session_start();

// Cek jika pengguna tidak login atau bukan admin
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
    header('Location: login.php');
    exit();
}

// search_pengembalian.php
include 'koneksi.php';

$searchTerm = mysqli_real_escape_string($conn, $_GET['term']);

$query = "SELECT * FROM pengembalian WHERE (nama LIKE '%$searchTerm%' OR judul LIKE '%$searchTerm%') AND status = 'belum dikembalikan'";
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
    $output .= "<td style='text-align: center;'>{$row['jumlah']}</td>";
    $output .= "<td style='text-align: center;'>"; // Kolom Aksi
    $output .= "<form action='proses_validasi_pengembalian.php' method='post'>";
    $output .= "<input type='hidden' name='id_pengembalian' value='{$row['id']}'>";
    $output .= "<button type='submit' class='btn btn-success btn-sm'>Validasi</button>";
    
    $output .= "</form>";
    $output .= "</td>";
    $output .= "</tr>";
    $no++;
}

echo $output;
mysqli_close($conn);



?>