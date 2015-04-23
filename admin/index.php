<?php
require("../.local.inc.php");
$page = $_GET['p'] ? $_GET['p'] : 'home';
$action = $_GET['a'] ? $_GET['a'] : null;
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <title>World Dance University &raquo; Admin</title>
    <meta charset='utf-8'>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/css/admin.css" />
    <link rel="stylesheet" href="/css/font-awesome.css">
    <script>var API_VERSION = 'v0.1';</script>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
    <?php if ($user->getIsLoggedIn()) { ?>
    <script src="/js/admin.js"></script>
    <script src="/js/ajax.js"></script>
    <?php } ?>
    <!--[if IE 7]>
    <link rel="stylesheet" href="/css/font-awesome-ie7.min.css">
    <![endif]-->
  </head>
  <body>
    <div style="position: absolute; top: 5px; right: 5px;"><strong><?php echo date("F j, Y, g:i a", time()); ?></strong></div>
    <table width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td colspan="2" valign="middle" class="header">
          <div class="header-logo">
            <a href="/"><img src="/img/logo.png" /></a>
          </div>
          <div class="header-admin">Administration Area</div>
        </td>
      </tr>
      <tr>
        <td valign="top" class="sidebar-cell" width="120">
          <div id="sidebar" style="line-height: 1.3em;">
            <?php if ($user->getIsLoggedIn()) { ?>
            <div class="<?php if ($page === "home") { echo "active"; } ?>menuitem"><a href="?p=home">Home</a></div>
              <div class="<?php if ($page === "videos") { echo "active"; } ?>menuitem"><a href="?p=videos">Videos</a></div>
              <div class="<?php if ($page === "users") { echo "active"; } ?>menuitem"><a href="?p=users">Users</a></div>
              <div class="<?php if ($page === "contact") { echo "active"; } ?>menuitem"><a href="?p=contact">Contact</a></div>
              <div class="<?php if ($page === "settings") { echo "active"; } ?>menuitem"><a href="?p=settings">settings</a></div>
            <a href="/logout.php" class="logout">Log Out</a>
            <?php } ?>
          </div>
        </td>
        <td valign="top" align="left" class="page">
          <?php if ($user->getIsLoggedIn()) { ?>
            <?php require($page . ".php"); ?>
          <?php } else { ?>
            <form method="post" action="/login-redirect.php">
              <table border="0" cellpadding="0" cellspacing="0" class="edit-table">
                <tr class="tableheader">
                  <th colspan="2">User Information</th>
                </tr>
                <tr>
                  <td class="edit-label">Email</td>
                  <td class="edit-field"><input type="text" name="email" /></td>
                </tr>
                <tr>
                  <td class="edit-label">Password</td>
                  <td class="edit-field"><input type="password" name="password" /></td>
                </tr>
                <tr>
                  <td class="edit-field" colspan="2" align="right">
                    <input type="hidden" name="logintype" value="admin" />
                    <button type="submit" class="button"><i class="icon-arrow-up"></i> Log In</button>
                  </td>
                </tr>
              </table>
            </form>
          <?php } ?>
        </td>
      </tr>
      <tr>
        <td colspan="2">
          <div id="footer">&copy; <?php echo date("Y"); ?> <a href="<?php echo DESKTOP_URL; ?>">World Dance University</a>, All Rights Reserved.</div>
        </td>
      </tr>
    </table>
    <br />
    <br />
    <br />
  </body>
</html>
<?php
if ($_GET['debug'] === "true" || ($_SESSION['debug'] === "true" && $_GET['debug'] !== "false")) {
  $mtime = explode(' ', microtime());
  $totaltime = $mtime[0] + $mtime[1] - $starttime;
  printf("<div style=\"background-color: #fff; color; #000; text-align: center;\">Page loaded in %.3f seconds.</div>", $totaltime);
}
?>
