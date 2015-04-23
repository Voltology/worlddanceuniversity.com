<?php
require(".local.inc.php");
session_start();
$_SESSION = array();
$user = array();
session_destroy();
setcookie("token");
header("Location: /");
?>
