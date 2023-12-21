<?php

session_start();

// Set batas waktu session (2 jam)
if (!isset($_SESSION['time_created'])) {
    $_SESSION['time_created'] = time();
} else {
    // Periksa apakah session telah melewati 2 jam
    if (time() - $_SESSION['time_created'] > 3600) {
        session_unset(); // Bersihkan variabel session
        session_destroy(); // Hancurkan session
        header("Location: ../admin/login.php"); // Arahkan ke halaman login
        exit;
    }
}

// Cek jika pengguna tidak login atau bukan siswa
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'siswa') {
    header("Location: ../admin/login.php"); // Jalur relatif ke halaman login
    exit();
}

include 'config.php';

// Fetch user-specific data from the database based on the logged-in username
$username = $_SESSION['username'];
$query = "SELECT * FROM siswa WHERE username = '$username'";
$result = mysqli_query($conn, $query);

if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}

$row = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Metadata -->
    <title>Profile</title>
    <link rel="icon" href="favicon.ico" type="image/x-icon">
    
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">

    <!-- Other CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />
    <link rel="stylesheet" href="https://unpkg.com/boxicons@latest/css/boxicons.min.css" />
    <link rel="stylesheet" href="css/profile.css" />
    <style>
    
        .header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0 20px;
    height: 80px;
    
}

.navbar {
    display: flex;
    align-items: center;
}

.navbar a {
    margin-right: 20px; /* Jarak antar item di navbar */
    color: #333; /* Warna teks */
    text-decoration: none;
}

.navbar a:hover {
    color: #007bff; /* Warna teks saat hover */
}

.dropdown {
    position: relative;
}


.dropdown-content a:hover {background-color: #f1f1f1;}

.icons {
    display: flex;
    align-items: center;
}

.icons a {
    color: #333;
    font-size: 24px; /* Ukuran ikon */
    margin-left: 15px;
}

.icons a:hover {
    color: #007bff;
}
    /* ... Your existing styles ... */

    .profile-pic-container {
        width: 150px;
        height: 150px;
        border-radius: 50%;
        overflow: hidden;
        margin: 0 auto;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 2px solid #ddd;
        /* Optional: for border */
        cursor: pointer;
        position: relative;
    }

    .profile-pic-container img {
        width: 100%;
        height: auto;
    }

    .file-input {
        display: none;
    }

    /* Spacing for form fields */
    .form-group {
        margin-top: 20px;
    }

    .form-control[readonly] {
        background-color: #f3f3f3;
        /* Warna lebih gelap untuk mode readonly */
    }
    
    .form-control-username[readonly] {
        background-color: #f3f3f3;
        /* Warna lebih gelap untuk mode readonly */
    }
    
    .form-control-nama[readonly] {
        background-color: #f3f3f3;
        /* Warna lebih gelap untuk mode readonly */
    }
    
    .form-control-nohp[readonly] {
        background-color: #f3f3f3;
        /* Warna lebih gelap untuk mode readonly */
    }
    
    .form-control-nis[readonly] {
        background-color: #f3f3f3;
        /* Warna lebih gelap untuk mode readonly */
    }
    
    .form-control-kelas[readonly] {
        background-color: #f3f3f3;
        /* Warna lebih gelap untuk mode readonly */
    }
    </style>

    <style>
    /* ... Existing styles ... */

    /* Style for form-label */
    .form-label {
        display: block;
        margin-bottom: 5px;
        font-weight: bold;
    }

    /* Style for form-control */
    .form-control {
        width: 100%;
        padding: 10px;
        margin-bottom: 15px;
        /* Adjust as needed */
        border: 1px solid #ddd;
        border-radius: 4px;
    }

/* Style for form-control */
    .form-control-username {
        width: 100%;
        padding: 10px;
        margin-bottom: 15px;
        /* Adjust as needed */
        border: 1px solid #ddd;
        border-radius: 4px;
    }
    
    /* Styling for the profile picture and form fields */
    .profile-pic-container {
        margin-bottom: 30px;
        /* Space between profile picture and fields */
    }

    /* ... */
    /* Styling for the overlay */
.overlay {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.7);
    justify-content: center;
    align-items: center;
    z-index: 1;
}

/* Styling for the modal */
.modal {
    background-color: #fff;
    width: 300px;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
    text-align: center;
}

.modal p {
    font-size: 18px;
    color: green; /* Ubah warna sesuai kebutuhan */
}

/* Close button styling */
.close-btn {
    cursor: pointer;
    margin-top: 10px;
    padding: 10px 20px;
    background-color: #007BFF; /* Ubah warna sesuai kebutuhan */
    color: #fff;
    border: none;
    border-radius: 5px;
}

    </style>
    
    
</head>

<body>
    <header class="header">
        <a href="#" class="logo"><img src="img/sman12.png" alt="" /></a>
        <nav class="navbar">
            <a href="homepagelogin.php">Beranda</a>
            <div class="dropdown">
                <a href="#">Kategori <i class="bx bx-chevron-down"></i> </a>
                <div class="dropdown-content">
                    <a href="pendidikan.php">Pendidikan</a>
                    <a href="fiksi.php">Fiksi</a>
                    <a href="nonfiksi.php">Non-Fiksi</a>
                </div>
            </div>
            <a href="profile.php">Profil Saya</a>
            <a href="../backend/logout.php">Keluar</a>
        </nav>

        <div class="icons">
            <div class="dropdownicons">
                <a href="keranjangpinjam.php" style="color: white"><i class="bx bxs-cart-alt"></i></a>
            </div>
        </div>
        </div>
    </header>

    <section class="home">
        <div class="content">
            <?php
            // Assuming you have the user's username stored in a variable $username
            echo "<h3>Hai $username!</h3>";
            ?>
            <h3>Ini Halaman Profile Kamu</h3>
        </div>
    </section>

    <section class="biodata">
        <h3 style="font-size: 18px;">Pengaturan Data Diri</h3>
        <div class="cardbio">
            <!-- Profile Picture Container -->
            <div class="profile-pic-container" onclick="document.getElementById('profile_picture').click()">
                <img id="preview" src="uploads/<?php echo $row['profile_picture']; ?>" alt="Profile Picture">
            </div>
            <input type="file" id="profile_picture" class="file-input" onchange="previewFile()">

            <!-- Username -->
            <div class="form-group">
                <label class="form-label">Username</label>
                <input type="text" class="form-control-username" value="<?php echo $row['username']; ?>" readonly>
            </div>

            <div class="form-group">
                <label class="form-label">Password</label>
                <input type="text" id="password" class="form-control" value="<?php echo $row['password']; ?>" readonly>
            </div>

            <!-- Other Fields -->
            <div class="form-group">
                <label class="form-label">Nama</label>
                <input type="text" id="nama" class="form-control-nama" value="<?php echo $row['namasiswa']; ?>" readonly>
            </div>
            <div class="form-group">
                <label class="form-label">Nomor Telepon</label>
                <input type="text" id="no_hp" class="form-control-nohp mb-1" value="<?php echo $row['no_hp']; ?>" readonly>
            </div>
            <div class="form-group">
                <label class="form-label">NIS</label>
                <input type="text" id="nis" class="form-control-nis" value="<?php echo $row['nis']; ?>" readonly>
            </div>
            <div class="form-group">
                <label class="form-label">Kelas</label>
                <input type="text" id="kelas" class="form-control-kelas" value="<?php echo $row['kelas']; ?>" readonly>
            </div>
            <div class="form-group">
                <label class="form-label">Alamat</label>
                <input type="text" id="alamat" class="form-control" value="<?php echo $row['alamat']; ?>" readonly>
            </div>
            <button type="button" class="btn btn-primary" onclick="toggleEditMode()">Edit</button>
            <button type="button" class="btn btn-success" onclick="saveChanges()">Simpan</button>
        </div>
    </section>
    
<div class="overlay" id="notificationOverlay">
    <div class="modal">
        <p>Profil berhasil diperbaharui</p>
        <button class="close-btn" onclick="closeNotification()">Tutup</button>
    </div>
</div>



    <script>
    function toggleEditMode() {
        var formControls = document.querySelectorAll('.form-control');

        formControls.forEach(function(control) {
            control.readOnly = !control.readOnly;
            if (control.readOnly) {
                control.style.backgroundColor = "#f3f3f3"; // Warna lebih gelap
            } else {
                control.style.backgroundColor = ""; // Warna default ketika editable
            }
        });
    }


function saveChanges() {
    var formData = new FormData();
    formData.append('nama', document.getElementById('nama').value);
    formData.append('no_hp', document.getElementById('no_hp').value);
    formData.append('nis', document.getElementById('nis').value);
    formData.append('kelas', document.getElementById('kelas').value);
    formData.append('alamat', document.getElementById('alamat').value);
    formData.append('password', document.getElementById('password').value);

    var profilePicture = document.getElementById('profile_picture').files[0];
    if (profilePicture) {
        formData.append('profile_picture', profilePicture);
    }

    var xhr = new XMLHttpRequest();
    xhr.open("POST", "update_profile.php", true);
    xhr.onreadystatechange = function() {
        if (this.readyState === XMLHttpRequest.DONE && this.status === 200) {
            
            toggleEditMode();
            openNotification(); // Tampilkan overlay pemberitahuan
            setTimeout(function() {
                                location.reload();
                            }, 1000);
        }
    }
    xhr.send(formData);
}





    // Function to initialize the form with readonly attributes
    function initializeForm() {
        var formControls = document.querySelectorAll('.form-control');
        var overlay = document.getElementById('overlay');

        formControls.forEach(function(control) {
            control.readOnly = true;
        });

        // Display the overlay initially
        overlay.style.display = 'flex';
    }

    // Call initializeForm when the page loads
    window.onload = initializeForm;
    </script>

    <script>
    function previewFile() {
        var preview = document.getElementById('preview');
        var file = document.getElementById('profile_picture').files[0];
        var reader = new FileReader();

        reader.onloadend = function() {
            preview.src = reader.result;
        }

        if (file) {
            reader.readAsDataURL(file);
        } else {
            preview.src = "";
        }
    }
    // Function to close the modal
// Function to open the notification overlay
function openNotification() {
    document.getElementById('notificationOverlay').style.display = 'flex';
}

// Function to close the notification overlay
function closeNotification() {
    document.getElementById('notificationOverlay').style.display = 'none';
}



    // ... Your existing scripts ...
    </script>
    
    
</body>

</html>