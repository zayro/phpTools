<?php

class Conexion extends PDO {

	private $tipo_de_base = typeDatabase;
	private $host = host;
	private $nombre_de_base = "";
	private $usuario = user;
	private $contrasena = pass;
	private $port = port;


	public function __construct() {
		try{

			parent::__construct($this->tipo_de_base.":host=".$this->host.";port=".$this->port.";dbname=".$this->nombre_de_base, $this->usuario, $this->contrasena);

		}
		catch(PDOException $e){

			echo "Ha surgido un error y no se puede conectar a la base de datos. Detalle: " . $e->getMessage();

			exit;

		}

	}

}

$accesoSql = file_get_contents("../../bdd/acceso.sql");
$registroSql = file_get_contents("../../bdd/registro.sql");
$inventarioSql = file_get_contents("../../bdd/inventario.sql");
$permisos = file_get_contents("../../bdd/permisos.sql");

$conexion = new Conexion();

try{

$conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$conexion->beginTransaction();

$conexion->exec("CREATE DATABASE ACCESO;");
$conexion->exec("USE ACCESO;");
$conexion->exec($accesoSql);
print "SE HA CREADO BDD ACCESO"."<br>";
$conexion->exec($registroSql);
print "SE HA INSERTADO REGISTROS ACCESO"."<br>";
$conexion->commit();

$conexion->beginTransaction();
$conexion->exec("CREATE DATABASE INVENTARIO;");
$conexion->exec("USE INVENTARIO;");
$conexion->exec($inventarioSql);
print "SE HA INSERTADO BDD INVENTARIO"."<br>";
$conexion->commit();

$conexion->beginTransaction();
$conexion->exec($permisos);
print "SE CREO PERFIL CON PERMISOS"."<br>";
$conexion->commit();

}

	catch(PDOException $e){

  $conexion->rollBack();
	echo "Fallo: " . $e->getMessage();

}
   catch (Exception $e) {

  $conexion->rollBack();
  echo "Fallo: " . $e->getMessage();

}