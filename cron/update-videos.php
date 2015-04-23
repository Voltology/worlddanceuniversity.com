<?php
include("/home/wdu/sites/worlddanceuniversity.com/stage/.local.inc.php");
$query = "DELETE FROM albums";
$mysqli->query($query);
$query = "UPDATE videos SET deleted=1";
$mysqli->query($query);

$vimeo = new Vimeo(VIMEO_CONSUMER_TOKEN, VIMEO_CONSUMER_SECRET);
$vimeo->setToken(VIMEO_ACCESS_TOKEN, VIMEO_ACCESS_SECRET);
$results = $vimeo->call('vimeo.albums.getAll', array('user_id' => VIMEO_USER, 'sort' => 'oldest'));
foreach ($results->albums->album as $result) {
  $album = new Album();
  if ($album->exists($result->id)) {
    $album->set();
    $album->setTitle($result->title);
    $album->save();
  } else {
    $album->setTitle($result->title);
    $album->setVimeoId($result->id);
    $album->add();
  }
}

$albums = new Album();
foreach ($albums->getAll2() as $album) {
  $allvids = $vimeo->call('vimeo.albums.getVideos', array('album_id' => $album['vimeo_id'], 'full_response' => '1', 'per_page' => '100'));
  $count = 0;
  foreach ($allvids->videos->video as $vid) {
    $video = new Video();
    if ($video->exists($vid->id)) {
      $video->setAlbum($album['vimeo_id']);
      $video->setDescription($vid->description);
      $video->setPlays($vid->number_of_plays);
      $video->setThumbnail($vid->thumbnails->thumbnail[1]->_content);
      $video->setTitle($vid->title);
      $video->setVimeoId($vid->id);
      $video->save();
    } else {
      $video->setAlbum($album['vimeo_id']);
      $video->setDescription($vid->description);
      $video->setPlays($vid->number_of_plays);
      $video->setThumbnail($vid->thumbnails->thumbnail[1]->_content);
      $video->setTitle($vid->title);
      $video->setVimeoId($vid->id);
      $video->add();
    }
    $count++;
  }
  echo $count . " total videos " . $album['title'] . "\n";
  if ($count > 0) {
    $query = "UPDATE albums SET deleted=0 WHERE id='" . $album['id'] . "'";
    $mysqli->query($query);
  }
}
?>
