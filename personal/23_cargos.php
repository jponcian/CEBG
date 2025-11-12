<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');

if ($_SESSION['VERIFICADO'] != "SI") { 
header ("Location: ../validacion.php?opcion=val"); 
exit(); }

$acceso=88;
//------- VALIDACION ACCESO USUARIO
include_once "../validacion_usuario.php";
//-----------------------------------
?>
<form id="form1" name="form1" method="post" onsubmit="return evitar();" >
<br>
<div  class="text-right mb-3"><a href="" class="btn btn-outline-primary btn-rounded btn-sm font-weight-bold" onclick="agregar();" data-toggle="modal" data-target="#modal_largo" data-keyboard="false"><i class="fas fa-plus-circle" ></i> Agregar Cargo</a></div>
 <br>
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
function eliminar2(partida, categoria)
	{
	alertify.confirm("Estas seguro de eliminar la Partida Registrada?",  
	function()
			{ 
			var parametros = "partida=" + partida + "&categoria=" + categoria;
			$.ajax({
			url: "personal/23h_eliminar.php",
			type: "POST",
			dataType:"json",
			data: parametros,
			success: function(data) {
			if (data.tipo=="info")
				{	alertify.success(data.msg);	
					listar_partidas(categoria);
				}
			else
				{	alertify.alert(data.msg);	}
			}
			});
		});
}
//----------------
function eliminar(id)
	{
	alertify.confirm("Estas seguro de eliminar el Cargo registrado?",  
	function()
			{ 
			var parametros = "id=" + id;
			$.ajax({
			url: "personal/23c_eliminar.php",
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
function guardar3(e,id)
{
	// Obtenemos la tecla pulsada
	(e.keyCode)?k=e.keyCode:k=e.which;
	// Si la tecla pulsada es enter (codigo ascii 13)
	if(k==13)
		{
		guardar2(id);
		}
}
//----------------
function guardar2(id)
 {
	var parametros = $("#form999").serialize(); 
	$.ajax({  
		type : 'POST',
		url  : 'personal/23g_guardar.php?id='+id,
		dataType:"json",
		data:  parametros, 
		success:function(data) {  	
			if (data.tipo=="info")
				{	alertify.success(data.msg);	
					document.form999.txt_cheque.value='';
				 	document.form999.txt_descripcion.value='';
				 	document.form999.txt_original.value='0';
					document.form999.txt_cheque.focus();
					listar_partidas(id);
				}
			else
				{	alertify.alert(data.msg);	
					document.form999.txt_cheque.value='';
				 	document.form999.txt_descripcion.value='';
				 	document.form999.txt_original.value='0';
					document.form999.txt_cheque.focus();
					listar_partidas(id);}
			}  
		});
 }
//----------------
function guardar()
 {
	var parametros = $("#form999").serialize(); 
	$.ajax({  
		type : 'POST',
		url  : 'personal/23d_guardar.php',
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
function listar_partidas(id)
	{
	$('#div3').html('<div align="center"><img width="125" height="125" src="images/espera(1).gif"/><br/>Un momento, por favor...</div>');
	$('#div3').load('personal/23f_tabla.php?id='+id);
	}
//----------------
function cheques(id)
	{
	$('#modal_lg').html('<div align="center"><img width="125" height="125" src="images/espera(1).gif"/><br/>Un momento, por favor...</div>');
	$('#modal_lg').load('personal/23e_modal.php?id='+id);
	}
//----------------
function agregar()
	{
	$('#modal_lg').html('<div align="center"><img width="125" height="125" src="images/espera(1).gif"/><br/>Un momento, por favor...</div>');
	$('#modal_lg').load('personal/23b_modal.php');
	}
//----------------
function buscar()
	{
	$('#div2').html('<div align="center"><img width="125" height="125" src="images/espera(1).gif"/><br/>Un momento, por favor...</div>');
	$('#div2').load('personal/23a_tabla.php');
	}
</script>