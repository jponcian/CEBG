<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');

if ($_SESSION['VERIFICADO'] != "SI") { 
header ("Location: ../validacion.php?opcion=val"); 
exit(); }
?>
<table class="formateada" border="1" align="center" width="100%">
<tr>
<td class="TituloTablaP" height="41" colspan="10" align="center">Estados de Cuenta cargados en Sistema</td>
</tr>
<tr>
<td  bgcolor="#CCCCCC" align="center"><strong>N:</strong></td>
<td  bgcolor="#CCCCCC" align="center"><strong>Banco:</strong></td>
<td  bgcolor="#CCCCCC" align="center"><strong>Usuario:</strong></td>
<td bgcolor="#CCCCCC" align="center"><strong>Fecha de carga al sistema:</strong></td>
<td bgcolor="#CCCCCC" align="center"><strong></strong></td>
</tr>
<?php 	
$estatus = array('No','Si');
$actualiza = array('1','0');
$id = $_GET['id']; 
$id_contribuyente = 0;
////------ MONTAJE DE LOS DATOS
$consultx = "SELECT	estado_cuenta_excel.id, banco_receptor.banco, 	banco_receptor.titular, estado_cuenta_excel.usuario, 	estado_cuenta_excel.fecha_proceso FROM	estado_cuenta_excel	INNER JOIN 	banco_receptor	ON 	estado_cuenta_excel.id_banco = banco_receptor.id_banco ORDER BY	id DESC;";
$tablx = $_SESSION['conexionsql']->query($consultx);
if ($tablx->num_rows>0){} else {echo '<tr><td colspan="10" height="35" align="center" ><strong>No hay Registros...</strong></td></tr>';}
while ($registro = $tablx->fetch_object())
	{
	$i++;
	?>
<tr id="fila<?php echo $i; ?>">
<td><div align="center" ><?php echo ($i); ?></div></td>
<td ><div align="center" ><?php echo ($registro->banco); ?></div></td>
<td ><div align="center" ><?php echo persona($registro->usuario); ?></div></td>
<td ><div align="center" ><strong><?php echo voltea_fecha(extrae_fecha($registro->fecha_proceso)).' '.(extrae_hora($registro->fecha_proceso)); ?></strong></div></td>
<td ><div align="center" ><a data-toggle="tooltip" title="Eliminar"><button type="button" class="btn btn-outline-danger btn-sm" onclick="eliminar_edo_cta('<?php echo encriptar($registro->id); ?>');"><i class="fas fa-trash-alt"></i></button></a></div></td>
	</tr>
 <?php 
 }
?>
  <tr>
<td colspan="10" class="PieTabla">Alcaldia del Municipio Francisco de Miranda</td>
</tr>
</table>