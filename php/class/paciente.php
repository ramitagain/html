<?php
require_once(__DIR__.'/../db/conexion.php');
require_once(__DIR__.'/usuario.php');
Class Paciente{
    public static function buscar($rut){
        try {
            $connection = Conexion::getConnect();
            $sql='SELECT * FROM ficha WHERE rut_ficha =:rut';
            $resultado = $connection->prepare($sql);
            $resultado->bindValue(':rut', $rut);
            $resultado->execute();
            if ($resultado->rowCount()>0){
                return json_encode(array('estado' => '1', 'datos'=>$resultado->fetchAll(PDO::FETCH_ASSOC)));
            }else{
                return json_encode(array('estado' => '2', 'msj'=>'Datos invalidos'));
            }
        } catch (Exception $e) {
            return json_encode(array('estado' => '4', 'error'=> $e->getMessage()));
        }
        
    }
    public static function agregar($id_usuario,$rut,$nombres,$ap_paterno,$ap_materno,$fecha_nacimiento,$patologias,$sexo){
        try {
            $connection = Conexion::getConnect();
            $sql='INSERT INTO ficha (rut_ficha,nombres_ficha,ap_paterno_ficha,ap_materno_ficha,fecha_nacimiento_ficha,patologia_ficha,id_usuario,sexo) 
            VALUES (:rut,:nombres,:ap_paterno,:ap_materno,:fecha_nacimiento,:patologias,:id_usuario,:sexo);';

            $resultado = $connection->prepare($sql);
            $resultado->bindValue(':rut', $rut);
            $resultado->bindValue(':nombres', $nombres);
            $resultado->bindValue(':ap_paterno', $ap_paterno);
            $resultado->bindValue(':ap_materno', $ap_materno);
            $resultado->bindValue(':fecha_nacimiento', $fecha_nacimiento);
            $resultado->bindValue(':patologias', $patologias);
            $resultado->bindValue(':id_usuario', $id_usuario);
            $resultado->bindValue(':sexo', $sexo);

            $connection->beginTransaction();

            $ejecucion = $resultado->execute();

            $connection->commit();
            if ($ejecucion){
                return json_encode(array('estado' => '1', 'msj'=> 'agregado correctamente'));
            }else{
                return json_encode(array('estado' => '2', 'msj'=>'Datos invalidos'));
            }
        } catch (Exception $e) {
            return json_encode(array('estado' => '4', 'error'=> $e->getMessage()));
        }
    }
    public static function modificar(){
        
    }
}
if(isset($_GET['func'])){
	switch ($_GET['func']) {
		case 'buscar':
			if(isset($_GET['rut'])){
                //$resp = Usuario::verificarSession(htmlspecialchars($_GET['id_usuario']), htmlspecialchars($_GET['session_id']));
                // if($resp){
                //     //echo Paciente::buscar(htmlspecialchars($_GET['rut']));
                // }
                echo Paciente::buscar(htmlspecialchars($_GET['rut']));
            }
            else{
                echo "Faltan datos";
            }
			break;
        case 'agregar':
			if(isset($_GET['nombres']) && isset($_GET['ap_paterno']) && isset($_GET['ap_materno']) 
            && isset($_GET['fecha_nacimiento']) && isset($_GET['sexo']) && isset($_GET['id_usuario']) && isset($_GET['rut'])){
                echo Paciente::agregar(
                htmlspecialchars($_GET['id_usuario']),htmlspecialchars($_GET['rut']),
                htmlspecialchars($_GET['nombres']),htmlspecialchars($_GET['ap_paterno']),
                htmlspecialchars($_GET['ap_materno']),htmlspecialchars($_GET['fecha_nacimiento']),
                htmlspecialchars($_GET['patologia']),htmlspecialchars($_GET['sexo']));
            }
            else{
                echo "Faltan datos";
            }
			break;
		default:
	           echo "Default";
			break;
	}
}
else{
	echo 'Su Ip ha sido registrada, si sigue intentado hacer consultas no permitidas, nos veremos obligados a tomar acciones sobre su procedimiento.'; //De broma eso si xD
}
?>
