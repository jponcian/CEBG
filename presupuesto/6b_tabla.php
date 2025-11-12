<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');
//-----------
$fecha = decriptar($_GET['fecha']);
$tipo = decriptar($_GET['tipo']);
$id_solicitud = 99999999999999;
$consultx = "SELECT anno, id, descripcion, desde, hasta FROM nomina_solicitudes WHERE tipo_pago = '$tipo'  AND hasta = '$fecha' ;"; //echo $consultx;
$tablx = $_SESSION['conexionsql']->query($consultx);
while ($registro = $tablx->fetch_object())
	{
	$id_solicitud = $id_solicitud .','. $registro->id;
	}
$_SESSION['id_solicitud'] = $id_solicitud;
?>
<table class="table table-hover" width="100%" border="0" align="center">
<tr>
<td class="TituloTablaP" height="41" colspan="10" align="center">Solicitudes de Pago (RRHH)</td>
</tr>
<tr>
<!--<td  bgcolor="#CCCCCC" align="center"><strong>N:</strong></td>
<td  bgcolor="#CCCCCC" align="left"><strong>Orden:</strong></td>
--><td  bgcolor="#CCCCCC" align="center" ><strong>Categoria:</strong></td>
<td  bgcolor="#CCCCCC" align="center"><strong>Partida:</strong></td>
<td bgcolor="#CCCCCC" align="left"><strong>Descripcion:</strong></td>
<td bgcolor="#CCCCCC" align="right"><strong>Total:</strong></td>
</tr>
<?php 	
//------ MONTAJE DE LOS DATOS
$consultx = "SELECT nomina_asignaciones.categoria, nomina_asignaciones.partida, a_partidas.descripcion, sum(nomina_asignaciones.asignaciones) as asignaciones, sum(nomina_asignaciones.total_asignacion) as total_asignaciones FROM nomina_solicitudes, nomina , nomina_asignaciones, a_partidas WHERE nomina_solicitudes.id = nomina.id_solicitud AND nomina.id_solicitud in (".$_SESSION['id_solicitud'].") AND nomina.id = nomina_asignaciones.id_nomina AND a_partidas.codigo = nomina_asignaciones.partida GROUP BY nomina_asignaciones.categoria, nomina_asignaciones.partida ORDER BY num_sol_pago, nomina_asignaciones.categoria, nomina_asignaciones.partida;"; //echo $consultx;
$tablx = $_SESSION['conexionsql']->query($consultx);

while ($registro = $tablx->fetch_object())
	{
	$i++;
	?>
<tr >
<!--<td><div align="center" ><?php //echo ($i); ?></div></td>
<td ><div align="left" ><?php //echo ($registro->num_sol_pago); ?></div></td>
--><td ><div align="center" ><?php echo ($registro->categoria); ?></div></td>
<td ><div align="center" ><?php echo ($registro->partida); ?></div></td>
<td ><div align="left" ><?php echo ($registro->descripcion); ?></div></td>
<td ><div align="right" ><?php echo formato_moneda($registro->total_asignaciones); ?></div></td>
</tr>
 <?php 
 }
 ?>
 <tr>
<td colspan="10" class="PieTabla">Contraloria del Estado Bolivariano de Gu&aacute;rico</td>
</tr>
</table>