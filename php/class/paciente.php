<?php
require_once(__DIR__.'/../db/conexion.php');
require_once(__DIR__.'/usuario.php');
Class Paciente{
    public static function buscar($rut){
        try {
            $connection = Conexion::getConnect();
            $query = $connection->prepare('SELECT * FROM ficha WHERE rut_ficha =:rut');
            $query->bindValue(':rut', $rut);
            $resultado = $query->execute();
            if ($query->rowCount()>0){
                $result = $query->fetchAll(PDO::FETCH_ASSOC);
                return json_encode(array('estado' => '1', 'datos'=>$result));
            }else{
                return json_encode(array('estado' => '2', 'msj'=>'No se encontre el RUT: $rut'));
            }
        } catch (Exception $e) {
			return json_encode(array('estado' => '4', 'error'=> $e->getMessage()));
		}
        
    }
    public static function agregar($rut,$nombres,$ap_paterno,$ap_materno,$fecha_nacimiento,$patologia,$id_usuario){
        try {
            $connection = Conexion::getConnect();
            //VERIFICAR SI EXISTE PRIMERO
            $query = $connection->prepare('SELECT * FROM ficha WHERE rut_ficha =:rut');
            $query->bindValue(":rut",$rut);
            $query->execute();
            $cant_filas = $query->rowCount();
            if ($cant_filas == 0) {
                //INSERTAR UNA VEZ YA VERIFICADO QUE NO ESTA
                $datos = Array('rut'=>$rut, 'nombres'=>$nombres, 'ap_paterno'=>$ap_paterno, 'ap_materno'=>$ap_materno, 'fecha_nacimiento'=>$fecha_nacimiento, 'patologia'=>$patologia, 'id_usuario'=>$id_usuario);
                $query = $connection->prepare('INSERT INTO ficha (rut_ficha, nombres_ficha, ap_paterno_ficha, ap_materno_ficha, fecha_nacimiento_ficha, patologia_ficha, id_usuario) VALUES (:rut,:nombres,:ap_paterno,:ap_materno,:fecha_nacimiento,:patologia,:id_usuario)');
                $connection->beginTransaction();
                $resultado = $query->execute($datos);
                $connection->commit();
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
            return json_encode($respuesta);
        } catch (Exception $e){
            return json_encode(array('estado' => '4', 'error'=> $e->getMessage()));
        }
    }
    public static function modificar(){
        
    }
    public static function cargarHistorial(){
        
    }
}
if(isset($_GET['func'])){
	switch ($_GET['func']) {
		case 'buscar':
			//if(isset($_GET['id_usuario']) && isset($_GET['rut']) && isset($_GET['session_id'])){
            if(isset($_GET['rut'])){
                //$resp = Usuario::verificarSession(htmlspecialchars($_GET['id_usuario']), htmlspecialchars($_GET['session_id']));
                $resp = true;
                if($resp){
                    echo Paciente::buscar(htmlspecialchars($_GET['rut']));
                }
			}
			break;
        case 'agregar':
			/*if(isset($_GET['id_usuario']) 
            && isset($_GET['rut']) 
            && isset($_GET['nombres'])
            && isset($_GET['ap_paterno'])
            && isset($_GET['ap_materno'])
            && isset($_GET['fecha_nacimiento'])
            && isset($_GET['patologia'])
            && isset($_GET['id_usuario'])
            && isset($_GET['session_id'])){*/
            if(isset($_GET['id_usuario']) 
            && isset($_GET['rut']) 
            && isset($_GET['nombres'])
            && isset($_GET['ap_paterno'])
            && isset($_GET['ap_materno'])
            && isset($_GET['fecha_nacimiento'])
            && isset($_GET['patologia'])){
                //$resp = Usuario::verificarSession(htmlspecialchars($_GET['id_usuario']), htmlspecialchars($_GET['session_id']));
                $resp = true;
                if($resp){
                    echo Paciente::agregar(htmlspecialchars($_GET['rut']),
                    htmlspecialchars($_GET['nombres']),
                    htmlspecialchars($_GET['ap_paterno']),
                    htmlspecialchars($_GET['ap_materno']),
                    htmlspecialchars($_GET['fecha_nacimiento']),
                    htmlspecialchars($_GET['patologia']),
                    htmlspecialchars($_GET['id_usuario']));
                }
			}else{
                echo "Error al recibir las variables";
            }
			break;
		default:
			# code...
			break;
	}
}
else{
	echo 'Su Ip ha sido registrada, si sigue intentado hacer consultas no permitidas, nos veremos obligados a tomar acciones sobre su procedimiento.'; //De broma eso si xD
}
?>
