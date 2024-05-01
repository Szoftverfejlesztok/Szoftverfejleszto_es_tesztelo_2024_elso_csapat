<?php
ini_set('display_errors', '0');
ini_set('display_startup_errors', '0');
error_reporting(0);
/*Ez tartalmazza a hibakezeléshez az AppError osztályt és függvényeit.*/

class AppError {
    
    public function __construct() {
        // modális ablak kinézetének beemelése
        require("view/modal.html");
        // JavaScript kód inicializálása
        echo "<script> var myModal = new bootstrap.Modal(document.getElementById('errorModal'),{keyboard: false});</script>";
    }

    public function ShowModal($type, $msg) {
        // modális ablak megjelenítése
        echo "<script>
        document.getElementById('modalTitle').innerText = '".addslashes($type)."';
        document.getElementById('modalText').innerText = '".addslashes($msg)."';
        myModal.show();
        </script>";
    }

    public function PutLog($msg) {
        // hibaüzenet naplózása
        global $config;
        error_log("[".date("Y.m.d h:i:s")."] ".$msg.PHP_EOL, 3, $config->getErrorLog());
    }
}

?>