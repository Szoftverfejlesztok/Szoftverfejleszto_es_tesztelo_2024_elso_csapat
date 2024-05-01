<?php
ini_set('display_errors', '0');
ini_set('display_startup_errors', '0');
error_reporting(0);
require_once("model/config.php");
require_once("model/database.php");
require_once("model/storage.php");
require_once("model/products.php");
require("controller/error.php");

// A BEJELENTKEZÉS ELVÉGZÉSÉHEZ AZ ALÁBBI VÁLTOZÓKRA LESZ SZÜKSÉGEM:
$config = new Config("config/config.json"); 
$dbconn = new Database($config);
$storage = new Storage($dbconn->getConnection());
$product = new Products($dbconn->getConnection());
//$storage_query_array = $storage->storage_query();

/*
echo "GET: ";
    foreach ($_GET as $key=>$value) {
        echo "key = ".$key." and value = ".$value." <br />";
    }
*/

$productId=$_GET['id'];   // GET metódussal átadott érték, a termék id-ját tartalmazza
$taroloId=$_GET['tarolo'];  // GET metódussal átadott érték, a tároló értékét tartalmazza
$prev_site=$_GET['prev_site'];
$nem=$_GET['nem'];
$kategoria=$_GET['kategoria'];
$tipus=$_GET['tipus'];
$meret=$_GET['meret'];


if (isset($_GET['photo_path'])) {  // Ha a GET metódussal érkezett fotóra mutató link,
    $photo_Path=$_GET['photo_path'];  // akkor berkjuk a változóba az elérési útvonalat
} 
if (isset($_GET['search'])) {  // Ha a keresés gombbal navigáltunk ide, tovább megyünk a megadott lapra, és GET-tel viszünk tovább két értéket
    if ($prev_site=="storage") {
        header("Location:listProductOnStorage.php?tarolo=".$taroloId."&id=".$productId);
    } else if ($prev_site=="product") {
        header("Location:searchProduct.php?nem=".$nem."&kategoria=".$kategoria."&tipus=".$tipus."&meret=".$meret);
    }
    
}
if(isset($_GET['delete'])) {   // Ha a törlés gombbal jutottunk ide...
    $is_delete_photo = $product->delete_Photo($productId,$photo_Path); // Ebben a változóban tároljuk el a törlés sikerességét, később, ha kellene valamire...
}

$photo_List = array();  // Létrehozunk a fotóknak egy tömböt: $photo_List[] néven
$productPhotos = $product->list_Photos($productId);
$photo_List[] = "img/no_photo.png"; // Belerakjuk első elemként a no_photo.png-t
foreach ($productPhotos as $x) {    // A $productionPhotos egy asszociatív tömb, és bejárjuk
    $photo_List[] = $x[0];        
    }
    
      // A $photo_List tömböt feltöltjüka termék fotóival
    // echo $x[0];
$photo_List[] = "img/no_photo.png";  // A tömb utolsó eleme megint egy no_photo.png kép lesz.
?>