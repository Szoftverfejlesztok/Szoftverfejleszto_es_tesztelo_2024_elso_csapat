<?php
ini_set('display_errors', '0');
ini_set('display_startup_errors', '0');
error_reporting(0);
session_start();
$username=$_SESSION['username'];

if (!isset($_SESSION["username"])) {
    header("location:index.php");
    }
require_once ("view/header.html");


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
    <title>Padlás főoldal</title>
</head>

<body>
    <form id="createNewProductForm" class = "container text-center searchform">
        <div id="welcome">
            <h1 class="mainpagetext p-3">Kedves <?php echo $username ?>!<h1>
            <h2 class="mainpagetext p-3">Üdvözünk a Padlás alkalmazásban!<h2>
            <h2 class=" mainpagetext p-3">Kérjük, válassz a fenti menüpontok közül!<h2>
        </div>
    </form>
    

    <?php
    require_once ("view/footer.html");
    ?>

</html>