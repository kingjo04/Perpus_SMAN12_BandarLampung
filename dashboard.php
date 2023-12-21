<?php
session_set_cookie_params(2); // Atur waktu hidup session menjadi 7200 detik (2 jam)
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


include '../backend/config.php';

// Query to get the sum of 'jumlah' column from 'buku' table
$sql = "SELECT SUM(jumlah) as total_stock FROM buku";
$result = $conn->query($sql);

// Check if the query was successful
if ($result) {
    // Fetch the result as an associative array
    $row = $result->fetch_assoc();

    // Get the total stock from the result
    $totalStock = $row['total_stock'];
} else {
    // Handle the case where the query was not successful
    $totalStock = "N/A";
}

// Query to get the number of rows in the 'siswa' table
$sqlSiswa = "SELECT COUNT(*) as total_siswa FROM siswa";
$resultSiswa = $conn->query($sqlSiswa);

// Check if the query was successful
if ($resultSiswa) {
    // Fetch the result as an associative array
    $rowSiswa = $resultSiswa->fetch_assoc();

    // Get the total number of rows from the result
    $totalSiswa = $rowSiswa['total_siswa'];
} else {
    // Handle the case where the query was not successful
    $totalSiswa = "N/A";
}

// Query to get the sum of 'jumlah' column from 'peminjaman' table
$sqlPeminjaman = "SELECT SUM(jumlah) as total_peminjaman FROM peminjaman WHERE status = 'menunggu divalidasi'";
$resultPeminjaman = $conn->query($sqlPeminjaman);

// Check if the query was successful
if ($resultPeminjaman) {
    // Fetch the result as an associative array
    $rowPeminjaman = $resultPeminjaman->fetch_assoc();

    // Get the total peminjaman from the result
    $totalPeminjaman = $rowPeminjaman['total_peminjaman'];
} else {
    // Handle the case where the query was not successful
    $totalPeminjaman = "N/A";
}

// Query to get the sum of 'jumlah' column from 'pengembalian' table
$sqlPengembalian = "SELECT SUM(jumlah) as total_pengembalian FROM pengembalian WHERE status = 'belum dikembalikan'";
$resultPengembalian = $conn->query($sqlPengembalian);

// Check if the query was successful
if ($resultPengembalian) {
    // Fetch the result as an associative array
    $rowPengembalian = $resultPengembalian->fetch_assoc();

    // Get the total pengembalian from the result
    $totalPengembalian = $rowPengembalian['total_pengembalian'];
} else {
    // Handle the case where the query was not successful
    $totalPengembalian = "N/A";
}

// Query to get the sum of 'total' column from 'denda' table where 'status' is 'Belum Lunas'
$sqlDenda = "SELECT SUM(total) as total_denda FROM denda WHERE status = 'Belum Lunas'";
$resultDenda = $conn->query($sqlDenda);

// Check if the query was successful
if ($resultDenda) {
    // Fetch the result as an associative array
    $rowDenda = $resultDenda->fetch_assoc();

    // Get the total denda from the result
    $totalDenda = $rowDenda['total_denda'];
} else {
    // Handle the case where the query was not successful
    $totalDenda = "N/A";
}


// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
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
                                    <a href="dashboard.php">Dashboard</a>
                                </div>';
    ?>

        <!-- Include the header.php file -->
        <?php include 'header.php'; ?>

        <!-- Konten -->
        <div class="content" id="dashboardContent">

            <h1 style="font-size: 15px; position: absolute; left: 350px; top: 110px;">
                Dashboard >
            </h1>
            <!-- Stock Box -->

            <!-- Stock Box -->
            <table
                style="position: absolute; left: 350px; top: 205px; width: 409px; height: 149px; border-radius: 20px; background-color: #9EDDFF;">
                <thead>
                    <tr>
                        <td
                            style="text-align: center; padding: 10px; font-size: 22px; font-weight: bold; justify-content:center">
                            Stock Buku
                        </td>
                        <td style="text-align: center; padding: 10px; font-size: 22px; font-weight: bold;">
                            <img src="assets/dashboard/bukuhitam.png" alt="buku hitam" style="scale: 0.5;">
                            <?php echo $totalStock; ?>
                        </td>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>


            <!-- Anggota Box -->

            <table
                style="position: absolute; left : 800px; top: 205px; width: 409px; height: 149px; border-radius: 20px; background-color: #9EDDFF;">
                <thead>
                    <td
                        style="text-align: center; padding: 10px; font-size : 22px; font-weight:bold; justify-content:center">
                        Anggota
                    </td>
                    <td style="text-align: center; padding: 10px; font-size : 22px; font-weight:bold;"> <img
                            src="assets/dashboard/orghitam.png" alt="org hitam" style="scale: 1;">
                        <?php echo $totalSiswa; ?>
                    </td>
                </thead>
            </table>

            <!-- Laporan Box -->
            <h1 style="font-size: 22px; position: absolute; left: 350px; top: 430px;">
                Laporan >
            </h1>

            <!-- Laporan Box -->
            <table
                style="position: absolute; left: 350px; top: 490px; width: 858px; height: 149px; border-radius: 20px; background-color: #9EDDFF;">
                <h3 style=" position: absolute; left: 60px; top: 28px; ">
                    <thead>
                        <tr>
                            <!-- Kolom " no" -->
                            <td style="text-align: center; padding: 10px; font-size : 22px; font-weight:bold;">
                                Peminjaman</td>

                            <!-- Kolom "sampul" -->
                            <td style="text-align: center; padding: 10px; font-size : 22px; font-weight:bold;">
                                Pengembalian</td>

                            <!-- Kolom "judul" -->
                            <td style="text-align: center; padding: 10px; font-size : 22px; font-weight:bold;">Denda
                            </td>
                        </tr>
                    </thead>
                    <tbody style="text-align:center;">
                        <td style="font-size:32px; font-weight:bold;"><?php echo $totalPeminjaman; ?></td>
                        <td style="font-size:32px; font-weight:bold;"><?php echo $totalPengembalian; ?></td>
                        <td style="font-size:32px; font-weight:bold;">
                            <?php echo 'Rp.' . number_format($totalDenda, 0, ',', '.'); ?></td>

                    </tbody>
            </table>

            <!-- Popup -->
            <?php include 'popup.php'; ?>
        </div>

        <script src=" https://code.jquery.com/jquery-3.2.1.slim.min.js "></script>
        <script src=" https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js "></script>
        <script src=" https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js "></script>
        <script src=" script/global.js">
        </script>


</body>

</html>