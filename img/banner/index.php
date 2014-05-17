<?php

$banners = array();

$d = opendir(".");
while($file = readdir($d)) {
    if($file == "." || $file == ".." || $file == basename($_SERVER['PHP_SELF']))
        continue;

    $banners[] = $file;
}

$select = rand(0, count($banners)-1);
$item = $banners[$select];
Header("Location: $item");
?>

