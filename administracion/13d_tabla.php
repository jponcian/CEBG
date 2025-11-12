<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');
//-----------
$id_cont = decriptar($_GET['id']);
?>
<table class="table table-hover" width="100%" border="0" align="center">
<tr>
<td  bgcolor="#CCCCCC" align="center"><strong>N:</strong></td>
<td  bgcolor="#CCCCCC" align="left"><strong>Ejecucion:</strong></td>
<td  bgcolor="#CCCCCC" align="center"><strong>Cant:</strong></td>
<td  bgcolor="#CCCCCC" align="left"><strong>Descripci&oacute;n:</strong></td>
<td bgcolor="#CCCCCC" align="right"><strong>Precio Uni:</strong></td>
<td bgcolor="#CCCCCC" align="right"><strong>Total:</strong></td>
<td bgcolor="#CCCCCC" align="center"><div align="center" ><a data-toggle="tooltip" title="Eliminar Todo"><button type="button" class="btn btn-outline-danger btn-sm" onclick="eliminar_t('<?php echo encriptar($id_cont); ?>');"><i class="fas fa-trash-alt"></i></button></a></div></td>
</tr>
<?php 	
//------ MONTAJE DE LOS DATOS
$consultx = "SELECT id, categoria, partida, cantidad, descripcion, precio_uni, total, exento FROM orden WHERE id_contribuyente=$id_cont AND tipo_orden='M' AND estatus=0 ORDER BY id;"; 
//echo $consultx;
$tablx = $_SESSION['conexionsql']->query($consultx);

while ($registro = $tablx->fetch_object())
	{
	$i++;
	$total = $total + $registro->total;
	if ($registro->exento==0)
		{	$base = $base + $registro->total; $precio=formato_moneda($registro->precio_uni); $monto=formato_moneda($registro->total); }
	else
		{	$precio=formato_moneda($registro->precio_uni).'(e)'; $monto=formato_moneda($registro->total).'(e)'; }
	?>
<tr >
<td><div align="center" ><?php echo ($i); ?></div></td>
<td ><div align="left" ><?php echo ($registro->categoria.'-'.$registro->partida); ?></div></td>
<td ><div align="center" ><?php echo formato_natural($registro->cantidad); ?></div></td>
<td ><div align="left" ><?php echo ($registro->descripcion); ?></div></td>
<td ><div align="right" ><?php echo $precio; ?></div></td>
<td ><div align="right" ><?php echo $monto; ?></div></td>
<td ><div align="center" ><a data-toggle="tooltip" title="Eliminar"><button type="button" class="btn btn-outline-danger btn-sm" onclick="eliminar('<?php echo encriptar($registro->id); ?>', '<?php echo encriptar($id_cont); ?>');"><i class="fas fa-trash-alt"></i></button></a></div></td>
</tr>
 <?php 
 }
 ?>
<tr >
<td bgcolor="#CCCCCC"  colspan="7" ><div align="right" ><strong>Total de la Orden => <?php echo formato_moneda($total); ?></strong></div></td>
</tr>
</table>
<script language="JavaScript">
document.form999.txt_total.value="<?php echo ($base); ?>";
</script>