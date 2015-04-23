<?php
if ($_SERVER['REQUEST_METHOD'] === "POST") {
  $errors = array();
  if (!$user->checkPassword($_POST['cur-password'])) {
    $errors[] = "Your password was incorrect";
  } else {
    if (strlen($_POST['new-password1']) < 6) { $errors[] = "The password must be at least 6 characters."; }
    if ($_POST['new-password1'] != $_POST['new-password2']) { $errors[] = "The new passwords do not match."; }
  }
  if (count($errors)) {
    echo "<div class=\"error\">";
    foreach ($errors as $error) {
      echo $error . "<br />";
    }
    echo "</div>";
  } else {
    if ($user->changePassword($_GET['id'])) {
      echo "<div class=\"success\">Your password has been updated.</div>";
    } else {
      echo "<div class=\"error\">There was an error processing your request.<br />Please try again.</div>";
    }
  }
}
?>
            <form method="post" action="?p=settings">
              <table border="0" cellpadding="0" cellspacing="0" class="edit-table" width="400">
                <tr class="tableheader">
                  <th colspan="2">Change Password</th>
                </tr>
                <tr>
                  <td class="edit-label" width="160">Current Password</td>
                  <td class="edit-field"><input type="password" name="cur-password" /></td>
                </tr>
                <tr>
                  <td class="edit-label">New Password</td>
                  <td class="edit-field"><input type="password" name="new-password1" /></td>
                </tr>
                <tr>
                  <td class="edit-label">Re-enter New Password</td>
                  <td class="edit-field"><input type="password" name="new-password2" /></td>
                </tr>
                <tr>
                  <td class="edit-field" colspan="2" align="right">
                    <button type="submit" class="button"><i class="icon-arrow-up"></i> Change Password</button>
                  </td>
                </tr>
              </table>
            </form>
