<?php
session_start();

// Cek jika pengguna tidak login atau bukan admin
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
    header('Location: login.php');
    exit();
}

include 'koneksi.php';

$response = array();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $namasiswa = mysqli_real_escape_string($conn, $_POST['namasiswa']);
    $nis = mysqli_real_escape_string($conn, $_POST['nis']);
    $kelas = mysqli_real_escape_string($conn, $_POST['kelas']);
    $no_hp = mysqli_real_escape_string($conn, $_POST['no_hp']);
    $alamat = mysqli_real_escape_string($conn, $_POST['alamat']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    // Insert data into the "siswa" table
    $query = "INSERT INTO siswa (namasiswa, nis, kelas, no_hp, alamat, username, password) 
              VALUES ('$namasiswa', '$nis', '$kelas', '$no_hp', '$alamat', '$username', '$password')";

    if (mysqli_query($conn, $query)) {
        // Insertion successful
        $response['status'] = 'success';
        $response['message'] = 'Data added successfully!';
    } else {
        // Insertion failed
        $response['status'] = 'error';
        $response['message'] = 'Error: ' . $query . '<br>' . mysqli_error($conn);
    }

    // Close the database connection
    mysqli_close($conn);

    // Return JSON response
    echo json_encode($response);
}
?>