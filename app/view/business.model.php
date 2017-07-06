<?php

namespace app\model;

//require_once('../config/conexion.php');

use app\config\connect;

/**
 * CLASE ADMIN PRINCIPAL.
 *
 * En esta parte nos encargamos de crear los tipos de conexion del proyecto
 * para poder asi administrar los tipos de permisos de acceso
 */
class business extends connect
{
    public function __construct()
    {
        parent::__construct($_SESSION['datos']['identificacion'], $_SESSION['datos']['clave']);
    }

    public function business_change_imagen($nombre, $telefono, $direccion, $imagen, $id)
    {
        $sql = 'UPDATE acceso.empresas SET  nombre =  ?, telefono = ?, direccion = ?, imagen = ? WHERE id = ?  ;';

        $params = array(
        "$nombre",
        "$telefono",
        "$direccion",
        "$imagen",
        "$id",
        );

        $result = $this->query_secure($sql, $params, false, true);

        if ($result === false) {
            $result = array(
                    'sql' => $this->sql,
                    'Error' => $this->getError(),
                    'success' => false,
                    'instance' => 'business_change_imagen',
                    );
        } else {
            $result = array(
                    'success' => true,
                    'result' => $result,
                    'total' => $this->rowcount(),
                    'instance' => 'business_change_imagen',
                    );
        }

        return json_encode($result);
        $this->disconnect();
    }

    public function business_change($nombre, $telefono, $direccion, $id)
    {
        $sql = 'UPDATE acceso.empresas SET  nombre =  ?, telefono = ?, direccion = ? WHERE id = ?  ;';

        $params = array(
        "$nombre",
        "$telefono",
        "$direccion",
        "$id",
        );

        $result = $this->query_secure($sql, $params, false, true);

        if ($result === false) {
            $result = array(
                    'params' => $params,
                    'Error' => $this->getError(),
                    'success' => false,
                    'instance' => 'business_change',
                    );
        } else {
            $result = array(
                    'success' => true,
                    'result' => $result,
                    'total' => $this->rowcount(),
                    'instance' => 'business_change',
                    );
        }

        return json_encode($result);
        $this->disconnect();
    }
}
