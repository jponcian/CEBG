<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');

if ($_SESSION['VERIFICADO'] != "SI") { 
header ("Location: validacion.php?opcion=val"); 
exit(); }

$acceso=34;
//------- VALIDACION ACCESO USUARIO
include_once "../validacion_usuario.php";
//-----------------------------------
?>
<form id="form999" name="form999" method="post" >
			<!-- Modal Header -->
<div class="modal-header bg-fondo text-center">
	<h4 align="center" style="background-color:#0275d8; color:#FFFFFF" class="modal-title w-100 font-weight-bold py-2">Comprometer Numero de Orden 
	  <button type="button" class="close" data-dismiss="modal">&times;</button></h4>
</div>
<!-- Modal body -->
		<div class="p-1">
			
			<div class="row">		
												
				<div class="form-group col-sm-6">
					<div class="input-group">
						<input class="form-control" type="text" style="text-align:center" maxlength="20" value="<?php echo orden_sig(); ?>" readonly />
					</div>
				</div>
				
				<div class="form-group col-sm-6">
					<div class="input-group">
						<input class="form-control" type="text" style="text-align:center" maxlength="20" value="<?php echo date('d/m/Y'); ?>" readonly />
					</div>
				</div>
				
			</div>
			
		</div>
<!-- Modal footer -->
<div class="modal-footer justify-content-center">
	<button type="button" id="boton" class="btn btn-outline-success waves-effect" onclick="apartar_num()" ><i class="fas fa-save prefix grey-text mr-1"></i>Apartar Numero</button>
</div>
</form>
<script language="JavaScript">
//--------------------------------
function apartar_num()
	{
	alertify.confirm("Estas seguro de comprometer la Orden de Pago?", function()
		{
		var parametros = $("#form999").serialize(); 
		$.ajax({  
		type : 'POST',
		url  : 'administracion/19a_guardar.php',
		dataType:"json",
		data:  parametros, 
		success:function(data) {  	
			if (data.tipo=="info")
				{	alertify.success(data.msg);	$('#modal_normal .close').click();	}
			else
				{	alertify.alert(data.msg);	}
			//--------------
			}  
		});
		});
	}
</script>