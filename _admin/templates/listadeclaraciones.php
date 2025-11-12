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
<section id="listardeclaraciones" ng-controller="pagosController" class="pl-5 pr-5">
    <div class="row">
        <div class="titulo col-md-12 mb-3">
            <h3>Relación de Declaraciones</h3>
        </div>
        <div class="input-group mb-3 col-md-5">
            <div class="input-group-prepend">
                <span class="input-group-text"><i class="far fa-calendar-alt mr-2"></i> Fecha de Presentación: </span>
            </div>
            <input type="text" id="fechabuscar" class="form-control" aria-label="Fecha" ng-model="busqueda">
            <div class="input-group-append">
                <div class="input-group-append ml-1">
                    <button class="btn btn-outline-secondary" type="button" id="button-addon2" ng-click="listarDecalarciones(busqueda)"><i class="fas fa-search"></i></button>
                </div>
            </div>
        </div>
        <div class="titulo col-md-7 mb-3">
        </div>
    </div>
    <div class="table-responsive" id="tabladeclaraciones">
        <table class="table table-bordered table-hover table-sm" style="font-size: 14px">
            <thead class="thead-dark">
                <tr>
                    <th scope="col">Item</th>
                    <th scope="col">Nº Patente</th>
                    <th scope="col">Rif</th>
                    <th scope="col">Contribuyente</th>
                    <th scope="col">Nº Declaración</th>
                    <th scope="col">Período</th>
                    <th scope="col">Monto Declarado BsS</th>
                    <th scope="col">Impuesto BsS</th>
                    <th scope="col">Estatus</th>
                </tr>
            </thead>
            <tbody>
                <tr ng-show="declaraciones.length == 0 && iniciobuscar == true">
                    <td colspan="9" align="center">NO HAY REGISTROS QUE MOSTRAR</td>
                </tr>
                <tr ng-repeat="item in declaraciones">
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
    <!--<button id="btnImprimirDiv" ng-click="imprimir()">Imprimir</button>-->
</section>