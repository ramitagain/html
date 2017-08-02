
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
                                <div class="col-lg-12">
                                    <div class="panel panel-default">
                                        <!-- /.panel-heading -->
                                        <div class="panel-body">
                                            <table width="100%" border="1" id="tabla">
                                                <thead>
                                                    <tr>
                                                        <th width="20%" data-dynatable-column = "rut">Rut</th>
                                                        <th width="20%" data-dynatable-column = "ap_paterno">Apellido Paterno</th>
                                                        <th width="20%" data-dynatable-column = "ap_materno">Apellido Materno</th>
                                                        <th width="20%" data-dynatable-column = "nombre">Nombres</th>
                                                        <th width="5%"data-dynatable-column = "estado">Estado</th>
                                                        <th width="5%"data-dynatable-column = "id_usuario"></th>
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
    function editarUsuario(id){
        window.location.href = "agregar_usuario.php?id_usuario="+id;  
    }
$(document).ready(function() {
    $.getJSON({
        type: 'GET',
        url: "../php/class/usuario.php",
        data: {func: "listUser"},
        dataType: "json",        
        "success": function(data) {
            //console.log(data);  
            for(var k in data) {
                var editar = '<a href="#" onClick="editarUsuario('+ data[k].id_usuario+')"><i class="fa fa-2x fa-pencil-square-o" aria-hidden="false"></i></a>';
                var desactivar = '<a href="#" onClick="desactUsuario('+ data[k].id_usuario+')"><i class="fa fa-2x fa-trash-o" aria-hidden="false"></i></a>';
                if(data[k].estado == true){
                    data[k].estado = "Activo";
                }else{
                    data[k].estado = "Inactivo";
                }
                data[k].id_usuario = editar;
                console.log(data[k].id_usuario);
            }
            $('#tabla').dynatable({
                dataset: {
                    records: data
                }, table: {
                    copyHeaderClass: true
                }
            });
        }, 
        "error": function (data) {                    
            console.log("ERROR "+ JSON.stringify(data));    
        }
    });
    
});
</script>