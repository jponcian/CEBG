<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');

if ($_SESSION['VERIFICADO'] != "SI") { 
header ("Location: ../validacion.php?opcion=val"); 
exit(); }

$acceso=91;
//------- VALIDACION ACCESO USUARIO
include_once "../validacion_usuario.php";
//-----------------------------------
$id = $_GET['id'];

$consultx = "SELECT bn_reasignaciones.* FROM bn_reasignaciones WHERE id=$id LIMIT 1;";
$tablx = $_SESSION['conexionsql']->query($consultx);
$registro = $tablx->fetch_object();
?>
<form id="form999" name="form999" method="post" >
			<!-- Modal Header -->
<div class="modal-header bg-fondo text-center">
<input type="hidden" id="oid" name="oid" value="<?php  echo $_GET['id'];?>"/>
<h4 align="center" style="background-color:#0275d8; color:#FFFFFF" class="modal-title w-100 font-weight-bold py-2">Modificar<button type="button" class="close" data-dismiss="modal">&times;</button></h4>
</div>
	<input id="oid" name="oid" type="hidden" value="<?php echo ($id);?>" required>
<!-- Modal body -->
		<div class="p-1">

			<div class="row">
						
		<div class="form-group col-sm-6">
			<div class="input-group">
				<div class="input-group-text">Numero</div>
				<input onfocus="this.select()" id="txt_numero" name="txt_numero" type="text" style="text-align:center" class="form-control " value="<?php echo ($registro->numero);?>" required></div>
		</div>	
		
		<div class="form-group col-sm-6">
			<div class="input-group">
				<div class="input-group-text"><i class="far fa-calendar-alt mr-2"></i>Fecha</div>
				<input id="txt_fecha" name="txt_fecha" type="text" style="text-align:center" class="form-control " value="<?php echo voltea_fecha($registro->fecha);?>" required></div>
		</div>	
		
	</div>
	</div>
		<br>	
			
<div align="center">			
	<button type="button" id="boton" class="btn btn-outline-success waves-effect" onclick="guardar('boton')" ><i class="fas fa-save prefix grey-text mr-1"></i> Guardar Cambios</button>			
</div>
	
	</div>
		</div>
<!-- Modal footer -->
<div class="modal-footer justify-content-center">
</div>

</form>
<script language="JavaScript">
//--------------------------------
setTimeout(function()	{
		$('#txt_numero').focus();
		},1000)	
//--------------------------------
$("#txt_fecha").datepicker();
</script>