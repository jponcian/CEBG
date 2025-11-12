var app = angular.module('proyectophp');
app.controller("patentesController", ["$scope", "$http", function($scope, $http) {
    $scope.indice = '';
    $scope.patente = {};
    $scope.patentes = [];
    $scope.patente.manana = '0800AM1200MM';
    $scope.patente.tarde = '0000';
    /*$scope.patente.turnos = 'Turnos';

    $scope.patente.nocturnos = 'Nocturno';

    $scope.patente.talento_vivo = 'Talento vivo';

    $scope.patente.rockola = 'Rockola';*/
    $scope.patente.turnos = 2;
    $scope.patente.nocturnos = 0;
    $scope.patente.talento_vivo = 0;
    $scope.patente.rockola = 0;
    $scope.patente.otro = '';
    $scope.numero_existe = false;
    $scope.editid = 0;
    $scope.editnumero = '';
    $scope.numerobase = '';
    $scope.editfecha = '';
	$scope.editestatu = '';
	$scope.editcierretmp = '';
	$scope.editcierredef = '';
    $scope.patente.vencimiento = '31/12/2019';
    $scope.editvencimiento = '2019-12-31';
    $scope.editdescripcion = '';
    $scope.editdireccion = '';
    $scope.editrepresentante = '';
    $scope.editcedula = '';
    $scope.editobreros = 0;
    $scope.editempleados = 0;
    $scope.editturnos = 2;
    $scope.editmanana = '0800AM1200MM';
    $scope.edittarde = '0000';
    $scope.editnocturnos = 0;
    $scope.edittalento_vivo = 0;
    $scope.editrockola = 0;
    $scope.editotros = '';
    $scope.editrif = '';
    $scope.editrifbase = '';
    $scope.actividades = [];
    $scope.actividades_base = [];
    $scope.actividades_tmp = [];
    $scope.actividad_tmp = {};
    $scope.actividad_tmp_id = 0;
    $scope.codigo_tmp = '';
    $scope.descripcion_tmp = '';
    $scope.idcliente = 0;
    $scope.idclientebase = 0;
    $scope.loading = true;
    $scope.filtrar = '1';
    $scope.patentebuscar = [];
    $scope.iniciobuscar = false;
    $scope.usuario = localStorage.getItem('alc_usuario');
    $("#fecha").datepicker();
    $("#editfecha").datepicker();
    $("#editcierredef").datepicker();
    $("#editcierretmp").datepicker();
    $scope.buscarPatente = function(dato, filtro) {
        $scope.iniciobuscar = false;
        $scope.loading = true;
        var obj = {
            dato: dato,
            filtro: filtro
        };
        $http.post('scripts/patentes_filtrar.php', {
            buscar: obj
        }).then(function success(e) {
            //console.log(e.data);
            $scope.patentes = e.data.resultado;
            $scope.loading = false;
            $scope.iniciobuscar = true;
        }, function error(e) {
            console.log("Se ha producido un error al recuperar la información");
            $scope.loading = false;
        });
    };
    $scope.selectCombos = function(a, b, c, d) {
        $scope.editnocturnos = a;
        $scope.edittalento_vivo = b;
        $scope.editrockola = c;
		$scope.editestatus = d;
    };
    $scope.buscarRif = function() {
        $scope.loading = true;
        var rif = $scope.patente.rif;
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
        if ($scope.editrif !== $scope.editrifbase) {
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
    $scope.obtenerId = function() {
        if ($scope.actividad_tmp_id != undefined || $scope.actividad_tmp_id != null) {
            const resultado = $scope.actividades.find(id => id.id == $scope.actividad_tmp_id);
            $scope.codigo_tmp = resultado.codigo;
            $scope.descripcion_tmp = resultado.descripcion;
            //console.log(resultado.codigo);
        }
    };
    $scope.filtrarActividades = function(codigo) {
        $scope.actividades = $scope.actividades.filter(function(activity) {
            return activity.codigo !== codigo;
        });
    };
    $scope.agregarActividadTmp = function() {
        $scope.loading = true;
        $scope.obtenerId();
        var obj = {
            codigo: $scope.codigo_tmp,
            descripcion: $scope.descripcion_tmp,
            id_actividad: $scope.actividad_tmp_id,
            numero: $scope.patente.numero,
            usuario: $scope.usuario
        };
        //console.log(obj);
        $http.post('scripts/patentesdetalle_agregar.php', {
            registro: obj
        }).then(function success(e) {
            //console.log(e.data.actividad.permitido);
            //if (e.data.actividad.permitido) {
            $scope.listarActividadesTmpAgregar($scope.patente.numero);
            $scope.actividad_tmp = {};
            $scope.filtrarActividades($scope.codigo_tmp);
            //console.log($scope.actividades_tmp);
            //}
            $scope.loading = false;
        }, function error(e) {
            console.log("Se ha producido un error al recuperar la información");
            $scope.loading = false;
        });
    };
    $scope.agregarActividadTmpEditar = function() {
        $scope.loading = true;
        $scope.obtenerId();
        var obj = {
            codigo: $scope.codigo_tmp,
            descripcion: $scope.descripcion_tmp,
            id_actividad: $scope.actividad_tmp_id,
            id_patente: $scope.editid,
            numero: $scope.editnumero,
            usuario: $scope.usuario
        };
        $http.post('scripts/patentesdetalle_editar.php', {
            registro: obj
        }).then(function success(e) {
            //console.log(e.data.actividad.permitido);
            if (e.data.actividad.permitido) {
                $scope.listarActividadesTmpEditar($scope.editid);
                $scope.actividad_tmp = {};
                $scope.filtrarActividades($scope.codigo_tmp);
            }
            $scope.loading = false;
        }, function error(e) {
            console.log("Se ha producido un error al recuperar la información");
            $scope.loading = false;
        });
    };
    $scope.eliminarTemporalAdd = function(numero) {
        $scope.loading = true;
        //console.log(numero);
        $http.get('scripts/patentestmpall_eliminar.php?numero=' + numero, {}).then(function success(response) {
            if (response.data.permitido) {
                //console.log(response.data.mensaje);
                /*$scope.actividades_tmp = [];

                $scope.actividades_base = [];*/
                $scope.listarActividades();
            }
            $scope.loading = false;
        }, function error(response) {
            console.log("Se ha producido un error al recuperar la información");
            $scope.loading = false;
        });
    };
    $scope.eliminarTemporal = function(numero) {
        $scope.loading = true;
        //console.log(numero);
        $http.get('scripts/actividadestmpall_eliminar.php?numero=' + numero, {}).then(function success(response) {
            //console.log(response.data);
            $scope.loading = false;
        }, function error(response) {
            console.log("Se ha producido un error al recuperar la información");
            $scope.loading = false;
        });
    };
    $scope.eliminarTemporalCarga = function() {
        $scope.loading = true;
        var usuario = $scope.usuario;
        $http.get('scripts/actividadestmp_eliminarcarga.php?usuario=' + usuario, {}).then(function success(response) {
            if (response.data.permitido) {
                //console.log(response.data.mensaje);
                $scope.actividades_tmp = [];
                $scope.listarActividades();
            }
            $scope.loading = false;
        }, function error(response) {
            console.log("Se ha producido un error al recuperar la información");
            $scope.loading = false;
        });
    };
    $scope.eliminarActividadTmp = function(index, id, numero) {
        //console.log(index + '-' + id + '-' + numero);
        alertify.confirm("¿Estas seguro de eliminar el registro?", function(e) {
            if (e) {
                $scope.loading = true;
                $scope.actividades_tmp.splice(index, 1);
                $http.get('scripts/actividadestmp_eliminar.php?id=' + id + '&numero=' + numero, {}).then(function success(response) {
                    //console.log(response.data.mensaje);
                    $scope.filtrarComboActividadEditar();
                    $scope.loading = false;
                }, function error(response) {
                    console.log("Se ha producido un error al recuperar la información");
                    $scope.loading = false;
                });
            }
        });
    };
    $scope.listarActividades = function() {
        $scope.loading = true;
        $http.get('scripts/actividades_listar.php', {}).then(function success(response) {
            //$scope.actividades_tmp = [];
            $scope.actividades = response.data.resultado;
            $scope.actividades_base = response.data.resultado;
            $scope.loading = false;
        }, function error(response) {
            console.log("Se ha producido un error al recuperar la información");
            $scope.loading = false;
        });
    };
    $scope.listarActividades();
    //Editar la actividad seleccionada
    $scope.cargarEditarPatente = function(id) {
        $scope.loading = true;
        var index = $scope.patentes.indexOf($scope.patentes.find(x => x.id == id));
        //console.log($scope.patentes[index]);
        //$scope.eliminarTemporal($scope.patentes[index].numero);
        $scope.idclientebase = $scope.patentes[index].id_contribuyente;
        $scope.idcliente = $scope.patentes[index].id_contribuyente;
        $scope.editrif = $scope.patentes[index].rif;
        $scope.editrifbase = $scope.patentes[index].rif;
        $scope.editid = $scope.patentes[index].id;
        $scope.editnumero = $scope.patentes[index].numero;
        $scope.numerobase = $scope.patentes[index].numero;
        $scope.editfecha = $scope.patentes[index].fecha_registro;
        $scope.editvencimiento = $scope.patentes[index].vencimiento;
        $scope.editdescripcion = $scope.patentes[index].descripcion_establecimiento;
        $scope.editdireccion = $scope.patentes[index].direeccion_establecimiento;
        $scope.editrepresentante = $scope.patentes[index].representante;
        $scope.editcedula = $scope.patentes[index].ced_representante;
		//$scope.editestatus = $scope.patentes[index].estatus;
		$scope.editcierre_tmp = $scope.patentes[index].cierre_tmp;
		$scope.editcierre_def = $scope.patentes[index].cierre_def;
			$scope.edittarde = $scope.patentes[index].expediente;
        $scope.editnocturnos = 0;
        $scope.edittalento_vivo = 0;
        $scope.editrockola = 0;
        $scope.editotros = $scope.patentes[index].otros;
        $scope.numero_existe = false;
        $scope.selectCombos($scope.patentes[index].nocturno, $scope.patentes[index].talento_vivo, $scope.patentes[index].rockola, $scope.patentes[index].estatus);
        $scope.cargarActividadesEditar($scope.editid, $scope.editnumero);
        $scope.filtrarComboActividadEditar();
        $scope.loading = false;
    };
    $scope.filtrarComboActividadEditar = function() {
        $scope.actividades = $scope.actividades_base;
        angular.forEach($scope.actividades_tmp, function(act) {
            $scope.filtrarActividades(act.codigo);
        });
    };
    $scope.AgregarDetalleTmpEditar = function(id_patente) {
        $scope.loading = true;
        //console.log(id_patente);
        $http.get('scripts/patente_agregartmp.php?id_patente=' + id_patente, {}).then(function success(response) {
            //console.log(response.data);
            //$scope.filtrarComboActividadEditar();
            $scope.loading = false;
        }, function error(response) {
            console.log("Se ha producido un error al recuperar la información");
            $scope.loading = false;
        });
    };
    $scope.listarActividadesTmpAgregar = function(numero) {
        //console.log(numero);
        var obj = {
            numero: numero
        };
        $scope.loading = true;
        $http.post('scripts/actividadestmpadd_listar.php', {
            registro: obj
        }).then(function success(response) {
            //console.log(response.data);
            $scope.actividades_tmp = response.data.resultado;
            $scope.filtrarComboActividadEditar();
            $scope.loading = false;
        }, function error(response) {
            console.log("Se ha producido un error al recuperar la información");
            $scope.loading = false;
        });
    };
    $scope.listarActividadesTmpEditar = function(numero) {
        $scope.loading = true;
        var obj = {
            numero: numero
        }
        //console.log(numero);
        $http.post('scripts/actividadestmp_listareditar.php', {
            registro: obj
        }).then(function success(response) {
            //console.log(response.data);
            $scope.actividades_tmp = response.data.resultado;
            //console.log($scope.actividades_tmp);
            //$scope.filtrarComboActividadEditar();
            $scope.loading = false;
        }, function error(response) {
            console.log("Se ha producido un error al recuperar la información");
            $scope.loading = false;
        });
    };
    $scope.cargarActividadesEditar = function(id_patente, numero) {
        //console.log(id_patente + 'test' + numero);
        var obj = {
            id_patente: id_patente,
            numero: numero
        };
        //console.log(obj);
        $scope.loading = true;
        //$scope.AgregarDetalleTmpEditar(id_patente);
        $http.post('scripts/actividadestmp_listar.php', {
            registro: obj
        }).then(function success(response) {
            //console.log(response.data);
            //console.log(response.data.resultado);
            $scope.actividades_tmp = response.data.resultado;
            //console.log($scope.actividades_tmp);
            $scope.filtrarComboActividadEditar();
            $scope.loading = false;
        }, function error(response) {
            console.log("Se ha producido un error al recuperar la información");
            $scope.loading = false;
        });
    };
    //listar Articulos
    $scope.listarPatentes = function() {
        $scope.loading = true;
        $http.get('scripts/patentes_listar.php', {}).then(function success(response) {
            $scope.patentes = response.data.resultado;
            $scope.loading = false;
        }, function error(response) {
            console.log("Se ha producido un error al recuperar la información");
            $scope.loading = false;
        });
    };
    //$scope.listarPatentes();
    $scope.buscarNumero = function(buscar) {
        $scope.loading = true;
        var obj = {
            numero: buscar
        };
        $http.post('scripts/patentes_buscarnumero.php', {
            registro: obj
        }).then(function success(response) {
            $scope.patentebuscar = response.data.resultado;
            //console.log($scope.patentebuscar);
            var index = $scope.patentebuscar.indexOf($scope.patentebuscar.find(x => x.numero == buscar));
            //console.log(index);
            //console.log('Cantidad: ' + $scope.bdactividades.length);
            //console.log(resultado.id);
            if (index >= 0) {
                $scope.numero_existe = true;
            } else {
                $scope.numero_existe = false;
            }
            //console.log(index + ' ---- ' + $scope.numero_existe);
            $scope.loading = false;
        }, function error(response) {
            console.log("Se ha producido un error al recuperar la información");
            $scope.loading = false;
        });
    };
    $scope.buscarNumeroEditar = function(buscar) {
        $scope.loading = true;
        if ($scope.editnumero != $scope.numerobase) {
            var obj = {
                numero: buscar
            };
            $http.post('scripts/patentes_buscarnumero.php', {
                registro: obj
            }).then(function success(response) {
                $scope.patentebuscar = response.data.resultado;
                var index = $scope.patentebuscar.indexOf($scope.patentebuscar.find(x => x.numero == buscar));
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
                $scope.loading = false;
            });
        }
        $scope.loading = false;
    };
    $scope.resetForm = function(form) {
        form.$setPristine();
        form.$setUntouched();
        $scope.eliminarTemporalAdd($scope.patente.numero);
        $scope.numero_existe = false;
        $scope.patente.manana = '0800am1200mm';
        $scope.patente.tarde = '0000';
        $scope.patente.turnos = 'Turnos';
        $scope.patente.nocturnos = 'Nocturno';
        $scope.patente.talento_vivo = 'Talento vivo';
        $scope.patente.rockola = 'Rockola';
    };
    $scope.resetFormEditar = function(form) {
        form.$setPristine();
        form.$setUntouched();
        $scope.eliminarTemporal($scope.editnumero);
        $scope.actividades = $scope.actividades_base;
        //$scope.actividades_tmp = [];
        $scope.actividad_tmp = {};
        $scope.patente = {};
    };
    //AGREGAR LA ACTIVIDAD
    $scope.agregarPatente = function(form) {
        if (form.$valid && $scope.idcliente > 0) {
            $scope.patente.obreros = 0;
            $scope.patente.empleados = 0;
            $scope.patente.turnos = 2;
            $scope.patente.nocturnos = 0;
            $scope.patente.talento_vivo = 0;
            $scope.patente.rockola = 0;
            $scope.patente.otro = '';
            $scope.patente.vencimiento = '31/12/2019';
            $scope.patente.usuario = $scope.usuario;
            $('#agregarpatente').attr("disabled", true);
            //console.log($scope.patente);
            $scope.loading = true;
            $http.post('scripts/patentes_agregar.php', {
                registro: $scope.patente
            }).then(function success(e) {
                //console.log(e.data);
                $('#myModalPatente').modal('hide');
                alertify.success('Registro exitoso');
                $scope.buscarPatente($scope.patente.numero, '1');
                $scope.patente = {};
                //$scope.actividades_tmp = [];
                $scope.resetForm(form);
                //$scope.listarPatentes();
                $scope.loading = false;
                $('#agregarpatente').attr("disabled", false);
            }, function error(e) {
                console.log("Se ha producido un error al recuperar la información");
                $scope.loading = false;
            });
        } else {
            alertify.error('Datos requeridos vacios, verifique');
            $scope.loading = false;
        }
    };
    //MODIFICAR
    $scope.modificarPatente = function(form) {
        if (form.$valid && $scope.idcliente > 0) {
            $('#modificarpatente').attr("disabled", true);
            $scope.loading = true;
            var obj = {
                id: $scope.editid,
                numero: $scope.editnumero,
                fecha: $scope.editfecha,
                descripcion: $scope.editdescripcion,
                direccion: $scope.editdireccion,
                representante: $scope.editrepresentante,
                cedula: $scope.editcedula,
                vencimiento: $scope.editvencimiento,
				estatus: $scope.editestatus,
				cierre_tmp: $scope.editcierre_tmp,
				cierre_def: $scope.editcierre_def,
                obreros: $scope.editobreros,
                empleados: $scope.editempleados,
                turnos: $scope.editturnos,
                manana: $scope.editmanana,
                tarde: $scope.edittarde,
                nocturno: 0,
                talento_vivo: 0,
                rockola: 0,
                otro: '',
                rif: $scope.editrif,
                usuario: $scope.usuario
            };
            //console.log(obj);
            $http.post('scripts/patentes_editar.php', {
                registro: obj
            }).then(function success(e) {
                //console.log(e.data);
                $('#myModalEditPatente').modal('hide');
                alertify.success('Registro modificado con éxito');
                $scope.buscarPatente($scope.editnumero, '1');
                $scope.editid = 0;
                $scope.editnumero = '';
                $scope.editfecha = '';
                $scope.editdescripcion = '';
                $scope.editdireccion = '';
                $scope.editrepresentante = '';
                $scope.editcedula = '';
                $scope.editestatus = '0';
                $scope.editcierretmp = '';
                $scope.editcierredef = '';
                 $scope.edittarde = '';/*$scope.editturnos = '';
                $scope.editmanana = '';
               
                $scope.editotros = '';*/
                $scope.editrif = '';
                /*$scope.editnocturnos = -1;
                $scope.edittalento_vivo = -1;
                $scope.editrockola = -1;*/
                $scope.numero_existe = false;
                $scope.resetForm(form);
                //$scope.listarPatentes();
                //$scope.actividades_tmp = [];
                $scope.loading = false;
                $('#modificarpatente').attr("disabled", false);
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
    $scope.eliminarPatente = function(id) {
        //var id = $scope.patentes[index].id;
        var index = $scope.patentes.indexOf($scope.patentes.find(x => x.id == id));
        //console.log(id);
        alertify.confirm("¿Estas seguro de eliminar el registro?", function(e) {
            if (e) {
                $scope.loading = true;
                //console.log(opcion);
                $http.get('scripts/patentes_eliminar.php?id=' + id, {}).then(function success(e) {
                    //console.log(e.data);
                    //$scope.listarPatentes();
                    $scope.patentes.splice(index, 1)
                    $scope.loading = false;
                }, function error(e) {
                    console.log("Se ha producido un error al recuperar la información");
                    $scope.loading = false;
                });
            }
        });
    };
}]);