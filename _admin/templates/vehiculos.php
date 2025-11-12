<?php
session_start();
include_once "../../conexion.php";

if ($_SESSION['VERIFICADO'] != "SI") { 
header ("Location: ../validacion.php?opcion=val"); 
exit(); }

$acceso=42;
//------- VALIDACION ACCESO USUARIO
include_once "../../validacion_usuario.php";
//-----------------------------------
?>
<div class="container" ng-controller="vehiculosController">
    <div class="loading" ng-if="loading">
        <div id="cssload-pgloading">
            <div class="cssload-loadingwrap">
                <ul class="cssload-bokeh">
                    <li></li>
                    <li></li>
                    <li></li>
                    <li></li>
                </ul>
            </div>
        </div>
    </div>
    <!-- Modal Agregar -->
    <div class="modal fade bd-example-modal-lg" id="myModalVehiculos">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header bg-fondo text-center">
                    <h4 class="modal-title w-100 font-weight-bold py-2" style="background-color:#0275d8; color:#FFFFFF">Datos del Vehiculo a Incluir</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" ng-click="resetForm(formVehiculos)">
                        <span aria-hidden="true" class="white-text">&times;</span>
                    </button>
                </div>
                <!-- Modal body -->
                <div class="modal-body bg-white">
                    <form id="formVehiculos" name="formVehiculos" method="post" novalidate>
                        <div class="p-1">
                            <div class="row">
                                <div class="form-group col-sm-4">
                                    <div class="input-group">
                                        <div class="input-group-text"><i class="fas fa-book"></i></div>
                                        <input type="text" ng-change="buscarPlaca(vehiculo.numero)" class="form-control {{formVehiculos.numero.$touched === true ? numero_existe === false ? 'is-valid' : 'is-invalid' : ''}}" name="numero" id="numero" placeholder="Numero placa" ng-model="vehiculo.numero" minlength="1" maxlength="7" required mayusculastodo>
                                    </div>
                                    <div class="col-md-12" style="font-size: 10px; padding-left: 50px">
                                        <strong class="text-danger stretched-link text-right" ng-show="numero_existe === true" role="alert">
                                            Placa ya registrada
                                        </strong>
                                    </div>
                                </div>
                                <div class="form-group col-sm-4">
                                    <div class="input-group">
                                        <div class="input-group-text"><i class="fas fa-id-card-alt"></i></div>
                                        <input type="text" ng-change="buscarRif()" class="form-control {{formVehiculos.rif.$touched === true ? vehiculo.rif.length > 9 && idcliente > 0? 'is-valid' : 'is-invalid' : ''}}" name="rif" id="rif" ui-mask="A-99999999-9" ui-mask-placeholder placeholder="Numero de Rif" ng-model="vehiculo.rif" required mayusculastodo>
                                    </div>
                                    <div class="col-md-12" style="font-size: 10px; padding-left: 50px">
                                        <strong class="text-danger stretched-link text-right" ng-show="vehiculo.rif.length > 9 && idcliente == 0" role="alert">
                                            Contribuyente no registrado
                                        </strong>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-sm-6">
                                    <div class="input-group">
                                        <div class="input-group-text"><i class="fas fa-file-signature"></i></div>
                                        <input type="text" class="form-control  {{formVehiculos.marca.$touched === true ? vehiculo.marca.length > 1 ? 'is-valid' : 'is-invalid' : ''}}" name="marca" id="marca" placeholder="Marca del vehiculo" ng-model="vehiculo.marca" minlength="1" maxlength="13" required mayusculastodo>
                                    </div>
                                </div>
                                <div class="form-group col-sm-6">
                                    <div class="input-group">
                                        <div class="input-group-text"><i class="fas fa-map-marker-alt"></i></div>
                                        <input type="text" class="form-control {{formVehiculos.modelo.$touched === true ? vehiculo.modelo.length > 1 ? 'is-valid' : 'is-invalid' : ''}}" name="modelo" id="modelo" placeholder="Modelo" ng-model="vehiculo.modelo" minlength="1" maxlength="20" required mayusculastodo>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-sm-6">
                                    <div class="input-group">
                                        <div class="input-group-text"><i class="fas fa-user-tag"></i></div>
                                        <input type="text" class="form-control  {{formVehiculos.anno.$touched === true ? vehiculo.anno.length > 0 ? 'is-valid' : 'is-invalid' : ''}}" name="anno" id="anno" placeholder="Año" ng-model="vehiculo.anno" minlength="4" maxlength="4" required mayusculastodo>
                                    </div>
                                </div>
                                <div class="form-group col-sm-6">
                                    <div class="input-group">
                                        <div class="input-group-text"><i class="far fa-id-card"></i></div>
                                        <input type="text" class="form-control {{formVehiculos.color.$touched === true ? vehiculo.color.length > 1 ? 'is-valid' : 'is-invalid' : ''}}" name="color" id="color" placeholder="Color" ng-model="vehiculo.color" minlength="1" maxlength="20" required mayusculastodo>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <!-- Modal footer -->
                <div class="modal-footer justify-content-center">
                    <button type="button" id="agregarvehiculo" class="btn btn-outline-primary waves-effect" ng-click="agregarVehiculo(formVehiculos)"><i class="fas fa-cloud-upload-alt prefix grey-text mr-1"></i> Guardar</button>
                </div>
            </div>
        </div>
    </div>
    <!--  FIN MODAL AGREGAR -->
    <!-- Modal EDITAR -->
    <div class="modal fade bd-example-modal-lg" id="myModalEditVehiculo">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header bg-fondo text-center">
                    <h4 style="background-color:#0275d8; color:#FFFFFF" class="modal-title w-100 font-weight-bold py-2">Datos de la Vehiculo a Editar</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" ng-click="resetFormEditar(formEditVehiculo)">
                        <span aria-hidden="true" class="white-text">&times;</span>
                    </button>
                </div>
                <!-- Modal body -->
                <div class="modal-body bg-white">
                    <form id="formEditVehiculo" name="formEditVehiculo" method="post" novalidate>
                        <div class="p-1">
                            <div class="row">
                                <div class="form-group col-sm-4">
                                    <div class="input-group">
                                        <div class="input-group-text"><i class="fas fa-book"></i></div>
                                        <input type="text" ng-change="buscarPlacaEditar(editnumero)" class="form-control {{formEditVehiculo.editnumero.$touched === true ? numero_existe === false ? 'is-valid' : 'is-invalid' : ''}}" name="editnumero" id="editnumero" placeholder="Numero placa" ng-model="editnumero" minlength="1" maxlength="7" required mayusculastodo>
                                    </div>
                                    <div class="col-md-12" style="font-size: 10px; padding-left: 50px">
                                        <strong class="text-danger stretched-link text-right" ng-show="numero_existe === true" role="alert">
                                            Placa ya registrada
                                        </strong>
                                    </div>
                                </div>
                                <div class="form-group col-sm-4">
                                    <div class="input-group">
                                        <div class="input-group-text"><i class="fas fa-id-card-alt"></i></div>
                                        <input type="text" ng-change="buscarRifEdit()" class="form-control {{formEditVehiculo.editrif.$touched === true ? editrif.length > 9 && idcliente > 0? 'is-valid' : 'is-invalid' : ''}}" name="editrif" id="editrif" ui-mask="A-99999999-9" ui-mask-placeholder placeholder="Numero de Rif" ng-model="editrif" required mayusculastodo>
                                    </div>
                                    <div class="col-md-12" style="font-size: 10px; padding-left: 50px">
                                        <strong class="text-danger stretched-link text-right" ng-show="editrif.length > 9 && idcliente == 0" role="alert">
                                            Contribuyente no registrado
                                        </strong>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-sm-6">
                                    <div class="input-group">
                                        <div class="input-group-text"><i class="fas fa-file-signature"></i></div>
                                        <input type="text" class="form-control {{formEditVehiculo.editmarca.$touched === true ? editmarca.length > 1 ? 'is-valid' : 'is-invalid' : ''}}" name="editmarca" id="editmarca" placeholder="Marca del vehiculo" ng-model="editmarca" maxlength="13" required mayusculastodo>
                                    </div>
                                </div>
                                <div class="form-group col-sm-6">
                                    <div class="input-group">
                                        <div class="input-group-text"><i class="fas fa-map-marker-alt"></i></div>
                                        <input type="text" class="form-control {{formEditVehiculo.editmodelo.$touched === true ? editmodelo.length > 1 ? 'is-valid' : 'is-invalid' : ''}}" name="editmodelo" id="editmodelo" placeholder="Modelo" ng-model="editmodelo" maxlength="20" required mayusculastodo>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-sm-6">
                                    <div class="input-group">
                                        <div class="input-group-text"><i class="fas fa-user-tag"></i></div>
                                        <input type="text" class="form-control {{formEditVehiculo.editanno.$touched === true ? editanno > 1900 ? 'is-valid' : 'is-invalid' : ''}}" name="editanno" id="editanno" placeholder="Año" ng-model="editanno" maxlength="4" required>
                                    </div>
                                </div>
                                <div class="form-group col-sm-6">
                                    <div class="input-group">
                                        <div class="input-group-text"><i class="far fa-id-card"></i></div>
                                        <input type="text" class="form-control {{formEditVehiculo.editcolor.$touched === true ? editcolor.length > 1 ? 'is-valid' : 'is-invalid' : ''}}" name="editcolor" id="editcolor" placeholder="Color" ng-model="editcolor" maxlength="20" required mayusculastodo>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <!-- Modal footer -->
                <div class="modal-footer justify-content-center">
                    <button type="button" id="modificarvehiculo" class="btn btn-outline-primary waves-effect" ng-click="modificarVehiculo(formEditVehiculo)"><i class="fas fa-cloud-upload-alt prefix grey-text mr-1"></i> Guardar Cambios</button>
                </div>
            </div>
        </div>
    </div>
    <!--  FIN MODAL EDITAR -->
    <div class="row">
        <div class="titulo col-md-12 mb-3">
            <h3>Gestión de Vehiculos</h3>
        </div>
        <div class="buscador col-md-8 mb-3">
            <div class="input-group">
                <input type="text" class="form-control" ng-model="busqueda" ng-keydown="$event.keyCode===13 && buscarPatente(busqueda, filtrar)">
                <div class="input-group-append">
                    <div class="input-group-append ml-1">
                        <button class="btn btn-outline-secondary" type="button" id="button-addon2" ng-click="buscarVehiculo(busqueda, filtrar)"><i class="fas fa-search"></i></button>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4 text-right mb-3">
            <a href="" class="btn btn-outline-primary btn-rounded btn-sm font-weight-bold" data-toggle="modal" data-target="#myModalVehiculos" data-backdrop="static" data-keyboard="false" ng-click="listarActividades();eliminarTemporalCarga()"><i class="fas fa-plus-circle"></i> Agregar Vehiculo</a>
        </div>
        <diw class="row ml-3">
            Opciones de Busqueda:
            <div class="form-check ml-3">
                <label class="form-check-label">
                    <input checked="" type="radio" class="form-check-input" name="optradio" value="1" ng-model="filtrar">Número de Placa
                </label>
            </div>
            <div class="form-check ml-3">
                <label class="form-check-label">
                    <input type="radio" class="form-check-input" name="optradio" ng-model="filtrar" value="2">Número de Rif
                </label>
            </div>
            <div class="form-check ml-3">
                <label class="form-check-label">
                    <input type="radio" class="form-check-input" name="optradio" ng-model="filtrar" value="3">Nombre o Razón Social
                </label>
            </div>
        </diw>
    </div>
    <table class="table table-hover table-sm table-responsive-sm">
        <thead class="thead-dark">
            <tr>
                <th scope="col">Item</th>
                <th scope="col">Placa</th>
                <th scope="col">Nombre/Razón Social</th>
                <th scope="col">Rif</th>
                <th scope="col">Marca</th>
                <th scope="col">Modelo</th>
                <th scope="col">Año</th>
                <th scope="col">Color</th>
                <th scope="col">Acciones</th>
            </tr>
        </thead>
        <tbody>
            <tr ng-show="vehiculos.length == 0 && iniciobuscar == true">
                <td colspan="9" align="center">NO HAY REGISTROS QUE MOSTRAR</td>
            </tr>
            <tr ng-repeat="x in vehiculos | limitTo: 20">
                <th scope="row" align="center">{{$index + 1}}</th>
                <td align="center">{{x.numero}}</td>
                <td>{{x.nombre}}</td>
                <td align="center">{{x.rif}}</td>
                <td align="center">{{x.marca}}</td>
                <td align="center">{{x.modelo}}</td>
                <td align="center">{{x.anno}}</td>
                <td align="center">{{x.color}}</td>
                <td align="center">
                    <button type="button" class="btn btn-outline-success blue light-3 btn-sm" data-toggle="modal" data-target="#myModalEditVehiculo" ng-click="cargarEditarVehiculo(x.id)" data-backdrop="static" data-keyboard="false"><i class="fas fa-edit"></i></button>
                    <button type="button" class="btn btn-outline-danger btn-sm" ng-click="eliminarVehiculo(x.id)"><i class="fas fa-trash-alt"></i></button>
                </td>
            </tr>
        </tbody>
    </table>
</div>