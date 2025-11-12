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
	<div align="center" class="TituloP">Anular Orden de Compra y/o Servicio</div>
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
	function anular(id, id_solicitud, estatus) {
		Swal.fire({
			title: "¿Estas seguro de continuar con la Anulación?",
			icon: "warning",
			showCancelButton: true,
			confirmButtonText: "Sí, anular",
			cancelButtonText: "Cancelar"
		}).then((result) => {
			if (result.isConfirmed) {
				var parametros = "id=" + id + "&id_solicitud=" + id_solicitud + "&estatus=" + estatus;
				$.ajax({
					type: 'POST',
					url: 'compras/5b_guardar.php',
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
	//----------------
	function buscar() {
		if ((document.form1.obuscar.value == "  " || document.form1.obuscar.value == " " || document.form1.obuscar.value == "") && document.form1.optradio.value != 4) {} else {
			//valor = document.form1.obuscar.value; 
			//valor = valor.replace(/ /g, '_');
			$('#div1').html('<div align="center"><div class="spinner-border" role="status"></div><br><strong>Un momento, por favor...</strong></div>');
			$('#div1').load('compras/5a_tabla.php?valor=' + cambia(document.form1.obuscar.value) + '&tipo=' + document.form1.optradio.value);
		}
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
</script>