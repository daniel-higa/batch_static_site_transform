#!/usr/bin/php
<?php

require_once('readfiles.php');
require_once('config.php');

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
    echo $item->getName() . "\n";
    print_r($item->getFirstHtml());
}
