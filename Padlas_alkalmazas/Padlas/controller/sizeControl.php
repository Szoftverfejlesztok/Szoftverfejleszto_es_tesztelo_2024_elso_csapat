<?php
ini_set('display_errors', '0');
ini_set('display_startup_errors', '0');
error_reporting(0);
function sizeControl($gender,$category,$type,$size,$id,$tarolo) {    
    $genderChoose = array("","Fiú","Lány");
    if ($category=="ruha") {
        $category="Ruha";
        if ($gender==1) {            
            $typeChoose = array("","","","Felső","Alsó","Egybe ruha","Kiegészítő","Fehérnemű");
            $sizeChoose = array("","50","56","62","68","74","80","86","92","98","104","110","116","122","128","134","140","146","152","158","164","170");
        } elseif ($gender==2) {
            $typeChoose = array("","","","Felső","Alsó","Egybe ruha","Kiegészítő","Fehérnemű");
            $sizeChoose = array("","50","56","62","68","74","80","86","92","98","104","110","116","122","128","134","140","146","152","158","164","170");
        }
    } elseif ($category=="cipo") {
        $category="Cipő";
        if ($gender==1) {
            $typeChoose = array("","","","","","","","","Szabadidőcipő","Sportcipő","Bakancs","Papucs","Szandál","Körömcipő");
            $sizeChoose = array("","","","","","","","","","","","","","","","","","","","","","","18","19","20","21","22","23","24","25","26","27","28","29","30","31","32","33","34","35","36","37","38","39","40");
        } elseif ($gender==2) {
            $typeChoose = array("","","","","","","","","Szabadidőcipő","Sportcipő","Bakancs","Papucs","Szandál","Körömcipő");
            $sizeChoose = array("","","","","","","","","","","","","","","","","","","","","","","18","19","20","21","22","23","24","25","26","27","28","29","30","31","32","33","34","35","36","37","38","39","40");
        }
    }
    $productChoose = array($genderChoose[$gender],$typeChoose[$type],$sizeChoose[$size],$category,$id,$tarolo);
    return ($productChoose);
}
?>
