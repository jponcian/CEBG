<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');

if ($_SESSION['VERIFICADO'] != "SI") {
	header("Location: ../validacion.php?opcion=val");
	exit();
}

$acceso = 45;
//------- VALIDACION ACCESO USUARIO
include_once "../validacion_usuario.php";
//-----------------------------------
?>
<form id="form1" name="form1" method="post" onsubmit="return evitar();">
	<div align="center" class="TituloP">Reversar Orden de Compra y/o Servicio</div>
	<br>
	<diw class="row ml-3">
		<strong>Opciones de Busqueda:</strong>
		<div class="form-check ml-3">
			<label class="form-check-label">
				<input type="radio" class="form-check-input" name="optradio" value="1">
				N&uacute;mero</label>
		</div>

		<div class="form-check ml-3">
			<label class="form-check-label">
				<input type="radio" class="form-check-input" name="optradio" value="2" checked="checked">
				Descripcion
			</label>
		</div>
		<div class="form-check ml-3">
			<label class="form-check-label">
				<input type="radio" class="form-check-input" name="optradio" value="4" onclick="buscar()">
				Ver Todos
			</label>
		</div>
	</diw>
	<input name="obuscar" id="obuscar" type="text" size="100" class="form-control" onchange="buscar()" />

	<br>

	<div id="div1"></div>
</form>
<script language="JavaScript">
	//----------------------
	function anular(id_pago, id_solicitud) {
		Swal.fire({
			title: "¿Estás seguro de reversar la Orden?",
			icon: "warning",
			showCancelButton: true,
			confirmButtonText: "Sí, reversar",
			cancelButtonText: "Cancelar"
		}).then((result) => {
			if (result.isConfirmed) {
				var parametros = "id_pago=" + id_pago + "&id_solicitud=" + id_solicitud;
				$.ajax({
					type: 'POST',
					url: 'compras/6b_guardar.php',
					dataType: "json",
					data: parametros,
					success: function(data) {
						if (data.tipo == "info") {
							Swal.fire("Éxito", data.msg, "success");
							buscar();
						} else {
							Swal.fire("Aviso", data.msg, "info");
						}
					}
				});
			}
		});
	}
	//---------------------
	function imprimir(id, tipo) {
		if (tipo == "1") {
			window.open("compras/formatos/2_orden_compra.php?id=" + id, "_blank");
		}
		if (tipo == "2") {
			window.open("compras/formatos/4_orden_servicio.php?id=" + id, "_blank");
		}
	}
	//----------------
	function buscar() {
		if ((document.form1.obuscar.value == "  " || document.form1.obuscar.value == " " || document.form1.obuscar.value == "") && document.form1.optradio.value != 4) {} else {
			$('#div1').html('<div align="center"><div class="spinner-border" role="status"></div><br><strong>Un momento, por favor...</strong></div>');
			$('#div1').load('compras/6a_tabla.php?valor=' + cambia(document.form1.obuscar.value) + '&tipo=' + document.form1.optradio.value);
		}
	}
</script>