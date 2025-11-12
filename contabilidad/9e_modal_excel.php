<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');

if ($_SESSION['VERIFICADO'] != "SI") { 
header ("Location: ../validacion.php?opcion=val"); 
exit(); }
?>
<form id="form999" name="form999" method="post" action="contabilidad/9g_subir.php" enctype="multipart/form-data">
			<!-- Modal Header -->
<div class="modal-header bg-fondo text-center">
	<h4 align="center" style="background-color:<?php echo $_SESSION['COLOR']; ?>; color:#FFFFFF" class="modal-title w-100 font-weight-bold py-2">Subir Estado de Cuenta
	  <button type="button" class="close" data-dismiss="modal">&times;</button></h4>
</div>
<!-- Modal body -->
		<div class="p-1">
		
<div class="row">
	<div class="form-group col-sm-12">
		<a data-toggle="tooltip" title="BANCO">
            <select class="form-control" name="txt_banco" id="txt_banco"  >
             <?php
			$consulta_x = 'SELECT * FROM a_cuentas WHERE id;'; 
			//---------------
			$tabla_x = $_SESSION['conexionsql']->query($consulta_x);
			while ($registro_x = $tabla_x->fetch_array())
				{
				echo '<option value='.$registro_x['id'].'>'.$registro_x['banco'].' '.$registro_x['cuenta'].' '.$registro_x['descripcion'].'</option>';
				}
			?>
            </select>
          </a>
	</div>
</div>
			
<div class="row">
	
	<div class="form-group col-sm-7">
		<input type="file" id="uploadedFile" name="uploadedFile" class="form-control"/>
	</div>
	
	<div class="form-group col-sm-4">	
		<button type="submit" class="btn btn-outline-success btn-sm"><i class="fas fa-cloud-upload-alt prefix grey-text mr-2"></i>Subir Archivo</button>
	</div>
</div>
			
		</div>
<!-- Modal footer -->
<div id="div3"></div>
</form>
<script language="JavaScript">
//------------------------------
//function subir_excel()
//	{
//	var parametros = $("#form999").serialize(); 
//	$.ajax({  
//		type : 'POST',
//		url  : 'contabilidad/9g_subir.php',
//		dataType:"json",
//		data:  parametros, 
//		success:function(data) {  	
//			if (data.tipo=="info")
//				{	alertify.success(data.msg);	listar_excel(); }
//			else
//				{	alertify.alert(data.msg);	}
//			//--------------
//			} 
//		 
//		});
//	}
//------------------------------ PARA ELIMINAR
function eliminar_edo_cta(id)
	{
	alertify.confirm("Estas seguro de eliminar el Registro?",  
	function()
			{ 
			var parametros = "id=" + id;
			$.ajax({
			url: "contabilidad/9h_eliminar.php",
			type: "POST",
			data: parametros,
			success: function(r) {
			alertify.success('Registro Eliminado Correctamente');
			//--------------
			listar_excel();
			}
			});
		});
	}
//----------------
listar_excel(); 
//----------------
function listar_excel()
	{
	$('#div3').html('<div align="center"><img width="125" height="125" src="images/espera(1).gif"/><br/>Un momento, por favor...</div>');
	$('#div3').load('contabilidad/9f_tabla.php');
	}
</script>