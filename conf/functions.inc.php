<?php

function pre($array) {
  echo "<pre class='pre'>";
  print_r($array);
  echo "</pre>";
}

function CreateImage($size, $source, $dest) {
  
  $imgsize = GetImageSize($source);
  $width = $imgsize[0];
  $height = $imgsize[1];
  
  //Grösse generieren
  $new_width = $size;
  $new_height = ceil(($size * $height) / $width);
  //Wenn es höher wird
  if($new_height > $size) {
    $new_height = $size;
    $new_width = ceil(($size * $width) / $height);
  }
  
  switch($imgsize[2]) {
    case 1:
      $im = ImageCreateFromGIF($source);
      break;
    case 2:
      $im = ImageCreateFromJPEG($source);
      break;
    case 3:
      $im = ImageCreateFromPNG($source);
      break;
  }
  
  $new_im = imagecreatetruecolor($new_width, $new_height);
  imagecopyresampled($new_im, $im, 0, 0, 0, 0, $new_width, $new_height, ImageSX($im), ImageSY($im));
  
  //Interlaced = 1
  imageinterlace($new_im, 0);
  
  // Bilderstellen
  if(file_exists($dest))
    unlink($dest);
  
  ImageJPEG($new_im, $dest);
}
?>