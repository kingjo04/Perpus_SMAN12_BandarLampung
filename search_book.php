<?php
// search_books.php
include 'koneksi.php';

if ($koneksi->connect_error) {
    die("Connection failed: " . $koneksi->connect_error);
}

if (isset($_GET['query'])) {
    $query = $koneksi->real_escape_string($_GET['query']);
    $sql = "SELECT * FROM buku WHERE judul_buku LIKE '%$query%'";
    $result = $koneksi->query($sql);

    $buku = [];
    while ($row = $result->fetch_assoc()) {
        $buku[] = $row;
    }

    echo json_encode($buku);
}

$koneksi->close();
?>
