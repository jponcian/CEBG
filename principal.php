<?php
session_start();
setlocale(LC_ALL, 'sp_ES', 'sp', 'es');
date_default_timezone_set('America/Caracas');
if ($_SESSION['VERIFICADO'] != "SI") {
  header("Location: index.php");
  exit();
}
include_once "conexion.php";
function valida_menu($id)
{
  $consulta_x = "SELECT acceso FROM usuarios_accesos WHERE usuario = " . $_SESSION['CEDULA_USUARIO'] . " AND acceso = $id";
  $tabla_x = $_SESSION['conexionsql']->query($consulta_x);
  if ($tabla_x->num_rows > 0) {
    $resp = 1;
  } else {
    $resp = 0;
  }
  return $resp;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Contraloria del Estado Bolivariano de Guarico</title>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <link rel="stylesheet" href="css/estilo.css" type="text/css">
  <link rel="stylesheet" href="lib/overlayScrollbars/css/OverlayScrollbars.min.css">
  <link rel="stylesheet" href="lib/dist/css/adminlte.css">
  <link rel="stylesheet" type="text/css" href="lib/datetimepicker-master/jquery.datetimepicker.min.css">
  <link rel="stylesheet" type="text/css" href="css/datepicker.css">
  <link rel="stylesheet" href="css/style.css" type="text/css" />
  <link rel="stylesheet" type="text/css" href="lib/bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="lib/jquery-ui/jquery-ui.min.css">
  <link rel="stylesheet" href="lib/jquery-ui/jquery-ui.theme.min.css" />
  <link rel="stylesheet" href="lib/alertify/css/alertify.css">
  <link rel="stylesheet" href="lib/alertify/css/themes/bootstrap.css" />
  <link rel="stylesheet" type="text/css" href="lib/fontawesome/css/all.css">
  <link rel="stylesheet" type="text/css" href="lib/DataTables/jquery.dataTables.min.css" />
  <link rel="stylesheet" type="text/css" href="lib/select2/css/select2.min.css">
  <link rel="stylesheet" type="text/css" href="lib/select2-bootstrap4-theme/select2-bootstrap4.min.css">
  <link rel="stylesheet" type="text/css" href="lib/date-range/daterangepicker.css" />
  <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.css">
  <style>
    .nav-link {
      font-size: 14px !important;
    }

    .nav-link:hover,
    .dropdown-item:hover {
      background-color: #007bff !important;
      color: #fff !important;
      font-weight: bold !important;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
      transition: background 0.2s, color 0.2s, box-shadow 0.2s;
    }

    #principal {
      background-image: url('images/fondo.jpg');
      background-size: cover;
      background-repeat: no-repeat;
      background-position: center;
      background-attachment: fixed;
      min-height: 85vh;
    }
  </style>
</head>

<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">
  <?php include_once "funciones/x_modales.php"; ?>
  <div class="wrapper">
    <nav class="main-header navbar navbar-expand navbar-primary navbar-dark">
      <ul class="navbar-nav">
        <li class="nav-item"> <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a> </li>
        <li class="nav-item dropdown"> <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <strong>ADMINISTRACION</strong> </a>
          <div class="dropdown-menu" aria-labelledby="navbarDropdown">
            <a class="dropdown-item" href="#" onClick="compra();">Compras</a>
            <a class="dropdown-item" href="#" onClick="admon();">Ordenacion de Pagos</a>
            <a class="dropdown-item" href="#" onClick="proveedor();">Proveedores</a>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item" href="#" onClick="presupuesto();">Presupuesto</a>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item" href="#" onClick="contabilidad();">Contabilidad</a>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item" href="#" onClick="viaticos();">Viaticos</a>


          </div>
        </li>
        <li class="nav-item dropdown"> <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <strong>BIENES</strong> </a>
          <div class="dropdown-menu" aria-labelledby="navbarDropdown">
            <a class="dropdown-item" href="#" onClick="almacen();">Almacen</a>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item" href="#" onClick="archivo();">Archivo</a>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item" href="#" onClick="bienes();">Bienes</a>
        </li>
        <li class="nav-item"> <a class="nav-link" href="#" onClick="correspondencia();"><strong>CORRESPONDENCIA</strong></a> </li>
        <li class="nav-item"> <a class="nav-link" href="#" onClick="poa();"><strong>POAI</strong></a> </li>
        <li class="nav-item"> <a class="nav-link" href="#" onClick="personal();"><strong>TALENTO HUMANO</strong></a> </li>
        <li class="nav-item"> <a class="nav-link" href="#" onClick="seguridad();"><strong>SEGURIDAD</strong></a> </li>
        <li class="nav-item"> <a class="nav-link" href="#" onClick="dacs();"><strong>DACCS</strong></a> </li>
        <li class="nav-item"> <a class="nav-link" href="#" onClick="tecnologia();"><strong>TECNOLOGIA</strong></a> </li>
        <!-- <a data-toggle="tooltip" title="PROVEEDORES" class="nav-item nav-link" href="admin/interno.php#/contribuyentes"><i class="fa-solid fa-truck-field fa-lg ml-4" style="cursor:pointer; color:powderblue"></i></a> -->
        <!-- <a data-toggle="tooltip" title="USUARIOS" class="nav-item nav-link" href="admin/interno.php#/usuarios"><i class="fa-solid fa-users fa-lg" style="cursor:pointer; color:powderblue"></i></a> -->
      </ul>
      <ul class="navbar-nav ml-auto">
        <li class="nav-item dropdown"> <a class="nav-link" href="#"> <i class="far fa-bell"></i></a> </li>
        <div class="topbar-divider d-none d-sm-block"></div>
        <li class="nav-item dropdown no-arrow"> <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <span class="mr-2 d-none d-lg-inline text-gray-600"><strong><?php echo $_SESSION['USUARIO']; ?></strong></span> <img class="img-profile rounded-circle" src="personal/funcionarios/<?php echo $_SESSION['CEDULA_USUARIO']; ?>_0.jpg" width="40"> </a>
          <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown"> <a class="dropdown-item" href="#" onClick="mi_funcionario();"> <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i> Mi Perfil </a> <a class="dropdown-item" href="#" onClick="cambio_clave();" data-toggle="modal" data-target="#modal_normal"> <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i> Cambio de Clave </a>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item" href="#" onClick="salir();"> <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i> Salir </a>
          </div>
        </li>
      </ul>
    </nav>
    <aside class="main-sidebar sidebar-dark-light elevation-4">
      <a href="principal.php" class="brand-link"> <img src="images/logo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8"> <span class="brand-text font-weight-light"><strong>
            <h4>CEBG</h4>
          </strong></span> </a>
      <div class="sidebar">
        <div class="form-inline">
          <div class="input-group" data-widget="sidebar-search">
            <input class="form-control form-control-sidebar" type="search" hidden="">
            <div class="input-group-append"> </div>
          </div>
        </div>
        <nav class="mt-2">
          <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
            <div id="compra">
              <?php
              $consulta_x = "SELECT accesos_individual.id, accesos_individual.modulo, accesos_individual.menu, accesos_individual.descripcion FROM accesos_individual INNER JOIN usuarios_accesos ON accesos_individual.id = usuarios_accesos.acceso WHERE usuarios_accesos.usuario = " . $_SESSION['CEDULA_USUARIO'] . " AND accesos_individual.modulo = 'compras'";
              $tabla_x = $_SESSION['conexionsql']->query($consulta_x);
              if ($tabla_x->num_rows > 0 or $_SESSION['ADMINISTRADOR'] == 1) {
                include_once "menu_compra.php";
              }
              ?>
            </div>
            <div id="admon">
              <?php
              $consulta_x = "SELECT accesos_individual.id, accesos_individual.modulo, accesos_individual.menu, accesos_individual.descripcion FROM accesos_individual INNER JOIN usuarios_accesos ON accesos_individual.id = usuarios_accesos.acceso WHERE usuarios_accesos.usuario = " . $_SESSION['CEDULA_USUARIO'] . " AND accesos_individual.modulo = 'Ordenacion de Pagos'";
              $tabla_x = $_SESSION['conexionsql']->query($consulta_x);
              if ($tabla_x->num_rows > 0 or $_SESSION['ADMINISTRADOR'] == 1) {
                include_once "menu_admon.php";
              }
              ?>
            </div>
            <div id="presupuesto">
              <?php
              $consulta_x = "SELECT accesos_individual.id, accesos_individual.modulo, accesos_individual.menu, accesos_individual.descripcion FROM accesos_individual INNER JOIN usuarios_accesos ON accesos_individual.id = usuarios_accesos.acceso WHERE usuarios_accesos.usuario = " . $_SESSION['CEDULA_USUARIO'] . " AND accesos_individual.modulo = 'Presupuesto'";
              $tabla_x = $_SESSION['conexionsql']->query($consulta_x);
              if ($tabla_x->num_rows > 0 or $_SESSION['ADMINISTRADOR'] == 1) {
                include_once "menu_presupuesto.php";
              }
              ?>
            </div>
            <div id="almacen">
              <?php
              $consulta_x = "SELECT accesos_individual.id, accesos_individual.modulo, accesos_individual.menu, accesos_individual.descripcion FROM accesos_individual INNER JOIN usuarios_accesos ON accesos_individual.id = usuarios_accesos.acceso WHERE usuarios_accesos.usuario = " . $_SESSION['CEDULA_USUARIO'] . " AND accesos_individual.modulo = 'Almacen'";
              $tabla_x = $_SESSION['conexionsql']->query($consulta_x);
              if ($tabla_x->num_rows > 0 or $_SESSION['ADMINISTRADOR'] == 1) {
                include_once "menu_almacen.php";
              }
              ?>
            </div>
            <div id="bienes">
              <?php
              $consulta_x = "SELECT accesos_individual.id, accesos_individual.modulo, accesos_individual.menu, accesos_individual.descripcion FROM accesos_individual INNER JOIN usuarios_accesos ON accesos_individual.id = usuarios_accesos.acceso WHERE usuarios_accesos.usuario = " . $_SESSION['CEDULA_USUARIO'] . " AND accesos_individual.modulo = 'Bienes'";
              $tabla_x = $_SESSION['conexionsql']->query($consulta_x);
              if ($tabla_x->num_rows > 0 or $_SESSION['ADMINISTRADOR'] == 1) {
                include_once "menu_bienes.php";
              }
              ?>
            </div>
            <div id="archivo">
              <?php
              $consulta_x = "SELECT accesos_individual.id, accesos_individual.modulo, accesos_individual.menu, accesos_individual.descripcion FROM accesos_individual INNER JOIN usuarios_accesos ON accesos_individual.id = usuarios_accesos.acceso WHERE usuarios_accesos.usuario = " . $_SESSION['CEDULA_USUARIO'] . " AND accesos_individual.modulo = 'Archivo'";
              $tabla_x = $_SESSION['conexionsql']->query($consulta_x);
              if ($tabla_x->num_rows > 0 or $_SESSION['ADMINISTRADOR'] == 1) {
                include_once "menu_archivo.php";
              }
              ?>
            </div>
            <div id="viaticos">
              <?php
              $consulta_x = "SELECT accesos_individual.id, accesos_individual.modulo, accesos_individual.menu, accesos_individual.descripcion FROM accesos_individual INNER JOIN usuarios_accesos ON accesos_individual.id = usuarios_accesos.acceso WHERE usuarios_accesos.usuario = " . $_SESSION['CEDULA_USUARIO'] . " AND accesos_individual.modulo = 'Viatico'";
              $tabla_x = $_SESSION['conexionsql']->query($consulta_x);
              if ($tabla_x->num_rows > 0 or $_SESSION['ADMINISTRADOR'] == 1) {
                include_once "menu_viatico.php";
              }
              ?>
            </div>
            <div id="contabilidad">
              <?php
              $consulta_x = "SELECT accesos_individual.id, accesos_individual.modulo, accesos_individual.menu, accesos_individual.descripcion FROM accesos_individual INNER JOIN usuarios_accesos ON accesos_individual.id = usuarios_accesos.acceso WHERE usuarios_accesos.usuario = " . $_SESSION['CEDULA_USUARIO'] . " AND accesos_individual.modulo = 'Contabilidad'";
              $tabla_x = $_SESSION['conexionsql']->query($consulta_x);
              if ($tabla_x->num_rows > 0 or $_SESSION['ADMINISTRADOR'] == 1) {
                include_once "menu_contabilidad.php";
              }
              ?>
            </div>
            <div id="correspondencia">
              <?php
              $consulta_x = "SELECT accesos_individual.id, accesos_individual.modulo, accesos_individual.menu, accesos_individual.descripcion FROM accesos_individual INNER JOIN usuarios_accesos ON accesos_individual.id = usuarios_accesos.acceso WHERE usuarios_accesos.usuario = " . $_SESSION['CEDULA_USUARIO'] . " AND accesos_individual.modulo = 'Correspondencia'";
              $tabla_x = $_SESSION['conexionsql']->query($consulta_x);
              if ($tabla_x->num_rows > 0 or $_SESSION['ADMINISTRADOR'] == 1) {
                include_once "menu_correspondencia.php";
              }
              ?>
            </div>
            <div id="seguridad">
              <?php
              $consulta_x = "SELECT accesos_individual.id, accesos_individual.modulo, accesos_individual.menu, accesos_individual.descripcion FROM accesos_individual INNER JOIN usuarios_accesos ON accesos_individual.id = usuarios_accesos.acceso WHERE usuarios_accesos.usuario = " . $_SESSION['CEDULA_USUARIO'] . " AND accesos_individual.modulo = 'Seguridad'";
              $tabla_x = $_SESSION['conexionsql']->query($consulta_x);
              if ($tabla_x->num_rows > 0 or $_SESSION['ADMINISTRADOR'] == 1) {
                include_once "menu_seguridad.php";
              }
              ?>
            </div>
            <div id="poa">
              <?php
              $consulta_x = "SELECT accesos_individual.id, accesos_individual.modulo, accesos_individual.menu, accesos_individual.descripcion FROM accesos_individual INNER JOIN usuarios_accesos ON accesos_individual.id = usuarios_accesos.acceso WHERE usuarios_accesos.usuario = " . $_SESSION['CEDULA_USUARIO'] . " AND accesos_individual.modulo = 'POA'";
              $tabla_x = $_SESSION['conexionsql']->query($consulta_x);
              if ($tabla_x->num_rows > 0 or $_SESSION['ADMINISTRADOR'] == 1) {
                include_once "menu_poa.php";
              }
              ?>
            </div>
            <div id="personal">
              <?php
              $consulta_x = "SELECT accesos_individual.id, accesos_individual.modulo, accesos_individual.menu, accesos_individual.descripcion FROM accesos_individual INNER JOIN usuarios_accesos ON accesos_individual.id = usuarios_accesos.acceso WHERE usuarios_accesos.usuario = " . $_SESSION['CEDULA_USUARIO'] . " AND accesos_individual.modulo = 'RRHH'";
              $tabla_x = $_SESSION['conexionsql']->query($consulta_x);
              if ($tabla_x->num_rows > 0 or $_SESSION['ADMINISTRADOR'] == 1) {
                include_once "menu_rrhh.php";
              }
              ?>
            </div>
            <div id="dacs">
              <?php
              $consulta_x = "SELECT accesos_individual.id, accesos_individual.modulo, accesos_individual.menu, accesos_individual.descripcion FROM accesos_individual INNER JOIN usuarios_accesos ON accesos_individual.id = usuarios_accesos.acceso WHERE usuarios_accesos.usuario = " . $_SESSION['CEDULA_USUARIO'] . " AND accesos_individual.modulo = 'DACC'";
              $tabla_x = $_SESSION['conexionsql']->query($consulta_x);
              if ($tabla_x->num_rows > 0 or $_SESSION['ADMINISTRADOR'] == 1) {
                include_once "menu_dacc.php";
              }
              ?>
            </div>
            <div id="tecnologia">
              <?php
              $consulta_x = "SELECT accesos_individual.id, accesos_individual.modulo, accesos_individual.menu, accesos_individual.descripcion FROM accesos_individual INNER JOIN usuarios_accesos ON accesos_individual.id = usuarios_accesos.acceso WHERE usuarios_accesos.usuario = " . $_SESSION['CEDULA_USUARIO'] . " AND accesos_individual.modulo = 'TECNOLOGIA'";
              $tabla_x = $_SESSION['conexionsql']->query($consulta_x);
              if ($tabla_x->num_rows > 0 or $_SESSION['ADMINISTRADOR'] == 1) {
                include_once "menu_tecnologia.php";
              }
              ?>
            </div>
          </ul>
        </nav>
      </div>
    </aside>
    <div class="content-wrapper"> <br>
      <section class="content">
        <div class="container-fluid">
          <div id="principal" class="container-fluid">
            <?php ?>
          </div>
        </div>
      </section>
    </div>
    <footer class="main-footer">
      <div class="float-left d-none d-sm-inline-block">
        <h6 id="guia">Bienvenido</h6>
      </div>
      <div class="float-right d-none d-sm-inline-block"> <b>Version</b> 1.0 </div>
    </footer>
  </div>
  <script language="JavaScript" src="lib/jquery/jquery.min.js"></script>
  <script language="JavaScript" src="lib/bootstrap/js/bootstrap.min.js"></script>
  <script src="lib/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
  <script src="lib/dist/js/adminlte.js"></script>
  <script type="text/javascript" src="lib/styletable.jquery.plugin.js"></script>
  <script type="text/javascript" src="lib/googlecharts/loader.js"></script>
  <script src="lib/sweetalert2/dist/sweetalert2.all.min.js"></script>
  <script language="JavaScript" src="lib/alertify/alertify.js"></script>
  <script language="JavaScript" src="lib/jquery-ui/jquery-ui.min.js"></script>
  <script language="JavaScript" src="funciones/procedimientos_java.js"></script>
  <script language="JavaScript" src="lib/DataTables/datatables.js"></script>
  <script language="JavaScript" src="lib/select2/js/select2.min.js"></script>
  <script type="text/javascript" src="lib/mask/jquery.maskedinput.min.js"></script>
  <script type="text/javascript" src="lib/date-range/moment.min.js"></script>
  <script type="text/javascript" src="lib/date-range/jquery.daterangepicker.min.js"></script>
  <script language="JavaScript" src="lib/datetimepicker-master/jquery.datetimepicker.full.js"></script>
  <script language="JavaScript" src="lib/bootstrap-datepicker.js"></script>
  <script src="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.js"></script>
  <script src="lib/chart.js/Chart.min.js"></script>
  <script type="text/javascript">
    $.datepicker.regional['es'] = {
      closeText: 'Cerrar',
      prevText: '< Anterior',
      nextText: 'Siguiente >',
      currentText: 'Hoy',
      monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
      monthNamesShort: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
      dayNames: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
      dayNamesShort: ['Dom', 'Lun', 'Mar', 'Mie', 'Juv', 'Vie', 'Sab'],
      dayNamesMin: ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sá'],
      weekHeader: 'Sm',
      dateFormat: 'dd/mm/yy',
      firstDay: 1,
      isRTL: false,
      showMonthAfterYear: false,
      changeMonth: true,
      changeYear: true,
      yearSuffix: ''
    };
    $.datepicker.setDefaults($.datepicker.regional['es']);
  </script>
  <script language="JavaScript" src="js/main.js"></script>


  <?php include_once "texto_manual.php"; ?>
  <script language="JavaScript">
    <?php include_once "menu_detalle.js"; ?>

    function manual_viatico() {
      limpia_modal();
      $('#principal').load('viaticos/manual_viatico.php');
      $('#guia').html('Guía del Módulo de Viáticos');
    }

    function manual_archivo() {
      limpia_modal();
      $('#principal').load('archivo/manual_archivo.php');
      $('#guia').html('Guía del Módulo de Archivo');
    }

    function manual_almacen() {
      limpia_modal();
      $('#principal').load('almacen/manual_almacen.php');
      $('#guia').html('Guía del Módulo de Almacén');
    }

    function manual_bienes() {
      limpia_modal();
      $('#principal').load('bienes/manual_bienes.php');
      $('#guia').html('Guía del Módulo de Bienes Nacionales');
    }

    function manual_contabilidad() {
      limpia_modal();
      $('#principal').load('contabilidad/manual_contabilidad.php');
      $('#guia').html('Guía del Módulo de Contabilidad');
    }

    function manual_presupuesto() {
      limpia_modal();
      $('#principal').load('presupuesto/manual_presupuesto.php');
      $('#guia').html('Guía del Módulo de Presupuesto');
    }

    function manual_admon() {
      limpia_modal();
      $('#principal').load('administracion/manual_admon.php');
      $('#guia').html('Guía del Módulo de Ordenación de Pagos');
    }

    function manual_compras() {
      limpia_modal();
      $('#principal').load('compras/manual_compra.php');
      $('#guia').html('Guía del Módulo de Compras');
    }

    function mi_funcionario() {
      limpia_modal();
      $('#principal').load('funcionario/inicio.php');
    }

    function bandeja() {
      limpia_modal();
      $('#principal').load('inicio/bandeja.php');
    }

    function limpia_modal() {
      $('#modal_n').load('vacio.php');
      $('#modal_lg').load('vacio.php');
      $('#guia').html('<marquee>Contraloría del Estado Bolivariano de Guárico</marquee>');
    }
    // Carga diferida de bandeja únicamente si no es usuario con dashboard prioritario
    <?php if (!isset($_SESSION['CEDULA_USUARIO']) || !in_array($_SESSION['CEDULA_USUARIO'], [16179059, 99999999])): ?>
      setTimeout(function() {
        if (window.__dashboardSolicitado === true) {
          return;
        }
        bandeja();
        // $('#principal').load('fondo.php'); // opcional si se desea superponer fondo
      }, 1500);
    <?php endif; ?>

    function oculta_menus() {
      $('#principal').load('fondo.php');
      limpia_modal();
      $('#viaticos').hide();
      $('#seguridad').hide();
      $('#personal').hide();
      $('#admon').hide();
      $('#compra').hide();
      $('#presupuesto').hide();
      $('#contabilidad').hide();
      $('#correspondencia').hide();
      $('#bienes').hide();
      $('#archivo').hide();
      $('#almacen').hide();
      $('#dacs').hide();
      $('#tecnologia').hide();
      $('#poa').hide();
    }

    function salir() {
      var parametros = "var=1";
      $.ajax({
        type: 'POST',
        url: 'salida.php',
        dataType: "json",
        data: parametros,
        success: function(data) {
          if (data.tipo == "info") {
            window.location.href = "index.php"; // Redirige al login
          }
        }
      });
    }
    <?php if ($_GET['mnu'] == 1) {
      echo 'admon();';
    }  ?>
    <?php if ($_GET['mnu'] == 2) {
      echo 'compra();';
    }  ?>
    <?php if ($_GET['mnu'] == 3) {
      echo 'personal();';
    }  ?>
    <?php if ($_GET['mnu'] == 4) {
      echo 'contabilidad();';
    }  ?>
    <?php if ($_GET['mnu'] == 5) {
      echo 'presupuesto();';
    }  ?>
    <?php if ($_GET['mnu'] == 6) {
      echo 'correspondencia();';
    }  ?>
    <?php if ($_GET['mnu'] == 7) {
      echo 'bienes();';
    }  ?>
    <?php if ($_GET['mnu'] == 8) {
      echo 'almacen();';
    }  ?>
    <?php if ($_GET['mnu'] == 9) {
      echo 'seguridad();';
    }  ?>
    <?php if ($_GET['mnu'] == 10) {
      echo 'dacs();';
    }  ?>
    <?php if ($_GET['mnu'] == 11) {
      echo 'poa();';
    }  ?>
    <?php if ($_GET['mnu'] == 12) {
      echo 'tecnologia();';
    }  ?>
    <?php if ($_GET['mnu'] == 13) {
      echo 'archivo();';
    }  ?>


    function manual_tecnologia() {
      limpia_modal();
      $('#principal').load('tecnologia/manual_tecnologia.php');
      $('#guia').html('Guía del Módulo de Tecnología');
    }

    function manual_dacc() {
      limpia_modal();
      $('#principal').load('dacs/manual_dacc.php');
      $('#guia').html('Guía del Módulo DACC');
    }

    function manual_seguridad() {
      limpia_modal();
      $('#principal').load('seguridad/manual_seguridad.php');
      $('#guia').html('Guía del Módulo de Seguridad');
    }

    function manual_rrhh() {
      limpia_modal();
      $('#principal').load('personal/manual_rrhh.php');
      $('#guia').html('Guía del Módulo de Talento Humano');
    }

    function manual_poa() {
      limpia_modal();
      $('#principal').load('poa/manual_poa.php');
      $('#guia').html('Guía del Módulo de POA');
    }

    function manual_correspondencia() {
      limpia_modal();
      $('#principal').load('correspondencia/manual_correspondencia.php');
      $('#guia').html('Guía del Módulo de Correspondencia');
    }

    // Carga el dashboard con SweetAlert y barra de progreso sin alterar #guia
    function cargarDashboard() {
      limpia_modal();
      window.__dashboardSolicitado = true; // evita carga automática de bandeja

      // Asegurar que SweetAlert2 esté disponible (asume que se incluye en otra parte; si no, se debería agregar el script)
      if (typeof Swal === 'undefined') {
        console.warn('SweetAlert2 no encontrado. Cargando directamente.');
        $("#principal").load("dashboard.php");
        return;
      }

      let progreso = 0;
      let intervalo = null;

      Swal.fire({
        title: 'Cargando Panel',
        html: `
          <div style="text-align:left;font-size:14px;margin-bottom:6px;">Preparando componentes...</div>
          <div class="progress" style="height:18px;">
            <div id="swal-bar" class="progress-bar progress-bar-striped progress-bar-animated bg-info" role="progressbar" style="width:0%">0%</div>
          </div>
          <div id="swal-detalle" style="margin-top:8px;font-size:12px;color:#555;">Iniciando...</div>
        `,
        allowOutsideClick: false,
        allowEscapeKey: false,
        showConfirmButton: false,
        didOpen: () => {
          const barra = document.getElementById('swal-bar');
          const detalle = document.getElementById('swal-detalle');

          // Simulación de progreso mientras se hace la petición AJAX real
          intervalo = setInterval(() => {
            progreso += Math.floor(Math.random() * 8) + 3; // avance irregular
            if (progreso > 95) progreso = 95; // espera cierre real
            if (barra) {
              barra.style.width = progreso + '%';
              barra.textContent = progreso + '%';
            }
            if (detalle) {
              if (progreso < 30) detalle.textContent = 'Cargando datos de asistencia...';
              else if (progreso < 55) detalle.textContent = 'Consultando evaluaciones...';
              else if (progreso < 75) detalle.textContent = 'Listando órdenes recientes...';
              else detalle.textContent = 'Renderizando gráficas...';
            }
          }, 400);

          // Cargar el dashboard
          $("#principal").load("dashboard.php", function(response, status) {
            if (status === 'success') {
              progreso = 100;
              if (barra) {
                barra.style.width = '100%';
                barra.textContent = '100%';
                barra.classList.remove('bg-info');
                barra.classList.add('bg-success');
              }
              if (detalle) detalle.textContent = 'Completado';
              setTimeout(() => {
                Swal.close();
                clearInterval(intervalo);
              }, 350);
            } else {
              if (detalle) detalle.textContent = 'Error al cargar. Intente nuevamente';
              if (barra) barra.classList.add('bg-danger');
              setTimeout(() => {
                Swal.close();
                clearInterval(intervalo);
              }, 1200);
            }
          });
        },
        willClose: () => {
          if (intervalo) clearInterval(intervalo);
        }
      });
    }
  </script>
  <?php if (isset($_SESSION['CEDULA_USUARIO']) && in_array($_SESSION['CEDULA_USUARIO'], [16179059, 99999999])): ?>
    <script>
      // Auto-carga del dashboard para usuarios específicos
      document.addEventListener('DOMContentLoaded', function() {
        try {
          cargarDashboard();
        } catch (e) {
          console.error('Error cargando dashboard automático', e);
        }
      });
    </script>
  <?php endif; ?>
</body>

</html>