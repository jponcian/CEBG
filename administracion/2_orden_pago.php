<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');

if ($_SESSION['VERIFICADO'] != "SI") { 
header ("Location: ../validacion.php?opcion=val"); 
exit(); }

$acceso=59;
//------- VALIDACION ACCESO USUARIO
include_once "../validacion_usuario.php";
//-----------------------------------
?>
<form id="form1" name="form1" method="post" onsubmit="return evitar();" >
        <diw class="row ml-3">
            <strong>Opciones para Filtrar:</strong>
            <div class="form-check ml-3">
                <label class="form-check-label">
                    <input type="radio" class="form-check-input" name="optradio" value="9" checked="checked" onclick="buscar()">
                    Pendientes
                </label>
            </div>
			<div class="form-check ml-3">
                <label class="form-check-label">
                    <input type="radio" class="form-check-input" name="optradio" value="10" onclick="buscar()" >
                   Culminadas
                </label>
            </div>
        </diw>

 <br>
 
 <div id="div1"></div></form>
<script language="JavaScript">
//-----------------
function enviar_datos(id){
	$('#modal_lg').html('<div align="center"><div class="spinner-border" role="status"></div><br><strong>Un momento, por favor...</strong></div>');
	$('#modal_lg').load('administracion/2c_modal.php?id='+id);
}
//---------------------
function imprimir_ord(id, tipo)
	{	
//	window.open("administracion/formatos/1_orden_pago.php?id="+id,"_blank");
	if (tipo=="NOMINA")
		{	window.open("administracion/formatos/1_orden_pago.php?id="+id,"_blank");	}
	if (tipo=="NOMINA MANUAL")
		{	window.open("administracion/formatos/1a_orden_pago.php?id="+id,"_blank");	}
//	if (tipo=="002")
//		{	window.open("personal/formatos/4_tickets.php?id="+id+"&estatus=1","_blank");	}
//	if (tipo=="003")
//		{	window.open("personal/formatos/3_vacaciones.php?id="+id+"&estatus=1","_blank");	}
	}
//------------------
function guardar(id)
{
	$('#boton').hide();
	//alertify.alert('Espere mientras la Orden de Pago es Procesada...');
	var parametros = $("#form999").serialize(); 
	$.ajax({  
		type : 'POST',
		url  : 'administracion/2d_guardar.php',
		dataType:"json",
		data:  parametros, 
		success:function(data) {  	
			if (data.tipo=="info")
				{	alertify.success(data.msg);	$('#modal_lg .close').click(); buscar();}
			else
				{	alertify.alert(data.msg);	$('#boton').show(); }
			//--------------
			} 
		 
		});
}
//------------------
buscar();
//----------------
function buscar(){
	$('#div1').html('<div align="center"><div class="spinner-border" role="status"></div><br><strong>Un momento, por favor...</strong></div>');
	$('#div1').load('administracion/2a_tabla.php?estatus='+document.form1.optradio.value);
}
</script>