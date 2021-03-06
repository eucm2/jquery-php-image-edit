<script type="text/javascript" src="js/jquery-1.6.2.js"></script>
<link rel="stylesheet" type="text/css" href="css/jquery-ui.css" />
<script type="text/javascript" src="js/jquery-ui.js"></script>
<script type="text/javascript" src="js/sh_main.min.js"></script>
<script type="text/javascript" src="js/sh_javascript.min.js"></script>
<link type="text/css" rel="stylesheet" href="css/sh_style.css" />
<script type="text/javascript">
    $(document).ready(function () {
        sh_highlightDocument();
    });
</script>
<link rel="stylesheet" type="text/css" href="css/style.jrac.css" />
<script type="text/javascript" src="js/jquery.jrac.js"></script>
<link rel="stylesheet" type="text/css" href="css/style.css" />
<style>
    /*MOSTRAMOS Y OCULTAMOS LOS TEXT QUE MUESTRAN EL ALTO Y ANCHO DE LA IMAGEN Y DEL CROP*/
    .ocultar{
        display: none;
    }
</style>
<script type="text/javascript">
    //GRADOS ANGULARES DE 0 A 360 QUE ESTA ROTADA LA IMAGEN
    var rotar = 0;
    //POSICION TOP (ABSOLUTA) EN LA QUE SE ENCUENTRA LA IMAGEN
    var arriba = 0;
    //POSICION LEFT (ABSOLUTA) EN LA QUE SE ENCUENTRA LA IMAGEN
    var izquierda = 0;
    //PROPORCION ENTRE ANCHO Y ALTO
    var proporcion;
    //ANCHO DE ORIGINAL DE LA IMAHEN
    var ancho;
    //ALTO DE ORIGINAL DE LA IMAHEN
    var alto;
    //NUEVO ALTO DE IMAGEN DEPUES DE ENCOJERLA
    var nuevoAlto;
    //NUEVO ANCHO DE IMAGEN DEPUES DE ENCOJERLA
    var nuevoAncho;
    var menosPixeles;
    $(document).ready(function () {
        var timer = setInterval(function () {
            var imagen = document.getElementById('imagen');
            //TOMAMOS EL ANCHO DE LA IMAGEN
            ancho = imagen.clientWidth;
            //TOMAMOS EL ALTO DE LA IMAGEN
            alto = imagen.clientHeight;
            //console.log(ancho);
            //SI EL ANCHO ES MAYOR QUE 0 APLICA LA FUNCION
            if (ancho > 0) {
                clearInterval(timer);
                //SI EL ANCHO ES MAYOR A 780 SE REGRESA A 780
                if (ancho > 780) {
                    nuevoAncho = 780;
                    proporcion = nuevoAncho / ancho;
                    nuevoAlto = alto * proporcion;
                }
                //SI EL ANCHO ES MENOR O IGUAL A 780 SE DEJA ESE TAMAÑO
                else {
                    nuevoAncho = ancho;
                    nuevoAlto = alto;
                }
                //ESTA VARIABLE AYUDA A RESTAR PIXELES AL LEFT Y AL TOP PARA QUE SE PEQUE LA DERECHA
                menosPixeles = (nuevoAncho - nuevoAlto) / 2;
                //FUNCION QUE PONE UN RECUADRO PARA RECORTAR
                $('#imagen').jrac({
                    'crop_width': nuevoAncho - 2,
                    'crop_height': nuevoAlto - 2,
                    'crop_x': 0,
                    'crop_y': 0,
                    'image_width': nuevoAncho,
                    'viewport_onload': function () {
                        var $viewport = this;
                        var inputs = $viewport.$container.parent('.pane').find('.coords input:text');
                        var events = ['jrac_crop_x', 'jrac_crop_y', 'jrac_crop_width', 'jrac_crop_height', 'jrac_image_width', 'jrac_image_height'];
                        for (var i = 0; i < events.length; i++) {
                            var event_name = events[i];
                            // Register an event with an element.
                            $viewport.observator.register(event_name, inputs.eq(i));
                            // Attach a handler to that event for the element.
                            inputs.eq(i).bind(event_name, function (event, $viewport, value) {
                                $(this).val(value);
                                //PEGA LA IMAGEN A LA DERECHA Y ARRIBA
                                ajustarArriba();
                                //SI LA IMAGEN PIEDE SUS MEDIDAS ORIGINALES SE MODIFICA EL HIDDEN "hecerResize"=1 DE LO CONTRARIO SE QUEDA EN 0
                                hecerResize();
                            }).change(event_name, function (event) {
                                var event_name = event.data;
                                $viewport.$image.scale_proportion_locked = $viewport.$container.parent('.pane').find('.coords input:checkbox').is(':checked');
                                $viewport.observator.set_property(event_name, $(this).val());
                            });
                        }
                        $viewport.$container.append('<div>Image natual size: ' + $viewport.$image.originalWidth + ' x ' + $viewport.$image.originalHeight + '</div>');
                    }
                }).bind('jrac_events', function (event, $viewport) {
                    //PONER LAS VARIALES X,Y ANCHO Y ALTO DEL CROP Y EL ANCHO DE LA IMAGEN
                    var inputs = $(this).parents('.pane').find('.coords input');
                });
                $("#rotar_derecha").click(function () {
                    //PASAMOS UN 1 POSITIVO PARA QUE GIRE A LA DERECHA
                    alRotar();
                    $("#rotar").val(rotar);
                    return false;
                });
            } else {
                window.alert("se repite");
            }
        }, 1000);
    });
    //CUANDO SE AJUSTA EL TAMAÑO DE LA IMAGEN SE TIENE QUE PEGAR ARRIBA
    function ajustarArriba() {
        if (rotar == 90) {
            arriba = (((((parseInt($("#jrac_image_width").val())))) - (parseInt($("#jrac_image_height").val()))) / 2);
            izquierda = (((parseInt($("#jrac_image_width").val())) - (parseInt($("#jrac_image_height").val()))) / 2) * (-1);
        } else if (rotar == 270) {
            arriba = (((((parseInt($("#jrac_image_width").val())))) - (parseInt($("#jrac_image_height").val()))) / 2);
            izquierda = (((parseInt($("#jrac_image_width").val())) - (parseInt($("#jrac_image_height").val()))) / 2) * (-1);
        }
        $("#imagen").css("top", arriba + "px");
        $("#imagen").css("left", izquierda + "px");
    }
    //ROTAR LA IMAGEN A LA DERECHA
    function alRotar() {
        if (rotar == 0) {
            rotar = 90;
            arriba = (((((parseInt($("#jrac_image_width").val())))) - (parseInt($("#jrac_image_height").val()))) / 2);
            izquierda = (((parseInt($("#jrac_image_width").val())) - (parseInt($("#jrac_image_height").val()))) / 2) * (-1);
        } else if (rotar == 90) {
            rotar = 180;
            arriba = 0;
            izquierda = 0;
        } else if (rotar == 180) {
            rotar = 270;
            arriba = (((((parseInt($("#jrac_image_width").val())))) - (parseInt($("#jrac_image_height").val()))) / 2);
            izquierda = (((parseInt($("#jrac_image_width").val())) - (parseInt($("#jrac_image_height").val()))) / 2) * (-1);
        } else if (rotar == 270) {
            rotar = 0;
            arriba = 0;
            izquierda = 0;
        }
        //PEGAMOS LA IMAGEN ARRIBA Y A LA DERECHA
        $("#imagen").css("top", arriba + "px");
        $("#imagen").css("left", izquierda + "px");
        //ROTAR LA IMAGEN A LA DERECHA
        $("#imagen").css("transform", "rotate(" + rotar + "deg)");
    }
    //ESTA FUNCION VALIDA QUE LAS MEDIDAS DE LA IMAGEN SEAN LA ORIGINALES PERO SI SE HA CAMBIADO CAMBIA EL HIDDEN hecerResize=1 PARA QUE SE HAGA LA FUNCION resize DE ZEBRA
    function hecerResize() {
        if (parseInt($("#jrac_image_width").val()) == parseInt(ancho) && parseInt($("#jrac_image_height").val()) == parseInt(alto)) {
            $("#hecerResize").val("0");
            //console.log("#hecerResize=0");
        } else {
            $("#hecerResize").val("1");
            //console.log("#hecerResize=1");
        }
    }
</script>
<!--        MANDAMOS A EDITAR LA IMAGEN DESDE "editar_imagenes/resize.php" PARA QUE SE PUEDA MANDAR A EDITAR LA IMAGEN DESDE LA APP-->
<form action="editar_imagenes/resize.php" method="get" >
    <?php
    ini_set('display_errors', 'Off');
    if ($_REQUEST[imagen]) {
        $imagenCarga = "$_REQUEST[imagen]";
    } else {
        $imagenCarga = "default.jpg";
    }
    ?>
    <div class="pane clearfix">
        <div class="coords">
            <button id="rotar_derecha"><img src="images/rotar_derecha.png"/></button>
            <input type="text" id="jrac_crop_x" name="jrac_crop_x" class="ocultar"  />
            <input type="text" id="jrac_crop_y" name="jrac_crop_y" class="ocultar"  />
            <input type="text" id="jrac_crop_width" name="jrac_crop_width" class="ocultar"  />
            <input type="text" id="jrac_crop_height" name="jrac_crop_height" class="ocultar"  />
            Image width <input type="text" id="jrac_image_width" name="jrac_image_width"/>
            Image height <input type="text" id="jrac_image_height" name="jrac_image_height"/>
            <input type="checkbox" checked="checked"  class="ocultar"  />
            Grados <input type="text" id="rotar" name="rotar" value="0" />
            <input type="submit" name="editar_imagen" value="GUARDAR"/>
        </div>
        <img id="imagen" src="upload/<?php echo $imagenCarga . "?t=" . time(); ?>" />
    </div>
    <input type="hidden" name="imagen" value="<?php echo $imagenCarga; ?>"/>
    <input type="hidden" name="hecerResize" id="hecerResize" value="0"/>
</form>
        <?php


