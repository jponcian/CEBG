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
<td class="TituloTablaP" height="41" colspan="10" align="center">Solicitud de Pago (Viaticos)</td>
</tr>
<tr>
<td  bgcolor="#CCCCCC" align="center"><strong>N:</strong></td>
<td  bgcolor="#CCCCCC" align="left"><strong>Funcionario:</strong></td>
<td  bgcolor="#CCCCCC" align="left"><strong>Fecha:</strong></td>
<td bgcolor="#CCCCCC" align="left"><strong>Numero:</strong></td>
<td bgcolor="#CCCCCC" align="left"><strong>Concepto:</strong></td>
<td bgcolor="#CCCCCC" align="right"><strong>Total:</strong></td>
<td bgcolor="#CCCCCC" align="center"></td>
<td bgcolor="#CCCCCC" align="center"></td>
</tr>
<?php 	
$i=0;
//------ MONTAJE DE LOS DATOS
$consultx = "SELECT contribuyente.nombre, orden_solicitudes.tipo_orden, orden_solicitudes.id_contribuyente, contribuyente.rif, orden_solicitudes.fecha, orden_solicitudes.descripcion, orden_solicitudes.numero, orden_solicitudes.total, orden_solicitudes.id FROM contribuyente , orden_solicitudes WHERE  orden_solicitudes.tipo_orden=5 and orden_solicitudes.id_contribuyente = contribuyente.id AND orden_solicitudes.estatus = 5 ORDER BY orden_solicitudes.fecha DESC;"; 
//echo $consultx;
$tablx = $_SESSION['conexionsql']->query($consultx);

while ($registro = $tablx->fetch_object())
	{
	$i++;
//	if ($registro->tipo_orden==1)	{$tipo='COMPRA ';}
//	if ($registro->tipo_orden==2)	{$tipo='SERVICIO ';}
//	if ($registro->tipo_orden==3)	{$tipo='MANUAL ';}
//	if ($registro->tipo_orden==4)	{$tipo='FINANCIERA ';}
//	if ($registro->tipo_orden==5)	{$tipo='VIATICO ';}
	if ($registro->tipo_orden==5)	{$tipo='';}
	?>
<tr >
<td><div align="center" ><?php echo ($i); ?></div></td>
<td ><div align="left" ><strong><?php echo ($registro->nombre); ?></strong></div></td>
<td ><div align="left" ><?php echo voltea_fecha($registro->fecha); ?></div></td>
<td ><div align="left" ><strong><?php echo $tipo.rellena_cero($registro->numero,6); ?></strong></div></td>
<td ><div align="left" ><?php echo ($registro->descripcion); ?></div></td>
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
		<button type="button" id="boton" class="btn btn-outline-success waves-effect" onclick="asignar_numero();" data-toggle="modal" data-target="#modal_normal" ><i class="fas fa-cloud-upload-alt prefix grey-text mr-1"></i> Generar Orden de Pago</button>
</div>
<?php 	
}
?>