<?php

session_start();

// Cek jika pengguna tidak login atau bukan admin
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
    header('Location: login.php');
    exit();
}
date_default_timezone_set('Asia/Jakarta');
include 'koneksi.php';

if (isset($_POST['id_denda'])) {
    $idDenda = mysqli_real_escape_string($conn, $_POST['id_denda']);

    // Query untuk mengupdate status denda menjadi "lunas"
    $queryUpdateDenda = "UPDATE denda SET status = 'lunas' WHERE id = '$idDenda'";

    // Eksekusi query UPDATE pada tabel denda
    if (mysqli_query($conn, $queryUpdateDenda)) {
        // Mendapatkan detail dari tabel denda
        $queryGetDetails = "SELECT nama, judul, start, tenggat FROM denda WHERE id = '$idDenda'";
        $resultDetails = mysqli_query($conn, $queryGetDetails);

        if ($resultDetails) {
            $rowDetails = mysqli_fetch_assoc($resultDetails);
            $username = $rowDetails['nama'];
            $judul = $rowDetails['judul'];
            $start = $rowDetails['start'];
            $tenggat = $rowDetails['tenggat'];

            // Query untuk mengupdate status di tabel pengembalian
            $queryUpdatePengembalian = "UPDATE pengembalian SET status = 'dikembalikan' 
                                        WHERE nama = '$username' AND judul = '$judul' AND tanggal = '$start' AND tenggat = '$tenggat'";

            if (mysqli_query($conn, $queryUpdatePengembalian)) {
                // Query untuk mengupdate status di tabel peminjaman
                $queryUpdatePeminjaman = "UPDATE peminjaman SET status_pengembalian = 'dikembalikan' 
                                          WHERE nama = '$username' AND judul = '$judul' AND tanggal = '$start' AND tenggat = '$tenggat'";

                if (mysqli_query($conn, $queryUpdatePeminjaman)) {
                    // Query untuk mendapatkan jumlah buku yang dikembalikan
                    $queryGetJumlah = "SELECT jumlah FROM pengembalian 
                                       WHERE nama = '$username' AND judul = '$judul' AND tanggal = '$start' AND tenggat = '$tenggat'";
                    $resultJumlah = mysqli_query($conn, $queryGetJumlah);

                    if ($resultJumlah) {
                        $rowJumlah = mysqli_fetch_assoc($resultJumlah);
                        $jumlahDikembalikan = $rowJumlah['jumlah'];

                        // Query untuk mengupdate stok buku di tabel buku
                        $queryUpdateStokBuku = "UPDATE buku SET jumlah = jumlah + $jumlahDikembalikan WHERE judul_buku = '$judul'";

                        if (mysqli_query($conn, $queryUpdateStokBuku)) {
                            // Query untuk memasukkan data ke tabel riwayat_kembali
                            $queryInsertRiwayat = "INSERT INTO riwayat_kembali (nama, judul, tanggal, tenggat, kembali, jumlah, status)
                                                   VALUES ('$username', '$judul', '$start', '$tenggat', NOW(), $jumlahDikembalikan, 'terlambat')";

                            // Eksekusi query INSERT pada tabel riwayat_kembali
                            if (mysqli_query($conn, $queryInsertRiwayat)) {
                                header('Location: denda.php');
                                exit();
                            } else {
                                echo "Error saat memasukkan data ke tabel riwayat_kembali: " . mysqli_error($conn);
                            }
                        } else {
                            echo "Error saat mengupdate stok buku: " . mysqli_error($conn);
                        }
                    } else {
                        echo "Error saat mendapatkan jumlah buku: " . mysqli_error($conn);
                    }
                } else {
                    echo "Error saat mengupdate status di tabel peminjaman: " . mysqli_error($conn);
                }
            } else {
                echo "Error saat mengupdate status di tabel pengembalian: " . mysqli_error($conn);
            }
        } else {
            echo "Error saat mengambil detail lengkap: " . mysqli_error($conn);
        }
    } else {
        echo "Error saat mengupdate status denda: " . mysqli_error($conn);
    }
} else {
    // Jika id_denda tidak ada, redirect ke halaman denda
    header('Location: denda.php');
    exit();
}

// Tutup koneksi database
mysqli_close($conn);
?>