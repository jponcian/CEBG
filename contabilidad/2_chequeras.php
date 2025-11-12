<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');

if ($_SESSION['VERIFICADO'] != "SI") { 
header ("Location: ../validacion.php?opcion=val"); 
exit(); }

$acceso=77;
//------- VALIDACION ACCESO USUARIO
include_once "../validacion_usuario.php";
//-----------------------------------
$_SESSION['estatus'] = array("Activa","Anulada");
$_SESSION['activar'] = array("1","0");
$_SESSION['boton'] = array("Anular","Activar");
?>
<form id="form1" name="form1" method="post" onsubmit="return evitar();" >
<br>
<div  class="text-right mb-3"><a href="" class="btn btn-outline-primary btn-rounded btn-sm font-weight-bold" onclick="agregar();" data-toggle="modal" data-target="#modal_normal" data-keyboard="false"><i class="fas fa-plus-circle" ></i> Agregar Chequera</a></div>
 <br>
<div id="div2"></div>
</form>
<script language="JavaScript">
buscar();
//----------------
function eliminar2(id, id_chequera)
	{
	alertify.confirm("Estas seguro de eliminar el Cheque Registrado?",  
	function()
			{ 
			var parametros = "id=" + id;
			$.ajax({
			url: "contabilidad/2h_eliminar.php",
			type: "POST",
			data: parametros,
			success: function(r) {
			alertify.success('Registro Eliminado Correctamente');
			//--------------
			listar_cheques(id_chequera);
			}
			});
		});
}
//----------------
function eliminar(id)
	{
	alertify.confirm("Estas seguro de eliminar La Chequera Registrada?",  
	function()
			{ 
			var parametros = "id=" + id;
			$.ajax({
			url: "contabilidad/2c_eliminar.php",
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
function guardar3(e,id)
{
	// Obtenemos la tecla pulsada
	(e.keyCode)?k=e.keyCode:k=e.which;
	// Si la tecla pulsada es enter (codigo ascii 13)
	if(k==13)
		{
		var parametros = $("#form999").serialize(); 
		$.ajax({  
		type : 'POST',
		url  : 'contabilidad/2g_guardar.php?id='+id,
		dataType:"json",
		data:  parametros, 
		success:function(data) {  	
			if (data.tipo=="info")
				{	alertify.success(data.msg);	
					document.form999.txt_cheque.value='';
					document.form999.txt_cheque.focus();
					listar_cheques(id);
				}
			else
				{	alertify.alert(data.msg);	}
			}  
		});
		}
}
//----------------
function guardar2(id)
 {
	var parametros = $("#form999").serialize(); 
	$.ajax({  
		type : 'POST',
		url  : 'contabilidad/2g_guardar.php?id='+id,
		dataType:"json",
		data:  parametros, 
		success:function(data) {  	
			if (data.tipo=="info")
				{	alertify.success(data.msg);	
					document.form999.txt_cheque.value='';
					document.form999.txt_cheque.focus();
					listar_cheques(id);
				}
			else
				{	alertify.alert(data.msg);	}
			}  
		});
 }
//----------------
function guardar(tipo)
 {
	var parametros = $("#form999").serialize(); 
	$.ajax({  
		type : 'POST',
		url  : 'contabilidad/2d_guardar.php',
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
function listar_cheques(id)
	{
	$('#div3').html('<div align="center"><div class="spinner-border" role="status"></div><br><strong>Un momento, por favor...</strong></div>');
	$('#div3').load('contabilidad/2f_tabla.php?id='+id);
	}
//----------------
function cheques(id)
	{
	$('#modal_n').html('<div align="center"><div class="spinner-border" role="status"></div><br><strong>Un momento, por favor...</strong></div>');
	$('#modal_n').load('contabilidad/2e_modal.php?id='+id);
	}
//----------------
function agregar()
	{
	$('#modal_n').html('<div align="center"><div class="spinner-border" role="status"></div><br><strong>Un momento, por favor...</strong></div>');
	$('#modal_n').load('contabilidad/2b_modal.php');
	}
//----------------
function buscar()
	{
	$('#div2').html('<div align="center"><div class="spinner-border" role="status"></div><br><strong>Un momento, por favor...</strong></div>');
	$('#div2').load('contabilidad/2a_tabla.php');
	}
</script>