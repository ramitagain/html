<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>HeridApp | Inicio Sesión</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="bower_components/bootstrap/dist/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="bower_components/font-awesome/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="bower_components/Ionicons/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/AdminLTE.min.css">
  <!-- iCheck -->
  <link rel="stylesheet" href="plugins/iCheck/square/blue.css">

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

  <!-- Google Font -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>
<body class="hold-transition login-page">
<div class="login-box">
  <div class="login-logo">
    <a href="index2.html"><b>Herid</b>App</a>
  </div>
  <!-- /.login-logo -->
  <div class="login-box-body">
    <p class="login-box-msg">Inicia Sesión</p>

      <div class="form-group has-feedback">
        <input id="rut" type="text" class="form-control" placeholder="11111111-1" onKeyPress="return soloNumeros(event)" autofocus>
        <span></span>
      </div>
      <div class="form-group has-feedback">
        <input id="pass" type="password" class="form-control" placeholder="Password">
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
      </div>
      <div class="row">
        <div class="col-xs-8">
          
        </div>
        <!-- /.col -->
        <div class="col-xs-4">
          <button id="ingresar" type="submit" class="btn btn-primary btn-block btn-flat">Ingresar</button>
        </div>
        <!-- /.col -->
      </div>

    <a href="#">Olvide mi contraseña</a><br>

  </div>
  <!-- /.login-box-body -->
</div>
<!-- /.login-box -->

<!-- jQuery 3 -->
<script src="bower_components/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<script src="js/jquery.rut.js"></script>
<script>
    function soloNumeros(e){
        var key = window.Event ? e.which : e.keyCode
        return (key >= 48 && key <= 57 || key == 107)
    }
    function ingresar(rut,pass){
        var url = '../php/class/usuario.php?func=loginWeb&rut='+rut+"&pass="+pass;
        //console.log(rut);
        //alert(url);
        $.ajax({
            type: "GET",
            url: url, 
            dataType: "json",
            success: function(data){
                console.log(data);
                if(data.estado == '1'){
                    window.location.href = "index.php";  
                }else if(data.estado == '2'){
                    alert("Usuario y/o Contraseña incorrecta");
                }else {
                    alert("Error contacte al administrador");
                }
            },
            error: function(data) {
                console.log(data);
                alert('Error'+data);
            }
        });
    }
    function validarCampos(rut,pass){
        if(rut.length > 0 || pass.length > 0){
            ingresar(rut,pass);
        }else{
            alert("Debe rellenar los campos");
        }
    }
    $(document).ready(function() {
        $("#ingresar").click(function(){
            var rut = $("#rut").val();
            var pass = $("#pass").val();
            validarCampos(rut,pass);
        });
        $("input#rut").rut({
            useThousandsSeparator : false,
            formatOn: 'blur',
            minimumLength: 8, // validar largo mínimo; default: 2
            validateOn: 'change' // si no se quiere validar, pasar null
        });
    });
</script>
</body>
</html>
