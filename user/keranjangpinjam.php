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

date_default_timezone_set('Asia/Jakarta');
include 'config.php';

if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];
    $searchTerm = isset($_POST['search']) ? $_POST['search'] : '';

    // Perbarui tanggal pinjam untuk semua entri di keranjang_pinjam milik pengguna ini
    $Tgl_pinjam = date('Y-m-d');
    $queryUpdateTglPinjam = "UPDATE keranjang_pinjam SET tgl_pinjam = '$Tgl_pinjam' WHERE username = '$username'";
    $conn->query($queryUpdateTglPinjam);

    // Query untuk mencari buku berdasarkan judul buku
    $query = "SELECT * FROM keranjang_pinjam WHERE username = '$username' AND judul_buku LIKE '%$searchTerm%'";
    $result = $conn->query($query);

    $Tgl_kembali = date('Y-m-d', strtotime('+7 days'));
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
    <link rel="stylesheet" href="css/keranjangpinjam.css" />
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

    <!-- Di dalam tag <style> Anda -->
    <style>
    /* Styling Tabel */
    table {
        width: 320%;
        border-collapse: collapse;
        margin: 20px 0;
    }

    th, td {
        border: 1px solid #ddd;
        padding: 10px 15px;
        text-align: left;
    }

    /* Header Tabel */
    thead th {
        background-color: #007BFF;
        color: white;
        font-weight: bold;
    }

    /* Hover Effects */
    tbody tr:hover {
        background-color: #f2f2f2;
    }

    /* Button Styling */
    .button {
        padding: 5px 10px;
        background-color: #007BFF;
        color: white;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        text-decoration: none;
        text-align: center;
    }

    .button:hover {
        background-color: #0056b3;
    }
    
    .sampul-img {
        width: 130px;  /* Lebar tetap untuk kotak gambar */
        height: 150px; /* Tinggi tetap untuk kotak gambar */
        display: flex;
        justify-content: center;
        align-items: center;
        overflow: hidden; /* Pastikan gambar tidak meluap keluar kotak */
    }

    .sampul-img img {
        max-width: 100%; /* Membuat gambar menyesuaikan lebar kotak */
        max-height: 100%; /* Membuat gambar menyesuaikan tinggi kotak */
        object-fit: cover; /* Skala gambar untuk menutupi kotak sepenuhnya */
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        table {
            width: 100%;
            overflow-x: auto;
        }
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
            <h3>Keranjang Buku Mu</h3>
            <h3>Ayo Pinjam Sekarang</h3>
            <form action="" method="post">
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

    <section class="hero">
        <div class="table-data-0">
            <div class="head">
                <p class="right" style="bottom: 40px; font-size:15px"><a href="riwayatpinjam.php">Peminjaman Menunggu/Divalidasi</a></p>
            </div>
            
            <div class="table-data-1">
            <div class="head">
                <p class="right" style="bottom: 40px; font-size:15px"><a href="riwayatkembali.php">Riwayat Pengembalian</a></p>
            </div>

<form action="Proses_Peminjaman.php" method="post" onsubmit="return validateForm()">

            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th><input type="checkbox" id="checkAllRows" onclick="toggleSelectAll(this)"></th>
                        <th>Sampul</th>
                        <th>Nomor Buku</th>
                        <th>Judul Buku</th>
                        <th>Tanggal Pinjam</th>
                        <th>Tanggal Kembali</th>
                        <th>Jumlah</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
    <?php
    date_default_timezone_set('Asia/Jakarta');
    $no = 1;
    $dataFound = false; // Variabel untuk mengecek apakah ada data

    while ($row = $result->fetch_assoc()) {
        $dataFound = true; // Data ditemukan
        echo '<tr>';
        echo '<td>' . $no++ . '</td>';
        echo '<td><input type="checkbox" name="bukuDipilih[]" value="' . $row['id'] . '"></td>';
        echo '<td class="sampul-img"><img src="uploads/' . $row['sampul'] . '" alt="Sampul Buku"></td>';
        echo '<td>' . $row['nomor_buku'] . '</td>';
        echo '<td>' . $row['judul_buku'] . '</td>';
        echo '<td><input type="date" name="tanggal_pinjam[]" min="' . date('Y-m-d') . '" value="' . $row['tgl_pinjam'] . '"></td>';
        echo '<td><input type="date" name="tanggal_kembali[]" min="' . date('Y-m-d') . '" value="' . $row['tgl_kembali'] . '"></td>';
        echo '<td>' . $row['jumlah'] . '</td>';
        echo '<td><a href="#" class="button" onclick="hapusBuku(' . $row['id'] . ')">Hapus</a></td>';
        echo '</tr>';
    }

    // Jika tidak ada data, tampilkan pesan
    if (!$dataFound) {
        echo '<tr><td colspan="9" style="text-align:center;">Tidak ada data peminjaman</td></tr>';
    }
    ?>
</tbody>

            </table>

            <div class="btnpinjam" style="background-color: #007BFF;">
                <button type="submit" href="riwayatpinjam.php">Ajukan Peminjaman</button>
            </div>
        </form>
        </div>
    </section>
    
    <div class="overlay" id="overlay">
    <div class="modal">
        <p>Pilih buku terlebih dahulu sebelum ajukan peminjaman.</p>
        <button class="close-btn" onclick="closeModal()">Tutup</button>
    </div>
</div>





    <script>
    // Function to open the modal
    function openModal() {
        document.getElementById('myModal').style.display = 'flex';
    }

    // Function to close the modal
    function closeModal() {
        document.getElementById('myModal').style.display = 'none';
    }
    </script>

    <script>

    // Function to delete a book from the cart
    function hapusBuku(id) {
        // Tampilkan konfirmasi penghapusan
        if (confirm('Apakah Anda yakin ingin menghapus buku ini dari keranjang?')) {
            // Kirim permintaan AJAX untuk menghapus buku
            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'hapus_buku.php', true);
            xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
            xhr.onreadystatechange = function() {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    // Refresh halaman setelah penghapusan berhasil
                    location.reload();
                }
            };
            xhr.send('id=' + id);
        }
    }-ha
    </script>

    <script>
    // Fungsi untuk menangani klik pada checkbox "Select All"
    function toggleSelectAll(control) {
        var checkboxes = document.querySelectorAll('input[name="bukuDipilih[]"]');
        checkboxes.forEach(function(checkbox) {
            checkbox.checked = control.checked;
        });
    }

    // Fungsi untuk memeriksa status checkbox individu dan mengatur checkbox "Select All"
    function updateSelectAllControl() {
        var checkboxes = document.querySelectorAll('input[name="bukuDipilih[]"]');
        var allChecked = true;

        checkboxes.forEach(function(checkbox) {
            if (!checkbox.checked) {
                allChecked = false;
            }
        });

        document.getElementById('checkAllRows').checked = allChecked;
    }

    // Menambahkan event listener ke setiap checkbox individu
    var individualCheckboxes = document.querySelectorAll('input[name="bukuDipilih[]"]');
    individualCheckboxes.forEach(function(checkbox) {
        checkbox.addEventListener('change', updateSelectAllControl);
    });
</script>

<script>
    document.querySelector('form').addEventListener('submit', function(event) {
        var valid = true;
        var tanggalPinjamInputs = document.querySelectorAll('input[name="tanggal_pinjam[]"]');
        var tanggalKembaliInputs = document.querySelectorAll('input[name="tanggal_kembali[]"]');

        tanggalPinjamInputs.forEach(function(tanggalPinjam, index) {
            var tanggalKembali = tanggalKembaliInputs[index];
            if (tanggalKembali.value < tanggalPinjam.value) {
                alert('Tanggal kembali tidak boleh lebih kecil dari tanggal pinjam.');
                valid = false;
            }
        });

        if (!valid) {
            event.preventDefault(); // Menghentikan pengiriman formulir
        }
    });
</script>

<script>
// Function to open the modal
function openModal() {
    document.getElementById('overlay').style.display = 'flex';
}

// Function to close the modal
function closeModal() {
    document.getElementById('overlay').style.display = 'none';
}

// Function to validate the form
function validateForm() {
    var checkboxes = document.querySelectorAll('input[name="bukuDipilih[]"]');
    var atLeastOneChecked = false;

    checkboxes.forEach(function(checkbox) {
        if (checkbox.checked) {
            atLeastOneChecked = true;
        }
    });

    if (!atLeastOneChecked) {
        // Tampilkan peringatan jika tidak ada checkbox yang dipilih
        openModal();
        return false; // Mencegah pengiriman formulir
    }

    return true; // Lanjutkan pengiriman formulir jika setidaknya satu checkbox dipilih
}
</script>


    
    

</body>

</html>