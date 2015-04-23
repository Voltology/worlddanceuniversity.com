              <h1>Welcome back, <?php echo $user->getFirstName(); ?>!</h1>
              <table border="0" cellpadding="0" cellspacing="0" class="edit-table" width="400">
                <tr class="tableheader">
                  <th colspan="2">Account Information</th>
                </tr>
                <tr>
                  <td class="edit-label" width="160">First Name</td>
                  <td class="edit-field"><?php echo $user->getFirstName(); ?></td>
                </tr>
                <tr>
                  <td class="edit-label">Last Name</td>
                  <td class="edit-field"><?php echo $user->getLastName(); ?></td>
                </tr>
                <tr>
                  <td class="edit-label">Email</td>
                  <td class="edit-field"><?php echo $user->getEmail(); ?></td>
                </tr>
                <tr>
                  <td class="edit-label">Member Since</td>
                  <td class="edit-field"><?php echo date("F j, Y, g:i a", $user->getCreation()); ?></td>
                </tr>
                <tr>
                  <td align="right" class="edit-field" colspan="2">Not you?  <a href="/logout.php">Click here</a> to log out.</td>
                </tr>
                <tr class="tableheader">
                  <th colspan="2">Site Information</th>
                </tr>
                <tr>
                  <td class="edit-label">Videos</td>
                  <td class="edit-field"><?php echo count(Video::getAll()); ?></td>
                </tr>
                <tr>
                  <td class="edit-label">Active Users</td>
                  <td class="edit-field"><?php echo User::getUserCount(DB::$conn, "Active"); ?></td>
                </tr>
                <tr>
                  <td class="edit-label">Suspended Users</td>
                  <td class="edit-field"><?php echo User::getUserCount(DB::$conn, "Suspended"); ?></td>
                </tr>
                <tr>
                  <td class="edit-label">Cancelled Users</td>
                  <td class="edit-field"><?php echo User::getUserCount(DB::$conn, "Cancelled"); ?></td>
                </tr>
                <tr>
                  <td class="edit-label">Archived Users</td>
                  <td class="edit-field"><?php echo User::getUserCount(DB::$conn, "Archived"); ?></td>
                </tr>
              </table>

