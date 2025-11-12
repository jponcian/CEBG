<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');

if ($_SESSION['VERIFICADO'] != "SI") {
	header("Location: ../validacion.php?opcion=val");
	exit();
}

$acceso = 20;
//------- VALIDACION ACCESO USUARIO
include_once "../validacion_usuario.php";
//-----------------------------------
?>
<form id="form999" name="form999" method="post">
	<!-- Modal Header -->
	<div class="modal-header bg-fondo text-center">
		<input type="hidden" id="oid" name="oid" value="0" />
		<input type="hidden" id="txt_id_rif" name="txt_id_rif" value="0" />
		<input type="hidden" id="txt_iva" name="txt_iva" value="0" />
		<input type="hidden" id="txt_iva1" name="txt_iva1" value="0" />
		<input type="hidden" id="txt_total" name="txt_total" value="" />
		<input type="hidden" id="txt_rif" name="txt_rif" value="" />
		<input type="hidden" id="txt_id_presupuesto" name="txt_id_presupuesto" value="0" />
		<input type="hidden" id="txt_tipo" name="txt_tipo" value="0" />
		<input type="hidden" id="txt_numero" name="txt_numero" value="0" />
		<h4 align="center" style="background-color:#0275d8; color:#FFFFFF" class="modal-title w-100 font-weight-bold py-2">Nueva Orden
			<button type="button" class="close" data-dismiss="modal" onclick="buscar2();">&times;</button>
		</h4>
	</div>

	<div class="btn-group btn-block " data-toggle="buttons"><!-- btn-group-toggle -->
		<label class="btn btn-primary"> <!-- active -->
			<input type="radio" name="txt_tipo2" id="COMPRA" value="COMPRA" autocomplete="off" checked> COMPRA
		</label>
		<label class="btn btn-primary">
			<input type="radio" name="txt_tipo2" id="SERVICIO" value="SERVICIO" autocomplete="off"> SERVICIO
		</label>
		<label class="btn btn-primary">
			<input type="radio" name="txt_tipo2" id="MIXTA" value="MIXTA" autocomplete="off"> MIXTA
		</label>
	</div>

	<br />
	<br />
	<!-- Modal body -->
	<div class="p-1">

		<div class="row">
			<div class="form-group col-sm-5">
				<div class="input-group">
					<div class="input-group-text" align="center">Presupuesto</div>
					<select class="custom-select" style="font-size: 14px" name="txt_area" id="txt_area" onchange="buscar_orden(this.value);">
						<option value="0">Seleccione el Presupuesto</option>
						<?php
						//--------------------
						$consultx = "SELECT id, tipo_orden, numero, anno FROM presupuesto_solicitudes WHERE estatus=3 ORDER BY anno, numero;";
						$tablx = $_SESSION['conexionsql']->query($consultx);
						while ($registro_x = $tablx->fetch_object())
						//-------------
						{
							echo '<option ';
							echo ' value="';
							echo $registro_x->id;
							echo '">';
							echo ($registro_x->tipo_orden) . '-' . rellena_cero($registro_x->numero, 3) . '-' . ($registro_x->anno);
							echo '</option>';
						}
						?>
					</select>
				</div>
			</div>

			<div class="form-group col-sm-7">
				<input id="txt_nombres" placeholder="Proveedor" name="txt_nombres" class="form-control" type="text" style="text-align:center" readonly="" />
			</div>
		</div>

		<div class="row">
			<div class="form-group col-sm-4">
				<div class="input-group">
					<div class="input-group-text"><i class="fas fa-file-invoice"></i></div>
					<input onkeyup="saltar(event,'txt_control');" onchange="buscar_factura();" type="text" style="text-align:center" class="form-control " name="txt_factura" id="txt_factura" placeholder="Numero Factura" minlength="1" maxlength="10" required>
				</div>
			</div>

			<div class="form-group col-sm-4">
				<div class="input-group">
					<div class="input-group-text"><i class="fas fa-file-alt"></i></div>
					<input onkeyup="saltar(event,'txt_fecha')" type="text" style="text-align:center" class="form-control " name="txt_control" id="txt_control" placeholder="Numero Control" minlength="1" maxlength="10" required>
				</div>
			</div>

			<div class="form-group col-sm-4">
				<div class="input-group">
					<div class="input-group-text"><i class="far fa-calendar-alt"></i></div>
					<input onkeyup="saltar(event,'txt_concepto')" type="text" style="text-align:center" class="form-control " name="txt_fecha" id="txt_fecha" placeholder="Fecha Factura" minlength="1" maxlength="10" onchange="combo0(this.value);" value="<?php echo date('d/m/Y'); ?>" required>
				</div>
			</div>

		</div>


		<div class="row">
			<div class="form-group col-sm-12">
				<textarea id="txt_concepto" name="txt_concepto" placeholder="Escribe aqui el Concepto" class="form-control" rows="4"></textarea>
			</div>
		</div>
		<div class="row">

			<div class="form-group col-sm-5">
				<div class="input-group-text">Categoria: <select class="custom-select" style="font-size: 14px" name="txt_categoria" id="txt_categoria" onchange="combo(this.value);">
						<option value="0">Seleccione</option>
					</select>
				</div>
			</div>

			<div class="form-group col-sm-7">
				<div class="input-group-text">Partida: <select class="custom-select" style="font-size: 14px" name="txt_partida" id="txt_partida" onchange="cargar_iva();">
						<option value="0">Espere miestras se cargan las partidas...</option>
					</select>
				</div>
			</div>
		</div>

		<table width="100%" border="1">
			<tr>
				<th scope="col"><input onkeydown="puro_numero('txt_cantidad');" onkeyup="saltar(event,'txt_detalle')" id="txt_cantidad" name="txt_cantidad" placeholder="Cant" class="form-control" type="text" style="text-align:center" /></th>
				<th width="65%" scope="col"><input onkeyup="saltar(event,'txt_precio')" id="txt_detalle" name="txt_detalle" placeholder="Detalle" class="form-control" type="text" style="text-align:center" /></th>
				<th width="20%" scope="col"><input onkeyup="guardar_detalle2(event)" id="txt_precio" name="txt_precio" placeholder="Precio" class="form-control" type="text" style="text-align:center" /></th>
				<th scope="col">Exento<input id="txt_exento" name="txt_exento" type="checkbox" class="switch_new" value="1" /><label for="txt_exento" class="lbl_switch"></label></th>
			</tr>
		</table>

		<br>
		<div align="center">
			<button type="button" id="boton" class="btn btn-outline-success waves-effect" onclick="guardar_detalle(0)"><i class="fas fa-save prefix grey-text mr-1"></i> Agregar Detalle</button>
		</div>

	</div>

	<!-- Modal footer -->
	<div class="modal-footer justify-content-center">
		<div align="center" id="div3">

		</div>
	</div>

</form>
<script language="JavaScript">
	$('#cmdbuscar').hide();
	//----------------- PARA VALIDAR
	function validar_detalle() {
		error = 0;
		if (document.form999.txt_id_rif.value == "" || document.form999.txt_id_rif.value == "0") {
			Swal.fire("Debe Indicar el Rif");
			error = 1;
		}
		if (document.form999.txt_concepto.value == "") {
			document.form999.txt_concepto.focus();
			Swal.fire("Debe Indicar el Concepto");
			error = 1;
		}
		if (document.form999.txt_partida.value == "0") {
			document.form999.txt_partida.focus();
			Swal.fire("Debe Seleccionar la Partida");
			error = 1;
		}
		if (document.form999.txt_categoria.value == "0") {
			document.form999.txt_categoria.focus();
			Swal.fire("Debe Seleccionar la Categoria");
			error = 1;
		}
		if (document.form999.txt_cantidad.value == "") {
			document.form999.txt_cantidad.focus();
			Swal.fire("Debe Indicar la Cantidad");
			error = 1;
		}
		if (document.form999.txt_detalle.value == "") {
			document.form999.txt_detalle.focus();
			Swal.fire("Debe Indicar la Descripcion");
			error = 1;
		}
		if (document.form999.txt_precio.value == "") {
			document.form999.txt_precio.focus();
			Swal.fire("Debe Indicar el Precio Unitario");
			error = 1;
		}
		return error;
	}
	//--------------------------- PARA GUARDAR
	function guardar_detalle2(e) {
		(e.keyCode) ? k = e.keyCode: k = e.which;
		// Si la tecla pulsada es enter (codigo ascii 13)
		if (k == 13) {
			guardar_detalle();
		}
	}
	//--------------------------- PARA GUARDAR
	function guardar_detalle() {
		if (validar_detalle() == 0) {
			$('#boton').hide();
			var parametros = $("#form999").serialize();
			$.ajax({
				type: 'POST',
				url: 'compras/2e_guardar.php',
				dataType: "json",
				data: parametros,
				success: function(data) {
					if (data.tipo == "info") {
						Swal.fire("Éxito", data.msg, "success");
						tabla();
						document.form999.txt_cantidad.value = '';
						document.form999.txt_detalle.value = '';
						document.form999.txt_precio.value = '';
						document.form999.txt_exento.checked = 0;
						combo(document.form999.txt_categoria.value);
						document.form999.txt_cantidad.focus();
						$('#boton').show();
					} else {
						Swal.fire("Aviso", data.msg, "info");
					}
				}
			});
		}
	}
	//-------------
	function combo0(fecha) {
		$.ajax({
			type: "POST",
			url: 'compras/2f_combo.php?fecha=' + fecha,
			success: function(resp) {
				$('#txt_categoria').html(resp);
			}
		});
	}
	//-------------
	function combo(categoria) {
		$.ajax({
			type: "POST",
			url: 'compras/2c_combo.php?categoria=' + categoria + '&partida=0&fecha=' + document.form999.txt_fecha.value + '&id_rif=' + document.form999.txt_id_rif.value,

			success: function(resp) {
				$('#txt_partida').html(resp);
			}
		});
	}
	//--------------------- PARA BUSCAR
	function buscar_orden(id) {
		var parametros = "id=" + id + "&tipo=" + document.form999.txt_tipo2.value;
		$.ajax({
			type: 'POST',
			url: 'compras/2i_buscar.php',
			data: parametros,
			dataType: "json",
			success: function(data) {
				if (data.tipo == "alerta") {
					Swal.fire(data.msg);
				} else {
					$('#txt_area').prop('disabled', true);
					document.form999.txt_control.value = data.control;
					document.form999.txt_factura.value = data.factura;
					document.form999.txt_fecha.value = data.fecha_factura;
					document.form999.txt_concepto.value = data.concepto;
					document.form999.txt_id_rif.value = data.id_contribuyente;
					document.form999.txt_rif.value = data.rif;
					document.form999.txt_id_presupuesto.value = data.id;
					document.form999.txt_tipo.value = data.tipo;
					$("#" + data.tipo2).attr('checked', true);
					document.form999.txt_numero.value = data.numero;
					setTimeout(function() {
						buscar_proveedor();
						tabla();
					}, 500)
					document.form999.txt_factura.focus();
				}
			}
		});
	}
	//--------------------- PARA BUSCAR
	function buscar_proveedor() {
		$('#cmdbuscar').hide();
		var parametros = "id=" + document.form999.txt_rif.value;
		$.ajax({
			type: 'POST',
			url: 'funciones/buscar_contribuyente.php',
			data: parametros,
			dataType: "json",
			success: function(data) {
				if (data.tipo == "alerta") {
					Swal.fire(data.msg);
				} else {
					document.form999.txt_nombres.value = data.contribuyente;
					$('#cmdbuscar').show();
				}
			}
		});
	}
	//-------------------------
	function modificar(id) {
		Swal.fire({
			title: "¿Estas seguro de modificar el Registro?",
			icon: "warning",
			showCancelButton: true,
			confirmButtonText: "Sí, modificar",
			cancelButtonText: "Cancelar"
		}).then((result) => {
			if (result.isConfirmed) {
				var parametros = $("#form999").serialize();
				$.ajax({
					url: "compras/2l_guardar.php?id=" + id,
					type: "POST",
					data: parametros,
					success: function(r) {
						Swal.fire("Registro Modificado Correctamente", "", "success");
						tabla();
					}
				});
			}
		});
	}
	//--------------------- PARA BUSCAR
	function buscar_factura() {
		var parametros = "id=" + document.form999.txt_id_rif.value + "&fact=" + document.form999.txt_factura.value;
		$.ajax({
			type: 'POST',
			url: 'compras/1k_buscar.php',
			data: parametros,
			dataType: "json",
			success: function(data) {
				if (data.tipo == "alerta") {
					Swal.fire(data.msg);
				}
			}
		});
	}
	//----------------- 
	function cargar_iva() {
		if (document.form999.txt_partida.value == "403180100000" || document.form999.txt_partida.value == "403180100" || document.form999.txt_partida.value == "403.18.01.00." || document.form999.txt_partida.value == "403.18.01.00.000" || document.form999.txt_partida.value == "403.180.100.000" || document.form999.txt_partida.value == "403.180.100.001") {
			Swal.fire({
				title: "Ingrese el porcentaje del Impuesto al Valor Agregado",
				input: "text",
				inputValue: "16",
				showCancelButton: true,
				confirmButtonText: "Aceptar",
				cancelButtonText: "Cancelar"
			}).then((result) => {
				if (result.isConfirmed) {
					var valor = result.value;
					var parametros = $("#form999").serialize();
					$.ajax({
						type: 'POST',
						url: 'compras/2k_iva.php',
						data: parametros,
						dataType: "json",
						success: function(data) {
							document.form999.txt_cantidad.value = 1;
							document.form999.txt_detalle.value = 'IMPUESTO AL VALOR AGREGADO';
							document.form999.txt_iva.value = valor;
							document.form999.txt_precio.value = number_format(data.monto * valor / 100, 2);
							setTimeout(function() {
								$('#txt_precio').focus();
							}, 500);
						}
					});
				}
			});
		}
	}
	//--------------------------------
	//setTimeout(function()	{
	//		$('#txt_rif').focus();
	//		},1000)	
	//--------------------------------
	$("#txt_fecha").datepicker();
	combo0('<?php echo date('d/m/Y'); ?>');
	//--------------------------------
	$("#txt_precio").on({
		"focus": function(event) {
			$(event.target).select();
		},
		"keyup": function(event) {
			$(event.target).val(function(index, value) {
				return value.replace(/\D/g, "")
					.replace(/([0-9])([0-9]{2})$/, '$1,$2')
					.replace(/\B(?=(\d{3})+(?!\d)\.?)/g, ".");
			});
		}
	});
</script>