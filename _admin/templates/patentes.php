<?php
session_start();
include_once "../../conexion.php";

if ($_SESSION['VERIFICADO'] != "SI") { 
header ("Location: ../validacion.php?opcion=val"); 
exit(); }

$acceso=28;
//------- VALIDACION ACCESO USUARIO
include_once "../../validacion_usuario.php";
//-----------------------------------
?>
<div class="container" ng-controller="patentesController">
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
    <div class="modal fade bd-example-modal-lg" id="myModalPatente">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header texto_blanco" style="background-color: #0275d8;text-align:center;">
                    <h4 class="modal-title w-90 font-weight-bold py-2">Datos de la Patente a Incluir</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" ng-click="resetForm(formPatente)">
                        <span aria-hidden="true" class="white-text">&times;</span>
                    </button>
                </div>
                <!-- Modal body -->
                <div class="modal-body bg-white">
                    <form id="formPatente" name="formPatente" method="post" novalidate>
                        <div class="p-1">
                            <div class="row">
                                <div class="form-group col-sm-4">
                                    <div class="input-group">
                                        <div class="input-group-text"><i class="fas fa-book"></i></div>
                                        <input type="text" ng-change="buscarNumero(patente.numero)" class="form-control {{formPatente.numero.$touched === true ? numero_existe === false ? 'is-valid' : 'is-invalid' : ''}}" name="numero" id="numero" placeholder="Numero patente" ng-model="patente.numero" minlength="1" maxlength="8" required mayusculastodo>
                                    </div>
                                    <div class="col-md-12" style="font-size: 10px; padding-left: 50px">
                                        <strong class="text-danger stretched-link text-right" ng-show="numero_existe === true" role="alert">
                                            Patente ya registrada
                                        </strong>
                                    </div>
                                </div>
                                <div class="form-group col-sm-4">
                                    <div class="input-group">
                                        <div class="input-group-text"><i class="fas fa-id-card-alt"></i></div>
                                        <input type="text" ng-change="buscarRif()" class="form-control {{formPatente.rif.$touched === true ? patente.rif.length > 9 && idcliente > 0? 'is-valid' : 'is-invalid' : ''}}" name="rif" id="rif" ui-mask="A-99999999-9" ui-mask-placeholder placeholder="Numero de Rif" ng-model="patente.rif" required mayusculastodo>
                                    </div>
                                    <div class="col-md-12" style="font-size: 10px; padding-left: 50px">
                                        <strong class="text-danger stretched-link text-right" ng-show="patente.rif.length > 9 && idcliente == 0" role="alert">
                                            Contribuyente no registrado
                                        </strong>
                                    </div>
                                </div>
                                <div class="form-group col-sm-4">
                                    <div class="input-group">
                                        <div class="input-group-text"><i class="far fa-calendar-alt"></i></div>
                                        <input type="text" class="form-control  {{formPatente.fecha.$touched === true ? patente.fecha.length > 9 ? 'is-valid' : 'is-invalid' : ''}}" name="fecha" id="fecha" placeholder="Fecha registro" ng-model="patente.fecha" minlength="10" maxlength="10" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-sm-6">
                                    <div class="input-group">
                                        <div class="input-group-text"><i class="fas fa-file-signature"></i></div>
                                        <input type="text" class="form-control  {{formPatente.descripcion.$touched === true ? patente.descripcion.length > 4 ? 'is-valid' : 'is-invalid' : ''}}" name="descripcion" id="descripcion" placeholder="Nombre del establecimiento" ng-model="patente.descripcion" minlength="5" maxlength="150" required mayusculastodo>
                                    </div>
                                </div>
                                <div class="form-group col-sm-6">
                                    <div class="input-group">
                                        <div class="input-group-text"><i class="fas fa-map-marker-alt"></i></div>
                                        <input type="text" class="form-control {{formPatente.direccion.$touched === true ? patente.direccion.length > 9 ? 'is-valid' : 'is-invalid' : ''}}" name="direccion" id="direccion" placeholder="Dirección del establecimiento" ng-model="patente.direccion" minlength="10" maxlength="150" required mayusculastodo>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-sm-6">
                                    <div class="input-group">
                                        <div class="input-group-text"><i class="fas fa-user-tag"></i></div>
                                        <input type="text" class="form-control  {{formPatente.representante.$touched === true ? patente.representante.length > 0 ? 'is-valid' : 'is-invalid' : ''}}" name="representante" id="representante" placeholder="Representante" ng-model="patente.representante" minlength="1" maxlength="150" required mayusculastodo>
                                    </div>
                                </div>
                                <div class="form-group col-sm-6">
                                    <div class="input-group">
                                        <div class="input-group-text"><i class="far fa-id-card"></i></div>
                                        <input type="text" class="form-control {{formPatente.cedula.$touched === true ? patente.cedula.length > 7 ? 'is-valid' : 'is-invalid' : ''}}" name="cedula" id="cedula" ui-mask="A-9999999?9" ui-mask-placeholder placeholder="Cedula representante" ng-model="patente.cedula" minlength="8" maxlength="10" required mayusculastodo>
                                    </div>
                                </div>
                            </div>
                            <!--<div class="row">



            <div class="form-group col-sm-4">

              <div class="input-group">

                <div class="input-group-text"><i class="far fa-calendar-alt"></i></div>

                <input type="text" class="form-control {{formPatente.vencimiento.$touched === true ? patente.vencimiento.length > 9 ? 'is-valid' : 'is-invalid' : ''}}" name="vencimiento" id="vencimiento" placeholder="Vencimiento" ng-model="patente.vencimiento" minlength="10" maxlength="10" required>

              </div>

            </div>

  

            <div class="form-group col-sm-4">

              <div class="input-group">

                <div class="input-group-text"><i class="fas fa-user"></i></div>

                <input type="text" class="form-control {{formPatente.obreros.$touched === true ? patente.obreros >= 0 ? 'is-valid' : 'is-invalid' : ''}}" name="obreros" id="obreros" placeholder="Nro de Obreros" ng-model="patente.obreros" minlength="1" maxlength="4" required entero>

              </div>

            </div>



            <div class="form-group col-sm-4">

              <div class="input-group">

                <div class="input-group-text"><i class="fas fa-user-tie"></i></div>

                <input type="text" class="form-control {{formPatente.empleados.$touched === true ? patente.empleados >= 0 ? 'is-valid' : 'is-invalid' : ''}}" name="empleados" id="empleados" placeholder="Nro de Empleados" ng-model="patente.empleados" minlength="1" maxlength="4" required entero>

              </div>

            </div>



          </div>

           

          <div class="row">



            <div class="form-group col-sm-4">

              <div class="input-group">

                <div class="input-group-text"><i class="fas fa-stopwatch"></i></div>

                <select name="turno" class="custom-select {{formPatente.turno.$touched === true ? patente.turnos > 0 ? 'is-valid' : 'is-invalid' : ''}}" ng-model="patente.turnos">

                  <option selected>Turnos</option>

                  <option value="1">1</option>

                  <option value="2">2</option>

                  <option value="3">3</option>

                  <option value="4">4</option>

                </select>

              </div>

            </div>



            <div class="form-group col-sm-4">

              <div class="input-group">

                <div class="input-group-text"><i class="far fa-sun"></i></div>

                <input type="text" class="form-control {{formPatente.manana.$touched === true ? patente.manana.length > 10 ? 'is-valid' : 'is-invalid' : ''}}" name="manana" id="manana" modelViewValue="false" ui-mask="99:99 AA - 99:99 AA" ui-mask-placeholder ng-model="patente.manana"  minlength="18" maxlength="25" required mayusculastodo>

              </div>

            </div>



            <div class="form-group col-sm-4">

              <div class="input-group">

                <div class="input-group-text"><i class="fas fa-sun"></i></div>

                <input type="text" class="form-control {{formPatente.tarde.$touched === true ? patente.tarde.length > 10 ? 'is-valid' : 'is-invalid' : ''}}" name="tarde" id="tarde"  ui-mask="99:99 AA - 99:99 AA" ui-mask-placeholder ng-model="patente.tarde"  minlength="18" maxlength="25" required mayusculastodo>

              </div>

            </div>

          

          </div>



          <div class="row">

          

            <div class="form-group col-sm-4">

              <div class="input-group">

                <div class="input-group-text"><i class="fas fa-moon"></i></div>

                <select name="nocturno" class="custom-select {{formPatente.nocturno.$touched === true ? patente.nocturnos > -1 ? 'is-valid' : 'is-invalid' : ''}}" ng-model="patente.nocturnos">

                  <option selected>Nocturno</option>

                  <option value="1">Si</option>

                  <option value="0">No</option>

                </select>

              </div>

            </div>



            <div class="form-group col-sm-4">

              <div class="input-group">

                <div class="input-group-text"><i class="fas fa-headset"></i></div>

                <select name="talento" class="custom-select {{formPatente.talento.$touched === true ? patente.talento_vivo > -1 ? 'is-valid' : 'is-invalid' : ''}}" ng-model="patente.talento_vivo">

                  <option selected>Talento vivo</option>

                  <option value="1">Si</option>

                  <option value="0">No</option>

                </select>

              </div>

            </div>



            <div class="form-group col-sm-4">

              <div class="input-group">

                <div class="input-group-text"><i class="fas fa-cash-register"></i></div>

                <select name="rockola" class="custom-select {{formPatente.rockola.$touched === true ? patente.rockola > -1 ? 'is-valid' : 'is-invalid' : ''}}" ng-model="patente.rockola">

                  <option selected>Rockola</option>

                  <option value="1">Si</option>

                  <option value="0">No</option>

                </select>

              </div>

            </div>



            <div class="form-group col-sm-12">

              <div class="input-group">

                <div class="input-group-text"><i class="fas fa-asterisk"></i></div>

                <input type="text" class="form-control" name="otro" id="otro" placeholder="Otros" ng-model="patente.otro" maxlength="50" mayusculastodo>

              </div>

            </div>

          </div>-->
                            <div class="row">
                                <div class="form-group col-sm-12">
                                    Actividad Económica:
                                </div>
                                <div class="form-group col-sm-8">
                                    <div class="input-group">
                                        <select name="actividad" class="custom-select" ng-model="actividad_tmp_id" style="font-size: 12px">
                                            <option ng-repeat="x in actividades" value="{{x.id}}">{{x.codigo}} - {{x.descripcion | limitTo: 100}}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group col-sm-4">
                                    <button type="button" class="btn btn-outline-primary waves-effect" ng-click="agregarActividadTmp()"><i class="fas fa-plus prefix grey-text mr-1"></i> Agregar Actividad</button>
                                </div>
                            </div>
                            <div class="col-sm-12 table-responsive-md">
                                <table class="table table-sm">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th scope="col">Codigo</th>
                                            <th scope="col">Descripcion</th>
                                            <th scope="col">Accion</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr ng-repeat="x in actividades_tmp">
                                            <th scope="row">{{x.codigo}}</th>
                                            <td>{{x.descripcion}}</td>
                                            <td align="center"><button type="button" class="btn btn-outline-danger btn-sm" ng-click="eliminarActividadTmp($index,x.id_actividad,patente.numero)"><i class="fas fa-trash-alt"></i></button></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </form>
                </div>
                <!-- Modal footer -->
                <div class="modal-footer justify-content-center">
                    <button type="button" id="agregarpatente" class="btn btn-outline-primary waves-effect" ng-click="agregarPatente(formPatente)"><i class="fas fa-cloud-upload-alt prefix grey-text mr-1"></i> Guardar</button>
                </div>
            </div>
        </div>
    </div>
    <!--  FIN MODAL AGREGAR -->
	
    <!-- Modal EDITAR -->
    <div class="modal fade bd-example-modal-lg" id="myModalEditPatente">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header texto_blanco" style="background-color: #0275d8;text-align:center;">
                    <h4 class="modal-title w-90 font-weight-bold py-2">Datos de la Patente a Editar</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" ng-click="resetFormEditar(formEditPatente)">
                        <span aria-hidden="true" class="white-text">&times;</span>
                    </button>
                </div>
                <!-- Modal body -->
                <div class="modal-body bg-white">
                    <form id="formEditPatente" name="formEditPatente" method="post" novalidate>
                        <div class="p-1">
                            <div class="row">
                                <div class="form-group col-sm-4">
                                    <div class="input-group">
                                        <div class="input-group-text"><i class="fas fa-book"></i></div>
                                        <input type="text" ng-change="buscarNumeroEditar(editnumero)" class="form-control {{formEditPatente.editnumero.$touched === true ? numero_existe === false ? 'is-valid' : 'is-invalid' : ''}}" name="editnumero" id="editnumero" placeholder="Numero patente" ng-model="editnumero" minlength="1" maxlength="8" required mayusculastodo>
                                    </div>
                                    <div class="col-md-12" style="font-size: 10px; padding-left: 50px">
                                        <strong class="text-danger stretched-link text-right" ng-show="numero_existe === true" role="alert">
                                            Patente ya registrada
                                        </strong>
                                    </div>
                                </div>
                                <div class="form-group col-sm-4">
                                    <div class="input-group">
                                        <div class="input-group-text"><i class="fas fa-id-card-alt"></i></div>
                                        <input type="text" ng-change="buscarRifEdit()" class="form-control {{formEditPatente.editrif.$touched === true ? editrif.length > 9 && idcliente > 0? 'is-valid' : 'is-invalid' : ''}}" name="editrif" id="editrif" ui-mask="A-99999999-9" ui-mask-placeholder placeholder="Numero de Rif" ng-model="editrif" required mayusculastodo>
                                    </div>
                                    <div class="col-md-12" style="font-size: 10px; padding-left: 50px">
                                        <strong class="text-danger stretched-link text-right" ng-show="editrif.length > 9 && idcliente == 0" role="alert">
                                            Contribuyente no registrado
                                        </strong>
                                    </div>
                                </div>
                                <div class="form-group col-sm-4">
                                    <div class="input-group">
                                        <div class="input-group-text"><i class="far fa-calendar-alt"></i></div>
                                        <input type="text" class="form-control  {{formEditPatente.editfecha.$touched === true ? editfecha.length > 9 ? 'is-valid' : 'is-invalid' : ''}}" name="editfecha" id="editfecha" placeholder="Fecha registro" ng-model="editfecha" minlength="10" maxlength="10" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-sm-6">
                                    <div class="input-group">
                                        <div class="input-group-text"><i class="fas fa-file-signature"></i></div>
                                        <input type="text" class="form-control {{formEditPatente.editdescripcion.$touched === true ? editdescripcion.length > 4 ? 'is-valid' : 'is-invalid' : ''}}" name="editdescripcion" id="editdescripcion" placeholder="Nombre del establecimiento" ng-model="editdescripcion" minlength="5" maxlength="150" required mayusculastodo>
                                    </div>
                                </div>
                                <div class="form-group col-sm-6">
                                    <div class="input-group">
                                        <div class="input-group-text"><i class="fas fa-map-marker-alt"></i></div>
                                        <input type="text" class="form-control {{formEditPatente.editdireccion.$touched === true ? editdireccion.length > 9 ? 'is-valid' : 'is-invalid' : ''}}" name="editdireccion" id="editdireccion" placeholder="Dirección del establecimiento" ng-model="editdireccion" minlength="10" maxlength="150" required mayusculastodo>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-sm-6">
                                    <div class="input-group">
                                        <div class="input-group-text"><i class="fas fa-user-tag"></i></div>
                                        <input type="text" class="form-control {{formEditPatente.editrepresentante.$touched === true ? editrepresentante.length > 0 ? 'is-valid' : 'is-invalid' : ''}}" name="editrepresentante" id="editrepresentante" placeholder="Representante" ng-model="editrepresentante" minlength="1" maxlength="150" required mayusculastodo>
                                    </div>
                                </div>
                                <div class="form-group col-sm-6">
                                    <div class="input-group">
                                        <div class="input-group-text"><i class="far fa-id-card"></i></div>
                                        <input type="text" class="form-control {{formEditPatente.editcedula.$touched === true ? editcedula.length > 7 ? 'is-valid' : 'is-invalid' : ''}}" name="editcedula" id="editcedula" ui-mask="A-9999999?9" ui-mask-placeholder placeholder="Cedula representante" ng-model="editcedula" minlength="8" maxlength="10" required mayusculastodo>
                                    </div>
                                </div>
                            </div>
             
<div class="row">

	<div class="form-group col-sm-3">
		<div class="input-group">
		<div class="input-group-text"><i class="fas fa-stream"></i></div>
		<select name="editestatu" class="custom-select {{formEditPatente.editestatus.$touched === true ? editestatus > -1 ? 'is-valid' : 'is-invalid' : ''}}" ng-model="editestatus">
		  <option ng-selected="editestatus == 0" value="0">Activa</option>
		  <option ng-selected="editestatus == 2" value="2">Anulada</option>
		  <option ng-selected="editestatus == 3" value="3">Cierre Temporal</option>
		  <option ng-selected="editestatus == 4" value="4">Cierre Definitivo</option>
		  <option ng-selected="editestatus == 5" value="5">Bloqueada</option>
		  <option ng-selected="editestatus == 6" value="6">Archivo Muerto</option>
		</select>
		</div>
	</div>

	<div class="form-group col-sm-3">	
		<div class="input-group">		
		<div class="input-group-text"><i class="far fa-calendar-alt"></i></div>		
		<input type="text" class="form-control {{formEditPatente.editcierre_tmp.$touched === true ? editcierre_tmp.length > 9 ? 'is-valid' : 'is-invalid' : ''}}" name="editcierretmp" id="editcierretmp" placeholder="Cierre Temporal" ng-model="editcierre_tmp" minlength="10" maxlength="10" >		
		</div>	
	</div>

	<div class="form-group col-sm-3">	
		<div class="input-group">		
		<div class="input-group-text"><i class="far fa-calendar-alt"></i></div>		
		<input type="text" class="form-control {{formEditPatente.editcierre_def.$touched === true ? editcierre_def.length > 9 ? 'is-valid' : 'is-invalid' : ''}}" name="editcierredef" id="editcierredef" placeholder="Cierre Definitivo" ng-model="editcierre_def" minlength="10" maxlength="10" >		
		</div>	
	</div>
	
	<div class="form-group col-sm-3">	
		<div class="input-group">		
		<div class="input-group-text"><i class="fas fa-book"></i></div>		
		<input type="text" class="form-control {{formEditPatente.edittarde.$touched === true ? edittarde.length > 0 ? 'is-valid' : 'is-invalid' : ''}}" name="edittarde" id="edittarde" placeholder="Expediente" ng-model="edittarde"  minlength="0" maxlength="10" mayusculastodo>
		</div>	
	</div>
			
</div>			 
			 
			                <!--<div class="row">



            <div class="form-group col-sm-4">

              <div class="input-group">

                <div class="input-group-text"><i class="far fa-calendar-alt"></i></div>

                <input type="text" class="form-control {{formEditPatente.editvencimiento.$touched === true ? editvencimiento.length > 9 ? 'is-valid' : 'is-invalid' : ''}}" name="editvencimiento" id="editvencimiento" placeholder="Vencimiento" ng-model="editvencimiento" minlength="10" maxlength="10" required>

              </div>

            </div>

       

            <div class="form-group col-sm-4">

              <div class="input-group">

                <div class="input-group-text"><i class="fas fa-user"></i></div>

                <input type="text" class="form-control {{formEditPatente.editobreros.$touched === true ? editobreros >= 0 ? 'is-valid' : 'is-invalid' : ''}}" name="editobreros" id="editobreros" placeholder="Nro de Obreros" ng-model="editobreros" minlength="1" maxlength="4" required entero>

              </div>

            </div>



            <div class="form-group col-sm-4">

              <div class="input-group">

                <div class="input-group-text"><i class="fas fa-user-tie"></i></div>

                <input type="text" class="form-control {{formEditPatente.editempleados.$touched === true ? editempleados >= 0 ? 'is-valid' : 'is-invalid' : ''}}" name="editempleados" id="editempleados" placeholder="Nro de Empleados" ng-model="editempleados" minlength="1" maxlength="4" required entero>

              </div>

            </div>



          </div>



          <div class="row">



            <div class="form-group col-sm-4">

              <div class="input-group">

                <div class="input-group-text"><i class="fas fa-stopwatch"></i></div>

                <select name="editturno" class="custom-select {{formEditPatente.editturno.$touched === true ? editturnos > 0 ? 'is-valid' : 'is-invalid' : ''}}" ng-model="editturnos">

                  <option selected>Turnos</option>

                  <option ng-selected="editturnos == 1" value="1">1</option>

                  <option ng-selected="editturnos == 2" value="2">2</option>

                  <option ng-selected="editturnos == 3" value="3">3</option>

                  <option ng-selected="editturnos == 4" value="4">4</option>

                </select>

              </div>

            </div>



            <div class="form-group col-sm-4">

              <div class="input-group">

                <div class="input-group-text"><i class="far fa-sun"></i></div>

                <input type="text" class="form-control {{formEditPatente.editmanana.$touched === true ? editmanana.length > 10 ? 'is-valid' : 'is-invalid' : ''}}" name="editmanana" id="editmanana" ui-mask="99:99 AA - 99:99 AA" ui-mask-placeholder ng-model="editmanana"  minlength="18" maxlength="25" required mayusculastodo>

              </div>

            </div>



            <div class="form-group col-sm-4">

              <div class="input-group">

                <div class="input-group-text"><i class="fas fa-sun"></i></div>

                <input type="text" class="form-control {{formEditPatente.edittarde.$touched === true ? edittarde.length > 10 ? 'is-valid' : 'is-invalid' : ''}}" name="edittarde" id="edittarde" ui-mask="99:99 AA - 99:99 AA" ui-mask-placeholder ng-model="edittarde"  minlength="18" maxlength="25" required mayusculastodo>

              </div>

            </div>

          

          </div>



          <div class="row">

          

            <div class="form-group col-sm-4">

              <div class="input-group">

                <div class="input-group-text"><i class="fas fa-moon"></i></div>

                <select name="editnocturno" class="custom-select {{formEditPatente.editnocturno.$touched === true ? editnocturnos > -1 ? 'is-valid' : 'is-invalid' : ''}}" ng-model="editnocturnos">

                  <option selected>Nocturno</option>

                  <option ng-selected="editnocturnos == 1" value="1">Si</option>

                  <option ng-selected="editnocturnos == 0" value="0">No</option>

                </select>

              </div>

            </div>



            <div class="form-group col-sm-4">

              <div class="input-group">

                <div class="input-group-text"><i class="fas fa-headset"></i></div>

                <select name="edittalento" class="custom-select {{formEditPatente.edittalento.$touched === true ? edittalento_vivo > -1 ? 'is-valid' : 'is-invalid' : ''}}" ng-model="edittalento_vivo">

                  <option selected>Talento vivo</option>

                  <option ng-selected="edittalento_vivo == 1" value="1">Si</option>

                  <option ng-selected="edittalento_vivo == 0" value="0">No</option>

                </select>

              </div>

            </div>



            <div class="form-group col-sm-4">

              <div class="input-group">

                <div class="input-group-text"><i class="fas fa-cash-register"></i></div>

                <select name="editrockola" id="editrockola" class="custom-select {{formEditPatente.editrockola.$touched === true ? editrockola > -1 ? 'is-valid' : 'is-invalid' : ''}}" ng-model="editrockola">

                  <option selected>Rockola</option>

                  <option ng-selected="editrockola == 1" value="1">Si</option>

                  <option ng-selected="editrockola == 0" value="0">No</option>

                </select>

              </div>

            </div>



            <div class="form-group col-sm-12">

              <div class="input-group">

                <div class="input-group-text"><i class="fas fa-asterisk"></i></div>

                <input type="text" class="form-control" name="editotro" id="otro" placeholder="Otros" ng-model="editotros" maxlength="50" mayusculastodo>

              </div>

            </div>           

          </div>-->
                            <div class="row">
                                <div class="form-group col-sm-12">
                                    Actividad Económica:
                                </div>
                                <div class="form-group col-sm-8">
                                    <div class="input-group">
                                        <select name="actividad" class="custom-select" ng-model="actividad_tmp_id" style="font-size: 12px">
                                            <option ng-repeat="x in actividades" value="{{x.id}}">{{x.codigo}} - {{x.descripcion | limitTo: 100}}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group col-sm-4">
                                    <button type="button" class="btn btn-outline-primary waves-effect" ng-click="agregarActividadTmpEditar()"><i class="fas fa-plus prefix grey-text mr-1"></i> Agregar Actividad</button>
                                </div>
                            </div>
                            <div class="col-sm-12 table-responsive-md">
                                <table class="table table-sm">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th scope="col">Codigo</th>
                                            <th scope="col">Descripcion</th>
                                            <th scope="col">Accion</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr ng-repeat="x in actividades_tmp">
                                            <th scope="row">{{x.codigo}}</th>
                                            <td>{{x.descripcion}}</td>
                                            <td align="center"><button type="button" class="btn btn-outline-danger btn-sm" ng-click="eliminarActividadTmp($index,x.id_actividad,editnumero)"><i class="fas fa-trash-alt"></i></button></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </form>
                </div>
                <!-- Modal footer -->
                <div class="modal-footer justify-content-center">
                    <button type="button" id="modificarpatente" class="btn btn-outline-primary waves-effect" ng-click="modificarPatente(formEditPatente)"><i class="fas fa-cloud-upload-alt prefix grey-text mr-1"></i> Guardar Cambios</button>
                </div>
            </div>
        </div>
    </div>
    <!--  FIN MODAL EDITAR -->
    <div class="row">
        <div class="titulo col-md-12 mb-3">
            <h3>Gestión de Patentes</h3>
        </div>
        <div class="buscador col-md-8 mb-3">
            <div class="input-group">
                <input type="text" class="form-control" ng-model="busqueda" ng-keydown="$event.keyCode===13 && buscarPatente(busqueda, filtrar)">
                <div class="input-group-append">
                    <div class="input-group-append ml-1">
                        <button class="btn btn-outline-secondary" type="button" id="button-addon2" ng-click="buscarPatente(busqueda, filtrar)"><i class="fas fa-search"></i></button>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4 text-right mb-3">
            <a href="" class="btn btn-outline-primary btn-rounded btn-sm font-weight-bold" data-toggle="modal" data-target="#myModalPatente" data-backdrop="static" data-keyboard="false" ng-click="listarActividades();eliminarTemporalCarga()"><i class="fas fa-plus-circle"></i> Agregar Patente</a>
        </div>
        <diw class="row ml-3">
            Opciones de Busqueda:
            <div class="form-check ml-3">
                <label class="form-check-label">
                    <input checked="" type="radio" class="form-check-input" name="optradio" value="1" ng-model="filtrar">Número de Patente
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
                <th scope="col">Numero</th>
                <th scope="col">Nombre/Razón Social</th>
                <th scope="col">Fecha</th>
                <th scope="col">Vencimiento</th>
                <th scope="col">Estatus</th>
                <th scope="col">Acciones</th>
            </tr>
        </thead>
        <tbody>
            <tr ng-show="patentes.length == 0 && iniciobuscar == true">
                <td colspan="7" align="center">NO HAY REGISTROS QUE MOSTRAR</td>
            </tr>
            <tr ng-repeat="x in patentes | limitTo: 20">
                <th scope="row" align="center">{{$index + 1}}</th>
                <td align="center">{{x.numero}}</td>
                <td>{{x.descripcion_establecimiento}}</td>
                <td align="center">{{x.fecha_registro | date : 'dd-MM-yyyy'}}</td>
                <td align="center">{{x.vencimiento | date : 'dd-MM-yyyy'}}</td>
                <td align="center"><span class="badge badge-pill badge-primary {{x.estatus == 5 ? 'badge-warning' : x.estatus == 4 ? 'badge-danger' : x.estatus == 3 ? 'badge-info' : x.estatus == 0 ? 'badge-info' : x.estatus == 1 ? 'badge-warning' : 'badge-danger'}}">{{x.estatus == 5 ? 'Bloqueada' : x.estatus == 4 ? 'Cierre Definitivo' : x.estatus == 3 ? 'Cierre Temporal' : x.estatus == 0 ? 'Activa' : x.estatus == 1 ? 'Vencida' : x.estatus == 6 ? 'Archivo Muerto' : 'Anulada'}}</span></td>
                <td align="center">
                    <button type="button" class="btn btn-outline-success blue light-3 btn-sm" data-toggle="modal" data-target="#myModalEditPatente" ng-click="cargarEditarPatente(x.id)" data-backdrop="static" data-keyboard="false"><i class="fas fa-edit"></i></button>
                    <button type="button" class="btn btn-outline-danger btn-sm" ng-click="eliminarPatente(x.id)"><i class="fas fa-trash-alt"></i></button>
                </td>
            </tr>
        </tbody>
    </table>
</div>