<?php
setlocale(LC_ALL, 'sp_ES', 'sp', 'es');
date_default_timezone_set('America/Caracas');
?>
<!DOCTYPE html>
<html ng-app="proyectophp">

<head>
    <meta charset="utf-8">
    <meta content="IE=edge" http-equiv="X-UA-Compatible">
    <title>SIACEBG</title>
    <!--<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">-->
    <link href="../lib/jquery-ui/jquery-ui.min.css" rel="stylesheet">
    <link href="../lib/jquery-ui/jquery-ui.theme.min.css" rel="stylesheet">
    <link href="../lib/alertify/css/alertify.min.css" rel="stylesheet" />
    <link href="../lib/alertify/css/themes/default.css" rel="stylesheet" />
    <link href="../lib/alertify/css/themes/bootstrap.css" rel="stylesheet" />
    <link href="../lib/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="../lib/dtpickerranger/daterangepicker.css" rel="stylesheet" />
    <!--<link href="../lib/mdb/css/mdb.min.css" rel="stylesheet">-->
    <link href="../lib/owlcarousel/css/owl.carousel.min.css" rel="stylesheet">
    <link href="../lib/owlcarousel/css/owl.theme.default.min.css" rel="stylesheet">
    <link href="../lib/fontawesome/css/all.css" rel="stylesheet">
    <link href="../css/normalize.css" rel="stylesheet">
    <link href="../css/style.css" rel="stylesheet">
    <link href="../css/styleside.css" rel="stylesheet">
    <link href="../css/sidebar_style.css" rel="stylesheet">
    <script src="../lib/ckeditor/ckeditor.js"></script>
</head>

<body ng-controller="sliderController" ng-class="loading ? 'hiddenloading' : ''" ng-init="validarLogin()">
    <nav id="barraPrincipal" class="navbar navbar-dark bg-fondo fixed-top fixed-top" ng-show="tipo_acceso !== null || tipo_acceso !== null">
        <button class="openbtn navbar-brand bg-fondo" onclick="openNav()">&#9776;</button>
        <span class="navbar-brand mr-auto"><strong>Alcaldia del Municipio Francisco de Miranda</strong></span>
        <span class="navbar-text text-white">
            Bienvenido <span class="font-weight-bold">{{nombreusuario | uppercase}}</span> Conectado
            <?php echo date("d-m-Y g:i a"); ?>
        </span>
        <button type="button" class="btn btn-outline-light btn-sm ml-5 mr-5 font-weight-bold" ng-click="salir()"><i class="fas fa-door-open"></i></button>
    </nav>
    <div id="mySidebar" class="sidebar" ng-show="tipo_acceso !== null || tipo_acceso !== null">
        <!--<a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>-->
        <a class="nav-link active" href="#/reportes" ng-show="tipo_acceso == 4 || tipo_acceso == 5 || tipo_acceso == 6 || tipo_acceso == 99">
            <div class="col-md-12 text-center">
                <spam class="icono"><i class="fas fa-print"></i></spam>
            </div>
            <div class="col-md-12 text-center">
                <spam>Reporte Recaudación</spam>
            </div>
        </a>
        <a class="nav-link active" href="#/reportediario" ng-show="tipo_acceso == 5 || tipo_acceso == 6 || tipo_acceso == 99">
            <div class="col-md-12 text-center">
                <spam class="icono"><i class="fas fa-print"></i></spam>
            </div>
            <div class="col-md-12 text-center">
                <spam>Reporte Conciliación</spam>
            </div>
        </a>
        <a class="nav-link active" href="#/solicitudes" ng-show="tipo_acceso == 3 || tipo_acceso == 5 || tipo_acceso == 6 || tipo_acceso == 99">
            <div class="col-md-12 text-center">
                <spam class="icono"><i class="fas fa-border-style"></i></spam>
            </div>
            <div class="col-md-12 text-center">
                <spam>Solicitudes Recibidas</spam>
            </div>
        </a> <a class="nav-link active" href="#/listado" ng-show="tipo_acceso == 5 || tipo_acceso == 6 || tipo_acceso == 99">
            <div class="col-md-12 text-center">
                <spam class="icono"><i class="far fa-list-alt"></i></spam>
            </div>
            <div class="col-md-12 text-center">
                <spam>Listado de Declaración</spam>
            </div>
        </a>
        <a class="nav-link active" href="#/slides" ng-show="tipo_acceso == 2 || tipo_acceso == 5 || tipo_acceso == 99">
            <div class="col-md-12 text-center">
                <spam class="icono"><i class="far fa-window-restore"></i></spam>
            </div>
            <div class="col-md-12 text-center">
                <spam>Sliders</spam>
            </div>
        </a>
        <a class="nav-link" href="#/articulos" ng-show="tipo_acceso == 2 || tipo_acceso == 5 || tipo_acceso == 99">
            <div class="col-md-12 text-center">
                <spam class="icono"><i class="fas fa-newspaper"></i></spam>
            </div>
            <div class="col-md-12 text-center">
                <spam>Articulos</spam>
            </div>
        </a>
        <a class="nav-link" href="#/estadocuenta" ng-show="tipo_acceso == 3 || tipo_acceso == 5 || tipo_acceso == 6 || tipo_acceso == 99">
            <div class="col-md-12 text-center">
                <spam class="icono"><i class="fas fa-chart-line"></i></spam>
            </div>
            <div class="col-md-12 text-center">
                <spam>Consultar Estado de Cuenta</spam>
            </div>
        </a>
        <a class="nav-link" href="#/contribuyentes" ng-show="tipo_acceso == 3 || tipo_acceso == 5 || tipo_acceso == 6 || tipo_acceso == 99">
            <div class="col-md-12 text-center">
                <spam class="icono"><i class="fas fa-business-time"></i></spam>
            </div>
            <div class="col-md-12 text-center">
                <spam>Contribuyentes</spam>
            </div>
        </a>
        <a class="nav-link" href="#/actividades" ng-show="tipo_acceso == 5 || tipo_acceso == 99">
            <div class="col-md-12 text-center">
                <spam class="icono"><i class="fas fa-money-check"></i></spam>
            </div>
            <div class="col-md-12 text-center">
                <spam>Actividades</spam>
            </div>
        </a>
        <a class="nav-link" href="#/patentes" ng-show="tipo_acceso == 3 || tipo_acceso == 5 || tipo_acceso == 6 || tipo_acceso == 99">
            <div class="col-md-12 text-center">
                <spam class="icono"><i class="far fa-id-card"></i></spam>
            </div>
            <div class="col-md-12 text-center">
                <spam>Patentes</spam>
            </div>
        </a>
        <a class="nav-link" href="#/declaraciones" ng-show="tipo_acceso == 4 || tipo_acceso == 99">
            <div class="col-md-12 text-center">
                <spam class="icono"><i class="fas fa-coins"></i></spam>
            </div>
            <div class="col-md-12 text-center">
                <spam>Gestión de Pagos</spam>
            </div>
        </a>
        <a class="nav-link" href="#/pagos" ng-show="tipo_acceso == 4 || tipo_acceso == 99">
            <div class="col-md-12 text-center">
                <spam class="icono"><i class="fas fa-hand-holding-usd"></i></i></spam>
            </div>
            <div class="col-md-12 text-center">
                <spam>Pagos Recibidos</spam>
            </div>
        </a>
        <a class="nav-link" href="#/listapagos" ng-show="tipo_acceso == 4 || tipo_acceso == 99">
            <div class="col-md-12 text-center">
                <spam class="icono"><i class="fas fa-list-ul"></i></spam>
            </div>
            <div class="col-md-12 text-center">
                <spam>Relacion de Pagos</spam>
            </div>
        </a>
        <a class="nav-link" href="#/usuarios" ng-show="tipo_acceso == 5 || tipo_acceso == 6 || tipo_acceso == 99">
            <div class="col-md-12 text-center">
                <spam class="icono"><i class="fas fa-users"></i></spam>
            </div>
            <div class="col-md-12 text-center">
                <spam>Usuarios</spam>
            </div>
        </a>
        <a class="nav-link" href="#/dectesoreria" ng-show="tipo_acceso == 3 || tipo_acceso == 5 || tipo_acceso == 6 || tipo_acceso == 99">
            <div class="col-md-12 text-center">
                <spam class="icono"><i class="fas fa-paste"></i></spam>
            </div>
            <div class="col-md-12 text-center">
                <spam>Generar Declaración</spam>
            </div>
        </a>
    </div>
    <div id="main" ng-view>
        <p>Content...</p>
    </div>
    <script>
    /* Set the width of the sidebar to 250px and the left margin of the page content to 250px */
    function openNav() {
        if (document.getElementById("mySidebar").clientWidth == 150) {
            document.getElementById("mySidebar").style.width = "0";
            document.getElementById("main").style.marginLeft = "0";
        } else {
            document.getElementById("mySidebar").style.width = "150px";
            document.getElementById("main").style.marginLeft = "150px";
        }
    }
    </script>
    <!-- COMPONENTES EXTERNOS -->
    <!--<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>-->
    <script src="../lib/jquery/jquery-3.4.1.min.js"></script>
    <script src="../lib/jquery-ui/jquery-ui.min.js"></script>
    <script src="../lib/alertify/alertify.js"></script><!---->
    <!--<script src="../lib/alertify/bootbox.min.js"></script>
    <script src="../lib/alertify/bootbox.locales.min.js"></script>-->
    <script src="../lib/bootstrap/js/popper.min.js"></script>
    <script src="../lib/bootstrap/js/bootstrap.min.js"></script>
    <script src="../lib/dtpickerranger/moment.min.js"></script>
    <!--<script src="../lib/dtpickerranger/daterangepicker.js"></script>-->
    <!--<script src="../lib/mdb/js/mdb.min.js"></script>-->
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
    <script src="js/usuariosControllers.js"></script>
    <script src="js/articulosControllers.js"></script>
    <script src="js/declaracionesControllers.js"></script>
    <script src="js/pagosControllers.js"></script>
    <script src="js/solicitudesControllers.js"></script>
    <script src="js/route.js"></script>
    <!--<script src="js/jq_funciones.js"></script>-->
</body>

</html>