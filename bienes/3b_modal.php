<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');

if ($_SESSION['VERIFICADO'] != "SI") {
	header("Location: ../validacion.php?opcion=val");
	exit();
}

$acceso = 49;
//------- VALIDACION ACCESO USUARIO
include_once "../validacion_usuario.php";
//-----------------------------------
//if ($_SESSION["bienes"]==0)
//	{	$condicion = "WHERE id=".$_SESSION["direccion"]; 	}
//else
//	{	$condicion = ""; 	} 
?>
<form id="form999" name="form999" method="post">
	<!-- Modal Header -->
	<div class="modal-header bg-fondo text-center">
		<h4 align="center" style="background-color:#0275d8; color:#FFFFFF" class="modal-title w-100 font-weight-bold py-2">Nuevo Movimiento
			<button type="button" class="close" data-dismiss="modal" onclick="buscar2();">&times;</button>
		</h4>
	</div>
	<!-- Modal body -->
	</br>
	<div class="form-group">
		<div class="input-group col-sm-12">
			<div class="input-group-prepend">
				<div class="input-group-text">Origen</div>
			</div>
			<select class="custom-select" style="font-size: 14px" name="txt_origen" id="txt_origen" onchange="combo2(this.value);">
				<option value="0">Seleccione</option>
				<?php
				//--------------------
				$consult = "SELECT * FROM bn_dependencias $condicion ORDER BY division;"; // WHERE id_direccion='$desde'
				$tablx = $_SESSION['conexionsql']->query($consult);
				while ($registro_x = $tablx->fetch_object())
				//-------------
				{
					echo '<option value="';
					echo $registro_x->id;
					echo '" ';
					if ($partida == $registro_x->id) {
						echo 'selected="selected"';
					}
					echo ' >';
					echo ($registro_x->codigo) . ' ' . $registro_x->division;
					echo '</option>';
				}
				?>
			</select>
		</div>
	</div>

	<div class="form-group">
		<div class="input-group col-sm-12">
			<div class="input-group-prepend">
				<div class="input-group-text">Destino</div>
			</div>
			<select class="custom-select" style="font-size: 14px" name="txt_destino" id="txt_destino" onchange="listar_bienes();combo3();">
				<option value="0">Seleccione</option>
			</select>
		</div>
	</div>

	<div class="form-group">
		<div class="input-group col-sm-12">
			<div class="input-group-prepend">
				<div class="input-group-text">Motivo</div>
			</div>
			<select name="txt_motivo" id="txt_motivo" class="select2" style="width: 500px" onChange="">
				<option value="0">Seleccione el Motivo del Movimiento</option>
			</select>
		</div>
	</div>

	<div class="form-group">
		<div class="input-group col-sm-12">
			<div class="input-group-prepend">
				<div class="input-group-text">Bien Nacional</div>
			</div>
			<select name="txt_bien" id="txt_bien" class="select2" style="width: 500px" onChange="reasignar(this.value);">
				<option value="0">Seleccione el Bien Nacional a Reasignar</option>

			</select>
		</div>
	</div>

	<!-- Modal footer -->
	<div class="modal-footer justify-content-center">
		<div id="div3"></div>
	</div>

</form>
<script language="JavaScript">
	// PARA EL SELECT2
	$(document).ready(function() {
		$('.select2').select2();
	});
	//------------------------------
	function reasignar(id) {
		Swal.fire({
			title: 'Estas seguro de Reasignar el Bien?',
			icon: 'question',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Si, reasignar!',
			cancelButtonText: 'Cancelar'
		}).then((result) => {
			if (result.isConfirmed) {
				//-----------------------
				if (validar_detalle() == 0) {
					var parametros = "id=" + id + "&origen=" + document.form999.txt_origen.value + "&destino=" + document.form999.txt_destino.value + "&motivo=" + document.form999.txt_motivo.value;
					$.ajax({
						url: "bienes/3f_reasignar.php",
						type: "POST",
						data: parametros,
						success: function(r) {
							Swal.fire({
								icon: 'success',
								title: 'Registro Reasignado Correctamente',
								showConfirmButton: false,
								timer: 2000
							});
							//--------------
							listar_bienes();
						}
					});
				}
				//-----------------------
			}
		})
	}
	//------------------------------ PARA ELIMINAR
	function eliminar(id) {
		Swal.fire({
			title: "Estas seguro de Eliminar el Registro?",
			icon: "question",
			showCancelButton: true,
			confirmButtonColor: "#3085d6",
			cancelButtonColor: "#d33",
			confirmButtonText: "Si, eliminar!",
			cancelButtonText: "Cancelar"
		}).then((result) => {
			if (result.isConfirmed) {
				var parametros = "id=" + id;
				$.ajax({
					url: "bienes/3h_eliminar.php",
					type: "POST",
					data: parametros,
					success: function(r) {
						Swal.fire({
							icon: 'success',
							title: 'Registro Eliminado Correctamente',
							showConfirmButton: false,
							timer: 2000
						});
						//--------------
						listar_bienes();
					}
				});
			}
		});
	}
	//----------------- PARA VALIDAR
	function validar_detalle() {
		error = 0;
		if (document.form999.txt_origen.value == "0") {
			document.form999.txt_origen.focus();
			Swal.fire({
				icon: 'warning',
				title: 'Debe Seleccionar el Area de Origen!'
			});
			error = 1;
		}
		if (document.form999.txt_destino.value == "0") {
			document.form999.txt_destino.focus();
			Swal.fire({
				icon: 'warning',
				title: 'Debe Seleccionar el Area de Destino!'
			});
			error = 1;
		}
		if (document.form999.txt_motivo.value == "0") {
			document.form999.txt_motivo.focus();
			Swal.fire({
				icon: 'warning',
				title: 'Debe Seleccionar el Motivo!'
			});
			error = 1;
		}
		return error;
	}
	//-------------
	function combo2() {
		$.ajax({
			type: "POST",
			url: 'bienes/3c_combo2.php?origen=' + document.form999.txt_origen.value,
			success: function(resp) {
				$('#txt_destino').html(resp);
				//listar_bienes();
			}
		});
	}
	//-------------
	function combo3() {
		$.ajax({
			type: "POST",
			url: 'bienes/3c_combo4.php?destino=' + document.form999.txt_destino.value,
			success: function(resp) {
				$('#txt_motivo').html(resp);
			}
		});
	}
	//--------------------- PARA BUSCAR
	function listar_bienes() {
		$.ajax({
			type: "POST",
			url: 'bienes/3c_combo3.php?origen=' + document.form999.txt_origen.value,
			success: function(resp) {
				$('#txt_bien').html(resp);
			}
		});
		//-------------
		$('#div3').html('<div align="center"><div class="spinner-border" role="status"></div><br><strong>Un momento, por favor...</strong></div>');
		$('#div3').load('bienes/3d_tabla.php?origen=' + (document.form999.txt_origen.value));
	}
</script>