var app = angular.module('proyectophp');
app.controller("actividadesController", ["$scope", "$http", function($scope, $http) {
    $scope.nombre = 'actividades';
    $scope.indice = '';
    $scope.actividad = {};
    $scope.bdactividades = [];
    $scope.codigo_existe = false;
    $scope.descripcion_existe = false;
    $scope.editid = '';
    $scope.editcodigo = '';
    $scope.editdescripcion = '';
    $scope.edittasa = '';
    $scope.codigobase = '';
    $scope.descripcionbase = '';
    $scope.carga = [];
    $scope.usuario = localStorage.getItem('alc_usuario');
    $scope.loading = true;
    //Editar la actividad seleccionada
    $scope.cargarEditarActividad = function(id) {
        var index = $scope.bdactividades.indexOf($scope.bdactividades.find(x => x.id == id));
        $scope.editid = $scope.bdactividades[index].id;
        $scope.editcodigo = $scope.bdactividades[index].codigo;
        $scope.editdescripcion = $scope.bdactividades[index].descripcion;
        $scope.edittasa = $scope.bdactividades[index].tasa;
        $scope.codigobase = $scope.bdactividades[index].codigo;
        $scope.descripcionbase = $scope.bdactividades[index].descripcion;
        $scope.codigo_existe = false;
        $scope.descripcion_existe = false;
    };
    //listar Articulos
    $scope.listarActividades = function() {
        $scope.loading = true;
        $http.get('scripts/actividades_listar.php', {}).then(function success(response) {
            $scope.bdactividades = response.data.resultado;
            $scope.loading = false;
        }, function error(response) {
            console.log("Se ha producido un error al recuperar la información");
            $scope.loading = false;
        });
    };
    $scope.listarActividades();
    $scope.buscarCodigo = function(buscar) {
        const resultado = $scope.bdactividades.find(codigo => codigo.codigo === buscar.toUpperCase());
        //console.log('Cantidad: ' + $scope.bdactividades.length);
        //console.log(resultado.id);
        if (resultado != null && resultado.id > 0) {
            $scope.codigo_existe = true;
        } else {
            $scope.codigo_existe = false;
        }
    };
    $scope.buscarDescripcion = function(buscar) {
        const resultado = $scope.bdactividades.find(descripcion => descripcion.descripcion === buscar.toUpperCase());
        //console.log('Cantidad: ' + $scope.bdactividades.length);
        //console.log(resultado.id);
        if (resultado != null && resultado.id > 0) {
            $scope.descripcion_existe = true;
        } else {
            $scope.descripcion_existe = false;
        }
    };
    $scope.buscarCodigoEditar = function(buscar) {
        if ($scope.editcodigo.toUpperCase() != $scope.codigobase.toUpperCase()) {
            const resultado = $scope.bdactividades.find(codigo => codigo.codigo === buscar.toUpperCase());
            //console.log('Cantidad: ' + $scope.bdactividades.length);
            //console.log(resultado.id);
            if (resultado != null && resultado.id > 0) {
                $scope.codigo_existe = true;
            } else {
                $scope.codigo_existe = false;
            }
        }
    };
    $scope.buscarDescripcionEditar = function(buscar) {
        if ($scope.editdescripcion.toUpperCase() != $scope.descripcionbase.toUpperCase()) {
            const resultado = $scope.bdactividades.find(descripcion => descripcion.descripcion === buscar.toUpperCase());
            //console.log('Cantidad: ' + $scope.bdactividades.length);
            //console.log(resultado.id);
            if (resultado != null && resultado.id > 0) {
                $scope.descripcion_existe = true;
            } else {
                $scope.descripcion_existe = false;
            }
        }
    };
    //AGREGAR LA ACTIVIDAD
    $scope.agregarActividad = function(form) {
        if (form.$valid) {
            $scope.loading = true;
            $('#agregaractividad').attr("disabled", true);
            //console.log($scope.actividad);
            $http.post('scripts/actividades_agregar.php?usuario=' + $scope.usuario, {
                registro: $scope.actividad
            }).then(function success(e) {
                console.log(e.data);
                if (e.data.actividad.permitido) {
                    $('#myModalActividades').modal('hide');
                    alertify.success('Registro exitoso');
                    $scope.actividad = {};
                    $scope.listarActividades();
                    $scope.resetForm(form);
                }
                $scope.loading = false;
                $('#agregaractividad').attr("disabled", false);
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
    $scope.modificarActividad = function(form) {
        if (form.$valid) {
            $scope.loading = true;
            $('#modificaractividad').attr("disabled", true);
            var obj = {
                id: $scope.editid,
                codigo: $scope.editcodigo,
                descripcion: $scope.editdescripcion,
                tasa: $scope.edittasa
            };
            $http.post('scripts/actividades_editar.php?usuario=' + $scope.usuario, {
                registro: obj
            }).then(function success(e) {
                //console.log(e.data.contribuyente.mensaje);
                if (e.data.actividad.permitido) {
                    $('#myModalEditActividades').modal('hide');
                    alertify.success(e.data.actividad.mensaje);
                    $scope.codigo_existe = false;
                    $scope.descripcion_existe = false;
                    $scope.editid = '';
                    $scope.editcodigo = '';
                    $scope.editdescripcion = '';
                    $scope.edittasa = '';
                    $scope.codigobase = '';
                    $scope.descripcionbase = '';
                    $scope.listarActividades();
                    $scope.resetFormEditar(form);
                }
                $scope.loading = false;
                $('#modificaractividad').attr("disabled", false);
            }, function error(e) {
                console.log("Se ha producido un error al recuperar la información");
                $scope.loading = false;
            });
        } else {
            alertify.error('Datos requeridos vacios, verifique');
            $scope.loading = false;
        }
    };
    //BORRAR SLIDER
    $scope.eliminarActividad = function(id) {
        alertify.confirm("¿Estas seguro de elimnar el registro?", function(e) {
            if (e) {
                $scope.loading = true;
                //console.log('Id: ' + id);
                $http.get('scripts/actividades_eliminar.php?id=' + id, {}).then(function success(e) {
                    console.log(e.data);
                    $scope.listarActividades();
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
        $scope.codigo_existe = false;
        $scope.descripcion_existe = false;
    };
    $scope.resetFormEditar = function(form) {
        form.$setPristine();
        form.$setUntouched();
        $scope.codigo_existe = false;
        $scope.descripcion_existe = false;
        $scope.editid = '';
        $scope.editcodigo = '';
        $scope.editdescripcion = '';
        $scope.edittasa = '';
        $scope.codigobase = '';
        $scope.descripcionbase = '';
    };
}]);