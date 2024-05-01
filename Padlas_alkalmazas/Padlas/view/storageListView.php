<!--A kilistázott tárolókat itt jelenítjük meg.-->
<?php
ini_set('display_errors', '0');
ini_set('display_startup_errors', '0');
error_reporting(0);
?>
<div id="storage">
    <form class= "container text-center searchform">
        <h2 class="text-center">A Padlás alkalmazában meglévő tárolók listája</h2>
        <p id="valasz"></p>
        <div class="d-flex justify-content-between px-3 py-2">
            <button id="ujtaroloLletrehoz"class="btn btn-primary" onclick="createNewStorage()">Új tároló létrehozása</button>
        </div>
    </form>
        
        <div class="row">
            <div class="col">
            </div>
                <div id="storageList" class="p-3 col-6">
                    <!-- TÁROLÓK táblázata -->
                    <table class="table table-hover table-responsive table-sm text-center">
                        <thead>
                            <tr class="table-info">
                                <th>Tároló neve</th>
                                <th>Termékek darabszáma</th>
                                <th>Termékek kivétele a tárolóból</th>
                                <th>Tároló törlése</th>
                            </tr>
                        </thead>
                        <tbody id = "storagetable">
                            <!-- Tárolók: -->
                            <?php foreach($storageArray as $singleStorage){
                            ?>

                            
                                <tr id = "<?= $singleStorage["id"]?>">
                                    <td><?= $singleStorage["taroloNev"]?></td>
                                    <td><?= $singleStorage["keszlet"]?></td>
                                    <td><a href ="#"onclick="removeItemFromStorage(<?= $singleStorage["id"] ?>)" class="kivetel"><i class="fa fa-arrow-right"></i></a></td>
                                    <td><a href="#" onclick="deleteStorage(<?= $singleStorage["id"] ?>)" class="torles"><i class="fa fa-trash"></i></a></td>
                                </tr>
                            <?php
                            }
                            ?>

                        </tbody>
                    </table>
                </div>
            <div class="col">
            </div>
        </div>
</div>


<!--A GOMBRA KATTINTÁS UTÁN EZ A FÜGGYVÉNY HÍVÓDIK MEG: createNewStorage
Ez pedig egy AJAX kérést küld a szervernek,amely kérés egy PHP oldalt (a model mappában lévő storage.php) hív meg, amely kezeli az adatbázisba történő új rekord beszúrását.-->
<script>

    // I. ÚJ TÁROLÓ LÉTREHOZÁSA

    function createNewStorage() {
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4) {
            if (this.status == 200) {
                const tr = document.createElement('tr');
                tr.id = this.responseText;
                tr.innerHTML = 
                        '<td>'+this.responseText+'</td>'+
                        '<td>0</td>'+
                        '<td><a href ="#"onclick="removeItemFromStorage('+this.responseText+')" class="kivetel"><i class="fa fa-arrow-right"></i></a></td>'+
                        '<td><a href ="#" onclick="deleteStorage('+this.responseText+')"class="torles"><i class="fa fa-trash"></i></a></td>';
                    document.getElementById('storagetable').appendChild(tr);
                console.log(this.responseText);
            } else {
                console.error("Hiba a kérés során");
            }
        }
    };
    xhttp.open("GET", "model/createStorage.php?", true);
    xhttp.send();
}

//II. TERMÉKEK TÖRLÉSE TÁROLÓBÓL - Itt kellene, hogy egyből frissüljön, hogy hány db termék van az adott tárolóban. 


function removeItemFromStorage(id){
    var row = document.getElementById(id);
    var keszlet = parseInt(row.children[1].innerText); // A tárolóban lévő termékek számának megszerzése
    console.log(keszlet);
    if(keszlet > 0){
        if(!window.confirm('Ha kiveszed a termékeket a tárolóból, akkor a termékek törlődnek az alkalmazásból! Biztos, hogy folytatni akarod a termékek kivételét?')){
            return 0;
        }
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4) {
                if (this.status == 200) {
                    console.log("Sikeres AJAX kérés");
                    console.log(this.responseText);
                    row.children[1].innerText ='0';
                } else {
                    console.error("Hiba a kérés során");
                }
            }
        };
        xhttp.open("GET", "model/removeItemFromStorage.php?id=" + id, true);
        console.log('GET');
        xhttp.send();
    } else {
        // Ha a tároló üres, megjelenik egy figyelmeztetés
        window.alert('A tároló üres, onnan nem lehet terméket kivenni.');
    }
}

//TÁROLÓ TÖRLÉSE: VALAMIÉRT FOLYAMATOSAN FLUGRIK AZ ALERT, NEM ENGEDI TÖRÖLNI A TERMÉKET. 


function deleteStorage(id){
    var row = document.getElementById(id).parentElement.parentElement;
    var keszlet = parseInt(row.children[1].innerText); // A tárolóban lévő termékek számának megszerzése    
    var keszlet2 = (document.getElementById(id).innerText).trim();    
    var keszlet2_hossz = (document.getElementById(id).innerText).trim().length;
    
    const keszlet3 = (document.getElementById(id).innerText).trim().split(String.fromCharCode(9));
    var darab = parseInt(keszlet3[1]);
    


    if(darab == 0){    
        let text = "Biztos törlöd a "+keszlet3[0]+" tárolót?";        
        if (confirm(text) == true) {
            // Ha a készlet 0, akkor az adott tároló törölhető
            var xhttp = new XMLHttpRequest();        
            xhttp.onreadystatechange = function() {
            if (this.readyState == 4) {                
                if (this.status == 200) {                    
                    console.log("Sikeres AJAX kérés");
                    console.log(this.responseText);                    
                    row.parentNode.removeChild(row); // Távolítsuk el a sort a táblából
                } else {
                    console.error("Hiba a kérés során");
                }
            }
        };        
        xhttp.open("GET", "model/deleteStorage.php?id=" + id, true);
        xhttp.send();
        }    
                             
    } else {
        // Ha a készlet nagyobb, mint 0, akkor a tárolóban vannak termékek, és megjelenik egy figyelmeztetés
        window.alert('A tárolóban termékek vannak, így azt nem lehet törölni!');
    }
    <?php echo "location.href = 'storageList.php';";?>
}

/* function deleteStorage(id,obj){
    console.log(obj);
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4) {
            if (this.status == 200) {
                console.log("Sikeres AJAX kérés");
                console.log(this.responseText);
                //  töröljük az adott sort a táblázatból, DE JELENLEG CSAK EGY KATTINTÁS EREJÉIG TÖRLI
                //var row = document.getElementById(id);
                var row = obj.parentElement.parentElement;
                //console.log(row);
                //row.parentNode.removeChild(row);
            } else {
                console.error("Hiba a kérés során");
            }
        }
    };
    xhttp.open("GET", "model/deleteStorage.php?id=" + id, true);
    xhttp.send();
} */

</script>
