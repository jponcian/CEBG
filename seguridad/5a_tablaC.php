<?php
session_start();
include_once "../conexion.php";
include_once "../funciones/auxiliar_php.php";
//-----------
$buscar = trim($_GET['buscar']);
if ($buscar <> '') {
	$filtro = " AND (asistencia_diaria.cedula like '%$buscar%' OR asistencia_diaria.direccion like '%$buscar%' OR asistencia_diaria.tipo like '%$buscar%' OR rac.nombre like '%$buscar%') ";	//$_SESSION['titulo'] = 'POR VERIFICAR';
}
?>
<style>
	.table-responsive {
		width: 100%;
		overflow-x: auto;
	}

	.table {
		font-size: 13px;
	}

	.table th,
	.table td {
		padding: 3px 6px;
		vertical-align: middle;
	}

	@media (max-width: 600px) {

		.table th,
		.table td {
			padding: 2px 3px;
			font-size: 11px;
			white-space: nowrap;
		}

		.TituloTablaP {
			font-size: 14px;
		}

		.PieTabla {
			font-size: 11px;
		}
	}
</style>
<div class="table-responsive">
	<table class="table table-striped table-hover table-condensed" style="background:#fff;">
		<thead>
			<tr>
				<td class="TituloTablaP" height="41" colspan="7" align="center">ASISTENCIA DIARIA</td>
			</tr>
			<tr>
				<td><strong>Cedula</strong></td>
				<td><strong>Funcionario</strong></td>
				<td><strong>Hora</strong></td>
				<td align="center"><strong></strong></td>
				<td align="center"><strong>Estatus</strong></td>
			</tr>
		</thead>
		<?php
		//------ MONTAJE DE LOS DATOS
		$consultx = "SELECT asistencia_diaria.id, asistencia_diaria.cedula, asistencia_diaria.direccion, asistencia_diaria.tipo, asistencia_diaria.fecha, asistencia_diaria.hora, asistencia_diaria.observacion, asistencia_diaria.estatus, CONCAT(rac.nombre,' ',rac.nombre2,' ',rac.apellido,' ',rac.apellido2) as  nombre FROM asistencia_diaria, rac WHERE	asistencia_diaria.estatus<>5 AND asistencia_diaria.cedula = rac.cedula AND fecha='" . date('Y/m/d') . "' $filtro ORDER BY fecha DESC, hora DESC;";
		//echo $consultx;
		$_SESSION['consulta'] = $consultx;
		$tablx = $_SESSION['conexionsql']->query($consultx);

		while ($registro = $tablx->fetch_object()) {
		?>
			<tr>
				<td>
					<div align="left"><?php echo ($registro->cedula); ?></div>
				</td>
				<td>
					<div align="left" <?php if ($registro->observacion <> '') { ?> onClick="ver_observacion('<?php echo ($registro->observacion); ?>');" <?php } ?>><strong><?php echo oraciones($registro->nombre); ?></strong></div>
				</td>

				<td>
					<div align="left" style="color: <?php echo ($registro->tipo == 'ENTRADA') ? '#28a745' : '#dc3545'; ?>">
						<?php echo hora_militar($registro->hora); ?>
					</div>
				</td>

				<td>
					<div align="center"><a data-toggle="tooltip" title="Eliminar"><button type="button" class="btn btn-outline-danger btn-sm" onclick="borrar('<?php echo encriptar($registro->id); ?>');"><i class="fas fa-trash-alt prefix fa-xs grey-text mr-1"></i></button></a></div>
				</td>

				<td align="center">
					<div>
						<h5><button <?php if ($registro->estatus > 0) { ?> data-toggle="modal" data-target="#modal_normal" onclick="motivo('<?php echo ($registro->id); ?>')" <?php } ?> type="button" class="badge badge-<?php if ($registro->estatus == '0') {
																																																								echo 'success';
																																																							} else {
																																																								echo 'danger';
																																																							} ?>"><i class="<?php if ($registro->estatus == '0') {
																																																												echo 'fa-regular fa-thumbs-up';
																																																											} else {
																																																												echo 'fa-solid fa-triangle-exclamation';
																																																											} ?>"></i> <?php echo $_SESSION['asistencia'][$registro->estatus] ?></button></h5>
					</div>
				</td>

			</tr>
		<?php
		}
		?>
		<tr>
			<td colspan="7" class="PieTabla">Contraloría del Estado Bolivariano de Gu&aacute;rico</td>
		</tr>
	</table>
</div>