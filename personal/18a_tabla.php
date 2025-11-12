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
    case 1:
        $filtrar = " nomina LIKE '%$dato_buscar%' AND estatus>0 AND estatus<99 ORDER BY tipo_pago, nomina, desde, hasta";
        break;
    case 2:
        $filtrar = " descripcion LIKE '%$dato_buscar%' AND estatus>0 AND estatus<99 ORDER BY tipo_pago, nomina, desde, hasta";
        break;
    case 3:
        $filtrar = " desde >= '$fecha1' AND hasta <= '$fecha2' AND estatus>0 AND estatus<>99 ORDER BY tipo_pago, nomina, desde, hasta";
        break;
    case 4:
        $filtrar = " estatus>0 AND estatus<>99 ORDER BY tipo_pago, nomina, desde, hasta";
        break;
}?>
<table class="table table-hover" width="100%" border="0" align="center">
<tr>
<td class="TituloTablaP" height="41" colspan="9" align="center">Nominas de Pago en Sistema</td>
</tr>
<!--<tr>
<td colspan="8" align="center"><button type="button" id="botonb" class="btn btn-lg btn-block btn-info" onClick="rep();"><i class="fas fa-search mr-2"></i>Ver Pdf</button></td>
</tr>-->
<tr>
<td bgcolor="#CCCCCC" align="center"><strong>N&deg; N&oacute;mina</strong></td>
<td width="250" bgcolor="#CCCCCC" align="left"><strong>N&oacute;mina:</strong></td>
<td  bgcolor="#CCCCCC" align="left"><strong>Descripci&oacute;n:</strong></td>
<td  bgcolor="#CCCCCC" align="center" ><strong>Periodo:</strong></td>
<td  bgcolor="#CCCCCC" align="center"><strong>Asignaciones:</strong></td>
<td bgcolor="#CCCCCC" align="center"><strong>Deducciones:</strong></td>
<td bgcolor="#CCCCCC" align="center"><strong>Total:</strong></td>
<td bgcolor="#CCCCCC" align="center"></td>
<td bgcolor="#CCCCCC" align="center"></td>
</tr>
<?php 	
//------ MONTAJE DE LOS DATOS
$consultx = "SELECT * FROM nomina_solicitudes WHERE estatus=5 AND $filtrar;";
//echo $consultx;
$_SESSION['consulta'] = $consultx;
$tablx = $_SESSION['conexionsql']->query($consultx);
while ($registro = $tablx->fetch_object())
	{
	$i++;
	?>
<tr >
<td><div align="center" ><?php echo ($i); ?></div></td>
<td ><div align="left" ><?php echo ($registro->nomina); ?></div></td>
<td ><div align="left" ><?php echo ($registro->descripcion); ?></div></td>
<td ><div align="center" ><?php echo voltea_fecha($registro->desde).' al '.voltea_fecha($registro->hasta); ?></div></td>
<td ><div align="right" ><?php echo formato_moneda($registro->asignaciones); ?></div></td>
<td ><div align="right" ><?php echo formato_moneda($registro->descuentos); ?></div></td>
<td ><div align="right" ><?php echo formato_moneda($registro->total); ?></div></td>
<td ><div align="center" ><a data-toggle="tooltip" title="Reversar Solicitud de Pago"><button type="button" class="btn btn-outline-danger waves-effect" onclick="anular('<?php echo encriptar($registro->id); ?>');" ><i class="fas fa-history prefix grey-text mr-1"></i></button></a></div></td>
<td ><div align="center" ><button type="button" class="btn btn-outline-info blue light-3 btn-sm" onclick="imprimir_sol('<?php echo encriptar($registro->id); ?>', '<?php echo ($registro->tipo_pago); ?>');"><i class="fas fa-print"></i></button></div></td>
</tr>
 <?php 
 }
 ?>
 <tr>
<td colspan="10" class="PieTabla">Alcaldia del Municipio Francisco de Miranda</td>
</tr>
</table>