
<?php
include 'config.php';  // Mengimpor konfigurasi database

$query = "SELECT * FROM keranjang_pinjam";  // Query untuk mengambil data
$result = $conn->query($query);

if ($result->num_rows > 0) {
    echo "<table border='1'>";
    echo "<tr><th>ID</th><th>Nama Item</th><th>Jumlah</th></tr>";  // Sesuaikan kolom tabel
    while($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row["id"] . "</td>";
        echo "<td>" . $row["nama_item"] . "</td>";  // Sesuaikan dengan nama kolom di database
        echo "<td>" . $row["jumlah"] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "0 results";
}

$conn->close();  // Menutup koneksi ke database
?>
