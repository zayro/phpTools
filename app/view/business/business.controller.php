<?php

require_once '../../../run.php';

use library\system;

$system = new system();
$system->validar_session();

use app\view\business;
use library\upload;
use library\file;

$upload = new upload();
$file = new file();
$business = new business();

if (isset($_FILES['files'])) {
    $ruta = '../../../app/assets/images/business/';

    if (is_dir($ruta)) {
        $upload->carga_archivos($ruta, $_FILES['files']);
        $upload->validar_error();
        $upload->guardar_archivo();

        $ruta_imagen = './app/assets/images/business/';

        echo $business->business_change_imagen($nombre, $telefono, $direccion, $ruta_imagen.$upload->nombre[0], $id);
    }
} else {
    echo  $business->business_change($nombre, $telefono, $direccion, $id);
}
