<?php
$newuser = new User(DB::$conn);
$newuser->getById($_REQUEST['id']);
if ($action === "save") {
  $newuser->setFirstName($_POST['firstname']);
  $newuser->setLastName($_POST['lastname']);
  $newuser->setEmail($_POST['email']);
  $newuser->setRole($_POST['role']);
  if ($newuser->save()) {
    echo "<div class=\"success\">User has been saved.</div>";
  } else {
    echo "<div class=\"error\">There was an error processing your request.<br />Please try again.</div>";
  }
} else if ($action === "cancel" || $action === "reactivate" || $action === "suspend") {
  if ($newuser->getPaypalToken() !== "FREE-USER-ACCOUNT" && $newuser->getPaypalToken() !== "FREE-ADMIN-ACCOUNT") {
    $paypal->manage_subscription_status($newuser->getPaypalToken(), ucwords($action), "User was updated in the admin panel.");
    $profile = $paypal->get_profile_details($newuser->getPaypalToken());
    $newuser->setPaypalStatus($profile['STATUS']);
  }
  if ($newuser->save()) {
    echo "<div class=\"success\">User's account has been updated.</div>";
  } else {
    echo "<div class=\"error\">There was an error processing your request.<br />Please try again.</div>";
  }
}
?>
      <h1>Users - Edit</h1>
      <a href="?p=users">&laquo; Back to User List</a>
      <form method="post" action="?p=edit-user&a=save">
        <table border="0" cellpadding="0" cellspacing="0" class="edit-table" width="500">
          <tr class="tableheader">
            <th colspan="2">Edit User</th>
          </tr>
          <tr>
            <td class="edit-label">Paypal Token ID</td>
            <td class="edit-field">
              <?php echo $newuser->getPaypalToken(); ?><br />
              <?php if ($newuser->getPaypalStatus() === "Active") { ?>
              <strong>Account Actions:</strong>
              (<a href="#" onclick="User.Suspend(<?php echo $_REQUEST['id']; ?>)">Suspend</a>)
              (<a href="#" onclick="User.Cancel(<?php echo $_REQUEST['id']; ?>)">Cancel</a>)
              <?php } else if ($newuser->getPaypalStatus() === "Suspended") { ?>
              <strong>Account Actions:</strong>
              (<a href="#" onclick="User.Reactivate(<?php echo $_REQUEST['id']; ?>)">Reactivate</a>)
              (<a href="#" onclick="User.Cancel(<?php echo $_REQUEST['id']; ?>)">Cancel</a>)
              <?php } ?>
            </td>
          </tr>
          <tr>
            <td class="edit-label">Account Status</td>
            <td class="edit-field">
              <?php
              if ($newuser->getPaypalStatus() === "Active") {
                echo "<img src=\"/img/ico-bullet-green.png\" />";
              } else if ($newuser->getPaypalStatus() === "Suspended") {
                echo "<img src=\"/img/ico-bullet-yellow.png\" />";
              } else {
                echo "<img src=\"/img/ico-bullet-red.png\" />";
              }
              ?>
              <?php echo $newuser->getPaypalStatus(); ?>
            </td>
          </tr>
          <tr>
            <td class="edit-label">First Name</td>
            <td class="edit-field"><input type="text" name="firstname" value="<?php echo $newuser->getFirstName(); ?>" /></td>
          </tr>
          <tr>
            <td class="edit-label">Last Name</td>
            <td class="edit-field"><input type="text" name="lastname" value="<?php echo $newuser->getLastName(); ?>" /></td>
          </tr>
          <tr>
            <td class="edit-label">Email</td>
            <td class="edit-field"><input type="text" name="email" value="<?php echo $newuser->getEmail(); ?>"/></td>
          </tr>
          <tr>
            <td class="edit-label">Role</td>
            <td class="edit-field">
              <select name="role">
                <option value="2"<?php if ($newuser->getRole() == "2") { echo " selected"; } ?>>Administrator</option>
                <option value="1"<?php if ($newuser->getRole() == "1") { echo " selected"; } ?>>User</option>
              </select>
            </td>
          </tr>
          <tr>
            <td class="edit-field" colspan="2" align="right">
              <input type="hidden" name="id" value="<?php echo $_REQUEST['id']; ?>" />
              <button type="submit" class="button"><i class="icon-save"></i> Save User</button>
            </td>
          </tr>
        </table>
      </form>
      <a href="?p=users">&laquo; Back to User List</a>
      <p>
        <h3>Account Status Legend</h3>
        <ul>
          <li><strong>Active: </strong>Full access and an active Paypal account</li>
          <li><strong>Suspended: </strong>Temporarily disabled by an admin or by Paypal</li>
          <li><strong>Cancelled: </strong>Permanently removed from Paypal</li>
          <li><strong>New: </strong>Began registration process, but did not complete payment (archiving this user will allow them to re-register)</li>
        </ul>
      </p>
