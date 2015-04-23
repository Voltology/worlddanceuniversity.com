<?php
class Video {
  private $_id;
  private $_vimeo_id;
  private $_definition;
  private $_key;
  private $_title;
  private $_description;
  private $_album;
  private $_plays;
  private $_thumbnail;
  private $_featured;
  private $_creation;

  function __construct($vimeo_id = null) {
    if ($vimeo_id) {
      $this->_vimeo_id = $vimeo_id;
      $this->set();
    }
  }

  public function add() {
    global $mysqli;
    $query = sprintf("INSERT INTO videos SET vimeo_id='%s', definition='%s', `key`='%s', title='%s', description='%s', album='%s', plays='%s', thumbnail='%s', creation='%s', deleted='0'",
      $mysqli->real_escape_string($this->_vimeo_id),
      $mysqli->real_escape_string($this->_definition),
      $mysqli->real_escape_string($this->_key),
      $mysqli->real_escape_string($this->_title),
      $mysqli->real_escape_string($this->_description),
      $mysqli->real_escape_string($this->_album),
      $mysqli->real_escape_string($this->_plays),
      $mysqli->real_escape_string($this->_thumbnail),
      $mysqli->real_escape_string(time()));
    $mysqli->query($query);
  }

  public function exists($vimeo_id) {
    global $mysqli;
    $query = sprintf("SELECT id,vimeo_id FROM videos WHERE vimeo_id='%s' LIMIT 1",
      $mysqli->real_escape_string($vimeo_id));
    if ($result = $mysqli->query($query)) {
      if ($result->num_rows > 0) {
        $result = $result->fetch_array(MYSQLI_ASSOC);
        $this->_id = $result['id'];
        $this->_vimeo_id = $result['vimeo_id'];
        $this->set();
        return true;
      } else {
        return false;
      }
    }
  }

  public static function getAll($order1 = "title", $order2 = "creation", $direction1 = "ASC", $direction2 = "ASC") {
    global $mysqli;
    $videos = array();
    $query = sprintf("SELECT videos.id,videos.vimeo_id,videos.definition,videos.key,videos.title,videos.description,videos.plays,videos.thumbnail,videos.featured,videos.album AS album_id,videos.creation,albums.title AS album FROM videos LEFT JOIN albums ON videos.album=albums.vimeo_id WHERE videos.deleted='0' ORDER BY %s %s, %s %s",
      $mysqli->real_escape_string($order1),
      $mysqli->real_escape_string($direction1),
      $mysqli->real_escape_string($order2),
      $mysqli->real_escape_string($direction2));
    $result = $mysqli->query($query);
    while ($video = $result->fetch_assoc()) {
      array_push($videos, $video);
    }
    return $videos;
  }

  public static function getFeatured() {
    global $mysqli;
    $query = sprintf("SELECT id,vimeo_id,definition,`key`,title,description,album,plays,thumbnail,featured,creation FROM videos WHERE featured='1' LIMIT 1");
    $result = $mysqli->query($query);
    return $result->fetch_array(MYSQLI_ASSOC);
  }

  public function save() {
    global $mysqli;
    $query = sprintf("UPDATE videos SET vimeo_id='%s', definition='%s', `key`='%s', title='%s', description='%s', album='%s', plays='%s', thumbnail='%s', featured='%s', deleted='0' WHERE id='%s'",
      $mysqli->real_escape_string($this->_vimeo_id),
      $mysqli->real_escape_string($this->_definition),
      $mysqli->real_escape_string($this->_key),
      $mysqli->real_escape_string($this->_title),
      $mysqli->real_escape_string($this->_description),
      $mysqli->real_escape_string($this->_album),
      $mysqli->real_escape_string($this->_plays),
      $mysqli->real_escape_string($this->_thumbnail),
      $mysqli->real_escape_string($this->_featured),
      $mysqli->real_escape_string($this->_id));
    $result = $mysqli->query($query);
  }

  public function set() {
    global $mysqli;
    $query = sprintf("SELECT id,vimeo_id,definition,`key`,title,description,album,plays,thumbnail,featured,creation FROM videos WHERE vimeo_id='%s'",
      $mysqli->real_escape_string($this->_vimeo_id));
    $result = $mysqli->query($query);
    $video = $result->fetch_array(MYSQLI_ASSOC);
    $this->_id = $video['id'];
    $this->_vimeo_id = $video['vimeo_id'];
    $this->_definition = $video['definition'];
    $this->_key = $video['key'];
    $this->_title = $video['title'];
    $this->_description = $video['description'];
    $this->_album = $video['album'];
    $this->_plays = $video['plays'];
    $this->_thumbnail = $video['thumbnail'];
    $this->_featured = $video['featured'];
    $this->_creation = $video['creation'];
  }

  public function setAlbum($album) {
    $this->_album = $album;
  }

  public function setDefinition($definition) {
    $this->_definition = $definition;
  }

  public function setDescription($description) {
    $this->_description = $description;
  }

  public function setFeatured($featured) {
    global $mysqli;
    $query = sprintf("UPDATE videos SET featured=0");
    $query = $mysqli->query($query);
    $this->_featured = $featured;
  }

  public function setKey($key) {
    $this->_key = $key;
  }

  public function setPlays($plays) {
    $this->_plays = $plays;
  }

  public function setThumbnail($thumbnail) {
    $this->_thumbnail = $thumbnail;
  }

  public function setTitle($title) {
    $this->_title = $title;
  }

  public function setVimeoId($vimeo_id) {
    $this->_vimeo_id = $vimeo_id;
  }

  public static function viewed($user_id, $video_id) {
  }
}
?>
