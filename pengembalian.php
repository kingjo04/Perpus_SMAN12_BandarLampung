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

// Fetch data from the "peminjaman" table
$queryPengembalian = "SELECT * FROM pengembalian WHERE status = 'belum dikembalikan'";
$resultPengembalian = mysqli_query($conn, $queryPengembalian);


// Check if the query was successful
if (!$resultPengembalian) {
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
    <title>Pengembalian</title>
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
                                    <a href="pengembalian.php">Pengembalian</a>
                                </div>';
    ?>

        <!-- Include the header.php file -->
        <?php include 'header.php'; ?>

        <!-- Konten -->

    </div>

    <div class="content" id="pengembalianContent">
        <h1 style=" font-size: 15px; position: absolute; left: 350px;  top: 110px;">
            Dashboard > Pengembalian
        </h1>

        <h2 style="font-size: 15px; position: absolute; left: 350px; top: 240px;">
            Validasi Pengembalian
        </h2>


        <form id="carinamaform" style="position: absolute; left: 881px; top: 184px;">
            <label for="search" style="position: relative; display: inline-block;">
                <input type="search" name="search" id="search" placeholder="Search ..."
                    style="width: 300px; height: 32px; border-radius: 20px; box-shadow: inset 0px 4px 4px rgba(0, 0, 0, 0.25); padding-left: 15px; /* Add padding to the left */">
                <img src="assets/global/iconsearch.png" alt="Search Icon"
                    style="position: absolute; left: 210px; top: 50%; transform: translateY(-50%) scale(0.2); cursor:pointer">
            </label>
        </form>

<button style="position: absolute; 
               width: 130px;
               height: 32px;
               border-radius: 20px;
               left: 1040px;
               top: 240px;
               background-color: #6499E9; /* Warna awal */
               cursor: pointer;
               color: white; /* Set text color to white */
               transition: background-color 0.3s; /* Add transition for smooth color change */
               display: flex;
               font-size: 12px;
               font-weight: bold;
               align-items: center;"
       onmouseover="this.style.backgroundColor='rgba(100, 153, 233, 0.8)'" 
       onmouseout="this.style.backgroundColor='#6499E9'"
       onclick="window.location.href='riwayatkembali.php';">
    <img src="assets/global/icon history.png" alt="Tambah Buku"
         style="width: 18px; height: 27px; margin-right: 5px;">
    <span>Lihat Riwayat</span>
</button>






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
                    <td style="text-align: center; padding: 10px; width: 50px;">No</td>

                    <!-- Kolom "Nama" -->
                    <td style="text-align: center; padding: 10px; width: 150px;">Nama</td>

                    <!-- Kolom "Judul" -->
                    <td style="text-align: center; padding: 10px; width: 150px;">Judul</td>

                    <!-- Kolom "Tanggal" -->
                    <td style="text-align: center; padding: 10px; width: 120px;">Tanggal</td>

                    <!-- Kolom "Tenggat" -->
                    <td style="text-align: center; padding: 10px; width: 120px;">Tenggat</td>

                    <!-- Kolom "jumlah" -->
                    <td style="text-align: center; padding: 10px; width: 100px;">Jumlah</td>

                    <!-- Kolom "aksi" -->
                    <td
                        style="text-align: center; padding: 10px; width: calc(838px - 50px - 150px - 150px - 120px - 120px - 100px);">
                        Aksi
                    </td>
                </tr>
            </thead>
            <tbody>
                <?php
        $no = 1; // Initialize a variable to store the sequential number
        while ($rowPengembalian = mysqli_fetch_assoc($resultPengembalian)) {
            $rowColor = ($no % 2 == 0) ? '#9EDDFF' : '#F4F4F4'; // Set background color based on row number
            echo "<tr style='background-color: {$rowColor};'>";
            echo "<td style='text-align: center;'>{$no}</td>"; // Display the sequential number
            echo "<td style='text-align: center;'>{$rowPengembalian['nama']}</td>";
            echo "<td style='text-align: center;'>{$rowPengembalian['judul']}</td>";
            echo "<td style='text-align: center;'>{$rowPengembalian['tanggal']}</td>";
            echo "<td style='text-align: center;'>{$rowPengembalian['tenggat']}</td>";
            echo "<td style='text-align: center;'>{$rowPengembalian['jumlah']}</td>";
            // You can add the actions column as needed
        echo "<td style='text-align: center; padding: 10px;'>
    <form action='Validasi_Pengembalian.php' method='post' class='validasi-form'>
        <input type='hidden' name='id_pengembalian' value='{$rowPengembalian['id']}'>
        <button type='submit' class='btn btn-success btn-sm validasi-btn'>Validasi</button>
    </form>
</td>";

            echo "</tr>";
            $no++; // Increment the sequential number for the next row
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
            url: 'search_pengembalian.php', // Sesuaikan dengan nama file PHP Anda
            type: 'GET',
            data: {term: searchTerm},
            success: function(response) {
                $('tbody').html(response);
                }
            });
        } else {
            // Jika search term kosong, muat semua data
            $.ajax({
                url: 'load_all_pengembalian.php', // Anda bisa membuat script PHP terpisah atau gunakan script yang sama dengan kondisi
                type: 'GET',
                success: function(response) {
                    $('tbody').html(response);
                }
            });
        }
    });
});
</script>

<script>
    // Event listener untuk tombol validasi
    document.addEventListener('DOMContentLoaded', function() {
        var validasiButtons = document.querySelectorAll('.validasi-btn');
        validasiButtons.forEach(function(btn) {
            btn.addEventListener('click', function(event) {
                var konfirmasi = confirm("Apakah Anda yakin ingin validasi pengembalian ini?");
                if (!konfirmasi) {
                    event.preventDefault(); // Mencegah form dari submit jika pengguna memilih "Cancel"
                }
            });
        });
    });
</script>



</body>

</html>