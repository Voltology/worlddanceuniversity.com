<?php
require(".local.inc.php");
$response = $paypal->start_subscription();
$user->setPaypalToken($response['PROFILEID']);
$user->setPaypalStatus("Active");
$user->save();
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
  <body style="padding: 40px 20px;">
    <img src="img/logo.png" style="height: 80px; width: 140px;" />
    <h2 style="color: #fff;">Thank you!</h2>
    <p style="color: white;">Thank you for registering with World Dance University! To log in, please <a href="/" target="_parent">click here</a>!</p>
  </body>
</html>
