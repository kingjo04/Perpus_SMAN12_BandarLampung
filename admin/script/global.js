document.getElementById("logo").addEventListener("click", function () {
  // Redirect to Home.php
  window.location.href = "../index.php";
});

function togglePopup(popupId) {
  var popup = document.getElementById(popupId);
  popup.style.display = popup.style.display === "flex" ? "none" : "flex";
}

function closePopup() {
  var popup = document.getElementById("popup");
  popup.style.display = "none";
}

function logout() {
  // Add your logout logic here
  alert("Anda Telah Logout");

  // Hapus session dan redirect ke halaman login
  window.location.href = "../backend/logout.php";
}
