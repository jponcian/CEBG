<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');
//-----------
$dato_buscar = trim($_GET['valor']);
$fecha1 = voltea_fecha($_GET['fecha1']);
$fecha2 = voltea_fecha($_GET['fecha2']);
//-----------
if ($_GET['tipo']=='1')	
	{
	$filtro = " numero='$dato_buscar' AND estatus=0 AND ";	
	} 
elseif ($_GET['tipo']=='2')	 
	{
	$filtro = " concepto like '%$dato_buscar%' AND estatus=0 AND ";	
	}
	elseif ($_GET['tipo']=='3')	 
		{	
		$filtro = " estatus=0 AND ";	
		}
	elseif ($_GET['tipo']=='4')	 
		{	
		$filtro = " estatus>0 AND ";	
		}
		elseif ($_GET['tipo']=='5')	 
			{	
			$filtro = " bn_reasignaciones_detalle.fecha >= '$fecha1' AND bn_reasignaciones_detalle.fecha <= '$fecha2' AND ";	
			}
			else {$filtro = "";}
?>
<table class="table table-hover" width="100%" border="0" align="center">
<tr>
<td class="TituloTablaP" height="41" colspan="10" align="center">Reasignaciones Generadas</td>
</tr>
<tr>
<td  bgcolor="#CCCCCC" align="center"><strong>N:</strong></td>
<td  bgcolor="#CCCCCC" align="center"><strong>Fecha</strong></td>
<td  bgcolor="#CCCCCC" align="left"><strong>Direccion Origen</strong></td>
<td  bgcolor="#CCCCCC" align="left"><strong>Direccion Destino</strong></td>
<td  bgcolor="#CCCCCC" align="center"><strong>Estatus</strong></td>
<td bgcolor="#CCCCCC" align="center"><strong>Eliminar</strong></td>
<td bgcolor="#CCCCCC" align="center"></td>
<td bgcolor="#CCCCCC" align="center"></td>
</tr>
<?php 	
//------ MONTAJE DE LOS DATOS
//if ($_GET['tipo']=='4')	 
//	{	
//	$consultx = "SELECT bn_reasignaciones.division_actual as id_origen,  bn_reasignaciones.division_destino as id_destino, bn_reasignaciones.id, bn_reasignaciones.fecha, 10 as estatus, bn_dependencias.division AS origen, bn_dependencias2.division AS destino FROM	bn_dependencias, bn_reasignaciones, bn_dependencias AS bn_dependencias2 WHERE bn_dependencias.id = bn_reasignaciones.division_actual 	AND bn_dependencias2.id = bn_reasignaciones.division_destino ORDER BY bn_reasignaciones.fecha DESC, bn_reasignaciones.numero DESC;";
//	}
//else
//	{	
	$consultx = "SELECT bn_reasignaciones_detalle.*, fecha, bn_dependencias.division AS origen, bn_dependencias2.division AS destino , bn_reasignaciones_detalle.id_origen,  bn_reasignaciones_detalle.id_destino FROM bn_dependencias, bn_reasignaciones_detalle, bn_dependencias AS bn_dependencias2 WHERE $filtro bn_dependencias.id = bn_reasignaciones_detalle.id_origen AND bn_dependencias2.id = bn_reasignaciones_detalle.id_destino GROUP BY bn_reasignaciones_detalle.id_reasignacion, bn_reasignaciones_detalle.tipo, bn_reasignaciones_detalle.id_origen,  bn_reasignaciones_detalle.id_destino ORDER BY estatus, fecha DESC;";
//	}
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
<td ><div align="center" ><?php if ($registro->estatus==0) {} else { ?><div align="center" ><a data-toggle="tooltip" title="Eliminar"><button type="button" class="btn btn-outline-danger waves-effect" onclick="borrar('<?php echo encriptar($registro->id_reasignacion); ?>');" ><i class="fas fa-trash-alt"></i></button></a></div><?php } ?></div></td>

	
<td ><div align="center" ><a data-toggle="tooltip" title="Preliminar"><button type="button" class="btn btn-outline-info waves-effect" onclick="imprimir('<?php echo encriptar($registro->id_origen); ?>', '<?php echo encriptar($registro->id_destino); ?>', '<?php echo ($registro->estatus); ?>', '<?php echo encriptar($registro->id_reasignacion); ?>');" ><i class="fas fa-print"></i></button></a></div></td>
<td ><div align="center" ><?php if ($registro->estatus==0) { ?><button onclick="generar_solicitud('<?php echo encriptar($registro->id_bien); ?>');" type="button" id="boton<?php echo ($registro->id_bien); ?>" class="btn btn-outline-success waves-effect"><i class="fa-regular fa-circle-check prefix grey-text mr-1"></i> Aprobar</button><?php } ?></div></td>
</tr>
 <?php 
 }
 ?>
 <tr>
<td colspan="10" class="PieTabla">Contraloria del Estado Bolivariano de Gu&aacute;rico</td>
</tr>
</table>