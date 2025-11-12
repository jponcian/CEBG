<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');
//-----------
$numero = $_GET['id'];
$fecha = anno(voltea_fecha($_GET['fecha']));
?>
<table class="table table-hover" width="100%" border="0" align="center">
<tr>
<td  bgcolor="#CCCCCC" align="center"><strong>N:</strong></td>
<td  bgcolor="#CCCCCC" align="left"><strong>Ejecucion:</strong></td>
<!--<td  bgcolor="#CCCCCC" align="center"><strong>Cant:</strong></td>
--><td  bgcolor="#CCCCCC" align="left"><strong>Partida:</strong></td>
<!--<td bgcolor="#CCCCCC" align="right"><strong>Precio Uni:</strong></td>
--><td bgcolor="#CCCCCC" align="right"><strong>Monto Bs:</strong></td>
<td bgcolor="#CCCCCC" align="center"></td>
</tr>
<?php 	
//------ MONTAJE DE LOS DATOS
$consultx = "SELECT id, categoria, partida, cantidad, descripcion, precio_uni, total FROM credito_adicional_detalle WHERE year(fecha)='$fecha' AND numero=$numero AND estatus=0 ORDER BY id;"; 
//echo $consultx;
$tablx = $_SESSION['conexionsql']->query($consultx);

while ($registro = $tablx->fetch_object())
	{
	$i++;
	$total = $total + $registro->total;
	?>
<tr >
<td><div align="center" ><?php echo ($i); ?></div></td>
<td ><div align="left" ><?php echo ($registro->categoria.'-'.$registro->partida); ?></div></td>
<!--<td ><div align="center" ><?php echo formato_natural($registro->cantidad); ?></div></td>
--><td ><div align="left" ><?php echo ($registro->descripcion); ?></div></td>
<!--<td ><div align="right" ><?php echo formato_moneda($registro->precio_uni); ?></div></td>
--><td ><div align="right" ><?php echo formato_moneda($registro->total); ?></div></td>
<td ><div align="center" ><a data-toggle="tooltip" title="Eliminar"><button type="button" class="btn btn-outline-danger btn-sm" onclick="eliminar('<?php echo encriptar($registro->id); ?>');"><i class="fas fa-trash-alt"></i></button></a></div></td>
</tr>
 <?php 
 }
 ?>
<tr >
<td bgcolor="#CCCCCC"  colspan="7" ><div align="right" ><strong>TOTAL DEL DECRETO => <?php echo formato_moneda($total); ?></strong></div></td>
</tr>
<?php 	
//------ MONTAJE DE LOS DATOS
$consultx = "SELECT categoria, partida, sum(total) as total, 
	a_categoria.descripcion FROM credito_adicional_detalle, a_categoria WHERE credito_adicional_detalle.categoria = a_categoria.codigo AND year(fecha)='$fecha' AND numero=$numero AND estatus=0 GROUP BY categoria ORDER BY categoria;"; 
//echo $consultx;
$tablx = $_SESSION['conexionsql']->query($consultx);

while ($registro = $tablx->fetch_object())
	{
	?>
	<tr >
	<td bgcolor="#CCCCCC"  colspan="3" ><div align="right" ><strong><?php echo ($registro->descripcion.' '.$registro->categoria); ?> =></strong></div></td>
	<td bgcolor="#CCCCCC"  colspan="2" ><div align="right" ><strong><?php echo formato_moneda($registro->total); ?></strong></div></td>
	</tr>
	<?php
	}
?>
</table>