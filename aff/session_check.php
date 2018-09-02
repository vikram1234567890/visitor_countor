<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION["indication_user"]) ) {
     echo '<script language="javascript">window.location.href ="login.php"</script>';
 exit;
}

?>