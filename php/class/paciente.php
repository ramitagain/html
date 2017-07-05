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

            if ($resultado->rowCount()>0){
                $result = $query->fetchAll(PDO::FETCH_ASSOC);
                return json_encode(array('estado' => '1', 'datos'=>$result));
            }else{
                return json_encode(array('estado' => '2', 'msj'=>'No se encontre el RUT: $rut'));
            }
        } catch (Exception $e) {
			return json_encode(array('estado' => '4', 'error'=> $e->getMessage()));
		}
        
    }
    public static function agregar(){
        
    }
    public static function modificar(){
        
    }
    public static function cargarHistorial(){
        
    }
}
if(isset($_GET['func'])){
	switch ($_GET['func']) {
		case 'buscar':
			if(isset($_GET['id_usuario']) && isset($_GET['rut']) && isset($_GET['session_id'])){
                $resp = Usuario::verificarSession(htmlspecialchars($_GET['id_usuario']), htmlspecialchars($_GET['session_id']));
                if($resp){
                    echo Paciente::buscar(htmlspecialchars($_GET['rut']));
                }
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
