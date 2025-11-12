<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');

if ($_SESSION['VERIFICADO'] != "SI") {
	header("Location: ../validacion.php?opcion=val");
	exit();
}

$acceso = 21;
//------- VALIDACION ACCESO USUARIO
include_once "../validacion_usuario.php";
//-----------------------------------
?>
<form id="form1" name="form1" method="post" onsubmit="return evitar();">
	<div align="center" class="TituloP">Relaci&oacute;n de Ordenes de Compra y Servicio</div>
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
	//------------------
	function guardar(boton) {
		Swal.fire({
			title: "¿Estas seguro de guardar los cambios?",
			icon: "question",
			showCancelButton: true,
			confirmButtonText: "Sí, guardar",
			cancelButtonText: "Cancelar"
		}).then((result) => {
			if (result.isConfirmed) {
				$('#' + boton).hide();
				// Swal.fire('Espere mientras se actualiza la Solicitud...');
				var parametros = $("#form999").serialize();
				$.ajax({
					type: 'POST',
					url: 'compras/3j_guardar.php',
					dataType: "json",
					data: parametros,
					success: function(data) {
						if (data.tipo == "info") {
							Swal.fire("Éxito", data.msg, "success");
							$('#modal_largo .close').click();
							buscar();
						} else {
							Swal.fire("Aviso", data.msg, "info");
						}
					}
				});
			}
		});
	}
	//-----------------------
	function modificar(id, tipo, estatus) {
		$('#modal_lg').html('<div align="center"><div class="spinner-border" role="status"></div><br><strong>Un momento, por favor...</strong></div>');
		$('#modal_lg').load('compras/3b_modal.php?id=' + id + '&tipo=' + tipo + '&estatus=' + estatus);
	}
	//----------------
	function buscar() {
		if ((document.form1.obuscar.value == "  " || document.form1.obuscar.value == " " || document.form1.obuscar.value == "") && document.form1.optradio.value != 4) {} else {
			//valor = document.form1.obuscar.value; 
			//valor = valor.replace(/ /g, '_');
			$('#div1').html('<div align="center"><div class="spinner-border" role="status"></div><br><strong>Un momento, por favor...</strong></div>');
			$('#div1').load('compras/3a_tabla.php?valor=' + cambia(document.form1.obuscar.value) + '&tipo=' + document.form1.optradio.value);
		}
	}
	//---------------------
	function imprimir(id, tipo) {
		window.open("compras/formatos/10_orden.php?p=1&id=" + id, "_blank");
		window.open("compras/formatos/8_recepcion.php?p=1&id=" + id, "_blank");
	}
</script>