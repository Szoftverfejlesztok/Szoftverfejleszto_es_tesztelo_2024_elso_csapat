<?php
ini_set('display_errors', '0');
ini_set('display_startup_errors', '0');
error_reporting(0);
//session_start();
$username = $_SESSION['username'];
$password = $_SESSION['password'];
$useremail = $_SESSION['user_email'];
?>
<br>
<div class="container">
    <div class="container-login">
        <form action="<?php echo $_SERVER["PHP_SELF"]?>" method="POST">
            <!--10. bejelentkezési űrlapkészítés--> 
            <fieldset>
                <legend class="text-center">Felhasználónév módosítása</legend>
                <input class="input" type="text" minlength=3 name="username" id="username_mod" value="<?=$username?>">
                <input class="input" type="email" minlength="10" maxlength="35" name="email" id="email" value="<?=$useremail?>" readonly>
                <div class="text-center">
                    <input class="btn btn-secondary text-center" type="button" value="Új felhasználónév mentése" onclick="submitModifyUserName()">
                </div>
            </fieldset>
        </form>
        <form id='modifyform' action="<?php echo $_SERVER["PHP_SELF"]?>" method="POST" onsubmit=" return jelszoModosit()">
            <fieldset>
                <legend class="text-center">Jelszó módosítása</legend>
                <input class="input" type="password" minlength=10 name="password_old" id="password_old" placeholder="Régi jelszó" required>
                <input class="input" type="password" minlength=10 name="password" id="password" placeholder="Új jelszó" required>
                <input class="input" type="password" minlength=10 name="password2" id="password2" placeholder="Új jelszó megerősítése" required> 
                <input class="input" type="hidden" name="useremail" id="useremail" value="<?=$useremail?>">
                <div class="text-center">
                    <input class="btn btn-secondary text-center" type="submit" value="Módosítás mentése" name="submitModifyPassword">
                </div>
            </fieldset>
        </form>
            <form id='deleteuserform' action="<?php echo $_SERVER["PHP_SELF"]?>" method="POST" onsubmit= "return felhasznaloTorol()">
            <fieldset>
                <div class="text-center">
                    <input class="btn btn-secondary text-center" type="submit" value="Felhasználói adatok törlése" name="submitDeleteUser">
                </div>
            </fieldset>
        </form>
    </div>
</div>
<script>
function jelszoModosit(){
    return window.confirm("Biztos, hogy módosítani akarja a jelszavát?");
    
}

function felhasznaloTorol(){
    return window.confirm("Biztos, hogy törölni akarja a felhasználóját?");
}

function submitModifyUserName() {
    var username = document.getElementById('username_mod').value.trim();
    var useremail = document.getElementById('email').value.trim();
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function() {
        if (this.readyState == 4) {
            if (this.status == 200) {
                console.log(this.responseText);
                if( this.responseText == 'true'){
                    window.alert("Sikeres módosítás");
                }else{
                    window.alert("Sikertelen módosítás");
                }
                
                
            } else {
                console.error("Hiba a kérés során");
            }
        }
    };
    
    // GET kérés küldése a model/modifyUsername.php fájlra
    xhr.open("GET", "model/modifyUsername.php?username=" + encodeURIComponent(username) + "&useremail=" + encodeURIComponent(useremail), true);
    xhr.send();
}


</script>























<!--Ide kell megírni a felhasználó oldalon (profil.php) található, a felhasználói adatok módosításához szükséges form-ot.-->
<!--<form action="<?php //echo $_SERVER["PHP_SELF"]?>" method="post"> 
    <fieldset>
    <legend id="legendmodifyuserdata" class="text-center p-t-115">Felhasználói adatok</legend>
        <div class="mb-3">
            <div class="mb-3 text-center">
                <label for="username">Felhasználó név</label>
                <input type="text" name="username" id="username" placeholder="Felhasználónév">
            </div>
            <div class="mb-3 text-center">
                <label for="email">Email cím</label>
                <input type="text" name="email" id="email" placeholder="Email cím">
            </div>
            <div class="mb-3 text-center">
                <label for="password">Jelszó</label>
                <input type="password" name="password" id="password" placeholder="Jelszó">
            </div>
            <div class="mb-3 text-center">
                <label for="jelszomegerosites">Jelszó megerősítése</label>
                <input type="password" name="password2" id="password2" placeholder="Jelszó megerősítése">
            </div>
        </div>
        <div class="mb-3 text-center">
                <input  class="text-center" type="submit" value="Regisztráció" name="submitRegistration">
        </div>
        <div class="text-center p-t-115">
            <a class="txt2" href="../Padlas_0308/index.php"> Vissza a bejelentkezés oldalra! </a> Ha rákattint a regisztrációra, akkor átvisz a regisztrációs oldalra.
        </div>
    </fieldset>
    </form>
    <div style="text-align: center;
        <img src="img/padlas_logo_150x150_nobg_fukszia.png" alt="Padlás logó" width="200" height="200">
    </div>-->