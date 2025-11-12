<?php
session_start();
include_once "../../conexion.php";

if ($_SESSION['VERIFICADO'] != "SI") {
  header("Location: ../validacion.php?opcion=val");
  exit();
}

$acceso = 48;
//------- VALIDACION ACCESO USUARIO
include_once "../../validacion_usuario.php";
//-----------------------------------
?>
<div class="container" ng-controller="contribuyenteController">
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
  <div class="modal fade bd-example-modal-lg" id="myModalContribuyente">
    <div class="modal-dialog modal-dialog-centered modal-lg">
      <div class="modal-content">

        <!-- Modal Header -->
        <div class="modal-header bg-fondo text-center">
          <h4 class="modal-title w-100 font-weight-bold py-2">Datos del Proveedor a Incluir</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close" ng-click="resetForm(formContribuyente)">
            <span aria-hidden="true" class="white-text">&times;</span>
          </button>
        </div>

        <!-- Modal body -->
        <div class="modal-body bg-white">
          <form id="formContribuyente" name="formContribuyente" method="post" novalidate>
            <div class="p-1">

              <div class="row">

                <div class="form-group col-sm-12">
                  <div class="input-group">
                    <div class="input-group-text"><i class="far fa-credit-card"></i></div>
                    <input type="text" ng-change="buscarRif()" class="form-control {{formContribuyente.rif.$touched === true ? contribuyente.rif.length > 9 && idcliente == 0? 'is-valid' : 'is-invalid' : ''}}" name="rif" id="rif" ui-mask="A-99999999-9" ui-mask-placeholder placeholder="Numero de Rif" ng-model="contribuyente.rif" required mayusculastodo>
                  </div>
                  <div class="col-md-12" style="font-size: 10px; padding-left: 50px">
                    <strong class="text-danger stretched-link text-right" ng-show="contribuyente.rif.length > 9 && idcliente > 0" role="alert">
                      Proveedor ya registrado
                    </strong>
                  </div>
                </div>

              </div>

              <div class="row">

                <div class="form-group col-sm-12">
                  <div class="input-group">
                    <div class="input-group-text"><i class="fas fa-file-signature"></i></div>
                    <input type="text" class="form-control {{formContribuyente.nombre.$touched === true ? contribuyente.nombre.length > 3 ? 'is-valid' : 'is-invalid' : ''}}" name="nombre" id="nombre" onblur="copia_cont();" placeholder="Nombre o Razon Social" ng-model="contribuyente.nombre" maxlength="150" required mayusculastodo>
                  </div>
                </div>

              </div>

              <div class="row">

                <div class="form-group col-sm-6">
                  <div class="input-group">
                    <div class="input-group-text"><i class="fas fa-grip-horizontal"></i></div>
                    <select class="form-control {{formContribuyente.estado.$touched === true ? contribuyente.estado > 0? 'is-valid' : 'is-invalid' : ''}}" name="estado" id="estado" ng-model="contribuyente.estado" ng-change="listarCiudades()">
                      <option ng-repeat="estado in estados" ng-value="estado.id" ng-bind="estado.descripcion"></option>
                    </select required>
                  </div>
                </div>

                <div class="form-group col-sm-6">
                  <div class="input-group">
                    <div class="input-group-text"><i class="far fa-building"></i></div>
                    <select class="form-control {{formContribuyente.ciudad.$touched === true ? contribuyente.ciudad > 0? 'is-valid' : 'is-invalid' : ''}}" name="ciudad" id="ciudad" ng-model="contribuyente.ciudad">
                      <option ng-repeat="ciudad in ciudades" ng-value="ciudad.id" ng-bind="ciudad.descripcion"></option>
                    </select required>
                  </div>
                </div>

              </div>

              <div class="row">

                <div class="form-group col-sm-4" style="display: none;">
                  <div class="input-group">
                    <div class="input-group-text"><i class="fas fa-hotel"></i></div>
                    <select class="form-control {{formContribuyente.zona.$touched === true ? contribuyente.zona > 0? 'is-valid' : 'is-invalid' : ''}}" name="zona" id="zona" ng-model="contribuyente.zona">
                      <option value="1">CORREGIR ZONA Y DIRECCION</option>
                    </select required>
                  </div>
                </div>

                <div class="form-group col-sm-12">
                  <div class="input-group">
                    <div class="input-group-text"><i class="fas fa-map-marker-alt"></i></div>
                    <input type="text" class="form-control {{formContribuyente.direccion.$touched === true ? contribuyente.direccion.length > 3 ? 'is-valid' : 'is-invalid' : ''}}" name="direccion" id="direccion" placeholder="Direccion o domicilio" ng-model="contribuyente.direccion" maxlength="150" required mayusculastodo>
                  </div>
                </div>

              </div>

              <div class="row">

                <div class="form-group col-sm-6">
                  <div class="input-group">
                    <div class="input-group-text"><i class="fas fa-user-tag"></i></div>
                    <input type="text" class="form-control {{formContribuyente.representante.$touched === true ? contribuyente.representante.length > 3 ? 'is-valid' : 'is-invalid' : ''}}" name="representante" id="representante" placeholder="Nombre del Representante" ng-model="contribuyente.representante" maxlength="150" required mayusculastodo>
                  </div>
                </div>

                <div class="form-group col-sm-6">
                  <div class="input-group">
                    <div class="input-group-text"><i class="far fa-id-card"></i></div>
                    <input type="text" class="form-control {{formContribuyente.cedula.$touched === true ? contribuyente.cedula.length > 3 ? 'is-valid' : 'is-invalid' : ''}}" name="cedula" id="cedula" ui-mask="A-9999999?9" ui-mask-placeholder placeholder="Cedula Representante" ng-model="contribuyente.cedula" maxlength="10" required mayusculastodo>
                  </div>
                </div>

              </div>

              <div class="row">

                <div class="form-group col-sm-6">
                  <div class="input-group">
                    <div class="input-group-text"><i class="fas fa-phone"></i></div>
                    <input type="text" class="form-control {{formContribuyente.celular.$touched === true ? contribuyente.celular.length > 3 ? 'is-valid' : 'is-invalid' : ''}}" name="celular" id="celular" ui-mask="9999-9999999" ui-mask-placeholder placeholder="Cel Contacto" ng-model="contribuyente.celular" maxlength="12" required>
                  </div>
                </div>

                <div class="form-group col-sm-6">
                  <div class="input-group">
                    <div class="input-group-text"><i class="far fa-envelope-open"></i></div>
                    <input type="email" class="form-control {{formContribuyente.correo.$touched === true ? formContribuyente.correo.$error.required === true || formContribuyente.correo.$error.pattern === true ? 'is-invalid' : 'is-valid' : ''}}" placeholder="Correo Electrónico" ng-pattern='/^(([^<>()\[\]\.,;:\s@\"]+(\.[^<>()\[\]\.,;:\s@\"]+)*)|(\".+\"))@(([^<>()[\]\.,;:\s@\"]+\.)+[^<>()[\]\.,;:\s@\"]{2,})$/i' name="correo" id="correo" ng-model="contribuyente.email" required mayusculastodo>
                  </div>
                </div>

              </div>
            </div>
          </form>

        </div>

        <!-- Modal footer -->
        <div class="modal-footer justify-content-center">
          <button id="agregarcontribuyente" type="button" class="btn btn-outline-primary waves-effect" ng-click="agregarContribuyente(formContribuyente)"><i class="fas fa-cloud-upload-alt prefix grey-text mr-1"></i> Guardar</button>
        </div>

      </div>
    </div>
  </div>
  <!--  FIN MODAL AGREGAR -->

  <!-- Modal Editar -->
  <div class="modal fade bd-example-modal-lg" id="myModalEditContribuyente">
    <div class="modal-dialog modal-dialog-centered modal-lg">
      <div class="modal-content">

        <!-- Modal Header -->
        <div class="modal-header bg-fondo text-center">
          <h4 class="modal-title w-100 font-weight-bold py-2">Datos del Proveedor a Modificar</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close" ng-click="resetFormEditar(formEditContribuyente)">
            <span aria-hidden="true" class="white-text">&times;</span>
          </button>
        </div>

        <!-- Modal body -->
        <div class="modal-body bg-white">
          <form id="formEditContribuyente" name="formEditContribuyente" method="post" novalidate>
            <div class="p-1">

              <div class="row">

                <div class="form-group col-sm-12">
                  <div class="input-group">
                    <div class="input-group-text"><i class="far fa-credit-card"></i></div>
                    <input type="text" ng-change="buscarRifEdit()" class="form-control {{formEditContribuyente.editrif.$touched === true ? editrif.length > 9 && idcliente == 0? 'is-valid' : 'is-invalid' : ''}}" name="editrif" id="editrif" ui-mask="A-99999999-9" ui-mask-placeholder placeholder="Numero de Rif" ng-model="editrif" required mayusculastodo>
                  </div>
                  <div class="col-md-12" style="font-size: 10px; padding-left: 50px">
                    <strong class="text-danger stretched-link text-right" ng-show="editrif.length > 9 && idcliente > 0" role="alert">
                      Contribuyente ya registrado
                    </strong>
                  </div>
                </div>

              </div>

              <div class="row">

                <div class="form-group col-sm-12">
                  <div class="input-group">
                    <div class="input-group-text"><i class="fas fa-file-signature"></i></div>
                    <input type="text" class="form-control {{formEditContribuyente.editnombre.$touched === true ? editnombre.length > 3 ? 'is-valid' : 'is-invalid' : ''}}" name="editnombre" id="editnombre" placeholder="Nombre o Razon Social" ng-model="editnombre" maxlength="150" required mayusculastodo>
                  </div>
                </div>

              </div>

              <div class="row">

                <div class="form-group col-sm-6">
                  <div class="input-group">
                    <div class="input-group-text"><i class="fas fa-grip-horizontal"></i></div>
                    <select class="form-control {{formContribuyente.editestado.$touched === true ? editestado > 0? 'is-valid' : 'is-invalid' : ''}}" name="editestado" id="editestado" ng-model="editestado" ng-change="listarCiudadesEdit()">
                      <option ng-repeat="estado in estados" ng-value="estado.id" ng-bind="estado.descripcion"></option>
                    </select required>
                  </div>
                </div>

                <div class="form-group col-sm-6">
                  <div class="input-group">
                    <div class="input-group-text"><i class="far fa-building"></i></div>
                    <select class="form-control {{formContribuyente.editciudad.$touched === true ? editciudad > 0? 'is-valid' : 'is-invalid' : ''}}" name="editciudad" id="editciudad" ng-model="editciudad">
                      <option ng-repeat="ciudad in ciudades" ng-value="ciudad.id" ng-bind="ciudad.descripcion"></option>
                    </select required>
                  </div>
                </div>

              </div>

              <div class="row">

                <div class="form-group col-sm-4" style="display:none;">
                  <div class="input-group">
                    <div class="input-group-text"><i class="fas fa-hotel"></i></div>
                    <select class="form-control {{formContribuyente.editzona.$touched === true ? editzona > 0? 'is-valid' : 'is-invalid' : ''}}" name="editzona" id="editzona" ng-model="editzona">
                      <option value="1">CORREGIR ZONA Y DIRECCION</option>
                    </select required>
                  </div>
                </div>

                <div class="form-group col-sm-12">
                  <div class="input-group">
                    <div class="input-group-text"><i class="fas fa-map-marker-alt"></i></div>
                    <input type="text" class="form-control {{formEditContribuyente.editdomicilio.$touched === true ? editdomicilio.length > 3 ? 'is-valid' : 'is-invalid' : ''}}" name="editdomicilio" id="editdomicilio" placeholder="Direccion o domicilio" ng-model="editdomicilio" maxlength="150" required mayusculastodo>
                  </div>
                </div>

              </div>

              <div class="row">

                <div class="form-group col-sm-6">
                  <div class="input-group">
                    <div class="input-group-text"><i class="fas fa-user-tag"></i></div>
                    <input type="text" class="form-control {{formEditContribuyente.editrepresentante.$touched === true ? editrepresentante.length > 3 ? 'is-valid' : 'is-invalid' : ''}}" name="editrepresentante" id="editrepresentante" placeholder="Nombre del Representante" ng-model="editrepresentante" maxlength="150" required mayusculastodo>
                  </div>
                </div>

                <div class="form-group col-sm-6">
                  <div class="input-group">
                    <div class="input-group-text"><i class="far fa-id-card"></i></div>
                    <input type="text" class="form-control {{formEditContribuyente.ced_representante.$touched === true ? 'is-valid' : ''}}" name="ced_representante" id="ced_representante" ui-mask="A-9999999?9" ui-mask-placeholder placeholder="Cedula Representante" ng-model="editced_representante" minlength="4" maxlength="10" required mayusculastodo>
                  </div>
                </div>

              </div>

              <div class="row">

                <div class="form-group col-sm-6">
                  <div class="input-group">
                    <div class="input-group-text"><i class="fas fa-phone"></i></div>
                    <input type="text" class="form-control {{formEditContribuyente.cel_contacto.$touched === true ? editcel_contacto.length > 3 ? 'is-valid' : 'is-invalid' : ''}}" name="cel_contacto" id="cel_contacto" ui-mask="9999-9999999" ui-mask-placeholder placeholder="Cel Contacto" ng-model="editcel_contacto" maxlength="12" required>
                  </div>
                </div>

                <div class="form-group col-sm-6">
                  <div class="input-group">
                    <div class="input-group-text"><i class="far fa-envelope-open"></i></div>
                    <input type="email" class="form-control {{formEditContribuyente.editemail.$touched === true ? editemail.$error.required === true || editemail.$error.pattern === true ? 'is-invalid' : 'is-valid' : ''}}" placeholder="Correo Electrónico" ng-pattern='/^(([^<>()\[\]\.,;:\s@\"]+(\.[^<>()\[\]\.,;:\s@\"]+)*)|(\".+\"))@(([^<>()[\]\.,;:\s@\"]+\.)+[^<>()[\]\.,;:\s@\"]{2,})$/i' name="editemail" id="editemail" ng-model="editemail" required mayusculastodo>
                  </div>
                </div>

              </div>
            </div>
          </form>

        </div>

        <!-- Modal footer -->
        <div class="modal-footer justify-content-center">
          <button id="modificarcontribuyente" type="button" class="btn btn-outline-primary waves-effect" ng-click="modificarContribuyente(formEditContribuyente)"><i class="fas fa-cloud-upload-alt prefix grey-text mr-1"></i> Guardar Cambios</button>
        </div>

      </div>
    </div>
  </div>
  <!-- FIN MODAL EDITAR -->

  <div class="row">
    <div class="titulo col-md-12 mb-3">
      <h3>Gestión de Contribuyente</h3>
    </div>
    <div class="buscador col-md-8 mb-3">
      <div class="input-group">
        <div class="input-group-prepend">
          <span class="input-group-text"><i class="fas fa-search"></i></span>
        </div>
        <input type="text" class="form-control" ng-model="busqueda">
      </div>
    </div>
    <div class="col-md-4 text-right mb-3">
      <a href="" class="btn btn-outline-primary btn-rounded btn-sm font-weight-bold" data-toggle="modal" data-target="#myModalContribuyente" ng-click="listarEstados()"><i class="fas fa-plus-circle"></i> Agregar Contribuyente</a>
    </div>
  </div>
  <table class="table table-hover table-sm table-responsive-sm">
    <thead class="thead-dark">
      <tr>
        <th scope="col">Rif</th>
        <th scope="col">Nombre</th>
        <th scope="col">Representante</th>
        <th scope="col">Cel Contacto</th>
        <th scope="col">Email</th>
        <th scope="col">Acciones</th>
      </tr>
    </thead>
    <tbody>
      <tr ng-repeat="x in contribuyentes | filter:busqueda | limitTo: 20">
        <th scope="row" align="center">{{x.rif}}</th>
        <td>{{x.nombre}}</td>
        <td>{{x.representante}}</td>
        <td>{{x.cel_contacto}}</td>
        <td>{{x.email}}</td>
        <td align="center">
          <button type="button" class="btn btn-outline-success blue light-3 btn-sm" data-toggle="modal" data-target="#myModalEditContribuyente" ng-click="cargarEditarContribuyente(x.id)" data-backdrop="static" data-keyboard="false"><i class="fas fa-edit"></i></button>
          <button type="button" class="btn btn-outline-danger btn-sm" ng-click="eliminarContribuyente(x.id)"><i class="fas fa-trash-alt"></i></button>
        </td>
      </tr>
    </tbody>
  </table>

</div>