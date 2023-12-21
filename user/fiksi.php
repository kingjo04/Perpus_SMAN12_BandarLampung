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

// Check if the search form is submitted
if (isset($_GET['search'])) {
    $searchTerm = $_GET['search'];
    // Modify the query to include a search condition
    $queryBuku = $conn->query("SELECT * FROM buku WHERE kategori='fiksi' AND judul_buku LIKE '%$searchTerm%'");
} else {
    // Default query without search condition
    $queryBuku = $conn->query("SELECT * FROM buku WHERE kategori='fiksi'");
}
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
    <link rel="stylesheet" href="css/fiksi.css" />
    <style>
    /* Tambahkan CSS untuk menyesuaikan tata letak tombol Tambahkan */
    .boxcard .box .stok-text {
        margin-bottom: 10px;
        /* Sesuaikan dengan nilai yang sesuai */
    }

    .boxcard .box .btn {
        margin-top: 10px;
        /* Sesuaikan dengan nilai yang sesuai */
    }
    </style>



    <style>
        .header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0 20px;
    height: 80px;
    
}

.navbar {
    display: flex;
    align-items: center;
}

.navbar a {
    margin-right: 20px; /* Jarak antar item di navbar */
    color: #333; /* Warna teks */
    text-decoration: none;
}

.navbar a:hover {
    color: #007bff; /* Warna teks saat hover */
}

.dropdown {
    position: relative;
}


.dropdown-content a:hover {background-color: #f1f1f1;}

.icons {
    display: flex;
    align-items: center;
}

.icons a {
    color: #333;
    font-size: 24px; /* Ukuran ikon */
    margin-left: 15px;
}

.icons a:hover {
    color: #007bff;
}
    
    
    /* Aturan CSS untuk mengatur gambar */
    .boxcard .box img {
        max-width: 100%;
        /* batasi lebar gambar agar tidak melebihi kontainernya */
        max-height: 150px;
        /* batasi tinggi gambar */
        object-fit: contain;
        /* pastikan gambar tidak terdistorsi */
        margin: 25px;
        /* beri ruang di atas dan bawah gambar */
        display: block;
        /* membuat gambar menjadi block-level element */

    }
    
    .boxcard {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-around;
    margin-top:50px;
}

.box {
    width: 200px; /* Sesuaikan dengan lebar yang diinginkan */
    margin: 15px;
    padding: 15px;
    border: 1px solid #ccc;
    text-align: center;
}

/* Aturan CSS untuk mengatur gambar */
.box img {
    max-width: 100%;
    max-height: 150px;
    object-fit: contain;
    margin-bottom: 10px;
}

/* Tambahkan CSS untuk menyesuaikan tata letak tombol Tambahkan */
.box .stok-text {
    margin-bottom: 10px;
}

.box .btn {
    margin-top: 10px;
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
    </header>

    <section class="home">
        <div class="content">
            <h3>Cari Buku Disini !</h3>
            <h3>Mau Baca Buku Apa Hari Ini ?</h3>
            <!-- Modify the form to include the search input and method -->
            <form action="" method="get">
                <div class="form-input">
                    <input type="search" name="search" placeholder="cari bukumu..." />
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

    <section class="card" id="card">
        <div class="txt" style="position: absolute; font-size: 25px; left: 600px">
            Kategori Fiksi
        </div>
        <div class="boxcard">
            <?php
            // Loop through the query results and display book data in the box
            while ($row = $queryBuku->fetch_assoc()) {
            ?>
                <div class="box">
                    <!-- Replace img with data from the database -->
                    <img src="uploads/<?php echo $row['sampul']; ?>" alt="">

                    <!-- Replace Book Title with data from the database -->
                    <p><?php echo $row['judul_buku']; ?></p>

                    <!-- Replace Available Stock with data from the database -->
                    <p class="stok-text">Stok Tersedia: <?php echo $row['jumlah']; ?></p>

                    <form action="tambah_ke_keranjang.php" method="post">
                        <input type="hidden" name="id_buku" value="<?php echo $row['id']; ?>">
                        <input type="submit" class="btn" value="Tambahkan">
                    </form>
                </div>
            <?php
            }
            ?>
        </div>
    </section>
</body>

</html>