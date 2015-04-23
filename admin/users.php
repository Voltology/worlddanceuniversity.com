<?php
if ($action === "archive") {
  $deluser = new User(DB::$conn);
  if ($deluser->delete($_GET['id'])) {
    echo "<div class=\"success\">This user has been deleted.</div>";
  } else {
    echo "<div class=\"error\">There was an error processing your request.<br />Please try again.</div>";
  }
}
?>
      <h1>Users <form method="post" action="?p=users&a=search"><button class="button go right">Go</button> <input type="text" class="search right" name="query" placeholder="Search" /></form></h1>
      <button type="button" class="button" onclick="document.location = '?p=add-user'"><i class="icon-plus"></i> Add New User</button>
      <table border="0" cellpadding="4" cellspacing="0" width="100%" class="edit-table">
        <tr class="tableheader">
          <th>#</th>
          <th><a href="">Email</a></th>
          <th><a href="">First Name</a></th>
          <th><a href="">Last Name</a></th>
          <th><a href="">Role</a></th>
          <th><a href="">Joined</a> <i class="icon-chevron-up"></i></th>
          <th align="right" style="text-align: right;">Options</th>
        </tr>
        <?php
        $bgclass = array("odd","even");
        $count = 0;
        if ($action === "search") {
          $userlist = User::search($_POST['query']);
        } else {
          $userlist = User::getAll();
        }

        foreach ($userlist as $row) {
        ?>
          <tr class="<?php echo $bgclass[$count % 2]; ?>">
            <td><?php echo ($count + 1); ?></td>
            <td>
              <?php
              if ($row['paypal_status'] === "Active") {
                echo "<img src=\"/img/ico-bullet-green.png\" />";
              } else if ($row['paypal_status'] === "Suspended") {
                echo "<img src=\"/img/ico-bullet-yellow.png\" />";
              } else {
                echo "<img src=\"/img/ico-bullet-red.png\" />";
              }
              ?>
              <strong><?php echo $row['email']; ?></strong>
            </td>
            <td><?php echo $row['firstname']; ?></td>
            <td><?php echo $row['lastname']; ?></td>
            <td><?php echo ucwords($row['role']); ?></td>
            <td><?php echo date("F j, Y, g:i a", $row['creation']); ?></td>
            <td align="right" class="option-icons">
              <a href="?p=edit-user&id=<?php echo $row['id']; ?>" alt="Edit this User"><i class="icon-edit"></i></a>
              <a href="javascript: return false;" onclick="User.Delete(<?php echo $row['id']; ?>)" alt="Ban this User"><i class="icon-remove-sign"></i></a>
            </td>
          </tr>
        <?php
          $count++;
        }
        ?>
      </table>
      <button type="button" class="button" onclick="document.location = '?p=add-user'"><i class="icon-plus"></i> Add New User</button>
