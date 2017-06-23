<?php
require("conexion.php");	
header("Content-Type: text/html;charset=utf-8");

if(isset($_POST['id_area']) && isset($_POST['session_id'])){
    //RECIBE LOS DATOS 
    $id_area = $_POST['id_area'];
    $session_id = $_POST['session_id'];
    // CREA LA CONEXION Y PREGUNTA SI LA SESSION ESTA INICIADA
    $db = new Connection();
    //$db->isSessionId();

    //CARGA LAS PREGUNTAS RELACIONADAS CON EL AREA RECIBIDA
    $datos = Array('id_area'=>$id_area);
    $query_preguntas = $db->prepare('SELECT id_seccion,campo_seccion FROM seccion WHERE id_area =:id_area AND estado_seccion = true');
    foreach ($datos as $campo => $valor) {
        $query_preguntas->bindValue(":".$campo,$valor);
    }
    $query_preguntas->execute();

    $cant_filas = $query_preguntas->rowCount();
    //SI EXISTEN PREGUNTAS PARA ESTA AREA SE CARGARAN LAS ALTERNATIVAS PARA CADA UNA DE ESTAS
    if ($cant_filas > 0) {
        $result = $query_preguntas->fetchAll(PDO::FETCH_ASSOC);
        //$respuesta = Array('estado'=>'1', 'preguntas'=>$result); // PREGUNTAS ENCONTRADAS
        $respuesta = Array();
        $contador_preguntas = 0;
        foreach ($result as $key => $row) {
            //print_r($row['id_seccion']);
            $datos = Array('id_seccion'=>$row['id_seccion']);
            $query_alternativas = $db->prepare('SELECT opcion_seccion.id_opcion_seccion,opcion.id_opcion,id_seccion,valor_opcion FROM opcion_seccion INNER JOIN opcion ON opcion.id_opcion = opcion_seccion.id_opcion WHERE id_seccion =:id_seccion');
            
            foreach ($datos as $campo => $valor) {
                $query_alternativas->bindValue(":".$campo,$valor);
            }
            
            $query_alternativas->execute();
            $resultado_alternativas = $query_alternativas->fetchAll(PDO::FETCH_ASSOC);
            //print_r($resultado_alternativas);
            $array_respuestas = Array();
            foreach ($resultado_alternativas as $key1 => $row1) {
                /*
                echo $row1['id_opcion_seccion'];
                echo $row1['id_opcion'];
                echo $row1['id_seccion'];
                echo $row1['valor_opcion'];
                */   
                $array_respuestas[$row1['id_opcion_seccion']] = $row1['valor_opcion'];
            }
            $respuesta['estado'] = '1';
            $respuesta[$contador_preguntas] = Array('pregunta'=>$row['campo_seccion'],'alternativas'=>$array_respuestas);
            $contador_preguntas++;
            //print_r($array_respuestas);
        }
        
        
    }else{
        $respuesta = Array('estado'=>'2'); // NO SE ENCONTRARON PREGUNTAS
    }
}else{
    $respuesta = Array('estado'=>'2'); // NO SE ENCONTRARON PREGUNTAS
}
// SE ENVIA EL RESULTADO
echo json_encode($respuesta,JSON_UNESCAPED_UNICODE);
?>