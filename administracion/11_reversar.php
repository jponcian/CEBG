<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');

if ($_SESSION['VERIFICADO'] != "SI") { 
header ("Location: ../validacion.php?opcion=val"); 
exit(); }

$acceso=39;
//------- VALIDACION ACCESO USUARIO
include_once "../validacion_usuario.php";
//-----------------------------------
?>
<form id="form1" name="form1" method="post" onsubmit="return evitar();" >
 <div align="center" class="TituloP">Reversar Orden de Pago</div>
		<br >
		<diw class="row ml-3">
            <strong>Opciones de Busqueda:</strong>
            <div class="form-check ml-3">
              <label class="form-check-label">
                    <input type="radio" class="form-check-input" name="optradio" value="1" >
                N&uacute;mero</label>
            </div>
           
            <div class="form-check ml-3">
                <label class="form-check-label">
                    <input type="radio" class="form-check-input" name="optradio" value="2" checked="checked" >
                    Descripcion
                </label>
            </div>
			<div class="form-check ml-3">
                <label class="form-check-label">
                    <input type="radio" class="form-check-input" name="optradio" value="4" onclick="buscar()" >
                   Ver Todos
                </label>
            </div>
        </diw>
 <input name="obuscar" id="obuscar" type="text" size="100" class="form-control" onchange="buscar()" />

 <br>
 
 <div id="div1"></div>
 </form>
<script language="JavaScript">
//----------------------
function anular3(id_pago, id_solicitud)
	{
	alertify.confirm("Estas seguro de reversar la Orden de Pago?", function()
		{
		var parametros = "id_pago=" + id_pago + "&id_solicitud=" +id_solicitud;
		$.ajax({  
		type : 'POST',
		url  : 'administracion/11d_guardar.php',
		dataType:"json",
		data:  parametros, 
		success:function(data) {  	
			if (data.tipo=="info")
				{	alertify.success(data.msg);	buscar();}
			else
				{	alertify.alert(data.msg);	}
			//--------------
			}  
		});
		});
	}
//----------------------
function anular2(id_pago, id_solicitud)
	{
	alertify.confirm("Estas seguro de reversar la Orden de Pago?", function()
		{
		var parametros = "id_pago=" + id_pago + "&id_solicitud=" +id_solicitud;
		$.ajax({  
		type : 'POST',
		url  : 'administracion/11c_guardar.php',
		dataType:"json",
		data:  parametros, 
		success:function(data) {  	
			if (data.tipo=="info")
				{	alertify.success(data.msg);	buscar();}
			else
				{	alertify.alert(data.msg);	}
			//--------------
			}  
		});
		});
	}
//----------------------
function anular(id_pago, id_solicitud)
	{
	alertify.confirm("Estas seguro de reversar la Orden de Pago?", function()
		{
		var parametros = "id_pago=" + id_pago + "&id_solicitud=" +id_solicitud;
		$.ajax({  
		type : 'POST',
		url  : 'administracion/11b_guardar.php',
		dataType:"json",
		data:  parametros, 
		success:function(data) {  	
			if (data.tipo=="info")
				{	alertify.success(data.msg);	buscar();}
			else
				{	alertify.alert(data.msg);	}
			//--------------
			}  
		});
		});
	}
//---------------------
function imprimir(id, tipo)
	{	
	//alertify.alert(tipo);
	if (tipo=="FINANCIERA")
		{	window.open("administracion/formatos/1b_orden_pago.php?id="+id,"_blank");	}
	if (tipo=="ORDEN" || tipo=="MANUAL")
		{	window.open("administracion/formatos/1a_orden_pago.php?id="+id,"_blank");	}
	if (tipo=="NOMINA")
		{	window.open("administracion/formatos/1_orden_pago.php?id="+id,"_blank");	}
	}
//----------------
function buscar(){
if((document.form1.obuscar.value=="  " || document.form1.obuscar.value==" " || document.form1.obuscar.value=="") && document.form1.optradio.value!=4){}
else	{
	$('#div1').html('<div align="center"><div class="spinner-border" role="status"></div><br><strong>Un momento, por favor...</strong></div>');
	$('#div1').load('administracion/11a_tabla.php?valor='+cambia(document.form1.obuscar.value)+'&tipo='+document.form1.optradio.value);
	}
}
</script>