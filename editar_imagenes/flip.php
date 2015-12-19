<?php

$imgFlip = new Zebra_Image();
$imgFlip->source_path = "../upload/$_REQUEST[imagen].jpg";
$imgFlip->target_path = "../upload/$_REQUEST[imagen].jpg";
if ($_REQUEST[text_flip_vertical]=="-1") {
    if ($imgFlip->$img->flip_vertical()) {
        echo "ok";
    } else {
        switch ($imgFlip->error) {

            case 1:
                echo 'Source file could not be found!';
                break;
            case 2:
                echo 'Source file is not readable!';
                break;
            case 3:
                echo 'Could not write target file!';
                break;
            case 4:
                echo 'Unsupported source file format!';
                break;
            case 5:
                echo 'Unsupported target file format!';
                break;
            case 6:
                echo 'GD library version does not support target file format!';
                break;
            case 7:
                echo 'GD library is not installed!';
                break;
        }
        echo "Error flip_vertical ---- ";
    }
}