<div class="container" ng-controller="sliderController">
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
  <div class="modal fade" id="ModalAddSlider" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-notify modal-danger" role="document">
      <!--Content-->
      <div class="modal-content">
        <!--Header-->
        <div class="modal-header bg-fondo text-white text-center">
          <h4 class="modal-title text-white w-100 font-weight-bold py-2">Cargar Slider</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true" class="white-text">&times;</span>
          </button>
        </div>

        <!--Body-->
        <div class="modal-body">
          <form id="formSliderAdd" name="formSliderAdd" role="form" autocomplete="off" enctype="multipart/form-data">
            <div class="input-group">
              <div class="input-group-prepend">
                <span class="input-group-text"><i class="fas fa-camera"></i></span>
              </div>
              <input type="file" file-input="files" class="form-control">
            </div>
          </form>
        </div>

        <!--Footer-->
        <div class="modal-footer justify-content-center">
          <button id="agregarslider" type="button" class="btn btn-outline-primary waves-effect" ng-click="uploadFile()"><i class="fas fa-cloud-upload-alt prefix grey-text mr-1"></i> Guardar</button>
        </div>
      </div>
      <!--/.Content-->
    </div>
  </div>
  <!-- Finish The Modal -->

  <!-- Modal Editar -->
  <div class="modal fade" id="ModalEditSlider" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-notify modal-danger" role="document">
      <!--Content-->
      <div class="modal-content">
        <!--Header-->
        <div class="modal-header bg-fondo text-white text-center">
          <h4 class="modal-title white-text w-100 font-weight-bold py-2">Editar Slider</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true" class="white-text">&times;</span>
          </button>
        </div>

        <!--Body-->
        <div class="modal-body">
          <form id="formSliderEdit" name="formSliderEdit" role="form" autocomplete="off" enctype="multipart/form-data">
            <label>Imagen actual</label>
            <div class="input-group">
              <div class="input-group-prepend">
                <span class="input-group-text"><i class="fas fa-camera"></i></span>
              </div>
              <input type="text" ng-model="imagen" class="form-control" disabled>
            </div>
            <label>Nueva Imagen</label>
            <div class="input-group">
              <div class="input-group-prepend">
                <span class="input-group-text"><i class="fas fa-camera"></i></span>
              </div>
              <input type="file" file-input="files" class="form-control">
            </div>
          </form>
        </div>

        <!--Footer-->
        <div class="modal-footer justify-content-center">
          <button id="modificarslide" type="button" class="btn btn-outline-primary waves-effect" ng-click="uploadFileEditar()"><i class="fas fa-cloud-upload-alt prefix grey-text mr-1"></i> Guardar Cambios</button>
        </div>
      </div>
      <!--/.Content-->
    </div>
  </div>
  <!-- Finish The Modal -->


  <div class="row">
    <div class="titulo col-md-12 mb-3">
      <h3>Gestión de Sliders</h3>
      <h6>(Tamaño de imagen recomendado 1900x550)</h6>
    </div>
    <div class="buscador col-md-8 mb-3">
      <div class="input-group" ng-hide="true">
        <div class="input-group-prepend">
          <span class="input-group-text"><i class="fas fa-search"></i></span>
        </div>
        <input type="text" class="form-control">
      </div>
    </div>
    <div class="col-md-4 text-right mb-3">
      <a href="" class="btn btn-outline-primary btn-rounded btn-sm font-weight-bold" data-toggle="modal" data-target="#ModalAddSlider" data-backdrop="static" data-keyboard="false"><i class="fas fa-plus-circle"></i> Agregar Slider</a>
    </div>
  </div>
  <table class="table table-hover table-sm table-responsive-sm">
    <thead class="thead-dark">
      <tr>
        <th scope="col">Item</th>
        <th scope="col">Ruta</th>
        <th scope="col">Imagen</th>
        <th scope="col">Acciones</th>
      </tr>
    </thead>
    <tbody>
      <tr ng-repeat="x in sliders">
        <th scope="row" align="center">{{$index + 1}}</th>
        <td align="left">{{x.ruta}}</td>
        <td align="center"><img src="{{'../' + x.ruta}}" class="img-fluid" width="100" alt="Responsive image"></td>
        <td align="center">
          <button type="button" class="btn btn-outline-success blue light-3 btn-sm" data-toggle="modal" data-target="#ModalEditSlider" ng-click="cargarEditarSlider($index)" data-backdrop="static" data-keyboard="false"><i class="fas fa-edit"></i></button>
          <button type="button" class="btn btn-outline-danger btn-sm" ng-click="eliminarSlider($index)"><i class="fas fa-trash-alt"></i></button>
        </td>
      </tr>
    </tbody>
  </table>

</div>