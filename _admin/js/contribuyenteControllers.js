var app = angular.module('proyectophp');
app.controller("contribuyenteController", ["$scope", "$http", function($scope, $http) {
    $scope.nombre = 'contribuyente';
    $scope.idcliente = 0;
    $scope.id_editar = 0;
    $scope.patente_existe = false;
    $scope.indice = '';
    $scope.contribuyente = {};
    $scope.editced_representante = '';
    $scope.editcel_contacto = '';
    $scope.editciudad = '';
    $scope.editdomicilio = '';
    $scope.editemail = '';
    $scope.editestado = '';
    $scope.editid_patente = '';
    $scope.editnombre = '';
    $scope.editzona = '';
    $scope.editpatente = '';
    $scope.editpatentebase = '';
    $scope.editrepresentante = '';
    $scope.editrif = '';
    $scope.editrifbase = '';
    $scope.tmp_contribuyente = {};
    $scope.contribuyentes = [];
    $scope.ciudades = [];
    $scope.estados = [];
    $scope.zonas = [];
    $scope.usuario = localStorage.getItem('alc_usuario');
    $scope.loading = true;
    $scope.contribuyente.estado = '';
    //Editar el contribuyente seleccionado
    $scope.listarEstados = function() {
        var obj = {
            nombre: 'estado',
            estado: $scope.contribuyente.estado
        };
        $scope.loading = true;
        $http.post('scripts/zonificacion_listar.php', {
            tabla: obj
        }).then(function success(response) {
            $scope.estados = response.data.zonificacion;
            $scope.listarCiudades();
            $scope.loading = false;
        }, function error(response) {
            console.log("Se ha producido un error al recuperar la información");
            $scope.loading = false;
        });
    };
    $scope.listarEstados();
    $scope.listarCiudades = function() {
        var obj = {
            nombre: 'ciudad',
            estado: $scope.contribuyente.estado
        };
        $scope.loading = true;
        $http.post('scripts/zonificacion_listar.php', {
            tabla: obj
        }).then(function success(response) {
            $scope.ciudades = response.data.zonificacion;
            $scope.listarZonas();
            $scope.loading = false;
        }, function error(response) {
            console.log("Se ha producido un error al recuperar la información");
            $scope.loading = false;
        });
    };
    $scope.listarCiudadesEdit = function() {
        var obj = {
            nombre: 'ciudad',
            estado: $scope.editestado
        };
        $scope.loading = true;
        $http.post('scripts/zonificacion_listar.php', {
            tabla: obj
        }).then(function success(response) {
            $scope.ciudades = response.data.zonificacion;
            $scope.listarZonas();
            $scope.loading = false;
        }, function error(response) {
            console.log("Se ha producido un error al recuperar la información");
            $scope.loading = false;
        });
    };
    $scope.listarZonas = function() {
        var obj = {
            nombre: 'zona',
            estado: $scope.contribuyente.estado
        };
        $scope.loading = true;
        $http.post('scripts/zonificacion_listar.php', {
            tabla: obj
        }).then(function success(response) {
            $scope.zonas = response.data.zonificacion;
            $scope.loading = false;
        }, function error(response) {
            console.log("Se ha producido un error al recuperar la información");
            $scope.loading = false;
        });
    };
    $scope.cargarEditarContribuyente = function(id) {
        var index = $scope.contribuyentes.indexOf($scope.contribuyentes.find(x => x.id == id));
        $scope.id_editar = $scope.contribuyentes[index].id;
        $scope.editrif = $scope.contribuyentes[index].rif;
        $scope.editid_patente = $scope.contribuyentes[index].id_patente;
        $scope.editpatente = $scope.contribuyentes[index].patente;
        $scope.editnombre = $scope.contribuyentes[index].nombre;
        $scope.editdomicilio = $scope.contribuyentes[index].domicilio;
        $scope.editestado = $scope.contribuyentes[index].estado;
        $scope.listarCiudadesEdit();
        $scope.editciudad = $scope.contribuyentes[index].ciudad;
        $scope.editzona = 1;
        $scope.editrepresentante = $scope.contribuyentes[index].representante;
        $scope.editced_representante = $scope.contribuyentes[index].ced_representante;
        $scope.editcel_contacto = $scope.contribuyentes[index].cel_contacto;
        $scope.editemail = $scope.contribuyentes[index].email;
        $scope.editrifbase = $scope.contribuyentes[index].rif;
        $scope.editpatentebase = $scope.contribuyentes[index].patente;
        $scope.patente_existe = true;
    };
    //listar Articulos
    $scope.listarContribuyente = function() {
        $scope.loading = true;
        $http.get('scripts/contribuyente_listar.php', {}).then(function success(response) {
            $scope.contribuyentes = response.data.contribuyentes;
            $scope.loading = false;
        }, function error(response) {
            console.log("Se ha producido un error al recuperar la información");
            $scope.loading = false;
        });
    };
    $scope.listarContribuyente();
    $scope.agregarContribuyente = function(form) {
        if (form.$valid) {
            $scope.loading = true;
            $('#agregarcontribuyente').attr("disabled", true);
           console.log($scope.contribuyente);
            $http.post('scripts/contribuyente_agregar.php?usuario=' + $scope.usuario, {
                registro: $scope.contribuyente
            }).then(function success(e) {
                //console.log(e.data);
                if (e.data.contribuyente.permitido) {
                    $('#myModalContribuyente .close').click();
                    alertify.success('Registro exitoso');
                    $scope.contribuyente = {};
                    $scope.resetForm(form);
                    $scope.listarContribuyente();
                }
                $scope.loading = false;
                $('#agregarcontribuyente').attr("disabled", false);
            }, function error(e) {
                console.log("Se ha producido un error al recuperar la información");
                $scope.loading = false;
            });
        } else {
            alertify.error('Datos requeridos vacios, verifique');
            $scope.loading = false;
        }
    };
    $scope.modificarContribuyente = function(form) {
        if (form.$valid) {
            $scope.loading = true;
            $('#modificarcontribuyente').attr("disabled", true);
            var obj = {
                id: $scope.id_editar,
                rif: $scope.editrif,
                patente: $scope.editpatente,
                nombre: $scope.editnombre,
                domicilio: $scope.editdomicilio,
                ciudad: $scope.editciudad,
                estado: $scope.editestado,
                zona: 1, //$scope.editzona,
                representante: $scope.editrepresentante,
                ced_representante: $scope.editced_representante,
                cel_contacto: $scope.editcel_contacto,
                email: $scope.editemail
            };
            $http.post('scripts/contribuyente_editar.php?usuario=' + $scope.usuario, {
                registro: obj
            }).then(function success(e) {
                //console.log(e.data.contribuyente.mensaje);
                if (e.data.contribuyente.permitido) {
                    alertify.success(e.data.contribuyente.mensaje);
                    $('#myModalEditContribuyente .close').click();
					//$('#myModalEditContribuyente').modal('hide');
                    $scope.id_editar = 0;
                    $scope.patente_existe = false;
                    $scope.editced_representante = '';
                    $scope.editcel_contacto = '';
                    $scope.editciudad = '';
                    $scope.editdomicilio = '';
                    $scope.editemail = '';
                    $scope.editestado = '';
                    $scope.editid_patente = '';
                    $scope.editnombre = '';
                    $scope.editzona = '';
                    $scope.editpatente = '';
                    $scope.editpatentebase = '';
                    $scope.editrepresentante = '';
                    $scope.editrif = '';
                    $scope.editrifbase = '';
                    $scope.resetFormEditar(form);
                    $scope.listarContribuyente();
                }
                $scope.loading = false;
                $('#modificarcontribuyente').attr("disabled", false);
            }, function error(e) {
                console.log("Se ha producido un error al recuperar la información");
                $scope.loading = false;
            });
        } else {
            alertify.error('Datos requeridos vacios, verifique');
            $scope.loading = false;
        }
    };
    $scope.buscarRif = function() {
        $scope.loading = true;
        var rif = $scope.contribuyente.rif;
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
        $scope.loading = true;
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
        }
    };
    $scope.buscarPatente = function() {
        $scope.loading = true;
        var patente = $scope.contribuyente.patente;
        $http.get('scripts/contribuyente_buscarpatente.php?patente=' + patente, {}).then(function success(e) {
            if (e.data.id > 0) {
                $scope.patente_existe = true;
            } else {
                $scope.patente_existe = false;
            }
            $scope.loading = false;
        }, function error(e) {
            console.log("Se ha producido un error al recuperar la información");
            $scope.loading = false;
        });
    };
    $scope.buscarPatenteEdit = function() {
        $scope.loading = true;
        $scope.patente_existe = true;
        if ($scope.editpatente != $scope.editpatentebase) {
            var patente = $scope.editpatente;
            $http.get('scripts/contribuyente_buscarpatente.php?patente=' + patente, {}).then(function success(e) {
                if (e.data.id > 0) {
                    $scope.patente_existe = true;
                } else {
                    $scope.patente_existe = false;
                }
                $scope.loading = false;
            }, function error(e) {
                console.log("Se ha producido un error al recuperar la información");
                $scope.loading = false;
            });
        }
    };
    //BORRAR SLIDER
    $scope.eliminarContribuyente = function(id) {
        alertify.confirm("¿Estas seguro de elimnar el registro?", function(e) {
            if (e) {
                $scope.loading = true;
                //var id = $scope.contribuyentes[index].id;
                //console.log('Id: ' + id);
                $http.get('scripts/contribuyente_eliminar.php?id=' + id, {}).then(function success(e) {
                    //console.log(e.data);
                    $scope.listarContribuyente();
                    $scope.loading = false;
                }, function error(e) {
                    console.log("Se ha producido un error al recuperar la información");
                    $scope.loading = false;
                });
            }
        });
    };
    $scope.resetForm = function(form) {
        form.$setPristine();
        form.$setUntouched();
        $scope.idcliente = 0;
        $scope.id_editar = 0;
        $scope.patente_existe = false;
        $scope.tmp_contribuyente = {};
    };
    $scope.resetFormEditar = function(form) {
        form.$setPristine();
        form.$setUntouched();
        $scope.id_editar = 0;
        $scope.patente_existe = false;
        $scope.editced_representante = '';
        $scope.editcel_contacto = '';
        $scope.editciudad = '';
        $scope.editdomicilio = '';
        $scope.editemail = '';
        $scope.editestado = '';
        $scope.editid_patente = '';
        $scope.editnombre = '';
        $scope.editzona = '';
        $scope.editpatente = '';
        $scope.editpatentebase = '';
        $scope.editrepresentante = '';
        $scope.editrif = '';
        $scope.editrifbase = '';
    };
}]);