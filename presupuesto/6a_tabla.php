<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');
//-----------
$dato_buscar = trim($_GET['valor']);
$filtro = $_GET['tipo'];
$fecha1 = voltea_fecha($_GET['fecha1']);
$fecha2 = voltea_fecha($_GET['fecha2']);

switch ($filtro) {
    case 2:
        $filtrar = "";
        break;
    case 3:
        $filtrar = " AND ((desde >= '$fecha1' AND desde <= '$fecha2') or (hasta >= '$fecha1' AND hasta <= '$fecha2'))";
        break;
}?>
<table class="table table-hover" width="100%" border="0" align="center">
<tr>
<td class="TituloTablaP" height="41" colspan="10" align="center">Nominas con Solicitud de Pago</td>
</tr>
<tr>
<td  bgcolor="#CCCCCC" align="center"><strong>N:</strong></td>
<td  bgcolor="#CCCCCC" align="left"><strong>Descripci&oacute;n:</strong></td>
<td  bgcolor="#CCCCCC" align="center" ><strong>Periodo:</strong></td>
<td  bgcolor="#CCCCCC" align="right"><strong>Asignaciones:</strong></td>
<td bgcolor="#CCCCCC" align="right"><strong>Deducciones:</strong></td>
<td bgcolor="#CCCCCC" align="right"><strong>Total:</strong></td>
<td bgcolor="#CCCCCC" align="right" colspan="2"></td>
</tr>
<?php 	
//------ MONTAJE DE LOS DATOS
$consultx = "SELECT descripcion, tipo_pago, desde, hasta, sum(asignaciones) as asignaciones, sum(descuentos) as descuentos, sum(total) as total FROM nomina WHERE tipo_pago IN ('001', '002', '003', '004', '005', '008', '009', '013') $filtrar GROUP BY tipo_pago, nomina.hasta ORDER BY fecha DESC;"; 
//echo $consultx;
$tablx = $_SESSION['conexionsql']->query($consultx);

while ($registro = $tablx->fetch_object())
	{
	$i++;
	?>
<tr >
<td><div align="center" ><?php echo ($i); ?></div></td>
<td ><div align="left" ><?php echo ($registro->descripcion); ?></div></td>
<td ><div align="center" ><?php echo voltea_fecha($registro->desde). ' al ' .voltea_fecha($registro->hasta); ?></div></td>
<td ><div align="right" ><?php echo formato_moneda($registro->asignaciones); ?></div></td>
<td ><div align="right" ><?php echo formato_moneda($registro->descuentos); ?></div></td>
<td ><div align="right" ><?php echo formato_moneda($registro->total); ?></div></td>
<td ><div align="center" ><button data-toggle="modal" data-target="#modal_largo" type="button" class="btn btn-outline-info waves-effect" onclick="enviar_listado('<?php echo encriptar($registro->hasta); ?>', '<?php echo encriptar($registro->tipo_pago); ?>');" >HTML</button></div></td>
<td ><div align="center" ><button type="button" class="btn btn-outline-primary waves-effect" onclick="imprimir_nom('<?php echo encriptar($registro->hasta); ?>', '<?php echo encriptar($registro->tipo_pago); ?>');" ><i class="fas fa-print prefix grey-text mr-1"></i></button></div></td>
</tr>
 <?php 
 }
 ?>
 <tr>
<td colspan="10" class="PieTabla">Contraloria del Estado Bolivariano de Gu&aacute;rico</td>
</tr>
</table>
<script language="JavaScript">
//----------------
function enviar_listado(hasta,tipo){
	$('#modal_lg').html('<div align="center"><div class="spinner-border" role="status"></div><br><strong>Un momento, por favor...</strong></div>');
	$('#modal_lg').load('presupuesto/6b_tabla.php?fecha='+hasta+ '&tipo=' +tipo);
}
</script>