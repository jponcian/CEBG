<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');
//-----------
$id =$_GET['id'];
$oficina =$_GET['oficina'];
?>
<table class="table table-hover" width="100%" border="0" align="center">
<tr>
<td class="TituloTablaP" height="41" colspan="10" align="center">Funcionario(s)</td>
</tr>
<tr>
<td  bgcolor="#CCCCCC" align="center"><strong>N:</strong></td>
<td  bgcolor="#CCCCCC" align="center"><strong>Cedula:</strong></td>
<td  bgcolor="#CCCCCC" align="center"><strong>Nombres:</strong></td>
<td bgcolor="#CCCCCC" align="center"><strong>Cargo</strong></td>
<td bgcolor="#CCCCCC" align="center"><strong>Opcion</strong></td>
</tr>
<?php 	
$cedulas="'0'";
//------ MONTAJE DE LOS DATOS
$consultx = "SELECT cedula FROM viaticos_solicitudes_detalle WHERE id_solicitud='$id';"; 
$tablx = $_SESSION['conexionsql']->query($consultx);
while ($registro_x = $tablx->fetch_object())
{
	$cedulas = $cedulas.','."'".$registro_x->cedula."'";
}
//--------------------

$consultx = "SELECT * FROM rac WHERE suspendido=0 AND temporal=0 AND (nomina)<>'EGRESADOS' AND id_div='$oficina' AND cedula NOT IN ($cedulas) ORDER BY nombre;"; //

$tablx = $_SESSION['conexionsql']->query($consultx);
while ($registro = $tablx->fetch_object())
	{
	$i++;
	?>
<tr id="fila<?php echo $registro->id; ?>">
	<td><div align="center" ><?php echo ($i); ?></div></td>
	<td ><div align="center" ><?php echo ($registro->cedula); ?></div></td>
	<td ><div align="center" ><?php echo ($registro->nombre); ?></div></td>
	<td ><div align="center" ><?php echo ($registro->cargo); ?></div></td>
<td align="center" ><button type="button" id="check_<?php  echo $registro->cedula;?>" class="btn btn-outline-info waves-effect" onclick="agregar2('<?php echo encriptar($registro->cedula);?>','check_<?php echo $registro->cedula;?>')" ><i class="fas fa-save prefix grey-text mr-1"></i></button></td>
</tr>
 <?php 
 }
 ?>
</table>