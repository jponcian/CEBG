<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');
//-----------
if ($_GET['tipo']=='2')	 
	{
	$filtro = " fecha >= '".voltea_fecha($_GET['fecha1'])."' AND fecha <= '".voltea_fecha($_GET['fecha2'])."' AND ";	
	}
	elseif ($_GET['tipo']=='3')	 
		{	
		$filtro = " estatus=5 AND ";	
		}
		else { $filtro = " estatus=10 AND "; }
?>
<table class="table table-hover" width="100%" border="0" align="center">
<tr>
<td class="TituloTablaP" height="41" colspan="10" align="center">Solicitudes</td>
</tr>
<tr>
<td  bgcolor="#CCCCCC" align="center"><strong>N:</strong></td>
<td  bgcolor="#CCCCCC" align="left"><strong>Direccion</strong></td>
<td  bgcolor="#CCCCCC" align="center"><strong>Numero</strong></td>
<td  bgcolor="#CCCCCC" align="center"><strong>Fecha</strong></td>
<td  bgcolor="#CCCCCC" align="center"><strong>Estatus</strong></td>
<td bgcolor="#CCCCCC" align="center"></td>
<td bgcolor="#CCCCCC" align="center"></td>
</tr>
<?php 	
//------ MONTAJE DE LOS DATOS
$consultx = "SELECT bn_solicitudes.id, bn_solicitudes.numero, bn_solicitudes.division as id_direccion, fecha, bn_solicitudes.estatus, a_direcciones.direccion FROM a_direcciones, bn_solicitudes WHERE $filtro a_direcciones.id = bn_solicitudes.division GROUP BY bn_solicitudes.id ORDER BY estatus, fecha DESC;";
//echo $consultx;
$tablx = $_SESSION['conexionsql']->query($consultx);

while ($registro = $tablx->fetch_object())
	{
	$i++;
	?>
<tr >
<td><div align="center" ><?php echo ($i); ?></div></td>
<td ><div align="left" ><?php echo ($registro->direccion); ?></div></td>
<td ><div align="center" ><strong><?php echo rellena_cero($registro->numero,5); ?></strong></div></td>
<td ><div align="center" ><strong><?php echo voltea_fecha($registro->fecha); ?></strong></div></td>
<td ><div align="center" ><strong><?php echo estatus_alm($registro->estatus); ?></strong></div></td>
<td ><div align="center" ><a data-toggle="tooltip" title="Solicitud"><button type="button" class="btn btn-outline-info waves-effect" onclick="imprimir('<?php echo encriptar($registro->id_direccion); ?>', '<?php echo ($registro->estatus); ?>', '<?php echo encriptar($registro->id); ?>');" ><i class="fas fa-print prefix grey-text mr-1"></i></button></a></div></td>
<td ><div align="center" ><?php if ($registro->estatus==5) { ?><button data-toggle="modal" data-target="#modal_largo" data-backdrop="static" data-keyboard="false" onclick="agregar('<?php echo encriptar($registro->id); ?>');" type="button" id="boton<?php echo ($registro->id_bien); ?>" class="btn btn-outline-success waves-effect"><i class="fas fa-cloud-upload-alt prefix grey-text mr-1"></i>Procesar</button><?php } ?></div></td>
</tr>
 <?php 
 }
 ?>
 <tr>
<td colspan="10" class="PieTabla">Contraloria del Estado Bolivariano de Gu&aacute;rico</td>
</tr>
</table>