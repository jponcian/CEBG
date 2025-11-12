<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');

if ($_SESSION['VERIFICADO'] != "SI") { 
header ("Location: ../validacion.php?opcion=val"); 
exit(); }

$acceso=77;
//------- VALIDACION ACCESO USUARIO
include_once "../validacion_usuario.php";
//-----------------------------------
?>
<form id="form999" name="form999" method="post" >
			<!-- Modal Header -->
<div class="modal-header bg-fondo text-center">
	<h4 align="center" style="background-color:#0275d8; color:#FFFFFF" class="modal-title w-100 font-weight-bold py-2">Registrar Cheque<button type="button" class="close" data-dismiss="modal">&times;</button></h4>
    <input type="hidden" id="oid" name="oid" value="0"/>
</div>
<!-- Modal body -->
		<div class="p-1">
			
			<div class="row">
			
				<div class="form-group col-sm-12">
					<div class="input-group-text"><i class="fas fa-sort-numeric-up mr-2"></i>
					<input id="txt_cheque" placeholder="Numero del Cheque" onkeyup="guardar3(event,'<?php echo $_GET['id']; ?>')" onkeypress="return SoloNumero(event,this);" maxlength="20" name="txt_cheque" class="form-control" type="text" style="text-align:center"/>
					</div>
				</div>
			</div>
			
		</div>
<!-- Modal footer -->
<div class="modal-footer justify-content-center">
	<button type="button" id="boton" class="btn btn-outline-success waves-effect" onclick="guardar2('<?php echo $_GET['id']; ?>')" ><i class="fas fa-save prefix grey-text mr-1"></i> Guardar</button>
</div>
<div id="div3"></div>
</form>
<script language="JavaScript">
listar_cheques(<?php echo $_GET['id']; ?>);
</script>