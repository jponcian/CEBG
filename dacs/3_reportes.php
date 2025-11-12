<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');

if ($_SESSION['VERIFICADO'] != "SI") { 
header ("Location: ../validacion.php?opcion=val"); 
exit(); }

$acceso=42;
//------- VALIDACION ACCESO USUARIO
include_once "../validacion_usuario.php";
//-----------------------------------
?>
<form id="form1" name="form1" method="post" >
<div align="center" class="TituloP">Reportes</div>
<br >
<table border="1"><tr align="center">
<td align="left" valign="top"><div style="width:300px" class="form-group">	  
<div class="col-xm-6">
<a data-toggle="tooltip" title="Visitas">
<select class="form-control" name="txt_tipo" id="txt_tipo" onchange="" >
<option value="1">Cuadro N° 4</option>
<!--<option value="0">Todos</option>-->
</select>
</a>
</div></div></td>
<td align="left" valign="top"><div class="input-group-prepend">
<input readonly class="form-control" type="text" name="OFECHA" id="OFECHA" size="15" placeholder="Desde" value="<?php echo date('d/m/Y'); ?>" style="text-align:center" /> 
<input readonly class="form-control" type="text" name="OFECHA2" id="OFECHA2" size="15" placeholder="Hasta" value="<?php echo date('d/m/Y'); ?>" style="text-align:center" />
</div></td>
<td align="left" valign="top"><button type="button" id="botonb" class="btn btn-warning" onClick="reportes2();"><i class="fas fa-search mr-2"></i> Ver Reporte</button></td>
</tr></table>
</form>
<script language="JavaScript">
$('#OFECHA').datepicker();
$('#OFECHA2').datepicker();
//---------------------------
function reportes2()
 	{
	 if (document.form1.OFECHA.value!='' && document.form1.OFECHA2.value!='')
	 	{
		if (document.form1.txt_tipo.value==1)
			{
			window.open("dacs/reporte/cuadro4.php?fecha1="+document.form1.OFECHA.value+"&fecha2="+document.form1.OFECHA2.value,"_blank");
			}
		}
	}
</script>