<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');
//-----------
//if ($_GET['periodo']<>'')	{$periodo = " WHERE desde='".voltea_fecha($_GET['periodo'])."'";} else {$periodo = "";}
$periodo = "";
?>
<table class="table table-hover" width="100%" border="0" align="center">
<tr>
<td class="TituloTablaP" height="41" colspan="10" align="center">Nominas con Solicitud de Pago</td>
</tr>
<tr>
<td  bgcolor="#CCCCCC" align="center"><strong>N:</strong></td>
<td width="250" bgcolor="#CCCCCC" align="left"><strong>Nomina:</strong></td>
<td  bgcolor="#CCCCCC" align="left"><strong>Descripci&oacute;n:</strong></td>
<td  bgcolor="#CCCCCC" align="center" ><strong>Periodo:</strong></td>
<td  bgcolor="#CCCCCC" align="right"><strong>Asignaciones:</strong></td>
<td bgcolor="#CCCCCC" align="right"><strong>Deducciones:</strong></td>
<td bgcolor="#CCCCCC" align="right"><strong>Total:</strong></td>
<td bgcolor="#CCCCCC" align="right"></td>
<td bgcolor="#CCCCCC" align="right"></td>
</tr>
<?php 	
$i=0;
//------ MONTAJE DE LOS DATOS
$consultx = "SELECT * FROM nomina_solicitudes WHERE estatus=5 ORDER BY tipo_pago, nomina, desde;"; 
//echo $consultx;
$tablx = $_SESSION['conexionsql']->query($consultx);

while ($registro = $tablx->fetch_object())
	{
	$i++;
	?>
<tr >
<td><div align="center" ><?php echo ($i); ?></div></td>
<td ><div align="left" ><?php echo ($registro->nomina); ?></div></td>
<td ><strong><div align="left" ><?php echo ($registro->descripcion).$_SESSION['tipo_nomina'][$registro->patria]; ?></div></strong></td>
<td ><div align="center" ><?php echo voltea_fecha($registro->desde). ' al ' .voltea_fecha($registro->hasta); ?></div></td>
<td ><div align="right" ><?php echo formato_moneda($registro->asignaciones); ?></div></td>
<td ><div align="right" ><?php echo formato_moneda($registro->descuentos); ?></div></td>
<td ><div align="right" ><?php echo formato_moneda($registro->total); ?></div></td>
<td ><div align="right" ><div class="input-group-prepend">
<span class="input-group-text">
<input name="osel<?php echo ($registro->id); ?>" type="checkbox" value="<?php echo ($registro->id); ?>" /></span>
</div>
</div></td>
</tr>
 <?php 
 }
 ?>
 <tr>
<td colspan="10" class="PieTabla">Contraloria del Estado Bolivariano de Gu&aacute;rico</td>
</tr>
</table>
<?php 	
if ($i>0) {
?> <div align="center">
		<button type="button" id="boton" class="btn btn-outline-success waves-effect" onclick="generar_pago();" ><i class="fas fa-cloud-upload-alt prefix grey-text mr-1"></i> Generar Orden de Pago</button>
</div>
<?php 	
}
?>