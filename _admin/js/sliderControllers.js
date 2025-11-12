var app = angular.module('proyectophp', ['ngRoute', 'ui.mask', 'ngSanitize']);
app.directive("fileInput", function($parse) {
    return {
        link: function($scope, element, attrs) {
            element.on("change", function(event) {
                var files = event.target.files;
                $parse(attrs.fileInput).assign($scope, element[0].files);
                $scope.$apply();
            });
        }
    }
});
app.directive('mayusculastodo', function() {
    return {
        require: 'ngModel',
        link: function(scope, element, attrs, modelCtrl) {
            var capitalize = function(inputValue) {
                if (inputValue == undefined) inputValue = '';
                var capitalized = inputValue.toUpperCase();
                if (capitalized !== inputValue) {
                    modelCtrl.$setViewValue(capitalized);
                    modelCtrl.$render();
                }
                return capitalized;
            }
            modelCtrl.$parsers.push(capitalize);
            capitalize(scope[attrs.ngModel]); // capitalize initial value
        }
    };
});
app.directive('decimal', function() {
    return {
        require: 'ngModel',
        restrict: 'A',
        link: function(scope, element, attr, ctrl) {
            function inputValue(val) {
                if (val) {
                    var digits = val.replace(/[^0-9.]/g, '');
                    if (digits.split('.').length > 2) {
                        digits = digits.substring(0, digits.length - 1);
                    }
                    if (digits !== val) {
                        ctrl.$setViewValue(digits);
                        ctrl.$render();
                    }
                    return parseFloat(digits);
                }
                return "";
            }
            ctrl.$parsers.push(inputValue);
        }
    };
});
app.directive('entero', function() {
    return {
        require: 'ngModel',
        restrict: 'A',
        link: function(scope, element, attr, ctrl) {
            function inputValue(val) {
                if (val) {
                    var value = val + ''; //convert to string
                    var digits = value.replace(/[^0-9]/g, '');
                    if (digits !== value) {
                        ctrl.$setViewValue(digits);
                        ctrl.$render();
                    }
                    return parseInt(digits);
                }
                return "";
            }
            ctrl.$parsers.push(inputValue);
        }
    };
});
app.controller("sliderController", ["$scope", "$http", function($scope, $http) {
    $scope.nombre = 'slide';
    $scope.imagen = '';
    $scope.indice = '';
    $scope.sliders = [];
    $scope.ahora = new Date()
    $scope.tipo_acceso = localStorage.getItem('alc_tipoacceso');
    $scope.id_contribuyente = localStorage.getItem('alc_idcontribuyente');
    $scope.usuario = localStorage.getItem('alc_usuario');
    $scope.user = localStorage.getItem('alc_nombre');
    $scope.nombreusuario = localStorage.getItem('alc_nomusuario');
    $scope.loading = true;
    const $archivos = document.querySelector("#inputFileImagen");
    $.datepicker.regional['es'] = {
        closeText: 'Cerrar',
        prevText: '< Ant',
        nextText: 'Sig >',
        currentText: 'Hoy',
        monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
        monthNamesShort: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
        dayNames: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
        dayNamesShort: ['Dom', 'Lun', 'Mar', 'Mié', 'Juv', 'Vie', 'Sáb'],
        dayNamesMin: ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sá'],
        weekHeader: 'Sm',
        dateFormat: 'dd/mm/yy',
        firstDay: 1,
        isRTL: false,
        showMonthAfterYear: false,
        yearSuffix: ''
    };
    $.datepicker.setDefaults($.datepicker.regional['es']);
    $scope.salir = function() {
        localStorage.removeItem('alc_tipoacceso');
        localStorage.removeItem('alc_idcontribuyente');
        localStorage.removeItem('alc_usuario');
        localStorage.removeItem('alc_nombre');
        localStorage.removeItem('alc_nomusuario');
        window.close();
    };
    $scope.validarLogin = function() {
        if ($scope.tipo_acceso === null && $scope.id_cliente === null) {
            $location.url("../SIACEBG/");
        }
    };
    $scope.uploadFile = function() {
        $scope.loading = true;
        $('#agregarslider').attr("disabled", true);
        var form_data = new FormData();
        angular.forEach($scope.files, function(file) {
            form_data.append('file', file);
        });
        //console.log($scope.files);
        $http.post('scripts/slider_agregar.php', form_data, {
            transformRequest: angular.identity,
            headers: {
                'Content-Type': undefined,
                'Process-Data': false
            }
        }).then(function success(response) {
            $('#ModalAddSlider').modal('hide');
            alertify.success(response.data.slider.mensaje);
            $scope.listarSliders();
        });
        $scope.loading = false;
        $('#agregarslider').attr("disabled", false);
    };
    $scope.uploadFileEditar = function() {
        $scope.loading = true;
        $('#modificarslide').attr("disabled", true);
        var form_data = new FormData();
        angular.forEach($scope.files, function(file) {
            form_data.append('file', file);
        });
        //console.log($scope.files);
        var id = $scope.sliders[$scope.indice].id;
        var imagen = $scope.imagen;
        $http.post('scripts/slider_editar.php?id=' + id + '&img=' + imagen, form_data, {
            transformRequest: angular.identity,
            headers: {
                'Content-Type': undefined,
                'Process-Data': false
            }
        }).then(function success(response) {
            $('#ModalEditSlider').modal('hide');
            //console.log(response.data);
            alertify.success(response.data.slider.mensaje);
            $scope.listarSliders();
        });
        $('#modificarslide').attr("disabled", false);
        $scope.loading = false;
    };
    //Cargar el Indice
    $scope.cargarEditarSlider = function(index) {
        $scope.loading = true;
        $scope.indice = index;
        var id = $scope.sliders[index].id;
        $http.get('scripts/slider_buscar.php?id=' + id, {}).then(function success(e) {
            $scope.imagen = e.data[0].ruta;
            //$scope.listarSliders();
            $scope.loading = false;
        }, function error(e) {
            console.log("Se ha producido un error al recuperar la información");
            $scope.loading = false;
        });
    };
    //listar Articulos
    $scope.listarSliders = function() {
        $scope.loading = true;
        $http.get('scripts/slider_listar.php', {}).then(function success(response) {
            $scope.sliders = response.data.slides;
            $scope.loading = false;
        }, function error(response) {
            console.log("Se ha producido un error al recuperar la información");
            $scope.loading = false;
        });
    };
    $scope.listarSliders();
    //BORRAR SLIDER
    $scope.eliminarSlider = function(index) {
        alertify.confirm("¿Estas seguro de elimnar el registro?", function(e) {
            if (e) {
                $scope.loading = true;
                var id = $scope.sliders[index].id;
                //console.log('Id: ' + id);
                $http.get('scripts/slider_eliminar.php?id=' + id, {}).then(function success(e) {
                    //console.log(e.data);
                    $scope.listarSliders();
                    $scope.loading = false;
                }, function error(e) {
                    console.log("Se ha producido un error al recuperar la información");
                    $scope.loading = false;
                });
            }
        });
    };
}]);