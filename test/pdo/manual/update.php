<?php


use core\BaseDatos;

$oBaseDatos = new BaseDatos();

$bEditar = $oBaseDatos->updateSingle('aq_1', 'icono_mapa_es',
array(
'nombre' => 'dato 10.1',
'descripcion' => 'dato 10.2',
'nombre_icono' => 'dato 10.3',
'orden' => 10
),
array(
'orden' => 4,
'id_icono_mapa' => 4
)
);
