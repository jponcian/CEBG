<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');

if ($_SESSION['VERIFICADO'] != "SI") { 
header ("Location: ../validacion.php?opcion=val"); 
exit(); }

$acceso=71;
//------- VALIDACION ACCESO USUARIO
include_once "../validacion_usuario.php";
//-----------------------------------
?>
<form id="form1" name="form1" method="post" onsubmit="return evitar();" >
<div id="div1"></div>
</form>
<script language="JavaScript">
//-----------------------
function asignar_numero()
	{
	$('#modal_n').html('<div align="center"><div class="spinner-border" role="status"></div><br><strong>Un momento, por favor...</strong></div>');
	$('#modal_n').load('administracion/23b_modal.php');
	}
//---------------------
function imprimir(id, tipo)
	{	
	if (tipo=="FINANCIERA")
		{	window.open("administracion/formatos/1b_orden_pago.php?id="+id,"_blank");	}
	if (tipo=="ORDEN" || tipo=="MANUAL")
		{	window.open("administracion/formatos/1a_orden_pago.php?id="+id,"_blank");	}
	if (tipo=="NOMINA")
		{	window.open("administracion/formatos/1_orden_pago.php?id="+id,"_blank");	}
	}
//------------------
function generar_pago()
{
	$('#boton').hide();
	//alertify.alert('Espere mientras la Orden de Pago es Generada...');
	var parametros = $("#form1").serialize(); 
	$.ajax({  
		type : 'POST',
		url  : 'administracion/23b_guardar.php?id='+document.form999.txt_nuevo.value,
		dataType:"json",
		data:  parametros, 
		success:function(data) {  	
			if (data.tipo=="info")
				{	$('#modal_normal .close').click();	alertify.success(data.msg);	tabla(); $('#boton').show(); imprimir(data.id, data.orden);	}
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
	$('#div1').load('administracion/23a_tabla.php');
}
</script>