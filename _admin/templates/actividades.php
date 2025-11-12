<div class="container" ng-controller="actividadesController">
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
<div class="modal font-modal" id="myModalActividades">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header bg-fondo text-white text-center">
        <h4 class="modal-title text-white w-100 font-weight-bold py-2">Datos de la Actividad a Incluir</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" ng-click="resetForm(formActividad)">
          <span aria-hidden="true" class="white-text">&times;</span>
        </button>
      </div>

      <!-- Modal body -->
      <div class="modal-body bg-white">
       <form id="formActividad" name="formActividad" method="post" novalidate>
        <div class="form-group">
          <div class="input-group mb-2">
            <div class="input-group-prepend">
              <div class="input-group-text"><i class="fab fa-creative-commons-sa"></i></div>
            </div>
            <input type="text" ng-change="buscarCodigo(actividad.codigo)" class="form-control {{formActividad.codigo.$touched === true ? actividad.codigo.length > 3 && codigo_existe === false ? 'is-valid' : 'is-invalid' : ''}}" name="codigo" id="codigo"  ng-model="actividad.codigo" placeholder="Codigo" minlength="3" maxlength="6" required mayusculastodo>
          </div>
                <div class="col-md-12" style="font-size: 10px; padding-left: 50px">              
                  <strong class="text-danger stretched-link text-right" ng-show="actividad.codigo.length > 3 && codigo_existe === true" role="alert">
                    Codigo ya registrado
                  </strong>
                </div>
        </div>

        <div class="form-group">
          <div class="input-group mb-2">
            <div class="input-group-prepend">
              <div class="input-group-text"><i class="fas fa-receipt"></i></div>
            </div>
            <textarea ng-change="buscarDescripcion(actividad.descripcion)" class="form-control {{formActividad.descripcion.$touched === true ? actividad.descripcion.length > 5  && descripcion_existe === false ? 'is-valid' : 'is-invalid' : ''}}" rows="5" id="descripcion" name="descripcion" ng-model="actividad.descripcion" placeholder="Descripcion" minlength="5" maxlength="1000" required mayusculastodo></textarea>
          </div>
                <div class="col-md-12" style="font-size: 10px; padding-left: 50px">              
                  <strong class="text-danger stretched-link text-right" ng-show="actividad.descripcion.length > 5 && descripcion_existe === true" role="alert">
                    Descripcion ya registrada
                  </strong>
                </div>
        </div>

        <div class="form-group">
          <div class="input-group mb-2">
            <div class="input-group-prepend">
              <div class="input-group-text"><i class="fas fa-percent"></i></i></div>
            </div>
            <input type="text" class="form-control {{formActividad.tasa.$touched === true ? actividad.tasa > 0 ? 'is-valid' : 'is-invalid' : ''}}" id="tasa" name="tasa" ng-model="actividad.tasa" placeholder="tasa" minlength="1" required decimal>
          </div>
        </div>

      </form>

      </div>

      <!-- Modal footer -->
      <div class="modal-footer justify-content-center">
        <button id="agregaractividad" type="button" class="btn btn-outline-primary waves-effect" ng-click="agregarActividad(formActividad)"><i class="fas fa-cloud-upload-alt prefix grey-text mr-1"></i> Guardar</button>
      </div>

    </div>
  </div>
</div>
<!-- FIN MODAL AGREGAR -->

<!-- Modal Modificar -->
<div class="modal font-modal" id="myModalEditActividades">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header bg-fondo text-white text-center">
        <h4 class="modal-title text-white w-100 font-weight-bold py-2">Datos de la Actividad a Modificar</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" ng-click="resetFormEditar(formEditActividad)">
          <span aria-hidden="true" class="white-text">&times;</span>
        </button>
      </div>

      <!-- Modal body -->
      <div class="modal-body bg-white">
       <form id="formActividad" name="formEditActividad" method="post" novalidate>
        <div class="form-group">
          <div class="input-group mb-2">
            <div class="input-group-prepend">
              <div class="input-group-text"><i class="fab fa-creative-commons-sa"></i></div>
            </div>
            <input type="text" ng-change="buscarCodigoEditar(editcodigo)" class="form-control {{formEditActividad.editcodigo.$touched === true ? editcodigo.length > 3 && codigo_existe === false ? 'is-valid' : 'is-invalid' : ''}}" name="editcodigo" id="editcodigo"  ng-model="editcodigo" placeholder="Codigo" minlength="3" maxlength="6" required mayusculastodo>
          </div>
                <div class="col-md-12" style="font-size: 10px; padding-left: 50px">              
                  <strong class="text-danger stretched-link text-right" ng-show="editcodigo.length > 3 && codigo_existe === true" role="alert">
                    Codigo ya registrado
                  </strong>
                </div>
        </div>

        <div class="form-group">
          <div class="input-group mb-2">
            <div class="input-group-prepend">
              <div class="input-group-text"><i class="fas fa-receipt"></i></div>
            </div>
            <textarea ng-change="buscarDescripcionEditar(editdescripcion)" class="form-control {{formEditActividad.editdescripcion.$touched === true ? editdescripcion.length > 5  && descripcion_existe === false ? 'is-valid' : 'is-invalid' : ''}}" rows="5" id="editdescripcion" name="editdescripcion" ng-model="editdescripcion" placeholder="Descripcion" minlength="5" maxlength="1000" required mayusculastodo></textarea>
          </div>
                <div class="col-md-12" style="font-size: 10px; padding-left: 50px">              
                  <strong class="text-danger stretched-link text-right" ng-show="editdescripcion.length > 5 && descripcion_existe === true" role="alert">
                    Descripcion ya registrada
                  </strong>
                </div>
        </div>

        <div class="form-group">
          <div class="input-group mb-2">
            <div class="input-group-prepend">
              <div class="input-group-text"><i class="fas fa-percent"></i></div>
            </div>
            <input type="text" class="form-control {{formEditActividad.edittasa.$touched === true ? edittasa > 0 ? 'is-valid' : 'is-invalid' : ''}}" id="edittasa" name="edittasa" ng-model="edittasa" placeholder="tasa" minlength="1" required decimal>
          </div>
        </div>

      </form>

      </div>

      <!-- Modal footer -->
      <div class="modal-footer justify-content-center">
        <button id="modificaractividad" type="button" class="btn btn-outline-primary waves-effect" ng-click="modificarActividad(formEditActividad)"><i class="fas fa-cloud-upload-alt prefix grey-text mr-1"></i> Guardar Cambios</button>
      </div>

    </div>
  </div>
</div>
<!-- FIN MODAL MODIFICAR -->
  <div class="row">
    <div class="titulo col-md-12 mb-3">
      <h3>Gestión de Actividades Económicas</h3>
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
      <a href="" class="btn btn-outline-primary btn-rounded btn-sm font-weight-bold" data-toggle="modal" data-target="#myModalActividades" data-backdrop="static" data-keyboard="false"><i class="fas fa-plus-circle"></i> Agregar Actividad</a>      
    </div>
  </div>
  <table class="table table-hover table-sm table-responsive-sm">
    <thead class="thead-dark">
      <tr>
        <th scope="col">Codigo</th>
        <th scope="col">Descripcion</th>
        <th scope="col">Tasa</th>
        <th scope="col">Acciones</th>
      </tr>
    </thead>
    <tbody>
      <tr ng-repeat="act in bdactividades | filter:busqueda">
        <th scope="row" align="center">{{act.codigo}}</th>
        <td >{{act.descripcion}}</td>
        <td align="center">{{act.tasa}}</td>
        <td align="center">
          <button type="button" class="btn btn-outline-success blue light-3 btn-sm" data-toggle="modal" data-target="#myModalEditActividades" ng-click="cargarEditarActividad(act.id)" data-backdrop="static" data-keyboard="false"><i class="fas fa-edit"></i></button> 
          <button type="button" class="btn btn-outline-danger btn-sm" ng-click="eliminarActividad(act.id)"><i class="fas fa-trash-alt"></i></button>
        </td>
      </tr>
    </tbody>
  </table>
{{}}
</div>
