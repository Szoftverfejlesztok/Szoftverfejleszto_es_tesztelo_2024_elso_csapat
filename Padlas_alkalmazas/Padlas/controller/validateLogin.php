<!--Ennek a fájlnak az a feladata, hogy a bejelentkezés fomr-mal (loginForm.html-el bekért adatokat) ellenőrizze és beleírja egy változóba. 
Illetve,ha sikeres a bejelentkezés, akkor a program 
1. vigyen át a searchProduct-php-ra
2. a menüben jelenjen meg a felhasználói profil menüpont.-->
<?php
ini_set('display_errors', '0');
ini_set('display_startup_errors', '0');
error_reporting(0);
//$felhasznalonev = trim($_POST["username"]);
//$jelszo = trim($_POST["password"]);

//$user->getLoginUser($felhasznalonev, $jelszo);
   


/*$error = ""; 
$msg = ""; 

class userException extends Exception{}
require_once("database.php");
session_start();*/

/*if(isset($_POST["submitLogin"]) && !empty($conn))
{
    /*try
    {
        $felhasznalonev = trim($_POST["username"]);
        $jelszo = trim($_POST["password"]);
        if(empty($felhasznalonev) || empty($jelszo))
        {
            throw new userException("Minden adatot kötelező megadni!");
        }

        $sqlLogin = "SELECT id,username,jelszo,fullname,moderator FROM felhasznalok WHERE jelszo=:jelszo";
        $queryLogin = $dbconn->prepare($sqlLogin); //lekérdezés előkésztíése
        $queryLogin->bindValue("jelszo", $jelszo, PDO::PARAM_STR); // a lekérdezéshez hozzáadjuk az adatokat, hogy melyik jelszóhoz tartozó felhasználói adatokat kérem le az adatbázisból
        $queryLogin->execute();
        if($queryLogin->rowCount() != 1){
            throw new userException("Hibás felhasználói azonosító");
        }
        $felhasznalo2 = $queryLogin->fetch(PDO::FETCH_ASSOC); // a fetch-el olvasom ki az adatbázisnak az egy sorát és ezt belerakom a felhazsnalo2 neve változóba asszociatív tömbként. 
        if(password_verify($jelszo,$felhasznalo["jelszo"]))
        {
            throw new userException("Hibás jelszó.");
        }


        $_SESSION["user"] = array("felhasznalonev"=>$felhasznalo2["username"], "fullname"=>$felhasznalo2["fullname"], "id"=>$felhasznalo2["id"], "moderator"=>$felhasznalo2["moderator"]); // itt megítjuk a session-t, hogy milyen adatokat adjon vissza
        //létrehozok egy sessiont, aminek változója a "user" és a session user változójába berakok egy tömböt, mely tömb a felhasznalo2 változóba tárolt bejelentkezési adatokat adja vissza. 

        setcookie("id", $felhasznalo2["id"],time()+60*3); // az első paraméter a cokkie neve - ez az "id", a 2. paraméter, mi az érték, amit el akarunk térolni a cokkie-ban, 3., hogy mikor jár le. 4. site-nak melyik részére érvényes a cookie


        //$msg = "Sikeres bejelentkezés ".$felhasznalo2["valodi_nev"];
        header("location:searchProduct.php"); // itt átirányítom a bejelentkezett oldalra (profil.php) a felhasználót. a header utasítást csak akkor lehet használni,ha nincs semmilyen kiírás az oldalon a header utasítás előtt, tehát a html5-ös törzs elé kell rakni., 
    }
    catch(userException $e)
    {
        $error = "Regisztrációs hiba: ".$e->getMessage();
    }
    catch(PDOException $e)
    {
        $error = "Adatbázis mentési hiba: ".$e->getMessage();
    }*/
//}






?>
