<?php

namespace library;

//require_once('../run.php');

use app\config\connect;

/**
 * CLASE EXCEL.
 *
 * En esta parte nos encargamos de crear un manejador de carpetas elimnar, mover, crear
 *
 * @author MARLON ZAYRO ARIAS VARGAS
 *
 * @version 1.0
 */
class excel extends connect
{
    /**
     * IMPORT CONSULTA EXCEL BDD.
     *
     * En este metodo se puede imprimir una consulta completa de la base de datos
     *
     * @method exportar exportar una tabla completa
     *
     * @param string $query    consulta sql para exportar
     * @param string $filename nombre del archivo a exportar
     */
    public function exportar($query, $filename)
    {
        //header("Content-Type: application/vnd.ms-excel");
        header('Content-Type: application/vnd.ms-excel; charset=utf-8');
        header('Content-type: application/x-msexcel; charset=utf-8');
        header("Content-Disposition: attachment; filename=$filename.xls");
        header('Pragma: no-cache');
        header('Expires: 0');

        $nombres = $this->query($query, 'named');

        $fields = array_keys($nombres);
        $count = count($fields);

        echo "<table border='1'>";

        echo '<tr>';

        for ($i = 0; $count > $i; ++$i) {
            echo '<th>'.$fields[$i].'</th>';
        }

        echo '</tr>';

        foreach ($this->query($query, 'both') as $row) {
            echo '<tr>';

            for ($i = 0; $count > $i; ++$i) {
                echo '<td>'.$row[$i].'</td>';
            }

            echo '</tr>';
        }

        echo '</table>';
    }
}

/*
  $instance = new excel();

  $instance->exportar('select * from demo', 'report');

  */

?>

