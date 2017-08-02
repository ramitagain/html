
<html>
    <head>
    <?php
    require("barra.php");
    $edicion = false;
    if(isset($_GET['id_usuario'])){
        $edicion = true;
    }else{
        $edicion = false;
        $_GET['id_usuario'] = "";
    } 
    ?>    
    </head>
    <body>
        <div class="content-wrapper">
            <section class="content container-fluid">
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
                                <div class="col-lg-6">
                                    <form role="form" id="form_usuario1" action="#">
                                        <div class="form-group">
                                            <label class="control-label" for="">Rut:</label>
                                            <input id="rut" name="rut" type="text" class="form-control" placeholder="11111111-1" onKeyPress="return soloNumeros(event)" id="inputSuccess">
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label" for="">Nombres:</label>
                                            <input name="nombres" type="text" class="form-control" placeholder="Juan Pablo" id="inputSuccess">
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label" for="">Apellido Paterno:</label>
                                            <input name="ap_paterno" type="text" class="form-control" placeholder="Veliz" id="inputSuccess">
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label" for="">Apellido Materno:</label>
                                            <input name="ap_materno" type="text" class="form-control" placeholder="Cabrera" id="inputSuccess">
                                        </div>
                                    </form>
                                </div>
                                <!-- /.col-lg-6 (nested) -->
                                <div class="col-lg-6">
                                    <form role="form" id="form_usuario2" action="#">
                                        <div class="form-group">
                                            <label class="control-label" for="">Contraseña:</label>
                                            <input name="pass" type="password" class="form-control" placeholder="*******" id="inputSuccess">
                                        </div>
                                        <div class="form-group">
                                            <label>Tipo Usuario:</label>
                                            <select id="id_tipo" name="id_tipo" class="form-control">
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>Area</label>
                                            <select id="id_area" name="id_area" class="form-control">
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>Estado</label>
                                            <div class="checkbox">
                                                <label>
                                                    <input id="estado" type="checkbox" checked> Activo
                                                </label>
                                            </div>
                                        </div>
                                    </form>
                                    <button id="guardar"  class="btn btn-default">Guardar</button>
                                    <button id="borrar" class="btn btn-default">Borrar Todo</button>
                                </div>
                                <!-- /.col-lg-6 (nested) -->
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
<script src="js/jquery.rut.js"></script>
<script>
    function cargarTipos(){
        $.ajax({
            type: "GET",
            url: '../php/class/usuario.php?func=tipoUser', 
            dataType: "json",
            success: function(data){
            $.each(data,function(key, registro) {
                $("#id_tipo").append('<option value='+registro.id_tipo+'>'+registro.nombre+'</option>');
            });        
            },
            error: function(data) {
            alert('error');
            }
        });
    }
    function cargarAreas(){
        $.ajax({
            type: "GET",
            url: '../php/class/usuario.php?func=areaUser', 
            dataType: "json",
            success: function(data){
            $.each(data,function(key, registro) {
                $("#id_area").append('<option value='+registro.id_area+'>'+registro.nombre+'</option>');
            });        
            },
            error: function(data) {
            alert('error');
            }
        });
    }
    function validarCampos(flag){
        var datos = "";
        if($('input[name=rut]').val().length < 1){datos = datos+"Rut ";}
        if($('input[name=nombres]').val().length < 1){datos = datos+"Nombres ";}
        if($('input[name=ap_paterno]').val().length < 1){datos = datos+"Apellido Paterno ";}
        if($('input[name=ap_materno]').val().length < 1){datos = datos+"Apellido Materno ";}
        if(flag){
            //alert($('input[name=pass]').val());
            if($('input[name=pass]').val().length < 1){datos = datos+"Contraseña";}
        }else{
            //alert($('input[name=pass]').val());
            if($('input[name=pass]').val().length < 1){datos = datos+"Contraseña";}
        }
        if(datos != ""){
            alert("Debe rellenar todos los campos = "+datos);
            return false;
        }else{
            return true;
        }
    }
    function agregarUsuario(){
        if(validarCampos(true)){
            var data1 = $("#form_usuario1").serialize();
            var data2 = $("#form_usuario2").serialize();
            var estado = document.getElementById("estado").checked;
            data = "func=agregar&"+data1+"&"+data2+"&estado="+estado;
            //alert(data);
            console.log(data);
            $.getJSON({
                type: 'GET',
                url: '../../php/class/usuario.php',
                data: data,
                dataType: "json",        
                "success": function(data) {
                    console.log(data);
                }, 
                "error": function (data) {                    
                    console.log("ERROR "+ JSON.stringify(data));    
                }
            }); 
        }
    }
    function soloNumeros(e){
        var key = window.Event ? e.which : e.keyCode
        return (key >= 48 && key <= 57 || key == 107)
    }
    function cargarUsuario(id){
        data = "func=cargarUser&id_usuario="+id;
        $.getJSON({
                type: 'GET',
                url: '../php/class/usuario.php',
                data: data,
                dataType: "json",        
                "success": function(data) {
                    console.log(data);
                    for(var k in data) {
                        $("input[name='rut']").val(data[k].rut);
                        $("input[name='nombres']").val(data[k].nombre);
                        $("input[name='ap_paterno']").val(data[k].ap_paterno);
                        $("input[name='ap_materno']").val(data[k].ap_materno);
                        $("input[name='pass']").val("666UCN666");
                        document.getElementById("id_tipo").value = data[k].id_tipo;
                        document.getElementById("id_area").value = data[k].id_area;
                        if(data[k].estado == true){
                            //checkeado
                            document.getElementById("estado").checked = true;
                        }else{
                            //No checkeado
                            document.getElementById("estado").checked = false;
                        }
                    }
                }, 
                "error": function (data) {                    
                    console.log("ERROR "+ JSON.stringify(data));    
                }
            });
    }
    function guardarCambios(id){
        validarCampos(false);
        var data1 = $("#form_usuario1").serialize();
        var data2 = $("#form_usuario2").serialize();
        var estado = document.getElementById("estado").checked;
        data = "func=actUser&"+data1+"&"+data2+"&estado="+estado+"&id_usuario="+id;
        //alert(data);
        console.log(data);
        $.getJSON({
            type: 'GET',
            url: '../php/class/usuario.php',
            data: data,
            dataType: "json",        
            "success": function(data) {
                console.log(data);
                if(data.estado == '1'){
                    alert("Datos Actulizados Correctamente");
                    window.location.href = "modificar_usuario.php";
                }
            }, 
            "error": function (data) {                    
                console.log("ERROR "+ JSON.stringify(data));    
            }
        }); 
    }
    $(document).ready(function() {
        <?php
        if($edicion){
            echo '$("#borrar").hide();cargarUsuario(';
            echo $_GET['id_usuario'];
            ');$("#guardar").click(function(){guardarCambios(';
            echo $_GET["id_usuario"];
            echo ');});';
        }else{
            echo '$("#guardar").click(function(){agregarUsuario();});';
        }
            
        ?>
        cargarAreas();
        cargarTipos();
        
        $("#borrar").click(function(){
            window.location.href = "agregar_usuario.php";
        });
        $("input#rut").rut({
            useThousandsSeparator : false,
            formatOn: 'blur',
            minimumLength: 8, // validar largo mínimo; default: 2
            validateOn: 'change' // si no se quiere validar, pasar null
        });
        
    });
    </script>