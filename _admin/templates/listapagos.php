
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

<section id="listarpagos" ng-controller="pagosController" class="pl-5 pr-5">
  <div class="row">
    <div class="titulo col-md-12 mb-3">
      <h3>Relación de Pagos</h3>
    </div>

    <div class="input-group mb-3 col-md-4">
      <div class="input-group-prepend">
        <span class="input-group-text">Nº Patente: </span>
      </div>
      <input type="text" class="form-control" aria-label="Amount (to the nearest dollar)" ng-model="buscarpatente">
      <div class="input-group-append">
        <div class="input-group-append ml-1">
          <button class="btn btn-outline-secondary" type="button" id="button-addon2" ng-click="listarPagos(buscarpatente)"><i class="fas fa-search"></i></button>
        </div>
      </div>
    </div>

  </div>
   
            <div class="table-responsive" id="tablapagos">
                <table class="table table-bordered ta ng-ble-hover table-sm">
                  <thead class="thead-dark">
                    <tr>
                      <th scope="col">Nº Patente</th>
                      <th scope="col">Nº Declaración</th>
                      <th scope="col">Fecha de Pago</th>
                      <th scope="col">Monto Pagado BsS</th>
                      <th scope="col">Estatus</th>
                      <th scope="col">Recibo</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr ng-repeat="pago in listarpagos">
                      <td align="center">{{pago.numero}}</td>
                      <td align="center">{{pago.numerodeclaracion}}</td>
                      <td align="center">{{pago.fecha}}</td>
                      <td align="right">{{pago.monto_pagado | number:2}}</td>
                      <td align="center">
                        <span class="badge badge-pill badge-primary {{pago.estatus == 0 ? 'badge-warning' : pago.estatus == 1 ? 'badge-success' : pago.estatus == 2 ? 'badge-info' : pago.estatus == 4 ? 'badge-secondary' : 'badge-danger'}}">{{pago.estatus == 0 ? 'Enviado' : pago.estatus == 1 ? 'Conciliado' : pago.estatus == 2 ? 'Confirmado' : pago.estatus == 4 ? 'Anulado' : 'Rechazado'}}</span>
                      </td>
                      <td align="center">
                        <form action="../scripts/1factura.php" target="_blank" method="post" accept-charset="utf-8">
                            <input type="hidden" name="id" id="id" value="{{pago.id_declaracion}}">
                            <button  ng-show="pago.estatus == 1" type="submit" class="badge badge-info"><i class="fas fa-search"></i> Ver</button>
                        </form>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
  
</section>
