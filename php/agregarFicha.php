<?php
require("conexion.php");	

$db = new Connection();

$rut = $_POST['rut'];
if (isset($_POST['rut'])) {

$nombres = $_POST['nombres'];
$ap_paterno = $_POST['ap_paterno'];
$ap_materno = $_POST['ap_materno'];
$fecha_nacimiento = $_POST['patologia_ficha'];
$patologia = $_POST['patologia'];
$id_usuario = $_POST['id_usuario'];

$respuesta = Array(estado => '0', error => null);

//VERIFICAR SI EXISTE PRIMERO

$datos = Array(rut=>$rut);
$query = $db->prepare('SELECT * FROM ficha WHERE rut =:rut');

foreach ($datos as $campo => $valor) {
	$query->bindValue(":".$campo,$valor);
}
$query->execute();
$cant_filas = $query->rowCount();
if ($cant_filas == 0) {
	//INSERTAR UNA VEZ YA VERIFICADO QUE NO ESTA
	$datos = Array(rut=>$rut, nombres=>$nombres, ap_paterno=>$ap_paterno, ap_materno=>$ap_materno, fecha_nacimiento=>$fecha_nacimiento, patologia=>$patologia, id_usuario=>$id_usuario);
	$query = $db->prepare('INSERT INTO ficha (rut, nombres, ap_paterno, ap_materno, fecha_nacimiento, patologia, id_usuario) VALUES (:rut,:nombres,:ap_paterno,:ap_materno,:fecha_nacimiento,:patologia,:id_usuario)');
	$db->beginTransaction();
	$resultado = $query->execute($datos);
	$db->commit();
	if ($resultado) {
		$respuesta['estado'] = '1'; // OPERACION REALIZADA EXITOSAMENTE
	}else{
		$respuesta['estado'] = '3';
		$respuesta['error'] = "Error al intentar agregar"; // ERROR AL INTENTAR INGRESAR
	}
}else{
	$respuesta['estado'] = '2';
	$respuesta['error'] = "El RUT ya existe"; // EL PACIENTE YA ESTA INGRESADO EN EL SISTEMA
}

}else{
	$respuesta['estado'] = '4';
	$respuesta['error'] = "RUT no valido"; // El RUT NO FUE INGRESADO
}
echo json_encode($respuesta);

?>