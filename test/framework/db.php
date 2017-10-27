<?php


namespace framework;

// Using Medoo namespace
use Medoo\Medoo;


class conexion extends Medoo {

	public function __construct() {

    $configuracion = spyc_load_file('../config.yaml');

    parent::__construct($configuracion['configuracion']);

    }

  }
