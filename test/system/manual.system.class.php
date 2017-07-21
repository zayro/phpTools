<?php

require_once __DIR__.'/../../vendor/autoload.php';

use PhPdOrm\system;

$instance = new system();

echo $instance->getCurrentUri();

echo '<br>';

echo $instance->obtener_ip();

echo '<br>';

echo $instance->ruta_actual();
