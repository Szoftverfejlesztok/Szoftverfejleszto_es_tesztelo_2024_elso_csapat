<?php
ini_set('display_errors', '0');
ini_set('display_startup_errors', '0');
error_reporting(0);
//session_start();
/* MINDEN FELHASZNÁLÓKKAL KAPCSLATOS SQL UTASÍTÁST IDE KELL ÍRNI: 
 fELHASZNÁLÓK LEKÉRDEZÉSÉT ÉS ÚJ FELHASZNÁLÓK LÉTREHOZÁSÁHOZ, MEGLÉVŐ FELHASZNÁLÓ MÓDOSÍTÁSÁHOZ,
 VAGY FELHASZNÁLÓ TÖRLÉSÉHEZ AZ SQL UTASÍTÁSOKAT IDE ÍRJUK MEG KÜLÖN-KÜLÖN FÜGGVÉNYBEN! 
 
 1. új felhasználó létrehozása - a registration.php a vezérlő és a registrationForm-on kérjük be az adatoat, amiket elmentünk (insert) az adatbázisba.
 2. felhasználó törlése - kell hozzá egy form, aminek a select-jeibe belegeneráljuk a meglévő felhasználókat és íhy kiválaszthatja a meglévő felhasznáók közül azt, akit törölni akar. 
 3. felhasználói adatok módosítása (update) - modifyUserDataForm-mal bekért adatokat update-tel küldjük az adatbázsnak
 4. Bejelentkezé*/



 class User{

   
    private $dbconn;

    public function __construct($db){ // a topic osztály konstruktora,ami egy paraméteres konstruktor.
        if (empty($db)){

          throw new Exception("Hibás adatbázis kapcsolat");

        }
        $this->dbconn = $db;
    }
     

    // A BEJELENTKEZÉS OLDALON KÉRDEZI LE A FELHASZNÁLÓ ADATAIT.
    public function getLoginUser($felhasznalonev, $jelszo){
        try{
            $felhasznalonev = trim($_POST["username"]);
            $jelszo = trim($_POST["password"]);
            if(empty($felhasznalonev) || empty($jelszo))
            {
                throw new Exception("Minden adatot kötelező megadni!"); 
                
            }

            $sqlLogin = "SELECT id,username,jelszo,email FROM felhasznalok WHERE username=:felhasznalonev";
            //$sqlLogin = "SELECT COUNT(username) FROM felhasznalok WHERE username=".$felhasznalonev;
            $queryLogin = $this->dbconn->prepare($sqlLogin); //lekérdezés előkésztíése
            $queryLogin->bindValue("felhasznalonev", $felhasznalonev, PDO::PARAM_STR); // a lekérdezéshez hozzáadjuk az adatokat, hogy melyik jelszóhoz tartozó felhasználói adatokat kérem le az adatbázisból
            // $queryLogin->bindValue("jelszo", $jelszo, PDO::PARAM_STR);
            $queryLogin->execute();
            if($queryLogin->rowCount() != 1){

                throw new Exception("Hibás felhasználói azonosító! Kérem, adja meg a helyes azonosítót!"); 
            
                
            }
            
            $felhasznalo2 = $queryLogin->fetch(PDO::FETCH_ASSOC); // a fetch-el olvasom ki az adatbázisnak az egy sorát és ezt belerakom a felhazsnalo2 nevű változóba asszociatív tömbként. 
            
            if (!password_verify($jelszo,$felhasznalo2["jelszo"]))
            {

                throw new Exception("Hibás jelszó! Kérem, adja meg újra a jelszavát!"); 
            }
            
            //$_SESSION["user"] = array("felhasznalonev"=>$felhasznalo2["username"], "fullname"=>$felhasznalo2["fullname"], "id"=>$felhasznalo2["id"], "moderator"=>$felhasznalo2["moderator"]); // itt megítjuk a session-t, hogy milyen adatokat adjon vissza
            //létrehozok egy sessiont, aminek változója a "user" és a session user változójába berakok egy tömböt, mely tömb a felhasznalo2 változóba tárolt bejelentkezési adatokat adja vissza. 

            //setcookie("id", $felhasznalo2["id"],time()+60*3); // az első paraméter a cokkie neve - ez az "id", a 2. paraméter, mi az érték, amit el akarunk térolni a cokkie-ban, 3., hogy mikor jár le. 4. site-nak melyik részére érvényes a cookie


            //$msg = "Sikeres bejelentkezés ".$felhasznalo2["valodi_nev"];
            $_SESSION['user_email'] = $felhasznalo2["email"];
            header("location:padlasMainPage.php"); // itt átirányítom a bejelentkezett oldalra a felhasználót. a header utasítást csak akkor lehet használni,ha nincs semmilyen kiírás az oldalon a header utasítás előtt, tehát a html5-ös törzs elé kell rakni., 


        }
        catch(PDOException $e)
        {
            $msg="Sikertelen lekérdezés: ".$e->getMessage();
        }
    }

    

    // ÚJ FELHASZNÁLÓ MENTÉSE ADATBÁZISBA

    public function newUserRegistration($felhasznalonev, $email, $jelszo, $jelszo2){
        
        try{            
            $felhasznalonev = trim($_POST["username"]);
            $jelszo = trim($_POST["password"]);
            $jelszo2 = trim($_POST["password2"]);
            $email = trim($_POST["email"]);            
            if(empty($felhasznalonev) || empty($jelszo) || empty($jelszo2) || empty($email))
            {                
                    
                throw new Exception("Minden adatot kötelező megadni! Kérem, adja meg az összes adatot!"); 
                
            }
                        
            if( $jelszo != $jelszo2)
            {                                
                throw new Exception("A két jelszó nem egyezik! Kérem, adja meg újra a jelszavát!");
            }

            try {
                // Ellenőrizzük, hogy az email cím már létezik-e az adatbázisban
                $query_check_email = $this->dbconn->prepare("SELECT COUNT(*) as count FROM felhasznalok WHERE email = :email");
                $query_check_email->execute(array("email" => $email));
                $row = $query_check_email->fetch(PDO::FETCH_ASSOC);
        
                if ($row['count'] > 0) {
                    // Az email cím már szerepel az adatbázisban
                    throw new Exception("A megadott email cím már szerepel az adatbázisban! Kérlek, adj meg új email címet!"); 
                    return true;
                } else {
                    // Az email cím még nem szerepel az adatbázisban
                    $hash = password_hash($jelszo,PASSWORD_DEFAULT);//Ezzel lehet titkosítani a jelszót. 
                    $sqlReg = "INSERT INTO felhasznalok (username,jelszo,email) VALUES (:username,:jelszo,:email)";
                    $queryReg = $this->dbconn->prepare($sqlReg);           
                    $queryReg->execute(array("username"=>$felhasznalonev,"jelszo"=>$hash,"email"=>$email));           
                    header("location:index.php");
                    return false;
                }
            } catch (PDOException $e) {
                // Hiba kezelése, ha a lekérdezés nem sikerült
                $msg = "Hiba a lekérdezés során: " . $e->getMessage();
                return false;
            }      
        }
        catch(PDOException $e)
        {            
            $msg="Sikertelen mentés! ".$e->getMessage();
            return false;
        }
        return true;
    }

    
    /*public function newUserRegistration($felhasznalonev, $email, $jelszo, $jelszo2){
        
        try{            
            $felhasznalonev = trim($_POST["username"]);
            $jelszo = trim($_POST["password"]);
            $jelszo2 = trim($_POST["password2"]);
            $email = trim($_POST["email"]);            
            if(empty($felhasznalonev) || empty($jelszo) || empty($jelszo2) || empty($email))
            {                
                    
                throw new Exception("Minden adatot kötelező megadni! Kérem, adja meg az összes adatot!"); 
                
            }
                        
            if( $jelszo != $jelszo2)
            {                                
                throw new Exception("A két jelszó nem egyezik! Kérem, adja meg újra a jelszavát!");
            }
            
            $hash = password_hash($jelszo,PASSWORD_DEFAULT);//Ezzel lehet titkosítani a jelszót. 
            $sqlReg = "INSERT INTO felhasznalok (username,jelszo,email) VALUES (:username,:jelszo,:email)";
            $queryReg = $this->dbconn->prepare($sqlReg);           
            
            $queryReg->execute(array("username"=>$felhasznalonev,"jelszo"=>$hash,"email"=>$email));           
            
            header("location:index.php");
            
            
        }
        catch(PDOException $e)
        {            
            $msg="Sikertelen mentés! ".$e->getMessage();
            return false;
        }
        return true;
    }*/

    
    

      // FELHASZNÁLÓNÉV MÓDOSÍTÁSA
    
      public function modifyUserName($username, $email) {


        try{

            $username = trim($_GET["username"]);
            $email = trim($_GET["useremail"]);
            

            // Ellenőrizzük, hogy mindegyik paraméter meg van adva
            if (empty($username) || empty($email)) {
                throw new Exception("Minden adatot kötelező megadni! Kérem, adja meg az összes adatot!");
            }

            $updateSql = "UPDATE felhasznalok SET username = :username WHERE email  = :email";
            $updateQuery = $this->dbconn->prepare($updateSql);
            $updateQuery->execute(array(
                ":username" => $username,
                ":email" => $email
            ));
        }catch(PDOException $e){
            $msg="Sikertelen mentés! ".$e->getMessage();
            return false;
        }

        // Sikeres módosítás esetén visszatérünk true-val
        return true;
        
    }

    //JELSZÓ MÓDOSÍTÁSA

    public function modifyUserPassword( $password_old, $password, $password2, $useremail){

        $password_old = trim($_POST["password_old"]);
        $password = trim($_POST["password"]);
        $password2 = trim($_POST["password2"]);
        $useremail = trim($_POST["useremail"]);

        if (empty($password_old) || empty($password) || empty($password2) || empty($useremail)) {
            throw new Exception("Minden adatot kötelező megadni! Kérem, adja meg az összes adatot!");
        }

        // Ellenőrizzük, hogy a két új jelszó megegyezik-e
        if ($password != $password2) {
            throw new Exception("A két új jelszó nem egyezik! Kérem, adja meg újra a jelszavát!");
        }

        // Ellenőrizzük, hogy a régi jelszó egyezik-e az adatbázisban tárolttal
        $sql = "SELECT jelszo FROM felhasznalok WHERE email = :useremail";
        $query = $this->dbconn->prepare($sql);
        $query->execute(array(":useremail" => $useremail));
        $result = $query->fetch(PDO::FETCH_ASSOC);
        if (!$result || !password_verify($password_old, $result['jelszo'])) {
            throw new Exception("A régi jelszó helytelen!");
        }

        // Ha minden ellenőrzés sikeres volt, módosítsuk az adatokat az adatbázisban
        $hash = password_hash($password, PASSWORD_DEFAULT); // Új jelszó titkosítása
        $updateSql = "UPDATE felhasznalok SET jelszo = :password  WHERE email = :useremail";
        $updateQuery = $this->dbconn->prepare($updateSql);
        $updateQuery->execute(array(
            ":password" => $hash,
            ":useremail" => $useremail
            
        ));


    }



    public function deleteUser($username) {
        try {
            // Ellenőrizzük, hogy a felhasználó létezik-e
            $sql = "SELECT id FROM felhasznalok WHERE username = :username";
            $query = $this->dbconn->prepare($sql);
            $query->execute(array(":username" => $username));
            $result = $query->fetch(PDO::FETCH_ASSOC);
            if (!$result) {
                throw new Exception("A felhasználó nem található az adatbázisban!");
            }
    
            // Töröljük a felhasználót az adatbázisból
            $deleteSql = "DELETE FROM felhasznalok WHERE username = :username";
            $deleteQuery = $this->dbconn->prepare($deleteSql);
            $deleteQuery->execute(array(":username" => $username));
    
            
    
        } catch(PDOException $e) {
            $msg = "Sikertelen törlés! ".$e->getMessage();
            return false;
        }
        return true;
    }
    

 }

   
  // FELHASZNÁLÓ ADATAINAK MÓDOSÍTÁSA
    

    
  /*public function modifyUserData($username, $email, $password_old, $password, $password2) {


    try{

        $username = trim($_POST["username"]);
        $email = trim($_POST["email"]);
        $password_old = trim($_POST["password_old"]);
        $password = trim($_POST["password"]);
        $password2 = trim($_POST["password2"]);

        // Ellenőrizzük, hogy mindegyik paraméter meg van adva
        if (empty($username) || empty($email) || empty($password_old) || empty($password) || empty($password2)) {
            throw new Exception("Minden adatot kötelező megadni! Kérem, adja meg az összes adatot!");
        }

        // Ellenőrizzük, hogy a két új jelszó megegyezik-e
        if ($password != $password2) {
            throw new Exception("A két új jelszó nem egyezik! Kérem, adja meg újra a jelszavát!");
        }

        // Ellenőrizzük, hogy a régi jelszó egyezik-e az adatbázisban tárolttal
        $sql = "SELECT jelszo FROM felhasznalok WHERE username = :username";
        $query = $this->dbconn->prepare($sql);
        $query->execute(array(":username" => $username));
        $result = $query->fetch(PDO::FETCH_ASSOC);
        if (!$result || !password_verify($password_old, $result['jelszo'])) {
            throw new Exception("A régi jelszó helytelen!");
        }

        

        // Ha minden ellenőrzés sikeres volt, módosítsuk az adatokat az adatbázisban
        $hash = password_hash($password, PASSWORD_DEFAULT); // Új jelszó titkosítása
        $updateSql = "UPDATE felhasznalok SET jelszo = :password, email = :email WHERE username = :username";
        $updateQuery = $this->dbconn->prepare($updateSql);
        $updateQuery->execute(array(
            ":password" => $hash,
            ":email" => $email,
            ":username" => $username
        ));

        // Elmentjük a felhasználó id-ját
        $userId = $result['id'];

        // Ellenőrizzük, hogy a felhasználónév megváltozott-e
        if ($username != $result['username']) {
        // Ha megváltozott, frissítjük az adatbázisban
        $updateUsernameSql = "UPDATE felhasznalok SET username = :new_username WHERE id = :id";
        $updateUsernameQuery = $this->dbconn->prepare($updateUsernameSql);
        $updateUsernameQuery->execute(array(":new_username" => $username, ":id" => $userId));
        }
    }catch(PDOException $e){
        $msg="Sikertelen mentés! ".$e->getMessage();
        return false;
    }

    // Sikeres módosítás esetén visszatérünk true-val
    return true;
    
}



public function deleteUser($username) {
    try {
        // Ellenőrizzük, hogy a felhasználó létezik-e
        $sql = "SELECT id FROM felhasznalok WHERE username = :username";
        $query = $this->dbconn->prepare($sql);
        $query->execute(array(":username" => $username));
        $result = $query->fetch(PDO::FETCH_ASSOC);
        if (!$result) {
            throw new Exception("A felhasználó nem található az adatbázisban!");
        }

        // Töröljük a felhasználót az adatbázisból
        $deleteSql = "DELETE FROM felhasznalok WHERE username = :username";
        $deleteQuery = $this->dbconn->prepare($deleteSql);
        $deleteQuery->execute(array(":username" => $username));

        // Szakítsuk meg a session-t
        session_unset();
        session_destroy();

        // Irányítsuk az index.php-ra
        header("location:index.php");
        exit(); // Biztonságos leállás

    } catch(PDOException $e) {
        $msg = "Sikertelen törlés! ".$e->getMessage();
        return false;
    }
}*/





?>