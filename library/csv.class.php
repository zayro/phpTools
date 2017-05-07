<?php

namespace library;

use app\config\connect;

/**
 * CLASE CSV.
 *
 * En esta parte nos encargamos de crear los tipos de conexion del proyecto
 * para poder asi administrar los tipos de permisos de acceso
 *
 * @author MARLON ZAYRO ARIAS VARGAS <zayro8905@gmail.com>
 *
 * @since 2015-05-02
 *
 * @version 1.0
 *
 * @license http://opensource.org/licenses/gpl-license.php  GNU Public License
 */
class csv extends connect
{
    /**
     * EXPORTAR CSV TABLA BDD.
     *
     * En este metodo se puede imprimir una tabla completa de la base de datos
     *
     * @method all_csv exportar una tabla completa
     *
     * @param string $table   se menciona la tabla de la base de datos
     * @param string $archivo nombre del archivo a exportar
     */
    public function all_csv($table, $archivo)
    {
        $rows = $this->query("SELECT *  FROM $table");

        $f = fopen('php://output', 'w');

        header('Content-Type: text/csv');

        header("Content-Disposition: attachement; filename='$archivo.csv'");

        header('Pragma: no-cache');

        header('Expires: 0');

        foreach ($rows as $line) {
            fputcsv($f, $line);
        }

        fclose($f);

        exit;
    }

    /**
     * EXPORTAR CONSULTA CSV TABLA BDD.
     *
     * En este metodo se puede imprimir una consulta completa de la base de datos
     *
     * @method all_csv exportar una tabla completa
     *
     * @param string $sql     consulta sql para exportar
     * @param string $archivo nombre del archivo a exportar
     */
    public function query_csv($sql, $archivo)
    {
        $rows = $this->query($sql);

        $f = fopen('php://output', 'w');

        header('Content-Type: text/csv');

        header("Content-Disposition: attachement; filename='$archivo.csv'");

        header('Pragma: no-cache');

        header('Expires: 0');

        foreach ($rows as $line) {
            fputcsv($f, $line);
        }

        fclose($f);

        exit;
    }

    /**
     * IMPORT CONSULTA CSV TABLA BDD.
     *
     * En este metodo se puede imprimir una consulta completa de la base de datos
     *
     * @method all_csv exportar una tabla completa
     *
     * @param string $sql consulta sql para exportar
     */
    public function import($sql)
    {
        $csv_file = $_FILES['csv_file']['tmp_name'];

        if (is_file($csv_file)) {
            $handle = fopen($csv_file, 'r');

            try {
                //prepare for insertion
                $query = $this->query($sql);

                //unset the first line like this
                fgets($handle);

                //created loop here
                while (($data = fgetcsv($handle, 1000, ',')) !== false) {
                    $query->execute($data);
                }

                fclose($handle);
            } catch (PDOException $e) {
                die($e->getMessage());
            }

            echo 'file imported';
        }
    }
}

/*
$instance = new csv();

$instance->query_csv('select * from demo', 'report');

*/
