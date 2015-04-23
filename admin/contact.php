<?php
$contact = new Contact(DB::$conn);
if ($action === "archive") {
  if ($contact->delete($_GET['id'])) {
    echo "<div class=\"success\">Converstaion has been archived.</div>";
  } else {
    echo "<div class=\"error\">There was an error processing your request.<br />Please try again.</div>";
  }
}
?>
      <h1>Contact Us</h1>
      <table border="0" cellpadding="4" cellspacing="0" width="100%" class="edit-table">
        <tr class="tableheader">
          <th>#</th>
          <th><a href="">Full Name</a></th>
          <th><a href="">Email</a></th>
          <th><a href="">Message</a></th>
          <th><a href="">Response</a></th>
          <th><a href="">Date</a></th>
          <th align="right" style="text-align: right;">Options</th>
        </tr>
        <?php
        $bgclass = array("odd","even");
        $count = 0;
        foreach ($contact->getAllUnarchived() as $row) {
        ?>
          <tr class="<?php echo $bgclass[$count % 2]; ?>">
            <td><?php echo ($count + 1); ?></td>
            <td><?php echo $row['fullname']; ?></td>
            <td><strong><?php echo $row['email']; ?></strong></td>
            <td><?php echo $row['message']; ?></td>
            <td><?php echo $row['response']; ?></td>
            <td><?php echo date("F j, Y, g:i a", $row['creation']); ?></td>
            <td align="right" class="option-icons">
              <a href="?p=reply&id=<?php echo $row['id']; ?>" alt="Reply to this Message"><i class="icon-reply"></i></a>
              <a href="javascript: return false;" onclick="Contact.Delete(<?php echo $row['id']; ?>)" alt="Archive this Message"><i class="icon-remove-sign"></i></a>
            </td>
          </tr>
        <?php
          $count++;
        }
        ?>
      </table>
