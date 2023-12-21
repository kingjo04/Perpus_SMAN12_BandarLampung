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

// Cek jika pengguna tidak login atau bukan siswa
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'siswa') {
    header("Location: ../admin/login.php"); // Jalur relatif ke halaman login
    exit();
}

include 'config.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Metadata -->
    <title>SIPERPUS</title>
    <link rel="icon" href="favicon.ico" type="image/x-icon">
    
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">

    <!-- Other CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />
    <link rel="stylesheet" href="https://unpkg.com/boxicons@latest/css/boxicons.min.css" />
    <link rel="stylesheet" href="css/riwayatpinjam.css" />
    <style>
    /* Styling for the overlay */
    .overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.7);
        justify-content: center;
        align-items: center;
        z-index: 1;
    }

    /* Styling for the modal */
    .modal {
        background-color: #fff;
        width: 860px;
        height: 135px;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
        text-align: center;
    }

    .modal p {
        font-size: 18px;
        color: red;
    }

    /* Close button styling */
    .close-btn {
        cursor: pointer;
        margin-top: 10px;
        padding: 10px 20px;
        background-color: #007BFF;
        color: #fff;
        border: none;
        border-radius: 5px;
    }
    </style>
    
    <style>
    /* Styling for the table and table headers */
    table {
        border-collapse: collapse;
        width: 100%;
        margin-top: 20px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
    }

    th {
        background-color: #007bff;
        color: white;
        text-align: left;
        padding: 10px;
    }

    td {
        padding: 10px;
        border-bottom: 1px solid #ddd;
    }

    /* Hover effect for rows */
    tr:hover {
        background-color: #f5f5f5;
    }

    /* Styling for the sampul image */
    .sampul-img img {
        max-width: 100px;  /* Adjust the width as needed */
        max-height: 120px; /* Adjust the height as needed */
        object-fit: cover;
    }

    /* Responsive design adjustments */
    @media screen and (max-width: 768px) {
        table {
            display: block;
            overflow-x: auto;
        }
    }
    
    /* ... (kode CSS lainnya) ... */

/* Warna hijau untuk status "Tepat Waktu" */
td.status-tepat {
    color: green !important;
}

/* Warna merah untuk status "Terlambat" */
td.status-terlambat {
    color: red !important;
}

</style>


</head>

<body>
    <header class="header">
        <a href="#" class="logo"><img src="img/sman12.png" alt="" /></a>
        <nav class="navbar">
            <a href="homepagelogin.php">Beranda</a>
            <div class="dropdown">
                <a href="#">Kategori <i class="bx bx-chevron-down"></i> </a>
                <div class="dropdown-content">
                    <a href="pendidikan.php">Pendidikan</a>
                    <a href="fiksi.php">Fiksi</a>
                    <a href="nonfiksi.php">Non-Fiksi</a>
                </div>
            </div>
            <a href="profile.php">Profil Saya</a>
            <a href="../backend/logout.php">Keluar</a>
        </nav>

        <div class="icons">
            <div class="dropdownicons">
                <a href="keranjangpinjam.php" style="color: white"><i class="bx bxs-cart-alt"></i></a>
            </div>
            </div>
        </div>
    </header>

    <section class="home">
        <div class="content">
            <h3>Daftar Riwayat </h3>
            <h3>Peminjaman Buku Mu</h3>
            <form action="">
                <div class="form-input">
                    <input type="search" id="searchInput" placeholder="cari ..." />
                    <button type="submit" class="search-btn">
                        <i class="bx bx-search-alt"></i>
                    </button>
                </div>
            </form>
        </div>
        <div class="imghome">
            <img src="img/book.png" alt="" />
        </div>
    </section>

   <section class="hero">
        <div class="table-data">
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Sampul</th>
                        <th>Judul Buku</th>
                        <th>Tanggal Pinjam</th>
                        <th>Tenggat</th>
                        <th>Tanggal Kembali</th>
                        <th>Jumlah</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $username = $_SESSION['username']; // asumsikan username diambil dari session
                    $query = "SELECT riwayat_kembali.*, buku.sampul, buku.nomor_buku FROM riwayat_kembali JOIN buku ON riwayat_kembali.judul = buku.judul_buku WHERE riwayat_kembali.nama = '$username'";
                    $result = mysqli_query($conn, $query);

                    if (mysqli_num_rows($result) > 0) {
                        $no = 1;
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<tr>";
                            echo "<td>" . $no++ . "</td>";
                            echo "<td><img src='uploads/" . $row['sampul'] . "' alt='Cover' style='width: 50px; height: auto;'></td>";
                            echo "<td>" . $row['judul'] . "</td>";
                            echo "<td>" . $row['tanggal'] . "</td>";
                            echo "<td>" . $row['tenggat'] . "</td>";
                            echo "<td>" . $row['kembali'] . "</td>";
                            echo "<td>" . $row['jumlah'] . "</td>";
                            $statusClass = '';
    if ($row['status'] == 'tepat waktu') {
        $statusClass = 'status-tepat';
    } elseif ($row['status'] == 'terlambat') {
        $statusClass = 'status-terlambat';
    }

    echo "<td class='$statusClass'>" . $row['status'] . "</td>";
    
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='8'>Tidak ada data</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </section>

    <script>
document.addEventListener('DOMContentLoaded', function () {
    // Function to handle the search functionality
    function searchTable() {
        var input, filter, table, tr, td, i, txtValue;
        input = document.getElementById("searchInput");
        filter = input.value.toUpperCase();
        table = document.querySelector("table");
        tr = table.getElementsByTagName("tr");

        // Loop through all table rows and hide those that don't match the search input
        for (i = 0; i < tr.length; i++) {
            td = tr[i].getElementsByTagName("td")[2]; // Change index to the column you want to search (zero-based)
            if (td) {
                txtValue = td.textContent || td.innerText;
                if (txtValue.toUpperCase().indexOf(filter) > -1) {
                    tr[i].style.display = "";
                } else {
                    tr[i].style.display = "none";
                }
            }
        }
    }

    // Add event listener to the search input
    document.getElementById("searchInput").addEventListener("input", searchTable);
});
</script>
</body>

</html>