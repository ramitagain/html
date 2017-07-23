<?php 
require_once(__DIR__.'/../db/conexion.php');
Class Usuario{

	public static function login($rut, $pass){
		try {
			$connection = Conexion::getConnect();
			$passwd = self::codificado($pass);
			//falta poner la query como antes, la cambié para pruebas. y algo mas le falta, lo terminan ustedes.
			$sql= 'SELECT nombre, ap_paterno, ap_materno, estado FROM usuario WHERE rut =:rut and pass =:passwd';
			$connection->beginTransaction();
			$resultado = $connection->prepare($sql);
            $resultado->bindValue(':rut', $rut);
            $resultado->bindValue(':passwd', $passwd);
            $resultado->execute();
            $connection->commit();
            //Lo del beginTransaction y el comit nunca los he usado, y no se si enrealidad estan haciendo los suyo, 
            //pero es buena idea usarlos en caso de que si esten funcionando :)
            if ($resultado->rowCount()>0){
                return json_encode(array('estado' => '1', 'datos' => $resultado->fetchAll() ));
            }else{
                return json_encode(array('estado' => '2', 'msj'=>'No se pudo obtener el registro del rut: '.$rut));
            }

		} catch (Exception $e) {
			return json_encode(array('estado' => '4', 'error'=> $e->getMessage()));
		}
	}

	public static function logout($id){
		try {
			$connection = Conexion::getConnect();
			$sql ='UPDATE usuario SET session_id = :session_id WHERE id_usuario = :id_usuario';
			$connection->beginTransaction();
			$resultado = $connection->prepare($sql);
			$resultado->bindValue(':session_id', "");
            $resultado->bindValue(':id_usuario', $id);
            $resultado->execute();
            $connection->commit();
            if ($resultado->rowCount()>0){
                return json_encode(array('estado' => '1', 'msj' => 'Sesion Finalizada Correctamente' ));
            }else{
                return json_encode(array('estado' => '2', 'msj'=>'No se pudo completar la operación'));
            }
		} catch (Exception $e) {
			return json_encode(array('estado' => '4', 'error'=> $e->getMessage()));
		}
	}

    public static function createUser(){
    	try {
    		$connection = Conexion::getConnect();
    		$sql= '';


    	} catch (Exception $e) {
    		return json_encode(array('estado' => '4', 'error'=> $e->getMessage()));
    	}
    }

    public static function updateUser($user, $pass){ //por el momento solo cambia la passwd, en el form html ven que mas agregan
    	try {
    		$connection = Conexion::getConnect();
    		$passwd = self::codificado($pass);  //Codificación de la passwd
    		$sql= 'UPDATE usuario SET pass = :pass';
    		$connection->beginTransaction();
    		$resultado = $connection->prepare($sql);
			$resultado->bindValue(':pass', $passwd);
            $resultado->execute();
            $connection->commit();

            if ($resultado->rowCount()>0){
                return json_encode(array('estado' => '1', 'msj' => 'Datos actualizados correctamente' ));
            }else{
                return json_encode(array('estado' => '2', 'msj'=>'Datos invalidos'));
            }
    		
    	} catch (Exception $e) {
    		return json_encode(array('estado' => '4', 'error'=> $e->getMessage()));
    	}
    }

    public static function codificado($pass){
        $salt = "Qwe321rty654uio987p";
        $passwd = $pass.$salt;
        return sha1($passwd);
    }


}
if(isset($_GET['func'])){
	switch ($_GET['func']) {
		case 'login':
			if(isset($_GET['user']) && isset($_GET['pass'])){
				echo Usuario::login(htmlspecialchars($_GET['user']),htmlspecialchars($_GET['pass']));
			}
			break;
		case 'logout':
			//crear la parte del logout :)
			break;
		case 'update':
			if(isset($_GET['user']) && isset($_GET['pass'])){
				echo Usuario::updateUser(htmlspecialchars($_GET['user']),htmlspecialchars($_GET['pass']));
			}
		default:
			# code...
			break;
	}
}
else{
	echo 'Su Ip ha sido registrada, si sigue intentado hacer consultas no permitidas, nos veremos obligados a tomar acciones sobre su procedimiento.'; //De broma eso si xD
}
	



?>