<?php
ini_set('display_errors', '0');
ini_set('display_startup_errors', '0');
error_reporting(0);
/* A DATABASE.PHP-BAN JÖN LÉTRE AZ ADATBÁZIS KAPCSOLAT ÉS 
A DATABASE osztály getConnection() függvénye a létrejött adatbázis kacsolatot adja vissza.*/
    
class Database {
    private $conn = null;
    
    public function __construct($conf){
        try {
        $dbparam = $conf->getDatabase();
        $this->conn = new PDO(
            "{$dbparam["dbtype"]}:host={$dbparam["dbhost"]};dbname={$dbparam["dbname"]}",
            $dbparam["dbuser"],
            $dbparam["dbpassword"]
        );
        $this->conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e){
        error_log("Adatbázis kapcsolódási hiba: ".$e->getMessage(),3,$conf->getErrorLog());
        }
    }
    public function getConnection(){
        return $this->conn;
    }
}


?>