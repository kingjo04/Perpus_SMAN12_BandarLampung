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

// Fetch data from the "denda" table
$queryDenda = "SELECT * FROM denda WHERE status = 'belum lunas'";
$resultDenda = mysqli_query($conn, $queryDenda);

// Check if the query was successful
if (!$resultDenda) {
    die("Query failed: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Denda</title>
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

    <div class="content" id="dendaContent">
        <h1 style=" font-size: 15px; position: absolute; left: 350px;  top: 110px;">
            Dashboard > Denda
        </h1>

        <h2 style="font-size: 15px; position: absolute; left: 350px; top: 240px;">
            Denda Keterlambatan
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
               align-items: center;" onmouseover="this.style.backgroundColor='rgba(100, 153, 233, 0.8)'"
            onmouseout="this.style.backgroundColor='#6499E9'" onclick="window.location.href='riwayatdenda.php';"
            onmouseover="this.style.backgroundColor='#ff0000'" onmouseout="this.style.backgroundColor='#6499E9'"
            onclick="window.location.href='riwayatdenda.php';">
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
                    <td style="text-align: center; padding: 10px;">No</td>

                    <!-- Kolom "Nama" -->
                    <td style="text-align: center; padding: 10px;">Nama</td>

                    <!-- Kolom "Judul" -->
                    <td style="text-align: center; padding: 10px;">Judul</td>

                    <!-- Kolom "Start" -->
                    <td style="text-align: center; padding: 10px;">Start</td>

                    <!-- Kolom "Tenggat" -->
                    <td style="text-align: center; padding: 10px;">Tenggat</td>

                    <!-- Kolom "Total" -->
                    <td style="text-align: center; padding: 10px;">Total</td>

                    <!-- Kolom "Status" -->
                    <td style="text-align: center; padding: 10px;">Status</td>

                    <!-- Kolom "Aksi" -->
                    <td style="text-align: center; padding: 10px;">Aksi</td>

                </tr>
            </thead>
            <tbody>
                <?php
    $count = 0;

    // Inside the while loop
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
    <form action='update_status_denda.php' method='post' onsubmit='return confirmValidation();'>
        <input type='hidden' name='id_denda' value='{$rowDenda['id']}'>
        <button type='submit' class='btn btn-success btn-sm'>Validasi</button>
    </form>
</td>";

        echo "</tr>";
    }
    ?>
            </tbody>

        </table>
    </div>


    <!-- Popup -->
    <?php include 'popup.php'; ?>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <script src="script/global.js"></script>
    <script>
    function changeStatus(dendaId) {
        console.log("Changing status for Denda ID: " + dendaId);
        var selectedValue = $("#aksiSelect" + dendaId).val();
        console.log("Selected value: " + selectedValue);
        var selectedValue = $("#aksiSelect" + dendaId).val();

        // You can perform an AJAX request to update the status in the database
        // For simplicity, I'll use jQuery for the AJAX request


        // AJAX request
        $.ajax({
            type: "POST",
            url: "update_status.php", // Change this to the actual file handling the update
            data: {
                dendaId: dendaId,
                newStatus: selectedValue
            },
            success: function(response) {
                // Update the displayed status in the table
                alert("Denda ID: " + dendaId + ", New Status: " + selectedValue);
                location.reload(); // Refresh the page after updating
            },
            error: function(error) {
                console.error("Error updating status: ", error);
            }
        });
    }

    function executeAction(dendaId) {
        // You can perform additional actions here
        alert("Executing action for Denda ID: " + dendaId);
    }

    document.addEventListener("DOMContentLoaded", function() {
        // Cek apakah ada parameter 'status' di URL
        const urlParams = new URLSearchParams(window.location.search);
        const status = urlParams.get('status');

        // Tampilkan alert jika pesan WA berhasil dikirim
        if (status === 'success') {
            alert("Pengembalian Buku Divalidasi.");
        }
    });
    </script>
    
    <script>
$(document).ready(function() {
    
    $('#search').keyup(function() {
        var searchTerm = $(this).val();
        if (searchTerm != '') {
        $.ajax({
            url: 'search_denda.php',
            type: 'GET',
            data: {term: searchTerm},
            success: function(response) {
                $('tbody').html(response);
                }
            });
        } else {
            // Jika search term kosong, muat semua data
            $.ajax({
                url: 'load_all_denda.php', // Anda bisa membuat script PHP terpisah atau gunakan script yang sama dengan kondisi
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
    function confirmValidation() {
        return confirm("Apakah Anda yakin ingin validasi denda ini menjadi lunas?");
    }
</script>








</body>

</html>