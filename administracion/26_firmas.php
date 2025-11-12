<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');

if ($_SESSION['VERIFICADO'] != "SI") { 
header ("Location: validacion.php?opcion=val"); 
exit(); }

$acceso=22;
//------- VALIDACION ACCESO USUARIO
include_once "../validacion_usuario.php";
//-----------------------------------
?>
<form id="form999" name="form999" method="post" >
			<!-- Modal Header -->
<div class="modal-header bg-fondo text-center">
	<h4 align="center" style="background-color:#0275d8; color:#FFFFFF" class="modal-title w-100 font-weight-bold py-2">Cambio de Firmas 
	  <button type="button" class="close" data-dismiss="modal">&times;</button></h4>
</div>
<!-- Modal body -->
		<div class="p-1">
			
			<div class="row">		
												
				<div class="form-group col-sm-5">
					<div class="input-group">
						<select onchange="combo(this.value);" class="custom-select" style="font-size: 16px" name="txt_firma" id="txt_firma">
						<option value="0" >Seleccione</option>
							<?php
						$consultx = "SELECT * FROM a_firmas ORDER BY id_direccion, id;"; 
						$tablx = $_SESSION['conexionsql']->query($consultx);
						while ($registro_x = $tablx->fetch_object())
						//-------------
						{
						echo '<option ';
						echo ' value="';
						echo $registro_x->id.'-'.$registro_x->cedula;
						echo '">';
						echo ($registro_x->formato);
						echo '</option>';
						}
						?>
						</select>
					</div>
				</div>
				
				<div class="form-group col-sm-7">
					<div class="input-group">
						<select class="select2" style="width: 440px" name="txt_ci" id="txt_ci">
						<option value="0">Seleccione</option>
						</select>
					</div>
				</div>
				
			</div>
			
		</div>
<!-- Modal footer -->
<div class="modal-footer justify-content-center">
	<button type="button" id="boton" class="btn btn-outline-success waves-effect" onclick="guardar_firma()" ><i class="fas fa-save prefix grey-text mr-1"></i>Guardar</button>
</div>
</form>
<script language="JavaScript">
// PARA EL SELECT2
$(document).ready(function() {
    $('.select2').select2();
});
//--------------------------------
setTimeout(function()	{
		$('#txt_actual').focus();
		},500)	
//-------------
function combo(id)
{
	$.ajax({
        type: "POST",
        url: 'administracion/26c_combo.php?id='+id,
        success: function(resp){
            $('#txt_ci').html(resp);
        }
    });
}
//--------------------------------
function guardar_firma()
	{
	alertify.confirm("Estas seguro de Guardar los Cambios?", function()
		{
		var parametros = $("#form999").serialize(); 
		$.ajax({  
		type : 'POST',
		url  : 'administracion/26a_guardar.php',
		dataType:"json",
		data:  parametros, 
		success:function(data) {  	
			if (data.tipo=="info")
				{	alertify.success(data.msg);	//$('#modal_largo .close').click();	
				}
			else
				{	alertify.alert(data.msg);	}
			//--------------
			}  
		});
		});
	}
</script>