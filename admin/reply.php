<?php
$contact = new Contact(DB::$conn, $_REQUEST['id']);
if ($action === "send") {
  if (trim($_POST['response']) !== "") {
    if ($contact->reply($_POST['response'])) {
      echo "<div class=\"success\">Your reply has been sent.</div>";
    } else {
      echo "<div class=\"error\">There was an error processing your request.<br />Please try again.</div>";
    }
  } else {
    echo "<div class=\"error\">Please make sure you fill out the reply field.</div>";
  }
}
?>
      <h1>Contact Us - Reply</h1>
      <form method="post" action="?p=reply&a=send">
        <table border="0" cellpadding="0" cellspacing="0" class="edit-table" width="500">
          <tr class="tableheader">
            <th colspan="2">Reply to Message</th>
          </tr>
          <tr>
            <td class="edit-label" width="120">Date Received</td>
            <td class="edit-field"><?php echo date("F j, Y, g:i a", $contact->getCreation()); ?></td>
          </tr>
          <tr>
            <td class="edit-label">Full Name</td>
            <td class="edit-field"><?php echo $contact->getFullName(); ?></td>
          </tr>
          <tr>
            <td class="edit-label">Email</td>
            <td class="edit-field"><?php echo $contact->getEmail(); ?></td>
          </tr>
          <tr>
            <td class="edit-label">Message</td>
            <td class="edit-field"><?php echo $contact->getMessage(); ?></td>
          </tr>
          <?php
          if ($contact->getResponse()) {
          ?>
          <tr>
            <td class="edit-label">Response</td>
            <td class="edit-field">
              <strong>Sent On <?php echo date("F j, Y, g:i a", $contact->getResponseTime()); ?><br /></strong>
              <?php echo $contact->getResponse(); ?>
            </td>
          </tr>
          <?php
          }
          ?>
          <tr>
            <td class="edit-label">Reply</td>
            <td class="edit-field"><textarea name="response" rows="7"></textarea></td>
          </tr>
          <tr>
            <td class="edit-field" colspan="2" align="right">
              <input type="hidden" name="id" value="<?php echo $_REQUEST['id']; ?>" />
              <button type="submit" class="button"><i class="icon-reply"></i> Send Reply</button>
            </td>
          </tr>
        </table>
      </form>
