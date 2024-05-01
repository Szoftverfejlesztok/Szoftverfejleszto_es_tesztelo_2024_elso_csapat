<?php
ini_set('display_errors', '0');
ini_set('display_startup_errors', '0');
error_reporting(0);
ini_set("display_error",1);

$email = trim($_POST["email"]);
$username = trim($_POST["username"]);
$password = trim($_POST["password"]);
$password2 = trim($_POST["password2"]);


//Hibakezelés - validálni kell, hogyha miden adat meg van adva és ki van töltve, akkor jönn ez: 

if($user->newUserRegistration($username,$email,$password, $password2)){
    //Ha sikerült az új felhasználó létrehozása, akkor jelezzünk vissza a felhasználónak. 
}


?>