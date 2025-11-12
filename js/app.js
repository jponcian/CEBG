var app = angular.module('proyectophp', ['ngRoute', 'ui.mask', 'ngSanitize']);
app.factory("serviceLogin", function() {
    return {
        usuario: {}
    }
});
app.controller('MainController', ['$scope', '$http', function($scope, $http, serviceLogin) {
    //console.log('test');
    $scope.loading = true;
    $scope.slide_images = [];
    $scope.articulos = [];
    $scope.articulo = {};
    $scope.errors = [];
    $scope.CurrentDate = new Date();
    $scope.usuarios = [];
    $scope.usuario = {};
    $scope.logueado = {};
    $scope.empresa = {};
    //$scope.rif = serviceLogin;
    window.addEventListener("load", function(event) {
        $scope.loading = true;
    });
    $scope.datosEmpresa = function() {
        $scope.loading = true;
        $http.get('scripts/datos_sistema.php', {}).then(function success(e) {
            $scope.empresa = e.data;
            //console.log(e.data);
            $scope.loading = false;
        }, function error(e) {
            console.log("Se ha producido un error al recuperar la información");
            $scope.loading = false;
        });
    };
//    $scope.listSlideImages = function() {
//        $scope.loading = true;
//        $http.get('scripts/listar_carousel.php', {}).then(function success(e) {
//            $scope.slide_images = e.data.slide_images;
//            $scope.loading = false;
//        }, function error(e) {
//            console.log("Se ha producido un error al recuperar la información");
//            $scope.loading = false;
//        });
//    };
   // $scope.listSlideImages();
    //listar Articulos
    $scope.listArticulos = function() {
        $scope.loading = true;
        $http.get('scripts/listar_art.php', {}).then(function success(art) {
            $scope.articulos = art.data.articulos;
            $scope.loading = false;
        }, function error(art) {
            console.log("Se ha producido un error al recuperar la información");
            $scope.loading = false;
        });
    };
   // $scope.listArticulos();
    //listar slide_imagenes
    $scope.loginUsuario = function() {
        $scope.loading = true;
        $http.post('scripts/listar_login.php', {
            producto: $scope.usuario
        }).then(function success(e) {
            //console.log(e.data);
            $scope.usuarios = e.data;
            //localStorage.clear();
            localStorage.removeItem('alc_tipoacceso');
            localStorage.removeItem('alc_idcontribuyente');
            localStorage.removeItem('alc_usuario');
            localStorage.removeItem('alc_nombre');
            localStorage.removeItem('alc_nomusuario');
            localStorage.setItem('alc_tipoacceso', e.data.tipo_acceso);
            localStorage.setItem('alc_idcontribuyente', e.data.id_contribuyente);
            localStorage.setItem('alc_usuario', e.data.usuario);
            localStorage.setItem('alc_nombre', e.data.user);
            localStorage.setItem('alc_nomusuario', e.data.nombre_usuario);
            if ($scope.usuarios.tipo_acceso > 0) {
                $scope.logueado.mostrar = 1;
                $scope.logueado.mensajelogin = '';
                if ($scope.usuarios.usuario == 'V020034218' || $scope.usuarios.usuario == 'J412043978') {
                    window.open('mantenimiento.php', '_blank');  $scope.logueado.mostrar = 0;
                } else
					{
//						if ($scope.usuarios.tipo_acceso == 1) 
//							{   window.open('declaracion.php', '_blank'); }
//						else 
//							{
//								if ($scope.usuarios.tipo_acceso == 999) 
//								{ 	 window.open('mantenimiento.php', '_blank');  $scope.logueado.mostrar = 0;	}
//								else
//								{ 	
//								if ($scope.usuarios.tipo_acceso == 10 || $scope.usuarios.tipo_acceso == 11 || $scope.usuarios.tipo_acceso == 17) 
//									{ 	window.open('principal_alcaldia.php', '_blank')	}
//								else
									 	window.open('principal.php', '_blank')	
//								}
//							}
					}
                $("#myModal .close").click();
                $scope.usuario = {};
            } else {
                $scope.logueado.mostrar = 0;
                $scope.logueado.mensajelogin = 'Usuario no registrado o contraseña incorrecta';
            }
            $scope.loading = false;
        }, function error(e) {
            console.log("Se ha producido un error al recuperar la información");
            $scope.loading = false;
        });
    };
    $scope.ocultarModalRegistro = function() {
        location.href = '#/registro';
        $("#myModal .close").click();
    };
    $scope.verArticulo = function(id) {
        $scope.loading = true;
        $http.get('scripts/articulo_listar.php?id=' + id, {}).then(function success(art) {
            $scope.articulo = art.data;
            //console.log($scope.articulo);
            $scope.loading = false;
        }, function error(art) {
            console.log("Se ha producido un error al recuperar la información");
            $scope.loading = false;
        });
    }
}]);