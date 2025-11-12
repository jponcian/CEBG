<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');
//-----------
$dato_buscar = trim($_GET['valor']);
$filtro = $_GET['tipo'];
$nomina = $_GET['nomina'];
$fecha1 = voltea_fecha($_GET['fecha1']);
$fecha2 = voltea_fecha($_GET['fecha2']);
//---------
if ($nomina<>'0')
	{
	$filtrar_1 = " nomina='$nomina' AND ";
	}
//---------
switch ($filtro) {
    case 6:
		$mes = date('m');
		$anno = date('Y');
		$filtrar = " $filtrar_1 MONTH(desde) = $mes and YEAR(desde)=$anno ";
        $_SESSION['titulo'] = "POR FECHA (desde el ".voltea_fecha($fecha1)." al ".voltea_fecha($fecha2).")";
		break;
    case 3:
		$filtrar = " $filtrar_1 desde>='$fecha1' AND desde<='$fecha2'";
		$_SESSION['titulo'] = "TODAS";
        break;
    case 4:
        $filtrar = " $filtrar_1 1=1 ";
        break;
}?>
<table class="table table-hover" width="100%" border="0" align="center">
<tr>
<td class="TituloTablaP" height="41" colspan="11" align="center">Nominas Generadas en Sistema</td>
</tr>
<tr>
<td bgcolor="#CCCCCC" colspan="5" align="center"><strong>Retenciones Trabajador</strong></td>
<td bgcolor="#CCCCCC" colspan="5" align="center"><strong>Aporte Patronal</strong></td>
<td bgcolor="#CCCCCC"  align="center"><strong>Total</strong></td>
</tr>
<tr>
<td bgcolor="#CCCCCC" align="right">SSO</td>
<td bgcolor="#CCCCCC" align="right">FP</td>
<td bgcolor="#CCCCCC" align="right">FAOV</td>
<td bgcolor="#CCCCCC" align="right">FEJ</td>
<td bgcolor="#CCCCCC" align="right"><strong>Total Retenciones</strong></td>
<td bgcolor="#CCCCCC" align="right">SSO</td>
<td bgcolor="#CCCCCC" align="right">FP</td>
<td bgcolor="#CCCCCC" align="right">FAOV</td>
<td bgcolor="#CCCCCC" align="right">FEJ</td>
<td bgcolor="#CCCCCC" align="right"><strong>Total Aporte</strong></td>
<td bgcolor="#CCCCCC" align="center"><strong>General</strong></td>
</tr>
<?php 	
$consultax = "SELECT Sum(nomina.fejp) as fejp, Sum(nomina.fpp) as fpp, Sum(nomina.lphp) as lphp, Sum(nomina.ssop) as ssop, Sum(nomina.sso) as sso, Sum(nomina.fp) as fp, Sum(nomina.lph) as lph, Sum(nomina.fej) as fej, Sum(nomina.aporte) as aporte, Sum(nomina.descuentos) as descuentos, Sum(nomina.total) as total FROM nomina WHERE $filtrar ;"; //GROUP BY nomina.tipo_pago
//echo $consultax;
//------------
$_SESSION['consulta'] = $consultax;
$tablx = $_SESSION['conexionsql']->query($consultax);
while ($registro = $tablx->fetch_object())
	{
	$i++;
	?>
<tr >
<td ><div align="right" ><?php echo formato_moneda($registro->sso); ?></div></td>
<td ><div align="right" ><?php echo formato_moneda($registro->fp); ?></div></td>
<td ><div align="right" ><?php echo formato_moneda($registro->lph); ?></div></td>
<td ><div align="right" ><?php echo formato_moneda($registro->fej); ?></div></td>
<td ><div align="right" ><strong><?php echo formato_moneda($registro->sso+$registro->fp+$registro->lph+$registro->fej); ?></strong></div></td>
<td ><div align="right" ><?php echo formato_moneda($registro->ssop); ?></div></td>
<td ><div align="right" ><?php echo formato_moneda($registro->fpp); ?></div></td>
<td ><div align="right" ><?php echo formato_moneda($registro->lph); ?></div></td>
<td ><div align="right" ><?php echo formato_moneda($registro->fejp); ?></div></td>
<td ><div align="right" ><strong><?php echo formato_moneda($registro->ssop+$registro->fpp+$registro->lphp+$registro->fejp); ?></strong></div></td>
<td ><div align="right" ><strong><?php echo formato_moneda($registro->sso+$registro->fp+$registro->lph+$registro->fej+$registro->ssop+$registro->fpp+$registro->lphp+$registro->fejp); ?></strong></div></td>
</tr>
 <?php 
 }
 ?>
 <tr>
<td colspan="11" class="PieTabla">Contraloria del Estado Bolivariano de Gu&aacute;rico</td>
</tr>
</table>