<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');

if ($_SESSION['VERIFICADO'] != "SI") { 
header ("Location: ../validacion.php?opcion=val"); 
exit(); }

$acceso=12;
//------- VALIDACION ACCESO USUARIO
include_once "../validacion_usuario.php";
//-----------------------------------
//echo $consultx;
?>
<form id="form1" name="form1" method="post" onsubmit="return evitar();" >
<table class="formateada" border="0" align="center" width="100%">
<tr>
<td class="TituloTablaP" height="41" colspan="10" align="center">Realizar Ajustes en Nominas Generadas</td>
</tr>
<tr >
  <td > 
  <br>
  <div class="form-group col-sm-8">
	<div class="input-group">
		<div class="input-group-text"><i class="fas fa-book"> Nomina</i></div>
		<select id="ONOMINA" name="ONOMINA" onchange="combo(1);">
    <option value="0">Seleccione</option>
    <?php
	$consultx = "SELECT nomina FROM nomina WHERE estatus=0 AND tipo_pago<>'008' GROUP BY nomina ORDER BY nomina;"; 
	$tablx = $_SESSION['conexionsql']->query($consultx);
	while ($registro_x = $tablx->fetch_array())
		{
		echo '<option value="'.$registro_x['nomina'].'">'.$registro_x['nomina'].'</option>';
		}
	?>
  </select>
	</div>
</div>

<div class="form-group col-sm-8">
	<div class="input-group">
		<div class="input-group-text"><i class="fas fa-book"> Tipo</i></div>
		<select id="ODESCRIPCION" name="ODESCRIPCION" onchange="combo(2);">
    <option value="0">Seleccione</option>
  </select>
	</div>
</div>

<div class="form-group col-sm-12">
	<div class="input-group">
		<div class="input-group-text"><i class="fas fa-book"> Quincena</i></div>
		<select id="OPERIODO" name="OPERIODO">
    <option value="0">Seleccione</option>
  </select>
	</div>
</div>
		
<div class="form-group col-sm-8">
	<div class="input-group">
		 <button type="button" id="boton" class="btn btn-outline-danger waves-effect" onclick="buscar();" ><i class="fas fa-search mr-2"></i> Buscar</button>
	</div>
</div>

  <br>
 </td>
</tr>
</table>
<div id="div1"></div>
</form>
<script language="JavaScript">
//---------------------------
function guardar2(e,id, tipo)
 	 {
	 (e.keyCode)?k=e.keyCode:k=e.which;
	// Si la tecla pulsada es enter (codigo ascii 13)
	if(k==13)
		{guardar(id, tipo);}
	}
//--------------------------------
function guardar(id, tipo)
 {
	var parametros = $("#form999").serialize(); 
	$.ajax({  
		type : 'POST',
		url  : 'personal/14d_guardar.php?id='+id+'&tipo='+tipo,
		dataType:"json",
		data:  parametros, 
		success:function(data) {  	
			if (data.tipo=="info")
				{	alertify.success(data.msg);	
					$('#modal_normal .close').click(); 
					buscar();
				}
			else
				{	alertify.alert(data.msg);	}
			}  
		});
 }
//--------------------- 
function editar(id, tipo, sueldo, prof, hijos, antiguedad, dias, tickets, bono, diferencia){
	$('#modal_n').html('<div align="center"><div class="spinner-border" role="status"></div><br><strong>Un momento, por favor...</strong></div>');
	$('#modal_n').load('personal/14c_modal.php?id='+id+'&tipo='+tipo+'&sueldo='+sueldo+'&prof='+prof+'&hijos='+hijos+'&antiguedad='+antiguedad+'&dias='+dias+'&tickets='+tickets+'&bono='+bono+'&diferencia='+diferencia);
}
//--------------------- PARA BUSCAR
function buscar(){
	$('#div1').html('<div align="center"><div class="spinner-border" role="status"></div><br><strong>Un momento, por favor...</strong></div>');
	$('#div1').load('personal/14b_tabla.php?periodo='+(document.form1.OPERIODO.value)+'&nomina='+cambia(document.form1.ONOMINA.value)+'&tipo='+cambia(document.form1.ODESCRIPCION.value));
}
//-------------
function combo(combo)
{
	var parametros = $("#form1").serialize(); 
	$.ajax({
		type : 'POST',
		url: 'personal/14a_combo.php?combo='+combo,
		data:  parametros, 
		success:function(resp) {  	
			if (combo==1) {$('#ODESCRIPCION').html(resp);}
				else {$('#OPERIODO').html(resp);}
			} 
		 
		});
}
</script>