var app = angular.module('proyectophp');
app.controller("vehiculosController", ["$scope", "$http", function($scope, $http) {
    $scope.indice = '';
    $scope.vehiculo = {};
    $scope.vehiculos = [];
    $scope.numero_existe = false;
    $scope.editid = 0;
    $scope.editnumero = '';
    $scope.editrif = '';
    $scope.editrifbase = '';
    $scope.editmarca = '';
    $scope.editmodelo = '';
    $scope.editanno = '';
    $scope.editcolor = '';
    $scope.idcliente = 0;
    $scope.idclientebase = 0;
    //$scope.loading = true;
    $scope.filtrar = '1';
    $scope.vehiculobuscar = [];
    $scope.iniciobuscar = false;
    $scope.usuario = localStorage.getItem('alc_usuario');
    $scope.buscarVehiculo = function(dato, filtro) {
        //console.log(dato + ' - 0000 - ' + filtro);
        $scope.iniciobuscar = false;
        $scope.loading = true;
        var obj = {
            dato: dato,
            filtro: filtro
        };
        //console.log(obj);
        $http.post('scripts/vehiculos_filtrar.php', {
            buscar: obj
        }).then(function success(e) {
            //console.log(e.data);
            $scope.vehiculos = e.data.resultado;
            $scope.loading = false;
            $scope.iniciobuscar = true;
        }, function error(e) {
            console.log("Se ha producido un error al recuperar la información");
            $scope.loading = false;
        });
    };

    $scope.buscarRif = function() {
        $scope.loading = true;
        var rif = $scope.vehiculo.rif;
        $http.get('scripts/contribuyente_buscarrif.php?rif=' + rif, {}).then(function success(e) {
            //console.log(e.data.id);
            $scope.idcliente = e.data.id;
            $scope.loading = false;
        }, function error(e) {
            console.log("Se ha producido un error al recuperar la información");
            $scope.loading = false;
        });
    };

    $scope.buscarRifEdit = function() {
        //$scope.idcliente = $scope.idclientebase;
        $scope.loading = true;
        //console.log($scope.editrif + ' .... ' + $scope.editrifbase);
        if ($scope.editrif != $scope.editrifbase) {
            var rif = $scope.editrif;
            $http.get('scripts/contribuyente_buscarrif.php?rif=' + rif, {}).then(function success(e) {
                //console.log(e.data.id);
                $scope.idcliente = e.data.id;
                $scope.loading = false;
            }, function error(e) {
                console.log("Se ha producido un error al recuperar la información");
                $scope.loading = false;
            });
        } else {
            $scope.idcliente = $scope.idclientebase;
        }
    };

    $scope.cargarEditarVehiculo = function(id) {
        $scope.loading = true;
        var index = $scope.vehiculos.indexOf($scope.vehiculos.find(x => x.id == id));
        //console.log($scope.vehiculos[index]);
        $scope.idclientebase = $scope.vehiculos[index].id_contribuyente;
        $scope.idcliente = $scope.vehiculos[index].id_contribuyente;
        $scope.editrif = $scope.vehiculos[index].rif;
        $scope.editrifbase = $scope.vehiculos[index].rif;
        $scope.editid = $scope.vehiculos[index].id;
        $scope.editnumero = $scope.vehiculos[index].numero;
        $scope.numerobase = $scope.vehiculos[index].numero;
        $scope.editmarca = $scope.vehiculos[index].marca;
        $scope.editmodelo = $scope.vehiculos[index].modelo;
        $scope.editanno = $scope.vehiculos[index].anno;
        $scope.editcolor = $scope.vehiculos[index].color;
        $scope.numero_existe = false;
        $scope.loading = false;
    };

    //listar Articulos
    $scope.listarVehiculos = function() {
        $scope.loading = true;
        $http.get('scripts/vehiculos_listar.php', {}).then(function success(response) {
            //console.log(response.data);
            $scope.vehiculos = response.data.resultado;
            $scope.loading = false;
        }, function error(response) {
            console.log("Se ha producido un error al recuperar la información");
            $scope.loading = false;
        });
    };
    //$scope.listarVehiculos();
    //console.log($scope.vehiculos);
    $scope.buscarPlaca = function(buscar) {
        $scope.loading = true;
        var obj = {
            numero: buscar
        };
        //console.log(obj);
        $http.post('scripts/vehiculos_buscarnumero.php', {
            registro: obj
        }).then(function success(response) {
            $scope.vehiculobuscar = response.data.resultado;
            //console.log($scope.vehiculobuscar);
            var index = $scope.vehiculobuscar.indexOf($scope.vehiculobuscar.find(x => x.placa == buscar));
            //console.log(index);
            //console.log('Cantidad: ' + $scope.bdactividades.length);
            //console.log(resultado.id);
            if (index >= 0) {
                $scope.numero_existe = true;
            } else {
                $scope.numero_existe = false;
            }
            console.log(index + ' ---- ' + $scope.numero_existe);
            $scope.loading = false;
        }, function error(response) {
            console.log("Se ha producido un error al recuperar la información");
            $scope.loading = false;
        });
    };
    $scope.buscarPlacaEditar = function(buscar) {
        //$scope.loading = true;
        if ($scope.editnumero != $scope.numerobase) {
            var obj = {
                numero: buscar
            };
            $http.post('scripts/vehiculos_buscarnumero.php', {
                registro: obj
            }).then(function success(response) {
                $scope.vehiculobuscar = response.data.resultado;
                var index = $scope.vehiculobuscar.indexOf($scope.vehiculobuscar.find(x => x.placa == buscar));
                //console.log('Cantidad: ' + $scope.bdactividades.length);
                //console.log(resultado.id);
                if (index >= 0) {
                    $scope.numero_existe = true;
                } else {
                    $scope.numero_existe = false;
                }
                //console.log($scope.numero_existe);
            }, function error(response) {
                console.log("Se ha producido un error al recuperar la información");
                //$scope.loading = false;
            });
        }
        //$scope.loading = false;
    };

    $scope.resetForm = function(form) {
        form.$setPristine();
        form.$setUntouched();
        $scope.numero_existe = false;
        $scope.vehiculo = {};
    };

    $scope.resetFormEditar = function(form) {
        form.$setPristine();
        form.$setUntouched();
        $scope.vehiculo = {};
    };

    //AGREGAR LA ACTIVIDAD
    $scope.agregarVehiculo = function(form) {
        if (form.$valid && $scope.idcliente > 0) {
            $scope.vehiculo.id_contribuyente = $scope.idcliente;
            $scope.vehiculo.usuario = $scope.usuario;
            $('#agregarvehiculo').attr("disabled", true);
            //console.log($scope.vehiculo);
            $scope.loading = true;
            $http.post('scripts/vehiculos_agregar.php', {
                registro: $scope.vehiculo
            }).then(function success(e) {
                console.log(e.data);
                $('#myModalVehiculos').modal('hide');
                alertify.success('Registro exitoso');
                $scope.buscarVehiculo($scope.vehiculo.numero, '1');
                $scope.vehiculo = {};
                $scope.resetForm(form);
                $scope.loading = false;
                $('#agregarvehiculo').attr("disabled", false);
            }, function error(e) {
                console.log("Se ha producido un error al recuperar la información");
                $scope.loading = false;
            });
        } else {
            alertify.error('Datos requeridos vacios, verifique');
            $scope.loading = false;
        }
    };
    //MODIFICAR ACTIVIDAD
    $scope.modificarVehiculo = function(form) {
        if (form.$valid && $scope.idcliente > 0) {
            var obj = {
                id: $scope.editid,
                id_contribuyente: $scope.idcliente,
                numero: $scope.editnumero,
                marca: $scope.editmarca,
                modelo: $scope.editmodelo,
                anno: $scope.editanno,
                color: $scope.editcolor,
                rif: $scope.editrif,
                usuario: $scope.usuario
            };
            $('#modificarvehiculo').attr("disabled", true);
            //$scope.loading = true;
            $http.post('scripts/vehiculos_editar.php', {
                registro: obj
            }).then(function success(e) {
                console.log(e.data);
                $('#myModalEditVehiculo').modal('hide');
                alertify.success('Registro modificado con éxito');
                $scope.buscarVehiculo($scope.editnumero, '1');
                $scope.vehiculo = {};
                $scope.numero_existe = false;
                //$scope.loading = false;
                $('#modificarvehiculo').attr("disabled", false);
            }, function error(e) {
                console.log("Se ha producido un error al recuperar la información");
                //$scope.loading = false;
            });
        } else {
            alertify.error('Datos requeridos vacios, verifique');
            //$scope.loading = false;
        }
    };

    //BORRAR SLIDER
    $scope.eliminarVehiculo = function(id) {
        //var id = $scope.patentes[index].id;
        var index = $scope.vehiculos.indexOf($scope.vehiculos.find(x => x.id == id));
        console.log(id);
        alertify.confirm("¿Estas seguro de elimnar el registro?", function(e) {
            if (e) {
                $scope.loading = true;
                //console.log(opcion);
                $http.get('scripts/vehiculos_eliminar.php?id=' + id, {}).then(function success(e) {
                    //console.log(e.data);
                    //$scope.listarvehiculos();
                    $scope.vehiculos.splice(index, 1)
                    $scope.loading = false;
                }, function error(e) {
                    console.log("Se ha producido un error al recuperar la información");
                    //$scope.loading = false;
                });
            }
        });
    };
}]);