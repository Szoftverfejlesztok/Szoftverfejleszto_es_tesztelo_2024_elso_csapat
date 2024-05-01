<?php
ini_set('display_errors', '0');
ini_set('display_startup_errors', '0');
error_reporting(0);
session_start();


if (!isset($_SESSION["username"])) {
    header("location:index.php");
    }
require_once("model/config.php");
require_once("model/database.php");
require_once("model/products.php");
require("controller/dataMarkerConversion.php");

$config = new Config("config/config.json"); 
$dbconn = new Database($config);
$product = new Products($dbconn->getConnection());




require_once("view/header.html");
require_once("view/listProductOnStorageForm.php");
//require_once("view/termekkereso.php")

?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="style/style.css">
    <link rel="stylesheet" href="style/header.css">
    <link rel="stylesheet" href="style/searchStyle.css">
    <link rel="stylesheet" href="style/footer.css">
    <title>Termékkereső</title>
</head>
<body>
<?php
$listStorage = $product->list_Storage($id); // Kikeressük a termékeket a tároló id alapján
$firstPhotoPath;  // Ez egy változó, amiben a kiválasztott termék első elérhető fotójának az elérési útvonalát tároljuk

?>
<!-- Ha van kiválasztva tároló, kiírjuk a nevét, és a benne elérhető termékek számát -->
<h2 class="text-center">Tároló neve: <?php echo (empty($id)) ? " Nincs tároló kiválasztva" : "$id termékek száma: ".count($listStorage); ?></h2>

<?php
echo '<div class="container">';
echo '<div class="row">';
echo '<div class="col">';
echo '</div>';
echo '<div class="col-10">';
echo '<table class="table table-hover table-responsive table-sm text-center">';
echo '<tr class="table-info"><th>ID</th><th>NEM</th><th>KATEGÓRIA</th><th>MÉRET</th><th>FOTÓ</th></tr>';
if (!empty($listStorage)) {   // Ha a változóban vannak termékek, tehát nem üres --> létrehozunk egy táblázatot
    
    for ($i=0; $i<count($listStorage);$i++) {  // Megvizsgáljuk, hány darab termékünk van
        
        // A adatbázisból kinyert adatokat változókba rakjuk
        $productId=($listStorage[$i]["id"]);
        $productGender=($listStorage[$i]["nem"]);
        $productCategory=($listStorage[$i]["kategoriaID"]);
        $productSize=($listStorage[$i]["meret"]);
        $pCategoryConversion = $product->data_Marker_Conversion($productGender,$productCategory,$productSize);  // Néhány kapott értéket konvertálni kell, hogy a helyes érték jelenjen meg
        
        
        $firstPhoto = $product->first_Photo($productId);    // Ez a metódus segít megtalálni a termék fotójának elérési útját
        
        foreach($firstPhoto as $x) {        
            $firstPhotoPath = ($x['fotoEleresiUt']);  // Itt tároljuk le a termék első fotójának elérési útvonalát
        }
        //var_dump($firstPhotoPath);
        if(!isset($firstPhotoPath)) {  // Ha a terméknek nincs fotója, akkor a $firstPhotoPath egy ún 'nincs kép' ikonra mutat
            $firstPhotoPath="img/no_photo.png";
            // A kiírásnál a 'nincs kép' ikon nem linkelhető
            echo '<tr><td><a href="modifyProduct.php?id='.$productId.'&photo=1&tarolo='.$id.'&prev_site=storage">'.$productId.'</a></td><td>'.$pCategoryConversion[0].'</td><td>'.$pCategoryConversion[1].'</td><td>'.$pCategoryConversion[2].'</td><td><img src="'.$firstPhotoPath.'" alt="" width=30px height=30px></td>';
        } else {
            // Megjelenítjük a termék ún első fotóját, linkelhető módon. Rákattintva a termék fotói töltődnek be
            echo '<tr><td><a href="modifyProduct.php?id='.$productId.'&photo=1&tarolo='.$id.'&prev_site=storage">'.$productId.'</a></td><td>'.$pCategoryConversion[0].'</td><td>'.$pCategoryConversion[1].'</td><td>'.$pCategoryConversion[2].'</td><td><a href="displayProductPhotos.php?id='.$productId.'&photo=1&tarolo='.$id.'&prev_site=storage"><img src="'.$firstPhotoPath.'" alt="" width=30px height=30px></a></td>';
        }
        
        
        $firstPhotoPath = null; // A változó elveszíti korábbi értékét
        
    } 
    
}
echo "</table>";
    echo "</div>";
    echo '<div class="col">';
    echo "</div>";
        
    echo "</div>";
    echo "</div>";
    echo "<br><br>";

     
?>

</body>
<?php
require_once("view/footer.html");
?>
</html>