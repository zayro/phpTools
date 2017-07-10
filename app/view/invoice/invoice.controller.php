<?php

require_once '../../../run.php';

use app\view\invoice;
use library\system;

$system = new system();

$objeto = new invoice();

$system->validar_session();

$url = explode(basename(__FILE__).'/', trim($returnValue, '/'));
$first = array_shift($url);
$id = array_pop($url);
$MaxArgs = count($url);

switch ($request) {
case 'GET':

if (isset($method) and $method == 'bill') {
    echo $result = $objeto->bill($desde, $hasta);
}

if (isset($method) and $method == 'search_bill') {
    echo $result = $objeto->search_bill($id);
}

break;

case 'POST':

$objeto->transaction('B');

$receives = json_decode(file_get_contents('php://input'), true);

$process = '';

foreach ($receives['maestro_factura'] as $key => $val) {
    $process .= $key.'='."'$val',";
}

$data = substr($process, 0, -1);

$objeto->master_invoice($data);

$count = count($receives['detalle_factura']);

if ($count > 1) {
    for ($i = 0; $i <= ($count - 1); ++$i) {
        $detalle_factura = '';
        $data_receives = $receives['detalle_factura'][$i];
        foreach ($data_receives as $key => $val) {
            if ($key == 'id_producto' or $key == 'cantidad' or $key == 'precio') {
                $detalle_factura .= $key.'='."'$val',";
            }
        }

        $data_detalle_factura = substr($detalle_factura, 0, -1);

        $objeto->detail_invoice($data_detalle_factura);
    }
} else {
    $detalle_factura = '';
    foreach ($receives['detalle_factura'][0] as $key => $val) {
        if ($key == 'id_producto' or $key == 'cantidad' or $key == 'precio') {
            $detalle_factura .= $key.'='."'$val',";
        }
    }

    $data_detalle_factura = substr($detalle_factura, 0, -1);

    $objeto->detail_invoice($data_detalle_factura);
}

$objeto->transaction('C');

$result = array(
'success' => true,
);
echo json_encode($result);

break;
}
