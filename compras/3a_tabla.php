<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');
//-----------
if ($_GET['tipo']=='1')	
	{$filtro = " numero='".($_GET['valor'])."' AND ";} 
else 
	{
	if ($_GET['tipo']=='2')	
		{$filtro = " concepto like '%".($_GET['valor'])."%' AND ";} 
	else {$filtro = "";}
	}

?>
<table class="table table-hover" width="100%" border="0" align="center">
<tr>
<td class="TituloTablaP" height="41" colspan="10" align="center">Ordenes de Compra y Servicio Registradas</td>
</tr>
<tr>
<td  bgcolor="#CCCCCC" align="center"><strong>N:</strong></td>
<td  bgcolor="#CCCCCC" align="left"><strong>Rif</strong></td>
<td  bgcolor="#CCCCCC" align="left"><strong>Contribuyente</strong></td>
<td  bgcolor="#CCCCCC" align="left"><strong>Fecha:</strong></td>
<td bgcolor="#CCCCCC" align="left"><strong>Numero:</strong></td>
<td bgcolor="#CCCCCC" align="left"><strong>Concepto:</strong></td>
<td bgcolor="#CCCCCC" align="right"><strong>Total:</strong></td>
<td bgcolor="#CCCCCC" align="center"></td>
<td bgcolor="#CCCCCC" align="center"></td>
</tr>
<?php 	
//------ MONTAJE DE LOS DATOS
$consultx = "SELECT tipo_orden, orden_solicitudes.estatus, orden_solicitudes.id, id_contribuyente, contribuyente.rif, fecha, numero, descripcion, total, contribuyente.nombre FROM orden_solicitudes, contribuyente WHERE $filtro (tipo_orden='CD' OR tipo_orden='CC' OR tipo_orden='CP') AND estatus<>99 AND orden_solicitudes.id_contribuyente = contribuyente.id ORDER BY fecha DESC, orden_solicitudes.id DESC;"; 
//echo $consultx;
$tablx = $_SESSION['conexionsql']->query($consultx);

while ($registro = $tablx->fetch_object())
	{
	$i++;
	?>
<tr >
<td><div align="center" ><?php echo ($i); ?></div></td>
<td ><div align="left" ><?php echo ($registro->rif); ?></div></td>
<td ><div align="left" ><?php echo ($registro->nombre); ?></div></td>
<td ><div align="left" ><?php echo voltea_fecha($registro->fecha); ?></div></td>
<td ><div align="left" ><strong><?php echo estatus($registro->estatus,rellena_cero($registro->numero,8)); ?></strong></div></td>
<td ><div align="left" ><?php echo ($registro->descripcion); ?></div></td>
<td ><div align="right" ><strong><?php echo formato_moneda($registro->total); ?></strong></div></td>
<td ><div align="center" ><a data-toggle="tooltip" title="Modificar Orden"><button type="button" data-toggle="modal" data-target="#modal_largo" class="btn btn-info waves-effect" onclick="modificar('<?php echo ($registro->id); ?>','<?php echo ($registro->tipo_orden); ?>','<?php echo ($registro->estatus); ?>');" >Modificar</button></a></div></td>
<td ><div align="center" ><a data-toggle="tooltip" title="Ver Orden"><button type="button" class="btn btn-outline-primary waves-effect" onclick="imprimir('<?php echo encriptar($registro->id); ?>','<?php echo ($registro->tipo_orden); ?>');" ><i class="fas fa-print prefix grey-text mr-1"></i></button></a></div></td>
</tr>
 <?php 
 }
 ?>
 <tr>
<td colspan="10" class="PieTabla">Contraloria del Estado Bolivariano de Gu&aacute;rico</td>
</tr>
</table>