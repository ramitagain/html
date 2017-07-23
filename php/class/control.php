<?php

require_once(__DIR__.'/../db/conexion.php');
require_once(__DIR__.'/usuario.php');

Class Control{

    public static function mostrarControl($id_usuario, $id_area, $session_id,$fecha_control,$id_herida){
        
        $connection = Conexion::getConnect();
        $respuesta = Array('estado'=>'1','mensaje'=>null,'controles'=>null);

        $query = $connection->prepare('SELECT control.fecha_control from control
                                    INNER JOIN diagnostico ON (diagnostico.id_diagnostico = control.id_diagnostico)
                                    WHERE control.id_diagnostico = :id_diagnostico
                                    ORDER BY (control.fecha_control) DESC limit 4');
         $query->bindValue(':id_diagnostico',$id_herida);
         $result = $query->execute();

         $cant_filas = $query->rowCount();

        if($cant_filas >0){

             $result = $query->fetchAll(PDO::FETCH_ASSOC);

                $respuesta = Array('estado'=>'1','controles'=>$result);
                return json_encode($respuesta);

        }else{
            $respuesta['estado'] = '3';
            $respuesta['herida'] = "Error al cargar las heridas";
        }
        return json_encode($respuesta);



    }

    public static function agregarControl($id_usuario, $id_area, $session_id, $fecha_control, $id_herida){

        $connection = Conexion::getConnect();
        $respuesta = Array('estado'=>'1','mensaje'=> null,'id'=> null);
        //Array de datos para la insercion.
        $datos = Array(
            'id_usuario'=>$id_usuario,
            'id_diagnostico'=>$id_herida,
            'fecha_control'=> $fecha_control
            );
        $query = $connection->prepare('INSERT INTO control(id_usuario,id_diagnostico,fecha_control) VALUES (:id_usuario,:id_diagnostico,:fecha_control)');
        $connection->beginTransaction();
        $resultado = $query->execute($datos);
        $connection->commit();

        if($resultado){
            $query_control= $connection->prepare('SELECT id_control FROM control ORDER BY (id_control) DESC LIMIT 1');
            $resultadoid = $query_control->execute();
            
            $cant_filas = $query_control->rowCount();

            if($cant_filas >0){
                    $result = $query_control->fetchAll(PDO::FETCH_ASSOC);

                    $respuesta['id'] = $result;
                    $respuesta['mensaje'] ='insercion realizada con exito';
                    return json_encode($respuesta);
                }

        }else{
            $respuesta['estado'] = '3';
            $respuesta['mensaje'] = "Error al agregar nueva herida";
        }
        return json_encode($respuesta);
    }

}

if(isset($_GET['func'])){
	switch ($_GET['func']) {
		case 'agregar':
			if(isset($_GET['id_usuario']) && isset($_GET['session_id'])){
                $resp = Usuario::verificarSession(htmlspecialchars($_GET['id_usuario']), htmlspecialchars($_GET['session_id']));
                if($resp){
                    echo Control::agregarControl(htmlspecialchars($_GET['id_usuario']), htmlspecialchars($_GET['id_area']), htmlspecialchars($_GET['session_id']), htmlspecialchars($_GET['fecha_control']), htmlspecialchars($_GET['id_herida']));
                }
				//echo Usuario::login(htmlspecialchars($_GET['user']),htmlspecialchars($_GET['pass']));
			}
			break;
        case 'mostrar':
            if(isset($_GET['id_usuario']) && isset($_GET['session_id'])){
                $resp = Usuario::verificarSession(htmlspecialchars($_GET['id_usuario']), htmlspecialchars($_GET['session_id']));
                if($resp){
                   echo Control::mostrarControl(htmlspecialchars($_GET['id_usuario']), htmlspecialchars($_GET['id_area']), htmlspecialchars($_GET['session_id']), htmlspecialchars($_GET['fecha_control']), htmlspecialchars($_GET['id_herida']));
                }
				//echo Usuario::login(htmlspecialchars($_GET['user']),htmlspecialchars($_GET['pass']));
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