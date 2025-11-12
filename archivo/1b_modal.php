<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');

if ($_SESSION['VERIFICADO'] != "SI") { 
header ("Location: ../validacion.php?opcion=val"); 
exit(); }

$acceso=112;
if ($_GET['id']>0)	{$id = $_GET['id'];} else {$id = 0;} 
//------- VALIDACION ACCESO USUARIO
include_once "../validacion_usuario.php";
//-----------------------------------
$consultx = "SELECT * FROM arc_biblioteca WHERE id = $id;";  //echo $consultx;
$tablx = $_SESSION['conexionsql']->query($consultx);
if ($tablx->num_rows>0)	
	{
	$registro = $tablx->fetch_object();
	//--------
	$id = $registro->id;
	$grupo = $registro->grupo;
	$numero = $registro->numero;
	$descripcion = $registro->descripcion;
	}
?>
<form id="form999" name="form999" method="post" >
			<!-- Modal Header -->
<div class="modal-header bg-fondo text-center">
	<h4 align="center" style="background-color:#0275d8; color:#FFFFFF" class="modal-title w-100 font-weight-bold py-2">Registrar Expediente(s) 
	  <button type="button" class="close" data-dismiss="modal">&times;</button></h4>
    <input type="hidden" id="oid" name="oid" value="<?php echo $id; ?>"/>
</div>
<br>
<!-- Modal body -->
		<div class="p-1">
						
			<div class="row">
				<div class="form-group col-sm-6">
					<div class="input-group">
						<div class="input-group-text" align="center">Grupo</div>
						<input maxlength="100" onkeyup="saltar(event,'txt_numero')"  placeholder="Grupo" id="txt_grupo" name="txt_grupo" class="form-control" type="text" style="text-align:left" value="<?php echo $grupo; ?>" />
					</div>
				</div>
			</div>

			<div class="row">
				<div class="form-group col-sm-6">
					<div class="input-group">
						<div class="input-group-text" align="center">Numero</div>
						<input maxlength="100" id="txt_numero" onkeyup="saltar(event,'txt_descripcion')" placeholder="Numero" name="txt_numero" class="form-control" type="text" value="<?php echo $numero; ?>"/>
					</div>
				</div>
			</div>
			
			<div class="row">
				<div class="form-group col-sm-12">
					<div class="input-group">
						<div class="input-group-text" align="center">Descripcion</div>
						<textarea id="txt_descripcion" name="txt_descripcion" placeholder="Descripcion del Contenido" class="form-control" rows="3" ><?php echo $descripcion; ?></textarea>
					</div>
				</div>
			</div>
			
		</div>
<!-- Modal footer -->
<div class="modal-footer justify-content-center">
	<button type="button" id="boton" class="btn btn-outline-success waves-effect" onclick="guardar()" ><i class="fas fa-save prefix grey-text mr-1"></i> Guardar Cambios</button>
</div>
</div>
</div>
</form>
<script language="JavaScript">
// PARA EL SELECT2
$(document).ready(function() {
    $('.select2').select2();
	//--------------------------------
	setTimeout(function()	{
			$('#txt_grupo').focus();
			},500)	
});
//--------------------------------
//$("#txt_valor").on({
//    "focus": function (event) {
//        $(event.target).select();
//    },
//    "keyup": function (event) {
//        $(event.target).val(function (index, value ) {
//            return value.replace(/\D/g, "")
//                        .replace(/([0-9])([0-9]{2})$/, '$1,$2')
//                        .replace(/\B(?=(\d{3})+(?!\d)\.?)/g, ".");
//        });
//    }
//});
</script>