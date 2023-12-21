<?php
session_start();

// Cek jika pengguna tidak login atau bukan admin
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
    header('Location: login.php');
    exit();
}
include 'koneksi.php';

// Ubah query sesuai kebutuhan
$queryDenda = "SELECT * FROM denda WHERE status = 'belum lunas'";
$resultDenda = mysqli_query($conn, $queryDenda);

$count = 0;



while ($rowDenda = mysqli_fetch_assoc($resultDenda)) {
    $count++;
    $background_color = $count % 2 === 0 ? '#9EDDFF' : '#F4F4F4';

        // Tentukan warna berdasarkan status denda
    $status_color = ($rowDenda['status'] == 'lunas') ? '#00ff00' : '#ff0000'; // Hijau untuk lunas, Merah untuk belum lunas

    echo "<tr style='background-color: $background_color;'>";
    echo "<td style='text-align: center; padding: 10px;'>$count</td>";
    echo "<td style='text-align: center;'>{$rowDenda['nama']}</td>";
    echo "<td style='text-align: center;'>{$rowDenda['judul']}</td>";
    echo "<td style='text-align: center;'>{$rowDenda['start']}</td>";
    echo "<td style='text-align: center;'>{$rowDenda['tenggat']}</td>";
    echo "<td style='text-align: center;'>{$rowDenda['total']}</td>";
    
    // Gunakan warna yang telah ditentukan untuk kolom status
    echo "<td style='text-align: center; color: $status_color;'>{$rowDenda['status']}</td>";


        // You can add the actions column as needed
echo "<td style='text-align: center; padding: 10px;'>
        <form action='update_status_denda.php' method='post'>
            <input type='hidden' name='id_denda' value='{$rowDenda['id']}'>
            <button type='submit' class='btn btn-success btn-sm'>Validasi</button>
        </form>
      </td>";

        echo "</tr>";
    }

mysqli_close($conn);
?>
