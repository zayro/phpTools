<?php

namespace app\model;

//require_once('../config/conexion.php');

use app\config\connect;

/**
 * CLASE GENERAL PRINCIPAL.
 *
 * En esta parte nos encargamos de crear los tipos de conexion del proyecto
 * para poder asi administrar los tipos de permisos de acceso
 */
class dashboard extends connect
{
    public function __construct()
    {
        parent::__construct($_SESSION['datos']['identificacion'], $_SESSION['datos']['clave']);
    }

    public function estadistica()
    {
        $execute = 'SELECT
					maestro_factura.total,
					tipo_factura.nombre AS factura,
					maestro_factura.creado AS fecha
					FROM
					maestro_factura
					INNER JOIN tipo_factura ON tipo_factura.id = maestro_factura.id_tipo_factura
					WHERE
					MONTH (
					maestro_factura.creado
					) = MONTH (NOW())';

        $result = $this->query($execute);

        if ($result === false) {
            $result = array(
                    'sql' => $this->sql,
                    'success' => false,
                    'Error' => $this->getError(),
                    'instance' => 'estadistica',
                    );
        } else {
            $result = array(
                    'sql' => $this->sql,
                    'success' => true,
                    'result' => $result,
                    'total' => $this->rowcount(),
                    'instance' => 'estadistica',
                    );
        }

        return json_encode($result);
        $this->disconnect();
    }

    public function ventas()
    {
        $execute = 'SELECT
					sum(maestro_factura.total) as total,
					maestro_factura.creado
					FROM
					maestro_factura
					WHERE id_tipo_factura = 1
					and MONTH(creado) =  MONTH(NOW())
					GROUP BY MONTH(`creado`)';

        $result = $this->query($execute);

        if ($result === false) {
            $result = array(
                    'sql' => $this->sql,
                    'success' => false,
                    'Error' => $this->getError(),
                    'instance' => 'total_ventas',
                    );
        } else {
            $result = array(
                    'sql' => $this->sql,
                    'success' => true,
                    'result' => $result,
                    'total' => $this->rowcount(),
                    'instance' => 'total_ventas',
                    );
        }

        return json_encode($result);
        $this->disconnect();
    }

    public function compras()
    {
        $execute = 'SELECT
					sum(maestro_factura.total) as total,
					maestro_factura.creado
					FROM
					maestro_factura
					WHERE id_tipo_factura = 2
					and MONTH(creado) =  MONTH(NOW())
					GROUP BY MONTH(`creado`)';

        $result = $this->query($execute);

        if ($result === false) {
            $result = array(
                    'sql' => $this->sql,
                    'success' => false,
                    'Error' => $this->getError(),
                    'instance' => 'total_ventas',
                    );
        } else {
            $result = array(
                    'sql' => $this->sql,
                    'success' => true,
                    'result' => $result,
                    'total' => $this->rowcount(),
                    'instance' => 'total_ventas',
                    );
        }

        return json_encode($result);
        $this->disconnect();
    }

    public function servicios()
    {
        $execute = 'SELECT
					sum(maestro_factura.total) as total,
					maestro_factura.creado
					FROM
					maestro_factura
					WHERE id_tipo_factura = 3
					and MONTH(creado) =  MONTH(NOW())
					GROUP BY MONTH(`creado`)';

        $result = $this->query($execute);

        if ($result === false) {
            $result = array(
                    'sql' => $this->sql,
                    'success' => false,
                    'Error' => $this->getError(),
                    'instance' => 'total_ventas',
                    );
        } else {
            $result = array(
                    'sql' => $this->sql,
                    'success' => true,
                    'result' => $result,
                    'total' => $this->rowcount(),
                    'instance' => 'total_ventas',
                    );
        }

        return json_encode($result);
        $this->disconnect();
    }
}

//$instance = new general();
/**Document Testing clase*/
//print_r($instance->search('demo', 1));
