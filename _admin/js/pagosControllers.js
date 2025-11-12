var app = angular.module('proyectophp');
app.controller("pagosController", ["$scope", "$http", function($scope, $http) {
    $scope.indice = '';
    $scope.planillas = [];
    $scope.pago = {};
    $scope.numpatente = '';
    $scope.selected = [];
    $scope.tipo_acceso = localStorage.getItem('alc_tipoacceso');
    $scope.id_contribuyente = localStorage.getItem('alc_idcontribuyente');
    $scope.usuario = localStorage.getItem('alc_usuario');
    $scope.user = localStorage.getItem('alc_nombre');
    $scope.bancos = [];
    $scope.pagosenviados = [];
    $scope.listarpagos = [];
    $scope.montopagar = 0;
    $scope.buscar_rif = '';
    $scope.montodiferencia = 0;
    $scope.monto_deuda = 0;
    $scope.mostrar = false;
    $scope.selectedAll = false;
    $scope.loading = true;
    $scope.idcliente = 0;
    $scope.contribuyente = {};
    $scope.declaraciones = [];
    $scope.estadocuentas = [];
    $scope.iniciobuscar = false;
    $scope.filtrar = '1';
    var f = new Date();
    $scope.CurrentDate = mipadLeft(f.getDate(), 2) + "/" + mipadLeft(f.getMonth() + 1, 2) + "/" + f.getFullYear();
    $scope.busqueda = $scope.CurrentDate;
    $scope.procesardeclaracion = true;
    $("#fechapago").datepicker();
    $("#fecha").datepicker();
    $("#fechaf").datepicker();
    $("#fechabuscar").datepicker();
    //LISTAR LOS PAGOS ENVIADOS
    function mipadLeft(number, width) {
        var numberOutput = Math.abs(number); /* Valor absoluto del número */
        var length = number.toString().length; /* Largo del número */
        var zero = "0"; /* String de cero */
        if (width <= length) {
            if (number < 0) {
                return ("-" + numberOutput.toString());
            } else {
                return numberOutput.toString();
            }
        } else {
            if (number < 0) {
                return ("-" + (zero.repeat(width - length)) + numberOutput.toString());
            } else {
                return ((zero.repeat(width - length)) + numberOutput.toString());
            }
        }
    };
    $scope.listarEstadoCuenta = function(dato, filtro) {
        $scope.iniciobuscar = false;
        $scope.loading = true;
        var obj = {
            dato: dato,
            filtro: filtro
        };
        $http.post('scripts/estadocuenta_filtrar.php', {
            buscar: obj
        }).then(function success(e) {
            //console.log(e.data);
            $scope.estadocuentas = e.data.resultado;
            $scope.loading = false;
            $scope.iniciobuscar = true;
        }, function error(e) {
            console.log("Se ha producido un error al recuperar la información");
            $scope.loading = false;
        });
    };
    $scope.listarDecalarciones = function(fecha) {
        $scope.iniciobuscar = false;
        $scope.loading = true;
        var obj = {
            fecha: fecha
        };
        $http.post('scripts/listado_declaraciones.php', {
            registro: obj
        }).then(function success(dec) {
            $scope.declaraciones = dec.data;
            //console.log($scope.declaraciones);
            $scope.loading = false;
            $scope.iniciobuscar = true;
        }, function error(art) {
            console.log("Se ha producido un error al recuperar la información");
            $scope.loading = false;
        });
    };
    $scope.procesarDeclaracion = function() {
        window.open('../declaracion.php', '_blank');
    };
    $scope.buscarRif = function() {
        $scope.idcliente = 0;
        //console.log($scope.idcliente);
        $scope.loading = true;
        var rif = $scope.buscar_rif;
        $http.get('scripts/declaracion_buscarrif.php?rif=' + rif, {}).then(function success(e) {
            $scope.contribuyente = e.data;
            //console.log($scope.contribuyente);
            $scope.idcliente = $scope.contribuyente.id;
            localStorage.setItem('alc_idcontribuyente', $scope.contribuyente.id);
            if ($scope.idcliente > 0) {
                $scope.procesardeclaracion = false;
            } else {
                $scope.procesardeclaracion = true;
            }
            $scope.loading = false;
        }, function error(e) {
            console.log("Se ha producido un error al recuperar la información");
            $scope.loading = false;
        });
    };
    $scope.listarPagosEnviados = function() {
        $scope.loading = true;
        $http.get('scripts/pagosenviados_listarall.php', {}).then(function success(p) {
            //console.log(p.data);
            $scope.pagosenviados = p.data;
            $scope.loading = false;
            //console.log($scope.pagosenviados);
        }, function error(art) {
            console.log("Se ha producido un error al recuperar la información");
            $scope.loading = false;
        });
    };
    $scope.listarPagos = function(numero) {
        $scope.loading = true;
        var obj = {
            numero: numero
        };
        $http.post('scripts/pagos_listarall.php', {
            pagos: obj
        }).then(function success(p) {
            console.log(p.data);
            $scope.listarpagos = p.data;
            $scope.loading = false;
            //console.log($scope.pagosenviados);
        }, function error(art) {
            console.log("Se ha producido un error al recuperar la información");
            $scope.loading = false;
        });
    };
    $scope.listarPagosEnviados();
    $scope.imprimir = function() {
        var w = window.open();
        w.document.write($('#tablapagos').html());
        w.print();
        w.close();
    };
    $scope.actualizarPago = function(id_declaracion, origen, id_planilla, id, accion, numero, referencia, bancodestino, monto_pagado) {
        console.log(id + ' - ' + accion);
        var mensaje = '';
        if (accion == 0) {
            mensaje = 'confirmar';
        } else {
            mensaje = 'rechazar';
        }
        alertify.confirm("¿Estas seguro de " + mensaje + " el pago de la Patente " + numero + " mediante transferencia " + referencia + " al " + bancodestino + " por BsS. " + monto_pagado + "?", function(e) {
            var obj = {
                id_declaracion: id_declaracion,
                origen: origen,
                id_planilla: id_planilla,
                id: id,
                accion: accion,
                usuario: $scope.usuario
            };
            //console.log(obj);
            if (e) {
                $scope.loading = true;
                //console.log(accion);
                $http.post('scripts/actualizar_pago.php', {
                    pago: obj
                }).then(function success(e) {
                    //console.log(e.data);
                    $scope.listarPagosEnviados();
                    if (accion == 0) {
                        alertify.success('Pago confirmado con éxito');
                    } else {
                        alertify.error('Pago rechazado con éxito');
                    }
                    $scope.loading = false;
                }, function error(e) {
                    console.log("Se ha producido un error al recuperar la información");
                    $scope.loading = false;
                });
            }
        });
    };
}]);