<!-- Header -->
<div class="header">
    <?php
        // Check if $customHeaderContent is set before echoing it
        if (isset($customHeaderContent)) {
            echo $customHeaderContent;
        }
    ?>

    <div style="position: relative; right: 50px;">
        <img src="assets/dashboard/maleprof.png" alt="" class="nav-icon" style="scale: 0.5; cursor: pointer;"
            onclick="window.location.href='pengaturan.php';">
    </div>


    <span style="color: #FFFFFF; position: relative; right: 50px;">Admin</span>

    <div class="panah bawah" id="popupTrigger">
        <img src="assets/dashboard/panahbawah.png" alt="panah" onclick="togglePopup('popupkeluar')"
            style="scale: 0.5; cursor: pointer;">
    </div>
</div>