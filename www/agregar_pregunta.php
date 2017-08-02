<html>
    <head>
    <?php
    require("barra.php");
    ?>    
    </head>
    <body>
        <div class="content-wrapper">
            <section class="content container-fluid">
                <div>
                    <div>
                        <div>
                            <div class="row">
                            <div class="col-lg-12">
                                <h1 class="page-header">Usuario</h1>
                            </div>
                            <!-- /.col-lg-12 -->
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="panel panel-default">
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <form role="form" id="form_pregunta">
                                                    <div class="form-group">
                                                        <label class="control-label" for="">Pregunta:</label>
                                                        <input id="pregunta" name="pregunta" type="text" class="form-control" placeholder="¿Que hora es?" id="inputSuccess">
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label>¿Pregunta con puntaje?</label>
                                            <div class="checkbox">
                                                <label>
                                                    <input id="si" type="checkbox" checked> Si
                                                </label>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-12">
                                                    <label class="control-label" for="">Alternativas:</label><button id="agregar" class="btn btn-default">Agregar</button></div>
                                                    <br>
                                                    <br>
                                                <div class="col-lg-12">
                                                    <form id="lista_alternativas">
                                                        <table id="alternativas" width="100%">
                                                            <thead>
                                                                <tr>
                                                                    <td class="col1" width="80%">Opción</td>
                                                                    <td class="col2" width="15%">Puntaje</td>
                                                                    <td class="col3" width="5%"></td>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                
                                                                    <tr>
                                                                        <td><input name="alternativa0" type="text" class="form-control" placeholder="Son las 13:00" id="inputSuccess"></td>
                                                                        <td><input name="puntaje0" type="text" class="form-control" placeholder="1" id="puntaje"></td>
                                                                        <td>&nbsp<a href="#" onClick="borrarFila(this)"><i class="fa fa-times" aria-hidden="false"></i></a></td>
                                                                    </tr>
                                                                
                                                            </tbody>
                                                        </table>
                                                    </form>
                                                    <br>
                                                </div>
                                            </div>
                                            <button id="guardar" type="submit" class="btn btn-default">Guardar</button>
                                            <button id="borrar" type="submit" class="btn btn-default">Borrar</button>
                                        </div>
                                        <!-- /.row (nested) -->
                                    </div>
                                    <!-- /.panel-body -->
                                </div>
                                <!-- /.panel -->
                            </div>
                            <!-- /.col-lg-12 -->
                        </div>
                    </div>
                </div>
            </section>
            <!-- /.content -->
        </div>
    <?php
    require("footer.php");
    ?>
    </body>
</html>
<script>
    function guardarDatos(pregunta,alternativas){
        console.log(pregunta);
        console.log(alternativas);
    }
    function borrarFila(btn){
        var cant_filas = $('#alternativas >tbody >tr').length;
        //console.log("Cantidad de filas->"+cant_filas);
        //console.log("Delete Row");
        if(cant_filas > 1){
            var row = btn.parentNode.parentNode;
            row.parentNode.removeChild(row);
        }else{
            alert("No es posible eliminar todas las filas");
        }
        
    }
    $(document).ready(function() {
        var cont = 1;
        $('#agregar').click(function(){
            $('#alternativas > tbody:last-child').append('<tr><td><input name="alternativa'+cont+'" type="text" class="form-control" placeholder="Son las 13:00" id="inputSuccess"></td><td><input name="puntaje'+cont+'" type="text" class="form-control" placeholder="1" id="inputSuccess"></td><td>&nbsp<a onClick="borrarFila(this);"href="#"><i class="fa fa-times" aria-hidden="false"></i></a></td></tr>');
            cont++;
            console.log("Nuevo campo");
        });
        $('#guardar').click(function(){
            var pregunta = $("#pregunta").val();
            var alternativas = $("#lista_alternativas").serialize();
            if(pregunta.length  != 0){
                guardarDatos(pregunta,alternativas);
            }else{
                alert("Debe rellenar todos los campos");
            }
        });
        $("#borrar").click(function(){
            window.location.href = "agregar_pregunta.php";
        });
        $('#si').change(function() {
            var bool = $('#si').is(':checked');
            if(bool){
                var num = 2;
                $("#table td:nth-child("+ num +"),td:nth-child("+ num +")").show();
            }else{
                var num = 2;
                $("#table td:nth-child("+ num +"),td:nth-child("+ num +")").hide();
            }
            
        });
    });
</script>