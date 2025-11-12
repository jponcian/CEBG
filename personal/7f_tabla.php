<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');

if ($_SESSION['VERIFICADO'] != "SI") { 
header ("Location: ../validacion.php?opcion=val"); 
exit(); }

$acceso=89;
//------- VALIDACION ACCESO USUARIO
include_once "../validacion_usuario.php";
//-----------------------------------
?>
<table class="formateada" border="1" align="center" width="100%">
<tr>
<td class="TituloTablaP" height="41" colspan="10" align="center">Excepciones Registradas</td>
</tr>
<tr>
<td  bgcolor="#CCCCCC" align="center"><strong>Item</strong></td>
<td bgcolor="#CCCCCC" align="center"><strong>Nomina</strong></td>
<td  bgcolor="#CCCCCC" align="center"><strong>Cargo</strong></td>
<td bgcolor="#CCCCCC" align="center"><strong>Monto</strong></td>
<td bgcolor="#CCCCCC" colspan="2" align="center"><strong>Opciones</strong></td>
</tr>
<?php 	
$id = $_GET['id']; 
$codigo = $_GET['codigo']; 
//------ MONTAJE DE LOS DATOS
$consultx = "SELECT * FROM a_bonos WHERE codigo = '$id' and cargo <> 'Todos' ORDER BY monto ";//$filtrar.$_GET['valor'].";"; 
//echo $consultx;
$tablx = $_SESSION['conexionsql']->query($consultx);
while ($registro = $tablx->fetch_object())
	{
	$i++;
	//list($banco,$cuenta)=explode(' ', $registro->codigo);
	?>
<tr id="fila<?php echo $registro->id; ?>">
<td><div align="center" ><?php echo ($i); ?></div></td>
<td ><div align="left" ><?php echo ($registro->codigo).' '.($registro->nomina); ?></div></td>
<td ><div align="left" ><strong><?php echo ($registro->cargo); ?></strong></div></td>
<td ><div align="right" ><strong><?php echo formato_moneda($registro->monto); ?></strong></div></td>
<!--<td ><div align="center" ><a data-toggle="tooltip" title="Editar"><button type="button" class="btn btn-outline-warning btn-sm" onclick="editar('<?php //echo ($registro->id); ?>', '<?php //echo $anno; ?>');"><i class="fas fa-edit"></i></button></a></div></td>-->
<td ><div align="center" ><a data-toggle="tooltip" title="Eliminar"><button type="button" class="btn btn-outline-danger btn-sm" onclick="eliminar2('<?php echo ($registro->id); ?>','<?php echo ($id); ?>');"><i class="fas fa-trash-alt"></i></button></a></div></tr>
 <?php 
 }
 ?>
  <tr>
<td colspan="10" class="PieTabla">Contraloria del Estado Bolivariano de Gu&aacute;rico</td>
</tr>
</table>