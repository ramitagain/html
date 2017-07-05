<?php
require("conexion.php");	
$db = new Connection();
//$db->isSessionId($session_id);
if(isset($_POST['id_usuario'])){
	$id_usuario = $_POST['id_usuario'];
	session_start();

	$datos = Array('session_id' => "", 'id_usuario' => $id_usuario);
	$query = $db->prepare('UPDATE usuario SET session_id = :session_id WHERE id_usuario = :id_usuario;');
	$db->beginTransaction();
	$resultado = $query->execute($datos);
	$db->commit();

	session_destroy();

	if ($resultado == true) {
		$respuesta = Array('estado' => '1', 'msj' => 'Sesion Finalizada Correctamente');
	}else{
		$respuesta = Array('estado' => '2', 'msj' => 'Error al Finalizar la Sesion');
	}
}else{
	$respuesta = Array('estado' => '2', 'msj' => 'Error al Finalizar la Sesion');
}
echo json_encode($respuesta,JSON_UNESCAPED_UNICODE);
?>