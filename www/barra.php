<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Heridapp | Home</title>
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <link rel="stylesheet" href="bower_components/bootstrap/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="bower_components/font-awesome/css/font-awesome.min.css">
  <link rel="stylesheet" href="bower_components/Ionicons/css/ionicons.min.css">
  <link rel="stylesheet" href="dist/css/AdminLTE.min.css">
  <link rel="stylesheet" href="dist/css/skins/skin-blue.min.css">
  <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

  <!-- Main Header -->
  <header class="main-header">

    <!-- Logo -->
    <a href="index.html" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini"><b>H</b>pp</span>
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg"><b>Herid</b>App</span>
    </a>
    <nav class="navbar navbar-static-top" role="navigation">
      <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
        <span class="sr-only"></span>
      </a>
    </nav>
  </header>
  <!-- Left side column. contains the logo and sidebar -->
  <aside class="main-sidebar">

    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">

      <!-- Sidebar user panel (optional) -->
      <div class="user-panel">
        <div class="pull-left image">
          <img src="dist/img/user1.jpg" class="img-circle" alt="">
        </div>
        <div class="pull-left info">
          <p id="nombre_usuario"></p>
          <!-- Status -->
          <a href="#"><i class="fa fa-circle text-success"></i>En línea</a>
        </div>
      </div>
      <!-- /.search form -->

      <!-- Sidebar Menu -->
      <ul class="sidebar-menu" data-widget="tree">
        <li class="header">Home</li>
        <li class="treeview">
          <a href="#"><i class="fa fa-users"></i> <span>Usuarios</span>
            <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="agregar_usuario.php">Agregar</a></li>
            <li><a href="modificar_usuario.php">Modificar</a></li>
          </ul>
        </li>
        <li class="treeview">
          <a href="#"><i class="fa fa-question"></i> <span>Preguntas</span>
            <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="agregar_pregunta.php">Agregar</a></li>
            <li><a href="modificar_pregunta.php">Modificar</a></li>
          </ul>
        </li>
        <li id="logout"><a href="#"><i class="fa fa-chevron-left "></i> <span>Salir</span></a></li>
      </ul>
      <!-- /.sidebar-menu -->
    </section>
    <!-- /.sidebar -->
  </aside>
  <script src="bower_components/jquery/dist/jquery.min.js"></script>
  <script>
    function logout(rut,pass){
        var url = '../php/class/usuario.php?func=logoutWeb';
        //alert(url);
        $.ajax({
            type: "GET",
            url: url, 
            dataType: "json",
            success: function(data){
                console.log(data)
                if(data.estado == '1'){
                    window.location.href = "login.php";  
                }else{
                    alert("Error al cerrar sesión");
                } 
            },
            error: function(data) {
                console.log(data);
                alert('Error al cerrar sesión');
            }
        });
    }
    function verificar(){
        var url = '../php/class/usuario.php?func=sessionWeb';
        //alert(url);
        $.ajax({
            type: "GET",
            url: url, 
            dataType: "json",
            success: function(data){
                console.log("Verificacion de sesión");
                console.log(data)
                if(data.estado != '1'){
                    window.location.href = "login.php";  
                }else{
                    document.getElementById("nombre_usuario").innerHTML = data.nombre;
                }
            },
            error: function(data) {
                console.log(data);
                alert('No se pudo verificar la sesión');
            }
        });
    }
    $(document).ready(function() {
      verificar();
        $("#logout").click(function(){
            var rut = $("#rut").val();
            var pass = $("#pass").val();
            logout(rut,pass);
        });
    });
</script>
