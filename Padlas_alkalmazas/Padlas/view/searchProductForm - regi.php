<?php
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
$storage_query_array = $storage->storage_query();
 
$nem="";
$meret="";
$kategoria="";
$tipus="";
if (isset($_REQUEST['submit'])) {
    $nem=$_REQUEST['nem'];
    $kategoria=$_REQUEST['kategoria'];
    $tipus=$_REQUEST['tipus'];
    $meret=$_REQUEST['meret'];
    
    //echo $product->list_Product($nem,$kategoria,$meret); 
    /*
    echo "POST: ";
    foreach ($_POST as $key=>$value) {
        echo "key = ".$key." and value = ".$value." <br />";
    }
    */
}
$is_searched_array;  // Inicializálunk egy változót (ez egy tömb lesz), amibe a keresési feltételeknek megfelelő termékek adatai kerülnek
//$aa;
/*
echo (!empty($nem))?"Nem ":"";
echo (empty($kategoria))?"Kategória ":"";
echo (!is_null($tipus))?"Tipus ":"";
echo (is_null($meret))?"Méret ":"";
*/
$is_searched_array = $product->search_engine($nem,$kategoria,$tipus,$meret);  // Itt hívjuk meg a keresési függvényt
//var_dump($is_searched_array);
/*
if (!empty($is_searched_array)) {
    for ($i=0;$i<count($is_searched_array);$i++) {
        $aa=$is_searched_array[$i]["kategoriaID"];
        echo $aa."<br>";        
    }
}
*/
//echo "KÉSZ";

?>


<form id="searchProductForm" class = "container text-center searchform" action="<?php echo $_SERVER["PHP_SELF"]?>" method="POST">
    <fieldset>
        <legend><h3>Termékkereső</h3></legend>
        <p style="font-weight: bold;">Kérlek, add meg a keresett termék(ek) adatait, vagy válassz egy tárolót!</p>
        <div class="row">
            <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                <label for="nem">Nem:</label>
                <select class=" .form-select form-control" id="nemValaszt" name="nem">
                <option value=""></option>
                <option value="1">Fiú</option>
                <option value="2">Lány</option>
                </select>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                <label for="kategoria">Kategória:</label>
                <select class=" .form-select form-control" id="kategoriaValaszt" name="kategoria">
                <option value=""></option>
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
            
        </div>
        <br>
        <!-- Keresés a kiválasztott jellemzők alapján -->
        <input class="btn btn-secondary text-center" type="submit" name="submit" value="    Keresés     ">  

        <!-- Keresés a kiválasztott tároló alapján -->
        <input class="btn btn-secondary text-center" type="submit" name="storage" onclick="this.form.action='listProductOnStorage.php'" value="Tároló választása">
    </fieldset>
</form>
        


<script>
    const tipusValaszt = document.getElementById('tipusValaszt');
    const meretValaszt = document.getElementById('meretValaszt');
    const nemValaszt = document.getElementById('nemValaszt');
    const kategoriaValaszt = document.getElementById('kategoriaValaszt');
    //const taroloValaszt = document.getElementById('taroloValaszt');

    kategoriaValaszt.addEventListener('change', Frissit);
    nemValaszt.addEventListener('change', Frissit);

    function Frissit() {
    const kategoria = kategoriaValaszt.value;
    const nem = nemValaszt.value;
    tipusValaszt.innerHTML = ''; // Törli a korábbi keresési beállítást
    meretValaszt.innerHTML = ''; // Törli a méret mezőből a korábbi keresési beállítást.
    //taroloValaszt.innerHTML = '';

    ////////////////////////////////////////////////////////////////////////////////////////
    //
    // Az adatbázisból a tárolók kilistázása az új termék hozzáadása oldalon a tároló fülön
    //
    ////////////////////////////////////////////////////////////////////////////////////////
    
    
    
    

    ///////////////////////////////////////////////////////////////////////////////////////

    if (kategoria === 'ruha') {
        if (nem === '1') {
        tipusValaszt.innerHTML = `
            <option value=""></option>
            <option value="3">Felső</option>
            <option value="4">Alsó</option>
            <option value="5">Egybe ruha</option>
            <option value="6">Kiegészítő</option>
            <option value="7">Fehérnemű</option>
        `;
        meretValaszt.innerHTML = `
            <option value=""></option>
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
            <option value=""></option>
            <option value="3">Felső</option>
            <option value="4">Alsó</option>
            <option value="5">Egybe ruha</option>
            <option value="6">Kiegészítő</option>
            <option value="7">Fehérnemű</option>
        `;
        meretValaszt.innerHTML = `
            <option value=""></option>
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
        } else if (nem == '') {
        tipusValaszt.innerHTML = `
            <option value=""></option>
            <option value="3">Felső</option>
            <option value="4">Alsó</option>
            <option value="5">Egybe ruha</option>
            <option value="6">Kiegészítő</option>
            <option value="7">Fehérnemű</option>
        `;
        meretValaszt.innerHTML = `
            <option value=""></option>
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
            <option value=""></option>
            <option value="8">Szabadidőcipő</option>
            <option value="9">Sportcipő</option>
            <option value="10">Bakancs</option>
            <option value="11">Papucs</option>
            <option value="12">Szandál</option>
            <option value="13">Körömcipő</option>
        `;
        meretValaszt.innerHTML = `
            <option value=""></option>
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
            <option value=""></option>
            <option value="8">Szabadidőcipő</option>
            <option value="9">Sportcipő</option>
            <option value="10">Bakancs</option>
            <option value="11">Papucs</option>
            <option value="12">Szandál</option>
            <option value="13">Körömcipő</option>
        `;
        meretValaszt.innerHTML = `
            <option value=""></option>
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
        } else if (nem == '') {
        tipusValaszt.innerHTML = `
            <option value=""></option>
            <option value="8">Szabadidőcipő</option>
            <option value="9">Sportcipő</option>
            <option value="10">Bakancs</option>
            <option value="11">Papucs</option>
            <option value="12">Szandál</option>
            <option value="13">Körömcipő</option>
        `;
        meretValaszt.innerHTML = `
            <option value=""></option>
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
    } else if (kategoria === '') {
        if (nem === '1') {
        tipusValaszt.innerHTML = `
            <option value=""></option>
            <option value="3">Felső</option>
            <option value="4">Alsó</option>
            <option value="5">Egybe ruha</option>
            <option value="6">Kiegészítő</option>
            <option value="7">Fehérnemű</option>
            <option value="8">Szabadidőcipő</option>
            <option value="9">Sportcipő</option>
            <option value="10">Bakancs</option>
            <option value="11">Papucs</option>
            <option value="12">Szandál</option>
            <option value="13">Körömcipő</option>
        `;
        meretValaszt.innerHTML = `
            <option value=""></option>
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
            <option value=""></option>
            <option value="3">Felső</option>
            <option value="4">Alsó</option>
            <option value="5">Egybe ruha</option>
            <option value="6">Kiegészítő</option>
            <option value="7">Fehérnemű</option>
            <option value="8">Szabadidőcipő</option>
            <option value="9">Sportcipő</option>
            <option value="10">Bakancs</option>
            <option value="11">Papucs</option>
            <option value="12">Szandál</option>
            <option value="13">Körömcipő</option>
        `;
        meretValaszt.innerHTML = `
            <option value=""></option>
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
        } else if (nem == '') {
        tipusValaszt.innerHTML = `
            <option value=""></option>
            <option value="3">Felső</option>
            <option value="4">Alsó</option>
            <option value="5">Egybe ruha</option>
            <option value="6">Kiegészítő</option>
            <option value="7">Fehérnemű</option>
            <option value="8">Szabadidőcipő</option>
            <option value="9">Sportcipő</option>
            <option value="10">Bakancs</option>
            <option value="11">Papucs</option>
            <option value="12">Szandál</option>
            <option value="13">Körömcipő</option>
        `;
        meretValaszt.innerHTML = `
            <option value=""></option>
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


