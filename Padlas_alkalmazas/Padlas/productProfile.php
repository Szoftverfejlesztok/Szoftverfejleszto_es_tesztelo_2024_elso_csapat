<?php
ini_set('display_errors', '0');
ini_set('display_startup_errors', '0');
error_reporting(0);
session_start();


if (!isset($_SESSION["username"])) {
    header("location:index.php");
    }
/*Ez a rész tartalmazza a már létező terméknek az adatlapját:
azaz ha már a találati listában rákattintott a termék nevére (linkjére), akkor nyíljon meg a termék adatlap egy új ablakban.
Ide be kell majd emelni az egyes view elemeket: 
pl.: header, footer, menu, a createproductprofile form , de itt alapértelmezetten jelenjenek meg az alapadatai a termékeknek
a  model mappában lévő product.php-ba meg kell írni a termékadatok update-jét.*/
require_once ("View/header.html");
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
    <title>Termék adatlapja</title>
</head>

<body>

</body>
<?php
require_once ("view/footer.html");
?>

</html>