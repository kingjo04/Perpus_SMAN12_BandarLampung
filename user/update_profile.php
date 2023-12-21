<?php
session_start();

// Cek jika pengguna tidak login atau bukan siswa
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'siswa') {
    header("Location: ../admin/login.php"); // Jalur relatif ke halaman login
    exit();
}
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_SESSION['username'];
    $newPassword = $_POST['password'];
    $nama = $_POST['nama'];
    $noHp = $_POST['no_hp'];
    $nis = $_POST['nis'];
    $kelas = $_POST['kelas'];
    $alamat = $_POST['alamat'];

    // Cek apakah password diubah
    $queryCheckPassword = "SELECT password FROM siswa WHERE username = '$username'";
    $result = mysqli_query($conn, $queryCheckPassword);
    $row = mysqli_fetch_assoc($result);

    $passwordChanged = $newPassword !== $row['password'];

    // Query untuk update data
    $query = "UPDATE siswa SET namasiswa='$nama', no_hp='$noHp', nis='$nis', kelas='$kelas', alamat='$alamat'";

    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] == 0) {
        $fileName = $_FILES['profile_picture']['name'];
        $tempName = $_FILES['profile_picture']['tmp_name'];
        $path = "uploads/" . $fileName;
        
        if (move_uploaded_file($tempName, $path)) {
            $query .= ", profile_picture='$fileName'";
        }
    }

    // Update password terakhir untuk menghindari update password jika file upload gagal
    $query .= ", password='$newPassword' WHERE username='$username'";

    if (mysqli_query($conn, $query)) {
        echo "Update berhasil";
        if ($passwordChanged) {
            // Jika password diubah, hancurkan sesi dan redirect ke halaman login
            session_destroy();
            header('Location: Admin/login.php');
            exit();
        }
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>
