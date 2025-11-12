<div class="container" ng-controller="declaracionesController">
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
    <div class="row">
        <div class="titulo col-md-12 mb-3">
            <h3>Gestión de Pagos</h3>
        </div>
    </div>
    <div class="d-flex justify-content-end text-right mb-3" ng-hide="impuestodeuda > 0 && impuestodeuda == montoacumulado">
        <a href="" class="btn btn-outline-primary btn-rounded btn-sm font-weight-bold" data-toggle="modal" data-target="#myModalIncluirPago" data-backdrop="static" data-keyboard="false" ng-click="validarCarga()"><i class="fas fa-plus-circle"></i> Agregar Pago</a>
    </div>
    <p class="text-center">
        Detalles de Pagos
    </p>
    <div class="table-responsive">
        <table class="table table-bordered table-hover table-sm">
            <thead class="thead-dark">
                <tr>
                    <th scope="col">Nº Patente.</th>
                    <th scope="col">Nº Declaracion</th>
                    <th scope="col">Fecha.</th>
                    <th scope="col">Forma Pago.</th>
                    <th scope="col">Nº Operación.</th>
                    <th scope="col">Monto BsS</th>
                    <th scope="col">Acción</th>
                </tr>
            </thead>
            <tbody>
                <tr ng-repeat="pago in pagosagregados">
                    <td align="center">{{pago.numeropatente}}</td>
                    <td align="center">{{pago.numero_declaracion}}</td>
                    <td align="center">{{pago.fechapago}}</td>
                    <td align="center">{{pago.formapago == 1 ? 'EFECTIVO' : pago.formapago == 2 ? 'TRANSFERENCIA' : pago.formapago == 3 ? 'PUNTO DE VENTA TD' : 'CHEQUE DE GERENCIA'}}</td>
                    <td align="center">{{pago.referencia}}</td>
                    <td align="center">{{pago.montopago | number:2}}</td>
                    <td align="center">
                        <button type="button" class="btn btn-outline-success btn-sm" ng-click="eliminarPago($index)"><i class="far fa-trash-alt"></i></button>
                    </td>
                </tr>
                <tr ng-show="pagosagregados.length > 0">
                    <td colspan="6" align="right">Total Impuesto</td>
                    <td align="right"><span class="text-info font-weight-bold">{{impuestodeuda | number:2}}</span></td>
                </tr>
                <tr ng-show="pagosagregados.length > 0">
                    <td colspan="6" align="right">Total Acumulado</td>
                    <td align="right"><span class="{{impuestodeuda == montoacumulado ? 'text-success' : 'text-secondary'}} font-weight-bold">{{montoacumulado | number:2}}</span></td>
                </tr>
                <tr ng-show="pagosagregados.length > 0">
                    <td colspan="6" align="right">Diferencia</td>
                    <td align="right"><span class="{{impuestodeuda == montoacumulado ? 'text-success' : 'text-danger'}} font-weight-bold">{{impuestodeuda - montoacumulado | number:2}}</span></td>
                </tr>
                <tr ng-show="impuestodeuda > 0 && impuestodeuda == montoacumulado">
                    <td colspan="7" align="center">
                        <button type="button" class="btn btn-outline-success btn-sm" ng-click="procesarPago()"><i class="far fa-check-circle"></i> Procesar Pago</button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <section id="pagosporconfirmar" ng-if="planillas.length > 0 && pagosenviados.length > 0">
        <p class="text-center">
            Relación de Pagos por Confirmar
        </p>
        <div class="buscador col-md-8 mb-3">
            <form id="formBuscarPago" name="formBuscarPago" method="post" novalidate>
                <div class="form-group col-sm-12">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-search"></i></span>
                        </div>
                        <input type="text" ng-change="buscarNumeroPatente()" class="form-control" name="buscarpago" id="buscarpago" placeholder="Ingrese datos del pago a buscar" ng-model="buscarpago" required entero>
                    </div>
                </div>
            </form>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered table-hover table-sm">
                <thead class="thead-dark">
                    <tr>
                        <th scope="col">Fecha</th>
                        <th scope="col">Nº Documento.</th>
                        <th scope="col">Banco Origen</th>
                        <th scope="col">Banco Destino</th>
                        <th scope="col">Forma de Pago</th>
                        <th scope="col">Monto Pagado BsS</th>
                        <th scope="col">Detalle Planillas</th>
                        <th scope="col">Acción</th>
                    </tr>
                </thead>
                <tbody>
                    <tr ng-repeat="pago in pagosenviados | filter:buscarpago">
                        <td align="center">{{pago.fecha}}</td>
                        <td align="center">{{pago.referencia}}</td>
                        <td align="center">{{pago.bancoorigen}}</td>
                        <td align="center">{{pago.bancodestino}}</td>
                        <td align="center">{{pago.formapago}}</td>
                        <td align="right">{{pago.monto_pagado | number:2}}</td>
                        <td align="center">{{pago.detalle_planilla}}</td>
                        <td align="center">
                            <button type="button" class="btn btn-outline-success btn-sm" ng-click="confirmarPago(pago.id, pago.monto_pagado, pago.referencia, pago.fecha, pago.id_bancodestino)"><i class="far fa-check-circle"></i> Confirmar</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </section>
    <!-- Modal Agregar PAGOS-->
    <div class="modal fade bd-example-modal-md" id="myModalIncluirPago">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header bg-fondo text-white text-center">
                    <h4 class="modal-title text-white w-100 font-weight-bold py-2"><i class="fas fa-money-bill-wave mr-3 rounded-circle bg-dark p-3"></i> Datos del Pago</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" ng-click="resetForm(formAgregarPago)">
                        <span aria-hidden="true" class="white-text">&times;</span>
                    </button>
                </div>
                <!-- Modal body -->
                <div class="modal-body bg-white pl-5 pr-5 pt-2 pb-2">
                    <form id="formAgregarPago" name="formAgregarPago" method="post" novalidate>
                        <div class="form-group">
                            <label for="fecha">Nº Patente</label>
                            <input type="text" class="form-control {{formAgregarPago.numeropatente.$touched === true ? idpatente > 0? 'is-valid' : 'is-invalid' : ''}}" name="numeropatente" id="numeropatente" ng-model="sendpago.numeropatente" ng-change="buscarNumeroPatente(sendpago.numeropatente)" maxlength="11" mayusculastodo ng-disabled="deshabilitarseleccion" required>
                        </div>
                        <div class="form-group">
                            <label for="fecha">Nº Declaracion</label>
                            <select class="form-control {{formAgregarPago.numerodeclaracion.$touched === true ? iddeclaracion > 0? 'is-valid' : 'is-invalid' : ''}}" name="numerodeclaracion" id="numerodeclaracion" ng-model="sendpago.numero_declaracion" ng-change='asignarIddeclaracion(sendpago.numero_declaracion)' ng-disabled="deshabilitarseleccion" required>
                                <option ng-repeat="numero in declaracionespatente" ng-value="numero.id" ng-bind="'Nro:' + numero.numero + ' Fecha: ' + numero.fecha + ' Monto: ' + numero.total_impuesto"></option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="fecha">Forma de Pago</label>
                            <select class="form-control {{formAgregarPago.cbotipopago.$touched === true ? sendpago.formapago > 0? 'is-valid' : 'is-invalid' : ''}}" name="cbotipopago" id="cbotipopago" ng-model="sendpago.formapago" ng-change="validarEfectivo(sendpago.formapago)">
                                <option ng-repeat="forma in formaspago" ng-value="forma.id" ng-bind="forma.descripcion"></option>
                            </select required>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-6" ng-show="!efectivo_ocultar">
                                <label for="fecha">Banco Origen</label>
                                <select class="form-control {{formAgregarPago.cbobancoorigen.$touched === true ? sendpago.bancoorigen > 0 ? 'is-valid' : 'is-invalid' : ''}}" name="cbobancoorigen" id="cbobancoorigen" ng-model="sendpago.bancoorigen">
                                    <option ng-repeat="origen in bancosorigen" ng-value="origen.id" ng-bind="origen.descripcion"></option>
                                </select required>
                            </div>
                            <div class="form-group col-md-6" ng-show="!efectivo_ocultar">
                                <label for="fecha">Banco Destino</label>
                                <select class="form-control {{formAgregarPago.cbobancodestino.$touched === true ? sendpago.bancodestino > 0 ? 'is-valid' : 'is-invalid' : ''}}" name="cbobancodestino" id="cbobancodestino" ng-model="sendpago.bancodestino">
                                    <option ng-repeat="numero in bancos" ng-value="numero.id" ng-bind="numero.banco"></option>
                                </select required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="fecha">Nº Operación</label>
                                <input type="text" class="form-control {{formAgregarPago.documento.$touched === true ? sendpago.referencia.length > 0 ? 'is-valid' : 'is-invalid' : ''}}" name="documento" id="documento" ng-model="sendpago.referencia" required ng-disabled="sendpago.formapago == 1 ? 'disabled' : ''">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="fecha">Fecha</label>
                                <input type="text" class="form-control {{formAgregarPago.fechapago.$touched === true ? sendpago.fechapago.length > 0 ? 'is-valid' : 'is-invalid' : ''}}" name="fechapago" id="fechapago" ng-model="sendpago.fechapago" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="fecha">Monto del Pago</label>
                            <input type="text" class="form-control text-right {{formAgregarPago.montopago.$touched === true ? sendpago.montopago > 0? 'is-valid' : 'is-invalid' : ''}}" name="montopago" id="montopago" ng-model="sendpago.montopago" decimal>
                        </div>
                    </form>
                </div>
                <!-- Modal footer -->
                <div class="modal-footer justify-content-center">
                    <button id="btnagregarpago" type="button" class="btn btn-outline-primary btn-sm" ng-click="agregarPago(formAgregarPago)"><i class="fas fa-check-square"></i> Registrar</button>
                </div>
            </div>
        </div>
    </div>
    <!--  FIN MODAL AGREGAR PAGO -->
</div>