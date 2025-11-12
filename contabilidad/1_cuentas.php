<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');

if ($_SESSION['VERIFICADO'] != "SI") { 
header ("Location: ../validacion.php?opcion=val"); 
exit(); }

$acceso=76;
//------- VALIDACION ACCESO USUARIO
include_once "../validacion_usuario.php";
//-----------------------------------
$_SESSION['estatus'] = array("Activa","Anulada");
$_SESSION['activar'] = array("1","0");
$_SESSION['boton'] = array("Anular","Activar");
?>
<form id="form1" name="form1" method="post" onsubmit="return evitar();" >
<br>
<div  class="text-right mb-3"><a href="" class="btn btn-outline-primary btn-rounded btn-sm font-weight-bold" onclick="agregar();" data-toggle="modal" data-target="#modal_largo" data-keyboard="false"><i class="fas fa-plus-circle" ></i> Agregar Cuenta</a></div>
 <br>
<div id="div2"></div>
</form>
<script language="JavaScript">
buscar();
//----------------
function activar(id,estatus)
{
	var parametros = "id=" + id+ "&estatus=" + estatus;
	$.ajax({
	url: "contabilidad/1e_activar.php",
	type: "POST",
	data: parametros,
	success: function(r) {
	alertify.success('Cuenta Actualizada Correctamente');
	//--------------
	buscar();
	}
	})
}
//----------------
function eliminar(id)
	{
	alertify.confirm("Estas seguro de eliminar La Cuenta Registrada?",  
	function()
			{ 
			var parametros = "id=" + id;
			$.ajax({
			url: "contabilidad/1c_eliminar.php",
			type: "POST",
			data: parametros,
			success: function(r) {
			alertify.success('Registro Eliminado Correctamente');
			//--------------
			buscar();
			}
			});
		});
}
//----------------
function guardar(tipo)
 {
	var parametros = $("#form999").serialize(); 
	$.ajax({  
		type : 'POST',
		url  : 'contabilidad/1d_guardar.php',
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
function agregar()
	{
	$('#modal_lg').html('<div align="center"><div class="spinner-border" role="status"></div><br><strong>Un momento, por favor...</strong></div>');
	$('#modal_lg').load('contabilidad/1b_modal.php');
	}
//----------------
function buscar()
	{
	$('#div2').html('<div align="center"><div class="spinner-border" role="status"></div><br><strong>Un momento, por favor...</strong></div>');
	$('#div2').load('contabilidad/1a_tabla.php');
	}
</script>