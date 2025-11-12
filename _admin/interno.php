<?php
session_start();
include_once "../conexion.php";

if ($_SESSION['VERIFICADO'] != "SI") { 
header ("Location: ../validacion.php?opcion=val"); 
exit(); }
?>
<!DOCTYPE html>
<html ng-app="proyectophp">

<head>
    <link rel="stylesheet" href="../css/style.css" type="text/css" />
    <link rel="stylesheet" type="text/css" href="../lib/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../lib/jquery-ui/jquery-ui.min.css">
    <link rel="stylesheet" href="../lib/jquery-ui/jquery-ui.theme.min.css" />
    <link rel="stylesheet" href="../lib/alertify/css/alertify.css">
    <link rel="stylesheet" href="../lib/alertify/css/themes/bootstrap.css" />
    <link rel="stylesheet" href="../lib/fontawesome/css/all.css">
    <link href="../lib/dtpickerranger/daterangepicker.css" rel="stylesheet" />
    <link href="../lib/owlcarousel/css/owl.carousel.min.css" rel="stylesheet">
    <link href="../lib/owlcarousel/css/owl.theme.default.min.css" rel="stylesheet">
    <link href="../css/normalize.css" rel="stylesheet">
    <script src="../lib/ckeditor/ckeditor.js"></script>
    <title>SIACEBG</title>
</head>

<body>
    <?php include_once "menu.php"; ?>
    <div id="main" ng-view></div>
    <!-- COMPONENTES EXTERNOS -->
    <!--<script src="../lib/alertify/bootbox.min.js"></script>
    <script src="../lib/alertify/bootbox.locales.min.js"></script>-->
    <script src="../lib/bootstrap/js/popper.min.js"></script>
    <script src="../lib/dtpickerranger/moment.min.js"></script>
    <!--<script src="../lib/dtpickerranger/daterangepicker.js"></script>-->
    <script src="../lib/angular/angular.min.js"></script>
    <script src="../lib/angular-locale_da-dk/angular-locale_da-dk.js"></script>
    <script src="../lib/angular-bootstrap/ui-bootstrap-tpls.min.js"></script>
    <script src="../lib/angular-animate/angular-animate.min.js"></script>
    <script src="../lib/angular-cookies/angular-cookies.min.js"></script>
    <script src="../lib/angular-resource/angular-resource.min.js"></script>
    <script src="../lib/angular-sanitize/angular-sanitize.min.js"></script>
    <script src="../lib/angular-touch/angular-touch.min.js"></script>
    <script src="../lib/angular-route/angular-route.min.js"></script>
    <script src="../lib/angular-ui-mask/mask.js"></script>
    <script src="js/sliderControllers.js"></script>
    <script src="js/contribuyenteControllers.js"></script>
    <script src="js/actividadesControllers.js"></script>
    <script src="js/patentesControllers.js"></script>
    <script src="js/vehiculosControllers.js"></script>
    <script src="js/usuariosControllers.js"></script>
    <script src="js/articulosControllers.js"></script>
    <script src="js/declaracionesControllers.js"></script>
    <script src="js/pagosControllers.js"></script>
    <script src="js/solicitudesControllers.js"></script>
    <script src="js/route.js"></script>
    <script language="JavaScript" src="../lib/alertify/alertify.js"></script>
    <script language="JavaScript" src="../lib/jquery/jquery.min.js"></script>
    <script language="JavaScript" src="../lib/jquery-ui/jquery-ui.min.js"></script>
    <script language="JavaScript" src="../lib/bootstrap/js/bootstrap.min.js"></script>
    <script language="JavaScript" src="../funciones/procedimientos_java.js"></script>
    <script type="text/javascript">
    $.datepicker.regional['es'] = {
        closeText: 'Cerrar',
        prevText: '< Ant',
        nextText: 'Sig >',
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
        yearSuffix: ''
    };
    $.datepicker.setDefaults($.datepicker.regional['es']);
    </script>
</body>

</html>