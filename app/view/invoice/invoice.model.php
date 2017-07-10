<?php

namespace app\view;

//require_once('../config/conexion.php');

use config\connect;

/**
 * CLASE GENERAL PRINCIPAL.
 *
 * En esta parte nos encargamos de crear los tipos de conexion del proyecto
 * para poder asi administrar los tipos de permisos de acceso
 */
class invoice extends connect
{
    public function __construct()
    {
        parent::__construct($_SESSION['datos']['identificacion'], $_SESSION['datos']['clave']);
    }

    public function bill($desde, $hasta)
    {
        $sql = 'SELECT
maestro_factura.id,
maestro_factura.descripcion,
maestro_factura.creado,
tercero.nombre AS persona,
tipo_factura.nombre AS factura,
tipo_pago.nombre AS pago,
maestro_factura.total
FROM
maestro_factura
INNER JOIN tipo_factura ON maestro_factura.id_tipo_factura = tipo_factura.id
INNER JOIN tipo_pago ON maestro_factura.id_tipo_pago = tipo_pago.id
INNER JOIN tercero ON maestro_factura.identificacion_tipo_tercero = tercero.identificacion
WHERE maestro_factura.creado  BETWEEN ? AND ?
ORDER BY id desc';

        $params = array("$desde.'  00:00:01'", "$hasta.' 23:59:59'");

        $result = $this->query_secure($sql, $params, true, true);

        if ($result === false) {
            $result = array(
'sql' => $this->sql,
'Error' => $this->getError(),
'instance' => 'bill',
);
        } else {
            $result = array(
'sql' => $this->sql,
'success' => true,
'result' => $result,
'total' => $this->rowcount(),
'instance' => 'bill',
);
        }

        return json_encode($result);

        $this->disconnect();
    }

    public function search_bill($id)
    {
        $sql = 'SELECT
usuarios.usuario,
maestro_factura.descripcion,
tipo_factura.nombre as factura,
tipo_pago.nombre as pago,
maestro_factura.total,
maestro_factura.creado,
detalle_factura.cantidad,
detalle_factura.precio,
producto.nombre as producto,
tercero.nombre as contacto,
tercero.telefono,
tercero.email,
producto.id as id_producto
FROM
maestro_factura
INNER JOIN detalle_factura ON detalle_factura.id_maestro_factura = maestro_factura.id
INNER JOIN acceso.usuarios ON maestro_factura.identificacion_usuario = usuarios.identificacion
INNER JOIN tipo_factura ON maestro_factura.id_tipo_factura = tipo_factura.id
INNER JOIN tipo_pago ON maestro_factura.id_tipo_pago = tipo_pago.id
INNER JOIN producto ON detalle_factura.id_producto = producto.id
INNER JOIN tercero ON maestro_factura.identificacion_tipo_tercero = tercero.identificacion
WHERE maestro_factura.id = ?';

        $params = array("$id");

        $result = $this->query_secure($sql, $params, true, true);

        if ($result === false) {
            $result = array(
'sql' => $this->sql,
'Error' => $this->getError(),
'instance' => 'search_bill',
);
        } else {
            $result = array(
'sql' => $this->sql,
'success' => true,
'result' => $result,
'total' => $this->rowcount(),
'instance' => 'search_bill',
);
        }

        return json_encode($result);

        $this->disconnect();
    }

    public function master_invoice($data)
    {
        $result = $this->insert('maestro_factura', "$data");

        if ($result === false) {
            $this->transaction('R');
            exit('ocurrio un problema');
        }
    }

    public function detail_invoice($data)
    {
        $getLatestId = $this->getLatestId('maestro_factura', 'id');

        $insert = "id_maestro_factura=$getLatestId,".$data;

        $result = $this->insert('detalle_factura', "$insert");

        if ($result === false) {
            $this->transaction('R');
            exit('ocurrio un problema');
        }
    }
}
