<?php
session_start();

// Cek jika pengguna tidak login atau bukan admin
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
    header('Location: login.php');
    exit();
}
// search_buku.php
include 'koneksi.php';

$searchTerm = mysqli_real_escape_string($conn, $_GET['term']);

$query = "SELECT * FROM buku WHERE judul_buku LIKE '%$searchTerm%'";
$result = mysqli_query($conn, $query);

$output = "";
$count = 0;
while ($row = mysqli_fetch_assoc($result)) {
    $count++;
    $background_color = $count % 2 === 0 ? '#9EDDFF' : '#F4F4F4';

    $output .= "<tr style='background-color: $background_color;'>
                    <td style='text-align: center; padding: 10px;'>$count</td>
                    <td style='text-align: center; padding: 10px;'><img src='../user/uploads/{$row['sampul']}' alt='Sampul Buku' style='width: 50px; border: none;'></td>
                    <td style='text-align: center; padding: 10px;'>{$row['judul_buku']}</td>
                    <td style='text-align: center; padding: 10px;'>{$row['nomor_buku']}</td>
                    <td style='text-align: center; padding: 10px;'>{$row['kategori']}</td>
                    <td style='text-align: center; padding: 10px;'>{$row['jumlah']}</td>
                    <td style='text-align: center; padding: 10px;'>
                        <img src='assets/dashboard/iconedit.png' alt='Edit' style='cursor:pointer; transform: scale(0.7);' onclick='openEditBukuPopup(\"{$row['id']}\", \"{$row['nomor_buku']}\", \"{$row['judul_buku']}\", \"{$row['penerbit']}\", \"{$row['pengarang']}\", \"{$row['kategori']}\", \"{$row['tahun']}\", \"{$row['jumlah']}\", \"{$row['sampul']}\")'>
                        <a href='delete_buku.php?id={$row['id']}' onclick='return confirm(\"Apakah Anda yakin ingin menghapus buku ini?\")'>
                            <img src='assets/global/iconhapus.png' alt='Hapus' style='cursor:pointer; transform: scale(0.7);'>
                        </a>
                    </td>
                </tr>";
}

echo $output;
mysqli_close($conn);


?>