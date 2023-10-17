<?php
session_start();
if (isset($_COOKIE[session_name()])) {
    $_SESSION = array();
    setcookie(session_name(), "", time() - 3600);
    session_destroy();
}
header("location: ManagerLogin.php");
exit();
?>