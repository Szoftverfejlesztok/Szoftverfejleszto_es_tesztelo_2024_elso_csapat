<!--A regisztrációs oldal űrlapja-->
<div class="container">
    <div class="row">
        <div class="col-md-8 mx-auto text-center">
            <div class="logo">
                <img src="img/Padlas_logo.png.png" alt="Padlás" width="400px" height="400px">
            </div>
        </div>
    </div>
    <div class="container-login">
        <form action="<?php echo $_SERVER["PHP_SELF"]?>" method="POST">
            <!--10. bejelentkezési űrlapkészítés--> 
            <fieldset>
            <legend class="text-center fs-1 fw-bold">Regisztráció</legend><br><br>
                <input class="input" type="text" minlength=3 name="username" id="username" placeholder="Felhasználónév" required>
                <input  class="input" type="email" minlength=10 maxlength=35 name="email" id="email" placeholder="Email cím" required >
                <input class="input" type="password" minlength=10 name="password" id="password" placeholder="Jelszó" required>
                <input  class="input" type="password" minlength=10 name="password2" id="password2" placeholder="Jelszó megerősítése" required> 
                <div class = "text-center">
    
                    <input class="btn btn-secondary text-center" type="submit" value="Regisztráció" name="submitRegistration">
                    
                </div>
                <div class="text-center">
                    <a href="../Padlas/index.php">Vissza a bejelentkezés oldalra!</a>
                </div>
            </fieldset>
        </form>
    </div>
</div>


































<?php
/*<form class="login100-form validate-form" action="" method="post">
    <!-- <span class="login100-form-title p-b-26"> Login </span> -->

<span class="login100-form-title p-b-48"><i class="zmdi zmdi-font"></i></span>
<div class="wrap-input100 validate-input"data-validate="Valid email is: pelda@email.hu">
<input class="input100" type="text"name="email" id="email"placeholder="Email cím"/>
<span class="focus-input100"data-placeholder="Email címv"></span>
</div>

<span class="login100-form-title p-b-48"><i class="zmdi zmdi-font"></i></span>
<div class="wrap-input100 validate-input">
<input class="input100" type="text"name="username" id="username"placeholder="Felhasználónév"/>
<span class="focus-input100"data-placeholder="Felhasználónév"></span>
</div>

<div class="wrap-input100 validate-input" data-validate="Enter password">
<span class="btn-show-pass"><i class="zmdi zmdi-eye"></i></span>
<input class="input100"type="password"name="password" id="password"placeholder="Jelszó"/>
<span class="focus-input100"data-placeholder="Jelszó"></span>
</div>

<div class="wrap-input100 validate-input" data-validate="Enter password">
    <span class="btn-show-pass"><i class="zmdi zmdi-eye"></i></span>
    <input class="input100"type="password"name="password2" id="password2"placeholder="Jelszó újra"/>
    <span class="focus-input100"data-placeholder="Jelszó újra"></span>
</div>


    <!-- <div class="container-login100-form-btn">
      <div class="wrap-login100-form-btn">
        <div class="login100-form-bgbtn"></div>-->
<div class="text-center">
<input type="submit" value="Regisztráció" name="submitRegistration" class="btn btn-secondary">
</div>

<div class="text-center p-t-115">
<a class="txt2" href="../Padlas_0308/index.php"> Vissza a bejelentkezés oldalra! </a> <!--Ha rákattint a regisztrációra, akkor átvisz a regisztrációs oldalra.-->
</div>
</form>*/
?>