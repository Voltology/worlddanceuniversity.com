<?php
require(".local.inc.php");
session_start();
$user = new User(DB::$conn, $_POST['email'], $_POST['password']);
if (!$user->getIsLoggedIn()) {
  header("Location: /?error=true");
} else {
  $user->setToken(md5($_POST['email'] . "-" . rand(1,99999) . "-" . microtime()));
  $user->save();
  setcookie('email', $user->getEmail());
  setcookie('token', $user->getToken());
  if ($_POST['logintype'] === "admin") {
    header("Location: /admin/");
  } else {
    header("Location: /");
  }
}
?>
