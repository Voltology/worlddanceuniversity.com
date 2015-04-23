<?php
class Album {
  private $_id;
  private $_vimeo_id;
  private $_title;

  function __construct($id = null) {
    if ($id) {
      $this->_id = $id;
      $this->set();
    }
  }

  public function add() {
    global $mysqli;
    $query = sprintf("INSERT INTO albums SET vimeo_id='%s', title='%s'",
      $mysqli->real_escape_string($this->_vimeo_id),
      $mysqli->real_escape_string($this->_title));
    $mysqli->query($query);
  }

  public function exists($vimeo_id) {
    global $mysqli;
    $query = sprintf("SELECT id FROM albums WHERE vimeo_id='%s' LIMIT 1",
      $mysqli->real_escape_string($vimeo_id));
    if ($result = $mysqli->query($query)) {
      if ($result->num_rows > 0) {
        $result = $result->fetch_array(MYSQLI_ASSOC);
        $this->_id = $result['id'];
        $this->set();
        return true;
      } else {
        return false;
      }
    }
  }

  public static function getAll() {
    global $mysqli;
    $albums = array();
    $query = sprintf("SELECT * FROM albums WHERE deleted='0' ORDER BY title ASC");
    $query = $mysqli->query($query);
    while ($album = $query->fetch_array(MYSQLI_ASSOC)) {
      array_push($albums, $album);
    }
    return $albums;
  }

  public static function getAll2() {
    global $mysqli;
    $albums = array();
    $query = sprintf("SELECT * FROM albums ORDER BY title ASC");
    $query = $mysqli->query($query);
    while ($album = $query->fetch_array(MYSQLI_ASSOC)) {
      array_push($albums, $album);
    }
    return $albums;
  }

  public function save() {
    global $mysqli;
    $query = sprintf("UPDATE albums SET title='%s' WHERE id='%s' LIMIT 1",
      $mysqli->real_escape_string($this->_title),
      $mysqli->real_escape_string($this->_id));
    $result = $mysqli->query($query);
  }

  public function set() {
    global $mysqli;
    $query = sprintf("SELECT * FROM albums WHERE id='%s' LIMIT 1",
      $mysqli->real_escape_string($this->_id));
    $query = $mysqli->query($query);
    $album = $query->fetch_array(MYSQLI_ASSOC);
    $this->_id = $album['id'];
    $this->_vimeo_id = $album['vimeo_id'];
    $this->_title = $album['title'];
  }

  public function setTitle($title) {
    $this->_title = $title;
  }

  public function setVimeoId($vimeo_id) {
    $this->_vimeo_id = $vimeo_id;
  }
}
?>
