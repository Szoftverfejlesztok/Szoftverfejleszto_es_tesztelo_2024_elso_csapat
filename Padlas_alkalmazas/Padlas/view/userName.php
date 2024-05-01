<?php
ini_set('display_errors', '0');
ini_set('display_startup_errors', '0');
error_reporting(0);
session_start();
$username=$_SESSION['username'];
echo "<p>".$username."</p>";
//echo $username;
?>