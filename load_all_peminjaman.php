<?php
session_start();

// Cek jika pengguna tidak login atau bukan admin
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
    header('Location: login.php');
    exit();
}
include 'koneksi.php';

// Ubah query sesuai kebutuhan
$querypeminjaman = "SELECT * FROM peminjaman WHERE status = 'menunggu divalidasi'";
$resultpeminjaman = mysqli_query($conn, $querypeminjaman);

// Inisialisasi nomor urut
$no = 1;

while ($rowpeminjaman = mysqli_fetch_assoc($resultpeminjaman)) {
    $rowColor = $no % 2 === 0 ? '#9EDDFF' : '#F4F4F4';

    echo "<tr style='background-color: $rowColor;'>
            <td style='text-align: center;'>$no</td>
            <td style='text-align: center;'>{$rowpeminjaman['nama']}</td>
            <td style='text-align: center;'>{$rowpeminjaman['nomor_buku']}</td>
            <td style='text-align: center;'>{$rowpeminjaman['judul']}</td>
            <td style='text-align: center;'>{$rowpeminjaman['tanggal']}</td>
            <td style='text-align: center;'>{$rowpeminjaman['tenggat']}</td>
            <td style='text-align: center;'>{$rowpeminjaman['jumlah']}</td>
            <td style='text-align: center;'>
                <div style='display: flex; justify-content: center; gap: 10px;'>
                    <form id='validasiForm{$rowPeminjaman['id']}' action='proses_validasi_peminjaman.php' method='post'>
                        <input type='hidden' name='id' value='{$rowPeminjaman['id']}'>
                        <button type='button' class='btn btn-success btn-sm' onclick='submitForm({$rowPeminjaman['id']}, \"validasiForm\")'>Validasi</button>
                    </form>
                    <form id='hapusForm{$rowPeminjaman['id']}' action='proses_update_status_peminjaman.php' method='post'>
                        <input type='hidden' name='id' value='{$rowPeminjaman['id']}'>
                        <button type='button' class='btn btn-danger btn-sm' onclick='submitForm({$rowPeminjaman['id']}, \"hapusForm\")'>Tolak</button>
                    </form>
                </div>
              </td>
          </tr>";

    $no++; // Increment nomor urut
}

mysqli_close($conn);
?>
