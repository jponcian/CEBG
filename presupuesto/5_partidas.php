<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');

if ($_SESSION['VERIFICADO'] != "SI") { 
header ("Location: ../validacion.php?opcion=val"); 
exit(); }

$acceso=69;
//------- VALIDACION ACCESO USUARIO
include_once "../validacion_usuario.php";
//-----------------------------------
?>
<form id="form1" name="form1" method="post" onsubmit="return evitar();" >
<br>
<div  class="text-right mb-3"><a href="" class="btn btn-outline-primary btn-rounded btn-sm font-weight-bold" onclick="agregar();" data-toggle="modal" data-target="#modal_largo" data-keyboard="false"><i class="fas fa-plus-circle" ></i> Agregar Actividad</a></div>
<!-- <br>-->
<div class="TituloTablaP" height="41" colspan="10" align="center">Ejecución Presupuestaria Anual => <select style="font-size: 14px" name="txt_anno" id="txt_anno" onChange="buscar();">
			<?php
			$i = date ('Y');
			while ($i>=2022)
			//-------------
			{
			echo '<option ';
			echo ' value="';
			echo $i;
			echo '">Ejercicio ';
			echo $i;
			echo '</option>';
			$i--;
			}
			?>
		</select></div>
	<div id="div2"></div>
</form>
<script language="JavaScript">
//-------------
function partida(id)
{
	//alertify.alert('Espere mientras se actualiza la Solicitud...');
	var parametros = "id=" + id; 
	$.ajax({  
		type : 'POST',
		url  : 'administracion/13l_partida.php',
		dataType:"json",
		data:  parametros, 
		success:function(data) {  	
			if (data.tipo=="info")
				{	 document.form999.txt_descripcion.value = (data.msg);	
					document.form999.txt_descripcion.focus();}
			//--------------
			} 
		});
}
buscar();
//----------------
function eliminar2(partida, categoria, anno)
	{
	alertify.confirm("Estas seguro de eliminar la Partida Registrada?",  
	function()
			{ 
			var parametros = "partida=" + partida + "&categoria=" + categoria+ "&anno=" + anno;
			$.ajax({
			url: "presupuesto/5h_eliminar.php",
			type: "POST",
			dataType:"json",
			data: parametros,
			success: function(data) {
			if (data.tipo=="info")
				{	alertify.success(data.msg);	
					listar_partidas(categoria, anno);
				}
			else
				{	alertify.alert(data.msg);	}
			}
			});
		});
}
//----------------
function eliminar(id, anno)
	{
	alertify.confirm("Estas seguro de eliminar La Actividad Registrada?",  
	function()
			{ 
			var parametros = "id=" + id + "&anno=" + anno;
			$.ajax({
			url: "presupuesto/5c_eliminar.php",
			type: "POST",
			dataType:"json",
			data: parametros,
			success: function(data) {
			if (data.tipo=="info")
				{	alertify.success(data.msg);	
					buscar();
				}
			else
				{	alertify.alert(data.msg);	}
			}
			});
		});
}
//----------------
function guardar()
 {
	var parametros = $("#form999").serialize(); 
	$.ajax({  
		type : 'POST',
		url  : 'presupuesto/5d_guardar.php',
		dataType:"json",
		data:  parametros, 
		success:function(data) {  	
			if (data.tipo=="info")
				{	alertify.success(data.msg);	
					$('#modal_largo .close').click(); 
					buscar();
				}
			else
				{	alertify.alert(data.msg);	}
			}  
		});
 }
//----------------
function cheques(id, anno)
	{
	$('#modal_lg').html('<div align="center"><div class="spinner-border" role="status"></div><br><strong>Un momento, por favor...</strong></div>');
	$('#modal_lg').load('presupuesto/5e_modal.php?id='+id+ "&anno=" + anno);
	}
//----------------
function agregar()
	{
	$('#modal_lg').html('<div align="center"><div class="spinner-border" role="status"></div><br><strong>Un momento, por favor...</strong></div>');
	$('#modal_lg').load('presupuesto/5b_modal.php');
	}
//----------------
function buscar()
	{
	$('#div2').html('<div align="center"><div class="spinner-border" role="status"></div><br><strong>Un momento, por favor...</strong></div>');
	$('#div2').load('presupuesto/5a_tabla.php?anno='+document.form1.txt_anno.value);
	}
</script>