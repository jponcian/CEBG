<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');

if ($_SESSION['VERIFICADO'] != "SI") { 
header ("Location: ../validacion.php?opcion=val"); 
exit(); }

$acceso=77;
//------- VALIDACION ACCESO USUARIO
include_once "../validacion_usuario.php";
//-----------------------------------
?>
<table class="formateada" border="1" align="center" width="100%">
<tr>
<td class="TituloTablaP" height="41" colspan="10" align="center">Cuentas Registradas</td>
</tr>
<tr>
<td  bgcolor="#CCCCCC" align="center"><strong>Item</strong></td>
<td bgcolor="#CCCCCC" align="center"><strong>Banco</strong></td>
<td  bgcolor="#CCCCCC" align="center"><strong>Cuenta</strong></td>
<td bgcolor="#CCCCCC" align="center"><strong>Chequera</strong></td>
<td  bgcolor="#CCCCCC" align="center"><strong>Cheque</strong></td>
<td bgcolor="#CCCCCC" colspan="2" align="center"><strong>Opciones</strong></td>
</tr>
<?php 	
$id = $_GET['id']; 
//------ MONTAJE DE LOS DATOS
$consultx = "SELECT * FROM	a_cuentas_cheques WHERE	id_chequera=$id ORDER BY cheque";//$filtrar.$_GET['valor'].";"; 
//echo $consultx;
$tablx = $_SESSION['conexionsql']->query($consultx);
while ($registro = $tablx->fetch_object())
	{
	$i++;
	//list($banco,$cuenta)=explode(' ', $registro->codigo);
	?>
<tr id="fila<?php echo $registro->id; ?>">
<td><div align="center" ><?php echo ($i); ?></div></td>
<td ><div align="left" ><?php echo ($registro->banco); ?></div></td>
<td ><div align="center" ><?php echo ($registro->cuenta); ?></div></td>
<td ><div align="left" ><?php echo ($registro->chequera); ?></div></td>
<td ><div align="left" ><?php echo ($registro->cheque); ?></div></td>
<td ><div align="center" ><a data-toggle="tooltip" title="Eliminar"><button type="button" class="btn btn-outline-danger btn-sm" onclick="eliminar2('<?php echo ($registro->id); ?>', '<?php echo $id; ?>');"><i class="fas fa-trash-alt"></i></button></a></div></tr>
 <?php 
 }
 ?>
  <tr>
<td colspan="10" class="PieTabla">Contraloria del Estado Bolivariano de Gu&aacute;rico</td>
</tr>
</table>