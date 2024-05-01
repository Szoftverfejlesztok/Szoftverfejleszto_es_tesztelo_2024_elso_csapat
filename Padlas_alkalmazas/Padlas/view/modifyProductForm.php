<script>
    function DeleteProduct() {
    var msg=confirm('Biztos törlöd a kijelölt terméket?');
    if (msg) {
        return true;
    }
    else {
        return false;
    }    
}
</script>
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

$h3_text = "Lehetőség van a termék módosítására, és törlésére! Termék ID: ";
/*
echo "POST: ";
    foreach ($_POST as $key=>$value) {
        echo "key = ".$key." and value = ".$value." <br />";
    }
*/
$productArrayValues = array();
$productArrayNames = array();
if (isset($_REQUEST['prev_site'])) {
    if ($_REQUEST['prev_site'] == 'product') {
        $prev_site=$_REQUEST['prev_site'];
        $nem=$_REQUEST['nem'];
        $nem_searched=$_REQUEST['nem'];
        $tipus=$_REQUEST['tipus'];
        $tipus_searched=$_REQUEST['tipus'];
        $meret=$_REQUEST['meret'];
        $meret_searched=$_REQUEST['meret'];
        $kategoria=$_REQUEST['kategoria'];
        $kategoria_searched=$_REQUEST['kategoria'];
    } 
    $prev_site=$_REQUEST['prev_site'];
    $taroloId=$_REQUEST['tarolo'];
    
} else {
    $nem;
    $tipus;
    $meret;
    $tarolo;
}

//$modifyProduct = array(false,false);
/*
echo "POST:::: ";
    foreach ($_REQUEST as $key=>$value) {
        echo "key = ".$key." and value = ".$value." <br />";
    }
*/
$modifyProduct[0] = !empty($_REQUEST['is_modify']);
$modifyProduct[1] = !empty($_REQUEST['false_modify']);
if ($modifyProduct[1]) {
    $h3_text = "Érvénytelen módosítás! ".$h3_text;
}
/*
var_dump ($modifyProduct);
echo "<br>";
echo $modifyProduct[0]."modify-0 ";
echo $modifyProduct[1]."modify-1 ";
*/


if (isset($_POST['delete']) || (isset($_POST['modify'])) || (isset($_POST['photoUpload'])) || (isset($_REQUEST['taroloId']))) { // Ha a létrehozás megszakítása gombra kattintottunk
    //echo "MOST ITT VAGYUNK!!!";  
    if (isset($_POST['delete'])) {             // Ha a termék törlése gombra nyomtunk
        $taroloId = $_POST['taroloId'];
        $id = $_POST['id'];
        //echo "----------------------       #############";
        
        $deleteProduct = $product->delete_Product($id);
        if ($prev_site=='product') {
            header("Location:searchProduct.php");
        } else {
            header("Location:listProductOnStorage.php?tarolo=".$taroloId);
        }
        
    } else if (isset($_POST['modify'])){         // Ha a termék módosítása gombra nyomtunk
            $counter = 0;
        // echo "POST: ";
        foreach ($_POST as $key=>$value) {
            //echo "key = ".$key." and value = ".$value." <br />";
            $productArrayValues[$counter] = $value;
            $productArrayNames[$counter] = $key;
            $counter++;    
        }
    /*
    echo "GET: ";
    foreach ($_GET as $key=>$value) {
        echo "key = ".$key." and value = ".$value." <br />";        
    }
    */
    $modifyProduct = $product->modify_Product($productArrayValues);
    //echo $modifyProduct[2];

    //aelkjrgh;
    //$product->modify_Product($productArrayValues);
    //var_dump ($modifyProduct);
    //echo "<br>modifyproduct";
    // sleep(2);
    //echo "productArrayValues";
    //var_dump($productArrayValues);
    //echo "<br>";
    //var_dump($productArrayNames);
    //unset($_POST);
    $id = $_REQUEST['id'];
    header("Location:modifyProduct.php?id=".$id."&is_modify=".$modifyProduct[0]."&false_modify=".$modifyProduct[1]);
        
        
    } else if(isset($_POST['photoUpload'])) {      // Ha a fotó feltöltése gombra nyomtunk
        $counter = 0;
        foreach ($_REQUEST as $key=>$value) {
            $productArrayValues[$counter] = $value;
            //echo $productArrayValues[$counter];
            $counter++;
        }
        $id = $_REQUEST['id'];
        header("Location:createNewPhotos.php?nem=".$productArrayValues[0]."&kategoria=".$productArrayValues[1]."&tipus=".$productArrayValues[2]."&meret=".$productArrayValues[3]."&tarolo=".$taroloId."&id=".$id);
    } else {
        $prev_site=$_REQUEST['prev_site'];
        if (isset($_REQUEST['nem_searched'])) {
            $nem_searched=$_REQUEST['nem_searched'];
            $tipus_searched=$_REQUEST['tipus_searched'];
            $meret_searched=$_REQUEST['meret_searched'];
            $kategoria_searched=$_REQUEST['kategoria_searched'];
        }
        
        if ($prev_site=="storage") {
            $taroloId = $_REQUEST['taroloId'];        
        header("Location:listProductOnStorage.php?tarolo=".$taroloId);
        } else {
            if (isset($nem_searched)) {
                header("Location:searchProduct.php?nem=".$nem_searched."&kategoria=".$kategoria_searched."&tipus=".$tipus_searched."&meret=".$meret_searched);
            } else {
                header("Location:searchProduct.php");
            }
            
            //header("Location:searchProduct.php");
        }
        
    }  
       
} else {
    if (isset($_GET["id"])) {
        //$nem = trim($_GET["nem"]);
        //$meret = trim($_GET["meret"]);
        //$kategoria = trim($_GET["kategoria"]);
        //$tipus = trim($_GET["tipus"]);
        $id = trim($_GET["id"]);
        //$tarolo = trim($_GET["tarolo"]);
        
        /*
        echo "Belementünk a GET-be\n";
        echo "GETT: ";
        foreach ($_GET as $key=>$value) {
            echo "key = ".$key." and value = ".$value." <br />";
        }
        */
        
        $searchedProduct = $product->search_Product($id);
        
        foreach($searchedProduct as $x) {
            
            $nem=$x['nem'];
            $tipus=$x['kategoriaID'];            
            $meret=$x['meret'];
            $tarolo=$x['taroloID'];            
        }
        

        $categoryType = $product->category_Type($tipus);
        foreach($categoryType as $x) {
            $kategoria = $x['szulo_id'];
            $kategoria_nev = $x['nev'];
        }
        if ($kategoria==1) {
            $kategoria="ruha";
        } else {
            $kategoria="cipo";
        }
    } 
    //echo "Adatok: id: ".$id.",  nem: ".$nem.",   kat: ".$tipus.",   tipus: ".$kategoria.",  meret: ".$meret.",  tarolo: ".$tarolo;
    
    $productChoose = sizeControl($nem,$kategoria,$tipus,$meret,$id,$tarolo); // A numerikus értékekből szöveges kategóriák létrehozása

    /*
    var_dump($productChoose);
    echo "<br>choose[0]".$productChoose[0];
    echo "<br>choose[1]".$productChoose[1];
    echo "<br>choose[2]".$productChoose[2];
    echo "<br>choose[3]".$productChoose[3];
    echo "<br>choose[4]".$productChoose[4];
    echo "<br>choose[5]".$productChoose[5];
    */
}

$searchedProduct = $product->search_Product($id);
        
        foreach($searchedProduct as $x) {
            
            $nem=$x['nem'];
            $tipus=$x['kategoriaID'];            
            $meret=$x['meret'];
            $tarolo=$x['taroloID'];            
        }

?>

<form id="createNewProductForm" class = "container text-center searchform" action="<?php echo $_SERVER["PHP_SELF"]?>" method="post">
    <fieldset>
        <legend><h3><?php echo (!$modifyProduct[0]) ? $h3_text."".$id : "A termék módosult! ID: ".$id; ?></h3></legend>
        <p style="font-weight: bold;">Kérlek, állítsd be a módosítani kívánt paramétereket!</p>
        <p style="font-weight: bold;">Nem: <?php echo $productChoose[0]; ?>  *  Kategória: <?php echo $productChoose[3]; ?>  *  Tipus: <?php echo $productChoose[1] ?>  *  Méret: <?php echo $productChoose[2]; ?>  *  Tároló: <?php echo $productChoose[5] ?></p>
        <div class="row">
            <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                <label for="nem">Nem:</label>
                <select class=" .form-select form-control" id="nemValaszt" name="nem">
                <?php echo '<option value="'.$nem.'">Ez egy '.$productChoose[0].' termék</option>' ?>              
                <option value="1">Fiú</option>
                <option value="2">Lány</option>
                </select>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                <label for="kategoria">Kategória:</label>
                <select class=" .form-select form-control" id="kategoriaValaszt" name="kategoria">
                <?php echo '<option value="'.$kategoria.'">A '.$productChoose[3].' kategóriába tartozik</option>' ?> 
                <option value="ruha">Ruha</option>
                <option value="cipo">Cipő</option>
                </select>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                <label for="tipus">Típus:</label>
                <select class=" .form-select form-control" id="tipusValaszt" name="tipus">
                <!-- Dinamikusan jelennek meg itt az adatok aszerint, hogy mit választtt kategóriának. -->
                </select>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                <label for= meret>Méret:</label>
                <select class=" .form-select form-control" id="meretValaszt" name="meret">
                <!-- Itt is dinamikusan jelennek meg az adatok -->
                </select>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                <label for=tarolo>Tároló:</label>
                <select class=" .form-select form-control" id="taroloValaszt" name="tarolo">
                <!-- <option value="ujTarolo">Új tárolóba rakom</option> -->
                <!-- IDE KELL MEGHÍVNI AZT A PHP KÓDOT,AMI AZ ADATBÁZISBÓL LEKÉRDEZI AZ ÖSSZES MEGLÉVŐ TÁROLÓT ÉS BELEKARKJA ŐKET EGY-EGY 
                OPTION-BE -->
                
                </select>
                
            </div>
        </div>
        <input class="btn btn-secondary text-center" type="submit" name="photoUpload" onclick="this.form.action=createNewPhotos.php?nem=<?php echo $nem ?>&kategoria=<?php echo $kategoria ?>&tipus=<?php echo $tipus ?>&meret=<?php echo $meret ?>&tarolo=<?php echo $tarolo ?>&id=<?php echo $id ?>" id="photoUpload" value="Kép feltöltése">
        <input class="btn btn-secondary text-center" type="submit" name="modify" id="modify" value="Termék módosítása">        
        <input class="btn btn-secondary text-center" type="submit" name="delete" onclick="return DeleteProduct()" id="delete" value="Termék törlése">
        <input class="btn btn-secondary text-center" type="submit" name="cancel" id="cancel" value="Mégse">
        <input type="hidden" name="taroloId" value="<?php echo $tarolo; ?>" >
        <input type="hidden" name="id" value="<?php echo $id; ?>">
        
        
        <input type="hidden" name="prev_site" value="<?php echo $prev_site; ?>">
        <?php 
        if (isset($prev_site) && $prev_site=='product') {
            ?>
                <input type="hidden" name="nem_searched" value="<?php echo $nem_searched; ?>">
                <input type="hidden" name="kategoria_searched" value="<?php echo $kategoria_searched; ?>">
                <input type="hidden" name="tipus_searched" value="<?php echo $tipus_searched; ?>">
                <input type="hidden" name="meret_searched" value="<?php echo $meret_searched; ?>">
            <?php
        }
        ?>
        

        
        <!--
        <input type="hidden" name="meretValaszt" value="<?php echo $meret; ?>">
        <input type="hidden" name="kategoria" value="<?php echo $tipus; ?>">        
        <input type="hidden" name="tipus" value="<?php echo $kategoria; ?>">
        --->


    </fieldset>
</form>

<script>
    const tipusValaszt = document.getElementById('tipusValaszt');
    const meretValaszt = document.getElementById('meretValaszt');
    const nemValaszt = document.getElementById('nemValaszt');
    const kategoriaValaszt = document.getElementById('kategoriaValaszt');
    const taroloValaszt = document.getElementById('taroloValaszt');

    kategoriaValaszt.addEventListener('change', Frissit);
    nemValaszt.addEventListener('change', Frissit);

    function Frissit() {
    const kategoria = kategoriaValaszt.value;
    const nem = nemValaszt.value;
    tipusValaszt.innerHTML = ''; // Törli a korábbi keresési beállítást
    meretValaszt.innerHTML = ''; // Törli a méret mezőből a korábbi keresési beállítást.
    taroloValaszt.innerHTML = '';

    ////////////////////////////////////////////////////////////////////////////////////////
    //
    // Az adatbázisból a tárolók kilistázása az új termék hozzáadása oldalon a tároló fülön
    //
    ////////////////////////////////////////////////////////////////////////////////////////
    
    <?php
    
    if (!is_array($storage_query_array)) { 
        
        ?>
        taroloValaszt.innerHTML = `
        <option value="0">Nincs tároló létrehozva</option>    
    ` <?php } else {        
        ?>taroloValaszt.innerHTML = `
        <option value="">Jelenleg a <?php echo $tarolo; ?> nevű tárolóban van</option>
        <?php
        foreach ($storage_query_array as $x => $y) { ?><option value="<?php echo $x ?>"><?php echo $y ?></option>            
    <?php     
        }
    ?>`
    <?php
    }
    ?>

    ///////////////////////////////////////////////////////////////////////////////////////

    if (kategoria === 'ruha') {
        if (nem === '1') {
        tipusValaszt.innerHTML = `
            <option value="">Ez egy: <?php echo $productChoose[1]; ?></option>
            <option value="3">Felső</option>
            <option value="4">Alsó</option>
            <option value="5">Egybe ruha</option>
            <option value="6">Kiegészítő</option>
            <option value="7">Fehérnemű</option>
        `;
        meretValaszt.innerHTML = `
            <option value="">Méret: <?php echo $productChoose[2]; ?></option>
            <option value="1">50</option>
            <option value="2">56</option>
            <option value="3">62</option>
            <option value="4">68</option>
            <option value="5">74</option>
            <option value="6">80</option>
            <option value="7">86</option>
            <option value="8">92</option>
            <option value="9">98</option>
            <option value="10">104</option>
            <option value="11">110</option>
            <option value="12">116</option>
            <option value="13">122</option>
            <option value="14">128</option>
            <option value="15">134</option>
            <option value="16">140</option>
            <option value="17">146</option>
            <option value="18">152</option>
            <option value="19">158</option>
            <option value="20">164</option>
            <option value="21">170</option>
        `;
        } else if (nem === '2') {
        tipusValaszt.innerHTML = `
            <option value="">Ez egy: <?php echo $productChoose[1]; ?></option>
            <option value="3">Felső</option>
            <option value="4">Alsó</option>
            <option value="5">Egybe ruha</option>
            <option value="6">Kiegészítő</option>
            <option value="7">Fehérnemű</option>
        `;
        meretValaszt.innerHTML = `
            <option value="">Méret: <?php echo $productChoose[2]; ?></option>
            <option value="1">50</option>
            <option value="2">56</option>
            <option value="3">62</option>
            <option value="4">68</option>
            <option value="5">74</option>
            <option value="6">80</option>
            <option value="7">86</option>
            <option value="8">92</option>
            <option value="9">98</option>
            <option value="10">104</option>
            <option value="11">110</option>
            <option value="12">116</option>
            <option value="13">122</option>
            <option value="14">128</option>
            <option value="15">134</option>
            <option value="16">140</option>
            <option value="17">146</option>
            <option value="18">152</option>
            <option value="19">158</option>
            <option value="20">164</option>
            <option value="21">170</option>
        `;
        }
    } else if (kategoria === 'cipo') {
        if (nem === '1') {
        tipusValaszt.innerHTML = `
            <option value="">Ez egy: <?php echo $productChoose[1]; ?></option>
            <option value="8">Szabadidőcipő</option>
            <option value="9">Sportcipő</option>
            <option value="10">Bakancs</option>
            <option value="11">Papucs</option>
            <option value="12">Szandál</option>
            <option value="13">Körömcipő</option>
        `;
        meretValaszt.innerHTML = `
            <option value="">Méret: <?php echo $productChoose[2]; ?></option>
            <option value="22">18</option>
            <option value="23">19</option>
            <option value="24">20</option>
            <option value="25">21</option>
            <option value="26">22</option>
            <option value="27">23</option>
            <option value="28">24</option>
            <option value="29">25</option>
            <option value="30">26</option>
            <option value="31">27</option>
            <option value="32">28</option>
            <option value="33">29</option>
            <option value="34">30</option>
            <option value="35">31</option>
            <option value="36">32</option>
            <option value="37">33</option>
            <option value="38">34</option>
            <option value="39">35</option>
            <option value="40">36</option>
            <option value="41">37</option>
            <option value="42">38</option>
            <option value="43">39</option>
            <option value="44">40</option>
        `;
        } else if (nem === '2') {
        tipusValaszt.innerHTML = `
            <option value="">Ez egy: <?php echo $productChoose[1]; ?></option>
            <option value="8">Szabadidőcipő</option>
            <option value="9">Sportcipő</option>
            <option value="10">Bakancs</option>
            <option value="11">Papucs</option>
            <option value="12">Szandál</option>
            <option value="13">Körömcipő</option>
        `;
        meretValaszt.innerHTML = `
            <option value="">Méret: <?php echo $productChoose[2]; ?></option>
            <option value="22">18</option>
            <option value="23">19</option>
            <option value="24">20</option>
            <option value="25">21</option>
            <option value="26">22</option>
            <option value="27">23</option>
            <option value="28">24</option>
            <option value="29">25</option>
            <option value="30">26</option>
            <option value="31">27</option>
            <option value="32">28</option>
            <option value="33">29</option>
            <option value="34">30</option>
            <option value="35">31</option>
            <option value="36">32</option>
            <option value="37">33</option>
            <option value="38">34</option>
            <option value="39">35</option>
            <option value="40">36</option>
            <option value="41">37</option>
            <option value="42">38</option>
            <option value="43">39</option>
            <option value="44">40</option>
        `;
        }
    }
    }

    // A függvény, aminek hatására megváltoznak a kiválasztás opciói
    Frissit();
</script>