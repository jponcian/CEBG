<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');

if ($_SESSION['VERIFICADO'] != "SI") { 
header ("Location: ../validacion.php?opcion=val"); 
exit(); }

$acceso=8;
//------- VALIDACION ACCESO USUARIO
include_once "../validacion_usuario.php";
//-----------------------------------
?>
<meta http-equiv="X-UA-Compatible" content="IE=edge">

<form id="form888" name="form888" method="post" onSubmit="return evitar();" >
			<!-- Modal Header -->
<div class="modal-header bg-fondo text-center">
<h4 align="center" style="background-color:#0275d8; color:#FFFFFF" class="modal-title w-100 font-weight-bold py-2">JUSTIFICACION
<button type="button" class="close" data-dismiss="modal" >&times;</button></h4>
</div>
<!-- Modal body -->
		
</br>	

<div class="row">
	<div class="form-group col-sm-12">
<textarea id="txt_observacion" name="txt_observacion" placeholder="Observaciones" class="form-control" rows="2" ></textarea></div>
	</div>


</div>

<div align="center">			
<button type="button" id="boton" class="btn btn-outline-success waves-effect" onclick="guardar('<?php echo encriptar($_GET['id']); ?>', '0')" ><i class="fas fa-save prefix grey-text mr-1"></i> Guardar</button>
	
<button type="button" id="boton2" class="btn btn-outline-success waves-effect" onclick="guardar('<?php echo encriptar($_GET['id']); ?>', '1')" ><i class="fas fa-save prefix grey-text mr-1"></i> Guardar & Cambiar</button></div>

</form>

<script language="JavaScript">
//------------------------------ PARA ELIMINAR
function guardar(id, boton)
	{
//	alertify.confirm("Estas seguro de Aprobar y Enviar la Correspondencia?",  
//	function()
//			{ 
			$('#boton').hide();
			var parametros = $("#form888").serialize();
			$.ajax({
			url: "seguridad/5d_guardar.php?id="+id+'&boton='+boton,
			type: "POST",
			dataType:"json",
			data: parametros,
			success: function(data) {  	
			if (data.tipo=="info")
				{	$('#modal_normal .close').click();	alertify.success(data.msg);	listar_tabla(); 
					//window.open("correspondencia/formatos/memo_dir.php?p=1&origen="+data.origen+"&destino="+data.destino+"&estatus=0&id="+id,"_blank");
				}
			else
				{	alertify.alert(data.msg);	}
			//--------------
			} 
			});
		//});
	}
//--------------------------------
setTimeout(function()	{
	$('#txt_concepto').focus();
	},1000)	
//--------------------------------
</script>