<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');

if ($_SESSION['VERIFICADO'] != "SI") { 
header ("Location: ../validacion.php?opcion=val"); 
exit(); }

$acceso=64;
//------- VALIDACION ACCESO USUARIO
include_once "../validacion_usuario.php";
//-----------------------------------
//$consultx = "SELECT * FROM rac WHERE rac = ".$_GET['id'].";";  //echo $consultx;
//$tablx = $_SESSION['conexionsql']->query($consultx);
//$registro = $tablx->fetch_object();
?>
<form id="form999" name="form999" method="post">
    <!-- Modal Header -->
    <div class="modal-header bg-fondo text-center">
        <h4 align="center" style="background-color:#0275d8; color:#FFFFFF" class="modal-title w-100 font-weight-bold py-2">Agregar Registro<button type="button" class="close" data-dismiss="modal">&times;</button></h4>
        <input type="hidden" id="oid" name="oid" value="0" />
    </div>
    <!-- Modal body -->
    <div class="p-1">
        <div class="row">
            <div class="form-group col-sm-12">
                <div class="input-group">
					<input value="" id="txt_descripcion" placeholder="Motivo de Atención" onFocus="this.select()" name="txt_descripcion" class="form-control" type="text" />
					</div>
            </div>
        </div>
        
    </div>
    <!-- Modal footer -->
    <div class="modal-footer justify-content-center">
        <button type="button" id="boton" class="btn btn-outline-success waves-effect" onclick="guardar(0)"><i class="fas fa-save prefix grey-text mr-1"></i> Guardar</button>
    </div>
    </div>
    </div>
</form>
<script language="JavaScript">
//--------------------------------
setTimeout(function()	{
		$('#txt_descripcion').focus();
		},700)	
</script>