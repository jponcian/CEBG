<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');
//-----------
//$dato_buscar = trim($_GET['valor']);
$tipo = $_GET['tipo'];
$valor = $_GET['valor'];
$desde = voltea_fecha($_GET['fecha1']);
$hasta = voltea_fecha($_GET['fecha2']);

$filtro = " (nomina_solicitudes.nomina LIKE '%$valor%' OR nomina_solicitudes.descripcion LIKE '%$valor%') AND (nomina_solicitudes.hasta >= '$desde' AND nomina_solicitudes.hasta <= '$hasta') AND ";

switch ($tipo) {
    case 2:
		$consultx = "SELECT *, id as indice FROM nomina_solicitudes WHERE $filtro LEFT(tipo_pago,1)<>'1' AND estatus=0 ORDER BY tipo_pago, nomina, desde;"; 
        break;
    case 3:
		$consultx = "SELECT *, id as indice FROM nomina_solicitudes WHERE $filtro LEFT(tipo_pago,1)<>'1' AND estatus=5 ORDER BY tipo_pago, nomina, desde;"; 
        break;
    case 4:
		$consultx = "SELECT *, id as indice FROM nomina_solicitudes WHERE $filtro LEFT(tipo_pago,1)<>'1' AND estatus>5 AND estatus<>99 ORDER BY tipo_pago, nomina, desde;"; 
        break;
    case 5:
		$consultx = "SELECT *, id as indice FROM nomina_solicitudes WHERE $filtro LEFT(tipo_pago,1)<>'1' AND estatus<>99 ORDER BY tipo_pago, nomina, desde;"; 
        break;
} 
//echo $consultx;
?>
<table class="table table-hover" width="100%" border="0" align="center">
<tr>
<td class="TituloTablaP" height="41" colspan="10" align="center">Nominas Generadas</td>
</tr>
<tr>
<td  bgcolor="#CCCCCC" align="center"><strong>N:</strong></td>
<td width="250" bgcolor="#CCCCCC" align="left"><strong>Nomina:</strong></td>
<td  bgcolor="#CCCCCC" align="left"><strong>Descripci&oacute;n:</strong></td>
<td  bgcolor="#CCCCCC" align="center" ><strong>Periodo:</strong></td>
<td  bgcolor="#CCCCCC" align="right"><strong>Asignaciones:</strong></td>
<td bgcolor="#CCCCCC" align="right"><strong>Deducciones:</strong></td>
<td bgcolor="#CCCCCC" align="right"><strong>Total:</strong></td>
<td bgcolor="#CCCCCC" align="right"></td>
<td bgcolor="#CCCCCC" align="right"></td>
<td bgcolor="#CCCCCC" align="right"></td>
</tr>
<?php 	
//------ MONTAJE DE LOS DATOS
//echo $consultx;
$tablx = $_SESSION['conexionsql']->query($consultx);

while ($registro = $tablx->fetch_object())
	{
	$i++;
	if ($registro->estatus==0) {	$estatus = 0;	}
		else {	$estatus = 1;	}
	?>
<tr >
<td><div align="center" ><?php echo ($i); ?></div></td>
<td ><div align="left" ><?php echo ($registro->nomina); ?></div></td>
<td ><div align="left" ><?php echo ($registro->descripcion); ?></div></td>
<td ><div align="center" ><?php echo voltea_fecha($registro->desde).' al '.voltea_fecha($registro->hasta); ?></div></td>
<td ><div align="right" ><?php echo formato_moneda($registro->asignaciones); ?></div></td>
<td ><div align="right" ><?php echo formato_moneda($registro->descuentos); ?></div></td>
<td ><div align="right" ><?php echo formato_moneda($registro->total); ?></div></td>
<td ><div align="center" ><button type="button" class="btn btn-outline-primary waves-effect" onclick="imprimir_sol('<?php echo encriptar($registro->id); ?>', '<?php echo ($registro->tipo_pago); ?>', '<?php echo ($estatus); ?>');" ><i class="fas fa-print prefix grey-text mr-1"></i></button></div></td>
<td ><div align="center" ><?php if ($estatus==0) { ?><button type="button" id="boton<?php echo ($registro->id); ?>" class="btn btn-outline-success waves-effect" onclick="generar_solicitud(<?php echo ($registro->id); ?>,boton<?php echo ($registro->id); ?>);" ><i class="fa-regular fa-circle-check prefix grey-text mr-1"></i> Aprobar y Enviar</button><?php } ?></div></td>
<td ><div align="center" ><a href="personal/5b_generar_txt_individual.php?id=<?php echo encriptar($registro->id); ?>" target="_blank" title="Archivo Txt Banco de Venezuela"><button type="button" class="btn btn-outline-danger waves-effect"><i class="fa-solid fa-v"></i></button></a></div></td>
</tr>
 <?php
 }
 ?>
 <tr>
<td colspan="10" class="PieTabla">Contraloria del Estado Bolivariano de Gu&aacute;rico</td>
</tr>
</table>