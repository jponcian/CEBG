var app = angular.module('proyectophp');
app.config(['$locationProvider',
    function($locationProvider) {
        $locationProvider.hashPrefix('');
        $locationProvider.html5Mode({
            enabled: false,
            requireBase: true
        });
    }
]);
app.config(['$routeProvider', function($routeProvider) {
    $routeProvider.when('/', {
        templateUrl: "templates/inicio.php",
        controller: "sliderController"
    }).when('/slides', {
        templateUrl: "templates/slides.php",
        controller: "sliderController"
    }).when('/articulos', {
        templateUrl: "templates/articulos.php",
        controller: "articulosController"
    }).when('/actividades', {
        templateUrl: "templates/actividades.php",
        controller: "actividadesController"
    }).when('/contribuyentes', {
        templateUrl: "templates/contribuyentes.php",
        controller: "contribuyenteController"
    }).when('/patentes', {
        templateUrl: "templates/patentes.php",
        controller: "patentesController"
    }).when('/vehiculos', {
        templateUrl: "templates/vehiculos.php",
        controller: "vehiculosController"
    }).when('/declaraciones', {
        templateUrl: "templates/declaraciones.php",
        controller: "declaracionesController"
    }).when('/pagos', {
        templateUrl: "templates/pagos.php",
        controller: "pagosController"
    }).when('/listapagos', {
        templateUrl: "templates/listapagos.php",
        controller: "pagosController"
    }).when('/usuarios', {
        templateUrl: "templates/usuarios.php",
        controller: "usuariosController"
    }).when('/dectesoreria', {
        templateUrl: "templates/dectesoreria.php",
        controller: "pagosController"
    }).when('/reportes', {
        templateUrl: "templates/reportes.php",
        controller: "pagosController"
    }).when('/listado', {
        templateUrl: "templates/listadeclaraciones.php",
        controller: "pagosController"
    }).when('/reportediario', {
        templateUrl: "templates/reportediario.php",
        controller: "pagosController"
    }).when('/estadocuenta', {
        templateUrl: "templates/estadocuenta.php",
        controller: "pagosController"
    }).when('/solicitudes', {
        templateUrl: "templates/solicitudes.php",
        controller: "solicitudesController"
    }).otherwise({
        redirectTo: '/'
    });
}]);