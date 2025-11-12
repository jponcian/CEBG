<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');
//-----------
if ($_GET['tipo'] == '1') {
	$filtro = " numero='" . ($_GET['valor']) . "' AND estatus=0 AND ";
} elseif ($_GET['tipo'] == '2') {
	$filtro = " concepto like '%" . ($_GET['valor']) . "%' AND estatus=0 AND ";
} elseif ($_GET['tipo'] == '3') {
	$filtro = " estatus=0 AND ";
} else {
	$filtro = "";
}
?>
<table class="table table-hover" width="100%" border="0" align="center">
	<tr>
		<td class="TituloTablaP" height="41" colspan="10" align="center">Ordenes de Compra Registradas</td>
	</tr>
	<tr>
		<td bgcolor="#CCCCCC" align="center"><strong>N:</strong></td>
		<td bgcolor="#CCCCCC" align="left"><strong>Rif</strong></td>
		<td bgcolor="#CCCCCC" align="left"><strong>Contribuyente</strong></td>
		<td bgcolor="#CCCCCC" align="left"><strong>Fecha:</strong></td>
		<td bgcolor="#CCCCCC" align="left"><strong>Concepto:</strong></td>
		<td bgcolor="#CCCCCC" align="right"><strong>Total:</strong></td>
		<td bgcolor="#CCCCCC" align="center"></td>
		<td bgcolor="#CCCCCC" align="center"></td>
	</tr>
	<?php
	//------ MONTAJE DE LOS DATOS
	$consultx = "SELECT id_solicitud, tipo_orden, orden.estatus, orden.id, id_contribuyente, orden.rif, fecha, numero, concepto, sum(total) as total1, contribuyente.nombre FROM orden, contribuyente WHERE $filtro orden.id_contribuyente = contribuyente.id AND tipo_orden<>'M' GROUP BY id_presupuesto, id_contribuyente, numero ORDER BY fecha DESC, orden.id DESC;";
	//echo $consultx;
	$tablx = $_SESSION['conexionsql']->query($consultx);

	while ($registro = $tablx->fetch_object()) {
		$i++;
	?>
		<tr>
			<td>
				<div align="center"><?php echo ($i); ?></div>
			</td>
			<td>
				<div align="left"><?php echo ($registro->rif); ?></div>
			</td>
			<td>
				<div align="left"><strong><?php echo ($registro->nombre); ?></strong></div>
			</td>
			<td>
				<div align="left"><?php echo voltea_fecha($registro->fecha); ?></div>
			</td>
			<td>
				<div align="left"><?php echo ($registro->concepto); ?></div>
			</td>
			<td>
				<div align="right"><strong><?php echo formato_moneda($registro->total1); ?></strong></div>
			</td>
			<td>
				<div align="center"><a data-toggle="tooltip" title="Preliminar"><button type="button" class="btn btn-outline-info waves-effect" onclick="imprimir('<?php echo encriptar($registro->id_contribuyente); ?>');"><i class="fas fa-print prefix grey-text mr-1"></i></button></a></div>
			</td>
			<td>
				<div align="center"><button onclick="asignar_numero('<?php echo encriptar($registro->id_contribuyente); ?>');" data-toggle="modal" data-target="#modal_normal" type="button" id="boton<?php echo ($registro->id_contribuyente); ?>" class="btn btn-outline-success waves-effect"><i class="fa-regular fa-circle-check prefix grey-text mr-1"></i> Aprobar Orden</button></div>
			</td>
		</tr>
	<?php
	}
	?>
	<tr>
		<td colspan="10" class="PieTabla">Contraloria del Estado Bolivariano de Gu&aacute;rico</td>
	</tr>
</table>