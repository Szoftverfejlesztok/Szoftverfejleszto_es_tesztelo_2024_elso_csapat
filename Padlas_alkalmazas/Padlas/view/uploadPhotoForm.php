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
$is_photo = "A termékhez a kép sikeresen feltöltve!";

if (isset($_POST['interrupt'])) { // Ha a létrehozás megszakítása gombra kattintottunk
    header("Location:createNewProduct.php");  // Dobjon vissza a megfelelő oldalra
} else {
    $nem = trim($_POST["nem"]);
    $meret = trim($_POST["meret"]);
    $kategoria = trim($_POST["kategoria"]);
    $tipus = trim($_POST["tipus"]);
    $id = trim($_POST["id"]);
    $tarolo = trim($_POST["tarolo"]);
    
    /*
    echo "POST: ";
    foreach ($_POST as $key=>$value) {
        echo "key = ".$key." and value = ".$value." <br />";
    }
    */

    ///////////////////////////////////////////////////////
    ////
    //// Fotó feltöltése
    ////
    ///////////////////////////////////////////////////////

    if (isset($_FILES["userfile"])) {
        try {
            // echo "Megjött a file: ".basename($_FILES["userfile"]["tmp_name"]);  // Csak egy segédkiírás
            upload($id, $product, $is_photo); // Meghívjuk az upload függvényt
        } catch (Exception $e) {
            echo $e->getMessage();
            echo 'Nem lehet feltölteni a fájlt';
        }
    } else {
        echo 'Nem jött meg';
    }
}

/////////////////////////////////////////////////////////////////////////////////////////////////////////////
////
////   Létrehozza a megfelelő könyvtárakat, és feltölti oda a filet, majd mghívja az uploadPhoto eljárást
////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////

function upload($id, $product, &$is_photo) {  // Az is_photo változót referenciaként adjuk át, így az esetleges módosítás érvényes lesz
    $filedir = 'uploads';
    $year = date ("Y");
    $month = date ("M");
    $day = date ("d");
    $filename = basename($_FILES['userfile']['name']);
    
    if (!is_dir($filedir)) {
        mkdir($filedir, 0755);
    }
    
    if (!is_dir($filedir.'/'.$year)) {
        mkdir($filedir.'/'.$year, 0755);
    }

    if (!is_dir($filedir.'/'.$year.'/'.$month)) {
        mkdir($filedir.'/'.$year.'/'.$month, 0755);
    }
     if (!is_dir($filedir.'/'.$year.'/'.$month.'/'.$day)) {
        mkdir($filedir.'/'.$year.'/'.$month.'/'.$day, 0755);
     }

    $filePath = $filedir.'/'.$year.'/'.$month.'/'.$day.'/'.$filename;
    //$filePath = $filedir.'/'.$year.'/'.$month.'/'.$day.'/'.basename($_FILES['userfile']['name']);
    $dirPath = $filedir.'/'.$year.'/'.$month.'/'.$day;

    if (is_uploaded_file($_FILES['userfile']['tmp_name'])) {
        if (!file_exists($dirPath.'/'.$filename)) {
            if (move_uploaded_file($_FILES['userfile']['tmp_name'],$dirPath.'/'.$filename)) {
                // echo 'A file feltöltésre került';
                echo $product->uploadPhoto($filePath, $id);      // Itt tölti fel adatbázisba a fotóhoz tartozó adatokat          
            }
        } else {
            $is_photo = "A kép már fel van töltve a termékhez!";            
        }
    } else {
        $is_photo = "Nem lehet feltölteni a fájlt!";
    }
    
}

?>

<?php
//<!--   echo '<form id="createNewProductForm" class = "container text-center searchform" action="createNewPhotos.php?nem='.$nem.'&kategoria='.$kategoria.'&tipus='.$tipus.'&meret='.$meret.'&tarolo='.$tarolo.'&id='.$id["LAST_INSERT_ID()"].'" method="post" enctype="multipart/form-data">';  -->   
//echo "<form id=\"createNewProductForm\" class = \"container text-center searchform\" action=\"createNewPhotos.php?nem=".$nem."&kategoria=".$kategoria."&tipus=".$tipus."&meret=".$meret."&tarolo=".$tarolo."&id=".$id["LAST_INSERT_ID()"]."\" method=\"post\" enctype=\"multipart\/form-data\">";
echo "<form id=\"createNewProductForm\" class = \"container text-center searchform\" action=\"createNewPhotos.php?nem=".$nem."&kategoria=".$kategoria."&tipus=".$tipus."&meret=".$meret."&id=".$id."&tarolo=".$tarolo."\" method=\"post\" enctype=\"multipart\/form-data\">";
?>
<!-- form id="createNewProductForm" class = "container text-center searchform" action="createNewPhotos.php?nem=" method="post" enctype="multipart/form-data"-->

<!--form id="createNewProductForm" class = "container text-center searchform" action="createNewPhotos.php?nem=".$nem."&kategoria=".$kategoria."&tipus=".$tipus."&meret=".$meret."&tarolo=".$tarolo."&id=".$id["LAST_INSERT_ID()"]"  method="POST" enctype="multipart/form-data"-->
    <fieldset>
        <legend><h3><?php echo $is_photo ?></h3></legend>
        <p style="font-weight: bold;">Szeretnél még képet feltölteni ehhez a termékhez?</p>
        <div class="row">
            
        </div>
        <input class="btn btn-secondary text-center" type="submit" name="create" id="create" value="Még egy kép feltöltése">
        <input class="btn btn-secondary text-center" type="submit" name="interrupt" id="interrupt" value="Kép feltöltés megszakítása">
    </fieldset>
</form>

<?php

?>
