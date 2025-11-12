<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');

if ($_SESSION['VERIFICADO'] != "SI") { 
header ("Location: ../validacion.php?opcion=val"); 
exit(); }

$acceso=10;
//------- VALIDACION ACCESO USUARIO
include_once "../validacion_usuario.php";
//-----------------------------------
?>
<form id="form1" name="form1" method="post" >
<div align="center" class="TituloP">Visitas Externas</div>
<br >
<table border="1"><tr align="center">
<td align="left" valign="top"><div class="form-group">	  
<div class="col-xm-6">
<a data-toggle="tooltip" title="Visitas">
<select class="form-control" name="txt_tipo" id="txt_tipo" onchange="combo(this.value)" >
<option value="1">Visita Diaria (Resumen)</option>
<option value="2">Visita Diaria (Detalle)</option>
<option value="3">Visita Diaria (Observación)</option>
<!--<option value="0">Todos</option>-->
</select>
</a>
</div></div></td>
<td align="left" valign="top"><div class="input-group-prepend">
<input readonly class="form-control" type="text" name="OFECHA" id="OFECHA" size="15" placeholder="Desde" value="<?php echo date('d/m/Y'); ?>" style="text-align:center" /> 
<input readonly class="form-control" type="text" name="OFECHA2" id="OFECHA2" size="15" placeholder="Hasta" value="<?php echo date('d/m/Y'); ?>" style="text-align:center" />
</div></td>
<td align="left" valign="top"><button type="button" id="botonb" class="btn btn-warning" onClick="reportes2();"><i class="fas fa-search mr-2"></i> Ver Reporte</button></td>
</tr>
<tr align="center">
	<td align="right" valign="top"><div id="txt_cia" class="form-group">	  
	<div class="col-xm-6">
	<a data-toggle="tooltip" title="Filtrar por Cédula">
	<div class="alert alert-primary" role="alert">
  <strong>Filtrar por Cedula:</strong>
</div>
	</a>
	</div></div></td>
	<td align="left" valign="top" colspan="2"><div id="txt_cib" class="form-group">	  
<a data-toggle="tooltip" title="Visitas">
<select class="select2" style="width: 600px" placeholder="Seleccione el Número de Cédula" name="txt_ci" id="txt_ci" >
</select>
<!--	<input id="txt_historial" name="txt_historial" type="checkbox" value="1" />-->
	
<!--
<div class="form-check form-switch">
  <input type="checkbox" id="txt_historial" value="1">
  <label class="form-check-label" for="txt_historial">Buscar en todo el historial</label>
</div>
-->
<!--
<input type="checkbox" class="btn-check" id="btn-check-2-outlined" checked autocomplete="off">
<label class="btn btn-outline-primary" for="btn-check-2-outlined">Buscar en todo el historial</label><br>
-->
</a>
</div></td>
</tr>
</table>
</form>
<script language="JavaScript">
//---------------------------
$(document).ready(function() {
    $('.select2').select2();
	//----------------
	$('#OFECHA').datepicker();
	$('#OFECHA2').datepicker();
	$('#txt_cia').hide();
	$('#txt_cib').hide();
});
//--------------------------------
function combo(tipo) {
	if (tipo==2)
		{
			$('#txt_cia').show();
			$('#txt_cib').show();
			$.ajax({
				type: "POST",
				url: 'seguridad/3k_combo.php',
				success: function(resp) {
					$('#txt_ci').html(resp);
				}
			});
		}
	else
		{
			$('#txt_cia').hide();
			$('#txt_cib').hide();
		}
}
//---------------------------
function reportes2()
 	{
	 if (document.form1.txt_tipo.value==1 && document.form1.OFECHA.value!='' && document.form1.OFECHA2.value!='')
	 	{
		window.open("seguridad/reporte/visitas.php?tipo="+document.form1.txt_tipo.value+"&fecha1="+document.form1.OFECHA.value+"&fecha2="+document.form1.OFECHA2.value,"_blank");
		}
	 if (document.form1.txt_tipo.value==2 && document.form1.OFECHA.value!='' && document.form1.OFECHA2.value!='')
	 	{
		window.open("seguridad/reporte/visitas_detalle.php?tipo="+document.form1.txt_tipo.value+"&fecha1="+document.form1.OFECHA.value+"&fecha2="+document.form1.OFECHA2.value+"&id="+document.form1.txt_ci.value,"_blank");//+"&h="+document.form1.txt_historial.value
		}
	 if (document.form1.txt_tipo.value==3 && document.form1.OFECHA.value!='' && document.form1.OFECHA2.value!='')
	 	{
		window.open("seguridad/reporte/visitas_detalle2.php?tipo="+document.form1.txt_tipo.value+"&fecha1="+document.form1.OFECHA.value+"&fecha2="+document.form1.OFECHA2.value,"_blank");
		}
	}
</script>