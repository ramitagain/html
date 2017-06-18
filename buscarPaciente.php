<?php
require("conexion.php");	

$db = new Connection();

$rut = $_POST['rut'];
$datos = Array(rut=>$rut);
//$query = $db->prepare('SELECT * FROM usuario WHERE rut =:rut AND pass=:pass');

$query = $db->prepare('SELECT * FROM ficha WHERE rut =:rut');

foreach ($datos as $campo => $valor) {
	$query->bindValue(":".$campo,$valor);
}

$query->execute();

$cant_filas = $query->rowCount();

if ($cant_filas == 1) {
	$result = $query->fetch(PDO::FETCH_ASSOC);
	$respuesta = Array(estado=>'1', datos_paciente=>$result); // PACIENTE ENCONTRADO
}else{
	$respuesta = Array(estado=>'2'); // NO SE ENCONTRO EL PACIENTE
}

echo json_encode($respuesta);

?>