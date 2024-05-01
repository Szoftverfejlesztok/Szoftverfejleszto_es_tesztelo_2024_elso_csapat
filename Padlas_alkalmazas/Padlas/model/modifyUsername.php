<?php
ini_set('display_errors', '0');
ini_set('display_startup_errors', '0');
error_reporting(0);
session_start();


require_once("config.php");
require_once("database.php");
require_once("user.php");

// Ellenőrizzük, hogy van-e id paraméter az AJAX kérésben
if(isset($_GET['username']) && isset($_GET['useremail'])) {
    // Az useremail paraméter értékét eltároljuk egy változóban
    $username = $_GET['username'];
    $email = $_GET['useremail'];
    
    // Ellenőrizzük, hogy a $username és $useremail változók üresek-e
    if (!empty($username) && !empty($email)) {
        
        // Konfiguráció és adatbázis kapcsolat létrehozása
        $config = new Config("../config/config.json"); 
        $dbconn = new Database($config);
        $user = new User($dbconn->getConnection());

        if ($user->modifyUserName($username, $email)){echo 'true';}
        $_SESSION['username'] = $username;
    } else {
        // Ha az $username vagy $useremail változók üresek, akkor visszaküldünk egy hibaüzenetet
        echo "Hiba: Üres felhasználónév vagy email paraméter";
    }
} else {
    // Ha nincs username vagy useremail paraméter az AJAX kérésben, akkor visszaküldünk egy hibaüzenetet
    echo "Hiba: Hiányzó paraméter";
    echo $username;
    echo $email;
}
?>