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

// Cek jika pengguna tidak login atau bukan admin
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
    header('Location: login.php');
    exit();
}

include 'koneksi.php';

// Fetch data from the "siswa" table
$querySiswa = "SELECT * FROM siswa";
$resultSiswa = mysqli_query($conn, $querySiswa);

// Fetch user-specific data from the database based on the logged-in username
$username = mysqli_real_escape_string($conn, $_SESSION['username']);
$query = "SELECT * FROM admin WHERE username = '$username'";
$result = mysqli_query($conn, $query);

if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}

// Fetch the user's data
$row = mysqli_fetch_assoc($result);

// HTML and PHP code for the user profile section
?>


    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Pengaturan</title>
        <link rel="icon" href="favicon.ico" type="image/x-icon">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
        <link rel="stylesheet" href="style/global.css">
        <style>
            /* Style for custom popup */
            
            #customPopup {
                display: none;
                position: fixed;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                background-color: white;
                padding: 20px;
                border-radius: 10px;
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
                z-index: 15;
            }
            
            #customPopupContent {
                text-align: center;
            }
            
            #customPopupClose {
                cursor: pointer;
            }
            /* Ganti warna latar belakang untuk baris ganjil */
            
            #dataTable tbody tr:nth-child(odd) {
                background-color: #9EDDFF;
            }
            /* Ganti warna latar belakang untuk baris genap */
            
            #dataTable tbody tr:nth-child(even) {
                background-color: #F4F4F4;
            }
        </style>
        
            <style>
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
    </style>
    </head>

    <body>
        <div class="container-fluid">
            <!-- Sidebar -->
            <?php include 'sidebar.php'; ?>

            <!-- Header -->
            <?php
        // Set a variable to be used in header.php
        $customHeaderContent = '<div class="halamansaatini">
                                    <a href="pengaturan.php">Pengaturan</a>
                                </div>';
    ?>

                <!-- Include the header.php file -->
                <?php include 'header.php'; ?>

                <!-- Konten -->

                <div class="content" id="pengaturanContent">
                    <h1 style="font-size: 15px; position: absolute; left: 350px;  top: 110px;">
                        Dashboard > Pengaturan
                    </h1>

                    <div class="judul_tbl" style=" position: absolute;
                        background-color: #9EDDFF;
                        width: 750px;
                        height: 75px;
                        left: 600px;
                        top: 165px;
                        font-weight: bold;
                        font-size: 20px;
                        display: flex;
                        align-items: center;">
                        <table>
                            <thead>
                                <tr>
                                    <th style="display:flex; flex: 1; margin-left: 30px;">Pengaturan Anggota</th>
                                    <th style="flex: 1; display: flex; justify-content: flex-start; position: absolute; top: 42px; left: 400px; transform: translate(-50%, -50%);">
                                        <label for="search" style="position: relative; left: 120px; display: inline-block; width: 150px;">
    <input type="search" name="search" id="search" placeholder="Search..." onkeyup="searchData()"
        style="width: 100%; height: 32px; border-radius: 20px; box-shadow: inset 0px 4px 4px rgba(0, 0, 0, 0.25); padding-left: 15px; /* Add padding to the left */">
    <img src="assets/global/iconsearch.png" alt="Search Icon"
        style="position: absolute; left: 70px; top: 50%; transform: translateY(-50%) scale(0.2); cursor: pointer"
        onclick="searchData()">
</label>

                                    </th>
                                    <th style="width: 100px; text-align: center; align-items: center; position: absolute; top: 22px; right: 40px;">
                                        <button id="tambah_anggota" onclick="openPopup()" style="width: 100%; height: 32px; border-radius: 20px; background-color: #6499E9; /* Warna awal */
                   cursor: pointer; color: white; transition: background-color 0.3s; /* Transisi halus */
                   font-size: 15px; font-weight: bold;" onmouseover="this.style.backgroundColor='rgba(100, 153, 233, 0.8)'" onmouseout="this.style.backgroundColor='#6499E9'">
        Tambah
    </button>
                                    </th>
                                </tr>
                            </thead>
                        </table>

                        <div id="popupContainer" style="display: none; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); background-color: white; padding: 20px; border-radius: 29px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); z-index: 5; background-image: url('assets/dashboard/bg\ popup.jpg'); background-size: cover; background-repeat: no-repeat; width: 450px; height: 600px;">
                            <!-- Popup Form Content -->
                            <form id="popupForm" action="process_form.php" method="post" onsubmit="addRowToTable(); return false;" style="position: relative;">
                                <!-- Add your form fields here -->
                                <div style="display: flex; flex-direction: column; position:relative; top:25px;">

                                    <label for="namasiswa" style="font-size: 12px; color: rgba(0, 0, 0, 0.65);">Nama
                                Siswa</label>
                                    <input type="text" id="namasiswa" name="namasiswa" required placeholder="Nama Siswa" style="border: 2px solid #6499E9; background-color: #f2f2f2; border-radius:20px ; width:410px; height:45px; margin-bottom: 10px;">

                                    <div style="display: flex; gap: 10px; margin-bottom: 10px;">
                                        <div style="flex: 1;">
                                            <label for="nis" style="font-size: 12px; color: rgba(0, 0, 0, 0.65);">NIS</label>
                                            <input type="text" id="nis" name="nis" placeholder="NIS" required style="border: 2px solid #6499E9; background-color: #f2f2f2; border-radius:20px; width: 100%;">
                                        </div>
                                        <div style="flex: 1;">
                                            <label for="kelas" style="font-size: 12px; color: rgba(0, 0, 0, 0.65);">Kelas</label>
                                            <input type="text" id="kelas" name="kelas" placeholder="Kelas" required style="border: 2px solid #6499E9; background-color: #f2f2f2; border-radius:20px; width: 100%;">
                                        </div>
                                    </div>

                                    <label for="no_hp" style="font-size: 12px; color: rgba(0, 0, 0, 0.65);">Nomor HP</label>
                                    <input type="text" id="no_hp" name="no_hp" placeholder="Nomor HP" required style="border: 2px solid #6499E9; background-color: #f2f2f2; border-radius:20px; width:410px; height:45px; margin-bottom: 10px;">

                                    <label for="alamat" style="font-size: 12px; color: rgba(0, 0, 0, 0.65);">Alamat</label>
                                    <input type="text" id="alamat" name="alamat" placeholder="Alamat" required style="border: 2px solid #6499E9; background-color: #f2f2f2; border-radius:20px; width:410px; height:45px; margin-bottom: 10px;">

                                    <label for="username" style="font-size: 12px; color: rgba(0, 0, 0, 0.65);">Username</label>
                                    <input type="text" id="username" name="username" placeholder="Username" required style="border: 2px solid #6499E9; background-color: #f2f2f2; border-radius:20px; width:410px; height:45px; margin-bottom: 10px;">

                                    <label for="password" style="font-size: 12px; color: rgba(0, 0, 0, 0.65);">Password</label>
                                    <input type="text" id="password" name="password" placeholder="Password" required style="border: 2px solid #6499E9; background-color: #f2f2f2; border-radius:20px; width:410px; height:45px; margin-bottom: 10px;">

                                    <div style="display: flex; justify-content: center;">
                                        <button type="submit" style="border: 2px solid #6499E9; background: #9EDDFF; box-shadow: 0px 4px 4px rgba(0, 0, 0, 0.25); width: 450px; height: 40px; color: black; border-radius: 20px;">Simpan</button>
                                    </div>

                                    <div <!-- Close button (X) in the top-right corner -->
                                        <button type="button" onclick="closePopup()" style="position: absolute; bottom: 520px; right: 5px; background-color: #f2f2f2; border: none;">X</button>

                                        <!-- Submit button centered at the bottom -->
                                    </div </div>
                            </form>
                            </div>
                        </div>
                    </div>

                    <table id="dataTable" style="position: absolute; width: 745px; left: 600px; top: 240px; border-collapse: collapse;">
                        <!-- Header Row -->
                        <thead>
                            <tr style="background-color: #F4F4F4; height: 50px;">
                                <!-- Kolom "no" -->
                                <th style="width: 15%; text-align: center; padding-right: 10px; font-weight: bold;">No</th>

                                <!-- Kolom "nama siswa" -->
                                <th style="width: 30%; text-align: center; padding-right: 10px; font-weight: bold;">Nama Siswa
                                </th>

                                <!-- Kolom "nis" -->
                                <th style="width: 20%; text-align: center; padding-right: 10px; font-weight: bold;">NIS</th>

                                <!-- Kolom "kelas" -->
                                <th style="width: 15%; text-align: center; padding-right: 10px; font-weight: bold;">Kelas
                                </th>

                                <!-- Kolom "no_hp" -->
                                <th style="width: 20%; text-align: center; padding-right: 10px; font-weight: bold;">Nomor HP
                                </th>

                                <!-- Kolom "alamat" -->
                                <th style="width: 15%; text-align: center; padding-right: 10px; font-weight: bold;">Alamat</th>

                                <!-- Kolom "username" -->
                                <th style="width: 15%; text-align: center; padding-right: 10px; font-weight: bold;">Username
                                </th>

                                <!-- Kolom "password" -->
                                <th style="width: 20%; text-align: center; padding-right: 10px; font-weight: bold;">Password
                                </th>

                                <!-- Kolom "aksi" -->
                                <th style="width: 15%; text-align: center; padding-right: 10px; font-weight: bold;">Aksi</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php
    $no = 1; // Initialize a variable to store the sequential number
    while ($rowSiswa = mysqli_fetch_assoc($resultSiswa)) {
        echo "<tr style='background-color: " . (($no % 2 == 0) ? '#F4F4F4' : '#9EDDFF') . ";'>"; // Set background color based on row number
        echo "<td style='text-align: center;'>{$no}</td>"; // Display the sequential number
        echo "<td style='text-align: center;'>{$rowSiswa['namasiswa']}</td>";
        echo "<td style='text-align: center;'>{$rowSiswa['nis']}</td>";
        echo "<td style='text-align: center;'>{$rowSiswa['kelas']}</td>";
        echo "<td style='text-align: center;'>{$rowSiswa['no_hp']}</td>";
        echo "<td style='text-align: center;'>{$rowSiswa['alamat']}</td>";
        echo "<td style='text-align: center;'>{$rowSiswa['username']}</td>";
        echo "<td style='text-align: center;'>{$rowSiswa['password']}</td>";
        echo "<td style='text-align: center;'>
                <div style='display: flex; justify-content: center; align-items: center; gap: 10px;'>
                    <img src='assets/dashboard/iconedit.png' alt='Edit' style='cursor:pointer; transform: scale(0.7);' onclick='bukaFormEdit(\"" . $rowSiswa['id'] . "\")'>
                    <img src='assets/global/iconhapus.png' alt='Hapus' style='cursor:pointer; transform: scale(0.7);' onclick='hapusData(" . $rowSiswa['id'] . ")'>
                </div>
              </td>";
        echo "</tr>";
        $no++; // Increment the sequential number for the next row
    }
    ?>
                        </tbody>



                    </table>

                    <!-- Profile Box -->
                    <section class="info_profil_box" style="
    position: absolute;
    background-color: #9EDDFF;
    width: 320px;
    height: 900px;
    left: 270px;
    top: 165px;
    display: flex;
    align-items: center;
    flex-direction: column;
">

                        <!-- Profile Picture Container -->
            <div class="profile-pic-container" onclick="document.getElementById('profile_picture').click()">
    <img id="preview" src="uploads/<?php echo $row['profile_picture']; ?>" alt="Profile Picture">
</div>
<input type="file" id="profile_picture" class="file-input" value="<?php echo $row['profile_picture']; ?>" onchange="previewFile()">





            <!-- Other Fields -->
            <div class="form-group" style="width: 100%; margin-bottom: 20px; padding: 10px; background-color: #FFFFFF; border-radius: 20px; border: 2px solid #6499E9; position: relative;">
                <label class="form-label">Nama</label>
                <input type="text" id="nama" class="form-control" value="<?php echo $row['nama']; ?>" readonly>
            </div>

            <!-- Username -->
            <div class="form-group" style="width: 100%; margin-bottom: 20px; padding: 10px; background-color: #FFFFFF; border-radius: 20px; border: 2px solid #6499E9; position: relative;">
                <label class="form-label">Username</label>
                <input type="text" class="form-control-username" value="<?php echo $row['username']; ?>" readonly>
            </div>

            <div class="form-group" style="width: 100%; margin-bottom: 20px; padding: 10px; background-color: #FFFFFF; border-radius: 20px; border: 2px solid #6499E9; position: relative;">
                <label class="form-label">Email</label>
                <input type="text" id="email" class="form-control" value="<?php echo $row['email']; ?>" readonly>
            </div>

            <div class="form-group" style="width: 100%; margin-bottom: 20px; padding: 10px; background-color: #FFFFFF; border-radius: 20px; border: 2px solid #6499E9; position: relative;">
                <label class="form-label">Password</label>
                <input type="text" id="password_admin" class="form-control" value="<?php echo $row['password_admin']; ?>" readonly>
            </div>

            <div class="form-group button-container" style="width: 100%; display: flex; justify-content: center; gap: 10px; margin-top: 20px;">
    <button type="button" style="border: 2px solid #6499E9; " class="btn btn-primary" onclick="toggleEditMode()">Edit</button>
    <button type="button" style="border: 2px solid #6499E9; " class="btn btn-success" onclick="saveChanges()">Simpan</button>
</div>
                        </div>
                    </form>
                </div>
                
    <!-- Form Edit Data -->
   <div id="editPopupContainer"
    style="display: none; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); background-color: white; padding: 20px; border-radius: 29px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); z-index: 5; background-image: url('assets/dashboard/bg\ popup.jpg'); background-size: cover; background-repeat: no-repeat; width: 450px; height: 600px;">
    <form id="editPopupForm" action="edit_siswa.php" method="post" style="position: relative;">
        <!-- Add your form fields here -->
        <div style="display: flex; flex-direction: column; position:relative; top:25px;">
            <input type="hidden" id="editId" name="id" style="width: 100%;    padding: 6px; background-color: #FFFFFF; border-radius: 20px; border: 2px solid #6499E9; position: relative;">

            <label for="editNamasiswa">Nama Siswa:</label>
            <input type="text" id="editNamasiswa" name="namasiswa" required placeholder="Nama Siswa" style="width: 100%;    padding: 6px; background-color: #FFFFFF; border-radius: 20px; border: 2px solid #6499E9; position: relative;">

            <label for="editNis">NIS:</label>
            <input type="text" id="editNis" name="nis" required placeholder="NIS" style="width: 100%;    padding: 6px; background-color: #FFFFFF; border-radius: 20px; border: 2px solid #6499E9; position: relative;">

            <label for="editKelas">Kelas:</label>
            <input type="text" id="editKelas" name="kelas" required placeholder="Kelas" style="width: 100%;    padding: 6px; background-color: #FFFFFF; border-radius: 20px; border: 2px solid #6499E9; position: relative;">

            <label for="editNoHp">Nomor HP:</label>
            <input type="text" id="editNoHp" name="no_hp" required placeholder="Nomor HP" style="width: 100%;    padding: 6px; background-color: #FFFFFF; border-radius: 20px; border: 2px solid #6499E9; position: relative;">

            <label for="editAlamat">Alamat:</label>
            <input type="text" id="editAlamat" name="alamat" required placeholder="Alamat" style="width: 100%;    padding: 6px; background-color: #FFFFFF; border-radius: 20px; border: 2px solid #6499E9; position: relative;">

            <label for="editPassword">Password:</label>
            <input type="password" id="editPassword" name="password" required placeholder="Password" style="width: 100%; padding: 6px; background-color: #FFFFFF; border-radius: 20px; border: 2px solid #6499E9; position: relative;">

            <!-- Add margin-top for spacing -->
            <button type="button" onclick="submitEditForm()" style="border: 2px solid #6499E9; margin-top: 20px; background: #9EDDFF; box-shadow: 0px 4px 4px rgba(0, 0, 0, 0.25); width: 410px; height: 40px; color: black; border-radius: 20px;">Simpan Perubahan</button>
            
            <!-- Close button (X) in the top-right corner -->
            <button type="button" onclick="closePopupedit()"
                style="position: absolute; bottom: 490px; right: 10px; background-color: #f2f2f2; border: none; padding: 5px 10px; cursor: pointer; border-radius: 5px;">X</button>
        </div>
    </form>
</div>

                <!-- Popup -->
                <?php include 'popup.php'; ?>

                <div id="customPopup">
                    <div id="customPopupContent"></div>
                </div>
        </div>



        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src=" https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js "></script>
        <script src=" https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js "></script>
        <script src=" script/global.js">
        </script>
        <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

        <script>
            function openPopup() {
                // Show the popup container
                document.getElementById('popupContainer').style.display = 'block';
            }

            function closePopup() {
                // Hide the popup container
                document.getElementById('popupContainer').style.display = 'none';
            }

            function openCustomPopup(message) {
                document.getElementById('customPopupContent').innerHTML = message;
                document.getElementById('customPopup').style.display = 'block';
            }

            function closeCustomPopup() {
                document.getElementById('customPopup').style.display = 'none';
            }

            function addRowToTable() {
                // Submit the form using AJAX
                $.ajax({
                    type: 'POST',
                    url: 'add_siswa.php',
                    data: $('#popupForm').serialize(), // Serialize form data
                    dataType: 'json',
                    success: function(response) {
                        if (response.status === 'success') {
                            openCustomPopup('Akun Siswa Berhasil Ditambahkan');
                            setTimeout(function() {
                                location.reload();
                            }, 1000);
                        } else {
                            openCustomPopup('Error: ' + response.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX Error: ' + status + ' - ' + error);
                    }
                });

                // Close the popup (if needed)
                closePopup();
            }

            function hapusData(idSiswa) {
                if (confirm('Apakah Anda yakin ingin menghapus data siswa ini?')) {
                    $.ajax({
                        type: 'POST',
                        url: 'hapus_siswa.php',
                        data: {
                            id: idSiswa
                        },
                        dataType: 'json', // Tambahkan baris ini
                        success: function(response) {
                            if (response.status === 'success') {
                                openCustomPopup('Data siswa berhasil dihapus.');
                                setTimeout(function() {
                                    location.reload();
                                }, 1000);
                            } else {
                                openCustomPopup('Error: ' + response.message);
                                setTimeout(function() {
                                    location.reload();
                                }, 1000);
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error('AJAX Error: ' + status + ' - ' + error);
                        }
                    });

                }
            }

            function bukaFormEdit(idSiswa) {
                $.ajax({
                    type: 'GET',
                    url: 'get_siswa.php',
                    data: {
                        id: idSiswa
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.status === 'success') {
                            // Mengisi form dengan data yang ditarik
                            $('#editId').val(response.data.id);
                            $('#editNamasiswa').val(response.data.namasiswa);
                            $('#editNis').val(response.data.nis);
                            $('#editKelas').val(response.data.kelas);
                            $('#editNoHp').val(response.data.no_hp);
                            $('#editAlamat').val(response.data.alamat);
                            $('#editUsername').val(response.data.username);
                            $('#editPassword').val(response.data.password);

                            // Menampilkan form edit
                            $('#editPopupContainer').show();
                        } else {
                            alert('Error: ' + response.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX Error: ' + status + ' - ' + error);
                    }
                });
            }

            function submitEditForm() {
    var formData = new FormData(document.getElementById('editPopupForm'));

    $.ajax({
        type: 'POST',
        url: 'edit_siswa.php',
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
            if (response.status === 'success') {
               openCustomPopup('Data siswa berhasil diedit.');
                                setTimeout(function() {
                                    location.reload();
                                }, 1000);
            } else {
                openCustomPopup('Data siswa berhasil diedit.');
                                setTimeout(function() {
                                    location.reload();
                                }, 1000);
            }
        },
        error: function(xhr, status, error) {
            console.error('AJAX Error: ' + status + ' - ' + error);
        }
    });
}


            function closePopupedit() {
                // Hide the edit popup
                $('#editPopupContainer').hide();
            }
        </script>

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
        formData.append('email', document.getElementById('email').value);
        formData.append('password_admin', document.getElementById('password_admin').value);

        var profilePicture = document.getElementById('profile_picture').files[0];
        if (profilePicture) {
            formData.append('profile_picture', profilePicture);
        }

        var xhr = new XMLHttpRequest();
        xhr.open("POST", "update_admin.php", true);
        xhr.onreadystatechange = function() {
            if (this.readyState === XMLHttpRequest.DONE && this.status === 200) {
                alert('Perubahan berhasil disimpan');
                // Reload the page to reflect changes or handle UI update here
                location.reload();
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
            function searchData() {
                var input, filter, table, tr, td, i, txtValue;
                input = document.getElementById("search");
                filter = input.value.toUpperCase();
                table = document.getElementById("dataTable");
                tr = table.getElementsByTagName("tr");

                for (i = 0; i < tr.length; i++) {
                    td = tr[i].getElementsByTagName("td")[1]; // Kolom nama siswa
                    tdNis = tr[i].getElementsByTagName("td")[2]; // Kolom NIS
                    if (td || tdNis) {
                        txtValue = td.textContent || td.innerText;
                        txtValueNis = tdNis.textContent || tdNis.innerText;
                        if (txtValue.toUpperCase().indexOf(filter) > -1 || txtValueNis.toUpperCase().indexOf(filter) > -1) {
                            tr[i].style.display = "";
                        } else {
                            tr[i].style.display = "none";
                        }
                    }
                }
            }

            function previewFile() {
    var preview = document.getElementById('preview');
    var file = document.getElementById('profile_picture').files[0];
    var reader = new FileReader();

    reader.onloadend = function() {
        if (file) {
            preview.src = reader.result;
        }
    }

    if (file) {
        reader.readAsDataURL(file);
    } else {
        preview.src = ""; // Atau tetapkan ke gambar default jika diperlukan
    }
}

        </script>

    </body>

    </html>