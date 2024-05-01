<?php  // itt az adott tárolóban elhelyezett termékek törléséhez példányosítok egy storage-ot. 
ini_set('display_errors', '0');
ini_set('display_startup_errors', '0');
error_reporting(0);
require_once("config.php");
require_once("database.php");
require_once("storage.php");
//var_dump;
// Ellenőrizzük, hogy van-e id paraméter az AJAX kérésben
if(isset($_GET['id'])) {
    // Az id paraméter értékét eltároljuk egy változóban
    $storageId = $_GET['id'];
    //var_dump($storageId,storasge)
    // Konfiguráció és adatbázis kapcsolat létrehozása
    $config = new Config("../config/config.json"); 
    $dbconn = new Database($config);

    // Storage objektum létrehozása az adatbázis kapcsolattal
    $storage = new Storage($dbconn->getConnection());
    
    // Termékek törlése az átadott id alapján
    $eredmeny = $storage->removeProductsFromStorage($storageId);
    echo "Eredmény: ".$storageId;
} else {
    // Ha nincs id paraméter az AJAX kérésben, akkor hibaüzenetet küldünk vissza
    echo "Hiba: hiányzó id paraméter";
}