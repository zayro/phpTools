<?php

use core\BaseDatos;

$oBaseDatos = new BaseDatos();

/**
* Realiza insert multiple via array
*
*/
$bGuardar = $oBaseDatos->insertMultiple('aq_1',
'icono_mapa_es',
 array(
   array(
      'nombre' => 'dato 1.1',
      'descripcion' => 'dato 1.2',
      'nombre_icono' => 'dato 1.3',
      'orden' => 1
      ),
      array(
      'nombre' => 'dato 2.1',
      'descripcion' => 'dato 2.2',
      'nombre_icono' => 'dato 2.3',
      'orden' => 2
      ),
      array(
      'nombre' => 'dato 3.1',
      'descripcion' => 'dato 3.2',
      'nombre_icono' => 'dato 3.3',
      'orden' => 3
      )
));


$bGuardar = $oBaseDatos->insertSingle(
  'aq_1',
  'icono_mapa_es',
  array(
  'nombre' => 'dato 4.1',
  'descripcion' => 'dato 4.2',
  'nombre_icono' => 'dato 4.3',
  'orden' => 4
  )
);

var_dump($bGuardar);
