<?php
ini_set('display_errors', '0');
ini_set('display_startup_errors', '0');
error_reporting(0);
session_start();


if (!isset($_SESSION["username"])) {
    header("location:index.php");
    }
/*Ez az oldal a profil oldal vezérlője, így ide kell beemelni minden profil oldallal kapcsolatos elemet
pl.: 1.felhasználói adatok módosítása form
2.ha a felhasználó admin jogosultságú, akkor jelenjen meg a 2. felhasználó törlése form
3.kijelentkezés gomb*/
//$logoff = ($_GET["submit"]);
if (!empty($_GET["submit"])){
    unset($_SESSION["user"]);
    session_destroy();
    //exit(header('Location:index.php'));
    ?><script><?php echo "location.href = 'index.php';";?></script>
    <?php
}


//MODEL BEEMELÉSE

require_once("model/config.php"); // beemeljük a model config.php-t - ez az 1., mert először szükségem van azadatbázis eléréséhez szükséges adatokra.
require_once("model/database.php"); // beemeljük a model database.php-t - ez a 2., ert ezzel hozom létre azadatbázis kapcsolatot. 
require_once("model/user.php"); // beemeljük a topic.php-t - ez a 3., mert már megvan az adatbázis kapcsolatom és ezzel tudom a témákat lekérdezni.
require("controller/error.php");

//FELHASZNÁLÓI ADATOK MÓDOSÍTÁSÁHOZ AZ ALÁBBI VÁLOTZÓKRA LESZ SZÜKSÉG: 
//$apperror = new AppError();
$config = new Config("config/config.json"); 
$dbconn = new Database($config);
$user = new User($dbconn->getConnection());

//Létrehozok egy $userModify változót
$userModify;


// HA A FELHASZNÁLÓNÉV MÓDOSUL:
if(isset($_POST["submitModifyUserName"])){
  try{
  $userModify = $user->modifyUserName($_POST['username'], $_POST['email']);
  }catch(Exception $e){
    $type="Mentési hiba!";
    $msg=$e->getMessage();
  }

}


//HA A JELSZÓ MÓDOSUL: 

if(isset($_POST["submitModifyPassword"])){
    try{
    $userModify = $user->modifyUserPassword( $_POST['password_old'], $_POST['password'], $_POST['password2'], $_POST['useremail'] );
   /* $_SESSION["user"] = array("felhasznalonev"=>$userLogin["username"], "fullname"=>$userLogin["fullname"], "id"=>$userLogin["id"], "moderator"=>$userLogin["moderator"]); // itt megítjuk a session-t, hogy milyen adatokat adjon vissza
    $_SESSION['username'] = $_POST['username'];
    $_SESSION['password'] = $_POST['password'];
    //létrehozok egy sessiont, aminek változója a "user" és a session user változójába berakok egy tömböt, mely tömb a felhasznalo2 változóba tárolt bejelentkezési adatokat adja vissza. 
    //setcookie("id", $felhasznalo2["id"],time()+60*3); // az első paraméter a cokkie neve - ez az "id", a 2. paraméter, mi az érték, amit el akarunk térolni a cokkie-ban, 3., hogy mikor jár le. 4. site-nak melyik részére érvényes a cookie.
    //$msg = "Sikeres bejelentkezés ".$felhasznalo2["valodi_nev"];
    header("location:padlasMainPage.php"); // itt átirányítom a bejelentkezett oldalra a felhasználót. a header utasítást csak akkor lehet használni,ha nincs semmilyen kiírás az oldalon a header utasítás előtt, tehát a html5-ös törzs elé kell rakni., */
  
    }catch(Exception $e){
      $type="Mentési hiba!";
      $msg=$e->getMessage();
    }
  
  }

// HA KEZDEMÉNYEZIK A FELHASZNÁLÓ TÖRLÉSÉT
if(isset($_POST["submitDeleteUser"])){
  echo 'almafa';
  try{
      
      if($deleteUser = $user->deleteUser(($_SESSION['username']))){
        // Szakítsuk meg a session-t
        session_unset();
        session_destroy();

        // Irányítsuk az index.php-ra
        header("location:index.php");
        exit(); // Biztonságos leállás
      };
      
    }catch(Exception $e){
        $type="Törlési hiba!";
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
    <link rel="stylesheet" href="style/loginStyle.css">
    <link rel="stylesheet" href="style/header.css">
    <title>Felhasználói profil</title>
</head>
<body>

<?php //A KIJELENTKEZÉS NEM JÓ, MERT HA A PROFIL MENÜPONTBAN A "FELHASZNÁLÓI ADATOK MÓDOSÍTÁSA" MENÜPONTRA KATTINT, AKKOR
// IS ÁTVISZ A RENDSZER A BEJELENTKEZÉS OLDALRA, PEDIG NEM KELLENE. eZÉRT VAN ITT EZ A RÉSZ KIKOMMENTELVE. 
//JAVÍTANI KELL!!!

    
    
    //$hossz = strlen($logoff);
    //echo '<script>alert("'.$hossz.'")</script>';
    if(!empty($msg)){
        $apperror = new AppError();
        $apperror ->ShowModal($type, $msg);
        $apperror ->PutLog($msg); 
      }

    require_once("view/header.html");
    require_once("view/modifyUserDataForm.php");
    require_once("view/footer.html");   

?>
</body>
</html>