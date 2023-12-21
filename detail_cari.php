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

<style>
        body {
            background-color: #6499E9;
            font-family: Arial, Helvetica, sans-serif;
            color: #FFFFFF;
        }
        .buku-detail {
            background-color: cornflowerblue;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 20px;
            margin: 30px auto;
            width: 80%;
            max-width: 600px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        .buku-detail img {
            width: 100%;
            height: auto;
            max-width: 250px;
            border-radius: 4px;
            margin-bottom: 15px;
        }
        .buku-detail h3 {
            color: #333;
            margin-bottom: 10px;
        }
        .buku-detail p {
            color: black;
            font-weight:bold;
            font-size: 16px;
            line-height: 1.5;
        }
    </style>

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

       <h1 style ="color : white; text-align:center;">Detail Buku</h1>
       <?php
    include 'koneksi.php';

    $idBuku = isset($_GET['id']) ? $conn->real_escape_string($_GET['id']) : 0;

    $sql = "SELECT * FROM buku WHERE id = {$idBuku}";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        echo "<div class='buku-detail'>";
        echo "<img src='../user/uploads/" . $row["sampul"] . "' alt='Sampul Buku'>";
        echo "<h3>" . $row["judul_buku"] . "</h3>";
        echo "<p>Penerbit: " . $row["penerbit"] . "</p>";
        echo "<p>Pengarang: " . $row["pengarang"] . "</p>";
        echo "<p>Kategori: " . $row["kategori"] . "</p>";
        echo "<p>Jumlah: " . $row["jumlah"] . "</p>";
        echo "<p>Tahun: " . $row["tahun"] . "</p>";
        echo "</div>";
    } else {
        echo "<p>Buku tidak ditemukan.</p>";
    }

    $conn->close();
    ?>
    
    <!-- Include Bootstrap JS and Popper.js -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <!-- Include Font Awesome for the search icon -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/js/all.min.js"></script>


</body>
</html>