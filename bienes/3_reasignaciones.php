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
?>
<form id="form1" name="form1" method="post" onsubmit="return evitar();">
	<div align="center" class="TituloP">Reasignaciones</div>
	<br>
	<div class="text-right mb-3"><a href="" class="btn btn-outline-primary btn-rounded btn-sm font-weight-bold" onclick="agregar();" data-toggle="modal" data-target="#modal_largo" data-backdrop="static" data-keyboard="false"><i class="fas fa-plus-circle"></i> Agregar Reasignacion</a></div>
	<diw class="row ml-3">
		<strong>Opciones de Busqueda:</strong>
		<!--<div class="form-check ml-3">
              <label class="form-check-label">
                    <input type="radio" class="form-check-input" name="optradio" value="1" >
                N&uacute;mero</label>
            </div>-->

		<div class="form-check ml-3">
			<label class="form-check-label">
				<input type="radio" class="form-check-input" name="optradio" value="2" checked="checked" onclick="ver();">
				Descripcion
			</label>
		</div>
		<div class="form-check ml-3">
			<label class="form-check-label">
				<input type="radio" class="form-check-input" name="optradio" value="3" onclick="ver();buscar()">
				Pendiente
			</label>
		</div>
		<div class="form-check ml-3">
			<label class="form-check-label">
				<input type="radio" class="form-check-input" name="optradio" value="4" onclick="ver();buscar()">
				Aprobadas
			</label>
		</div>
		<div class="form-check ml-3">
			<label class="form-check-label">
				<input type="radio" class="form-check-input" name="optradio" value="5" onclick="ver();">
				Por Fecha
			</label>
		</div>
		<!--<div class="form-check ml-3">
                <label class="form-check-label">
                    <input type="radio" class="form-check-input" name="optradio" value="4" onclick="buscar()" >
                   Ver Todos
                </label>
            </div>-->
	</diw>

	<div id="cuadro"><input name="obuscar" id="obuscar" type="text" size="100" class="form-control" onchange="buscar()" /></div>
	<div id="fecha">
		<table>
			<tr>
				<td align="left" valign="top">
					<input class="form-control" type="text" name="OFECHA" id="OFECHA" size="15" placeholder="Desde" value="<?php echo date('d/m/Y'); ?>" style="text-align:center" />
				</td>
				<td>
					<input class="form-control" type="text" name="OFECHA2" id="OFECHA2" size="15" placeholder="Hasta" value="<?php echo date('d/m/Y'); ?>" style="text-align:center" />
				</td>
				<td>
					<button type="button" id="botonb" class="btn btn-primary" onClick="buscar();"><i class="fas fa-search mr-2"></i>Buscar</button>
				</td>
			</tr>
		</table>
	</div>

	<br>

	<div id="div1"></div>
</form>
<script language="JavaScript">
	$('#cuadro').show();
	$('#fecha').hide();
	//---------------------
	function borrar(id) {
		Swal.fire({
			title: 'Estas seguro de eliminar el Movimiento?',
			text: "Esta acción no se puede revertir!",
			icon: 'question',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Si, borrar!',
			cancelButtonText: 'Cancelar'
		}).then((result) => {
			if (result.isConfirmed) {
				//-----------------------
				var parametros = "id=" + id;
				$.ajax({
					url: "bienes/3k_eliminar.php",
					type: "POST",
					data: parametros,
					success: function(r) {
						//Swal.fire('Borrado!', 'El registro fue borrado.', 'success');
						alertify.success('El Movimiento fue borrado con Exito!');
						buscar();
					}
				});
				//-----------------------
			}
		})
	}
	//------------------------
	$('#OFECHA').dateRangePicker({
		//	startDate: moment().format("DD-MM-YYYY"),
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
	function ver() {
		$('#cuadro').hide();
		$('#fecha').hide();
		if (document.form1.optradio.value == 2) {
			$('#cuadro').show();
		}
		if (document.form1.optradio.value == 5) {
			$('#fecha').show();
		}
	}
	//------------------
	function generar_solicitud(id) {
		Swal.fire({
			title: "¿Estas seguro de generar el Movimiento?",
			icon: "question",
			showCancelButton: true,
			confirmButtonText: "Sí, generar",
			cancelButtonText: "Cancelar"
		}).then((result) => {
			if (result.isConfirmed) {
				var parametros = "id=" + id;
				$.ajax({
					type: 'POST',
					url: 'bienes/3j_guardar.php?',
					dataType: "json",
					data: parametros,
					success: function(data) {
						if (data.tipo == "info") {
							$('#modal_normal .close').click();
							Swal.fire({
								icon: "success",
								title: "Éxito",
								text: data.msg,
								timer: 2000,
								showConfirmButton: false
							});
							buscar();
						} else {
							Swal.fire({
								icon: "info",
								title: "Información",
								text: data.msg
							});
						}
						//--------------
					}
				});
			}
		});
	}
	//----------------
	function buscar2() {
		document.form1.optradio.value = 3;
		$('#div1').html('<div align="center"><div class="spinner-border" role="status"></div><br><strong>Un momento, por favor...</strong></div>');
		$('#div1').load('bienes/3a_tabla.php?valor=' + cambia(document.form1.obuscar.value) + '&tipo=3');
	}
	//----------------
	function buscar() {
		if ((document.form1.obuscar.value == "  " || document.form1.obuscar.value == " " || document.form1.obuscar.value == "") && document.form1.optradio.value == 5) {
			$('#div1').html('<div align="center"><div class="spinner-border" role="status"></div><br><strong>Un momento, por favor...</strong></div>');
			$('#div1').load('bienes/3a_tabla.php?fecha1=' + document.form1.OFECHA.value + '&fecha2=' + document.form1.OFECHA2.value + '&tipo=' + document.form1.optradio.value);
		} else {
			$('#div1').html('<div align="center"><div class="spinner-border" role="status"></div><br><strong>Un momento, por favor...</strong></div>');
			$('#div1').load('bienes/3a_tabla.php?valor=' + cambia(document.form1.obuscar.value) + '&tipo=' + document.form1.optradio.value);
		}
	}
	//-----------------------
	function agregar() {
		$('#modal_lg').html('<div align="center"><div class="spinner-border" role="status"></div><br><strong>Un momento, por favor...</strong></div>');
		$('#modal_lg').load('bienes/3b_modal.php');
	}
	//---------------------
	function imprimir(origen, destino, estatus, id) {
		//	window.open("bienes/reporte/3_rea_21.php?p=1&origen="+origen+"&destino="+destino+"&estatus="+estatus+"&id="+id,"_blank");
		window.open("bienes/reporte/reasignacion.php?p=1&origen=" + origen + "&destino=" + destino + "&estatus=" + estatus + "&id=" + id, "_blank");
		//	if (estatus==10) { window.open("bienes/formatos/memorando_reasignacion.php?id="+id,"_blank");	}
	}
</script>