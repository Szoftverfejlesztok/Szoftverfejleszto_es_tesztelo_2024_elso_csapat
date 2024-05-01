<?php
ini_set('display_errors', '0');
ini_set('display_startup_errors', '0');
error_reporting(0);
/* MINDEN TERMÉKEKKEL KAPCSLATOS SQL UTASÍTÁST IDE KELL ÍRNI: 
 TERMÉKEK LEKÉRDEZÉSÉT ÉS ÚJ TERMÉK LÉTREHOZÁSÁHOZ AZ SQL UTASÍTÁSOKAT IDE ÍRJUK MEG KÜLÖN-KÜLÖN FÜGGVÉNYBEN! */

$error = ""; // az error üzenetnek véltozó
$msg = ""; // a message-nek változó
class productException extends Exception{}


class Products{
    private $dbconn;



public function __construct($db){ // a topic osztály konstruktora,ami egy paraméteres konstruktor.
    if (empty($db)){
      throw new Exception("Hibás adatbázis kapcsolat");
    }
    $this->dbconn = $db;
  }





public function getSearched($text, $limit = 0, $offset = 0) {
  try {
    $textLike = "%$text%";
    $sql = "SELECT topic, title, description, createTime, postNumber FROM padlas WHERE topicId=0";
    $sql .= $limit > 0 ? " LIMIT $limit OFFSET $offset" : "";
    $query = $this->dbconn->prepare($sql);
    $query->bindValue("textLike",$textLike,PDO::PARAM_STR);
    $query->execute();
    return $query->fetchAll();
  } catch (PDOException $e){
    echo "";
  }
}


 
 
  /* I. Ide kell jönnie egy olyan univerzális lekérdezésnek / lekérdezéseknek, ami/amik mindig aszerint kérdezi le
   a termékeket az adatbázisból, amilyen adatokat a felhasználó kiválasztott a termékkereső form-jában.*/


  /*II. Ide kell jönnie az új termék létrehozásáért felelős SQL utasításnak. */

public function createProduct() {
  if (trim($_POST["nem"]) != ""  || (trim($_POST["tarolo"]) != "")) {
    try {
      $nem = trim($_POST["nem"]);
      $kategoria = trim($_POST["kategoria"]);
      $tipus = trim($_POST["tipus"]);
      $meret = trim($_POST["meret"]);
      $tarolo = trim($_POST["tarolo"]);
      // var_dump($nem." ".$kategoria." ".$tipus." ".$meret." ".$tarolo);        
        
        $sqlNewProduct = "INSERT INTO termekek (letrehozas_datuma,nem,kategoriaID,meret,taroloID) VALUES (NOW(), :nem, :tipus, :meret, :tarolo);";
        $insertNewProduct = $this->dbconn->prepare($sqlNewProduct);    
        $insertNewProduct->execute(array("nem"=>$nem,"tipus"=>$tipus,"meret"=>$meret,"tarolo"=>$tarolo));

        // Lekérjük, hogy milyen AUTO_INCREMENT id-val rögzítette a terméket

        $sql = "SELECT LAST_INSERT_ID()";
        $query = $this->dbconn->prepare($sql);
        $query->execute();
        $id = $query->fetch(PDO::FETCH_ASSOC); 
               
        header("Location:createNewPhotos.php?nem=".$nem."&kategoria=".$kategoria."&tipus=".$tipus."&meret=".$meret."&tarolo=".$tarolo."&id=".$id["LAST_INSERT_ID()"]);      
     
    } catch(Exception $e) {
      echo "Valami gáz van!".$e->getMessage();
      
    }
  }
  
  //$_POST["nem"]="";
}






public function uploadPhoto($path, $id) {
  $sqlNewPhoto = "INSERT INTO fotok (fotoEleresiUt, fotoDatum, termekId) VALUES (:eleresiUt, NOW(), :id)";
  $insertNewPhoto = $this->dbconn->prepare($sqlNewPhoto);
  $insertNewPhoto->bindValue("eleresiUt",$path,PDO::PARAM_STR);
  $insertNewPhoto->bindValue("id",$id,PDO::PARAM_INT);
  $insertNewPhoto->execute();
}






public function list_Product($nem,$kategoria,$meret) {
  try {
    $sql_list_product="SELECT nem,kategoriaID,meret,taroloID FROM termekek WHERE nem=:nem AND kategoriaID=:kategoriaID AND meret=:meret";
    $query_list_product = $this->dbconn->prepare($sql_list_product);    
    $query_list_product->execute(array("nem"=>$nem,"kategoriaID"=>$kategoria,"meret"=>$meret));
    return $query_list_product->fetchAll();
  } catch (PDOException $e) {
    echo "";
  }
}






public function list_Storage($id) {
  try {
    $sql_List_Storage="SELECT termekek.id, termekek.nem, termekek.kategoriaID, termekek.meret FROM termekek WHERE taroloID=:id";
    $query_List_Storage= $this->dbconn->prepare($sql_List_Storage);
    $query_List_Storage->bindValue("id",$id,PDO::PARAM_INT);
    $query_List_Storage->execute();
    return $query_List_Storage->fetchAll();    
  } catch (PDOException $e) {
    echo "Valami gáz van!!!";
  }
}






public function data_Marker_Conversion($pGender,$pCategory,$psize) {
    $conversion = array(); // A visszaküldendő adatoknak egy tömböt hozunk lét4e
    $gender = ["","Fiú","Lány"];
    $conversion[] = $gender[$pGender];
    $category = "";
    
    $sql_product_choose="SELECT nev FROM kategoria WHERE id=:pCategory";
    $query_product_choose = $this->dbconn->prepare($sql_product_choose);
    $query_product_choose->bindValue("pCategory",$pCategory,PDO::PARAM_INT);
    $query_product_choose->execute();
    $pCategoryConversion = $query_product_choose->fetchAll();
    
    foreach ($pCategoryConversion as $x) {    
      $category=$x[0];
    }    
    $conversion[] = $category;    
    $size = ["","50","56","62","68","74","80","86","92","98","104","110","116","122","128","134","140","146","152","158","164","170","18","19","20","21","22","23","24","25","26","27","28","29","30","31","32","33","34","35","36","37","38","39","40"];
    $conversion[] = $size[$psize];    
    return $conversion;
}






public function first_Photo($id) {
  $sql_first_photo="SELECT fotoEleresiUt FROM fotok WHERE termekId=:id LIMIT 1";
  $query_first_photo = $this->dbconn->prepare($sql_first_photo);
  $query_first_photo->bindValue("id",$id,PDO::PARAM_INT);
  $query_first_photo->execute();
  //var_dump($query_first_photo);
  return $query_first_photo;
}






public function list_Photos($id) {
  $sql_photo_list = "SELECT fotoEleresiUt FROM fotok WHERE termekId=:id";
  $query_photo_list = $this->dbconn->prepare($sql_photo_list);
  $query_photo_list->bindValue("id",$id,PDO::PARAM_INT);
  $query_photo_list->execute();
  //$query_photo_list->fetchAll();
  return $query_photo_list;
}






public function delete_Photo($id,$single_photo_path) {
  $delete_single_photo_ok = false;
  $sql_delete_photo = "DELETE FROM fotok WHERE termekId=:id AND fotoEleresiUt=:photo_path";
  $query_delete_photo = $this->dbconn->prepare($sql_delete_photo);
  $query_delete_photo->bindValue("id",$id,PDO::PARAM_INT);
  $query_delete_photo->bindValue("photo_path",$single_photo_path,PDO::PARAM_STR);
  $query_delete_photo->execute();
  if (!empty($single_photo_path)) {
    if(file_exists($single_photo_path)) {
      if (unlink($single_photo_path)) {
        $delete_single_photo_ok = true;
      }
    }
  }
  return $delete_single_photo_ok;
}






public function search_Product($productid) {
  $sql_search_product = "SELECT nem,kategoriaID,meret,taroloID FROM termekek WHERE id=:id";
  $query_search_product = $this->dbconn->prepare($sql_search_product);
  $query_search_product->bindValue("id",$productid,PDO::PARAM_INT);
  $query_search_product->execute();
  //$query_search_product->fetchAll();
  //var_dump($query_search_product);
  return $query_search_product;
}






public function category_Type($category) {
  $sql_category_type = "SELECT szulo_id,nev FROM kategoria WHERE id=:category";
  $query_category_type = $this->dbconn->prepare($sql_category_type);
  $query_category_type->bindValue("category",$category,PDO::PARAM_INT);
  $query_category_type->execute();
  return $query_category_type;
}







public function delete_Product($termekId) {
  $delete_photo_ok = false;
  $sql_request_photo_path="SELECT fotoEleresiUt FROM fotok WHERE termekId=:id";
  $query_request_photo_path=$this->dbconn->prepare($sql_request_photo_path);
  $query_request_photo_path->bindValue("id",$termekId,PDO::PARAM_INT);
  $query_request_photo_path->execute();
  $photo_paths_array=$query_request_photo_path->fetchAll();  
  if (!empty($photo_paths_array)) {    
    for ($i = 0; $i < count($photo_paths_array); $i++) {      
      $photo_path = ($photo_paths_array[$i]['fotoEleresiUt']);      
      if (file_exists($photo_path)) {        
        if (!unlink($photo_path)) {
          $delete_photo_ok = false;
        }
        $photo_path_parts = explode('/',$photo_path);
                $new_text = array();
                for ($i=count($photo_path_parts)-1;$i>=0;$i--) {
                  $text_2 = array();
                  $new_text_2;
                  for ($j=0;$j<=$i;$j++) {
                    $text_2[] = $photo_path_parts[$j];                    
                  }
                  $new_text[] = "/".$text_2[$i];
                  
                  $new_text_2 = implode('/',$text_2);
                  $new_text_3 = (rtrim($new_text_2,$new_text[count($photo_path_parts)-$i-1]));
                  
                  if (is_dir($new_text_3)) {
                    if ($dh = opendir($new_text_3)) {                      
                      if (readdir($dh)) {
                        rmdir($new_text_3);                        
                        //echo $new_text_3;
                      }
                      closedir($dh);
                    }
                  }
                }        
      }
    }
  }
  $sql_delete_photos="DELETE FROM fotok WHERE termekId=:id";
  $query_delete_photos=$this->dbconn->prepare($sql_delete_photos);  
  $query_delete_photos->bindValue("id",$termekId,PDO::PARAM_INT);
  $query_delete_photos->execute();
  $sql_delete_product="DELETE FROM termekek WHERE id=:id";
  $query_delete_product=$this->dbconn->prepare($sql_delete_product);
  $query_delete_product->bindValue("id",$termekId,PDO::PARAM_INT);
  $query_delete_product->execute();
  echo $skjuajk;
  return $delete_photo_ok;
}








public function modify_Product($product) {
  $modifyProduct = [false,false,""];
  $selected_szulo_id;
  $control_text = "";
  // $product: 0: nem (1 - Fiú, 2 - Lány), 1: kategória (ruha v cipő), 2: tipus (pl bakancs v fehérnemű), 3: méret, 4: tároló 5: termékmódosítás (fix), 6: tároló, 7: termékId
  //echo "produc a product valtozoban: <br>";
  //var_dump($product);
  
  //echo "<br> ######## ---------";
  $sql_product_datas = "SELECT * FROM termekek WHERE id=:id";
  $query_product_datas = $this->dbconn->prepare($sql_product_datas);
  $query_product_datas->bindValue("id",$product[7],PDO::PARAM_INT);  
  $query_product_datas->execute();
  $product_datas=$query_product_datas->fetchAll();
  // $product_datas: 0: 'id', 1: 'letrehozas_datuma' 2: nem (1 - Fiú, 2 - Lány), 3: 'kategoriaID', 4: 'meret'
  //var_dump($product_datas);
  foreach($product_datas as $x) {            
    $nem=$x['nem'];
    $tipus=$x['kategoriaID'];            
    $meret=$x['meret'];
    $tarolo=$x['taroloID'];            
  }
  $sql_product_type = "SELECT szulo_id FROM kategoria WHERE id=:tipus";
  $query_product_type = $this->dbconn->prepare($sql_product_type);
  $query_product_type->bindValue("tipus",$tipus,PDO::PARAM_INT);
  $query_product_type->execute();
  $selected_product_type = $query_product_type->fetchAll();
  foreach($selected_product_type as $y) {
    $selected_szulo_id = $y[0];
  }
  //var_dump ($selected_product_type);
  //echo"<br>";
  //echo ($selected_szulo_id);
  if ($selected_szulo_id=='1') {
    $selected_szulo_id='ruha';
  } else {
    $selected_szulo_id='cipo';
  }
  
  echo"<br>";
  echo $selected_szulo_id;
  var_dump ($selected_szulo_id);


  if (($selected_szulo_id==$product[1]) || (($product[1]=='ruha' && !(!($product[2]>"2" && $product[2]<"8") && !($product[3]>"0" && $product[3]<"22"))) || ($product[1]=='cipo' && !(!($product[2]>"7" && $product[2]<"14") && !($product[3]>"21" && $product[3]<"45"))))) {

    //if (!(($selected_szulo_id!=$product[1]) || (($selected_szulo_id=='ruha' && !($product[3]>"0" && $product[3]<"22")) || ($selected_szulo_id=='cipo' && !($product[3]>"21" && $product[3]<"45"))))) {

    
      echo " Termék: Méret: ".$meret." Tipus: ".$tipus." Nem: ".$nem."  Tároló: ".$tarolo;
      //echo "<br>Módosítás: Méret: ".$product[3];
      
      // Megvizsgáljuk, hogy 'ruha' termékről van-e szó, és ha korábban is ruha volt, akkor ok, vagy ha korábban nem 'ruha' volt, kell legyen hozzá tipus, és méret is
      // Ha a feltétel igaz, az modifyProduct változó akkor lesz igaz, ha a ruhának megfelelő a tipus, és/vagy a méret
      if ($product[1]=="ruha" && (($tipus>"2" && $tipus<"8") || !(empty($product[2]) || empty($product[3])))) $modifyProduct[0]=(((empty($product[2]) || ($product[2]>"2" && $product[2]<"8")) && (($product[3]>"0" && $product[3]<"22") || empty($product[3]))) && (!(empty($product[3]) && empty($product[2])) || ((!empty($product[4]) && $product[4]!=$tarolo) || $product[0]!=$nem)));
      if ($product[1]=="cipo" && (($tipus>"7" && $tipus<"14") || !(empty($product[2]) || empty($product[3])))) $modifyProduct[0]=(((empty($product[2]) || ($product[2]>"7" && $product[2]<"14")) && (($product[3]>"21" && $product[3]<"45") || empty($product[3]))) && (!(empty($product[3]) && empty($product[2])) || ((!empty($product[4]) && $product[4]!=$tarolo) || $product[0]!=$nem)));
      
        //echo "ruha és üres és jó a méret";
        //$modifyProduct[0] = true;
        echo ($modifyProduct[0])?"igaz":"hamis";
        //echo ($modifyProduct[1])?"true":"false";
      
      if ($modifyProduct[0]) {                
        if (!empty($product[3]) && $product[3]!=$meret) {
          $control_text .= " Méretben vagyunk ";
          $sql_size_choose="UPDATE termekek SET meret=:meret WHERE id=:id";
          $update_size_choose=$this->dbconn->prepare($sql_size_choose);
          $update_size_choose->bindValue("meret",$product[3],PDO::PARAM_INT);
          $update_size_choose->bindValue("id",$product[7],PDO::PARAM_INT);
          $update_size_choose->execute();
          $control_text .= " Módusult a méret! ";
        } else {
          $control_text .= " Nem módosult a méret. ";
        }
        if (!empty($product[0]) && $product[0]!=$nem) {
          $sql_size_choose="UPDATE termekek SET nem=:nem WHERE id=:id";
          $update_size_choose=$this->dbconn->prepare($sql_size_choose);
          $update_size_choose->bindValue("nem",$product[0],PDO::PARAM_INT);
          $update_size_choose->bindValue("id",$product[7],PDO::PARAM_INT);
          $update_size_choose->execute();
          $control_text .= " Módusult a nem! ";
        } else {
          $control_text .= " Nem módosult a nem. ";
        }
        if (!empty($product[4]) && $product[4]!=$tarolo) {
          $sql_size_choose="UPDATE termekek SET taroloID=:taroloID WHERE id=:id";
          $update_size_choose=$this->dbconn->prepare($sql_size_choose);
          $update_size_choose->bindValue("taroloID",$product[4],PDO::PARAM_INT);
          $update_size_choose->bindValue("id",$product[7],PDO::PARAM_INT);
          $update_size_choose->execute();
          $control_text .= " Módusult a tároló! ";
        } else {
          $control_text .= " Nem módosult a tároló. ";
        }
        if (!empty($product[2]) && $product[2]!=$tipus) {
          $sql_size_choose="UPDATE termekek SET kategoriaID=:kategoriaID WHERE id=:id";
          $update_size_choose=$this->dbconn->prepare($sql_size_choose);
          $update_size_choose->bindValue("kategoriaID",$product[2],PDO::PARAM_INT);
          $update_size_choose->bindValue("id",$product[7],PDO::PARAM_INT);
          $update_size_choose->execute();
          $control_text .= " Módusult a tipus! ";
        } else {
          $control_text .= " Nem módosult a tipus. ";
        }
     }

    } else {
      $modifyProduct[1] = true;
    }
    //$modifyProduct[1] = true;
    $modifyProduct[2] = $control_text;
  return $modifyProduct;
}






public function search_engine($product_gender,$product_category,$product_type,$product_size) {
  /*
  $is_szuloid = "van";
  echo "<br>product_gender: ".((!empty($product_gender))?$product_gender:" üres nem; ");
  echo "<br>product_category: ".((!empty($product_category))?$product_category:" üres kategória; ");
  echo "<br>product_type: ".((!empty($product_type))?$product_type:" üres tipus; ");
  echo "<br>product_size: ".((!empty($product_size))?$product_size:" üres méret; ");
  */
  $sql_search_size = (!empty($product_size))?"meret=:product_size":"meret!=:product_size";  // Ternary, ha nem üres a "méret" mező
  $sql_search_gender = (!empty($product_gender))?"nem=:product_gender":"nem!=:product_gender";  // Ternary, ha nem üres a "nem" mező
  $sql_search_type = (!empty($product_type))?"kategoriaID=:product_category":"kategoriaID!=:product_category";  // Ternary, ha nem üres a "tipus" mező
  switch($product_category) {  // EElenőrizzük a "kategória" mezőt
    case "ruha":
      $szulo_id='1';  // Ha ruha nemű, 1-es kód
      break;
    case "cipo":
      $szulo_id='2';  // Ha lábbeli, 2-es kód
      break;
    default: {
      $szulo_id='';  // Egyébként meg üres mező
      //$is_szuloid = 'nincs';
    }      
  }
  $sql_search_category = (!empty($product_category))?"kategoriaID IN (SELECT id FROM kategoria WHERE szulo_id=:szulo_id)":"kategoriaID IN (SELECT id FROM kategoria WHERE szulo_id!=:szulo_id)";
  $sql_search_engine = "SELECT * FROM termekek WHERE ".$sql_search_size." AND ".$sql_search_gender." AND ".$sql_search_type." AND ".$sql_search_category;
  //$sql_search_engine = (!empty($product_size))?"SELECT * FROM termekek WHERE meret=:product_size" :"SELECT * FROM termekek WHERE meret!=:product_size" ;  
  
  /*
  echo $sql_search_engine;
  echo "<br>szulo_id: ".$szulo_id;
  echo "<br>is_szuloid:".$is_szuloid;
  */

  $query_search_engine = $this->dbconn->prepare($sql_search_engine);
  $query_search_engine->bindValue("product_size",$product_size,PDO::PARAM_INT);
  $query_search_engine->bindValue("product_gender",$product_gender,PDO::PARAM_INT);
  $query_search_engine->bindValue("product_category",$product_type,PDO::PARAM_INT);
  $query_search_engine->bindValue("szulo_id",$szulo_id,PDO::PARAM_INT);
  $query_search_engine->execute();
  //$is_searched_array = $query_search_engine->fetchAll();    
  return $query_search_engine->fetchAll();
}
  /*III.Ide kell jönnie a termékadatok módosításáért felelős SQL utasításnak. */


  /*Ide kell jönnie a termék törlését végző sql utasításoknak */

}



?>