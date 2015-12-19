<?php
$imgCrop = new Zebra_Image();
$imgCrop->source_path = "../upload/temporal_$_REQUEST[imagen]";
$imgCrop->target_path = "../upload/temporal_$_REQUEST[imagen]";
$imgCrop->jpeg_quality = 100;
$imgCrop->preserve_aspect_ratio = true;
$imgCrop->enlarge_smaller_images = true;
$imgCrop->preserve_time = true;
//SI EL CROP SE REALIZO CORRECTAMENTE RENOMBRAMOS LA IMAGEN
if ($imgCrop->crop(intval($_REQUEST[jrac_crop_x]), intval($_REQUEST[jrac_crop_y]), intval($_REQUEST[jrac_crop_width]) + intval($_REQUEST[jrac_crop_x]), intval($_REQUEST[jrac_crop_height]))) {
    if (rename("../upload/temporal_$_REQUEST[imagen]", "../upload/$_REQUEST[imagen]")) {
        $tiempoRedir = 5;
        ?>
        <html>
            <head>
                <title>Redimencion de imagen</title>
                <meta http-equiv="refresh" content="<?php echo $tiempoRedir; ?>; url=<?php echo "../index.php?module=EDITA_EDITAR_IMAGEN&action=index&imagen=$_REQUEST[imagen]&resultado=ok"; ?>" />
                <link href='//maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css' rel='stylesheet'  type='text/css'/>
                <script src="https://code.jquery.com/jquery-1.8.2.min.js" type="text/javascript"></script>
                <script>
                    $(document).ready(function () {
                        var count = <?php echo $tiempoRedir; ?>;
                        var counter = setInterval(timer, 1000); //1000 will  run it every 1 second
                        function timer() {
                            count = count - 1;
                            $("#cuenta").text(count);
                            if (count <= 0) {
                                clearInterval(counter);
                                //counter ended, do something here
                                return;
                            }
                        }
                    });
                </script>
            </head>
            <body>
                <div class="container">
                    <div class="row clearfix">
                        <div class="col-md-12 column">
                            <div class='alert alert-success' style="margin-top:20px;">
                                Su imagen se esta redimencionando, porfavor espere <span id="cuenta"></span> Seg.
                            </div>
                            <img src="../images/engrane.gif" class="img-responsive center-block" style="width: 800px;" />
                        </div>
                    </div>
                </div>
            </body>
        </html>                    

        <?php
    }
} else {
    switch ($imgCrop->error) {

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
