<?php
session_set_cookie_params(7200); // Atur waktu hidup session menjadi 7200 detik (2 jam)
session_start(); // Mulai session
include 'config.php'; // Sertakan file konfigurasi database

// Periksa jika form login telah disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil username dan password dari form
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Cek di tabel admin
    $queryAdmin = "SELECT * FROM admin WHERE username='$username'";
    $resultAdmin = $conn->query($queryAdmin);

    if ($resultAdmin->num_rows > 0) {
        // Username ada di tabel admin
        $rowAdmin = $resultAdmin->fetch_assoc();

        if ($rowAdmin['password_admin'] == $password) {
            // Password benar untuk admin
            $_SESSION['username'] = $username;
            $_SESSION['role'] = 'admin';
            header("Location: ../admin/dashboard.php"); // Jalur relatif ke dashboard admin
            exit;
        } else {
            // Password salah untuk admin
            $_SESSION['error'] = 'Password salah!';
            header("Location: ../admin/login.php"); // Jalur relatif ke halaman login admin
            exit;
        }
    } else {
        // Cek di tabel siswa
        $querySiswa = "SELECT * FROM siswa WHERE username='$username'";
        $resultSiswa = $conn->query($querySiswa);

        if ($resultSiswa->num_rows > 0) {
            // Username ada di tabel siswa
            $rowSiswa = $resultSiswa->fetch_assoc();

            if ($rowSiswa['password'] == $password) {
                // Password benar untuk siswa
                $_SESSION['username'] = $username;
                $_SESSION['role'] = 'siswa';
                header("Location: ../user/homepagelogin.php"); // Jalur relatif ke halaman siswa
                exit;
            } else {
                // Password salah untuk siswa
                $_SESSION['error'] = 'Password salah!';
                header("Location: ../admin/login.php"); // Jalur relatif ke halaman login admin
                exit;
            }
        } else {
            // Username tidak ditemukan di kedua tabel
            $_SESSION['error'] = 'Data tidak ditemukan!';
            header("Location: ../admin/login.php"); // Jalur relatif ke halaman login admin
            exit;
        }
    }
}

// Tidak perlu menutup koneksi di sini
?>
