<?php
session_start();

// Cek jika pengguna tidak login atau bukan admin
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
    header('Location: login.php');
    exit();
}

// search_peminjaman.php
include 'koneksi.php';

$searchTerm = mysqli_real_escape_string($conn, $_GET['term']);

$query = "SELECT * FROM peminjaman WHERE (nama LIKE '%$searchTerm%' OR judul LIKE '%$searchTerm%') AND status = 'menunggu divalidasi'";
$result = mysqli_query($conn, $query);

$output = "";
$no = 1;
while ($row = mysqli_fetch_assoc($result)) {
    $rowColor = ($no % 2 == 0) ? '#9EDDFF' : '#F4F4F4';

    $output .= "<tr style='background-color: {$rowColor};'>";
    $output .= "<td style='text-align: center;'>{$no}</td>";
    $output .= "<td style='text-align: center;'>{$row['nama']}</td>";
    $output .= "<td style='text-align: center;'>{$row['nomor_buku']}</td>";
    $output .= "<td style='text-align: center;'>{$row['judul']}</td>";
    $output .= "<td style='text-align: center;'>{$row['tanggal']}</td>";
    $output .= "<td style='text-align: center;'>{$row['tenggat']}</td>";
    $output .= "<td style='text-align: center;'>{$row['jumlah']}</td>";
    $output .= "<td style='text-align: center;'>
                <div style='display: flex; justify-content: center; gap: 10px;'>
                    <form id='validasiForm{$row['id']}' action='proses_validasi_peminjaman.php' method='post'>
                        <input type='hidden' name='id' value='{$row['id']}'>
                        <input type='button' class='btn btn-success btn-sm' onclick='submitForm({$row['id']}, \"validasiForm\")' value='Validasi'>
                    </form>
                    <form id='hapusForm{$row['id']}' action='proses_update_status_peminjaman.php' method='post'>
                        <input type='hidden' name='id' value='{$row['id']}'>
                        <input type='button' class='btn btn-danger btn-sm' onclick='submitForm({$row['id']}, \"hapusForm\")' value='Tolak'>
                    </form>
                </div>
            </td>";
$output .= "</tr>";


    $no++;
}

echo $output;
mysqli_close($conn);

?>