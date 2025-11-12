<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');

if ($_SESSION['VERIFICADO'] != "SI") {
	header("Location: ../validacion.php?opcion=val");
	exit();
}

$acceso = 15;
//------- VALIDACION ACCESO USUARIO
include_once "../validacion_usuario.php";
//-----------------------------------

// Obtener todas las nóminas distintas
$nominas = [];
$nomina_query = $_SESSION['conexionsql']->query("SELECT DISTINCT nomina FROM rac WHERE nomina<>'EGRESADOS' AND temporal=0 ORDER BY nomina;");
while ($row = $nomina_query->fetch_object()) {
	$nominas[] = $row->nomina;
}

// Obtener todos los parentescos distintos
$parentescos = [];
$parentesco_query = $_SESSION['conexionsql']->query("SELECT DISTINCT parentesco FROM rac_carga ORDER BY parentesco;");
while ($row = $parentesco_query->fetch_object()) {
	if ($row->parentesco != "") {
		$parentescos[] = $row->parentesco;
	}
}

// Obtener todos los sexos distintos
$sexos = [];
$sexo_query = $_SESSION['conexionsql']->query("SELECT DISTINCT sexo FROM rac_carga ORDER BY sexo;");
while ($row = $sexo_query->fetch_object()) {
	if ($row->sexo != "") {
		$sexos[] = $row->sexo;
	}
}
?>
<form id="form1" name="form1" method="post" onsubmit="return evitar();"><br>
	<div class="row mb-3">
		<div class="col text-left">
			<button type="button" id="botonb" class="btn btn-outline-success btn-rounded btn-sm font-weight-bold mr-2" onClick="rep();">
				<i class="fas fa-file-code"></i> Ver HTML
			</button>
			<button type="button" id="botonb2" class="btn btn-outline-success btn-rounded btn-sm font-weight-bold" onClick="reph();">
				<i class="fas fa-file-pdf"></i> Ver PDF
			</button>
		</div>
	</div>

	<div class="form-group row justify-content-center">
		<div class="col-auto">
			<label for="filtroNomina" class="font-weight-bold mr-2">Nómina:</label>
			<select id="filtroNomina" class="form-control form-control-sm d-inline-block" style="width:auto;">
				<option value="">Todas</option>
				<?php foreach ($nominas as $nomina): ?>
					<option value="<?php echo htmlspecialchars($nomina); ?>"><?php echo htmlspecialchars($nomina); ?></option>
				<?php endforeach; ?>
			</select>
		</div>
		<div class="col-auto">
			<label for="filtroSexo" class="font-weight-bold mr-2">Sexo:</label>
			<select id="filtroSexo" class="form-control form-control-sm d-inline-block" style="width:auto;">
				<option value="">Todos</option>
				<?php foreach ($sexos as $sexo): ?>
					<?php
					$genero = '';
					if ($sexo == 'M') $genero = 'Masculino';
					elseif ($sexo == 'F') $genero = 'Femenino';
					else $genero = $sexo;
					?>
					<option value="<?php echo htmlspecialchars($sexo); ?>"><?php echo htmlspecialchars($genero); ?></option>
				<?php endforeach; ?>
			</select>
		</div>
		<div class="col-auto">
			<label for="filtroEdadMin" class="font-weight-bold mr-2">Edad mínima:</label>
			<input type="number" id="filtroEdadMin" class="form-control form-control-sm d-inline-block" style="width:80px;" min="0">
		</div>
		<div class="col-auto">
			<label for="filtroEdadMax" class="font-weight-bold mr-2">Edad máxima:</label>
			<input type="number" id="filtroEdadMax" class="form-control form-control-sm d-inline-block" style="width:80px;" min="0">
		</div>
		<div class="col-auto">
			<label for="filtroParentesco" class="font-weight-bold mr-2">Parentesco:</label>
			<select id="filtroParentesco" class="form-control form-control-sm d-inline-block" style="width:auto;">
				<option value="">Todos</option>
				<?php foreach ($parentescos as $parentesco): ?>
					<option value="<?php echo htmlspecialchars($parentesco); ?>"><?php echo htmlspecialchars($parentesco); ?></option>
				<?php endforeach; ?>
			</select>
		</div>
	</div>
	<div id="div2a"></div>
	<br>
</form>
<script language="JavaScript">
	//---------------------------
	function reph() {
		var edad_min = $('#filtroEdadMin').val();
		var edad_max = $('#filtroEdadMax').val();
		window.open("personal/reporte/2_hijos.php?edad_min=" + edad_min + "&edad_max=" + edad_max, "_blank");
	}
	//---------------------------
	function rep() {
		var edad_min = $('#filtroEdadMin').val();
		var edad_max = $('#filtroEdadMax').val();
		window.open("personal/reporte/2_hijos_html.php?edad_min=" + edad_min + "&edad_max=" + edad_max, "_blank");
	}
	//----------------
	function cargarTabla() {
		var nomina = $('#filtroNomina').val();
		var sexo = $('#filtroSexo').val();
		var edad_min = $('#filtroEdadMin').val();
		var edad_max = $('#filtroEdadMax').val();
		var parentesco = $('#filtroParentesco').val();
		$('#div2a').html('<div align="center"><div class="spinner-border" role="status"></div><br><strong>Un momento, por favor...</strong></div>');
		$('#div2a').load('personal/33a_tabla.php?nomina=' + nomina + '&sexo=' + sexo + '&edad_min=' + edad_min + '&edad_max=' + edad_max + '&parentesco=' + parentesco);
	}

	$(document).ready(function() {
		cargarTabla();
		$('#filtroNomina, #filtroSexo, #filtroEdadMin, #filtroEdadMax, #filtroParentesco').on('change keyup', function() {
			cargarTabla();
		});
	});
</script>