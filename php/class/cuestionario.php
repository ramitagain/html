<?php
require_once(__DIR__.'/../db/conexion.php');
require_once(__DIR__.'/usuario.php');
Class Cuestionario{
    
    public static function cargarCuestionario($id_usuario, $id_area, $session_id){
        $connection = Conexion::getConnect();

        //CARGA LAS PREGUNTAS RELACIONADAS CON EL AREA RECIBIDA
        $datos = Array('id_area'=>$id_area);
        $query_preguntas = $connection->prepare('SELECT id_seccion,campo_seccion FROM seccion WHERE id_area =:id_area AND estado_seccion = true');
        foreach ($datos as $campo => $valor) {
            $query_preguntas->bindValue(":".$campo,$valor);
        }
        $query_preguntas->execute();

        $cant_filas = $query_preguntas->rowCount();
        //SI EXISTEN PREGUNTAS PARA ESTA AREA SE CARGARAN LAS ALTERNATIVAS PARA CADA UNA DE ESTAS
        if ($cant_filas > 0) {
            $result = $query_preguntas->fetchAll(PDO::FETCH_ASSOC);
            $respuesta = Array('estado'=>'1', 'preguntas'=>$result); // PREGUNTAS ENCONTRADAS
            $respuesta = Array();
            $contador_preguntas = 0;
            foreach ($result as $key => $row) {
                //print_r($row['id_seccion']);
                $datos = Array('id_seccion'=>$row['id_seccion']);
                $query_alternativas = $connection->prepare('SELECT opcion_seccion.id_opcion_seccion,opcion.id_opcion,id_seccion,valor_opcion,puntaje FROM opcion_seccion INNER JOIN opcion ON opcion.id_opcion = opcion_seccion.id_opcion WHERE id_seccion =:id_seccion');
                
                foreach ($datos as $campo => $valor) {
                    $query_alternativas->bindValue(":".$campo,$valor);
                }
                
                $query_alternativas->execute();
                $resultado_alternativas = $query_alternativas->fetchAll(PDO::FETCH_ASSOC);
                //print_r($resultado_alternativas);
                $array_respuestas = Array();
                $array_valor_respuestas = Array();
                foreach ($resultado_alternativas as $key1 => $row1) {
                    /*
                    echo $row1['id_opcion_seccion'];
                    echo $row1['id_opcion'];
                    echo $row1['id_seccion'];
                    echo $row1['valor_opcion'];
                    */   
                    $array_respuestas[$row1['id_opcion_seccion']] = $row1['valor_opcion'];
                    $array_valor_respuestas[$row1['id_opcion_seccion']] = $row1['puntaje'];
                }
                $respuesta[$contador_preguntas] = Array('0'=> $row['campo_seccion'], '1'=>$array_respuestas, '2'=>$array_valor_respuestas);
                $contador_preguntas++;
                //print_r($array_respuestas);
            }
            $respuesta['estado'] = '1';
            return json_encode($respuesta);
        }else{
            $respuesta = Array('estado'=>'2'); // NO SE ENCONTRARON PREGUNTAS
        }
        // SE ENVIA EL RESULTADO
    return json_encode($respuesta);
    }
    
}
if(isset($_GET['func'])){
	switch ($_GET['func']) {
		case 'cargar':
			if(isset($_GET['id_usuario']) && isset($_GET['id_area']) && isset($_GET['session_id'])){
                $resp = Usuario::verificarSession(htmlspecialchars($_GET['id_usuario']), htmlspecialchars($_GET['session_id']));
                if($resp){
                    echo Cuestionario::cargarCuestionario(htmlspecialchars($_GET['id_usuario']), htmlspecialchars($_GET['id_area']), 
                    htmlspecialchars($_GET['session_id']));
                }
				//echo Usuario::login(htmlspecialchars($_GET['user']),htmlspecialchars($_GET['pass']));
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