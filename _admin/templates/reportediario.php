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
      <h3>Reporte de Recaudación - Conciliación Diaria Online</h3>
    </div>

    <div class="input-group mb-3 col-md-6">
      <form action="../reporte/recaudacion_diaria.php" target="_blank" method="post" accept-charset="utf-8">
          <div class="row">
            <div class="form-group col-sm-6">
              Desde: 
              <div class="input-group">
                <div class="input-group-text"><i class="far fa-calendar-alt"></i></div>
                <input type="text" class="form-control" name="fecha" id="fecha" placeholder="Vencimiento" minlength="10" maxlength="10" value="<?php echo date('d/m/Y'); ?>" required>
              </div>
            </div>
            <div class="form-group col-sm-6">
              Hasta: 
              <div class="input-group">
                <div class="input-group-text"><i class="far fa-calendar-alt"></i></div>
                <input type="text" class="form-control" name="fechaf" id="fechaf" placeholder="Fecha Hasta" minlength="10" maxlength="10" value="<?php echo date('d/m/Y'); ?>" required>
              </div>
            </div>
            <div class="form-group col-sm-6">
              <button type="submit" class="btn btn-outline-primary"><i class="fas fa-search"></i> Generar Reporte</button>
            </div>
          </div>
      </form>
    </div>

  </div>
  
<!--<button id="btnImprimirDiv" ng-click="imprimir()">Imprimir</button>-->
</div>
