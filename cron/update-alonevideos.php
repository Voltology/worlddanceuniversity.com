<?php
include("/home/wdu/sites/worlddanceuniversity.com/stage/.local.inc.php");

$vimeo = new Vimeo(VIMEO_CONSUMER_TOKEN, VIMEO_CONSUMER_SECRET);
$vimeo->setToken(VIMEO_ACCESS_TOKEN, VIMEO_ACCESS_SECRET);
$allvids = $vimeo->call('vimeo.videos.getAll', array('full_response' => '1', 'page' => '1', 'per_page' => '50'));
$pages = ceil($allvids->videos->total / 50);
$count = 1;
do {
  foreach ($allvids->videos->video as $vid) {
    $video = new Video();
    if (!$video->exists($vid->id)) {
      $video->setDescription($vid->description);
      $video->setPlays($vid->number_of_plays);
      $video->setThumbnail($vid->thumbnails->thumbnail[1]->_content);
      $video->setTitle($vid->title);
      $video->setVimeoId($vid->id);
      $video->add();
    }
    echo $count;
    $allvids = @$vimeo->call('vimeo.videos.getAll', array('full_response' => '1', 'page' => $count, 'per_page' => '50'));
    $count++;
  }
} while ($count < $pages);
?>
