<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasil Pencarian Buku</title>
    <link rel="icon" href="./admin/favicon.ico" type="image/x-icon">
    <!-- Include Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <style>
    /* Custom styles for hover effect */
    .navbar-light .navbar-nav .nav-item:hover .nav-link {
        color: #6499e9;
        /* Change the color to your desired hover color */
    }

    body {
        background-color: #6499E9;
        font-family: Arial, Helvetica, sans-serif;
    }

    /* Set the color of the navbar links to white */
    .navbar-light .navbar-nav .nav-link {
        color: #FFFFFF;
    }


    </style>
    
    <style>
        .buku-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
        }
        .buku {
            border: 1px solid #ddd;
            padding: 10px;
            width: 200px;
            text-align: center;
        }
        .buku img {
            width: 100%;
            height: auto;
        }
    </style>
</head>

<body>

    <?php
        $pageTitle = "Sistem Informasi Perpustakaan";
    ?>

    <header class="custom-header" style="box-shadow: 0px 12px 4px rgba(0, 0, 0, 0.25); background-color: #9EDDFF;">
        <nav class="navbar navbar-expand-lg navbar-light">
            <!-- Navbar content goes here -->
            <a class="navbar-brand" href="index.php" style="display: flexbox; margin-left: 11%; scale: 0.7;">
                <img src="admin/assets/dashboard/logo.png" alt="logo" class="logosma">
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav"
                style="font-family: Arial, Helvetica, sans-serif; font-size: 20px; text-decoration: none;">
                <strong>
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="index.php">Beranda</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="admin/login.php">Masuk</a>
                        </li>
                    </ul>
                </strong>
            </div>
        </nav>
    </header>

       <h1 style="color: white; text-align: center;">Hasil Pencarian Buku</h1>
    <div class="buku-container">
    <?php
    include 'koneksi.php';
    
    if (isset($_GET['search']) && $_GET['search'] != '') {
        $searchTerm = $conn->real_escape_string($_GET['search']);
        $sql = "SELECT * FROM buku WHERE judul_buku LIKE '%{$searchTerm}%'";

        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            echo "<div class='buku'>";
            echo "<img src='../user/uploads/" . $row["sampul"] . "' alt='Sampul Buku'>";
            echo "<h3>" . $row["judul_buku"] . "</h3>";
            echo "<p>Stok: " . $row["jumlah"] . "</p>";
            // Tombol Lihat Selengkapnya
            echo "<a href='detail_cari.php?id=" . $row["id"] . "' class='btn btn-primary'>Lihat Selengkapnya</a>";
            echo "</div>";
        }
    } else {
        echo "<p style='text-align: center;'>Tidak ada hasil untuk pencarian tersebut.</p>";
    }
    }

    $conn->close();
    ?>
    </div>
    
    <!-- Include Bootstrap JS and Popper.js -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <!-- Include Font Awesome for the search icon -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/js/all.min.js"></script>

</body>
</html>