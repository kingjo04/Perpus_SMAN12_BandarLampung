<?php
$servername = "localhost";
$username = "smanlibr_kingjo";
$password = ";JsT#8JvO?~G";
$dbname = "smanlibr_sman12";

// Membuat koneksi
$conn = new mysqli($servername, $username, $password, $dbname);

// Memeriksa koneksi
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>