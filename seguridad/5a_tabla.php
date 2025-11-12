<?php
session_start();
include_once "../conexion.php";
include_once "../funciones/auxiliar_php.php";
//-----------
$buscar = trim($_GET['buscar']);
if ($buscar <> '') {
	$filtro = " AND (asistencia_diaria.cedula like '%$buscar%' OR asistencia_diaria.direccion like '%$buscar%' OR asistencia_diaria.tipo like '%$buscar%' OR rac.nombre like '%$buscar%') ";
}
?>
<table class="table table-striped table-hover table-condensed" bgcolor="#FFFFFF" width="100%" border="0" align="center">
	<thead>
		<tr>
			<td class="TituloTablaP" height="41" colspan="10" align="center">ASISTENCIA DIARIA</td>
		</tr>
		<tr>
			<td bgcolor="#CCCCCC" align="left"><strong>N:</strong></td>
			<td bgcolor="#CCCCCC" align="left"><strong>Cedula</strong></td>
			<td bgcolor="#CCCCCC" align="left"><strong>Funcionario</strong></td>
			<td bgcolor="#CCCCCC" align="left"><strong>Direccion</strong></td>
			<td bgcolor="#CCCCCC" align="left"><strong>Hora</strong></td>
			<td bgcolor="#CCCCCC" align="center"><strong>Tipo</strong></td>
			<td bgcolor="#CCCCCC" align="center"><strong>Horario</strong></td>
			<td bgcolor="#CCCCCC" align="center"><strong></strong></td>
			<td bgcolor="#CCCCCC" align="center"><strong>Estatus</strong></td>
		</tr>
	</thead>
	<tbody>
		<?php
		$consultx = "SELECT asistencia_diaria.id, asistencia_diaria.cedula, asistencia_diaria.direccion, asistencia_diaria.tipo, asistencia_diaria.fecha, asistencia_diaria.hora, asistencia_diaria.horario, asistencia_diaria.observacion, asistencia_diaria.estatus, CONCAT(rac.nombre,' ',rac.nombre2,' ',rac.apellido,' ',rac.apellido2) as nombre FROM asistencia_diaria, rac WHERE asistencia_diaria.estatus<>5 AND asistencia_diaria.cedula = rac.cedula AND fecha='" . date('Y/m/d') . "' $filtro ORDER BY fecha DESC, hora DESC;";
		$_SESSION['consulta'] = $consultx;
		$tablx = $_SESSION['conexionsql']->query($consultx);
		$i = 0;
		while ($registro = $tablx->fetch_object()) {
			$i++;
		?>
			<tr>
				<td>
					<div align="center"><?php echo ($i); ?></div>
					<?php if ($registro->observacion <> '') { ?>
						<a data-toggle="tooltip" title="<?php echo ($registro->observacion); ?>">
							<div class="spinner-grow spinner-grow-sm" role="status"></div>
						</a>
					<?php } ?>
				</td>
				<td>
					<div align="left"><?php echo ($registro->cedula); ?></div>
				</td>
				<td>
					<div align="left" <?php if ($registro->observacion <> '') { ?> onClick="ver_observacion('<?php echo ($registro->observacion); ?>');" <?php } ?>>
						<strong><?php echo oraciones($registro->nombre); ?></strong>
					</div>
				</td>
				<td>
					<div align="left"><?php echo oraciones($registro->direccion); ?></div>
				</td>
				<td>
					<div align="left" style="color: <?php echo ($registro->tipo == 'ENTRADA') ? '#28a745' : '#dc3545'; ?>">
						<?php echo hora_militar($registro->hora); ?>
					</div>
				</td>
				<td align="center">
					<div>
						<h6 <?php if ($registro->tipo == 'ENTRADA') { ?> onClick="ingresob('<?php echo ($registro->id); ?>');" <?php } else { ?> onClick="salidab('<?php echo ($registro->id); ?>');" <?php } ?>>
							<i class="<?php echo ($registro->tipo == 'ENTRADA') ? 'fa-solid fa-person-arrow-down-to-line' : 'fa-solid fa-person-arrow-up-from-line'; ?>"></i>
							<?php echo ($registro->tipo == 'ENTRADA') ? 'ENTRADA' : 'SALIDA'; ?>
						</h6>
					</div>
				</td>
				<td>
					<div align="left"><?php echo hora_militar($registro->horario); ?></div>
				</td>
				<td>
					<div align="center">
						<a data-toggle="tooltip" title="Eliminar">
							<button type="button" class="btn btn-outline-danger btn-sm" onclick="borrar('<?php echo encriptar($registro->id); ?>');">
								<i class="fa-solid fa-trash prefix fa-xs grey-text mr-1"></i>
							</button>
						</a>
					</div>
				</td>
				<td align="center">
					<div>
						<h5>
							<button
								<?php if ($registro->estatus > 0) { ?>
								data-toggle="modal" data-target="#modal_normal" onclick="motivo('<?php echo ($registro->id); ?>')"
								<?php } ?>
								type="button"
								class="badge badge-<?php echo ($registro->estatus == '0') ? 'success' : 'danger'; ?>">
								<i class="<?php echo ($registro->estatus == '0') ? 'fa-regular fa-thumbs-up' : 'fa-solid fa-triangle-exclamation'; ?>"></i>
								<?php echo $_SESSION['asistencia'][$registro->estatus] ?>
							</button>
						</h5>
					</div>
				</td>
			</tr>
		<?php
		}
		?>
		<tr>
			<td colspan="10" class="PieTabla">Contraloria del Estado Bolivariano de Guárico</td>
		</tr>
	</tbody>
</table>