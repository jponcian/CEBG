<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');

if ($_SESSION['VERIFICADO'] != "SI") { 
header ("Location: ../validacion.php?opcion=val"); 
exit(); }

$acceso=89;
//------- VALIDACION ACCESO USUARIO
include_once "../validacion_usuario.php";
//-----------------------------------
?>
<form id="form1" name="form1" method="post" onsubmit="return evitar();" >
<br>
<div  class="text-right mb-3"><a href="" class="btn btn-outline-primary btn-rounded btn-sm font-weight-bold" onclick="agregar();" data-toggle="modal" data-target="#modal_normal" data-keyboard="false"><i class="fas fa-plus-circle" ></i> Agregar Nomina</a></div>
<!-- <br>-->
<div class="TituloTablaP" height="41" colspan="10" align="center">Montos Asignados por Nomina para Ayuda Económica</div>
	<div id="div2"></div>
</form>
<script language="JavaScript">
buscar();
//----------------
function eliminar2(id, codigo)
	{
	alertify.confirm("Estas seguro de eliminar el Cargo Registrado?",  
	function()
			{ 
			var parametros = "id=" + id + "&codigo="+codigo;
			$.ajax({
			url: "personal/7h_eliminar.php",
			type: "POST",
			dataType:"json",
			data: parametros,
			success: function(data) {
			if (data.tipo=="info")
				{	alertify.success(data.msg);	
					listar_partidas(codigo);
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
	Swal.fire({
	title: 'Estas seguro de eliminar La Nomina Registrada?',
	text: "Esta acción no se puede revertir!",
	icon: 'question',
	showCancelButton: true,
	confirmButtonColor: '#3085d6',
	cancelButtonColor: '#d33',
	confirmButtonText: 'Si, borrar!',
	cancelButtonText: 'Cancelar'
	}).then((result) => {
	if (result.isConfirmed) {
		//-----------------------
			var parametros = "id=" + id + "&anno=" + anno;
			$.ajax({
			url: "personal/7c_eliminar.php",
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
		//-----------------------
		}
	})
}
//----------------
function guardar()
 {
	var parametros = $("#form999").serialize(); 
	$.ajax({  
		type : 'POST',
		url  : 'personal/7d_guardar.php',
		dataType:"json",
		data:  parametros, 
		success:function(data) {  	
			if (data.tipo=="info")
				{	alertify.success(data.msg);	
					$('#modal_normal .close').click(); 
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
	$('#modal_lg').load('personal/7e_modal.php?id='+id+ "&nomina=" + anno);
	}
//----------------
function agregar()
	{
	$('#modal_n').html('<div align="center"><div class="spinner-border" role="status"></div><br><strong>Un momento, por favor...</strong></div>');
	$('#modal_n').load('personal/7b_modal.php');
	}
//----------------
function buscar()
	{
	$('#div2').html('<div align="center"><div class="spinner-border" role="status"></div><br><strong>Un momento, por favor...</strong></div>');
	$('#div2').load('personal/7a_tabla.php');
	}
</script>