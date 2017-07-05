<?php 
require_once('datosConexion.php');

class Conexion{
	public static function getConnect(){
		$conexion = new PDO('pgsql:host='.db_host.'; dbname='.db_nombre, db_user, db_pass);
		$conexion->exec("set character utf8");
		$conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		return $conexion;
	}

}


 ?>