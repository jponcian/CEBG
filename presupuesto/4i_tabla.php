<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');
//-----------
?>
<table class="table table-hover" width="100%" border="0" align="center">
<tr>
<td class="TituloTablaP" height="41" colspan="7" align="center">Traslados en Espera</td>
</tr>
<tr>
<td bgcolor="#CCCCCC" align="center"><strong>N&deg;</strong></td>
<td bgcolor="#CCCCCC" align="center"><strong>Categoria</strong></td>
<td bgcolor="#CCCCCC" align="center"><strong>Partida</strong></td>
<td bgcolor="#CCCCCC" align="center"><strong>Descripcion</strong></td>
<td bgcolor="#CCCCCC" align="center"><strong>Monto Trasladado</strong></td>
<td bgcolor="#CCCCCC" align="center"><strong>Tipo</strong></td>
<td bgcolor="#CCCCCC" align="center"><strong>Eliminar</strong></td>
</tr>
<?php 	
//------ MONTAJE DE LOS DATOS
$consultx = "SELECT * FROM traslados WHERE estatus=0 ORDER BY categoria1, partida1, monto1, categoria2, partida2, monto2;";
//echo $consultx;
$tablx = $_SESSION['conexionsql']->query($consultx);
while ($registro = $tablx->fetch_object())
	{
	$i++;
	?>
<tr >
<td><div align="center" ><?php echo ($i); ?></div></td>
<td ><div align="center" ><?php if ($registro->categoria1<>'') {echo $registro->categoria1;} else {echo $registro->categoria2;} ?></div></td>
<td ><div align="center" ><?php if ($registro->partida1<>'') {echo $registro->partida1;} else {echo $registro->partida2;} ?></div></td>
<td ><div align="left" ><?php if ($registro->partida1<>'') {echo partida($registro->partida1);} else {echo partida($registro->partida2);} ?></div></td>
<td ><div align="center" ><?php if ($registro->monto1>0) {echo formato_moneda($registro->monto1);} else {echo formato_moneda($registro->monto2);} ?></div></td>
<td ><div align="center" ><?php if ($registro->monto2>0) {?><i class="fa-regular fa-circle-up fa-2x" style="color: green"></i><?php } else { ?><i class="fa-regular fa-circle-down fa-2x" style="color: red"></i><?php } ?></div></td>
<td ><div align="center" ><a data-toggle="tooltip" title="Eliminar"><button type="button" class="btn btn-outline-danger btn-sm" onclick="eliminar_traslado('<?php echo encriptar($registro->id); ?>');"><i class="fas fa-trash-alt"></i></button></a></div></td>
</tr>
 <?php 
 }
 ?>
 <tr>
<td colspan="7" class="PieTabla">Contraloria del Estado Bolivariano de Gu&aacute;rico</td>
</tr>
</table>