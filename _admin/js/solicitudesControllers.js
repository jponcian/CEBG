var app = angular.module('proyectophp');
app.controller("solicitudesController", ["$scope", "$http", function($scope, $http) {
    $scope.tipo_acceso = localStorage.getItem('alc_tipoacceso');
    $scope.id_contribuyente = localStorage.getItem('alc_idcontribuyente');
    $scope.usuario = localStorage.getItem('alc_usuario');
    $scope.user = localStorage.getItem('alc_nombre');
    $scope.idsolicitud = 0;
    $scope.solicitudes = [];
    $scope.conceptos = [];
    $scope.partidas = [];
    $scope.detalles = [];
    $scope.deuda = {};
    $('#fecha1').daterangepicker({
        "locale": {
            "format": "DD-MM-YYYY",
            "separator": " - ",
            "applyLabel": "Guardar",
            "cancelLabel": "Cancelar",
            "customRangeLabel": "Personalizar",
            "daysOfWeek": ["Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sa"],
            "monthNames": ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Setiembre", "Octubre", "Noviembre", "Diciembre"],
            "firstDay": 1
        },
        "startDate": moment(),
        "endDate": moment() + 1,
        "opens": "center"
    });
    $('#fecha1').on('apply.daterangepicker', function(ev, picker) {
        $scope.deuda.fecha_inicio = picker.startDate.format('DD-MM-YYYY');
        $scope.deuda.fecha_fin = picker.endDate.format('DD-MM-YYYY');
    });
    $scope.listarSolicitudes = function() {
        $scope.loading = true;
        $http.post('scripts/solicitudes_listar.php', {}).then(function success(e) {
            //console.log(e.data);
            $scope.solicitudes = e.data.resultado;
            $scope.loading = false;
        }, function error(e) {
            console.log("Se ha producido un error al recuperar la información");
            $scope.loading = false;
        });
    };
    $scope.listarSolicitudes();
    $scope.cargarConceptos = function() {
        $scope.deuda = {};
        $scope.detalles = [];
        $scope.loading = true;
        $http.post('scripts/solicitudes_conceptos.php', {}).then(function success(e) {
            //console.log(e.data);
            $scope.conceptos = e.data.resultado;
            $scope.loading = false;
        }, function error(e) {
            console.log("Se ha producido un error al recuperar la información");
            $scope.loading = false;
        });
    };
    $scope.llenarPartida = function(id) {
        //console.log(id);
        var obj = {
            id: id
        };
        $scope.loading = true;
        $http.post('scripts/solicitudes_partidas.php', {
            partida: obj
        }).then(function success(e) {
            //console.log(e.data);
            $scope.partidas = e.data.resultado;
            $scope.loading = false;
        }, function error(e) {
            console.log("Se ha producido un error al recuperar la información");
            $scope.loading = false;
        });
    };
    $scope.agregarDetalle = function(form) {
        if (form.$valid) {
            var cadena = $scope.deuda.periodo.split(' - ');
            $scope.deuda.fecha_inicio = cadena[0];
            $scope.deuda.fecha_fin = cadena[1];
            $scope.deuda.usuario = $scope.usuario;
            $scope.deuda.id_solicitud = $scope.idsolicitud;
            $scope.deuda.id_contribuyente = $scope.id_contribuyente;
            $scope.deuda.id_patente = $scope.id_patente;
            var id = $scope.deuda.concepto;
            var index = $scope.conceptos.indexOf($scope.conceptos.find(x => x.id == id));
            var concepto = $scope.conceptos[index].descripcion;
            //console.log($scope.deuda);
            $scope.deuda.textoconcepto = concepto;
            $scope.detalles.push($scope.deuda);
            $scope.deuda = {};
            form.$setPristine();
            form.$setUntouched();
        } else {
            alertify.error("Campos vacios, verifique y vuelva a intentarlo");
        }
    };
    $scope.procesarSolicitud = function(id, id_contribuyente, id_patente) {
        $scope.idsolicitud = id;
        $scope.id_contribuyente = id_contribuyente;
        $scope.id_patente = id_patente;
        bootbox.dialog({
            title: 'SIACEBG - Alcaldía Francisco de Miranda',
            message: '¿El contribuyente posee DEUDAS con la institución?',
            size: 'large',
            onEscape: true,
            backdrop: true,
            buttons: {
                Si: {
                    label: 'Sí',
                    className: 'btn-success',
                    callback: function() {
                        //alert('Cargamos las multas');
                        $scope.cargarConceptos();
                        //$('#modalCargarDeudas').modal('show');
                        $('#modalCargarDeudas').modal({
                            backdrop: 'static',
                            keyboard: false,
                            show: true
                        });
                    }
                },
                No: {
                    label: 'No',
                    className: 'btn-outline-dark',
                    callback: function() {
                        bootbox.dialog({
                            title: 'SIACEBG - Alcaldía Francisco de Miranda',
                            message: '¿Está seguro de aprobar la emisión de la solvencia?',
                            size: 'large',
                            onEscape: true,
                            backdrop: true,
                            buttons: {
                                Aprobar: {
                                    label: 'Si',
                                    className: 'btn-success',
                                    callback: function() {
                                        //alertify.success('Este es el id: ' + id);
                                        $scope.loading = true;
                                        var obj = {
                                            id: id,
                                            usuario: $scope.usuario
                                        };
                                        $http.post('scripts/solicitudes_cerrar.php', {
                                            solicitud: obj
                                        }).then(function success(e) {
                                            //console.log(e.data);
                                            $scope.listarSolicitudes();
                                            alertify.success(e.data.resultado.mensaje);
                                            $scope.loading = false;
                                        }, function error(e) {
                                            console.log("Se ha producido un error al recuperar la información");
                                            $scope.loading = false;
                                        });
                                    }
                                },
                                Negar: {
                                    label: 'No',
                                    className: 'btn-danger',
                                    callback: function() {
                                        //alert('Negamos y salimos');
                                    }
                                }
                            }
                        });
                    }
                }
            }
        });
    };
    $scope.eliminarDetalle = function(idx) {
        $scope.detalles.splice(idx, 1);
    };
    $scope.agregarPlanilla = function(form) {
        if ($scope.detalles.length > 0) {
            $('#agregarplanilla').hide();
            //console.log($scope.detalles);
            $http.post('scripts/solicitudes_agregarplanilla.php', {
                solvencia: $scope.detalles
            }).then(function success(e) {
                $("#modalCargarDeudas .close").click();
                //$('.modal-backdrop').remove();
                $scope.listarSolicitudes();
                alertify.success(e.data.resultado.mensaje);
                $scope.loading = false;
                $('#agregarplanilla').show();
            }, function error(e) {
                console.log("Se ha producido un error al recuperar la información");
                $scope.loading = false;
                $('#agregarplanilla').show();
            });
        } else {
            alertify.error("No existen detalles para agregar");
        }
    };
}]);