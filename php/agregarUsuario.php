<?php 

	require("conexion.php");
	$db = new Connection();

	$id_tipo = $_POST['id_tipo'];
	$id_area = $_POST['id_area'];
	
	$pass = $_POST['pass'];
	$pass = hash ("sha256", $pass);

	$rut = $_POST['rut'];
	$nombre = $_POST['nombre'];
	$ap_materno = $_POST['ap_materno'];
	$ap_paterno = $_POST['ap_paterno'];
	$estado = $_POST['estado'];
	//$session_id = ????
	
	$query = $db->prepare("INSERT INTO usuario(id_tipo, id_area, rut, nombre, ap_paterno, ap_paterno, estado. pass)
						   VALUES ($id_tipo, $id_area, $pass, $rut, $nombre, $ap_materno, $ap_paterno, $estado, $pass)");
	$query->execute();

	$db.close();

 ?>