var app = angular.module('proyectophp');
app.controller("declaracionesController", ["$scope", "$http", function($scope, $http) {
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
    $scope.efectivo_ocultar = false;
    $scope.pagosenviados = [];
    $scope.montopagar = 0;
    $scope.montodiferencia = 0;
    $scope.monto_deuda = 0;
    $scope.mostrar = false;
    $scope.selectedAll = false;
    $scope.loading = true;
    $scope.deshabilitarseleccion = false;
    $scope.formaspago = [];
    $scope.pagosagregados = [];
    $scope.numeropatente = 0;
    $scope.iddeclaracion = 0;
    $scope.bancosorigen = [];
    $scope.declaracionespatente = [];
    $scope.montoacumulado = 0;
    $scope.impuestodeuda = 0;
    $scope.sendpago = {};
    $("#fechapago").datepicker();
    var today = new Date();
    var date = today.getDate();
    var month = today.getMonth() + 1;
    var year = today.getFullYear();
    if (date < 10) date = '0' + date;
    if (month < 10) month = '0' + month;
    var current_date = date + '/' + month + '/' + year;
    $scope.sendpago.fechapago = current_date;
    //LISTAR LOS PAGOS ENVIADOS
    $scope.sumarMonto = function() {
        var sumamonto = 0;
        var max = $scope.pagosagregados.length;
        for (var i = 0; i < max; i++) {
            var iteracion = $scope.pagosagregados[i];
            sumamonto = sumamonto + parseFloat(iteracion.montopago);
        }
        $scope.montoacumulado = sumamonto;
        var id = $scope.iddeclaracion;
        var index = $scope.declaracionespatente.indexOf($scope.declaracionespatente.find(x => x.id == id));
        $scope.impuestodeuda = $scope.declaracionespatente[index].total_impuesto;
        //console.log($scope.impuestodeuda);
    }
    $scope.procesarPago = function() {
        //console.log($scope.pagosagregados);
        $('#btnagregarpago').attr("disabled", true);
        $http.post('scripts/procesar_pago.php', {
            pagos: $scope.pagosagregados
        }).then(function success(e) {
            //console.log(e.data);
            if (e.data.permitido) {
                $scope.pagosagregados = [];
                $scope.declaracionespatente = [];
                $scope.numeropatente = '';
                $scope.iddeclaracion = 0;
                $scope.impuestodeuda = 0;
                $scope.montoacumulado = 0;
                $scope.mostrar = false;
                $scope.efectivo_ocultar = false;
                //console.log($scope.pagosagregados);
                alertify.success("Pago registrado con exito");
            }
            $('#btnagregarpago').attr("disabled", false);
        }, function error(e) {
            console.log("Se ha producido un error al recuperar la información");
        });
    }
    $scope.resetForm = function(form) {
        form.$setPristine();
        form.$setUntouched();
    };
    $scope.validarCarga = function() {
        if ($scope.idpatente > 0) {
            $scope.sendpago.numeropatente = $scope.numeropatente;
            $scope.sendpago.numero_declaracion = $scope.iddeclaracion;
            $scope.deshabilitarseleccion = true;
            $scope.sendpago.montopago = $scope.impuestodeuda - $scope.montoacumulado;
        } else {
            $scope.deshabilitarseleccion = false;
        }
        if ($scope.pagosagregados.length === 0) {
            $scope.declaracionespatente = [];
            $scope.numeropatente = '';
            $scope.iddeclaracion = 0;
            $scope.deshabilitarseleccion = false;
            $scope.efectivo_ocultar = false;
        }
    };
    $scope.asignarIddeclaracion = function(id) {
        $scope.iddeclaracion = id;
        var index = $scope.declaracionespatente.indexOf($scope.declaracionespatente.find(x => x.id == id));
        $scope.impuestodeuda = $scope.declaracionespatente[index].total_impuesto;
        $scope.sendpago.idplanilla = $scope.declaracionespatente[index].id_planilla;
        $scope.sendpago.montopago = $scope.impuestodeuda - $scope.montoacumulado;
        //console.log($scope.impuestodeuda);
    };
    $scope.eliminarPago = function(index) {
        //console.log(index);
        $scope.montoacumulado = $scope.montoacumulado - $scope.pagosagregados[index].montopago;
        $scope.pagosagregados.splice(index);
        //console.log($scope.pagosagregados.length);
        if ($scope.pagosagregados.length === 0) {
            $scope.numeropatente = '';
            $scope.iddeclaracion = 0;
            $scope.impuestodeuda = 0;
            $scope.deshabilitarseleccion = false;
        }
    };
    $scope.validarEfectivo = function(id) {
        //console.log(id);
        if (id == 3) {
            $scope.efectivo_ocultar = true;
            $scope.sendpago.bancoorigen = 3;
            $scope.sendpago.bancodestino = 3;
        } else if (id == 4) {
            $scope.efectivo_ocultar = true;
            $scope.sendpago.bancoorigen = 2;
            $scope.sendpago.bancodestino = 2;
        } else if (id == 5) {
            $scope.efectivo_ocultar = true;
            $scope.sendpago.bancoorigen = 2;
            $scope.sendpago.bancodestino = 2;
        } else {
            $scope.efectivo_ocultar = false;
        }
    };
    $scope.listarPagosEnviados = function(id) {
        $scope.loading = true;
        $http.get('scripts/pagosenviados_listar.php?id=' + id, {}).then(function success(p) {
            $scope.pagosenviados = p.data;
            $scope.loading = false;
            //console.log($scope.pagosenviados);
        }, function error(art) {
            console.log("Se ha producido un error al recuperar la información");
            $scope.loading = false;
        });
    };
    //Collapse Sidebar
    $scope.listadoBancosOrigen = function() {
        $scope.loading = true;
        $http.get('../scripts/bancosorigen_listar.php', {}).then(function success(p) {
            //console.log(p.data);
            $scope.bancosorigen = p.data;
            $scope.loading = false;
        }, function error(art) {
            console.log("Se ha producido un error al recuperar la información");
            $scope.loading = false;
        });
    };
    $scope.listadoBancosOrigen();
    $scope.listarFormaPago = function() {
        $scope.loading = true;
        $http.get('../scripts/formapago_listar.php?accion=0', {}).then(function success(p) {
            //console.log(p.data);
            $scope.formaspago = p.data;
            $scope.loading = false;
        }, function error(art) {
            console.log("Se ha producido un error al recuperar la información");
            $scope.loading = false;
        });
    };
    $scope.listarFormaPago();
    //listar Estado de cuenta
    $scope.listarBancos = function() {
        $scope.loading = true;
        $http.get('../scripts/cuentas_listar.php', {}).then(function success(p) {
            $scope.bancos = p.data;
            $scope.loading = false;
        }, function error(art) {
            console.log("Se ha producido un error al recuperar la información");
            $scope.loading = false;
        });
    };
    $scope.listarBancos();
    $scope.agregarPago = function(form) {
        if (form.$valid) {
            var f1 = new Date(); //31 de diciembre de 2015
            var fecha2 = $scope.sendpago.fechapago.split("/");
            var f2 = new Date(fecha2[2], fecha2[1] - 1, fecha2[0]); //30 de noviembre de 2014
            if (f2 <= f1) {
                $scope.sendpago.usuario = $scope.usuario;
                $scope.pagosagregados.push($scope.sendpago);
                $('#myModalIncluirPago .close').click();
                //$('.modal-backdrop').remove();
                //console.log($scope.pagosagregados);
                $scope.sendpago = {};
                $scope.sumarMonto();
                $scope.resetForm(form);
                $scope.sendpago.fechapago = current_date;
            } else {
                alertify.error('La fecha del pago es incorrecta, verifique')
            }
        } else {
            alertify.error('Datos requeridos vacios, vrifique');
            $scope.resetForm(form);
            if (!$scope.deshabilitarseleccion) {
                $scope.numeropatente = '';
                $scope.impuestodeuda = 0;
            }
        }
    };
    $scope.estadoCuenta = function() {
        $scope.loading = true;
        var id = $scope.idpatente;
        $http.get('scripts/declaraciones_listar.php?id=' + id, {}).then(function success(p) {
            //console.log(p.data);
            $scope.planillas = p.data;
            var sumamonto = 0;
            var max = $scope.planillas.length;
            for (var i = 0; i < max; i++) {
                var iteracion = $scope.planillas[i];
                sumamonto = sumamonto + parseFloat(iteracion.monto);
            }
            $scope.monto_deuda = sumamonto;
            $scope.listarPagosEnviados(id);
            $scope.loading = false;
        }, function error(art) {
            console.log("Se ha producido un error al recuperar la información");
            $scope.loading = false;
        });
    };
    //$scope.estadoCuenta();
    $scope.existe = function(item) {
        return $scope.selected.indexOf(item) > -1;
    };
    $scope.toggleSelection = function(item) {
        //$scope.loading = true;
        var idx = $scope.selected.indexOf(item);
        if (idx > -1) {
            $scope.selected.splice(idx, 1);
            $scope.montopagar = $scope.montopagar - parseFloat(item.monto);
        } else {
            $scope.selected.push(item);
            $scope.montopagar = $scope.montopagar + parseFloat(item.monto);
        }
        $scope.montodiferencia = $scope.monto_deuda - $scope.montopagar;
    };
    $scope.checkAll = function() {
        //alert($scope.selectedAll);
        //$scope.loading = true;
        //$scope.montopagar = 0;
        if ($scope.selectedAll) {
            angular.forEach($scope.planillas, function(item) {
                var idx = $scope.selected.indexOf(item);
                if (idx >= 0) {
                    return true;
                } else {
                    $scope.selected.push(item);
                    $scope.montopagar = $scope.montopagar + parseFloat(item.monto);
                }
            });
        } else {
            $scope.selected = [];
            $scope.montopagar = 0;
        }
        $scope.montodiferencia = $scope.monto_deuda - $scope.montopagar;
        $scope.loading = false;
    };
    //Buscar rif
    $scope.buscarNumeroPatente = function(numero) {
        $scope.loading = true;
        var obj = {
            numero: numero
        };
        $http.post('scripts/patente_buscarnumero.php', {
            patente: obj
        }).then(function success(e) {
            //console.log(e.data);
            $scope.idpatente = e.data.id;
            $scope.numeropatente = numero;
            $scope.listarDeclaraciones($scope.idpatente);
            $scope.loading = false;
        }, function error(e) {
            console.log("Se ha producido un error al recuperar la información");
            $scope.loading = false;
        });
    };
    $scope.listarDeclaraciones = function(id) {
        var obj = {
            id: id
        }
        $scope.loading = true;
        //console.log(id);
        $http.post('../scripts/declaracion_listar.php', {
            declaracion: obj
        }).then(function success(e) {
            //console.log(e.data);
            $scope.declaracionespatente = e.data;
            $scope.loading = false;
        }, function error(e) {
            console.log("Se ha producido un error al recuperar la información");
            $scope.loading = false;
        });
    };
    $scope.confirmarPago = function(id, monto, referencia, fecha, banco) {
        //console.log(id);
        if ($scope.montopagar > 0 && $scope.montopagar == monto) {
            alertify.confirm("¿Estas seguro de confirmar el pago y las planillas seleccionadas?", function(e) {
                if (e) {
                    $scope.loading = true;
                    angular.forEach($scope.planillas, function(item) {
                        var idx = $scope.selected.indexOf(item);
                        if (idx >= 0) {
                            //console.log(item.id);
                            var obj = {
                                usuario: $scope.usuario,
                                referencia: referencia,
                                fecha: fecha,
                                monto_pago: monto,
                                id_planilla: item.id,
                                monto_planilla: item.monto,
                                id_banco: banco,
                                id_pago: id
                            };
                            //console.log(obj);
                            $http.post('scripts/planillas_pagos.php', {
                                registro: obj
                            }).then(function success(e) {
                                //console.log(e.data);
                                $scope.loading = false;
                            }, function error(e) {
                                console.log("Se ha producido un error al recuperar la información");
                                $scope.loading = false;
                            });
                        }
                    });
                    alertify.success('Pago confirmado con éxito');
                    $scope.selectedAll = false;
                    $scope.pago = {};
                    $scope.selected = [];
                    $scope.buscarNumeroPatente();
                    $scope.listarPagosEnviados($scope.idpatente);
                    $scope.montopagar = 0;
                    $scope.monto_deuda = 0;
                }
            });
        } else {
            alertify.error('Monto seleccionado debe ser igual a monto pagado, por favor verifique');
        }
        $scope.montopagar = 0;
        $scope.monto_deuda = 0;
    };
}]);