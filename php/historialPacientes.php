<?php 

	require("conexion.php");
	
	$db = new Connection();
	//$rut = $_POST['rut'];

	$query = $db->prepare("SELECT TH.id_tipo_herida, TH.tipo, C.fecha_control 
						   FROM tipo_herida TH, control C, diagnostico D, ficha F
						   WHERE  C.id_diagnostico = D.id_diagnostico and C.id_tipo_herida = TH.id_tipo_herida and 
						    D.id_ficha = F.id_ficha and F.rut_ficha = '0'
						   ORDER BY C.fecha_control");

	$query -> execute();
	$resultado = $query->fetch(PDO::FETCH_ASSOC);

	echo json_encode($resultado,JSON_UNESCAPED_UNICODE);
	
	$db.close();
 ?>