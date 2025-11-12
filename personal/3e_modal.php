<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');

if ($_SESSION['VERIFICADO'] != "SI") { 
header ("Location: ../validacion.php?opcion=val"); 
exit(); }

$acceso=96;
//----VALIDACION ACCESO USUARIO
include_once "../validacion_usuario.php";
//-----------------------------------
$id_proyecto = decriptar($_GET['id']); 
$estatus = ($_GET['estatus']); 
?>
<form id="form999a" name="form999a" method="post" >
			<!-- Modal Header -->
<div class="modal-header bg-fondo text-center">
	<h4 align="center" style="background-color:#0275d8; color:#FFFFFF" class="modal-title w-100 font-weight-bold py-2">Estatus de las Evaluaciones 
	  <button type="button" class="close" data-dismiss="modal">&times;</button></h4>
</div>
    <input type="hidden" id="oid" name="oid" value="<?php echo encriptar($id_proyecto); ?>"/>
<!-- Modal body -->
	
<br>
<div class="p-1">

	<div class="form-group col-sm-12">
		<div class="input-group">
	<!--		<div class="input-group-text">Estatus:</div>-->
			<select class="select2" style="width: 600px" style="font-size: 14px" name="txt_estatus" id="txt_estatus" onchange="validar_campo('txt_estatus');">
			<?php if ($estatus<0) { ?><option <?php if ($estatus==0) {echo 'selected';} ?> value="0">REGISTRADA</option><?php } ?>
			<?php if ($estatus<2) { ?><option <?php if ($estatus==2) {echo 'selected';} ?> value="2">ASIGNAR ODIS</option><?php } ?>
			<?php if ($estatus<4) { ?><option <?php if ($estatus==4) {echo 'selected';} ?> value="4">ACEPTAR ODIS</option><?php } ?>
			<?php if ($estatus<6) { ?><option <?php if ($estatus==6) {echo 'selected';} ?> value="6">EVALUACIONES</option><?php } ?>
			<?php if ($estatus<8) { ?><option <?php if ($estatus==8) {echo 'selected';} ?> value="8">ACEPTAR EVALUACIONES</option><?php } ?>
			<?php if ($estatus<10) { ?><option <?php if ($estatus==10) {echo 'selected';} ?> value="10">PROCESO CULMINADO</option><?php } ?>
			</select>
		</div>
	</div>

		<!-- Modal footer -->
	<div class="modal-footer justify-content-center">
	<button type="button" id="boton" class="btn btn-outline-success waves-effect" onclick="guardar_estatus()" ><i class="fas fa-save prefix grey-text mr-1"></i>Guardar</button>
	</div>
	
</div>
</form>
<script language="JavaScript">
// PARA EL SELECT2
$(document).ready(function() {
    $('.select2').select2();
});
//----------------
function guardar_estatus()
 {
	var parametros = $("#form999a").serialize(); 
	$.ajax({  
		type : 'POST',
		url  : 'personal/3f_guardar.php',
		dataType:"json",
		data:  parametros, 
		success:function(data) {  	
			if (data.tipo=="info")
				{	alertify.success(data.msg);	
				 	$('#modal_normal .close').click(); 
					buscar();
				}
			else
				{	
					Swal.fire({
				//		  title: 'Informacion!',
						  icon: 'info',				
						  html: data.msg,				
						  timer: 4500,				
						  timerProgressBar: true,				
						  showDenyButton: false,
						  showCancelButton: false
						})
//					document.form999.txt_codigo.focus();
			}  
			}  
		});
 }
</script>