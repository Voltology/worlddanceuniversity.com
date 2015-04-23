<?php
require(".local.inc.php");
$user->setIsLoggedIn(false);
$user->remove();
setcookie("email", "");
setcookie("token", "");
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title>World Dance University</title>
    <meta name="description" content="World Dance University" />
    <meta name="author" content="World Dance University, developed by Populous Digital" />
    <link rel="stylesheet" href="./css/style.css">
  </head>
  <body style="padding: 20px;">
    <img src="img/logo.png" style="height: 80px; width: 140px;" />
    <h2 style="color: #fff;">Payment Error</h2>
    <p style="color: red;">There was a problem with your payment.  Please <a href="/index.php#join" target="_parent">click here</a> and try registering again.</p>
  </body>
</html>
