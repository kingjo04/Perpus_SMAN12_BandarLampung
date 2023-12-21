<?php

session_start();

// Cek jika pengguna tidak login atau bukan admin
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
    header('Location: login.php');
    exit();
}
include 'koneksi.php';

// Validasi dan sanitasi input
$idSiswa = isset($_GET['id']) ? (int) $_GET['id'] : 0;

// Mempersiapkan statement untuk mencegah SQL injection
$stmt = $conn->prepare("SELECT * FROM siswa WHERE id = ?");
$stmt->bind_param("i", $idSiswa); // "i" berarti tipe data integer
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    echo json_encode(['status' => 'success', 'data' => $row]);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Data tidak ditemukan']);
}

$stmt->close();
$conn->close();
?>
