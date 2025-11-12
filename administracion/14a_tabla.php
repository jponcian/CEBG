<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');
//-----------
if ($_GET['tipo']=='1')	
	{
	$filtro = " numero='".($_GET['valor'])."' AND estatus=0 AND ";	
	} 
elseif ($_GET['tipo']=='2')	 
	{
	$filtro = " concepto like '%".($_GET['valor'])."%' AND estatus=0 AND ";	
	}
	elseif ($_GET['tipo']=='3')	 
		{	
		$filtro = " estatus=0 AND ";	
		}
		elseif ($_GET['tipo']=='4')	 
			{	
			$filtro = " estatus=5 AND ";	
			}
			else {$filtro = " estatus>5 AND estatus<>99 AND ";}
?>
<table class="table table-hover" width="100%" border="0" align="center">
<tr>
<td class="TituloTablaP" height="41" colspan="10" align="center">Orden <!--de Pago -->Financiera Registradas</td>
</tr>
<tr>
<td  bgcolor="#CCCCCC" align="center"><strong>N:</strong></td>
<td  bgcolor="#CCCCCC" align="left"><strong>Rif</strong></td>
<td  bgcolor="#CCCCCC" align="left"><strong>Contribuyente</strong></td>
<td  bgcolor="#CCCCCC" align="left"><strong>Fecha:</strong></td>
<td bgcolor="#CCCCCC" align="left"><strong>Concepto:</strong></td>
<td bgcolor="#CCCCCC" align="right"><strong>Total:</strong></td>
<td bgcolor="#CCCCCC" align="center"></td>
<td bgcolor="#CCCCCC" align="center"></td>
</tr>
<?php 	
//------ MONTAJE DE LOS DATOS
$consultx = "SELECT id_solicitud, tipo_orden, orden.estatus, orden.id, id_contribuyente, orden.rif, fecha, numero, concepto, sum(total) as total1, contribuyente.nombre FROM orden, contribuyente WHERE $filtro tipo_orden='F' AND orden.id_contribuyente = contribuyente.id GROUP BY numero, id_contribuyente, fecha, estatus ORDER BY fecha DESC, orden.id DESC;"; 
//echo $consultx;
$tablx = $_SESSION['conexionsql']->query($consultx);

while ($registro = $tablx->fetch_object())
	{
	$i++;
	?>
<tr >
<td><div align="center" ><?php echo ($i); ?></div></td>
<td ><div align="left" ><?php echo ($registro->rif); ?></div></td>
<td ><div align="left" ><?php echo ($registro->nombre); ?></div></td>
<td ><div align="left" ><?php echo voltea_fecha($registro->fecha); ?></div></td>
<td ><div align="left" ><?php echo ($registro->concepto); ?></div></td>
<td ><div align="right" ><strong><?php echo formato_moneda($registro->total1); ?></strong></div></td>
<td ><div align="center" ><a data-toggle="tooltip" title="Preliminar"><button type="button" class="btn btn-outline-info waves-effect" <?php if ($registro->estatus==0) { ?> onclick="imprimir('<?php echo encriptar($registro->id_contribuyente); ?>','0');" <?php } else {?> onclick="imprimir('<?php echo encriptar($registro->id_solicitud); ?>','1');" <?php } ?> ><i class="fas fa-print prefix grey-text mr-1"></i></button></a></div></td>
<?php if ($registro->estatus<5)	{ ?>
<td ><div align="center" ><button type="button" id="boton<?php echo ($registro->id_contribuyente); ?>" class="btn btn-outline-success waves-effect" onclick="generar_solicitud('<?php echo encriptar($registro->id_contribuyente); ?>','boton<?php echo ($registro->id_contribuyente); ?>');" >Generar Solicitud</button></div></td>
<?php } ?>
</tr>
 <?php 
 }
 ?>
 <tr>
<td colspan="10" class="PieTabla">Contraloria del Estado Bolivariano de Gu&aacute;rico</td>
</tr>
</table>