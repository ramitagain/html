<?php 
require_once(__DIR__.'/../db/conexion.php');
Class Usuario{

	public static function login($rut, $pass){
		try {
			$connection = Conexion::getConnect();
			$passwd = self::codificado($pass);
			$sql= 'SELECT id_usuario, id_tipo, id_area, rut, nombre, ap_paterno, ap_materno, estado FROM usuario WHERE rut =:rut AND pass =:passwd';
			$resultado = $connection->prepare($sql);
            $resultado->bindValue(':rut', $rut);
            $resultado->bindValue(':passwd', $passwd);
            $resultado->execute();
            if ($resultado->rowCount()>0){
				$datos = $resultado->fetchAll(PDO::FETCH_ASSOC);
				$id_usuario = $datos[0][0];
				$session_id = self::set_session_id($id_usuario, $connection);
                return json_encode(array('estado' => '1', 'datos' => $datos, 'session_id' => $session_id));
            }else{
                return json_encode(array('estado' => '2', 'msj'=>'No se pudo obtener el registro del rut: '.$rut));
            }
		} catch (Exception $e) {
			return json_encode(array('estado' => '4', 'error'=> $e->getMessage()));
		}
	}

	public static function loginWeb($rut, $pass){
		try {
			$connection = Conexion::getConnect();
			$passwd = self::codificado($pass);
			$sql= 'SELECT id_usuario, id_tipo, id_area, rut, nombre, ap_paterno, ap_materno, estado FROM usuario WHERE rut =:rut AND pass =:passwd';
			$resultado = $connection->prepare($sql);
            $resultado->bindValue(':rut', $rut);
            $resultado->bindValue(':passwd', $passwd);
            $resultado->execute();
            if ($resultado->rowCount()>0){
				$datos = $resultado->fetchAll(PDO::FETCH_ASSOC);
				$id_usuario = $datos[0];
				session_start();
				$_SESSION['id_usuario'] = $datos[0];
				$_SESSION['id_tipo'] = $datos[1];
				$_SESSION['id_area'] = $datos[2];
				$_SESSION['rut'] = $datos[3];
				$_SESSION['nombre'] = $datos[4];
				$_SESSION['ap_paterno'] = $datos[5];
				$_SESSION['ap_materno'] = $datos[6];
				$_SESSION['session_id'] = session_create_id();
                return json_encode(array('estado' => '1', 'datos' => $datos));
            }else{
                return json_encode(array('estado' => '2', 'msj'=>'No se pudo obtener el registro del rut: '.$rut));
            }
		} catch (Exception $e) {
			return json_encode(array('estado' => '4', 'error'=> $e->getMessage()));
		}
	}

	public static function set_session_id($id_usuario, $connection){
		try{
			session_start();
			$session_id = session_id();
			// UPDATE SESSION ID
			$datos = Array('session_id' => $session_id, 'id_usuario' => $id_usuario);
			$query = $connection->prepare('UPDATE usuario SET session_id = :session_id WHERE id_usuario = :id_usuario;');
			$connection->beginTransaction();
			$resultado = $query->execute($datos);
			$connection->commit();
			return $session_id;
		} catch (Exception $e) {
			return json_encode(array('estado' => '4', 'error'=> $e->getMessage()));
		}
	}

	public static function unset_session_id($id_usuario, $connection){
		try{
			session_start();
			session_destroy();
			$session_id = null;
			// UPDATE SESSION ID
			$datos = Array('session_id' => $session_id, 'id_usuario' => $id_usuario);
			$query = $connection->prepare('UPDATE usuario SET session_id = :session_id WHERE id_usuario = :id_usuario;');
			$connection->beginTransaction();
			$resultado = $query->execute($datos);
			$connection->commit();
			return $resultado;
		} catch (Exception $e) {
			return json_encode(array('estado' => '4', 'error'=> $e->getMessage()));
		}
	}

	public static function logout($id_usuario){
		try {
			$connection = Conexion::getConnect();
			$resultado = self::unset_session_id($id_usuario,$connection);
            if ($resultado){
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

    public static function updateUser($id_usuario, $user, $pass){ //por el momento solo cambia la passwd, en el form html ven que mas agregan
    	try {
    		$connection = Conexion::getConnect();
    		$passwd = self::codificado($pass);  //Codificación de la passwd
    		$sql= 'UPDATE usuario SET pass = :pass WHERE id_usuario = :id_usuario';
    		$connection->beginTransaction();
    		$resultado = $connection->prepare($sql);
			$resultado->bindValue(':pass', $passwd);
			$resultado->bindValue(':id_usuario', $id_usuario);
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

	public static function verificarSession($id_usuario,$session_id){
		try {
			$connection = Conexion::getConnect();
			//falta poner la query como antes, la cambié para pruebas. y algo mas le falta, lo terminan ustedes.
			$sql= 'SELECT * FROM usuario WHERE id_usuario = :id_usuario AND session_id =:session_id';
			$resultado = $connection->prepare($sql);
            $resultado->bindValue(':id_usuario', $id_usuario);
            $resultado->bindValue(':session_id', $session_id);
            $resultado->execute();
			if ($resultado->rowCount()>0){
                return true;
            }else{
                return false;
            }
		} catch (Exception $e) {
    		return json_encode(array('estado' => '4', 'error'=> $e->getMessage()));
    	}
	}

	public static function listaUsuarios(){
		try {
			$connection = Conexion::getConnect();
			$sql= 'SELECT rut,ap_paterno,ap_materno,nombre,id_usuario FROM usuario';
			$resultado = $connection->prepare($sql);
            $resultado->execute();
			if ($resultado->rowCount()>0){
               $datos = $resultado->fetchAll(PDO::FETCH_ASSOC);
			   //print_r($datos);
			   foreach($datos as $fila){
					$fila['id_usuario'] = '<button type="button" id="'.$fila['id_usuario'].'">Editar</button>';
					//echo $fila['id_usuario'];
			   }
			   //print_r($datos);
			   return json_encode($datos);
            }else{
                return false;
            }
		} catch (Exception $e) {
    		return json_encode(array('estado' => '4', 'error'=> $e->getMessage()));
    	}
	}


}
if(isset($_GET['func'])){
	switch ($_GET['func']) {
		case 'login':
			if(isset($_GET['user']) && isset($_GET['pass'])){
				echo Usuario::login(htmlspecialchars($_GET['user']),htmlspecialchars($_GET['pass']));
			}
			break;
		case 'loginWeb':
			if(isset($_GET['user']) && isset($_GET['pass'])){
				echo Usuario::loginWeb(htmlspecialchars($_GET['user']),htmlspecialchars($_GET['pass']));
			}
			break;
		case 'logout':
			if(isset($_GET['id_usuario'])){
				echo Usuario::logout(htmlspecialchars($_GET['id_usuario']));
			}
			break;
		case 'update':
			if(isset($_GET['id_usuario']) && isset($_GET['user']) && isset($_GET['pass'])){
				echo Usuario::updateUser(htmlspecialchars($_GET['id_usuario']),htmlspecialchars($_GET['user']),htmlspecialchars($_GET['pass']));
			}
		case 'listUser':
			echo Usuario::listaUsuarios();
		default:
			break;
	}
}
else{
	echo 'Su Ip ha sido registrada, si sigue intentado hacer consultas no permitidas, nos veremos obligados a tomar acciones sobre su procedimiento.'; //De broma eso si xD
}
	



?>