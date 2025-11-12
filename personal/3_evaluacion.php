<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');

if ($_SESSION['VERIFICADO'] != "SI") { 
header ("Location: ../validacion.php?opcion=val"); 
exit(); }

$acceso=96;
//------- VALIDACION ACCESO USUARIO
include_once "../validacion_usuario.php";
//-----------------------------------
?>
<form id="form1" name="form1" method="post" onsubmit="return evitar();" >
<br>
<div  class="text-right mb-3"><a href="" class="btn btn-outline-primary btn-rounded btn-sm font-weight-bold" onclick="agregar();" data-toggle="modal" data-target="#modal_largo" data-keyboard="false"><i class="fas fa-plus-circle" ></i> Generar Evaluación</a></div>
 <br>
<div id="div2"></div>
</form>
<script language="JavaScript">
//---------------------
function imprimir(ci, id)
	{	
	window.open("personal/reporte/4_evaluacion.php?p=1&id="+id+"&ci="+ci,"_blank");
	}
//-------------
buscar();
//----------------
function eliminarg(id)
	{
	Swal.fire({
		title: 'Estas seguro de eliminar el Registro?',
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
			var parametros = "id=" + id;
			$.ajax({
			url: "personal/3c_eliminar.php",
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
		url  : 'personal/3d_guardar.php',
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
function estadistica(id, estatus)
	{
	$('#modal_lg').html('<div align="center"><div class="spinner-border" role="status"></div><br><strong>Un momento, por favor...</strong></div>');
	$('#modal_lg').load('personal/3g_modal.php?id='+id+'&estatus='+estatus);
	}
//----------------
function cheques(id, estatus)
	{
	$('#modal_n').html('<div align="center"><div class="spinner-border" role="status"></div><br><strong>Un momento, por favor...</strong></div>');
	$('#modal_n').load('personal/3e_modal.php?id='+id+'&estatus='+estatus);
	}
//----------------
function agregar()
	{
	$('#modal_lg').html('<div align="center"><div class="spinner-border" role="status"></div><br><strong>Un momento, por favor...</strong></div>');
	$('#modal_lg').load('personal/3b_modal.php');
	}
//----------------
function buscar()
	{
	$('#div2').html('<div align="center"><div class="spinner-border" role="status"></div><br><strong>Un momento, por favor...</strong></div>');
	$('#div2').load('personal/3a_tabla.php');
	}
</script>