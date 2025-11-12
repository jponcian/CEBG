<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');
//-----------
?>
<table class="table table-hover" width="100%" border="0" align="center">
	<tr>
		<td class="TituloTablaP" height="41" colspan="10" align="center">Carga Familiar Registrada</td>
	</tr>
	<tr>
		<td bgcolor="#CCCCCC" align="center"><strong>N:</strong></td>
		<td bgcolor="#CCCCCC" align="center"><strong>Cedula:</strong></td>
		<td bgcolor="#CCCCCC" align="center"><strong>Nombres:</strong></td>
		<td bgcolor="#CCCCCC" align="center"><strong>Fecha Nacimiento:</strong></td>
		<td bgcolor="#CCCCCC" align="center"><strong>Edad:</strong></td>
		<td bgcolor="#CCCCCC" align="center"><strong>Sexo:</strong></td>
		<td bgcolor="#CCCCCC" align="center"><strong>Parentesco:</strong></td>
		<td bgcolor="#CCCCCC" align="center"><strong>Opcion</strong></td>
	</tr>
	<?php
	//------ MONTAJE DE LOS DATOS
	$consultx = "SELECT * FROM rac_carga WHERE rac_rep='" . $_GET['id'] . "' ORDER BY fecha_nac;";
	//echo $consultx;
	$tablx = $_SESSION['conexionsql']->query($consultx);

	while ($registro = $tablx->fetch_object()) {
		$i++;
	?>
		<tr id="fila<?php echo $registro->id; ?>">
			<td>
				<div align="center"><?php echo ($i); ?></div>
			</td>
			<td>
				<div align="center"><?php echo ($registro->cedula); ?></div>
			</td>
			<td>
				<div align="center"><?php echo ($registro->nombres); ?></div>
			</td>
			<td>
				<div align="center"><?php echo voltea_fecha($registro->fecha_nac); ?></div>
			</td>
			<td>
				<div align="center"><?php echo edad($registro->fecha_nac); ?></div>
			</td>

			<td>
				<div align="center">
					<?php
					$genero = '';
					if ($registro->sexo == 'M') $genero = 'Masculino';
					elseif ($registro->sexo == 'F') $genero = 'Femenino';
					echo $genero;
					?>
				</div>
			</td>
			<td>
				<div align="left"><?php echo ($registro->parentesco); ?></div>
			</td>
			<td>
				<div align="center"><a data-toggle="tooltip" title="Eliminar"><button type="button" class="btn btn-outline-danger btn-sm" onclick="eliminar_hijo('<?php echo ($registro->id); ?>','<?php echo ($_GET['id']); ?>','<?php echo encriptar($registro->parentesco); ?>');"><i class="fas fa-trash-alt"></i></button></a></div>
			</td>
		</tr>
	<?php
	}
	?>
	<tr>
		<td colspan="10" class="PieTabla">Contraloria del Estado Bolivariano de Gu&aacute;rico</td>
	</tr>
</table>