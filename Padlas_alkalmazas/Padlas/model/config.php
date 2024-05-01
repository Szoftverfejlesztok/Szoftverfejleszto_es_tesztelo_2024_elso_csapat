<?php
ini_set('display_errors', '0');
ini_set('display_startup_errors', '0');
error_reporting(0);
/*AZ ADATBÁZIS KAPCSOLAT LÉTESÍTÉSÉHEZ LÉTREHOZOTT CONFIG.PHP FÁJL FELADATA, HOGY A CONFIG.JSON FÁJLBAN MEGADOTT ADATBÁZIS ELÉRÉSÉHEZ SZÜKSÉGES 
ADATOKAT ELTÁROLJA ÉS FELOLVASSA.*/

class Config { // létrehozzuk a Config osztályt, aminek az adattagjai konstansok. 
  private $DBTYPE;
  private $DBHOST;
  private $DBUSER;
  private $DBPASSWORD;
  private $DBNAME;
  private $ERRORLOG;

  public function __construct($configfile){ // megírjuk a konstruktorát a Config osztálynak
    $json = file_get_contents($configfile); // bekérjük a $joson változóba a Config mappában lévő config.json fájlnak az adatait. 
    $data = json_decode($json); // a $data változóba dekódoljuk a $json változóba bekért adatokat. 
    $this->DBTYPE = $data->db->dbtype;
    $this->DBHOST = $data->db->dbhost;
    $this->DBUSER = $data->db->dbuser;
    $this->DBPASSWORD = $data->db->dbpassword;
    $this->DBNAME = $data->db->dbname;
    $this->ERRORLOG = $data->log->errorlog;
  }
  public function getDatabase(){ // visszaadja az adatbázis kapcsolódáshoz szükséges adatokat egy tömbben. 
    return array(
      "dbtype" => $this->DBTYPE, 
      "dbhost" => $this->DBHOST,
      "dbuser" => $this->DBUSER,
      "dbpassword" => $this->DBPASSWORD,
      "dbname" => $this->DBNAME
    );
  }
  public function getErrorLog(){
    return $this->ERRORLOG;
  }
}

?>