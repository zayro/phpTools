<?php

namespace app\model;

//require_once('../config/conexion.php');

use config\connect;
use library\system;

$system = new system();

/**
 * CLASE ADMIN PRINCIPAL.
 *
 * En esta parte nos encargamos de crear los tipos de conexion del proyecto
 * para poder asi administrar los tipos de permisos de acceso
 *
 * @package app
 * @subpackage model
 */
class admin extends connect
{
    public function __construct($user = '', $pass = '')
    {
        parent::__construct($user, $pass);
    }

 /**
  * Metodo agregar registros a la base de datos
  *
  * @param string $table *description* se debe colocar bdd.table
  *
  * @return json
  */
    public function add(string $table, string $data)
    {
        $result = $this->insert("$table", " $data");

        if ($result === false) {
            $result = array(
            'sql' => $this->sql,
            'Error' => $this->getError(),
            'success' => false,
            'instance' => 'add',
        );
        } else {
            $result = array(
            'success' => true,
            'total' => $this->rowcount(),
            'instance' => 'add',
        );

        //$convertir = array("'" => "|", '"' => "|");
        //$accion_convertida = strtr($this->sql, $convertir);

        $this->audit("se almaceno: $table", $this->sql);
        }

        $this->disconnect();
        return json_encode($result);
    }

    public function add_last_insert($table, $data, $explore = ',')
    {
        $result = $this->increment("$table", "$data", $explore);

        if ($result === false) {
            $result = array(
            'sql' => $this->sql,
            'Error' => $this->getError(),
            'success' => false,
            'instance' => 'add_last_insert',
        );
            exit();
        } else {
            $result = array(
            'success' => true,
            'total' => $this->rowcount(),
            'instance' => 'add_last_insert',
        );

            $this->audit("se almaceno: $table", $this->sql);
        }

        return json_encode($result);
        $this->disconnect();
    }

    public function change($table, $data, $id)
    {
        $result = $this->update("$table", " $data", "id = $id");
        if ($result === false) {
            $error = array(
            'error' => $this->getError(),
            'sql' => $this->getSql(),
            'result' => $result,
            );

            return json_encode($error);
        } else {
            $result = array(
            'success' => true,
            'result' => $result,
            );

            $this->audit("se actualizo: $table", $this->sql);

            return json_encode($result);
        }
        $this->disconnect();
    }

    public function sql_encode($query)
    {
        $execute = base64_decode($query);

        $result = $this->query($execute);

        if ($result === false) {
            $result = array(
        'sql' => $this->sql,
        'Error' => $this->getError(),
        'instance' => 'filter',
        );
        } else {
            $result = array(
        'sql' => $this->sql,
        'success' => true,
        'result' => $result,
        'total' => $this->rowcount(),
        'instance' => 'filter',
        );
        }

        return json_encode($result);
        $this->disconnect();
    }

    public function sql_file($file)
    {
        $sql = file_get_contents($file);
        
        $result = $this->query($sql);

        if ($result === false) {
            $result = array(
            'Error' => $this->getError(),
            'success' => false,
            'instance' => 'add',
        );
        } else {
            $result = array(
            'success' => true,
            'result' => $result,
            'total' => $this->rowcount(),
            'instance' => 'add',
        );
        }
    }

    public function filter($fields, $table, $condition)
    {
        $execute = "SELECT $fields FROM $table WHERE $condition ;";

        $result = $this->query($execute);

        if ($result === false) {
            $result = array(
        'sql' => $this->sql,
        'Error' => $this->getError(),
        'instance' => 'filter',
        );
        } else {
            $result = array(
        'sql' => $this->sql,
        'success' => true,
        'result' => $result,
        'total' => $this->rowcount(),
        'instance' => 'filter',
        );
        }

        return json_encode($result);
        $this->disconnect();
    }

    public function search($table, $id)
    {
        $sql = "SELECT * FROM $table WHERE id  = ?";

        $params = array(
        "$id",
        );

        $result = $this->query_secure($sql, $params, true, true);

        if ($result === false) {
            $result = array(
            'sql' => $this->sql,
            'Error' => $this->getError(),
            'success' => false,
            'instance' => 'add',
        );
        } else {
            $result = array(
            'success' => true,
            'result' => $result,
            'total' => $this->rowcount(),
            'instance' => 'add',
        );
        }

        return json_encode($result);
        $this->disconnect();
    }

    public function consult($fields, $table)
    {
        $execute = "SELECT $fields FROM $table ";
        $result = $this->query($execute);

        if ($result === false) {
            $result = array(
        'sql' => $this->sql,
        'Error' => $this->getError(),
        'instance' => 'consult',
        );
        } else {
            $result = array(
        'sql' => $this->sql,
        'success' => true,
        'result' => $result,
        'total' => $this->rowcount(),
        'instance' => 'consult',
        );
        }

        return json_encode($result);
        $this->disconnect();
    }

    public function all_field($table)
    {
        $execute = "SELECT * FROM $table ;";

        $result = $this->query($execute);

        if ($result === false) {
            $result = array(
            'sql' => $this->sql,
            'Error' => $this->getError(),
            'instance' => 'all', );
        } else {
            $result = array(
            'success' => true,
            'result' => $result,
            'total' => $this->rowcount(),
            'instance' => 'all', );
        }

        return json_encode($result);
        $this->disconnect();
    }

    public function column_table($table)
    {
        $rows = $this->columns($table);
        $result = array(
        'Error' => $this->getError(),
        'total' => $this->rowcount(),
        'result' => $rows,
        'instance' => 'all',
        );

        return json_encode($result);
        $this->disconnect();
    }

    public function stored_procedure($method, $bdd)
    {
        $this->query("use $bdd");

        $sql = "call $method";

        $params = array();

        $result = $this->query_secure($sql, $params, true, true);

        if ($result === false) {
            $result = array(
            'sql' => $this->sql,
            'Error' => $this->getError(),
            'success' => false,
            'instance' => 'add',
        );
        } else {
            $result = array(
            'success' => true,
            'result' => $result,
            'total' => $this->rowcount(),
            'instance' => 'add',
        );
        }

        return json_encode($result);
        $this->disconnect();
    }

    private function audit($mensaje, $proceso)
    {
        $ip = system::obtener_ip();
        $archivo = __FILE__;

        $sql = "INSERT INTO auditoria (mensaje,proceso,ip,archivo, user) values (?,?,?,?, current_user);";

        $params = array(
        "$mensaje",
        "$proceso",
        "$ip",
        "$archivo",
        );

        $result = $this->query_secure($sql, $params, false, true);



        if ($result === false) {
            $result = array(
            'sql' => $this->sql,
            'Error' => $this->getError(),
            'success' => false,
            'instance' => 'audit',
        );
        } else {
            $result = array(
            'success' => true,
            'result' => $result,
            'total' => $this->rowcount(),
            'instance' => 'audit',
        );
        }

        return json_encode($result);
        $this->disconnect();
    }

    public function transaction_json($statement)
    {
        $this->transaction('B');

        foreach ($statement as $table => $sql) {
            for ($i = 0; $i <= count($sql) - 1;) {
                $process = '';

                foreach ($sql[$i] as $key => $val) {
                    if (is_string($val)) {
                        $val = strtolower(ltrim($val));
                    }

                    $valid = strpos($val, 'select');

                    if ($valid === false) {
                        $process .= $key.'='."'$val'|*";
                    } else {
                        $val = str_replace("'", '', $val);
                        $process .= "$key=$val|*";
                    }
                }

                $data = substr($process, 0, -2);

                $result = $this->add_last_insert("$table", " $data", '|*');

                if ($result === false) {
                    $result = array(
                      'sql' => $this->sql,
                      'Error' => $this->getError(),
                      'success' => false,
                      'result' => $result,
                      );

                    system::cabecera_html();
                    $this->transaction('R');

                    return  json_encode($result);
                }

                ++$i;
            }
        }

        system::cabecera_json();
        $this->transaction('C');

        $result = array(
            'success' => true,
            );

        return json_encode($result);
    }

    public function remove($table, $id)
    {
        $result = $this->delete("$table", "id = $id");
        if ($result === false) {
            $error = array(
            'error' => $this->getError(),
            'msg' => $this->err_msg,
            );

            return json_encode($error);
        } else {
            $result = array(
            'success' => true,
            'result' => $result,
            );

            $this->audit("se elimino: $table", $this->sql);

            return json_encode($result);
        }
        $this->disconnect();
    }

    public function remove_temp($table, $id)
    {
        $execute = "UPDATE $table SET eliminado = NOW() WHERE id = $id ";
        $result = $this->query($execute);

        if ($result === false) {
            $result = array(
            'sql' => $this->sql,
            'Error' => $this->getError(),
            'instance' => 'remove_temp',
        );
        } else {
            $result = array(
            'sql' => $this->sql,
            'success' => true,
            'result' => $result,
            'total' => $this->rowcount(),
            'instance' => 'remove_temp',
        );
        }

        return json_encode($result);
        $this->disconnect();
    }

    public function exit()
    {
        @session_destroy;
    }
}
