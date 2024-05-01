<!--A termékkeresés lekérdezésének eredményét jelenítjük itt meg, tehát ez a terméklista kinézetéért felelős-->
<?php
ini_set('display_errors', '0');
ini_set('display_startup_errors', '0');
error_reporting(0);
?>
<div class="products" class="conatiner-fluid"><!--Belerakom az egész lekérdezés eredményét egy div-be-->

    <?php
    foreach ($topicArray as $singleTopic){ // for ciklussal végigmegyek a $topicArray listába előzetesen lekérdezett összes aktív témán és kigyűjtöm az alábbi adatokat: 
    ?>

        <div class="topicList p-3">
            <div class="d-flex justify-content-between px-3 py-2 my-1 border border-1 border-secondary rounded-2">
                <a href="topic.html" class=""><?= $topicArray["title"]  ?></a> <!--Kigyűjtöm az összes aktív topicnak a címét. -->
                <span>
                    <span title="Hozzászólások száma"><?= $topicArray["postNumber"]?></span> <!--Kigyűjtöm az összes topic hozzászólásának a számát.-->
                    | <span title="Utolsó hozzászólás"><?= $topicArray["createTime"]?></span> <!--Kigyűjtöm az összes aktív topic utolsó hozzászólásának dátumát.-->
                    | <a href="#" title="Jelentés" class="reportIcon"><i class="fa fa-bell"></i></a>
                </span>
            </div>
        </div>

    <?php
    }
    ?>
</div>