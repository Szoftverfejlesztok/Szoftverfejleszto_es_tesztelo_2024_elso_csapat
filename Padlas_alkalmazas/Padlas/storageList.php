<?php
ini_set('display_errors', '0');
ini_set('display_startup_errors', '0');
error_reporting(0);
session_start();


if (!isset($_SESSION["username"])) {
    header("location:index.php");
    }
require_once ("model/config.php");
require_once ("model/database.php");
require_once ("model/storage.php");
require ("controller/error.php");

// A BEJELENTKEZÉS ELVÉGZÉSÉHEZ AZ ALÁBBI VÁLTOZÓKRA LESZ SZÜKSÉGEM:
$config = new Config("config/config.json");
$dbconn = new Database($config);
$storage = new Storage($dbconn->getConnection());

//MIELŐTT MÉG BETÖLTENÉNK A NÉZETET, ELŐTTE LEKÉRDEZZÜK A TÁROLÓKAT ÉS A BENNÜK LÉVŐ TERMÉKEK DARABSZÁMÁT ÍGY: 
$storageArray = $storage->storageLoad(); // Itt hívom a függvényt, amiben a lekérdezés van. 


// Ellenőrizzük, hogy az AJAX kérés történt-e: - ez ide már nem is kell! 
/*if ($_SERVER["REQUEST_METHOD"] == "GET") {
    // Itt futtatjuk azt a függvényt, ami létrehozza 
    $storage->create_new_storage();
}*/
?>





<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="style/style.css">
    <link rel="stylesheet" href="style/header.css">
    <link rel="stylesheet" href="style/searchStyle.css">
    <link rel="stylesheet" href="style/footer.css">
    <title>Tárolók listája és új tároló létrehozása</title>
</head>

<body>
    <?php
    require_once ("view/header.html");
    require_once ("view/storageListView.php"); // itt jelenítjük meg a lekérdezés eredményét.
    require_once ("view/footer.html");
    ?>
</body>

</html>