<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');

if ($_SESSION['VERIFICADO'] != "SI") { 
header ("Location: ../validacion.php?opcion=val"); 
exit(); }

$acceso=104;
//------- VALIDACION ACCESO USUARIO
include_once "../validacion_usuario.php";
//-----------------------------------
//echo $consultx;
//$consultx = "SELECT * FROM rac WHERE cedula = '16179059';";  //echo $consultx;
//$tablx = $_SESSION['conexionsql']->query($consultx);
//$registro = $tablx->fetch_object();
?>
<form id="form1" name="form1" method="post" onsubmit="return evitar();" >
<table class="formateada" border="0" align="center" width="100%">
<tr>
<td class="TituloTablaP" height="41" colspan="10" align="center">Generar Aguinaldos</td>
</tr>
<tr >
  <td > 
  <br>
<div class="form-group col-sm-8">
	<div class="input-group">
		<div class="input-group-text"><i class="fas fa-book"> Cant. Meses Aguinaldo</i></div>
		<input type="text" name="txt_dias" id="txt_dias" size="6" style="text-align: center" />
    </div>
</div>
	
	<div class="form-group col-sm-8">
		<div class="input-group">
			 <button type="button" id="boton" class="btn btn-outline-success waves-effect" onclick="generar_fideicomiso();" ><i class="fas fa-cloud-upload-alt prefix grey-text mr-1"></i> Generar Aguinaldos</button>
		</div>
	</div>

  <br>
 </td>
</tr>
</table>
<div id="div1"></div>
</form>
<script language="JavaScript">
tabla(); $('#cesta').hide(); $('#vaca').hide();
function eliminar_nomina(num, tipo, boton)
	{
	$(boton).hide();
	$('#div1').html('<div align="center"><img width="125" height="125" src="images/espera(1).gif"/><br/>Espere mientras la lista es Recargada...</div>');
	var parametros = "num=" + num + "&tipo=" +tipo;
	$.ajax({  
		type : 'POST',
		url  : 'personal/1c_eliminar.php',
		dataType:"json",
		data:  parametros, 
		success:function(data) {  	
			if (data.tipo=="info")
				{	alertify.success(data.msg);	tabla(); $(boton).show();}
			else
				{	alertify.alert(data.msg);	}
			//--------------
			} 
		 
		});
	}
//---------------------
function imprimir_sol(id, tipo, estatus)
	{	
	if (tipo=="013")
		{	window.open("personal/formatos/11_aguinaldos.php?id="+id+"&tipo="+tipo+"&estatus="+estatus,"_blank");	}
	}
//---------------------
function validar()
	{
	error = 0;
	if(document.form1.txt_dias.value=="" || document.form1.txt_dias.value==0)	
		{	 document.form1.txt_dias.focus(); 	alertify.alert("Debe indicar la Cantidad de Dias a pagar! (30 dias = 1 mes)");	error = 1;  }
	return error;
	}
//--------------------- PARA BUSCAR
function tabla(){
	$('#div1').html('<div align="center"><img width="125" height="125" src="images/espera(1).gif"/><br/>Un momento, por favor...</div>');
	$('#div1').load('personal/20b_tabla.php');
}
//------------------
function generar_fideicomiso()
{
if (validar()==0)
{
	$('#boton').hide();
	alertify.alert('Espere mientras el Pago es Generado...');
	$('#div1').html('<div align="center"><img width="125" height="125" src="images/espera(1).gif"/><br/>Espere mientras el Pago es Generado...</div>');
	var parametros = $("#form1").serialize(); 
	$.ajax({  
		type : 'POST',
		url  : 'personal/20a_guardar.php',
		dataType:"json",
		data:  parametros, 
		success:function(data) {  	
			if (data.tipo=="info")
				{	alertify.success(data.msg);	tabla(); $('#boton').show();}
			else
				{	alertify.alert(data.msg);	}
			//--------------
			} 
		});
}
}
//--------------------------------
setTimeout(function()	{
		$('#txt_dias').focus();
		},500)	
</script>