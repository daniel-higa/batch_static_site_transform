<?php
function convert($html) {
   $text = ereg_replace('<h2.*/h2>', '', $html);
   $text = ereg_replace('<br/>', '\n', $text);
   $text = ereg_replace("\n", '<br/>', trim(strip_tags($text)));
   $text_ini = substr($text, 0, MAX_CHAR);
   $words = explode(' ', substr($text, MAX_CHAR, strlen($text) - MAX_CHAR));
   $text = $text_ini;
   if (!empty($words)) {
       $text .= $words[0];
   }
   return $text;
}

function format_jpg($file, $save_path) {
    $img = new SimpleImage();
    $img->load($file->path);
    if ($img->getWidth() > MAX_WIDTH) {
        $img->resizeToWidth(MAX_WIDTH);
    }
    if ($img->getHeight() > MAX_HEIGHT) {
        $img->resizeToHeight(MAX_HEIGHT);
    }
    $img->watermark(WATERMARK);
    $img->save($save_path . '/' . $file->getName());
}

function getSortImages($html) {
    $dom = new DOMDocument();
    $dom->loadHTML($html);
    $imgs = $dom->getElementsByTagName('img');
    $imgs_path = array();
    foreach ($imgs as $img) {
       $imgs_path[] = $img->getAttribute('src');
    }
    return $imgs_path;
}
