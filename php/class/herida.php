<?php

require_once(__DIR__.'/../db/conexion.php');
require_once(__DIR__.'/usuario.php');

Class Herida{



    public static function agregarHerida($id_usuario,$id_area,$session_id,$id_ficha,$fecha_inicio){
      try{ 
            $connection = Conexion::getConnect();
    
            $datos = Array(
                'id_ficha'=> $id_ficha,
                'id_usuario'=> $id_usuario,
                'fecha_inicio'=>$fecha_inicio
            );

            $query = $connection->prepare('INSERT INTO diagnostico(id_ficha,id_usuario,fecha_inicio) values (:id_ficha,:id_usuario,:fecha_inicio)');
            $connection->beginTransaction();
            $resultado = $query->execute($datos);
            $connection->commit();
            if($resultado){
                $query_diagnostico= $connection->prepare('SELECT id_diagnostico FROM diagnostico ORDER BY (id_diagnostico) DESC LIMIT 1');
                $resultadoid = $query_diagnostico->execute();

                if($resultado->rowCount() >0){
                            $respuesta = Array('estado'=>'1','herida'=>$resultado->fetchAll(PDO::FETCH_ASSOC));
                            return json_encode($respuesta);
                }
            }else{
                    return json_encode(array('estado' => '2', 'msj'=>'Datos invalidos'));
            }
        } catch (Exception $e) {
            return json_encode(array('estado' => '4', 'error'=> $e->getMessage()));
        }
            
        return json_encode($respuesta);
    }

    public static function mostrarHerida($id_ficha){
        try {
            $connection = Conexion::getConnect();
            $resultado = $connection->prepare('SELECT distinct c.fecha_control AS fecha , d.id_diagnostico AS herida, d.descripcion as nombre,t.tipo as tipo
                                        FROM control c                                        
                                        INNER JOIN diagnostico d on (c.id_diagnostico = d.id_diagnostico)
                                        INNER JOIN ficha f on (f.id_ficha = d.id_ficha)
                                        INNer JOIN tipo_herida t on (t.id_tipo_herida = c.id_tipo_herida)
                                        INNER JOIN (SELECT MAX(fecha_control) as fecha, id_diagnostico from control group by(id_diagnostico)) l on l.fecha = c.fecha_control
                                        WHERE d.id_ficha = :id_ficha and l.fecha=c.fecha_control
                                        ORDER BY (c.fecha_control) DESC' );
            $resultado->bindValue(':id_ficha',$id_ficha);
            $resultado->execute();

            if($resultado->rowCount() >0){
                $respuesta = Array('estado'=>'1','herida'=>$resultado->fetchAll(PDO::FETCH_ASSOC));
                return json_encode($respuesta);
            }else{
                return json_encode(array('estado' => '2', 'msj'=>'Datos invalidos'));
            }
        } catch (Exception $e) {
            return json_encode(array('estado' => '4', 'error'=> $e->getMessage()));
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
                    echo Herida::agregarHerida(htmlspecialchars($_GET['id_usuario']), htmlspecialchars($_GET['id_area']), 
                    htmlspecialchars($_GET['session_id']),htmlspecialchars($_GET['id_ficha']),htmlspecialchars($_GET['fecha_inicio']));
                }
			}
			break;
        case 'mostrar':
            if(isset($_GET['id_ficha'])){
                echo Herida::mostrarHerida(htmlspecialchars($_GET['id_ficha']));
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

