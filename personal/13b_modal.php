<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');

if ($_SESSION['VERIFICADO'] != "SI") { 
header ("Location: validacion.php?opcion=val"); 
exit(); }

$acceso=100;
//------- VALIDACION ACCESO USUARIO
include_once "../validacion_usuario.php";
//-----------------------------------
?>
<form id="form999" name="form999" method="post" >
			<!-- Modal Header -->
<div class="modal-header bg-fondo text-center">
	<h4 align="center" style="background-color:#0275d8; color:#FFFFFF" class="modal-title w-100 font-weight-bold py-2">Nueva Competencia
	  <button type="button" class="close" data-dismiss="modal">&times;</button></h4>
</div>
<!-- Modal body -->
<div class="p-1">
			
<br>

	<div class="form-group col-sm-12">
		<div class="input-group">
			<input onchange="validar_campo('txt_descripcion');" id="txt_descripcion" name="txt_descripcion" class="form-control" type="text" placeholder="Descripcion" maxlength="250" value="" />
		</div>
	</div>

	</div>
<!-- Modal footer -->
<div class="modal-footer justify-content-center">
	<button type="button" id="boton" class="btn btn-outline-success waves-effect" onclick="generar_poa()" ><i class="fas fa-save prefix grey-text mr-1"></i>Guardar</button>
</div>
</form>
<script language="JavaScript">
//----------------
function generar_poa()
	{
		//-----------------------
		var parametros = $("#form999").serialize(); 
		$.ajax({  
		type : 'POST',
		url  : 'personal/13f_guardar.php',
		dataType:"json",
		data:  parametros, 
		success:function(data) {  	
			if (data.tipo=="info")
				{	alertify.success(data.msg);	$('#modal_largo .close').click(); buscar();	}
			else
				{	Swal.fire(data.msg, '', data.tipo)	}
			//--------------
			}  
		});
	}
</script>