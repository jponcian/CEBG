<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');

if ($_SESSION['VERIFICADO'] != "SI") { 
header ("Location: ../validacion.php?opcion=val"); 
exit(); }

$acceso=40;
//------- VALIDACION ACCESO USUARIO
include_once "../validacion_usuario.php";
//-----------------------------------
?>
<form id="form1" name="form1" method="post" onsubmit="return evitar();" >
 <div align="center" class="TituloP">Anulaci&oacute;n Orden de Pago</div>
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
function anular(id_pago, id_solicitud)
	{
	alertify.confirm("Estas seguro de continuar con la Anulacion?", function()
		{
		var parametros = "id_pago=" + id_pago + "&id_solicitud=" +id_solicitud;
		$.ajax({  
		type : 'POST',
		url  : 'administracion/7b_guardar.php',
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
	if (tipo=="ORDEN")
		{	window.open("administracion/formatos/1a_orden_pago.php?id="+id,"_blank");	}
	}
//----------------
function buscar(){
if((document.form1.obuscar.value=="  " || document.form1.obuscar.value==" " || document.form1.obuscar.value=="") && document.form1.optradio.value!=4){}
else	{
	$('#div1').html('<div align="center"><div class="spinner-border" role="status"></div><br><strong>Un momento, por favor...</strong></div>');
	$('#div1').load('administracion/7a_tabla.php?valor='+cambia(document.form1.obuscar.value)+'&tipo='+document.form1.optradio.value);
	}
}
</script>