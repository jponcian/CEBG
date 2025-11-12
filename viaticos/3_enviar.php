<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');

if ($_SESSION['VERIFICADO'] != "SI") { 
header ("Location: ../validacion.php?opcion=val"); 
exit(); }

$acceso=83;
//------- VALIDACION ACCESO USUARIO
include_once "../validacion_usuario.php";
//-----------------------------------
?>
<form id="form1" name="form1" method="post" onsubmit="return evitar();" >
<div id="div1"></div>
</form>
<script language="JavaScript">
//---------------------
function imprimir(id)
	{	
	window.open("viaticos/formatos/1_memo.php?id="+id,"_blank");	
	}
//------------------
function generar_pago()
{
	$('#boton').hide();
	//alertify.alert('Espere mientras la Orden de Pago es Generada...');
	var parametros = $("#form1").serialize(); 
	$.ajax({  
		type : 'POST',
		url  : 'viaticos/3b_guardar.php',
		dataType:"json",
		data:  parametros, 
		success:function(data) {  	
			if (data.tipo=="info")
				{	alertify.success(data.msg);	tabla(); $('#boton').show(); imprimir(data.id, data.orden);	}
			else
				{	alertify.alert(data.msg);	$('#boton').show(); }
			//--------------
			} 
		 
		});
}
//------------------
tabla();
//--------------------- PARA BUSCAR
function tabla(){
	$('#div1').html('<div align="center"><div class="spinner-border" role="status"></div><br><strong>Un momento, por favor...</strong></div>');
	$('#div1').load('viaticos/3a_tabla.php');
}
</script>