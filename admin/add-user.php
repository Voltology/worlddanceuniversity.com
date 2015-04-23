<?php
$newuser = new User(DB::$conn);
$newuser->getById($_REQUEST['id']);
if ($action === "save") {
  $newuser->setFirstName($_POST['firstname']);
  $newuser->setLastName($_POST['lastname']);
  $newuser->setEmail($_POST['email']);
  $newuser->setPassword(md5($_POST['password']));
  $newuser->setRole($_POST['role']);
  if ($_POST['role'] == "1") {
    $newuser->setPaypalToken("FREE-USER-ACCOUNT");
  } else if ($_POST['role'] == "2") {
    $newuser->setPaypalToken("FREE-ADMIN-ACCOUNT");
  }
  if ($newuser->add()) {
    echo "<div class=\"success\">User has been added.</div>";
  } else {
    echo "<div class=\"error\">There was an error processing your request.<br />Please try again.</div>";
  }
}
?>
      <h1>Users - Add</h1>
      <a href="?p=users">&laquo; Back to User List</a>
      <form method="post" action="?p=add-user&a=save">
        <table border="0" cellpadding="0" cellspacing="0" class="edit-table" width="500">
          <tr class="tableheader">
            <th colspan="2">Add New User</th>
          </tr>
          <tr>
            <td class="edit-label">First Name</td>
            <td class="edit-field"><input type="text" name="firstname" value="<?php echo $_POST['firstname']; ?>" /></td>
          </tr>
          <tr>
            <td class="edit-label">Last Name</td>
            <td class="edit-field"><input type="text" name="lastname" value="<?php echo $_POST['lastname']; ?>" /></td>
          </tr>
          <tr>
            <td class="edit-label">Email</td>
            <td class="edit-field"><input type="text" name="email" value="<?php echo $_POST['email']; ?>"/></td>
          </tr>
          <tr>
            <td class="edit-label">Password</td>
            <td class="edit-field"><input type="text" name="password" value="<?php echo $_POST['password']; ?>"/></td>
          </tr>
          <tr>
            <td class="edit-label">Role</td>
            <td class="edit-field">
              <select name="role">
                <option value="2"<?php if ($_POST['role'] == "2") { echo " selected"; } ?>>Administrator</option>
                <option value="1"<?php if ($_POST['role'] == "1" || !$_POST['role']) { echo " selected"; } ?>>User</option>
              </select>
            </td>
          </tr>
          <tr>
            <td class="edit-field" colspan="2" align="right">
              <button type="submit" class="button"><i class="icon-save"></i> Save User</button>
            </td>
          </tr>
        </table>
      </form>
      <a href="?p=users">&laquo; Back to User List</a>
