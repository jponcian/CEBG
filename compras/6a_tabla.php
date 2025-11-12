<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');
//-----------
$dato_buscar = trim($_GET['valor']);
$filtro = $_GET['tipo'];

switch ($filtro) {
    case 1:
        $filtrar = " AND numero = '$dato_buscar'";
        break;
    case 2:
        $filtrar = " AND concepto LIKE '%$dato_buscar%'";
        break;
    case 3:
        $filtrar = " AND contribuyente.nombre LIKE '%$dato_buscar%'";
        break;
    case 4:
        $filtrar = "";
        break;
}?>
<table class="table table-hover" width="100%" border="0" align="center">
<tr>
<td class="TituloTablaP" height="41" colspan="10" align="center">Orden de Pago en Sistema</td>
</tr>
<tr>
<td  bgcolor="#CCCCCC" align="center"><strong>N</strong></td>
<td  bgcolor="#CCCCCC" align="left"><strong>Rif</strong></td>
<td  bgcolor="#CCCCCC" align="left"><strong>Contribuyente</strong></td>
<td  bgcolor="#CCCCCC" align="left"><strong>Fecha</strong></td>
<td bgcolor="#CCCCCC" align="left"><strong>Numero</strong></td>
<td bgcolor="#CCCCCC" align="left"><strong>Concepto</strong></td>
<td bgcolor="#CCCCCC" align="right"><strong>Total</strong></td>
<td bgcolor="#CCCCCC" align="center"><strong>Reversar</strong></td>
<td bgcolor="#CCCCCC" align="center"></td>
</tr>
<?php 	
//------ MONTAJE DE LOS DATOS
$consultx = "SELECT id_solicitud, tipo_orden, orden.estatus, orden.id, id_contribuyente, orden.rif, fecha, numero, concepto, sum(total) as total1, contribuyente.nombre FROM orden, contribuyente WHERE estatus=5 AND orden.id_contribuyente = contribuyente.id $filtrar GROUP BY numero, id_contribuyente ORDER BY fecha DESC, orden.id DESC;"; 
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
<td ><div align="left" ><strong><?php echo rellena_cero($registro->numero,8); ?></strong></div></td>
<td ><div align="left" ><?php echo ($registro->concepto); ?></div></td>
<td ><div align="right" ><?php echo formato_moneda($registro->total1); ?></div></td>
<td ><div align="center" ><a data-toggle="tooltip" title="Reversar Orden de Pago"><button type="button" class="btn btn-outline-danger waves-effect" onclick="anular('<?php echo encriptar($registro->id); ?>','<?php echo encriptar($registro->id_solicitud); ?>');" ><i class="fas fa-history prefix grey-text mr-1"></i></button></a></div></td>
<td ><div align="center" ><a data-toggle="tooltip" title="Ver Orden"><button type="button" class="btn btn-outline-primary waves-effect" onclick="imprimir('<?php echo encriptar($registro->id_solicitud); ?>','<?php echo ($registro->tipo_orden); ?>');" ><i class="fas fa-print prefix grey-text mr-1"></i></button></a></div></td>
</tr>
 <?php 
 }
 ?>
 <tr>
<td colspan="10" class="PieTabla">Contraloria del Estado Bolivariano de Gu&aacute;rico</td>
</tr>
</table>