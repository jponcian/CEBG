<?php
session_start();
include_once "../conexion.php";
include_once "../funciones/auxiliar_php.php";
//-----------
?>
<table class="table table-striped table-hover" bgcolor="#FFFFFF" width="100%" border="0" align="center">
	<thead>
		<tr>
			<td class="TituloTablaP" height="41" colspan="10" align="center">FUNCIONARIOS AÚN DENTRO DE LA INSTITUCIÓN</td>
		</tr>
		<tr>
			<td bgcolor="#CCCCCC" align="left"><strong>N:</strong></td>
			<td bgcolor="#CCCCCC" align="left"><strong>Cedula</strong></td>
			<td bgcolor="#CCCCCC" align="left"><strong>Funcionario</strong></td>
			<td bgcolor="#CCCCCC" align="left"><strong>Direccion</strong></td>
			<td bgcolor="#CCCCCC" align="left"><strong>Hora Ingreso</strong></td>
		</tr>
	</thead>
	<?php
	//------ MONTAJE DE LOS DATOS
	$consultx = "SELECT asistencia_diaria.id, asistencia_diaria.cedula, asistencia_diaria.direccion, asistencia_diaria.tipo, asistencia_diaria.fecha, asistencia_diaria.hora, asistencia_diaria.horario, asistencia_diaria.observacion, asistencia_diaria.estatus, CONCAT(rac.nombre,' ',rac.nombre2,' ',rac.apellido,' ',rac.apellido2) as  nombre FROM asistencia_diaria, rac WHERE	 asistencia_diaria.cedula = rac.cedula AND asistencia_diaria.fecha = '" . date('Y/m/d') . "' AND asistencia_diaria.tipo = 'ENTRADA' AND asistencia_diaria.salio = '0' ORDER BY asistencia_diaria.hora DESC;";
	//echo $consultx;
	$_SESSION['consulta'] = $consultx;
	$tablx = $_SESSION['conexionsql']->query($consultx);

	while ($registro = $tablx->fetch_object()) {
		$i++;
	?>
		<tr>
			<td>
				<div align="center"><?php echo ($i); ?></div><?php if ($registro->observacion <> '') { ?><a data-toggle="tooltip" title="<?php echo ($registro->observacion); ?>">
						<div class="spinner-grow spinner-grow-sm" role="status"></div>
					</a> <?php } ?>
			</td>
			<td>
				<div align="left"><?php echo ($registro->cedula); ?></div>
			</td>
			<td>
				<div align="left" <?php if ($registro->observacion <> '') { ?> onClick="ver_observacion('<?php echo ($registro->observacion); ?>');" <?php } ?>><strong><?php echo ($registro->nombre); ?></strong></div>
			</td>
			<td>
				<div align="left"><?php echo ($registro->direccion); ?></div>
			</td>
			<td>
				<div align="left"><?php echo hora_militar($registro->hora); ?></div>
			</td>
			</div>
			</td>
		</tr>
	<?php
	}
	?>
	<tr>
		<td colspan="10" class="PieTabla">Contraloria del Estado Bolivariano de Gu&aacute;rico</td>
	</tr>
</table>