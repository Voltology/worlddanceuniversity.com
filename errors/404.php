<?php
include(".local.inc.php");
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title>World Dance University - 404 Page Not Found</title>
    <meta name="description" content="World Dance University" />
    <meta name="author" content="World Dance University, developed by Populous Digital" />
    <meta name="viewport" content="width=device-width">
    <link rel="stylesheet" href="./css/style.css">
    <style>
    html, body {
      color: #fff;
      margin: 0;
      padding: 0;
    }
    .error-code {
      font-size: 96px;
      margin: 20px;
    }
    .error-desc {
      right: 20px;
      position: absolute;
      top: 0px;
    }
    </style>
  </head>
  <body>
    <h1 class="error-code">404</h1>
    <h3 class="error-desc">Page Not Found</h3>
    <script>
      (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
      (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
      m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
      })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

      ga('create', '<?php echo GA_CODE; ?>', 'auto');
      ga('send', 'pageview');

    </script>
  </body>
</html>
