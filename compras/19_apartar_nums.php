<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');

if ($_SESSION['VERIFICADO'] != "SI") {
	header("Location: validacion.php?opcion=val");
	exit();
}

$acceso = 117;
//------- VALIDACION ACCESO USUARIO
include_once "../validacion_usuario.php";
//-----------------------------------
?>
<form id="form999" name="form999" method="post">
	<!-- Modal Header -->
	<div class="modal-header bg-fondo text-center">
		<h4 align="center" style="background-color:#0275d8; color:#FFFFFF" class="modal-title w-100 font-weight-bold py-2">Comprometer Orden de Servicio
			<button type="button" class="close" data-dismiss="modal">&times;</button>
		</h4>
	</div>
	<!-- Modal body -->
	<div class="p-1">

		<div class="row">

			<div class="form-group col-sm-6">
				<div class="input-group">
					<input class="form-control" type="text" style="text-align:center" maxlength="20" value="<?php echo compromiso_sig(2); ?>" readonly />
				</div>
			</div>

			<div class="form-group col-sm-6">
				<div class="input-group">
					<input class="form-control" type="text" style="text-align:center" maxlength="20" value="<?php echo date('d/m/Y'); ?>" readonly />
				</div>
			</div>

		</div>

	</div>
	<!-- Modal footer -->
	<div class="modal-footer justify-content-center">
		<button type="button" id="boton" class="btn btn-outline-success waves-effect" onclick="apartar_num()"><i class="fas fa-save prefix grey-text mr-1"></i>Apartar Numero</button>
	</div>
</form>
<script language="JavaScript">
	//--------------------------------
	function apartar_num() {
		Swal.fire({
			title: "¿Estas seguro de comprometer la Orden de Servicio?",
			icon: "question",
			showCancelButton: true,
			confirmButtonText: "Sí, comprometer",
			cancelButtonText: "Cancelar"
		}).then((result) => {
			if (result.isConfirmed) {
				var parametros = $("#form999").serialize();
				$.ajax({
					type: 'POST',
					url: 'compras/19a_guardar.php?tipo=2',
					dataType: "json",
					data: parametros,
					success: function(data) {
						if (data.tipo == "info") {
							Swal.fire("Éxito", data.msg, "success");
							$('#modal_normal .close').click();
						} else {
							Swal.fire("Aviso", data.msg, "info");
						}
					}
				});
			}
		});
	}
</script>