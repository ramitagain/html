<?php
require("conexion.php");	

$db = new Connection();

if(isset($_POST['usuario']) && isset($_POST['pass'])){
	//RECIBO LOS DATOS
	$rut = $_POST['usuario'];
	$pass = $_POST['pass'];
	//SE ENCRIPTA LA CONTRASEÑA
	$pass = hash ("sha256", $pass);
	$datos = Array('rut'=>$rut, 'pass'=>$pass);
	//CONSULTA SI EL USUARIO Y LA CONTRASEÑA COINCIDEN
	$query = $db->prepare('SELECT id_usuario, id_tipo, id_area, rut, nombre, ap_paterno, ap_materno, estado FROM usuario WHERE rut =:rut AND pass =:pass');
	foreach ($datos as $campo => $valor) {
		$query->bindValue(":".$campo,$valor);
	}

	$query->execute();

	$cant_filas = $query->rowCount();

	if ($cant_filas == 1) {
		$result = $query->fetch(PDO::FETCH_ASSOC);
		if($result['estado'] == 1){
			session_start();
			$session_id = session_id();
			$respuesta = Array('estado'=>'1', 'session_id' => $session_id, 'datos_usuario'=>$result); // USUARIO ENCONTRADO CORRECTAMENTE
			// UPDATE SESSION ID
			$id_usuario = $respuesta['datos_paciente']['id_usuario'];
			$datos = Array('session_id' => $session_id, 'id_usuario' => $id_usuario);
			$query = $db->prepare('UPDATE usuario SET session_id = :session_id WHERE id_usuario = :id_usuario;');
			$db->beginTransaction();
			$resultado = $query->execute($datos);
			$db->commit();

		}else{
			$respuesta = Array('estado'=>'3'); // USUARIO DESACTIVADO
		}
	}else{
		$respuesta = Array('estado'=>'2'); // ERROR EN USUARIO Y/O CONTRASEÑA
	}
}else{
	$respuesta = Array('estado'=>'4'); // ERROR EN LA TRANSFERENCIA DE LOS DATOS
}
echo json_encode($respuesta,JSON_UNESCAPED_UNICODE);
?>