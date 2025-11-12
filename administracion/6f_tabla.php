<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');

if ($_SESSION['VERIFICADO'] != "SI") { 
header ("Location: ../validacion.php?opcion=val"); 
exit(); }

$acceso=27;
//------- VALIDACION ACCESO USUARIO
include_once "../validacion_usuario.php";
//-----------------------------------
?>
<table class="formateada" border="1" align="center" width="100%">
<tr>
<td class="TituloTablaP" height="41" colspan="10" align="center">Pagos Registrados</td>
</tr>
<tr>
<td  bgcolor="#CCCCCC" align="center"><strong>Item</strong></td>
<td bgcolor="#CCCCCC" align="center"><strong>Banco</strong></td>
<td  bgcolor="#CCCCCC" align="center"><strong>Cuenta</strong></td>
<td bgcolor="#CCCCCC" align="center"><strong>Pago</strong></td>
<td  bgcolor="#CCCCCC" align="center"><strong>Fecha</strong></td>
<td  bgcolor="#CCCCCC" align="center"><strong>Monto</strong></td>
<td bgcolor="#CCCCCC" colspan="2" align="center"><strong>Opciones</strong></td>
</tr>
<?php 	
$id = $_GET['id']; 
//------ MONTAJE DE LOS DATOS
$consultx = "SELECT * FROM	ordenes_pago_pagos WHERE id_orden=$id ";
//echo $consultx;
$tablx = $_SESSION['conexionsql']->query($consultx);
while ($registro = $tablx->fetch_object())
	{
	$i++;
	$monto_pagado+=$registro->monto;
	?>
<tr id="fila<?php echo $registro->id; ?>">
<td><div align="center" ><?php echo ($i); ?></div></td>
<td ><div align="left" ><?php echo ($registro->banco); ?></div></td>
<td ><div align="center" ><?php echo ($registro->cuenta); ?></div></td>
<td ><div align="left" ><?php echo ($registro->num_pago); ?></div></td>
<td ><div align="center" ><?php echo voltea_fecha($registro->fecha_pago); ?></div></td>
<td ><div align="right" ><?php echo formato_moneda($registro->monto); ?></div></td>
<td ><div align="center" ><a data-toggle="tooltip" title="Eliminar"><button type="button" class="btn btn-outline-danger btn-sm" onclick="eliminarp('<?php echo ($registro->id); ?>');"><i class="fas fa-trash-alt"></i></button></a></div></tr>
 <?php 
 }
$consultx = "SELECT total FROM ordenes_pago WHERE id= $id"; 
$tablx = $_SESSION['conexionsql']->query($consultx);
if ($tablx->num_rows>0)	
	{	
	$registro_x = $tablx->fetch_object();
	//------
	$total = $registro_x->total;
	$restante = $registro_x->total - $monto_pagado;
	}
?>
  <tr>
<td colspan="10" class="PieTabla">Contraloria del Estado Bolivariano de Gu&aacute;rico</td>
</tr>
</table>
<script language="JavaScript">
document.form999.txt_monto.value = '<?php echo formato_moneda($restante);	?>';
</script>