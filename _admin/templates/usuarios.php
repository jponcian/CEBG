<?php
session_start();
include_once "../../conexion.php";

if ($_SESSION['VERIFICADO'] != "SI") { 
header ("Location: ../validacion.php?opcion=val"); 
exit(); }

$acceso=41;
//------- VALIDACION ACCESO USUARIO
include_once "../../validacion_usuario.php";
//-----------------------------------
?>
<div class="container" ng-controller="usuariosController">
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

<!-- The Modal Agregar-->
<div class="modal font-modal" id="myModalUsuario">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header bg-fondo text-center">
        <h4 class="modal-title w-100 font-weight-bold py-2">Datos del Usuario a Incluir</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" ng-click="resetForm(formUsuario)">
          <span aria-hidden="true" class="white-text">&times;</span>
        </button>
      </div>

      <!-- Modal body -->
      <div class="modal-body bg-white">
       <form id="formUsuario" name="formUsuario" method="post" novalidate>
        <!--<div class="form-group">
          <div class="input-group mb-2">
            <div class="input-group-prepend">
              <div class="input-group-text"><i class="far fa-credit-card"></i></div>
            </div>
            <input type="text" class="form-control {{formUsuario.rif.$touched === true ? usuario.rif.length > 9 && rif_existe === false ? 'is-valid' : 'is-invalid' : ''}}" ng-change="buscarRif(usuario.rif)" id="rif" name="rif" ng-model="usuario.rif" placeholder="Numero de Rif" minlength="10" maxlength="10" required>
          </div>
                <div class="col-md-12" style="font-size: 10px; padding-left: 50px">              
                  <strong class="text-danger stretched-link text-right" ng-show="usuario.rif.length > 9 && rif_existe === true" role="alert">
                    Rif ya registrado
                  </strong>
                </div>
        </div>-->

        <div class="form-group">
          <div class="input-group mb-2">
            <div class="input-group-prepend">
              <div class="input-group-text"><i class="fas fa-file-signature"></i></div>
            </div>
            <input type="text" class="form-control {{formUsuario.nombre.$touched === true ? usuario_ang.nombre_usuario.length > 3 ? 'is-valid' : 'is-invalid' : ''}}" name="nombre" id="nombre" ng-model="usuario_ang.nombre_usuario" placeholder="Nombre del Usuario" minlength="4" maxlength="255" required mayusculastodo>
          </div>
        </div>

        <div class="form-group">
          <div class="input-group mb-2">
            <div class="input-group-prepend">
              <div class="input-group-text"><i class="fas fa-id-card-alt"></i></div>
            </div>
            <input type="text" ng-change="buscarCedulaUsuario(usuario_ang.cedula)" class="form-control {{formUsuario.cedula.$touched === true ? cedula_existe === false ? 'is-valid' : 'is-invalid' : ''}}" name="cedula" id="cedula"  ng-model="usuario_ang.usuario" placeholder="Cedula del Usuario" minlength="5" maxlength="10" required entero>
          </div>
                <div class="col-md-12" style="font-size: 10px; padding-left: 50px">              
                  <strong class="text-danger stretched-link text-right" ng-show="usuario_ang.cedula.length > 5 && cedula_existe === true" role="alert">
                    Usuario ya registrado
                  </strong>
                </div>
        </div>

        <div class="form-group">
          <div class="input-group mb-2">
            <div class="input-group-prepend">
              <div class="input-group-text"><i class="fas fa-user-check"></i></div>
            </div>
            <input type="text" ng-change="buscarUsuario(usuario_ang.user)" class="form-control {{formUsuario.user.$touched === true ? usuario_ang.user.length > 3 && usuario_existe === false ? 'is-valid' : 'is-invalid' : ''}}" name="user" id="user"  ng-model="usuario_ang.user" placeholder="Username" minlength="4" maxlength="16" required>
          </div>
                <div class="col-md-12" style="font-size: 10px; padding-left: 50px">              
                  <strong class="text-danger stretched-link text-right" ng-show="usuario_ang.user.length > 3 && usuario_existe === true" role="alert">
                    Usuario ya registrado
                  </strong>
                </div>
        </div>

        <div class="form-group">
          <div class="input-group mb-2">
            <div class="input-group-prepend">
              <div class="input-group-text"><i class="fas fa-key"></i></div>
            </div>
            <input type="password" class="form-control {{formUsuario.password.$touched === true ? usuario_ang.password.length > 3 ? 'is-valid' : 'is-invalid' : ''}}" id="password" name="password" ng-model="usuario_ang.password" placeholder="Contraseña" minlength="4" maxlength="12" required>
          </div>
        </div>

        <div class="form-group">
          <div class="input-group mb-2">
            <div class="input-group-prepend">
              <div class="input-group-text"><i class="far fa-envelope-open"></i></div>
            </div>
            <input type="email" class="form-control {{formUsuario.correo.$touched === true ? formUsuario.correo.$error.required === true || formUsuario.correo.$error.pattern === true ? 'is-invalid' : 'is-valid' : ''}}" placeholder="Correo Electrónico"  ng-pattern = '/^(([^<>()\[\]\.,;:\s@\"]+(\.[^<>()\[\]\.,;:\s@\"]+)*)|(\".+\"))@(([^<>()[\]\.,;:\s@\"]+\.)+[^<>()[\]\.,;:\s@\"]{2,})$/i' name="correo" id="correo" placeholder="Correo electronico" ng-model="usuario_ang.email" required mayusculastodo>
          </div>
        </div>

        <div class="form-group">
          <div class="input-group mb-2">
            <div class="input-group-prepend">
              <div class="input-group-text"><i class="fas fa-lock-open"></i></div>
            </div>
            <select name="tipo_acceso" class="custom-select {{formUsuario.tipo_acceso.$touched === true ? usuario_ang.tipo_acceso > 0 ? 'is-valid' : 'is-invalid' : ''}}" ng-model="usuario_ang.tipo_acceso">
              <option selected>Tipo de Acceso</option>
              <option ng-repeat="tipo in permisos" ng-value="tipo.acceso" ng-bind="tipo.descripcion"></option>
            </select>
          </div>
        </div>

      </form>

      </div>

      <!-- Modal footer -->
      <div class="modal-footer justify-content-center">
        <button id="agregarusuario" type="button" class="btn btn-outline-primary waves-effect" ng-click="agregarUsuario(formUsuario)"><i class="fas fa-cloud-upload-alt prefix grey-text mr-1"></i> Guardar</button>
      </div>

    </div>
  </div>
</div>
<!-- FIN MODL AGREGAR -->

<!-- The Modal EDITAR-->
<div class="modal font-modal" id="myModalEditUsuario">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header bg-fondo text-center">
        <h4 class="modal-title w-100 font-weight-bold py-2">Datos del Usuario a Modificar</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" ng-click="resetFormEditar(formUsuario)">
          <span aria-hidden="true" class="white-text">&times;</span>
        </button>
      </div>

      <!-- Modal body -->
      <div class="modal-body bg-white">
       <form id="formEditUsuario" name="formEditUsuario" method="post" novalidate>
        <!--<div class="form-group">
          <div class="input-group mb-2">
            <div class="input-group-prepend">
              <div class="input-group-text"><i class="far fa-credit-card"></i></div>
            </div>
            <input type="text" class="form-control {{formEditUsuario.editrif.$touched === true ? usuario.editrif.length > 9 && rif_existe === false ? 'is-valid' : 'is-invalid' : ''}}" ng-change="buscarRif(usuario.editrif)" id="editrif" name="editrif" ng-model="usuario.editrif" placeholder="Numero de Rif" minlength="10" maxlength="10" required>
          </div>
                <div class="col-md-12" style="font-size: 10px; padding-left: 50px">              
                  <strong class="text-danger stretched-link text-right" ng-show="usuario.editrif.length > 9 && rif_existe === true" role="alert">
                    Rif ya registrado
                  </strong>
                </div>
        </div>-->
        <div class="form-group">
          <div class="input-group mb-2">
            <div class="input-group-prepend">
              <div class="input-group-text"><i class="fas fa-file-signature"></i></div>
            </div>
            <input type="text" class="form-control {{formUsuario.editnombre.$touched === true ? editnombre.length > 3 ? 'is-valid' : 'is-invalid' : ''}}" name="editnombre" id="editnombre"  ng-model="editnombre" placeholder="Nombre del Usuario" minlength="4" maxlength="255" required mayusculastodo>
          </div>
        </div>

        <div class="form-group">
          <div class="input-group mb-2">
            <div class="input-group-prepend">
              <div class="input-group-text"><i class="fas fa-id-card-alt"></i></div>
            </div>
            <input type="text" ng-change="buscarCedulaUsuarioEditar(editcedula)" class="form-control {{formUsuario.editcedula.$touched === true ? edula_existe === false ? 'is-valid' : 'is-invalid' : ''}}" name="editcedula" id="editcedula"  ng-model="editcedula" placeholder="Cedula del Usuario" minlength="5" maxlength="10" required entero>
          </div>
                <div class="col-md-12" style="font-size: 10px; padding-left: 50px">              
                  <strong class="text-danger stretched-link text-right" ng-show="editcedula.length > 5 && cedula_existe === true" role="alert">
                    Usuario ya registrado
                  </strong>
                </div>
        </div>

        <div class="form-group">
          <div class="input-group mb-2">
            <div class="input-group-prepend">
              <div class="input-group-text"><i class="fas fa-user-check"></i></div>
            </div>
            <input type="text" ng-change="buscarUsuarioEditar(edituser)" class="form-control {{formEditUsuario.edituser.$touched === true ? edituser.length > 3 && usuario_existe === false ? 'is-valid' : 'is-invalid' : ''}}" name="edituser" id="edituser"  ng-model="edituser" placeholder="Username" minlength="4" maxlength="16" required>
          </div>
                <div class="col-md-12" style="font-size: 10px; padding-left: 50px">              
                  <strong class="text-danger stretched-link text-right" ng-show="edituser.length > 3 && usuario_existe === true" role="alert">
                    Usuario ya registrado
                  </strong>
                </div>
        </div>

        <div class="form-group">
          <div class="input-group mb-2">
            <div class="input-group-prepend">
              <div class="input-group-text"><i class="fas fa-key"></i></div>
            </div>
            <input type="password" class="form-control {{formEditUsuario.editpassword.$touched === true ? editpassword.length > 3 ? 'is-valid' : 'is-invalid' : ''}}" id="editpassword" name="editpassword" ng-model="editpassword" placeholder="Contraseña" minlength="4" maxlength="12" required>
          </div>
        </div>

        <div class="form-group">
          <div class="input-group mb-2">
            <div class="input-group-prepend">
              <div class="input-group-text"><i class="far fa-envelope-open"></i></div>
            </div>
            <input type="email" class="form-control {{formEditUsuario.editcorreo.$touched === true ? formEditUsuario.editcorreo.$error.required === true || formEditUsuario.editcorreo.$error.pattern === true ? 'is-invalid' : 'is-valid' : ''}}" placeholder="Correo Electrónico"  ng-pattern = '/^(([^<>()\[\]\.,;:\s@\"]+(\.[^<>()\[\]\.,;:\s@\"]+)*)|(\".+\"))@(([^<>()[\]\.,;:\s@\"]+\.)+[^<>()[\]\.,;:\s@\"]{2,})$/i' name="editcorreo" id="editcorreo" placeholder="Correo electronico" ng-model="editemail" required mayusculastodo>
          </div>
        </div>

        <div class="form-group">
          <div class="input-group mb-2">
            <div class="input-group-prepend">
              <div class="input-group-text"><i class="fas fa-lock-open"></i></div>
            </div>
            <select name="edittipo_acceso" class="custom-select {{formEditUsuario.edittipo_acceso.$touched === true ? edittipo_acceso > 0 ? 'is-valid' : 'is-invalid' : ''}}" ng-model="edittipo_acceso">
              <option selected>Tipo de Acceso</option>
              <option ng-repeat="tipo in permisos" ng-value="tipo.acceso" ng-bind="tipo.descripcion"></option>
            </select>
          </div>
        </div>

      </form>

      </div>

      <!-- Modal footer -->
      <div class="modal-footer justify-content-center">
        <button id="modificarusuario" type="button" class="btn btn-outline-primary waves-effect" ng-click="modificarUsuario(formEditUsuario)"><i class="fas fa-cloud-upload-alt prefix grey-text mr-1"></i> Guardar Cambios</button>
      </div>

    </div>
  </div>
</div>
<!-- FIN MODAL EDITAR -->
  <div class="row">
    <div class="titulo col-md-12 mb-3">
      <h3>Gestión de Usuarios</h3>
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
      <a href="" class="btn btn-outline-primary btn-rounded btn-sm font-weight-bold" data-toggle="modal" data-target="#myModalUsuario" data-backdrop="static" data-keyboard="false"><i class="fas fa-plus-circle"></i> Agregar Usuario</a>     
    </div>
  </div>
  <table class="table table-hover table-sm table-responsive-sm">
    <thead class="thead-dark">
      <tr>
        <th scope="col">Item</th>
        <th scope="col">Nombre Usuario</th>
        <th scope="col">Username</th>
        <th scope="col">email</th>
        <th scope="col">Tipo Acceso</th>
        <th scope="col">Acciones</th>
      </tr>
    </thead>
    <tbody>
      <tr ng-repeat="x in usuarios | filter:busqueda">
        <th scope="row" align="center">{{$index + 1}}</th>
        <td align="left">{{x.nombre_usuario}}</td>
        <td align="center">{{x.user}}</td>
        <td align="center">{{x.email}}</td>
        <td align="center">{{x.descripcion}}</td>
        <td align="center">
          <button type="button" class="btn btn-outline-success blue light-3 btn-sm" data-toggle="modal" data-target="#myModalEditUsuario" data-backdrop="static" data-keyboard="false" ng-click="cargarEditarUsuario(x.id)"><i class="fas fa-edit"></i></button> 
          <button type="button" class="btn btn-outline-danger btn-sm" ng-click="eliminarUsuario(x.id)"><i class="fas fa-trash-alt"></i></button>
        </td>
      </tr>
    </tbody>
  </table>

</div>

