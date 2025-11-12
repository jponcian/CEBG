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
<section id="pagosporconfirmar" ng-controller="pagosController" class="pl-5 pr-5">
    <h3>Relación de Pagos Recibidos</h3>
    <div class="buscador col-md-12 mb-3">
        <form id="formBuscarPago" name="formBuscarPago" method="post" novalidate>
            <div class="form-group col-sm-12">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                    </div>
                    <input type="text" ng-change="buscarNumeroPatente()" class="form-control" name="buscarpago" id="buscarpago" placeholder="Ingrese datos del pago a buscar" ng-model="buscarpago" required>
                </div>
            </div>
        </form>
    </div>
    <div class="table-responsive" id="tablapagos">
        <table class="table table-bordered table-hover table-sm">
            <thead class="thead-dark">
                <tr>
                    <th scope="col">Nº Patente</th>
                    <th scope="col">Contribuyente</th>
                    <th scope="col">Concepto</th>
                    <th scope="col">Forma de Pago</th>
                    <!--<th scope="col">Banco Origen</th>
                    <th scope="col">Banco Destino</th>-->
                    <th scope="col">Nº Operación</th>
                    <th scope="col">Fecha del Pago</th>
                    <th scope="col">Monto Pagado BsS</th>
                    <th scope="col">Acción</th>
                </tr>
            </thead>
            <tbody>
                <tr ng-show="pagosenviados.length == 0">
                    <td colspan="9" align="center">NO HAY PAGOS RECIBIDOS PENDIENTES POR CONCILIAR</td>
                </tr>
                <tr ng-repeat="pago in pagosenviados | filter:buscarpago">
                    <td align="center">{{pago.numero}}</td>
                    <td>{{pago.razonsocial}}</td>
                    <td>{{pago.concepto}}</td>
                    <td>{{pago.descripcion_pago}}</td>
                    <!--<td>{{pago.bancoorigen}}</td>
                    <td>{{pago.bancodestino}}</td>-->
                    <td align="center">{{pago.referencia}}</td>
                    <td align="center">{{pago.fecha}}</td>
                    <td align="right">{{pago.monto_pagado | number:2}}</td>
                    <td align="center">
                        <button type="button" class="badge badge-success" ng-click="actualizarPago(pago.id_declaracion, pago.origen, pago.id_planilla, pago.id, 0, pago.numero, pago.referencia, pago.bancodestino, pago.monto_pagado | number:2)"><i class="far fa-check-circle"></i> Conciliar</button>
                        <button type="button" class="badge badge-danger" ng-click="actualizarPago(pago.id_declaracion, pago.origen, pago.id_planilla, pago.id, 1, pago.numero, pago.referencia, pago.bancodestino, pago.monto_pagado | number:2)"><i class="far fa-times-circle"></i> Rechazar</button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</section>