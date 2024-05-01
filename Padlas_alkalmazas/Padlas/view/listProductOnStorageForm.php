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
$storage_query_array = $storage->storage_query();  // A meglévő tárolók neveit rakja egy tömbbe
$id=0;  // Inicializáljuk az 'id' változót nullára. Ez tartalmazza a tároló értékét.
if (isset($_POST['submit']) || isset($_REQUEST['tarolo'])) {    // Megvizsgáljuk, hogy 'submit' gombbal jöttünk-e ide, vagy van-e 'tarolo' nevű kulcs
    $id=trim($_REQUEST['tarolo']);  // Belerakjuk a 'tarolo' nevű kulcs értékét
    $listStorage = $product->list_Storage($id);    // Meghívjuk a tárolólistázó függvényt. A visszatérési érték egy tömb, ami tartalmazza a tárolóban levő termékek adatait
    //var_dump($listStorage);
}
/*
echo "POST: ";
    foreach ($_POST as $key=>$value) {
        echo "key = ".$key." and value = ".$value." <br />";
    }
echo "tarolo: ".$id;
*/

?>

<form id="searchProductForm" class = "container text-center searchform" action="<?php echo $_SERVER["PHP_SELF"]?>" method="POST">
    <fieldset>
        <legend><h3>Tárolólistázó</h3></legend>
        <p style="font-weight: bold;">Kérlek, add meg melyik tárolót szeretnéd megtekinteni!</p>
        <div class="row">
            <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                <label for="nem">Tároló:</label>
                <select class=" .form-select form-control" id="taroloValaszt" name="tarolo">                
                
                </select>
            </div>
            
            
        </div>
        <br>
        <input class="btn btn-secondary text-center" type="submit" name="submit" value="Listázás">
        <input class="btn btn-secondary text-center" type="submit" name="product" onclick="this.form.action='searchProduct.php'" value="Termék keresés">
    </fieldset>
</form>

<script>    
    const taroloValaszt = document.getElementById('taroloValaszt');

    

    function Frissit() {
    
    taroloValaszt.innerHTML = '';

    ////////////////////////////////////////////////////////////////////////////////////////
    //
    // Az adatbázisból a tárolók kilistázása az új termék hozzáadása oldalon a tároló fülön
    //
    ////////////////////////////////////////////////////////////////////////////////////////
    
    <?php
    
    if (!is_array($storage_query_array)) {  // Ha nem üres a tárolók listáját tartalmazó tömb -> tehát van legalább egy darab tárolónk...
        
        ?>
        taroloValaszt.innerHTML = `
        <option value="0">Nincs tároló létrehozva</option>    
    ` <?php } else {        
        ?>taroloValaszt.innerHTML = `
        <option value="">Kérem válasszon tárolót!</option>
        <?php   // A lenyíló fülön választhatjuk ki a listából a tárolót
        foreach ($storage_query_array as $x => $y) { ?><option value="<?php echo $x ?>"><?php echo $y ?></option>            
    <?php     
        }
    ?>`
    <?php
    }
    ?>;

    ///////////////////////////////////////////////////////////////////////////////////////
    
    
    }

    
    // A függvény, aminek hatására megváltoznak a kiválasztás opciói
    Frissit();
    
</script>