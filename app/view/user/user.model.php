<?php

namespace app\view;

//require_once('../config/conexion.php');

use config\connect;
use library\system;
use library\auth;

class usuarios extends connect
{
    public function __construct($usuario, $clave)
    {
        if (parent::__construct($usuario, $clave) == false) {
            //            print_r($this->getError());
        }
    }

    public function login($usuario, $clave)
    {
        $sql = 'SELECT empresas.id AS business, empresas.nombre AS empresa, empresas.bd, grupo.nombre AS perfil, usuarios.usuario, usuarios.identificacion, usuarios.clave FROM acceso.usuarios INNER JOIN acceso.empresas ON usuarios.id_empresa = empresas.id INNER JOIN acceso.grupo ON usuarios.id_grupo = grupo.id WHERE identificacion = ? AND clave = ?';

        $params = array("$usuario", "$clave");

        $result = $this->query_secure($sql, $params, true, true);

        $_SESSION['datos'] = $result[0];

        if ($result === false) {
            $result = array(
            'sql' => $this->sql,
            'Error' => $this->getError(),
            'instance' => 'login', );
            system::cabeceras(401);
        } elseif ($this->rowcount() > 0) {
            $sql = 'SELECT m.nombre AS menu, submenu_1.nombre AS submenu1, submenu_1.modulo AS modulo1, submenu_2.nombre AS submenu2, submenu_2.modulo AS modulo2 FROM acceso.privilegio AS p INNER JOIN acceso.grupo AS g ON p.id_grupo = g.id INNER JOIN acceso.menu AS m ON p.id_menu = m.id INNER JOIN acceso.usuarios AS u ON u.id_grupo = g.id LEFT JOIN acceso.submenu_1 ON submenu_1.id_menu = m.id LEFT JOIN acceso.submenu_2 ON submenu_2.id_submenu_1 = submenu_1.id WHERE u.identificacion = ? ORDER BY 	m.nombre, submenu_1.nombre  ASC';

            $params = array("$usuario");

            $rows = $this->query_secure($sql, $params, true, true);

            $token = array('user' => $usuario, 'id' => base64_encode($clave));

            $result = array(
              'menu' => $rows,
               'datos' => $result,
               'instancia' => 'login',
                'estado' => 'success',
              'token' => auth::SignIn($token),
              );

            system::cabeceras(202);
        } else {
            $result = array(
            'sql' => $this->sql,
            'params' => $params,
            'instance' => 'login',
            );
            system::cabeceras(401);
        }

        system::cabecera_json();

        return json_encode($result);

        $this->disconnect();
    }

    public function logout($id)
    {
        $sql = 'DELETE FROM acceso.enlinea  WHERE  identificacion = ?';

        $params = array("$id");

        $result = $this->query_secure($sql, $params, false, true);

        if ($result === false) {
            $error = array('error' => $db->getError());

            return json_encode($error);
        } else {
            $result = array('eliminados' => $this->rowcount());

            return json_encode($result);
        }

        $this->disconnect();

        session_destroy();
    }

    public function exit()
    {
        @session_destroy();
    }
}

/*
instancia = new usuarios();

print ($instancia->login('1098669883', '123456'));
*/
