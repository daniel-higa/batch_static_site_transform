<?php
function convert($html) {
   return strip_tags($html);
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

