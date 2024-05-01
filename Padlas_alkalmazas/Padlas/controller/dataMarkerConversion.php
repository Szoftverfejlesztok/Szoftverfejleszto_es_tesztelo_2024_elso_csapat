<?php
ini_set('display_errors', '0');
ini_set('display_startup_errors', '0');
error_reporting(0);
function dataMarkerConversion($pGender,$pCategory,$psize) {
    $sql_product_choose="SELECT nev FROM kategoria WHERE id=$pCategory";
    $query_product_choose = $dbconn->prepare($sql_product_choose);    
    $query_product_choose->execute();
    $pCategoryConversion = $query_product_choose->fetchAll();
    return $pCategoryConversion;
}
?>