<?php
date_default_timezone_set('Asia/Jakarta');
include 'koneksi.php';

$queryDenda = "SELECT * FROM denda WHERE status = 'belum lunas'";
$resultDenda = mysqli_query($conn, $queryDenda);

if (!$resultDenda) {
    error_log("Query failed: " . mysqli_error($conn)); // Log error instead of dying
    exit();
}

$dendaPerPengguna = [];

while ($rowDenda = mysqli_fetch_assoc($resultDenda)) {
    $tanggalTenggat = new DateTime($rowDenda['tenggat']);
    $tanggalSekarang = new DateTime();

    if ($tanggalSekarang > $tanggalTenggat) {
        $hariTerlambat = $tanggalSekarang->diff($tanggalTenggat)->days;
        $totalDenda = $hariTerlambat * 1000;

        if (!isset($dendaPerPengguna[$rowDenda['no_hp']]['total_denda'])) {
            $dendaPerPengguna[$rowDenda['no_hp']]['total_denda'] = 0;
        }

        $dendaPerPengguna[$rowDenda['no_hp']]['nama'] = $rowDenda['nama'];
        $dendaPerPengguna[$rowDenda['no_hp']]['total_denda'] += $totalDenda;
        $dendaPerPengguna[$rowDenda['no_hp']]['buku'][] = [
            'judul' => $rowDenda['judul'],
            'start' => $rowDenda['start'],
            'tenggat' => $rowDenda['tenggat'],
            'hari_terlambat' => $hariTerlambat,
            'total' => $totalDenda
        ];
    }
}

// Fungsi untuk mengecek apakah sudah mengirim pesan hari ini
function sudahDikirim($conn, $no_hp) {
    $tanggalSekarang = date('Y-m-d');
    $query = "SELECT * FROM catatan_pengiriman WHERE no_hp = '$no_hp' AND tanggal_pengiriman = '$tanggalSekarang'";
    $result = mysqli_query($conn, $query);
    return mysqli_num_rows($result) > 0;
}

// Fungsi untuk mencatat pengiriman pesan
function catatPengiriman($conn, $no_hp) {
    $tanggalSekarang = new DateTime();
    $tanggalSekarangFormatted = $tanggalSekarang->format('Y-m-d'); // Mengubah ke format 'YYYY-MM-DD'

    $query = "INSERT INTO catatan_pengiriman (no_hp, tanggal_pengiriman) VALUES ('$no_hp', '$tanggalSekarangFormatted')";
    mysqli_query($conn, $query);
}


foreach ($dendaPerPengguna as $no_hp => $data) {
    if (!sudahDikirim($conn, $no_hp)) {
        $message = "{$data['nama']}, \n \nAnda memiliki total denda sebesar *Rp. {$data['total_denda']}* untuk buku-buku berikut:\n";
        
        foreach ($data['buku'] as $buku) {
            $message .= "\n*Judul Buku :* {$buku['judul']}\n*Tanggal Pinjam :* {$buku['start']}\n*Tenggat :* {$buku['tenggat']}\n*Hari Terlambat :* {$buku['hari_terlambat']} hari\n*Denda :* Rp. {$buku['total']}\n";
        }
         $message .= "\nAyo kembalikan buku ke perpustakaan karena denda akan berjalan tiap harinya:)";

        // Initialize cURL session for Fonnte API
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.fonnte.com/send',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => array('target' => $no_hp, 'message' => $message),
            CURLOPT_HTTPHEADER => array(
                'Authorization: jamZEby7jV@e3724Ii7q'
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);

        // Catat pengiriman pesan
        catatPengiriman($conn, $no_hp);
    }
}

mysqli_close($conn);
?>
