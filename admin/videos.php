      <?php
      $o1 = $_GET["o1"] ? $_GET["o1"] : "creation";
      $o2 = $_GET["o2"] ? $_GET["o2"] : "title";
      $dir1 = $_GET["dir1"] == "0" || $_GET["dir1"] == null ? "ASC" : "DESC";
      $dir2 = $_GET["dir2"] == "0" || $_GET["dir2"] == null ? "ASC" : "DESC";
      if ($_GET['featured'] !== null) {
        $query = "UPDATE videos SET featured=0";
        $mysqli->query($query);
        $query = "UPDATE videos SET featured=1 WHERE id='" . $_GET['featured'] ."'";
        $mysqli->query($query);
      }
      ?>
      <h1>Videos</h1>
      <table border="0" cellpadding="4" cellspacing="0" width="100%" class="edit-table">
        <tr class="tableheader">
          <th width="36">#</th>
          <th><a href="?p=videos&o1=title&dir1=<?php echo $dir1 === "ASC" ? "1" : "0"; ?>&dir2=<?php echo $dir2 === "ASC" ? "0" : "1"; ?>">Title</a></th>
          <th><a href="?p=videos&o1=definition&dir=0">Definition</a></th>
          <th width="400">Key</th>
          <th width="140"><a href="?p=videos&o1=album&dir=0">Album</a></th>
          <th><a href="?p=videos&o1=plays&dir=0">Plays</a></th>
          <th><a href="?p=videos&o1=creation&dir=0">Creation</a> <i class="icon-chevron-up"></i></th>
        </tr>
        <?php
        $videos = Video::getAll($o1, $o2, $dir1, $dir2);
        $bgclass = array("odd","even");
        $count = 0;
        foreach ($videos as $video) {
        ?>
          <tr class="<?php echo $bgclass[$count % 2]; ?>">
            <td><a href="?p=videos&featured=<?php echo $video['id']; ?>"><i class="icon-star<?php if ($video['featured'] != "1") { echo "-empty"; }?>"></i></a> <?php echo ($count + 1); ?></td>
            <td><strong><?php echo $video['title']; ?></strong></td>
            <td>
              <select id="definition-<?php echo $video['vimeo_id']; ?>" name="definition-<?php echo $video['vimeo_id']; ?>"<?php if ($video['key'] !== "") { echo " disabled";} ?>>
                <option value="null">Select Definition</option>
                <option value="hd"<?php if ($video["definition"] === "hd") { echo " selected"; }?>>High</option>
                <option value="sd"<?php if ($video["definition"] === "sd") {echo " selected"; } ?>>Standard</option>
              </select>
            </td>
            <td>
              <?php
              if ($video['key'] !== "") {
                echo "<div id=\"add-key-" . $video['vimeo_id'] . "\">" . $video['key'] . " <i class=\"icon-ok\" style=\"color: #0d0\"></i> [<span onclick=\"video.editKey('" . $video['vimeo_id'] . "');\" style=\"cursor: pointer;\"><i class=\"icon-pencil\"></i> Edit</span>]</div>";
              } else {
                echo "<div id=\"add-key-" . $video['vimeo_id'] . "\"><a href=\"#\" onclick=\"video.addKey('" . $video['vimeo_id'] . "');\"><i class=\"icon-plus\"></i> Add</a></div>";
              }
              ?>
            </td>
            <td><?php if ($video['album'] !== "") { echo $video['album']; } else { echo "<strong style=\"color: red;\">No Album</scrong>"; }?></td>
            <td><?php echo $video['plays']; ?></td>
            <td><?php echo date("F j, Y, g:i a", $video['creation']); ?></td>
          </tr>
        <?php
          $count++;
        }
        ?>
      </table>
