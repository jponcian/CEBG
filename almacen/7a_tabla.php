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
		$filtro = " estatus=0 AND ";	
		}
		else { $filtro = " estatus>=3 AND "; }
?>
<table class="table table-hover" width="100%" border="0" align="center">
<tr>
<td class="TituloTablaP" height="41" colspan="10" align="center">Solicitudes</td>
</tr>
<tr>
<td  bgcolor="#CCCCCC" align="center"><strong>N:</strong></td>
<td  bgcolor="#CCCCCC" align="center"><strong>Numero</strong></td>
<td  bgcolor="#CCCCCC" align="center"><strong>Fecha</strong></td>
<td  bgcolor="#CCCCCC" align="left"><strong>Direccion</strong></td>
<td  bgcolor="#CCCCCC" align="center"><strong>Estatus</strong></td>
<td bgcolor="#CCCCCC" ></td>
<td colspan="2" bgcolor="#CCCCCC" ></td>
</tr>
<?php 	
//------ MONTAJE DE LOS DATOS
if ($_GET['tipo']=='4')	 
	{	
	$consultx = "SELECT bn_ingresos.id, bn_ingresos.numero, bn_ingresos.division as id_direccion, fecha, bn_ingresos.estatus, a_direcciones.direccion FROM a_direcciones, bn_ingresos WHERE $filtro a_direcciones.id = bn_ingresos.division ORDER BY fecha DESC, numero DESC;";
	}
else
	{	
	$consultx = "SELECT 'Preliminar' as numero, bn_ingresos_detalle.division as id_direccion, bn_ingresos_detalle.id_bien, fecha, a_direcciones.direccion, estatus FROM a_direcciones, bn_ingresos_detalle WHERE $filtro a_direcciones.id = bn_ingresos_detalle.division GROUP BY bn_ingresos_detalle.division ORDER BY estatus, fecha DESC;";
	}
//echo $consultx;
$tablx = $_SESSION['conexionsql']->query($consultx);

while ($registro = $tablx->fetch_object())
	{
	$i++;
	?>
<tr >
<td><div align="center" ><?php echo ($i); ?></div></td>
<td ><div align="center" ><strong><?php echo ($registro->numero); ?></strong></div></td>
<td ><div align="center" ><strong><?php echo voltea_fecha($registro->fecha); ?></strong></div></td>
<td ><div align="left" ><?php echo ($registro->direccion); ?></div></td>
<td ><div align="center" ><strong><?php echo estatus_ing($registro->estatus); ?></strong></div></td>
<td ><div align="center" ><a data-toggle="tooltip" title="Preliminar"><button type="button" class="btn btn-outline-info waves-effect" onclick="imprimir('<?php echo ($registro->estatus); ?>', '<?php echo encriptar($registro->id); ?>');" ><i class="fas fa-print prefix grey-text mr-1"></i></button></a></div></td>
<td ><div align="center" ><?php if ($registro->estatus==0) { ?><button onclick="generar_solicitud('<?php echo encriptar($registro->id_bien); ?>');" type="button" id="boton<?php echo ($registro->id_bien); ?>" class="btn btn-outline-success waves-effect"><i class="fas fa-cloud-upload-alt prefix grey-text mr-1"></i>Aprobar</button><?php } ?></div></td>
<td ><div align="center" ><?php if ($registro->estatus==10) { ?><button type="button" class="btn btn-outline-danger btn-sm" onclick="eliminar_ing('<?php echo encriptar($registro->id); ?>');"><i class="fas fa-trash-alt"></i></button><?php } ?></div></td>
</tr>
 <?php 
 }
 ?>
 <tr>
<td colspan="10" class="PieTabla">Contraloria del Estado Bolivariano de Gu&aacute;rico</td>
</tr>
</table>