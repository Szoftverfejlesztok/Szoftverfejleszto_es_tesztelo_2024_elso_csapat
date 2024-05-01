<?php
ini_set('display_errors', '0');
ini_set('display_startup_errors', '0');
error_reporting(0);
?>
<!--Ide írom meg az összes oldalon megjelenű menüt. Ebbe kell beemelni az egyes menüelemeket külön-külön.
Pl.: Bejelentkezés/regisztrációs form, Felhasználó , menüpontok-->
<div id="toolbarTop" class="container-fluid p-2">
    <div class="flex-container">
        <?php
        require_once("view/header.html"); // beemelem a keresést
        ?>
    </div>
</div>