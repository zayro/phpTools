<?php

require '../../system.class.php';

use library\system;

$instance = new system();

echo $instance->getCurrentUri();

echo '<br>';

echo $instance->obtener_ip();

echo '<br>';

echo $instance->ruta_actual();
