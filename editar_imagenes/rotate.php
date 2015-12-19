<?php

//SI EL ROTAR ES MAYOR A 0 SE ROTA DE LO CONTRARIO BRINCAMOS A HACER CROP
if ($_REQUEST[rotar] > 0) {
    $imgRotate = new Zebra_Image();
    $imgRotate->source_path = "../upload/temporal_$_REQUEST[imagen]";
    $imgRotate->target_path = "../upload/temporal_$_REQUEST[imagen]";
    $imgRotate->jpeg_quality = 100;
    $imgRotate->preserve_aspect_ratio = true;
    $imgRotate->enlarge_smaller_images = true;
    $imgRotate->preserve_time = true;
    if ($imgRotate->rotate(intval($_REQUEST[rotar]))) {
        require_once './crop.php';
    } else {
        switch ($imgRotate->error) {

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
        require_once './crop.php';
    }
}
//SI LA ROTACION ES = 0 SE BRINCA Y PASAMOS AL CROP
else{
    //echo "NO SE ROTO</br>";
    require_once './crop.php';
}