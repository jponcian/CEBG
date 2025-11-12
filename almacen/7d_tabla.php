<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');
//-----------
?>
<table class="table table-hover" width="100%" border="0" align="center">
<tr>
<td bgcolor="#99FFCC" align="center" colspan="6" height="4"><strong>Materiales en Proceso de Carga</strong></td>
</tr>
<tr>
<td  bgcolor="#CCCCCC" align="center"><strong>Item:</strong></td>
<td  bgcolor="#CCCCCC" align="center"><strong>Descripci&oacute;n:</strong></td>
<td  bgcolor="#CCCCCC" align="center"><strong>Unidad:</strong></td>
<td  bgcolor="#CCCCCC" align="center"><strong>Cantidad:</strong></td>
<td  bgcolor="#CCCCCC" align="left"><strong>Acci&oacute;n:</strong></td>
<td bgcolor="#CCCCCC" align="center"></td>
</tr>
<?php 	
$i=0;
//------ MONTAJE DE LOS DATOS
$consultx = "SELECT * FROM bn_ingresos_detalle, bn_materiales WHERE bn_materiales.id_bien = bn_ingresos_detalle.id_bien AND estatus=0;"; 
//echo $consultx;
$tablx = $_SESSION['conexionsql']->query($consultx);

while ($registro = $tablx->fetch_object())
	{
	$i++;
?>
<tr >
<td><div align="center" ><?php echo ($i); ?></div></td>
<td ><div align="left" ><?php echo ($registro->descripcion_bien); ?></div></td>
<td ><div align="center" ><?php echo ($registro->unidad); ?></div></td>
<td ><div align="center" ><?php echo ($registro->cantidad); ?></div></td>
<td ><div align="center" ><a data-toggle="tooltip" title="Eliminar"><button type="button" class="btn btn-outline-danger btn-sm" onclick="eliminar('<?php echo encriptar($registro->id_bien); ?>');"><i class="fas fa-trash-alt"></i></button></a></div></td>
</tr>
 <?php 
 }
 ?>
</table>