#!/usr/bin/php
<?php

require_once('includes/readfiles.php');
require_once('includes/functions.php');
require_once('includes/simpleimage.php');
require_once('includes/config.php');

echo "start\n";

if (isset($_SERVER['argv']) and isset($_SERVER['argv'][1]) and isset($_SERVER['argv'][2])) {
    $in_dir = $_SERVER['argv'][1];
    $out_dir = $_SERVER['argv'][2];
} else {
    die("Uso\nphp convert directorio_de_entrada directorio_de_salida\n\n");
}

if (is_dir($in_dir)) {
    echo "$in_dir Existe\n";
}  else {
    die("$in_dir NO Existe o no es un Directorio \n");
}

if (is_dir($out_dir) or is_file($out_dir)) {
    //die("$out_dir Ya Existe\n");
} else {
    mkdir($out_dir);
}


$tree = new ReadFiles($in_dir);
$dirs = $tree->getDirs();

foreach ($dirs as $item) {
    echo "Directorio: " . $item->getName() . "\n";
    $content = '';
    if ($html = $item->getFirstHTML()) {
        $content = file_get_contents($html->path);
    }
    $jpgs = $item->getAllImages();
    $sort_jpgs = getSortImages($content);
    

    if (!empty($content) and !empty($jpgs)) {
        mkdir($out_dir .'/' . $item->getName(), 0770, true);
        $text = convert($content);
        file_put_contents($out_dir .'/' . $item->getName() . '/1.txt', $text);
        file_put_contents($out_dir .'/' . $item->getName() . '/images.txt', implode("\n", $sort_jpgs));
        while ($jpg = array_pop($jpgs)) {
            format_jpg($jpg, $out_dir .'/' . $item->getName());
        }
    }
}
