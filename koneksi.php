<?php
$servername = "localhost";
$username = "smanlibr_kingjo";
$password = ";JsT#8JvO?~G";
$dbname = "smanlibr_sman12";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>