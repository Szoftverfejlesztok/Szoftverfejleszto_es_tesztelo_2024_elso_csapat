<?php
ini_set('display_errors', '0');
ini_set('display_startup_errors', '0');
error_reporting(0);
//session_start();

/*
if (!isset($_SESSION["username"])) {
    header("location:index.php");
    }
  */
/*Ha nincs még regisztrálva a felhasználó és az index.html(bejelentkezés oldalon) a regisztrál gombra kattint, akkor ide fogja hozni
Itt meg kell jeleníteni a regisztrációs formot.
validateNewUsr controllernek el kell küldenia  formmal bekért adatokat, ami ellenőrzi és a modális ablakkal vissza kell jelezni, hogy a regisztrációs sikeres,
majd vissza kell dobni a bejelentkezés oldalra, ahol be tud jelentkezni.*/
error_reporting(0);
ini_set("display_error",1);

//MODEL BEEMELÉSE

require_once("model/config.php"); // beemeljük a model config.php-t - ez az 1., mert először szükségem van azadatbázis eléréséhez szükséges adatokra.
require_once("model/database.php"); // beemeljük a model database.php-t - ez a 2., ert ezzel hozom létre azadatbázis kapcsolatot. 
require_once("model/user.php"); // beemeljük a topic.php-t - ez a 3., mert már megvan az adatbázis kapcsolatom és ezzel tudom a témákat lekérdezni.
require("controller/error.php");

//REGISZTRÁCIÓHOZ AZ ALÁBI VÁLOTZÓKRA LESZ SZÜKSÉG: 
$apperror = new AppError();
$config = new Config("config/config.json"); 
$dbconn = new Database($config);
$user = new User($dbconn->getConnection());

// ÚJ FELHASZNÁLÓ ELKÜLDÉSE AZ ADATBÁZISNAK:
if(isset($_POST["submitRegistration"])){
    try{
    require("controller/validateRegistration.php");
    }
    catch(Exception $e){
      $type="Regisztrációs hiba!";
      $msg=$e->getMessage();
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
    <link rel="stylesheet" href="style/style.css">
    <link rel ="stylesheet" href="style/loginStyle.css">
    <title>Regisztráció</title>
</head>
<body>
<?php
if(!empty($msg)){
  $apperror = new AppError();
  $apperror ->ShowModal($type, $msg);
  $apperror ->PutLog($msg); 
}


require_once("view/registrationForm.php");
require_once("view/footer.html");
?>
</body>

</html>