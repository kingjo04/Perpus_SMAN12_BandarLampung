<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem informasi perpustakaan SMA Negeri 12 Bandar Lampung</title>
    <link rel="icon" href="/favicon.ico" type="image/x-icon">
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

    .container-welcome {
        display: flex;
        justify-content: center;
        /* Pusatkan secara horizontal */
        align-items: center;
        /* Pusatkan secara vertikal */
        flex-wrap: wrap;
        /* Biarkan elemen wrap jika tidak cukup ruang */
    }

    .text-left h1 {
        font-size: 30px;
        margin-top: 3rem;
        /* Adjust the top margin as needed */
        margin-bottom: 2rem;
        /* Adjust the bottom margin as needed */
        color: #FFFFFF;
        font-weight: bold;
    }

    .text-left h2 {
        margin-top: 1rem;
        /* Adjust the top margin as needed */
        margin-bottom: 2rem;
        /* Adjust the bottom margin as needed */
        text-shadow: 0px 10px 4px rgba(0, 0, 0, 0.25);
        color: #FFFFFF;
        font-weight: bold;
    }

    /* Set the color of the navbar links to white */
    .navbar-light .navbar-nav .nav-link {
        color: #FFFFFF;
    }

    /* Custom styles for positioning and responsiveness */
    .container-welcome {
        margin-left: 18%;
        text-align: center;
        /* Center-align the welcome text */
    }

    .container-welcome h2 {
        font-size: 2.5rem;
        margin-top: 4.7rem;
        /* Sesuaikan margin-top agar terlihat proporsional */
    }

    .text-left {
        flex: 1;
        /* Bagian teks mengambil sebagian besar ruang */
        padding: 2rem;
        /* Berikan padding agar teks terlihat lebih baik */
    }

    .gambarbuku {
        flex: 1;
        /* Bagian gambar mengambil sebagian besar ruang */
        max-width: 100%;
        /* Maksimum lebar sesuai dengan konten parent */
        height: auto;
        margin-top: 2rem;
    }

    /* Remove unnecessary style for text-left class */
    .text-left {
        text-align: left;
    }

    /* Style for the search form */
    #caribukuform {
        display: flex;
        justify-content: flex-start;
        /* Geser ke kiri */
        align-items: center;
        /* Pusatkan secara vertikal */
        width: 100%;
        margin-top: 2rem;
    }

    #caribukuform .input-group {
        width: 35%;
        /* Sesuaikan dengan lebar yang diinginkan */
        max-width: 600px;
        margin-left: 14%;
        /* Sesuaikan geseran ke kiri */
        position: relative;
    }


    #caribukuform input[type="search"] {
        width: 100%;
        height: 50px;
        border: none;
        border-radius: 20px;
        padding: 0 40px 0 20px;
        background: #D0E7D2;
        color: #000000;
        font-size: 16px;
    }

    #caribukuform button {
        z-index: 4;
        position: absolute;
        right: 0;
        top: 0;
        width: 50px;
        height: 50px;
        border: none;
        border-radius: 20px;
        background: #D0E7D2;
        color: #000000;
        font-size: 20px;
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
            <a class="navbar-brand" href="#" style="display: flexbox; margin-left: 11%; scale: 0.7;">
                <img src="assets/dashboard/logo.png" alt="logo" class="logosma">
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

    <div class="container-welcome mt-5">
        <div class="text-left">
            <h1 class="display-4">Selamat Datang !</h1>
            <h2 class="display-4">Sistem Informasi<br>Perpustakaan<br><span class="text-warning">SMA Negeri
                    12</span><br>Bandar Lampung</h2>
        </div>
        <div class="gambarbuku-container">
            <img src="assets/global/buku.png" alt="buku" class="img-fluid gambarbuku">
        </div>
    </div>

    <form class="container mt-4" id="caribukuform">
        <div class="input-group">
            <input type="search" name="search" id="search" class="form-control" placeholder="Cari bukumu">
            <button class="btn btn-primary search-icon" type="button">
                <i class="fas fa-search"></i> <!-- Bootstrap search icon -->
            </button>
        </div>
    </form>

    <!-- Include Bootstrap JS and Popper.js -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <!-- Include Font Awesome for the search icon -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/js/all.min.js"></script>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const cariBukuText = document.querySelector('a[href="#caribukuform"]');
        const searchInput = document.getElementById('search');
        cariBukuText.addEventListener('click', function(event) {
            event.preventDefault();
            searchInput.focus();
        });
    });
    </script>

</body>

</html>