var app = angular.module('proyectophp');
app.controller("usuariosController", ["$scope", "$http", function($scope, $http) {
    $scope.indice = '';
    $scope.usuario_ang = {};
    $scope.usuarios = [];
    $scope.permisos = [];
    $scope.usuario_ang.tipo_acceso = 'Tipo de Acceso';
    $scope.editid = 0;
    //$scope.editrif = '';
    $scope.editnombre = '';
    $scope.editcedula = '';
    $scope.edituser = '';
    $scope.editpassword = '';
    $scope.editemail = '';
    $scope.edittipo_acceso = '';
    $scope.usuario_existe = false;
    $scope.cedula_existe = false;
    $scope.rif_existe = false;
    $scope.loading = true;
    $scope.usuario = localStorage.getItem('alc_usuario');
    //Editar la actividad seleccionada
    $scope.cargarEditarUsuario = function(id) {
        var index = $scope.usuarios.indexOf($scope.usuarios.find(x => x.id == id));
        $scope.editid = $scope.usuarios[index].id;
        //$scope.editrif = $scope.usuarios[index].rif;
        $scope.editnombre = $scope.usuarios[index].nombre_usuario;
        $scope.edituser = $scope.usuarios[index].user;
        $scope.clave = $scope.usuarios[index].password;
        $scope.editemail = $scope.usuarios[index].email;
        $scope.edittipo_acceso = $scope.usuarios[index].acceso;
        $scope.userbase = $scope.usuarios[index].user;
        $scope.rifbase = $scope.usuarios[index].rif;
        $scope.editcedula = $scope.usuarios[index].usuario;
        $scope.cedulabase = $scope.usuarios[index].usuario;
        $scope.rif_existe = false;
        $scope.usuario_existe = false;
        $scope.cedula_existe = false;
        //console.log($scope.edittipo_acceso);
        $scope.desencriptar($scope.clave);
    };
    $scope.desencriptar = function(pass) {
        $scope.loading = true;
        $http.get('../scripts/desencriptar.php?passw=' + pass, {}).then(function success(c) {
            //console.log(c.data.clave);
            $scope.editpassword = c.data.clave;
            $scope.loading = false;
        }, function error(c) {
            console.log("Se ha producido un error al recuperar la información");
            $scope.loading = false;
        });
    };
    //listar Articulos
    $scope.listarPermisos = function() {
        $scope.loading = true;
        $http.get('scripts/accesos_listar.php', {}).then(function success(response) {
            $scope.permisos = response.data;
            $scope.loading = false;
        }, function error(response) {
            console.log("Se ha producido un error al recuperar la información");
            $scope.loading = false;
        });
    };
    $scope.listarPermisos();
    //listar Articulos
    $scope.listarUsuarios = function() {
        $scope.loading = true;
        $http.get('scripts/usuarios_listar.php', {}).then(function success(response) {
            //console.log(response.data);
            $scope.usuarios = response.data.resultado;
            $scope.loading = false;
        }, function error(response) {
            console.log("Se ha producido un error al recuperar la información");
            $scope.loading = false;
        });
    };
    $scope.listarUsuarios();
    $scope.buscarUsuario = function(buscar) {
        const resultado = $scope.usuarios.find(user => user.user === buscar);
        //console.log('Cantidad: ' + $scope.bdactividades.length);
        //console.log(resultado.id);
        if (resultado != null && resultado.id > 0) {
            $scope.usuario_existe = true;
        } else {
            $scope.usuario_existe = false;
        }
    };
    $scope.buscarCedulaUsuario = function(buscar) {
        const resultado = $scope.usuarios.find(user => user.usuario == buscar);
        //console.log('Cantidad: ' + $scope.bdactividades.length);
        //console.log(resultado.id);
        if (resultado != null && resultado.id > 0) {
            $scope.cedula_existe = true;
        } else {
            $scope.cedula_existe = false;
        }
    };
    $scope.buscarUsuarioEditar = function(buscar) {
        if ($scope.edituser != $scope.userbase) {
            const resultado = $scope.usuarios.find(user => user.user === buscar);
            //console.log('Cantidad: ' + $scope.bdactividades.length);
            //console.log(resultado.id);
            if (resultado != null && resultado.id > 0) {
                $scope.usuario_existe = true;
            } else {
                $scope.usuario_existe = false;
            }
            //console.log($scope.usuario_existe);
        }
    };
    $scope.buscarCedulaUsuarioEditar = function(buscar) {
        if ($scope.editcedula != $scope.cedulabase) {
            const resultado = $scope.usuarios.find(user => user.usuario == buscar);
            //console.log('Cantidad: ' + $scope.bdactividades.length);
            //console.log(resultado.id);
            if (resultado != null && resultado.id > 0) {
                $scope.cedula_existe = true;
            } else {
                $scope.cedula_existe = false;
            }
            //console.log($scope.usuario_existe);
        }
    };
    $scope.resetForm = function(form) {
        form.$setPristine();
        form.$setUntouched();
        $scope.usuario_ang = {};
        $scope.usuario.tipo_acceso = 'Tipo de Acceso';
        $scope.usuario_existe = false;
        $scope.rif_existe = false;
    };
    $scope.resetFormEditar = function(form) {
        form.$setPristine();
        form.$setUntouched();
        $scope.editid = 0;
        //$scope.editrif = '';
        $scope.edituser = '';
        $scope.editpassword = '';
        $scope.editemail = '';
        $scope.edittipo_acceso = '';
        $scope.usuario_existe = false;
        $scope.rif_existe = false;
    };
    //AGREGAR LA ACTIVIDAD
    $scope.agregarUsuario = function(form) {
        if (form.$valid) {
            $scope.loading = true;
            $('#agregarusuario').attr("disabled", true);
            //console.log($scope.usuario);
            $http.post('scripts/usuarios_agregar.php', {
                registro: $scope.usuario_ang
            }).then(function success(e) {
                //console.log(e.data);
                if (e.data.usuario.permitido) {
                    $('#myModalUsuario').modal('hide');
                    alertify.success('Registro exitoso');
                    $scope.usuario_ang = {};
                    $scope.listarUsuarios();
                    $scope.resetForm(form);
                }
                $scope.loading = false;
                $('#agregarusuario').attr("disabled", false);
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
    $scope.modificarUsuario = function(form) {
        if (form.$valid) {
            $scope.loading = true;
            $('#modificarusuario').attr("disabled", true);
            var obj = {
                id: $scope.editid,
                nombre_usuario: $scope.editnombre,
                user: $scope.edituser,
                password: $scope.editpassword,
                email: $scope.editemail,
                tipo_acceso: $scope.edittipo_acceso,
                usuario: $scope.editcedula
            };
            $http.post('scripts/usuarios_editar.php', {
                registro: obj
            }).then(function success(e) {
                //console.log(e.data.resultado['mensaje']);
                $('#myModalEditUsuario').modal('hide');
                alertify.success('Registro modificado');
                $scope.resetFormEditar(form);
                $scope.listarUsuarios();
                $scope.loading = false;
                $('#modificarusuario').attr("disabled", false);
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
    $scope.eliminarUsuario = function(id) {
        //var id = $scope.usuarios[index].id;
        alertify.confirm("¿Estas seguro de elimnar el registro?", function(e) {
            if (e) {
                $scope.loading = true;
                //console.log('Id: ' + id);
                $http.get('scripts/usuarios_eliminar.php?id=' + id, {}).then(function success(e) {
                    //console.log(e.data);
                    $scope.listarUsuarios();
                    $scope.loading = false;
                }, function error(e) {
                    console.log("Se ha producido un error al recuperar la información");
                    $scope.loading = false;
                });
            }
        });
    };
}]);