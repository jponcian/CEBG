<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');
//-----------
$dato_buscar = trim($_GET['valor']);
$filtro = $_GET['tipo'];
$fecha1 = voltea_fecha($_GET['fecha1']);
$fecha2 = voltea_fecha($_GET['fecha2']);
$anno = ($_GET['anno']);

switch ($filtro) {
    case 1:
        $filtrar = " numero = $dato_buscar";
        break;
    case 2:
        $filtrar = " concepto like '%$dato_buscar%' ";
        break;
    case 3:
        $filtrar = " estatus=0 ";
        break;
    case 4:
        $filtrar = " estatus>0 ";
        break;
    case 5:
        $filtrar = " fecha >= '$fecha1' AND fecha <= '$fecha2' ";
		$_SESSION['titulo'] = "Relacion de Decretos desde el ".$_GET['fecha1']." al ".$_GET['fecha2'];
        break;
}
//-----------
?>
<table class="table table-hover" width="100%" border="0" align="center">
<tr>
<td class="TituloTablaP" height="41" colspan="10" align="center">Decretos Registrados (Creditos Adicionales)</td>
</tr>
<tr>
<td colspan="10" align="center"><button type="button" id="botonb" class="btn btn-lg btn-block btn-info" onClick="rep();"><i class="fas fa-search mr-2"></i>Ver Pdf</button></td>
</tr>
<tr>
<td  bgcolor="#CCCCCC" align="center"><strong>N&deg;</strong></td>
<td  bgcolor="#CCCCCC" align="left"><strong>Numero:</strong></td>
<td  bgcolor="#CCCCCC" align="center"><strong>Fecha:</strong></td>
<td bgcolor="#CCCCCC" align="left"><strong>Concepto:</strong></td>
<td bgcolor="#CCCCCC" align="right"><strong>Total:</strong></td>
<td bgcolor="#CCCCCC" align="center"></td>
<td bgcolor="#CCCCCC" align="center" colspan="3"></td>
</tr>
<?php 	
//------ MONTAJE DE LOS DATOS
$consultx = "SELECT numero, id_credito, tipo_orden, credito_adicional_detalle.estatus, credito_adicional_detalle.id, fecha, numero, concepto, sum(total) as total1 FROM credito_adicional_detalle WHERE YEAR(credito_adicional_detalle.fecha)=$anno AND $filtrar GROUP BY numero ORDER BY numero DESC, credito_adicional_detalle.id DESC;"; 
//echo $consultx;
$_SESSION['consulta'] = $consultx;
$tablx = $_SESSION['conexionsql']->query($consultx);
while ($registro = $tablx->fetch_object())
	{
	$i++;
	?>
<tr >
<td><div align="center" ><?php echo ($i); ?></div></td>
<td ><div align="left" ><?php echo rellena_cero($registro->numero,8); ?></div></td>
<td ><div align="center" ><?php echo voltea_fecha($registro->fecha); ?></div></td>
<td ><div align="left" ><?php echo ($registro->concepto); ?></div></td>
<td ><div align="right" ><strong><?php echo formato_moneda($registro->total1); ?></strong></div></td>
	<td ><?php if ($registro->estatus==0) { ?><div align="center" ><a data-toggle="tooltip" title="Detalle del Credito"><button type="button" class="btn btn-outline-info waves-effect" onclick="imprimir('<?php echo encriptar($registro->numero); ?>','<?php echo ($registro->fecha); ?>');" ><i class="fas fa-print prefix grey-text mr-1"></i></button></a></div><?php } ?></td>
<td ><?php if ($registro->estatus>0) { ?><div align="center" ><a data-toggle="tooltip" title="Detalle del Credito"><button type="button" class="btn btn-outline-info waves-effect" onclick="imprimir2('<?php echo encriptar($registro->id_credito); ?>');" ><i class="fas fa-print prefix grey-text mr-1"></i></button></a></div><?php } ?></td>
	<td><?php if ($registro->estatus==0) { ?><div align="center" ><button type="button" id="boton1" class="btn btn-outline-success waves-effect" onclick="generar_solicitud('<?php echo ($registro->numero); ?>','<?php echo ($registro->fecha); ?>','boton1');" ><i class="fa-regular fa-circle-check prefix grey-text mr-1"></i> Aprobar</button></div><?php } ?><?php if ($registro->estatus>0 and $registro->estatus<>99) { ?><div align="center" ><button type="button" id="boton2<?php echo ($registro->id_credito); ?>" class="btn btn-warning waves-effect" onclick="reversar_credito('<?php echo encriptar($registro->id_credito); ?>','boton2<?php echo ($registro->id_credito); ?>');" ><i class="fas fa-history"></i> Reversar</button></div></td><td><div align="center" ><button type="button" id="boton<?php echo ($registro->id_credito); ?>" class="btn btn-outline-danger waves-effect" onclick="anular_credito('<?php echo encriptar($registro->id_credito); ?>','boton<?php echo ($registro->id_credito); ?>');" ><i class="fas fa-window-close"></i> Anular</button></div><?php } ?></td>
</tr>
 <?php 
 }
 ?>
 <tr>
<td colspan="10" class="PieTabla">Contraloria del Estado Bolivariano de Gu&aacute;rico</td>
</tr>
</table>