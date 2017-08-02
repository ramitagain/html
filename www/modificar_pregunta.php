
<html>
    <head>
    <?php
    require("barra.php");
    ?>
    <link rel="stylesheet" href="plugins/dynatable/jquery.dynatable.css">
    <link rel="stylesheet" href="plugins/datatables/css/dataTables.bootstrap.css">
    <style>

    </style>
    </head>
    <body>
        <div class="content-wrapper">
            <section class="content container-fluid">
                <div>
                    <div>
                        <div class="row">
                            <div class="col-lg-12">
                                <h1 class="page-header">Lista de usuarios</h1>
                            </div>
                            <!-- /.col-lg-12 -->
                        </div>
                        <!-- /.row -->
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="panel panel-default">
                                        <!-- /.panel-heading -->
                                        <div class="panel-body">
                                            <table width="100%" border="1" id="tabla1">
                                                <thead>
                                                    <tr>
                                                        <th width="40%" data-dynatable-column = "campo_seccion">Pregunta</th>
                                                        <th width="40%" data-dynatable-column = "estado_seccion">Estado</th>
                                                        <th width="10%" data-dynatable-column = "id_seccion"></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    
                                                </tbody>
                                            </table>
                                            <!-- /.table-responsive -->
                                        </div>
                                        <!-- /.panel-body -->
                                    </div>
                                    <!-- /.panel -->
                                </div>
                                <div class="col-lg-6">
                                    <div class="panel panel-default">
                                        <!-- /.panel-heading -->
                                        <div class="panel-body">
                                            <table width="100%" border="1" id="tabla2">
                                                <thead>
                                                    <tr>
                                                        <th width="20%" data-dynatable-column = "valor_opcion">Valor</th>
                                                        <th width="20%" data-dynatable-column = "puntaje">Puntaje</th>
                                                        <th width="20%" data-dynatable-column = "estado_opcion">Estado</th>
                                                        <th width="20%" data-dynatable-column = "id_opcion"></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    
                                                </tbody>
                                            </table>
                                            <!-- /.table-responsive -->
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

<script src="plugins/dynatable/jquery.dynatable.js"></script>



<script src="bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<script src="bower_components/fastclick/lib/fastclick.js"></script>
<script src="dist/js/adminlte.min.js"></script>
<script src="bower_components/jquery-sparkline/dist/jquery.sparkline.min.js"></script>
<script src="plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script>
<script src="plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script>
<script src="bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
<script src="bower_components/Chart.js/Chart.js"></script>
<script src="dist/js/demo.js"></script>

<script>
    function cargarPreguntas(id){
        $("#tabla2").empty
        $.getJSON({
            type: 'GET',
            url: "../php/class/cuestionario.php",
            data: {func: "listAlt",id_seccion: id},
            dataType: "json",        
            "success": function(data) {
                console.log(data);  
                /*
                for(var k in data) {
                    var preguntas = '<a href="#" onClick="cargarPreguntas('+ data[k].id_seccion+')"><i class="fa fa-2x fa-chevron-right" aria-hidden="false"></i></a>';
                    //var desactivar = '<a href="#" onClick="desactUsuario('+ data[k].id_usuario+')"><i class="fa fa-2x fa-trash-o" aria-hidden="false"></i></a>';
                    data[k].id_seccion = preguntas
                    if(data[k].estado_seccion == true){
                        data[k].estado_seccion = "Activo";
                    }else{
                        data[k].estado_seccion = "Inactivo";
                    }
                }*/
                var dynatable = $('#tabla2').dynatable({
                    dataset: {
                        records: data
                    }, table: {
                        copyHeaderClass: true
                    }
                }).data('dynatable');
                dynatable.settings.dataset.originalRecords = data;
                dynatable.process();
            }, 
            "error": function (data) {                    
                console.log("ERROR "+ JSON.stringify(data));    
            }
        });
    }
$(document).ready(function() {
    $.getJSON({
        type: 'GET',
        url: "../php/class/cuestionario.php",
        data: {func: "listQ"},
        dataType: "json",        
        "success": function(data) {
            console.log(data);  
            for(var k in data) {
                var preguntas = '<a href="#" onClick="cargarPreguntas('+ data[k].id_seccion+')"><i class="fa fa-2x fa-chevron-right" aria-hidden="false"></i></a>';
                //var desactivar = '<a href="#" onClick="desactUsuario('+ data[k].id_usuario+')"><i class="fa fa-2x fa-trash-o" aria-hidden="false"></i></a>';
                data[k].id_seccion = preguntas
                if(data[k].estado_seccion == true){
                    data[k].estado_seccion = "Activo";
                }else{
                    data[k].estado_seccion = "Inactivo";
                }
            }
            var dynatable = $('#tabla1').dynatable({
                dataset: {
                    records: data
                }, table: {
                    copyHeaderClass: true
                }
            }).data('dynatable');
            dynatable.settings.dataset.originalRecords = data;
            dynatable.process();
        }, 
        "error": function (data) {                    
            console.log("ERROR "+ JSON.stringify(data));    
        }
    });
    
});
</script>