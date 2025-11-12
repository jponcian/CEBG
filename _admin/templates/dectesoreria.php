<?php
session_start();
include_once "../../conexion.php";

if ($_SESSION['VERIFICADO'] != "SI") { 
header ("Location: ../validacion.php?opcion=val"); 
exit(); }

$acceso=31;
//------- VALIDACION ACCESO USUARIO
include_once "../../validacion_usuario.php";
//-----------------------------------
?>
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
    <div class="titulo col-md-12 mb-5">
      <h3>Generar Declaración Definitiva</h3>
    </div>
       <form id="formContribuyente" name="formContribuyente" method="post" novalidate>
          <div class="row ml-3">

        <div class="form-group">
        <div class="input-group">
          <div class="input-group-prepend">
            <span class="input-group-text"><i class="fas fa-search"></i></span>
          </div>
          <input type="text" ng-change="buscarRif()" class="form-control {{formContribuyente.rif.$touched === true ? buscar_rif.length > 9 && idcliente !== 0? 'is-valid' : 'is-invalid' : ''}}" name="rif" id="rif" ui-mask="A-99999999-9" ui-mask-placeholder placeholder="Numero de Rif" ng-model="buscar_rif" required mayusculastodo>
        </div>
          <div class="col-md-12" style="font-size: 10px; padding-left: 50px">              
            <strong class="text-danger stretched-link text-right" ng-show="contribuyente.rif.length > 9 && idcliente == 0" role="alert">
              Contribuyente NO registrado
            </strong>
          </div>
        </div>
 
        <div class="form-group">
        <div class="input-group">
          <button type="button" class="btn btn-outline-dark" ng-disabled="procesardeclaracion" ng-click="procesarDeclaracion()">PROCESAR DECLARACION</button>
        </div>
        </div>
      </div>
      </form>

  </div>

<!--<button id="btnImprimirDiv" ng-click="imprimir()">Imprimir</button>-->
<div class="container mt-2" ng-show="contribuyente.id > 0">
    <div class="row col-md-12">
      <span class="card-title">Nombre: {{contribuyente.nombre}}</span>
    <div class="row col-md-12">
      <span class="mb-2 text-muted">Rif: {{contribuyente.rif}}</span>     
    </div>
    <div class="row col-md-12">
      <span class="mb-2 text-muted">Domicilio: {{contribuyente.domicilio}}</span>
    </div>
    <div class="row col-md-12">
      <span class="mb-2 text-muted">Email: {{contribuyente.email}}</span>
    </div>
    <div class="row col-md-12">
      <span class="mb-2 text-muted">Representante Legal: {{contribuyente.representante}}</span>
    </div>
    <div class="row col-md-12">
      <span class="mb-2 text-muted">C.I. Representante: {{contribuyente.ced_representante}}</span>
    </div>
    <div class="row col-md-12">
      <span class="mb-2 text-muted">Telefóno de Contacto: {{contribuyente.cel_contacto}}</span>
    </div>
</div>

</div>

