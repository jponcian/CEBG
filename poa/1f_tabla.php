<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');

if ($_SESSION['VERIFICADO'] != "SI") { 
header ("Location: ../validacion.php?opcion=val"); 
exit(); }

$acceso=62;
//------- VALIDACION ACCESO USUARIO
include_once "../validacion_usuario.php";
//-----------------------------------
?>
<table class="formateada" border="1" align="center" width="100%">
<tr>
<td class="TituloTablaP" height="41" colspan="10" align="center">Proyectos Registrados</td>
</tr>
<tr>
<td  bgcolor="#CCCCCC" align="center"><strong>Numero</strong></td>
<td  bgcolor="#CCCCCC" align="center"><strong>Tipo</strong></td>
<td  bgcolor="#CCCCCC" align="center"><strong>Denominacion</strong></td>
<td bgcolor="#CCCCCC" align="center"><strong>Responsables</strong></td>
<td bgcolor="#CCCCCC" colspan="2" align="center"><strong>Opciones</strong></td>
</tr>
<?php 	
$id = $_GET['id_poa']; 
$anno = $_GET['anno']; 
//------ MONTAJE DE LOS DATOS
$consultx = "SELECT * FROM poa_proyecto WHERE id_poa = '$id' ORDER BY numero";//$filtrar.$_GET['valor'].";"; 
//echo $consultx;
$tablx = $_SESSION['conexionsql']->query($consultx);
while ($registro = $tablx->fetch_object())
	{
	$i++;
	//list($banco,$cuenta)=explode(' ', $registro->codigo);
	?>
<tr id="fila<?php echo $registro->id; ?>">
<td><div align="center" ><?php echo ($registro->numero); ?></div></td>
<td ><div align="left" ><?php echo ($registro->tipo); ?></div></td>
<td ><div align="left" ><?php echo ($registro->descripcion); ?></div></td>

<td ><div align="center" ><a data-toggle="tooltip" title="Agregar o Eliminar"><button type="button" class="btn btn-outline-info btn-sm" onclick="responsables('<?php echo $id; ?>','<?php echo $anno; ?>', '<?php echo ($registro->id); ?>');">Responsables</button></a></div></td>

<td ><div align="center" ><a data-toggle="tooltip" title="Editar"><button type="button" class="btn btn-outline-warning btn-sm" onclick="editar('<?php echo ($registro->id); ?>', '<?php echo $id; ?>');"><i class="fas fa-edit"></i></button></a></div>
<td ><div align="center" ><a data-toggle="tooltip" title="Eliminar"><button type="button" class="btn btn-outline-danger btn-sm" onclick="eliminar2('<?php echo ($registro->id); ?>', '<?php echo $id; ?>','<?php echo $anno; ?>');"><i class="fas fa-trash-alt"></i></button></a></div></tr>
 <?php 
 }
 ?>
  <tr>
<td colspan="10" class="PieTabla">Contraloria del Estado Bolivariano de Gu&aacute;rico</td>
</tr>
</table>