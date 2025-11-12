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

$nominas = [];
$result = $_SESSION['conexionsql']->query("SELECT DISTINCT nomina FROM rac WHERE temporal=0");
while ($row = $result->fetch_assoc()) {
	$nominas[] = $row['nomina'];
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
		<div class="col text-right">
			<a href="" class="btn btn-outline-primary btn-rounded btn-sm font-weight-bold" onclick="agregar();" data-toggle="modal" data-target="#modal_largo" data-keyboard="false">
				<i class="fas fa-plus-circle"></i> Agregar Empleado
			</a>
		</div>
	</div>

	<div class="form-group row justify-content-center">
		<div class="col-auto d-flex align-items-center flex-wrap" style="gap:6px;">
			<label for="filtroNomina" class="font-weight-bold mb-0">Nóminas:</label>
			<select id="filtroNomina" class="select2" style="width:320px" multiple="multiple" placeholder="Seleccione una o varias nóminas">
				<?php foreach ($nominas as $nomina): ?>
					<option value="<?php echo htmlspecialchars($nomina); ?>"><?php echo htmlspecialchars($nomina); ?></option>
				<?php endforeach; ?>
			</select>
		</div>
		<div class="col-auto d-flex align-items-center" style="gap:6px;">
			<label for="filtroSexo" class="font-weight-bold mb-0">Sexo:</label>
			<select id="filtroSexo" class="form-control form-control-sm" style="width:auto;">
				<option value="">Todos</option>
				<option value="M">Masculino</option>
				<option value="F">Femenino</option>
			</select>
		</div>
	</div>
	<div id="div2a"></div>
	<br>
</form>
<script language="JavaScript">
	//---------------------------
	function ficha(id) {
		window.open("personal/reporte/3_ficha.php?id=" + id, "_blank");
	}
	//---------------------------
	function reph() {
		window.open("personal/reporte/1_empleados.php", "_blank");
	}
	//---------------------------
	function rep() {
		window.open("personal/reporte/1_empleados_html.php", "_blank");
	}
	//--------------------------------
	function guardar(tipo) {
		var parametros = $("#form999").serialize();
		$.ajax({
			type: 'POST',
			url: 'personal/4e_guardar.php?tipo=' + tipo,
			dataType: "json",
			data: parametros,
			success: function(data) {
				if (data.tipo == "info") {
					alertify.success(data.msg);
					$('#modal_largo .close').click();
					busca_empleados();
				} else {
					alertify.alert(data.msg);
				}
			}
		});
	}
	//--------------------------------------------
	function agregar() {
		$('#modal_lg').html('<div align="center"><div class="spinner-border" role="status"></div><br><strong>Un momento, por favor...</strong></div>');
		$('#modal_lg').load('personal/4b_modal.php');
	}
	//----------------
	function basicos(id) {
		$('#modal_lg').html('<div align="center"><div class="spinner-border" role="status"></div><br><strong>Un momento, por favor...</strong></div>');
		$('#modal_lg').load('personal/4d_modal.php?id=' + id);
	}
	//----------------
	function encargaduria(id) {
		$('#modal_lg').html('<div align="center"><div class="spinner-border" role="status"></div><br><strong>Un momento, por favor...</strong></div>');
		$('#modal_lg').load('personal/4k_modal.php?id=' + id);
	}
	//----------------
	function laboral(id) {
		$('#modal_lg').html('<div align="center"><div class="spinner-border" role="status"></div><br><strong>Un momento, por favor...</strong></div>');
		$('#modal_lg').load('personal/4a_modal.php?id=' + id);
	}
	//----------------
	function hijos(id) {
		$('#modal_lg').html('<div align="center"><div class="spinner-border" role="status"></div><br><strong>Un momento, por favor...</strong></div>');
		$('#modal_lg').load('personal/4g_modal.php?id=' + id);
	}
	//----------------
	function foto(id) {
		$('#modal_n').html('<div align="center"><div class="spinner-border" role="status"></div><br><strong>Un momento, por favor...</strong></div>');
		$('#modal_n').load('personal/4o_modal.php?id=' + id);
	}
	//----------------
	function cargarTabla(nomina = '', sexo = '') {
		$('#div2a').html('<div align="center"><div class="spinner-border" role="status"></div><br><strong>Un momento, por favor...</strong></div>');
		var nominaParam = '';
		if (Array.isArray(nomina)) {
			nominaParam = nomina.join(',');
		} else if (typeof nomina === 'string') {
			nominaParam = nomina;
		}
		$('#div2a').load('personal/4f_tabla.php?nomina=' + encodeURIComponent(nominaParam) + '&sexo=' + encodeURIComponent(sexo || ''));
	}

	$(document).ready(function() {
		// Inicializa Select2 con la misma firma simple usada en Compras
		if ($.fn.select2) {
			$('.select2').select2({});
		}

		cargarTabla();

		$('#filtroNomina, #filtroSexo').on('change', function() {
			cargarTabla($('#filtroNomina').val() || [], $('#filtroSexo').val());
		});
	});
</script>