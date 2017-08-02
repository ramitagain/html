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
				$id_usuario = $datos[0]['id_usuario'];
				$session_id = self::set_session_id($id_usuario, $connection);
				//echo $session_id;
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
				$id_usuario = $datos[0]['id_usuario'];
				session_start();
				$_SESSION['id_usuario'] = $datos[0]['id_usuario'];
				$_SESSION['id_tipo'] = $datos[0]['id_tipo'];
				$_SESSION['id_area'] = $datos[0]['id_area'];
				$_SESSION['rut'] = $datos[0]['rut'];
				$_SESSION['nombre'] = $datos[0]['nombre'];
				$_SESSION['ap_paterno'] = $datos[0]['ap_paterno'];
				$_SESSION['ap_materno'] = $datos[0]['ap_materno'];
				$_SESSION['session_id'] = session_id();
                return json_encode(array('estado' => '1', 'datos' => $datos));
            }else{
                return json_encode(array('estado' => '2', 'msj'=>'No se pudo obtener el registro del rut: '.$rut));
            }
		} catch (Exception $e) {
			return json_encode(array('estado' => '4', 'error'=> $e->getMessage()));
		}
	}
	public static function logoutWeb(){
		session_start();
		if(session_destroy()){
			return json_encode(array('estado' => '1'));
		}else{
			return json_encode(array('estado' => '2'));
		}
	}
	public static function verificarSessionWeb(){
		session_start();
		if(isset($_SESSION['session_id'])){
			$nombre = $_SESSION['nombre'].' '.$_SESSION['ap_paterno'];
			return json_encode(array('estado' => '1', 'nombre' => $nombre));
		}else{
			return json_encode(array('estado' => '2'));
		}
	}

	public static function set_session_id($id_usuario, $connection){
		try{
			session_start();
			$session_id = session_id();
			// UPDATE SESSION ID
			$datos = Array('session_id' => $session_id, 'id_usuario' => $id_usuario);
			$sql = 'UPDATE usuario SET session_id = :session_id WHERE id_usuario = :id_usuario;';
			$query = $connection->prepare($sql);
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
			$sql= 'SELECT rut,ap_paterno,ap_materno,nombre,id_usuario,estado FROM usuario';
			$resultado = $connection->prepare($sql);
            $resultado->execute();
			if ($resultado->rowCount()>0){
               $datos = $resultado->fetchAll(PDO::FETCH_ASSOC);
			   return json_encode($datos);
            }else{
                return false;
            }
		} catch (Exception $e) {
    		return json_encode(array('estado' => '4', 'error'=> $e->getMessage()));
    	}
	}
	public static function existeUsuario($rut){
		try {
			$connection = Conexion::getConnect();
			$sql= 'SELECT * FROM usuario WHERE rut = :rut';
			$resultado = $connection->prepare($sql);
			$resultado->bindValue(':rut', $rut);
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
	public static function agregarUser($rut,$nombre,$ap_paterno,$ap_materno,$password,$id_tipo,$id_area,$estado){
		if(!Usuario::existeUsuario($rut)){
			try {
				$connection = Conexion::getConnect();
				$sql='INSERT INTO usuario (rut,nombre,ap_paterno,ap_materno,pass,id_tipo,id_area,estado) 
				VALUES (:rut,:nombre,:ap_paterno,:ap_materno,:pass,:id_tipo,:id_area,:estado);';

				$resultado = $connection->prepare($sql);
				$resultado->bindValue(':rut', $rut);
				$resultado->bindValue(':nombre', $nombre);
				$resultado->bindValue(':ap_paterno', $ap_paterno);
				$resultado->bindValue(':ap_materno', $ap_materno);
				$resultado->bindValue(':pass', codificado($password));
				$resultado->bindValue(':id_tipo', $id_tipo);
				$resultado->bindValue(':id_area', $id_area);
				$resultado->bindValue(':estado', $estado);

				$connection->beginTransaction();

				$ejecucion = $resultado->execute();

				$connection->commit();
				if ($ejecucion){
					return json_encode(array('estado' => '1', 'msj'=> 'Agregado correctamente'));
				}else{
					return json_encode(array('estado' => '2', 'msj'=>'Datos invalidos'));
				}
			} catch (Exception $e) {
				return json_encode(array('estado' => '4', 'error'=> $e->getMessage()));
			}
		}else{
			return json_encode(array('estado' => '3', 'msj'=> "El usuario ya existe"));
		}
		
	}

	public static function areasUsuarios(){
		try {
			$connection = Conexion::getConnect();
			$sql= 'SELECT id_area,nombre FROM area';
			$resultado = $connection->prepare($sql);
            $resultado->execute();
			if ($resultado->rowCount()>0){
               $datos = $resultado->fetchAll(PDO::FETCH_ASSOC);
			   return json_encode($datos);
            }else{
                return false;
            }
		} catch (Exception $e) {
    		return json_encode(array('estado' => '4', 'error'=> $e->getMessage()));
    	}
	}

	public static function tiposUsuarios(){
		try {
			$connection = Conexion::getConnect();
			$sql= 'SELECT id_tipo,nombre FROM tipo_usuario';
			$resultado = $connection->prepare($sql);
            $resultado->execute();
			if ($resultado->rowCount()>0){
               $datos = $resultado->fetchAll(PDO::FETCH_ASSOC);
			   return json_encode($datos);
            }else{
                return false;
            }
		} catch (Exception $e) {
    		return json_encode(array('estado' => '4', 'error'=> $e->getMessage()));
    	}
	}
	public static function cargarUsuario($id_usuario){
		try {
			$connection = Conexion::getConnect();
			$sql= 'SELECT * FROM usuario WHERE id_usuario = :id_usuario';
			$resultado = $connection->prepare($sql);
			$resultado->bindValue(':id_usuario', $id_usuario);
            $resultado->execute();
			if ($resultado->rowCount()>0){
               $datos = $resultado->fetchAll(PDO::FETCH_ASSOC);
			   return json_encode($datos);
            }else{
                return json_encode();
            }
		} catch (Exception $e) {
    		return json_encode(array('estado' => '4', 'error'=> $e->getMessage()));
    	}
	}
	public static function actualizarUsuario($id_usuario,$rut,$nombre,$ap_paterno,$ap_materno,$password,$id_tipo,$id_area,$estado){
		if($password!="666UCN66"){
			$actualizar_pass = true;
		}else{
			$actualizar_pass = false;
		}
		try {
    		$connection = Conexion::getConnect();
    		$password = self::codificado($password);  //Codificación de la passwd
			if($actualizar_pass){
				$sql= 'UPDATE usuario SET rut = :rut, nombre = :nombre, ap_paterno = :ap_paterno, ap_materno = :ap_materno ,
				pass = :pass, id_tipo = :id_tipo, id_area = :id_area, estado = :estado WHERE id_usuario = :id_usuario';
			}else{
				$sql= 'UPDATE usuario SET rut = :rut, nombre = :nombre, ap_paterno = :ap_paterno, ap_materno = :ap_materno ,
				 id_tipo = :id_tipo, id_area = :id_area, estado = :estado WHERE id_usuario = :id_usuario';
			}
    		$connection->beginTransaction();
    		$resultado = $connection->prepare($sql);
			$resultado->bindValue(':rut', $rut);
			$resultado->bindValue(':nombre', $nombre);
			$resultado->bindValue(':ap_paterno', $ap_paterno);
			$resultado->bindValue(':ap_materno', $ap_materno);
			if($actualizar_pass){
				$resultado->bindValue(':pass', $password);
			}
			$resultado->bindValue(':id_tipo', $id_tipo);
			$resultado->bindValue(':id_area', $id_area);
			$resultado->bindValue(':estado', $estado);
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

}

if(isset($_GET['func'])){
	switch ($_GET['func']) {
		case 'login':
			if(isset($_GET['user']) && isset($_GET['pass'])){
				echo Usuario::login(htmlspecialchars($_GET['user']),htmlspecialchars($_GET['pass']));
			}
			break;
		case 'loginWeb':
			if(isset($_GET['rut']) && isset($_GET['pass'])){
				echo Usuario::loginWeb(htmlspecialchars($_GET['rut']),htmlspecialchars($_GET['pass']));
			}
			break;
		case 'logout':
			if(isset($_GET['id_usuario'])){
				echo Usuario::logout(htmlspecialchars($_GET['id_usuario']));
			}
			break;
		case 'logoutWeb':
			echo Usuario::logoutWeb();
			break;
		case 'sessionWeb':
			echo Usuario::verificarSessionWeb();
			break;
		case 'update':
			if(isset($_GET['id_usuario']) && isset($_GET['user']) && isset($_GET['pass'])){
				echo Usuario::updateUser(htmlspecialchars($_GET['id_usuario']),htmlspecialchars($_GET['user']),htmlspecialchars($_GET['pass']));
			}
		case 'agregar':
			if(isset($_GET['rut']) && isset($_GET['nombres']) && isset($_GET['ap_paterno']) 
			&& isset($_GET['ap_materno']) && isset($_GET['pass']) && isset($_GET['id_tipo']) 
			&& isset($_GET['id_area']) && isset($_GET['estado'])){

				echo Usuario::agregarUser(htmlspecialchars($_GET['rut']),htmlspecialchars($_GET['nombres']),htmlspecialchars($_GET['ap_paterno']),
				htmlspecialchars($_GET['ap_materno']),htmlspecialchars($_GET['pass']),htmlspecialchars($_GET['id_tipo']),
				htmlspecialchars($_GET['id_area']),htmlspecialchars($_GET['estado']));

			}
			break;
		case 'actUser':
			if(isset($_GET['id_usuario']) && isset($_GET['rut']) && isset($_GET['nombres']) && isset($_GET['ap_paterno']) 
			&& isset($_GET['ap_materno']) && isset($_GET['pass']) && isset($_GET['id_tipo']) 
			&& isset($_GET['id_area']) && isset($_GET['estado'])){

				echo Usuario::actualizarUsuario(htmlspecialchars($_GET['id_usuario']),htmlspecialchars($_GET['rut']),htmlspecialchars($_GET['nombres']),
				htmlspecialchars($_GET['ap_paterno']),htmlspecialchars($_GET['ap_materno']),htmlspecialchars($_GET['pass']),htmlspecialchars($_GET['id_tipo']),
				htmlspecialchars($_GET['id_area']),htmlspecialchars($_GET['estado']));

			}
			break;
		case 'cargarUser':
			if(isset($_GET['id_usuario'])){
				echo Usuario::cargarUsuario(htmlspecialchars($_GET['id_usuario']));
			}
			break;
		case 'listUser':
			echo Usuario::listaUsuarios();
			break;
		case 'tipoUser':
			echo Usuario::TiposUsuarios();
			break;
		case 'areaUser':
			echo Usuario::areasUsuarios();
			break;
		default:
			break;
	}
}
else{
	echo 'Su Ip ha sido registrada, si sigue intentado hacer consultas no permitidas, nos veremos obligados a tomar acciones sobre su procedimiento.'; //De broma eso si xD
}
	



?>