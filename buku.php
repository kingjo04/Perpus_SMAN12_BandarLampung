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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nomor = mysqli_real_escape_string($conn, trim($_POST['nomor']));
    $judul = mysqli_real_escape_string($conn, trim($_POST['judul']));
    $penerbit = mysqli_real_escape_string($conn, trim($_POST['penerbit']));
    $pengarang = mysqli_real_escape_string($conn, trim($_POST['pengarang']));
    $kategori = mysqli_real_escape_string($conn, trim($_POST['kategori']));
    $jumlah = mysqli_real_escape_string($conn, trim($_POST['jumlah']));
    $tahun = mysqli_real_escape_string($conn, trim($_POST['tahun']));

    $sampulDir = "../user/uploads/";
    $sampulPath = $sampulDir . basename($_FILES['sampul']['name']);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($sampulPath, PATHINFO_EXTENSION));
    $maxFileSize = 3 * 1024 * 1024; // 3MB

    // Check file type
    $allowedTypes = array('jpg', 'jpeg', 'png');
    if (!in_array($imageFileType, $allowedTypes)) {
        echo "Sorry, only JPG, JPEG, and PNG files are allowed.";
        $uploadOk = 0;
    }

    // Check file size
    if ($_FILES['sampul']['size'] > $maxFileSize) {
        echo "Sorry, your file is too large (max 1MB). Data will not be inserted.";
        $uploadOk = 0;
    }

    if ($uploadOk) {
        if (move_uploaded_file($_FILES["sampul"]["tmp_name"], $sampulPath)) {
            echo "The file " . htmlspecialchars(basename($_FILES["sampul"]["name"])) . " has been uploaded.";
            $relativePath = basename($_FILES['sampul']['name']);
        } else {
            echo "Sorry, there was an error uploading your file.";
            $relativePath = "";
        }
    } else {
        echo "File not uploaded due to errors.";
        $relativePath = "";
        exit(); // Stop execution if file is too large
    }

    $query = $conn->prepare("INSERT INTO buku (nomor_buku, judul_buku, penerbit, pengarang, kategori, jumlah, tahun, sampul) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $query->bind_param("sssssiis", $nomor, $judul, $penerbit, $pengarang, $kategori, $jumlah, $tahun, $relativePath);
    
    if ($query->execute()) {
        echo "Data inserted successfully.";
    } else {
        echo "Error: " . $query->error;
    }

    mysqli_close($conn);
    exit();
}

$query = "SELECT * FROM buku";
$result = mysqli_query($conn, $query);

if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}

mysqli_close($conn);
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buku</title>
    <link rel="icon" href="favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="style/global.css">
    <style>
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
                                    <a href="buku.php">Data Buku</a>
                                </div>';
    ?>

        <!-- Include the header.php file -->
        <?php include 'header.php'; ?>

        <!-- Konten -->
        <div class="content" id="bukuContent">
            <h1 style="font-size: 15px; position: absolute; left: 350px; top: 110px;">
                Dashboard > Buku
            </h1>


<form id="caribukuform" style="position: absolute; left: 681px; top: 184px;">
    <label for="search" style="position: relative; display: inline-block;">
        <input type="search" name="search" id="search" placeholder="Search ..."
             style="width: 300px; height: 32px; border-radius: 20px; 
           box-shadow: inset 0px 4px 4px rgba(0, 0, 0, 0.25);
           padding-left: 15px; /* Add padding to the left */">
        <img src="assets/global/iconsearch.png" alt="Search Icon"
            style="position: absolute; left: 210px; top: 50%; transform: translateY(-50%) scale(0.2); cursor:pointer">
    </label>
</form>



            <!-- "Tambah Buku" Button -->
            <button id="tambahbukubtn" onclick="openPopup()" style=" position: absolute; 
                        width: 130px;
                        height: 32px;
                        border-radius: 20px;
                        left: 1030px;
                        top: 184px;
                        background-color: #6499E9;
                        cursor: pointer;
                        color: white; /* Set text color to white */
                        transition: background-color 0.3s; /* Add transition for smooth color change */
                        " onmouseover="this.style.backgroundColor='#9eddff'"
                onmouseout="this.style.backgroundColor='#6499E9'">
                Tambah Buku
            </button>

            <!-- Popup Container -->
<div id="popupContainer" style="display: none; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); background-color: white; padding: 20px; border-radius: 29px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); z-index:5; height:580px; background-image: url('assets/dashboard/bg\ popup.jpg'); background-size: cover; background-repeat: no-repeat;">
    <!-- Popup Form Content -->
    <form id="popupForm" enctype="multipart/form-data" style="display: inline-grid; flex-direction: column; align-items: center;">
        <!-- Add your form fields here -->
        <label for="nomor" style="display:contents;">Nomor Buku</label>
        <input type="text" id="nomorInput" name="nomor" placeholder="Nomor Buku" required style="border: 2px solid #6499E9; background-color: #f2f2f2; border-radius:20px; width:410px; height:45px; margin-bottom: 10px;">

        <label for="judul">Judul Buku</label>
        <input type="text" id="judulInput" name="judul" placeholder="Judul Buku " required style="border: 2px solid #6499E9; background-color: #f2f2f2; border-radius:20px; width:410px; height:45px; margin-bottom: 10px;">

        <label for="penerbit">Penerbit</label>
        <input type="text" id="penerbitInput" name="penerbit" placeholder="Penerbit" required style="border: 2px solid #6499E9; background-color: #f2f2f2; border-radius:20px; width:410px; height:45px; margin-bottom: 10px;">

        <label for="pengarang">Pengarang</label>
        <input type="text" id="pengarangInput" name="pengarang" placeholder="Pengarang" required style="border: 2px solid #6499E9; background-color: #f2f2f2; border-radius:20px; width:410px; height:45px; margin-bottom: 10px;">

        <div style="display: flex; justify-content: space-between; width: 100%;">

            <!-- Category Dropdown -->
            <div style="flex: 1; margin-left: 8px; width: 40px;">
                <label for="kategori">Kategori</label>
                <select id="kategoriInput" name="kategori" required style="border: 2px solid #6499E9; background-color: #f2f2f2; border-radius: 20px; width: 193px; height: 45px; margin-bottom: 10px;">
                    <option value="pendidikan">Pendidikan</option>
                    <option value="fiksi">Fiksi</option>
                    <option value="nonfiksi">Nonfiksi</option>
                </select>
            </div>

            <div style="flex: 1; margin-left: 10px;">
                <label for="tahun">Tahun</label>
                <input type="text" id="tahunInput" name="tahun" placeholder="Tahun" required style="border: 2px solid #6499E9; background-color: #f2f2f2; border-radius:20px; height:45px;"><br>
            </div>
        </div>

        <div style="display: flex; justify-content: space-between; width: 430px;">
            <div style="flex: 1; margin-left: 8px; ">
                <label for="jumlah">Jumlah</label>
                <input type="text" id="jumlahInput" name="jumlah" placeholder="Jumlah" required style="border: 2px solid #6499E9; background-color: #f2f2f2; border-radius:20px; height:45px;"><br>
            </div>
            <div style="flex: 1; margin-left: 10px;">
                <label for="sampul">Sampul</label>
                <input type="file" id="sampulInput" name="sampul" accept="image/jpeg, image/png, image/jpg" style="width:193px; height:45px; margin-bottom: 10px;" required>
            </div>
        </div>

        <button type="submit" style="border: 2px solid #6499E9; width: 430px; height: 44px; background: #9EDDFF; box-shadow: 0px 4px 4px rgba(0, 0, 0, 0.25); border-radius: 20px;">Simpan</button>

        <button type="button" onclick="closePopup()" style="position: absolute; top: 10px; right: 10px; font-size: 20px; cursor: pointer;">X</button>
    </form>
</div>





            <!-- Table -->
            <table style="position: absolute;
    background-color: #9EDDFF;
    border-collapse: collapse;
    border-top-left-radius: 20px;
    border-top-right-radius: 20px;
    width: 838px;
    left: 350px;
    top: 280px;
    
    font-size: 20px;">
                <thead style="font-weight: bold;">
                    <tr>
                        <!-- Kolom "no" -->
                        <td style="text-align: center; padding: 10px;">No</td>

                        <!-- Kolom "sampul" -->
                        <td style="text-align: center; padding: 10px;">Sampul</td>

                        <!-- Kolom "judul" -->
                        <td style="text-align: center; padding: 10px;">Judul</td>

                        <!-- Kolom "nomor" -->
                        <td style="text-align: center; padding: 10px;">Nomor</td>

                        <!-- Kolom "kategori" -->
                        <td style="text-align: center; padding: 10px;">Kategori</td>

                        <!-- Kolom "jumlah" -->
                        <td style="text-align: center; padding: 10px;">Jumlah</td>

                        <!-- Kolom "aksi" -->
                        <td style="text-align: center; padding: 10px;">Aksi</td>
                    </tr>
                </thead>
                <tbody>
                    <?php
    // Display data in the table
    $count = 0;
    while ($row = mysqli_fetch_assoc($result)) {
        $count++;
        $background_color = $count % 2 === 0 ? '#9EDDFF' : '#F4F4F4';

        echo "<tr style='background-color: $background_color;'>
                <td style='text-align: center; padding: 10px;'>$count</td>
                <td style='text-align: center; padding: 10px;'><img src='../user/uploads/{$row['sampul']}' alt='Sampul Buku' style='width: 50px; border: none;'></td>
                <td style='text-align: center; padding: 10px;'>{$row['judul_buku']}</td>
                <td style='text-align: center; padding: 10px;'>{$row['nomor_buku']}</td>
                <td style='text-align: center; padding: 10px;'>{$row['kategori']}</td>
                <td style='text-align: center; padding: 10px;'>{$row['jumlah']}</td>
                <td style='text-align: center; padding: 10px;'>
<img src=\"assets/dashboard/iconedit.png\" alt=\"Edit\" style=\"cursor:pointer; transform: scale(0.7);\" onclick=\"openEditBukuPopup('{$row['id']}', '{$row['nomor_buku']}', '{$row['judul_buku']}', '{$row['penerbit']}', '{$row['pengarang']}', '{$row['kategori']}', '{$row['tahun']}', '{$row['jumlah']}', '{$row['sampul']}')\">
                    <a href='delete_buku.php?id={$row['id']}' onclick='return confirm(\"Apakah Anda yakin ingin menghapus buku ini?\")'>
                        <img src=\"assets/global/iconhapus.png\" alt=\"Hapus\" style=\"cursor:pointer; transform: scale(0.7);\">
                    </a>
                </td>
            </tr>";
    }
    ?>
                </tbody>




            </table>


           <!-- Popup Edit Buku -->
<div id="editBukuPopup"
    style="display: none; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); background-color: white; padding: 20px; border-radius: 29px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); z-index: 5; height: 580px; background-image: url('assets/dashboard/bg\ popup.jpg'); background-size: cover; background-repeat: no-repeat;">
    <form id="formEditBuku" action="update_buku.php" method="post" enctype="multipart/form-data"
        style="display: table-caption; flex-direction: column; align-items: center;">
        <input type="hidden" name="id" id="editId">
        <label for="editNomor">Nomor Buku</label>
        <input type="text" name="nomor" id="editNomor" placeholder="Nomor Buku" required
            style="border: 2px solid #6499E9; background-color: #f2f2f2; border-radius:20px; width:410px; height:45px; margin-bottom: 10px;"><br>
        <label for="editJudul">Judul Buku</label>
        <input type="text" name="judul" id="editJudul" placeholder="Judul Buku " required
            style="border: 2px solid #6499E9; background-color: #f2f2f2; border-radius:20px; width:410px; height:45px; margin-bottom: 10px;"><br>
        <label for="editPenerbit">Penerbit</label>
        <input type="text" name="penerbit" id="editPenerbit" placeholder="Penerbit" required
            style="border: 2px solid #6499E9; background-color: #f2f2f2; border-radius:20px; width:410px; height:45px; margin-bottom: 10px;"><br>
        <label for="editPengarang">Pengarang</label>
        <input type="text" name="pengarang" id="editPengarang" placeholder="Pengarang" required
            style="border: 2px solid #6499E9; background-color: #f2f2f2; border-radius:20px; width:410px; height:45px; margin-bottom: 10px;"><br>

        <div style="display: flex; justify-content: space-between; width: 100%;">
            <div style="flex: 1; margin-left: 12px;">
                <label for="editKategori">Kategori</label>
                <select id="editKategori" name="kategori" required
                    style="border: 2px solid #6499E9; background-color: #f2f2f2; border-radius:20px; width:193px; height:45px; margin-bottom: 10px;">
                    <option value="pendidikan">Pendidikan</option>
                    <option value="fiksi">Fiksi</option>
                    <option value="nonfiksi">Nonfiksi</option>
                </select><br>
            </div>
            <div style="flex: 1; margin-left: 10px;">
                <label for="editTahun">Tahun</label>
                <input type="text" id="editTahun" name="tahun" placeholder="Tahun" required
                    style="border: 2px solid #6499E9; background-color: #f2f2f2; border-radius:20px; width:193px; height:45px; margin-bottom: 10px;"><br>
            </div>
        </div>

        <div style="display: flex; justify-content: space-between; width: 100%;">
            <div style="flex: 1; margin-left: 12px;">
                <label for="editJumlah">Jumlah</label>
                <input type="text" id="editJumlah" name="jumlah" placeholder="Jumlah" required
                    style="border: 2px solid #6499E9; background-color: #f2f2f2; border-radius:20px; width:193px; height:45px; margin-bottom: 10px;"><br>
            </div>
            <div style="flex: 1; margin-left: 10px;">
                <label for="editSampul">Sampul</label>
                <input type="file" id="editSampul" name="sampul" accept="image/*"
                    style="width:193px; height:45px; margin-bottom: 10px;"><br>
            </div>
        </div>

        <button type="submit"
            style="border: 2px solid #6499E9; width: 430px; height: 40px; background: #9EDDFF; box-shadow: 0px 4px 4px rgba(0, 0, 0, 0.25); border-radius: 20px;">Simpan
            Perubahan</button>
        <button type="button" onclick="closeEditBukuPopup()"
            style="position: absolute; top: 10px; right: 10px; font-size: 20px; cursor: pointer;">X</button>
    </form>
</div>



<div id="alertContainer" style="display: none; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); background-color: #4CAF50; color: white; padding: 15px; border-radius: 10px; z-index: 15;">
    <!-- Alert message will be displayed here -->
</div>



            <!-- Popup -->
            <?php include 'popup.php'; ?>
        </div>
        
        <div id="customPopup">
                    <div id="customPopupContent"></div>
                </div>
        </div>

        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

        <script src=" https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js "></script>
        <script src=" https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js "></script>
        <script src=" script/global.js">
        </script>
        <script>
        function openPopup() {
            // Show the popup container
            document.getElementById('popupContainer').style.display = 'block';
        }

        function closePopup() {
            // Hide the popup container
            document.getElementById('popupContainer').style.display = 'none';
        }
        </script>


        <!-- ... (Your existing HTML code) ... -->
        <script>
        let rowCount = 0;

        document.getElementById('popupForm').addEventListener('submit', function(event) {
            event.preventDefault();

            // Get form data
            var formData = new FormData(this);

            // Use AJAX to submit the form data to the server
            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'buku.php', true);
            xhr.onload = function() {
                if (xhr.status === 200) {
                    openCustomPopup('Buku Berhasil Ditambahkan');
                            setTimeout(function() {
                                location.reload();
                            }, 1000);
                } else {
                    // Error
                    alert('Error: ' + xhr.status);
                }
            };
            xhr.send(formData);
        });
        </script>

        <script>
        function delete_buku(button) {
            // Get the book number from the data-nomor attribute
            var nomor = button.getAttribute('data-nomor');

            // Confirm deletion
            var confirmDelete = confirm('Apakah Anda yakin ingin menghapus buku ini?');

            if (confirmDelete) {
                // Use AJAX to submit the form data to the server
                var xhr = new XMLHttpRequest();
                xhr.open('POST', 'delete_buku.php', true);
                xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

                // Set up the data to be sent
                var data = 'nomor=' + encodeURIComponent(nomor);

                xhr.onload = function() {
                    if (xhr.status === 200) {
                        openCustomPopup('Akun Siswa Berhasil Ditambahkan');
                            setTimeout(function() {
                                location.reload();
                            }, 1000);
                    } else {
                        // Error
                        alert('Error: ' + xhr.status);
                    }
                };
                xhr.send(data);
            }
        }

        function openEditBukuPopup(id, nomor, judul, penerbit, pengarang, kategori, tahun,
        jumlah /*, field lainnya*/ ) {
            document.getElementById('editId').value = id;
            document.getElementById('editNomor').value = nomor;
            document.getElementById('editJudul').value = judul;
            document.getElementById('editPenerbit').value = penerbit;
            document.getElementById('editPengarang').value = pengarang;
            document.getElementById('editKategori').value = kategori;
            document.getElementById('editTahun').value = tahun;
            document.getElementById('editJumlah').value = jumlah;


            document.getElementById('editBukuPopup').style.display = 'block';
        }

        function closeEditBukuPopup() {
            document.getElementById('editBukuPopup').style.display = 'none';
        }
        </script>
        
        <script>
$(document).ready(function() {
    $('#search').keyup(function() {
        var searchTerm = $(this).val();
        if (searchTerm != '') {
            // Jika search term tidak kosong, cari berdasarkan term tersebut
            $.ajax({
                url: 'search_buku.php',
                type: 'GET',
                data: {term: searchTerm},
                success: function(response) {
                    $('tbody').html(response);
                }
            });
        } else {
            // Jika search term kosong, muat semua data
            $.ajax({
                url: 'load_all_buku.php', // Anda bisa membuat script PHP terpisah atau gunakan script yang sama dengan kondisi
                type: 'GET',
                success: function(response) {
                    $('tbody').html(response);
                }
            });
        }
    });
});

</script>

<script>
    function openCustomPopup(message) {
                document.getElementById('customPopupContent').innerHTML = message;
                document.getElementById('customPopup').style.display = 'block';
            }

            function closeCustomPopup() {
                document.getElementById('customPopup').style.display = 'none';
            }
</script>

<script>
document.getElementById('popupForm').addEventListener('submit', function(event) {
    event.preventDefault();

    // Get form data
    var formData = new FormData(this);

    // Use AJAX to submit the form data to the server
    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'buku.php', true);
    xhr.onload = function() {
        if (xhr.status === 200) {
            // Display success message
            displayAlert('Buku Berhasil Ditambahkan');

            // Refresh the page after a delay
            setTimeout(function() {
                location.reload();
            }, 1000);
        } else {
            // Display error message
            displayAlert('Error: ' + xhr.status, true);
        }
    };
    xhr.send(formData);
});

// Function to display the alert
function displayAlert(message, isError = false) {
    var alertContainer = document.getElementById('alertContainer');
    alertContainer.innerHTML = message;
    alertContainer.style.backgroundColor = isError ? '#FF0000' : '#4CAF50';
    alertContainer.style.display = 'block';

    // Hide the alert after 3 seconds
    setTimeout(function() {
        alertContainer.style.display = 'none';
    }, 3000);
}
</script>

<script>
function delete_buku(button) {
    // Get the book number from the data-nomor attribute
    var nomor = button.getAttribute('data-nomor');

    // Confirm deletion
    var confirmDelete = confirm('Apakah Anda yakin ingin menghapus buku ini?');

    if (confirmDelete) {
        // Use AJAX to submit the form data to the server
        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'delete_buku.php', true);
        xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

        // Set up the data to be sent
        var data = 'nomor=' + encodeURIComponent(nomor);

        xhr.onload = function() {
            if (xhr.status === 200) {
                openCustomPopup('Buku Berhasil Dihapus');
                setTimeout(function() {
                    location.reload();
                }, 1000);
            } else {
                // Error
                alert('Error: ' + xhr.status);
            }
        };
        xhr.send(data);
    }
}
</script>





        <!-- ... (Your existing JavaScript code) ... -->




</body>

</html>