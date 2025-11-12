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
<section id="listarsolicitudes" ng-controller="solicitudesController" class="pl-5 pr-5">
    <div class="row">
        <div class="titulo col-md-12 mb-3">
            <h3>Solicitudes Pendientes</h3>
        </div>
    </div>
    <div class="titulo col-md-7 mb-3">
    </div>
    </div>
    <div class="table-responsive" id="tablasolicitudes">
        <table class="table table-bordered table-hover table-sm" style="font-size: 14px">
            <thead class="thead-dark">
                <tr>
                    <th scope="col">Item</th>
                    <th scope="col">Nº Patente</th>
                    <th scope="col">Rif</th>
                    <th scope="col">Contribuyente</th>
                    <th scope="col">Tipo Solicitud</th>
                    <th scope="col">Fecha</th>
                    <th scope=" col">Estatus</th>
                    <th scope="col">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <tr ng-show="solicitudes.length == 0">
                    <td colspan="9" align="center">NO HAY REGISTROS QUE MOSTRAR</td>
                </tr>
                <tr ng-repeat="item in solicitudes">
                    <td align="center">{{$index + 1}}</td>
                    <td align="center">{{item.numero}}</td>
                    <td align="center">{{item.rif}}</td>
                    <td align="left">{{item.descripcion_establecimiento}}</td>
                    <td align="center">{{item.descripcion}}</td>
                    <td align="center">{{item.fecha}}</td>
                    <td align="center">
                        <!--<i class="fas fa-search">-->
                        <span class="badge badge-pill badge-primary {{item.estatus == 0 ? 'badge-warning' : item.estatus == 1 ? 'badge-info' : 'badge-success'}}">{{item.estatus == 0 ? 'Solicitada' : item.estatus == 1 ? 'En Proceso' : 'Concluida'}}</span>
                    </td>
                    <td align="center"><button class="btn btn-success btn-sm" type="button" ng-click="procesarSolicitud(item.id_solicitud, item.id_contribuyente,item.id_patente)" ng-show="item.estatus == 0"><i class="fas fa-pray"></i> Procesar
                        </button></td>
                </tr>
            </tbody>
        </table>
    </div>
    <!--<button id="btnImprimirDiv" ng-click="imprimir()">Imprimir</button>-->
    <!-- The Modal Agregar-->
    <div class="modal font-modal" id="modalCargarDeudas">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header bg-fondo text-white text-center">
                    <h4 class="modal-title text-white w-100 font-weight-bold py-2">Cargar Deudas Pendientes</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" ng-click="resetForm(formCargarDeudas)">
                        <span aria-hidden="true" class="white-text">&times;</span>
                    </button>
                </div>
                <!-- Modal body -->
                <div class="modal-body bg-white">
                    <form id="formCargarDeudas" name="formCargarDeudas" method="post" novalidate>
                        <table class="table table-sm" style="font-size: 14px">
                            <thead class="thead-light">
                                <tr>
                                    <th scope="col">
                                        Concepto
                                    </th>
                                    <th scope="col">
                                        Periodo
                                    </th>
                                    <th scope="col">
                                        Monto BsS
                                    </th>
                                    <th scope="col">
                                        Acción
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <select name="concepto" class="custom-select {{formCargarDeudas.concepto.$touched === true ? deuda.concepto > 0 ? 'is-valid' : 'is-invalid' : ''}}" ng-model="deuda.concepto" ng-change="llenarPartida(deuda.concepto)">
                                            <option ng-repeat="x in conceptos" ng-value="x.id" ng-bind="x.descripcion"></option>
                                        </select>
                                    </td>
                                    <td align="right">
                                        <input type="text" class="form-control {{formCargarDeudas.fecha1.$touched === true ? deuda.periodo != '' ? 'is-valid' : 'is-invalid' : ''}}" name="fecha1" id="fecha1" ng-model="deuda.periodo" required>
                                    </td>
                                    <td align="right">
                                        <input type="text" class="text-right form-control {{formCargarDeudas.monto.$touched === true ? deuda.monto > 0 ? 'is-valid' : 'is-invalid' : ''}}" name="monto" id="monto" ng-model="deuda.monto" minlength="1" maxlength="15" required decimal>
                                    </td>
                                    <td align="center">
                                        <button type="button" class="btn btn-primary btn-block" ng-click="agregarDetalle(formCargarDeudas)">Agregar</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <table class="table table-hover table-sm" style="font-size: 14px">
                            <thead class="thead-dark">
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Concepto</th>
                                    <th scope="col">Concepto</th>
                                    <th scope="col">Monto</th>
                                    <th scope="col">Acción</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr ng-repeat="item in detalles">
                                    <th scope="row">{{$index + 1}}</th>
                                    <td>{{item.textoconcepto}}</td>
                                    <td align="right">{{item.periodo}}</td>
                                    <td align="right">{{item.monto | number:2}}</td>
                                    <td align="center"><button type="button" class="btn btn-outline-danger btn-sm" ng-click="eliminarDetalle($index)"><i class="fas fa-trash-alt"></i></button></td>
                                </tr>
                            </tbody>
                        </table>
                    </form>
                </div>
                <!-- Modal footer -->
                <div class="modal-footer justify-content-center">
                    <button id="agregarplanilla" type="button" class="btn btn-outline-primary waves-effect" ng-click="agregarPlanilla(formCargarDeudas)"><i class="fas fa-cloud-upload-alt prefix grey-text mr-1"></i> Guardar</button>
                </div>
            </div>
        </div>
    </div>
    <!-- FIN MODL AGREGAR -->
</section>