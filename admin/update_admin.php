<?php

session_start();

// Cek jika pengguna tidak login atau bukan admin
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
    header('Location: login.php');
    exit();
}

include 'config.php';

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = mysqli_real_escape_string($conn, $_SESSION['username']);

    // Mengambil password lama dari database
    $queryOldPassword = "SELECT password_admin FROM admin WHERE username = '$username'";
    $result = mysqli_query($conn, $queryOldPassword);
    $row = mysqli_fetch_assoc($result);
    $oldPassword = $row['password_admin'];

    // Sanitizing and retrieving data from the POST request
    $password_admin = mysqli_real_escape_string($conn, $_POST['password_admin']);
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);

    // Membuat query update dasar untuk tabel admin
    $query = "UPDATE admin SET password_admin = '$password_admin', nama = '$nama', email = '$email' WHERE username = '$username'";

    // Menjalankan query update untuk data pengguna
    if (!mysqli_query($conn, $query)) {
        echo "Error updating profile: " . mysqli_error($conn);
        exit;
    }

    // Proses upload file jika ada file yang diunggah
    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] == 0) {
        $uploadDir = 'uploads/';
        $fileTmpPath = $_FILES['profile_picture']['tmp_name'];
        $fileName = $_FILES['profile_picture']['name'];
        $fileNameCmps = explode(".", $fileName);
        $fileExtension = strtolower(end($fileNameCmps));
        $newFileName = md5(time() . $fileName) . '.' . $fileExtension;
        $dest_path = $uploadDir . $newFileName;

        // Move the file from the temporary directory to the desired location
        if (move_uploaded_file($fileTmpPath, $dest_path)) {
            $queryFile = "UPDATE admin SET profile_picture = '$newFileName' WHERE username = '$username'";
            if (!mysqli_query($conn, $queryFile)) {
                echo "Error updating profile picture: " . mysqli_error($conn);
                exit;
            }
        } else {
            echo "There was an error uploading the file";
            exit;
        }
    } elseif (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] !== UPLOAD_ERR_NO_FILE) {
        echo "Error uploading file: " . $_FILES['profile_picture']['error'];
        exit;
    }

    echo "Profile updated successfully.";

    // Cek jika password berubah, hancurkan sesi
    if ($password_admin != $oldPassword) {
        session_destroy();
        header('Location: login.php'); // Redirect ke halaman login
        exit();
    }

    // Redirect atau proses lebih lanjut jika password tidak berubah
    // Misalnya, redirect ke halaman profil
}

?>
