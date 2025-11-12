<div class="container" ng-controller="articulosController">
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

<div ng-hide="loading">
  
<!-- Modal Agregar AGREGAR -->
<div  class="modal fade" id="ModalAddArticulo" tabindex="-1" role="dialog" aria-labelledby="myModalArticulo"
  aria-hidden="true">
  <div class="modal-dialog modal-notify modal-danger modal-lg" role="document">
    <!--Content-->
    <div class="modal-content">
      <!--Header-->
      <div class="modal-header bg-fondo text-white text-center">
        <h4 class="modal-title text-white w-100 font-weight-bold py-2">Cargar Articulo</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" ng-click="resetForm(formArticulo)">
          <span aria-hidden="true" class="white-text">&times;</span>
        </button>
      </div>

      <!--Body-->
      <div class="modal-body">
    <form id="formArticulo" name="formArticulo" role="form" autocomplete="off" enctype="multipart/form-data">
        <div class="form-group">
          <div class="input-group mb-2">
            <div class="input-group-prepend">
              <div class="input-group-text"><i class="fas fa-key"></i></div>
            </div>
            <input type="text" class="form-control {{formArticulo.titulo.$touched === true ? articulo.titulo.length > 3 ? 'is-valid' : 'is-invalid' : ''}}" id="titulo" name="titulo" ng-model="articulo.titulo" placeholder="Titulo del Articulo" minlength="1" maxlength="150" required mayusculastodo>
          </div>
        </div>

        <div class="form-group">
            <label for="txtdescripcion">Cuerpo del Articulo:</label>
            <textarea ng-model="articulo.descripcion" class="form-control" name="txtdescripcion" id="txtdescripcion" rows="10" cols="80" placeholder="Required example textarea" maxlength="100" required></textarea>
        </div>

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
        <button id="agregararticulo" type="button" class="btn btn-outline-primary waves-effect" ng-click="uploadFile()"><i class="fas fa-cloud-upload-alt prefix grey-text mr-1"></i> Guardar</button>
      </div>
    </div>
    <!--/.Content-->
  </div>
</div>
<!-- Finish The Modal AGREGAR-->

<!-- Modal Editar -->
<div  class="modal fade" id="ModalEditArticulo" tabindex="-1" role="dialog" aria-labelledby="myModalEditArticulo"
  aria-hidden="true">
  <div class="modal-dialog modal-notify modal-danger modal-lg" role="document">
    <!--Content-->
    <div class="modal-content">
      <!--Header-->
      <div class="modal-header bg-fondo text-white text-center">
        <h4 class="modal-title white-text w-100 font-weight-bold py-2">Editar Articulo</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" ng-click="resetFormEditar(formEditArticulo)">
          <span aria-hidden="true" class="white-text">&times;</span>
        </button>
      </div>

      <!--Body-->
      <div class="modal-body">
    <form id="formEditArticulo" name="formEditArticulo" role="form" autocomplete="off" enctype="multipart/form-data">
        <div class="form-group">
          <div class="input-group mb-2">
            <div class="input-group-prepend">
              <div class="input-group-text"><i class="fas fa-key"></i></div>
            </div>
            <input type="text" class="form-control {{formEditArticulo.edittitulo.$touched === true ? edittitulo.length > 3 ? 'is-valid' : 'is-invalid' : ''}}" id="edittitulo" name="edittitulo" ng-model="edittitulo" placeholder="Titulo del Articulo" minlength="1" maxlength="150" required mayusculastodo>
          </div>
        </div>
        <div class="form-group">
            <label for="txtdescripcion">Cuerpo del Articulo:</label>
            <textarea ng-model="editdescripciondb" class="form-control" name="txtdescripcioneditar" id="txtdescripcioneditar" rows="10" cols="80" placeholder="Required example textarea" maxlength="100" required></textarea>
        </div>

      <label>Imagen actual</label>
      <div class="input-group">
        <div class="input-group-prepend">
          <span class="input-group-text"><i class="fas fa-camera"></i></span>
        </div>
            <input type="text" ng-model="editimagen" class="form-control" disabled>
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
        <button id="modificararticulo" type="button" class="btn btn-outline-primary waves-effect" ng-click="uploadFileEditar()"><i class="fas fa-cloud-upload-alt prefix grey-text mr-1"></i> Guardar Cambios</button>
      </div>
    </div>
    <!--/.Content-->
  </div>
</div>
<!-- Finish The Modal EDITAR-->


  <div class="row">
    <div class="titulo col-md-12 mb-3">
      <h3>Gestión de Articulos</h3>
      <h6>(Tamaño de imagen recomendado 320x180)</h6>
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
      <a href="" class="btn btn-outline-primary btn-rounded btn-sm font-weight-bold" data-toggle="modal" data-target="#ModalAddArticulo" data-backdrop="static" data-keyboard="false"><i class="fas fa-plus-circle"></i> Agregar Articulo</a>      
    </div>
  </div>
  <table class="table table-hover table-sm table-responsive-sm">
    <thead class="thead-dark">
      <tr>
        <th scope="col">Item</th>
        <th scope="col">Fecha</th>
        <th scope="col">Titulo</th>
        <th scope="col">Descripcion</th>
        <th scope="col">Acciones</th>
      </tr>
    </thead>
    <tbody>
      <tr ng-repeat="x in articulos | filter:busqueda">
        <th scope="row" align="center">{{$index + 1}}</th>
        <td align="center">{{x.fecha | date:'dd/MM/yyyy'}}</td>
        <td align="left">{{x.titulo}}</td>
        <td align="center"><img src="../{{x.image}}" class="img-fluid" width="100" alt="Responsive image"></td>
        <td align="center">
          <button type="button" class="btn btn-outline-success blue light-3 btn-sm" data-toggle="modal" data-target="#ModalEditArticulo" ng-click="cargarEditarArticulo(x.id)" data-backdrop="static" data-keyboard="false"><i class="fas fa-edit"></i></button> 
          <button type="button" class="btn btn-outline-danger btn-sm" ng-click="eliminarArticulo(x.id)"><i class="fas fa-trash-alt"></i></button>
        </td>
      </tr>
    </tbody>
  </table>

</div>
</div>

<script>
    CKEDITOR.replace('txtdescripcion', {
      lang: 'es',
      entities: false,
      allowedContent: true,
      ignoreEmptyParagraph: false,
      enterMode: CKEDITOR.ENTER_BR,
      uiColor: '#9AB8F3',
      toolbarGroups: [{
          "name": "basicstyles",
          "groups": ["basicstyles"]
        },
        {
          "name": "links",
          "groups": ["links"]
        },
        {
          "name": "paragraph",
          "groups": ["list", "blocks"]
        },
        {
          "name": "document",
          "groups": ["mode"]
        },
        {
          "name": "insert",
          "groups": ["insert"]
        },
        {
          "name": "styles",
          "groups": ["styles"]
        },
        {
          "name": "about",
          "groups": ["about"]
        }
      ],
      // Remove the redundant buttons from toolbar groups defined above.
      removeButtons: 'Underline,Strike,Subscript,Superscript,Anchor,Styles,Specialchar,Image,Format,Italic'
    });

    CKEDITOR.replace('txtdescripcioneditar', {
      lang: 'es',
      entities: false,
      allowedContent: true,
      ignoreEmptyParagraph: false,
      enterMode: CKEDITOR.ENTER_BR,
      uiColor: '#9AB8F3',
      toolbarGroups: [{
          "name": "basicstyles",
          "groups": ["basicstyles"]
        },
        {
          "name": "links",
          "groups": ["links"]
        },
        {
          "name": "paragraph",
          "groups": ["list", "blocks"]
        },
        {
          "name": "document",
          "groups": ["mode"]
        },
        {
          "name": "insert",
          "groups": ["insert"]
        },
        {
          "name": "styles",
          "groups": ["styles"]
        },
        {
          "name": "about",
          "groups": ["about"]
        }
      ],
      // Remove the redundant buttons from toolbar groups defined above.
      removeButtons: 'Underline,Strike,Subscript,Superscript,Anchor,Styles,Specialchar,Image,Format,Italic'
    });
  </script>