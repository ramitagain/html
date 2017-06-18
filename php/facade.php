<?php
require("conexion.php");	
$db = new Connection();

public function query($query,$sql,$datos){
	switch ($query) {
		case 'select':
			$query = $db->prepare($sql);
			foreach ($datos as $campo => $valor) {
				$query->bindValue(":".$campo,$valor);
			}
			$resultado = $query->execute();
			return $query->rowCount();
			break;

		case 'update':
			$query = $db->prepare($sql);
			$db->beginTransaction();
			$resultado = $query->execute($datos);
			$db->commit();
			return $resultado;
			break;
	}
	
}
?>