<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');

if ($_SESSION['VERIFICADO'] != "SI") { 
header ("Location: ../validacion.php?opcion=val"); 
exit(); }

$acceso=100;
//------- VALIDACION ACCESO USUARIO
include_once "../validacion_usuario.php";
//-----------------------------------
?>
<form id="form1" name="form1" method="post" onsubmit="return evitar();" >
<br>
<div  class="text-right mb-3"><a href="" class="btn btn-outline-primary btn-rounded btn-sm font-weight-bold" onclick="agregar();" data-toggle="modal" data-target="#modal_largo" data-keyboard="false"><i class="fas fa-plus-circle" ></i> Crear Competencia</a></div>

 <br><div id="div2"></div>
</form>
<script language="JavaScript">
//-------------
// PARA EL SELECT2
$(document).ready(function() {
    $('.select2').select2();
	buscar();
});
//----------------
function activar(id, estatus)
 {
	var parametros = "id="+id+"&estatus="+estatus;
	$.ajax({  
		type : 'POST',
		url  : 'personal/13d_guardar.php',
		dataType:"json",
		data:  parametros, 
		success:function(data) {  	
			if (data.tipo=="info")
				{	alertify.success(data.msg);	
//					$('#modal_normal .close').click(); 
//					buscar();
				}
			else
				{	alertify.alert(data.msg);	}
			}  
		});
 }
//----------------
function listar_areas(id) {
    $.ajax({
        type: "POST",
        url: 'personal/13b_combo.php?id=' + id,
        success: function(resp) {
            $('#txt_area').html(resp);
			buscar();
        }
    });
}
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
			url: "personal/13e_eliminar.php",
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
function agregar()
	{
	$('#modal_lg').html('<div align="center"><div class="spinner-border" role="status"></div><br><strong>Un momento, por favor...</strong></div>');
	$('#modal_lg').load('personal/13b_modal.php');
	}
//----------------
function buscar()
	{
	$('#div2').html('<div align="center"><div class="spinner-border" role="status"></div><br><strong>Un momento, por favor...</strong></div>');
	$('#div2').load('personal/13a_tabla.php');
	}
</script>