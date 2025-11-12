<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');
//-----------
if ($_GET['nomina'] <> '') {
	$nomina = " AND nomina.nomina LIKE '" . (($_GET['nomina'])) . "'";
} else {
	$nomina = "";
}
if ($_GET['tipo'] <> '') {
	$tipo = " AND nomina.descripcion LIKE '" . (($_GET['tipo'])) . "'";
} else {
	$tipo = "";
}
if ($_GET['periodo'] <> '') {
	$periodo = " AND nomina.hasta = '" . (($_GET['periodo'])) . "'";
} else {
	$periodo = "";
}
?>
<table class="table table-hover" width="100%" border="0" align="center">
	<tr>
		<td class="TituloTablaP" height="41" colspan="13" align="center">Nominas Generadas</td>
	</tr>
	<tr>
		<td bgcolor="#CCCCCC" align="center"><strong>N:</strong></td>
		<td bgcolor="#CCCCCC" align="center"><strong>Cedula:</strong></td>
		<td bgcolor="#CCCCCC" align="left"><strong>Empleado:</strong></td>
		<!--<td  bgcolor="#CCCCCC" align="left"><strong>Cargo:</strong></td>-->
		<!--<td  bgcolor="#CCCCCC" align="center"><strong>Periodo:</strong></td>-->
		<td colspan="" bgcolor="#CCCCCC" align="center"><strong>Sueldo:</strong></td>
		<td colspan="" bgcolor="#CCCCCC" align="center"><strong>Prima Ant.:</strong></td>
		<td colspan="" bgcolor="#CCCCCC" align="center"><strong>Prima Hijos:</strong></td>
		<td colspan="" bgcolor="#CCCCCC" align="center"><strong>Prima Prof.:</strong></td>
		<td colspan="" bgcolor="#CCCCCC" align="center"><strong>Bono:</strong></td>
		<td colspan="" bgcolor="#CCCCCC" align="center"><strong>Dias Adic.:</strong></td>
		<td colspan="" bgcolor="#CCCCCC" align="center"><strong>Dif.:</strong></td>
		<td colspan="" bgcolor="#CCCCCC" align="center"><strong><?php if ($_GET['tipo'] == 'PAGO_DE_QUINCENA') {
																	echo 'Quincena:';
																} elseif ($_GET['tipo'] == 'PAGO_DE_CESTATICKETS') {
																	echo 'CestaTickets:';
																} elseif ($_GET['tipo'] == 'PAGO_DE_VACACIONES') {
																	echo 'Total Mensual (30 dias):';
																} ?></strong></td>
		<td colspan="" bgcolor="#CCCCCC" align="center"><strong>Total:</strong></td>
		<td colspan="" bgcolor="#CCCCCC" align="center"><strong>Modificar:</strong></td>
	</tr>
	<?php
	//------ MONTAJE DE LOS DATOS
	$consultx = "SELECT nomina.*, CONCAT(rac.nombre,' ',rac.nombre2,' ',rac.apellido,' ',rac.apellido2) as  nombre FROM nomina, rac WHERE nomina.cedula= rac.cedula AND tipo_pago <> '008' $nomina $tipo $periodo ORDER BY nomina.cedula;";
	//echo $consultx;
	$tablx = $_SESSION['conexionsql']->query($consultx);

	while ($registro = $tablx->fetch_object()) {
		$i++;
		$tickets = formato_moneda($registro->sueldo);
		$sueldo = formato_moneda($registro->sueldo_mensual);
		$prof = formato_moneda($registro->prof);
		$hijos = formato_moneda($registro->hijos);
		$antiguedad = formato_moneda($registro->antiguedad);
		$dias = formato_moneda($registro->dias);
		$diferencia = formato_moneda($registro->diferencia);
		$bono = formato_moneda($registro->bono);
	?>
		<tr>
			<td>
				<div align="center"><?php echo ($i); ?></div>
			</td>
			<td>
				<div align="center"><?php echo ($registro->cedula); ?></div>
			</td>
			<td>
				<div align="left"><strong><?php echo ($registro->nombre); ?></strong></div>
			</td>
			<!--<td ><div align="left" ><?php //echo ($registro->cargo); 
										?></div></td>-->
			<!--<td ><div align="center" ><?php //echo voltea_fecha($registro->desde).' al '.voltea_fecha($registro->hasta); 
											?></div></td>-->
			<td>
				<div align="right">
					<?php echo formato_moneda($registro->sueldo_mensual);	?>
				</div>
			</td>
			<td>
				<div align="right">
					<?php echo formato_moneda($registro->antiguedad);	?>
				</div>
			</td>
			<td>
				<div align="right">
					<?php echo formato_moneda($registro->hijos);	?>
				</div>
			</td>
			<td>
				<div align="right">
					<?php echo formato_moneda($registro->prof);	?>
				</div>
			</td>
			<td>
				<div align="right">
					<?php echo formato_moneda($registro->bono);	?>
				</div>
			</td>
			<td>
				<div align="right">
					<?php echo formato_moneda($registro->dias);		?>
				</div>
			</td>
			<td>
				<div align="right">
					<?php echo formato_moneda($registro->diferencia);		?>
				</div>
			</td>
			<td>
				<div align="right">
					<?php echo formato_moneda($registro->sueldo);		?>
				</div>
			</td>
			<td>
				<div align="right"><?php echo formato_moneda($registro->total); ?></div>
			</td>
			<td>
				<div align="center"><a title="Editar" data-toggle="modal" data-target="#modal_normal"><button type="button" class="btn btn-outline-success waves-effect" onclick="editar('<?php echo ($registro->id); ?>','<?php echo ($registro->tipo_pago); ?>','<?php echo ($sueldo); ?>','<?php echo ($prof); ?>','<?php echo ($hijos); ?>','<?php echo ($antiguedad); ?>','<?php echo ($dias); ?>','<?php echo ($tickets); ?>','<?php echo ($bono); ?>','<?php echo ($diferencia); ?>');"><i class="far fa-edit"></i></button></a></div>
			</td>
		</tr>
	<?php
	}
	?>
	<tr>
		<td colspan="13" class="PieTabla">Contraloria del Estado Bolivariano de Gu&aacute;rico</td>
	</tr>
</table>

<style>
	/* Puedes agregar esto en tu archivo CSS principal */
	.table-hover td,
	.table-hover th {
		padding-top: 4px;
		padding-bottom: 4px;
		font-size: 13px;
	}
</style>