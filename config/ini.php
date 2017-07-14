<?php

# habilitar compresiones
ini_set("zlib.output_compression", "On");

# activar o desactivar mensajes de error
error_reporting(E_ERROR | E_WARNING | E_PARSE);
ini_set("display_errors", 1);

set_time_limit(0);

# zona horaria
date_default_timezone_set('America/Bogota');

extract($_REQUEST);

$request = strtoupper($_SERVER['REQUEST_METHOD']);

$returnValue = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
