<?php
require("conexion.php");	

if(isset($_POST['rut']) && isset($_POST['session_id'])){
	$db = new Connection();
	//$db->isSessionId($_POST['session_id']);
	$rut = $_POST['rut'];
	$datos = Array('rut'=>$rut);
	//$query = $db->prepare('SELECT * FROM usuario WHERE rut =:rut AND pass=:pass');

	$query = $db->prepare('SELECT * FROM ficha WHERE rut =:rut');

	foreach ($datos as $campo => $valor) {
		$query->bindValue(":".$campo,$valor);
	}

	$query->execute();

	$cant_filas = $query->rowCount();

	if ($cant_filas == 1) {
		$result = $query->fetch(PDO::FETCH_ASSOC);
		$respuesta = Array('estado'=>'1', 'datos_paciente'=>$result); // PACIENTE ENCONTRADO
	}else{
		$respuesta = Array('estado'=>'2'); // NO SE ENCONTRO EL PACIENTE
	}
}else{
	$respuesta = Array('estado'=>'3'); // ERROR EN LA TRANSFERENCIA DE LOS DATOS
}
echo json_encode($respuesta,JSON_UNESCAPED_UNICODE);
?>