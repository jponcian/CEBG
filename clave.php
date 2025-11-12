<?php
session_start();
include_once "conexion.php";
include_once "funciones/auxiliar_php.php";

if ($_SESSION['VERIFICADO'] != "SI") { 
header ("Location: validacion.php?opcion=val"); 
exit(); }
?>
<form id="form999" name="form999" method="post" >
			<!-- Modal Header -->
<div class="modal-header bg-fondo text-center">
	<h4 align="center" style="background-color:#0275d8; color:#FFFFFF" class="modal-title w-100 font-weight-bold py-2">Cambio de Contraseña 
	  <button type="button" class="close" data-dismiss="modal">&times;</button></h4>
</div>
<!-- Modal body -->
		<div class="p-1">
			
			<div class="row">		
												
				<div class="form-group col-sm-6">
					<div class="input-group">
						<input id="txt_actual" onkeyup="saltar(event,'txt_nueva')" placeholder="Clave Actual" name="txt_actual" class="form-control" type="password" style="text-align:center" />
					</div>
				</div>
				
				<div class="form-group col-sm-6">
					<div class="input-group">
						<input id="txt_nueva" placeholder="Clave Nueva" name="txt_nueva" class="form-control" type="password" style="text-align:center" maxlength="20" />
					</div>
				</div>
				
			</div>
			
		</div>
<!-- Modal footer -->
<div class="modal-footer justify-content-center">
	<button type="button" class="btn btn-outline-success waves-effect" onclick="cambiar_clave()" ><i class="fas fa-save prefix grey-text mr-1"></i>Guardar</button>
</div>
<div class="modal-footer justify-content-center">
	<button type="button" class="btn btn-outline-danger waves-effect" onclick="bdd()" ><i class="mr-1"></i>Usar Sistema de Prueba</button>
</div>
</form>
<script language="JavaScript">
//--------------------------------
setTimeout(function()	{
		$('#txt_actual').focus();
		},500)	
//--------------------------------
function cambiar_clave()
	{
	alertify.confirm("Estas seguro de Cambiar la Contraseña?", function()
		{
		var parametros = $("#form999").serialize(); 
		$.ajax({  
		type : 'POST',
		url  : 'clave_guardar.php',
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
//--------------------------------
function bdd()
	{
	alertify.confirm("Estas seguro de Activar el Sistema de Prueba?", function()
		{
		var parametros = $("#form999").serialize(); 
		$.ajax({  
		type : 'POST',
		url  : 'bdd.php',
		dataType:"json",
		data:  parametros, 
		success:function(data) {  	
			if (data.tipo=="info")
				{	alertify.success('Sistema de Prueba Activado');	$('#modal_normal .close').click(); document.getElementById('fondob').style.backgroundColor = 'dimgray';	}
			//--------------
			}  
		});
		});
	}
</script>