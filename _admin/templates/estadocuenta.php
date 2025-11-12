<div class="container" ng-controller="pagosController">
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
            <h3>Consultar Cuenta por Contribuyente</h3>
        </div>
        <div class="buscador col-md-8 mb-3">
            <div class="input-group">
                <input type="text" class="form-control" ng-model="busquedaedo">
                <div class="input-group-append">
                    <div class="input-group-append ml-1">
                        <button class="btn btn-outline-secondary" type="button" id="button-addon2" ng-click="listarEstadoCuenta(busquedaedo, filtrar)"><i class="fas fa-search"></i></button>
                    </div>
                </div>
            </div>
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
    <div class="table-responsive" id="tabladestadocuenta">
        <table class="table table-bordered table-hover table-sm" style="font-size: 14px">
            <thead class="thead-dark">
                <tr>
                    <th scope="col">Item</th>
                    <th scope="col">Nº Patente</th>
                    <th scope="col">Rif</th>
                    <th scope="col">Contribuyente</th>
                    <th scope="col">Nº Declaración</th>
                    <th scope="col">Periodo</th>
                    <th scope="col">Monto Declarado BsS</th>
                    <th scope="col">Impuesto BsS</th>
                    <th scope="col">Estatus</th>
                </tr>
            </thead>
            <tbody>
                <tr ng-show="estadocuentas.length == 0 && iniciobuscar == true">
                    <td colspan="9" align="center">NO HAY REGISTROS QUE MOSTRAR</td>
                </tr>
                <tr ng-repeat="item in estadocuentas">
                    <td align="center">{{$index + 1}}</td>
                    <td align="center">{{item.numeropatente}}</td>
                    <td align="center">{{item.rif}}</td>
                    <td align="center">{{item.descripcion_establecimiento}}</td>
                    <td align="center">
                        <form action="../scripts/2declaracion.php" target="_blank" method="post" accept-charset="utf-8">
                            {{item.numerodeclaracion}}
                            <input type="hidden" name="id" id="id" value="{{item.id}}">
                            <button type="submit" class="badge badge-info" data-toggle="tooltip" data-placement="top" title="Ver Declaración"><i class="fas fa-search"></i></button>
                        </form>
                    </td>
                    <td align="center">{{item.periodo}}</td>
                    <td align="right">{{item.monto_declarado | number:2}}</td>
                    <td align="right">{{item.total_impuesto | number:2}}</td>
                    <td align="center">
                        <span class="badge badge-pill badge-primary {{item.estatus == 0 ? 'badge-warning' : item.estatus == 1 ? 'badge-info' : 'badge-success'}}">{{item.estatus == 0 ? 'Registrada' : item.estatus == 1 ? 'Pagada' : 'Conciliada'}}</span>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>