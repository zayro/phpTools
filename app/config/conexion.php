<?php

namespace app\config;

use library\system;

//require_once '../../run.php';

use library\DBMS;

const PI = 3.14;

/**
 * CLASE GENERAL PRINCIPAL.
 *
 * En esta parte nos encargamos de crear los tipos de conexion del proyecto
 * para poder asi administrar los tipos de permisos de acceso
 */
class connect extends DBMS
{
    protected $database_type = 'mysql';

    protected $host_type = 'localhost';

    protected $port_type = '3307';

    protected $user_type;

    protected $password_type;

    public function __construct($user, $password, $bd = '')
    {
        $this->user_type = $user;
        $this->password_type = $password;
        $this->db_type = $bd;

       parent::__construct($this->database_type, $this->host_type, $this->db_type, $this->user_type, $this->password_type, $this->port_type);

       $response = $this->Cnxn();

        if ($response === false) {
            $result = array('Error' => $this->getError(),'success' => false );
            system::cabeceras(401);
        } else {
            $result = array('success' => true);
            system::cabeceras(202);
        }

        return json_encode($result);
    }
}

/*
print_r($instance = new connect('user', 'pass'));
*/
