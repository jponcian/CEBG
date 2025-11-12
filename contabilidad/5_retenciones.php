<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');

if ($_SESSION['VERIFICADO'] != "SI") { 
header ("Location: ../validacion.php?opcion=val"); 
exit(); }

$acceso=75;
//------- VALIDACION ACCESO USUARIO
include_once "../validacion_usuario.php";
//-----------------------------------
?>
<form id="form1" name="form1" method="post" >
<div align="center" class="TituloP">Generar archivos de Retenciones Realizadas</div>
<br >
<table border="1"><tr align="center">
<td align="left" valign="top"><div style="width:300px" class="form-group">	  
<div class="col-xm-6">
<a data-toggle="tooltip" title="Tipo de Retencion">
<select class="form-control" name="txt_tipo" id="txt_tipo" onchange="" >
<option value="0">Seleccione</option>
  <?php
	$consultx = "SELECT * FROM a_retenciones WHERE rif = 'G200003030'"; 
	$tablx = $_SESSION['conexionsql']->query($consultx);
	while ($registro_x = $tablx->fetch_array())
		{
		echo '<option value='.$registro_x['id'].'>'.$registro_x['decripcion'].'</option>';
		}
?></select>
</a>
</div></div></td>
<td align="left" valign="top"><div class="input-group-prepend">
<input class="form-control" type="text" name="OFECHA" id="OFECHA" size="15" placeholder="Desde" value="<?php echo '01'.date('/m/Y'); ?>" style="text-align:center" /> 
<input class="form-control" type="text" name="OFECHA2" id="OFECHA2" size="15" placeholder="Hasta" value="<?php echo date('d/m/Y'); ?>" style="text-align:center" />
</div></td>
<td align="left" valign="top"><button type="button" id="botonb" class="btn btn-warning" onClick="generar_archivo();"><i class="fas fa-search mr-2"></i>Generar Archivo</button>
</td>
</tr></table>
</form>
<script language="JavaScript">
$('#OFECHA').datepicker();
$('#OFECHA2').datepicker();
//---------------------------
function generar_archivo()
{
	if (document.form1.txt_tipo!='0')
		{
		window.open("contabilidad/5b_generar.php?tipo="+document.form1.txt_tipo.value+"&fecha1="+document.form1.OFECHA.value+"&fecha2="+document.form1.OFECHA2.value,"_blank");
		}
}
</script>