<script>  // Ez egy szkript, ami feldob egy ablakot, hogy biztos törölni akarjuk-e a fotót
    function DeletePhoto() {
    var msg=confirm('Biztos törlöd ezt a képet?');
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
session_start();


if (!isset($_SESSION["username"])) {
    header("location:index.php");
    }
require_once("view/header.html");
require_once("view/displayProductPhotosForm.php");
//require_once("view/termekkereso.php")
//$photo_Number = $_GET['photo'];

$current_Photo;  // Inicializáljuk a változót, ez egy számláló, ami az aktuális fotóra mutat a tömbben

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
    <title>Fotók megtekintése</title>
</head>
<body>
    <div id="searchProductForm" class = "container text-center searchform">
            <?php
                if (empty($photo_List)) {
                    $h1_text = "<h1>Nincs megjeleníthető fotó!</h1>";
                } else {
                    if (!isset($_GET['photo'])) {
                        $current_Photo="1";
                    }
                    $current_Photo=$_GET['photo'];  // Ha terméklistázós oldalról jövünk, akkor ez egy 1-es érték lesz, vagyis az első képet fogja jelenteni.
                    $productId=$_GET['id'];  // TermékId
                    //echo "# ".$current_Photo;
                    // echo "  : ".count($photo_List);
                    //var_dump($photo_List);
                    if ($current_Photo<1) {  // Ha lapozáskor az első kép elé lapoznánk,
                        $current_Photo=1;  // akkor is az első képet látjuk.
                    }
                    if ($current_Photo>count($photo_List)-2) {  // Ha lapozáskor az utolsó kép után lapoznánk,
                        $current_Photo = count($photo_List)-2;  // akkor is az utolsó képet látjuk
                    }
                    
                    ?>
                    <h1>Fotó megtekintése: <?php echo (count($photo_List)>2) ? ($current_Photo.'/'.count($photo_List)-2) : "Nincs megjeleníthető fotó!"; ?></h1>
                    <br>
                    <div class="container">
                        <div class="row">
                            <div class="col">
                                <?php echo $current_Photo; ?>                      
                            </div>
                            <div class="col-10">
                            
                                <input type="hidden" name="tarolo" value="><?php echo $taroloId; ?>">
                            <div class="row">
                                    <div class="col text-center align-self-center">
                                        <?php
                                            if ($current_Photo=="1") {  // Ha az első képet látjuk, akkor a tömbben a nulladik sorszámú ún. no_photo.png-t mutatja lapozhatóság nélkül
                                                ?>
                                                    <img src="<?php echo $photo_List[$current_Photo-1]; ?>" alt="" width="100" hiegth="100">
                                                <?php
                                            } elseif (count($photo_List)<=2) {  // Ha az első képet látjuk, akkor a tömbben a nulladik sorszámú ún. no_photo.png-t mutatja lapozhatóság nélkül
                                                ?>
                                                    <img src="<?php echo $photo_List[$current_Photo]; ?>" alt="" width="100" hiegth="100">
                                                <?php
                                            } else 
                                            {  // Különben az aktuális előtti fotót, lapozhatóan
                                            ?>    
                                                <a href="displayProductPhotos.php?id=<?php echo $productId.'&photo='.$current_Photo-1; ?><?php echo '&tarolo='.$taroloId; ?><?php echo '&prev_site='.$prev_site; ?><?php echo '&nem='.$nem; ?><?php echo '&kategoria='.$kategoria; ?><?php echo '&tipus='.$tipus; ?><?php echo '&meret='.$meret; ?>"><img src="<?php echo $photo_List[$current_Photo-1]; ?>" alt="" width="100" hiegth="100"></a>;
                                            <?php    
                                            }
                                        ?>
                                        

                                    </div>
                                    <div class="col-8 text-center">
                                        <img src="<?php echo $photo_List[$current_Photo]; ?>" alt="" width="350" heigth="350">
                                    </div>
                                    <div class="col text-center align-self-center">
                                        <?php
                                            if ($current_Photo == count($photo_List)-2) {  // Ha az utolsó képet látjuk, akkor a tömbben az utolsó sorszámú ún. no_photo.png-t mutatja lapozhatóság nélkül
                                                ?>
                                                    <img src="<?php echo $photo_List[$current_Photo+1]; ?>" alt="" width="100" hiegth="100">
                                                <?php
                                            } else {  // Különben az aktuális utáni fotót, lapozhatóan
                                                ?>
                                                    <a href="displayProductPhotos.php?id=<?php echo $productId.'&photo='.$current_Photo+1; ?><?php echo '&tarolo='.$taroloId; ?><?php echo '&prev_site='.$prev_site; ?><?php echo '&nem='.$nem; ?><?php echo '&kategoria='.$kategoria; ?><?php echo '&tipus='.$tipus; ?><?php echo '&meret='.$meret; ?>"><img src="<?php echo $photo_List[$current_Photo+1]; ?>" alt="" width="100" hiegth="100"></a>
                                                <?php
                                            }
                                        ?>
                                        
                                    </div>
                                </div>
                            
                            </div>
                            <div class="col">
                                
                            </div>

                        </div>

                    </div>
                    <?php
                }

            ?>

            <!--  <form action="listProductOnStorage.php" method="post">  -->
            <br><br>
            <form action="<?php echo $_SERVER["PHP_SELF"]?>" method="get">
            <input class="btn btn-secondary text-center" type="submit" name="search" value="Vissza a találati listához">
            <?php if ($current_Photo>0) echo '
            <input class="btn btn-secondary text-center" type="submit" name="delete" onclick="return DeletePhoto()" value="Kép törlése">
            ' ?>
            <input type="hidden" name="tarolo" value="<?php echo $taroloId ?>">

            <input type="hidden" name="nem" value="<?php echo $nem ?>">
            <input type="hidden" name="kategoria" value="<?php echo $kategoria ?>">
            <input type="hidden" name="tipus" value="<?php echo $tipus ?>">
            <input type="hidden" name="meret" value="<?php echo $meret ?>">

            <input type="hidden" name="prev_site" value="<?php echo $prev_site ?>">
            <input type="hidden" name="id" value="<?php echo $productId ?>">
            <input type="hidden" name="photo" value="<?php echo $current_Photo ?>">
            <input type="hidden" name="photo_path" value="<?php echo $photo_List[$current_Photo] ?>">
            </form>


    </div>
</body>
<?php
require_once("view/footer.html");
?>
</html>