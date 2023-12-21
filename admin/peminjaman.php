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

// Fetch data from the "peminjaman" table where status is 'menunggu divalidasi'
$queryPeminjaman = "SELECT * FROM peminjaman WHERE status = 'menunggu divalidasi'";
$resultPeminjaman = mysqli_query($conn, $queryPeminjaman);

// Check if the query was successful
if (!$resultPeminjaman) {
    die("Query failed: " . mysqli_error($conn));
}

// ... (sisanya sama dengan kode Anda) ...



// Check if the query was successful
if (!$resultPeminjaman) {
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
    <title>Peminjaman</title>
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
                                    <a href="peminjaman.php">Peminjaman</a>
                                </div>';
    ?>

        <!-- Include the header.php file -->
        <?php include 'header.php'; ?>

        <!-- Konten -->
    </div>

    <div class="content" id="peminjamanContent">
        <h1 style=" font-size: 15px; position: absolute; left: 350px;  top: 110px;">
            Dashboard > Peminjaman
        </h1>

        <h2 style="font-size: 15px; position: absolute; left: 350px; top: 240px;">
            Validasi Peminjaman
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
       onclick="window.location.href='riwayatpeminjaman.php';">
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

                    <!-- Kolom "Nomor" -->
                    <td style="text-align: center; padding: 10px;">Nomor</td>

                    <!-- Kolom "Judul" -->
                    <td style="text-align: center; padding: 10px;">Judul</td>

                    <!-- Kolom "Tanggal" -->
                    <td style="text-align: center; padding: 10px;">Tanggal</td>

                    <!-- Kolom "Tenggat" -->
                    <td style="text-align: center; padding: 10px;">Tenggat</td>

                    <!-- Kolom "jumlah" -->
                    <td style="text-align: center; padding: 10px;">Jumlah</td>

                    <!-- Kolom "aksi" -->
                    <td style="text-align: center; padding: 10px;">Aksi</td>
                </tr>
            </thead>
            <tbody>
                <?php
    $no = 1; // Initialize a variable to store the sequential number
    while ($rowPeminjaman = mysqli_fetch_assoc($resultPeminjaman)) {
        $rowColor = ($no % 2 == 0) ? '#9EDDFF' : '#F4F4F4'; // Set background color based on row number
        echo "<tr style='background-color: {$rowColor};'>";
        echo "<td style='text-align: center;'>{$no}</td>"; // Display the sequential number
        echo "<td style='text-align: center;'>{$rowPeminjaman['nama']}</td>";
        echo "<td style='text-align: center;'>{$rowPeminjaman['nomor_buku']}</td>";
        echo "<td style='text-align: center;'>{$rowPeminjaman['judul']}</td>";
        echo "<td style='text-align: center;'>{$rowPeminjaman['tanggal']}</td>";
        echo "<td style='text-align: center;'>{$rowPeminjaman['tenggat']}</td>";
        echo "<td style='text-align: center;'>{$rowPeminjaman['jumlah']}</td>";
        // You can add the actions column as needed
echo "<td style='text-align: center;'>
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
              </td>";
        echo "</tr>";

        $no++;
    }
    ?>
</tbody>


        </table>

    </div>

    </div>



    <!-- Popup -->
    <?php include 'popup.php'; ?>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src=" https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js "></script>
    <script src=" https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js "></script>
    <script src=" script/global.js">
    </script>
    <!-- ... (your PHP code) ... -->

<!-- ... (your PHP code) ... -->

<script>
    function submitForm(id, formId) {
        var message = formId === "validasiForm" ?
            "Apakah Anda yakin ingin validasi peminjaman ini?" :
            "Apakah Anda yakin ingin menolak peminjaman ini?";

        if (confirm(message)) {
            var form = document.getElementById(formId + id);
            form.submit();
        }
    }
</script>

<script>
$(document).ready(function() {
    $('#search').keyup(function() {
        var searchTerm = $(this).val();
        if (searchTerm != '') {
        $.ajax({
            url: 'search_peminjaman.php', // Pastikan URL ini sesuai
            type: 'GET',
            data: {term: searchTerm},
            success: function(response) {
                $('tbody').html(response);
            }
        });
    }else {
            // Jika search term kosong, muat semua data
            $.ajax({
                url: 'load_all_peminjaman.php', // Anda bisa membuat script PHP terpisah atau gunakan script yang sama dengan kondisi
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



</body>

</html>