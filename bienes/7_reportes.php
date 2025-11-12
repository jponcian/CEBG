<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');
if ($_SESSION['VERIFICADO'] != "SI") {
	header("Location: ../validacion.php?opcion=val");
	exit();
}
$acceso = 92;
//------- VALIDACION ACCESO USUARIO
include_once "../validacion_usuario.php";
//-----------------------------------
?>

<form id="form1" name="form1" method="post">
	<div align="center" class="TituloP">Incorporaciones de Bienes</div>
	<br>
	<div class="form-group">
		<div class="input-group col-sm-12">
			<div class="input-group-prepend">
				<div class="input-group-text">
					<i class="fa-solid fa-building-circle-arrow-right"></i>
				</div>
			</div>
			<select class="select2" name="txt_division" id="txt_division">
				<option value="0">Todos</option>
				<?php
				$consultx = "SELECT bn_dependencias.* FROM bn_dependencias, bn_bienes WHERE bn_bienes.id_dependencia=bn_dependencias.id GROUP BY bn_dependencias.id ORDER BY division;";
				$tablx = $_SESSION['conexionsql']->query($consultx);
				while ($registro_x = $tablx->fetch_array()) {
					echo '<option value=' . encriptar($registro_x['id']) . '>' . $registro_x['division'] . '</option>';
				}
				?>
			</select>
		</div>
	</div>

	<div class="form-group">
		<div class="input-group col-sm-12">
			<div class="input-group-prepend">
				<div class="input-group-text">
					<i class="fa-regular fa-calendar-check"></i>
				</div>
			</div>
			<a data-toggle="tooltip" title="Periodo de los Movimientos">
				<div id="fecha" class="d-flex">
					<input class="form-control" type="text" name="OFECHA" id="OFECHA" size="15" placeholder="Desde" value="<?php echo date('d/m/Y'); ?>" style="text-align:center; border-top-right-radius: 0; border-bottom-right-radius: 0;" />
					<input class="form-control" type="text" name="OFECHA2" id="OFECHA2" size="15" placeholder="Hasta" value="<?php echo date('d/m/Y'); ?>" style="text-align:center; border-top-left-radius: 0; border-bottom-left-radius: 0; margin-left: -1px;" />
				</div>
			</a>
		</div>
	</div>

	<div class="form-group col-sm-5">
		<div class="input-group">
			<button type="button" id="botonb" class="btn btn-danger" onClick="reportes2();">
				<i class="fas fa-file-pdf mr-2"></i> Ver Reporte
			</button>
			<style>
				#botonb {
					transition: box-shadow 0.2s, transform 0.2s, background 0.2s;
				}

				#botonb:hover {
					background: #c82333;
					color: #fff;
					box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, .25), 0 4px 16px rgba(0, 0, 0, 0.12);
					transform: scale(1.07);
				}
			</style>
		</div>
	</div>
</form>

<script language="JavaScript">
	// PARA EL SELECT2
	$(document).ready(function() {
		$('.select2').select2();
	});
	//--------------------------------
	$('#OFECHA').dateRangePicker({
		//  startDate: moment().format("DD-MM-YYYY"),
		autoClose: true,
		format: 'DD-MM-YYYY',
		language: 'es',
		extraClass: 'date-range-picker19',
		separator: ' al ',
		getValue: function() {
			if ($('#OFECHA').val() && $('#OFECHA2').val())
				return $('#OFECHA').val() + ' al ' + $('#OFECHA2').val();
			else
				return '';
		},
		setValue: function(s, s1, s2) {
			$('#OFECHA').val(s1);
			$('#OFECHA2').val(s2);
		}
	});
	//---------------------------
	function reportes2() {
		window.open("bienes/reporte/adquisicion.php?fecha1=" + document.form1.OFECHA.value + "&fecha2=" + document.form1.OFECHA2.value + "&division=" + document.form1.txt_division.value, "_blank");
	}
</script>