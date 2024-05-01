<?php
ini_set('display_errors', '0');
ini_set('display_startup_errors', '0');
error_reporting(0);
session_start();

// MODEL BEEMELÉSE A KEZDŐOLDALRA:

require_once ("model/config.php");
require_once ("model/database.php");
require_once ("model/user.php");
require ("controller/error.php");


// A BEJELENTKEZÉS ELVÉGZÉSÉHEZ AZ ALÁBBI VÁLTOZÓKRA LESZ SZÜKSÉGEM:

$apperror = new AppError();
$config = new Config("config/config.json");
$dbconn = new Database($config);
$user = new User($dbconn->getConnection());

//Létrehozok egy $userLogin változót
$userLogin;

// HA A FELHASZNÁLÓ MEGADTA AZ ADATAIT, AKKOR AZT ELKÜLDÖM AZ ADATBÁZISNAK ÍGY:

if (isset($_POST["submitLogin"])) {
  try {
    $userLogin = $user->getLoginUser($_POST['username'], $_POST['password']);
    $_SESSION["user"] = array("felhasznalonev" => $userLogin["username"], "fullname" => $userLogin["fullname"], "id" => $userLogin["id"], "moderator" => $userLogin["moderator"]); // itt megítjuk a session-t, hogy milyen adatokat adjon vissza
    $_SESSION['username'] = $_POST['username'];
    $_SESSION['password'] = $_POST['password'];
    //létrehozok egy sessiont, aminek változója a "user" és a session user változójába berakok egy tömböt, mely tömb a felhasznalo2 változóba tárolt bejelentkezési adatokat adja vissza. 
    //setcookie("id", $felhasznalo2["id"],time()+60*3); // az első paraméter a cokkie neve - ez az "id", a 2. paraméter, mi az érték, amit el akarunk térolni a cokkie-ban, 3., hogy mikor jár le. 4. site-nak melyik részére érvényes a cookie.
    //$msg = "Sikeres bejelentkezés ".$felhasznalo2["valodi_nev"];
    header("location:padlasMainPage.php"); // itt átirányítom a bejelentkezett oldalra a felhasználót. a header utasítást csak akkor lehet használni,ha nincs semmilyen kiírás az oldalon a header utasítás előtt, tehát a html5-ös törzs elé kell rakni., 

  } catch (Exception $e) {
    $type = "Bejelentkezési hiba!";
    $msg = $e->getMessage();
  }

}


// Ellenőrizzük, hogy az űrlap elküldte-e az adatokat
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Ellenőrizd, hogy minden szükséges adat megérkezett-e
  if (isset($_POST['username']) && isset($_POST['password'])) {
    // Elmentjük a felhasználónevet és a jelszót külön változókba
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Itt használhatod a $username és $password változókat további feldolgozásra vagy validációra
  } else {
    // Ha valamelyik adat hiányzik, akkor kezeld le ezt a helyzetet
    echo "Hiányzó adat(ok) az űrlapról!";
  }
}







?>







<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <link rel="stylesheet" href="style/loginStyle.css">
  <link rel="stylesheet" href="style/style.css">
  <link rel="stylesheet" href="style/header.css">
  <link rel="stylesheet" href="style/footer.css">

  <title>Bejelentkezés</title>
</head>

<body>
  <?php

  if (!empty($msg)) {
    $apperror = new AppError();
    $apperror->ShowModal($type, $msg);
    $apperror->PutLog($msg);
  }

  require_once ("view/loginForm.php");
  require_once ("view/footer.html");
  ?>

</body>


</html>