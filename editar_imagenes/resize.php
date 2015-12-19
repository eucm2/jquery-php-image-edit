<?php
ini_set('display_errors', 'Off');
require_once '../class/Zebra_Image.php';
//SI LA IMAGEN SII SE LE REALIZO RESIZE ENTRA A ESTA FUNCION
if ($_REQUEST[hecerResize] == "1") {
    $img = new Zebra_Image();
    $img->source_path = "../upload/$_REQUEST[imagen]";
    $img->target_path = "../upload/temporal_$_REQUEST[imagen]";
    $img->sharpen_images = true;
    $img->jpeg_quality = 100;
    $img->preserve_aspect_ratio = true;
    $img->enlarge_smaller_images = true;
    $img->preserve_time = true;
    if ($img->resize(intval($_REQUEST[jrac_image_width]), intval($_REQUEST[jrac_image_height]), ZEBRA_IMAGE_CROP_CENTER)) {
        //PASAMOS A ROTAR LA IMAGEN
        require_once './rotate.php';
    } else {
        switch ($img->error) {

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
    }
}
//SI LA IMAGEN NO SE LE REALIZO NINGUN RESIZE SE BRINCA ESTA FUNCION Y SE PASA DIRECTAMENTE A ROTAR PARA QUE NO PIERDA CALIDAD
else {
    //echo "NO RESIZE</br>";
    //COPIAMOS EL FICHERO PARA TENER UN TEMPORAL AL CUAL REALIZAR LAS MODIFICACIONES
    if (!copy("../upload/$_REQUEST[imagen]", "../upload/temporal_$_REQUEST[imagen]")) {
        echo "Error al copiar $fichero...\n";
    }
    //SI EL FICHERO SE COPIO BIEN PASAMOS A ROTAR
    else{
        //echo "SE COPIO</br>";
        require_once './rotate.php';
    }
    
}