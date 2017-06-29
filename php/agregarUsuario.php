<?php 
	// NOTA ESTA IDEA ESTA BIEN PERO PRIMERO DEBES PREGUNTAR SI EL RUT QUE INTENTAS AGREGAR YA EXISTE, PARA EVITAR LA DUPLICIDAD DE LOS DATOS, 
	// ESO LO HACES CON UN SELECT PERO ESTE NO SE HACE DE LA MISMA MANERA QUE EL INSERT, REVISA EL LOGIN.PHP PARA CACHAR COMO :D
	require("conexion.php");
	$db = new Connection();

	// LO QUE HAGO YO PARA PROBAR SI FUNCIONAN LOS CODIGOS ES COMENTAR LA PARTE DEL POST Y AGREGAR LA VARIABLE DERECTAMENTE
	$id_tipo = 1;//$_POST['id_tipo'];
	$id_area = 1;//$_POST['id_area'];
	
	$pass = '1234';//$_POST['pass'];
	$pass = hash ("sha256", $pass);

	$rut = '44444444-1';//$_POST['rut'];
	$nombre = 'Pedro';//$_POST['nombre'];
	$ap_materno = 'Prueba';//$_POST['ap_materno'];
	$ap_paterno = 'A';//$_POST['ap_paterno'];
	$estado = true;//$_POST['estado'];
	// UNA VEZ RECIBIDA LAS VARIBLES SE CREAR UNA ARRAY DONDE GUARDAS ESTAS CON SUS RESPECTIVOS NOMBRES

	$datos = Array('id_tipo'=>$id_tipo, 'id_area'=>$id_area, 'rut'=>$rut, 'nombre'=>$nombre, 'ap_paterno'=>$ap_paterno, 
	'ap_materno'=>$ap_materno, 'estado'=>$estado, 'pass'=>$pass);
	
	// UNA VES CREADO EL ARRAY SE PREPARA LA CONSULTA DONDE DE USA EL :NOMBRE_DE_LA_VARIBALE PARA NOMBRARLA DE ESTA FORMA SE EVITA LA INJECCION SQL
	$query = $db->prepare("INSERT INTO usuario(id_tipo, id_area, rut, nombre, ap_materno, ap_paterno, estado, pass) 
	VALUES (:id_tipo, :id_area, :rut, :nombre, :ap_materno, :ap_paterno, :estado, :pass)");	
	// COMO ESTA ES QUERY ES UN INSERT DEBEMOS DECIRLE AL SERVIDOR QUE VAMOS A TRANSFERIR DATOS CON LA SIGUIENTE SENTENCIA 
	$db->beginTransaction();
	// UNA VEZ QUE SE INICIO LA TRANSEFERENCIA SE REALIZA EL EXECUTE Y COMO PARAMETRO SE LE DA EL ARRAY CON LA INFORMACION, LA FUNCION HACE LA MAGIA DE DECODIFICAR TODO
	$resultado = $query->execute($datos);
	// UNA VEZ REALIZADA LA TRANFERENCIA SE FINALIZA CON LA SIGUIENTE FUNCION Y POSTERIOR A ESO SE CIERRA LA CONEXION A LA BASE DE DATOS Y SE ANALIZA LA RESPUESTA QUE ESTA DIO
	$db->commit();
	$db->close();
	if($resultado){
		$respuesta = Array('estado'=>'1', 'msj'=>'Operacion exitosa');
	}else{
		$respuesta = Array('estado'=>'2', 'msj' => 'Error al agregar usuario');
	}
	//SE IMPRIME EL JSON DE RESPUETA 'JSON_UNESCAPED_UNICODE' ESTO ES PARA EVITAR PROBLEMA CON LAS Ñ'S Y LOS ACENTOS
	echo json_encode($respuesta,JSON_UNESCAPED_UNICODE);
 ?>