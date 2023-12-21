<?php

session_start();

// Cek jika pengguna tidak login atau bukan admin
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
    header('Location: login.php');
    exit();
}
session_start();

if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized access']);
    exit();
}

include 'koneksi.php';

if (isset($_POST['id'])) {
    $idSiswa = mysqli_real_escape_string($conn, $_POST['id']);
    
    $query = "DELETE FROM siswa WHERE id = '$idSiswa'";
    if (mysqli_query($conn, $query)) {
    echo json_encode(['status' => 'success', 'message' => 'Data berhasil dihapus']);
} else {
    echo json_encode(['status' => 'error', 'message' => mysqli_error($conn)]);
}
} else {
    echo json_encode(['status' => 'error', 'message' => 'No ID provided']);
}

mysqli_close($conn);
?>
