<?php
session_start();

// Set batas waktu session (2 jam)
if (!isset($_SESSION['time_created'])) {
    $_SESSION['time_created'] = time();
} else {
    // Periksa apakah session telah melewati 2 jam
    if (time() - $_SESSION['time_created'] > 3600) {
        session_unset(); // Bersihkan variabel session
        session_destroy(); // Hancurkan session
        header("Location: ../admin/login.php"); // Arahkan ke halaman login
        exit;
    }
}

// Cek jika pengguna tidak login atau bukan admin
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
    header('Location: login.php');
    exit();
}

include 'koneksi.php';

// Fetch data from the "riwayat_denda" table where the status is 'lunas'
$queryRiwayatdenda = "SELECT * FROM denda WHERE status = 'lunas'";
$resultRiwayatdenda = mysqli_query($conn, $queryRiwayatdenda);

// Check if the query was successful
if (!$resultRiwayatdenda) {
    die("Query failed: " . mysqli_error($conn));
}


// Close the database connection
mysqli_close($conn);
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Denda</title>
    <link rel="icon" href="favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="style/global.css">
</head>

<body>
    <div class="container-fluid">
        <!-- Sidebar -->
        <?php include 'sidebar.php'; ?>

        <!-- Header -->
        <?php
        // Set a variable to be used in header.php
        $customHeaderContent = '<div class="halamansaatini">
                                    <a href="denda.php">Denda</a>
                                </div>';
    ?>

        <!-- Include the header.php file -->
        <?php include 'header.php'; ?>

        <!-- Konten -->
    </div>

    <div class="content" id="peminjamanContent">
        <h1 style=" font-size: 15px; position: absolute; left: 350px;  top: 110px;">
            Dashboard > Denda > Riwayat Denda
        </h1>

        <h2 style="font-size: 15px; position: absolute; left: 350px; top: 240px;">
            Riwayat Denda
        </h2>

        <form id="carinamaform" style="position: absolute; left: 881px; top: 184px;">
            <label for="search" style="position: relative; display: inline-block;">
                <input type="search" name="search" id="search" placeholder="Search ..."
                    style="width: 300px; height: 32px; border-radius: 20px; box-shadow: inset 0px 4px 4px rgba(0, 0, 0, 0.25); padding-left: 15px; /* Add padding to the left */">
                <img src="assets/global/iconsearch.png" alt="Search Icon"
                    style="position: absolute; left: 210px; top: 50%; transform: translateY(-50%) scale(0.2); cursor:pointer">
            </label>
        </form>

        <table style="position: absolute;
              background-color: #9EDDFF;
              border-collapse: collapse;
              border-top-left-radius: 20px;
              border-top-right-radius: 20px;
              width: 838px;
              left: 350px;
              top: 280px;
              
              font-size: 20px;">
            <thead>
                <tr style="font-weight: bold;">
                    <!-- Kolom "No" -->
                    <td style="text-align: center; padding: 10px;">No</td>

                    <!-- Kolom "Nama" -->
                    <td style="text-align: center; padding: 10px;">Nama</td>
                    
                    <td style="text-align: center; padding: 10px;">Judul</td>
                    
                    <td style="text-align: center; padding: 10px;">Start</td>
                    
                    <td style="text-align: center; padding: 10px;">Tenggat</td>

                    <!-- Kolom "jumlah" -->
                    <td style="text-align: center; padding: 10px;">Total Denda</td>
                    
                    <td style="text-align: center; padding: 10px;">Status</td>
                </tr>
            </thead>
            <tbody>
                <?php
$no = 1;
while ($rowRiwayatdenda = mysqli_fetch_assoc($resultRiwayatdenda)) {
    $rowColor = ($no % 2 == 0) ? '#9EDDFF' : '#F4F4F4';
    $statusColor = ($rowRiwayatdenda['status'] == 'lunas') ? '#00ff00' : '#ff0000'; // Hijau untuk lunas, Merah untuk belum lunas

    echo "<tr style='background-color: {$rowColor};'>";
    echo "<td style='text-align: center;'>{$no}</td>";
    echo "<td style='text-align: center;'>{$rowRiwayatdenda['nama']}</td>";
    echo "<td style='text-align: center;'>{$rowRiwayatdenda['judul']}</td>";
    echo "<td style='text-align: center;'>{$rowRiwayatdenda['start']}</td>";
    echo "<td style='text-align: center;'>{$rowRiwayatdenda['tenggat']}</td>";
    echo "<td style='text-align: center;'>{$rowRiwayatdenda['total']}</td>";

    // Menggunakan warna yang telah ditentukan untuk kolom status
    echo "<td style='text-align: center; color: {$statusColor};'>{$rowRiwayatdenda['status']}</td>";
    
    echo "</tr>";
    $no++;
}
?>

            </tbody>
        </table>
    </div>



    <!-- Popup -->
    <?php include 'popup.php'; ?>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src=" https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js "></script>
    <script src=" https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js "></script>
    <script src=" script/global.js">
    </script>
    
        <script>
$(document).ready(function() {
    $('#search').keyup(function() {
        var searchTerm = $(this).val();
        if (searchTerm != '') {
        $.ajax({
            url: 'search_riwayatdenda.php', // Pastikan URL ini sesuai
            type: 'GET',
            data: {term: searchTerm},
            success: function(response) {
                $('tbody').html(response);
            }
        });
    }else {
            // Jika search term kosong, muat semua data
            $.ajax({
                url: 'load_all_riwayatdenda.php', // Anda bisa membuat script PHP terpisah atau gunakan script yang sama dengan kondisi
                type: 'GET',
                success: function(response) {
                    $('tbody').html(response);
                }
            });
        }
    });
});

</script>

</body>

</html>