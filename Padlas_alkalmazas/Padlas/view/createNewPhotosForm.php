<?php
ini_set('display_errors', '0');
ini_set('display_startup_errors', '0');
error_reporting(0);
require_once("model/config.php");
require_once("model/database.php");
require_once("model/storage.php");
require_once("model/products.php");
require_once("controller/sizeControl.php");
require("controller/error.php");

// A BEJELENTKEZÉS ELVÉGZÉSÉHEZ AZ ALÁBBI VÁLTOZÓKRA LESZ SZÜKSÉGEM:
$config = new Config("config/config.json"); 
$dbconn = new Database($config);
$storage = new Storage($dbconn->getConnection());
$product = new Products($dbconn->getConnection());
$storage_query_array = $storage->storage_query();

if (isset($_POST['interrupt'])) { // Ha a létrehozás megszakítása gombra kattintottunk
    header("Location:createNewProduct.php"); // Dobjon vissza a megfelelő oldalra
} else {
    if (isset($_GET["nem"])) {
        $nem = trim($_GET["nem"]);
        $meret = trim($_GET["meret"]);
        $kategoria = trim($_GET["kategoria"]);
        $tipus = trim($_GET["tipus"]);
        $id = trim($_GET["id"]);
        $tarolo = trim($_GET["tarolo"]);
        
        /*
        echo "Belementünk a GET-be\n";
        echo "GETT: ";
        foreach ($_GET as $key=>$value) {
            echo "key = ".$key." and value = ".$value." <br />";
        }
        echo "ID: ".$id;
        */

        $searchedProduct = $product->search_Product($id);
        foreach($searchedProduct as $x) {
            
            $nem=$x['nem'];
            $tipus=$x['kategoriaID'];            
            $meret=$x['meret'];
            $tarolo=$x['taroloID'];            
        }
    } else {
        /*
        echo "POST: ";
    foreach ($_POST as $key=>$value) {
        echo "key = ".$key." and value = ".$value." <br />";
        
    }
    */

        $nem = trim($_POST["nem"]);
        $meret = trim($_POST["meret"]);
        $kategoria = trim($_POST["kategoria"]);
        $tipus = trim($_POST["tipus"]);
        $id = trim($_POST["id"]);
        $tarolo = trim($_POST["tarolo"]);
        //echo "POST-ban vagyunk";
    }
    
    
    $productChoose = sizeControl($nem,$kategoria,$tipus,$meret,$id,$tarolo); // A numerikus értékekből szöveges kategóriák létrehozása
}



?>

<form id="createNewProductForm" class = "container text-center searchform" action="uploadPhoto.php" method="post" enctype="multipart/form-data">
    <fieldset>
        <legend><h3>Új termékhez kép feltöltése</h3></legend>
        <p style="font-weight: bold;">Kérlek, add meg a feltölteni kívánt képet!</p>
        <div class="row">
            <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                <label for="nem">Nem:</label>
                <select class=" .form-select form-control" id="nemValaszt" name="nem">                
                <?php echo '<option value="'.$nem.'">'.$productChoose[0].'</option>' ?>
                </select>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                <label for="kategoria">Kategória:</label>
                <select class=" .form-select form-control" id="kategoriaValaszt" name="kategoria">
                <?php echo '<option value="'.$kategoria.'">'.$productChoose[3].'</option>' ?>
                </select>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                <label for="tipus">Típus:</label>
                <select class=" .form-select form-control" id="tipusValaszt" name="tipus">
                <!-- Dinamikusan jelennek meg itt az adatok aszerint, hogy mit választtt kategóriának. -->
                <?php echo '<option value="'.$tipus.'">'.$productChoose[1].'</option>' ?>
                </select>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                <label for="meret">Méret:</label>
                <select class=" .form-select form-control" id="meretValaszt" name="meret">
                <!-- Itt is dinamikusan jelennek meg az adatok -->
                <?php echo '<option value="'.$meret.'">'.$productChoose[2].'</option>' ?>
                </select>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                <label for=picture>Kép:</label>
                <input class=" .form-select form-control" id="taroloValaszt" type="file" name="userfile">
                    
                <!-- <option value="ujTarolo">Új tárolóba rakom</option> -->
                <!-- IDE KELL MEGHÍVNI AZT A PHP KÓDOT,AMI AZ ADATBÁZISBÓL LEKÉRDEZI AZ ÖSSZES MEGLÉVŐ TÁROLÓT ÉS BELEKARKJA ŐKET EGY-EGY 
                OPTION-BE -->                
            </div>
            <div>
                <input type="hidden" id="id" name="id" value=>
                <?php  echo '<input type="hidden" id="id" name="id" value="'.$productChoose[4].'">'  ?>
            </div>
            <div>
                <input type="hidden" id="tarolo" name="tarolo" value=>
                <?php  echo '<input type="hidden" id="tarolo" name="tarolo" value="'.$productChoose[5].'">'  ?>
            </div>
        </div>
        <input class="btn btn-secondary text-center" type="submit" name="create" id="create" value="Kép feltöltése">
        <input class="btn btn-secondary text-center" type="submit" name="interrupt" id="interrupt" value="Kép feltöltés megszakítása">
    </fieldset>
</form>