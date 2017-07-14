<?php

$oBaseDatos = new BaseDatos();

$oBaseDatos->sSql = "SELECT * FROM mng_13.icono_mapa_es";
$oBaseDatos->execQueryList();
print_r($oBaseDatos->List);

