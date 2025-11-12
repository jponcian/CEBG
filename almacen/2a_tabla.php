<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');
//-----------
if ($_GET['tipo']=='1')	
	{
	$filtro = " numero='".($_GET['valor'])."' AND estatus=0 AND ";	
	} 
elseif ($_GET['tipo']=='2')	 
	{
	$filtro = " concepto like '%".($_GET['valor'])."%' AND estatus=0 AND ";	
	}
	elseif ($_GET['tipo']=='3')	 
		{	
		$filtro = " estatus=0 AND ";	
		}
		else {$filtro = "";}
?>
<table class="table table-hover" width="100%" border="0" align="center">
<tr>
<td class="TituloTablaP" height="41" colspan="10" align="center">Movimientos entre Areas</td>
</tr>
<tr>
<td  bgcolor="#CCCCCC" align="center"><strong>N:</strong></td>
<td  bgcolor="#CCCCCC" align="center"><strong>Fecha</strong></td>
<td  bgcolor="#CCCCCC" align="left"><strong>Area Origen</strong></td>
<td  bgcolor="#CCCCCC" align="left"><strong>Area Destino</strong></td>
<td  bgcolor="#CCCCCC" align="center"><strong>Estatus</strong></td>
<td bgcolor="#CCCCCC" align="center"></td>
<td bgcolor="#CCCCCC" align="center"></td>
</tr>
<?php 	
//------ MONTAJE DE LOS DATOS
if ($_GET['tipo']=='4')	 
	{	
	$consultx = "SELECT bn_reasignaciones.id, bn_reasignaciones.fecha, bn_reasignaciones_detalle.id_area_origen, bn_reasignaciones_detalle.id_area_destino,	estatus, a_areas.area AS origen, a_areas2.area AS destino, bn_reasignaciones_detalle.id_area_origen, bn_reasignaciones_detalle.id_area_destino, estatus FROM	a_areas, bn_reasignaciones_detalle,	bn_reasignaciones, a_areas AS a_areas2 WHERE bn_reasignaciones_detalle.tipo='AREA' AND bn_reasignaciones_detalle.id_reasignacion = bn_reasignaciones.id and a_areas.id = bn_reasignaciones_detalle.id_area_origen AND a_areas2.id = bn_reasignaciones_detalle.id_area_destino GROUP BY bn_reasignaciones_detalle.id_area_origen, bn_reasignaciones_detalle.id_area_destino ORDER BY bn_reasignaciones.fecha DESC, bn_reasignaciones.numero DESC;";
	}
else
	{	
	$consultx = "SELECT bn_reasignaciones_detalle.*, fecha, estatus, a_areas.area AS origen, a_areas2.area AS destino,bn_reasignaciones_detalle.id_area_origen,  bn_reasignaciones_detalle.id_area_destino, estatus FROM a_areas, bn_reasignaciones_detalle, a_areas AS a_areas2 WHERE bn_reasignaciones_detalle.tipo='AREA' AND a_areas.id = bn_reasignaciones_detalle.id_area_origen AND a_areas2.id = bn_reasignaciones_detalle.id_area_destino AND estatus=0 GROUP BY bn_reasignaciones_detalle.id_area_origen,  bn_reasignaciones_detalle.id_area_destino ORDER BY estatus, fecha DESC;";
	}
//echo $consultx;
$tablx = $_SESSION['conexionsql']->query($consultx);

while ($registro = $tablx->fetch_object())
	{
	$i++;
	?>
<tr >
<td><div align="center" ><?php echo ($i); ?></div></td>
<td ><div align="center" ><strong><?php echo voltea_fecha($registro->fecha); ?></strong></div></td>
<td ><div align="left" ><?php echo ($registro->origen); ?></div></td>
<td ><div align="left" ><strong><?php echo ($registro->destino); ?></strong></div></td>
<td ><div align="center" ><strong><?php echo estatus_bn($registro->estatus); ?></strong></div></td>
<td ><div align="center" ><a data-toggle="tooltip" title="Preliminar"><button type="button" class="btn btn-outline-info waves-effect" onclick="imprimir('<?php echo encriptar($registro->id_area_origen); ?>', '<?php echo encriptar($registro->id_area_destino); ?>', '<?php echo encriptar($registro->estatus); ?>', '<?php echo encriptar($registro->id); ?>');" ><i class="fas fa-print prefix grey-text mr-1"></i></button></a></div></td>
<td ><div align="center" ><?php if ($registro->estatus==0) { ?><button onclick="generar_solicitud('<?php echo encriptar($registro->id_bien); ?>');" type="button" id="boton<?php echo ($registro->id_bien); ?>" class="btn btn-outline-success waves-effect"><i class="fa-regular fa-circle-check prefix grey-text mr-1"></i> Aprobar</button><?php } ?></div></td>
</tr>
 <?php 
 }
 ?>
 <tr>
<td colspan="10" class="PieTabla">Contraloria del Estado Bolivariano de Gu&aacute;rico</td>
</tr>
</table>