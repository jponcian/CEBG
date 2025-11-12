<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');

if ($_SESSION['VERIFICADO'] != "SI") {
	header("Location: ../validacion.php?opcion=val");
	exit();
}

$acceso = 60;
//------- VALIDACION ACCESO USUARIO
include_once "../validacion_usuario.php";
//-----------------------------------
$condicion = "WHERE id=" . $_SESSION["direccion"];
?>
<form id="form1" name="form1" method="post">
	<div align="center" class="TituloP">Movimientos de Inventario</div>
	<br>
	<table border="1">
		<tr align="center">

			<diw class="row ml-3">
				<strong>Opciones de Busqueda:</strong>

				<div class="form-check ml-3">
					<label class="form-check-label">
						<input type="radio" class="form-check-input" name="optradio" value="<?php echo encriptar('5'); ?>">
						Articulos de Trabajo
					</label>
				</div>

				<div class="form-check ml-3">
					<label class="form-check-label">
						<input type="radio" class="form-check-input" name="optradio" value="<?php echo encriptar('6'); ?>">
						Suministros
					</label>
				</div>

				<div class="form-check ml-3">
					<label class="form-check-label">
						<input type="radio" class="form-check-input" name="optradio" value="<?php echo encriptar('4'); ?>" checked>
						Ver Todos
					</label>
				</div>
			</diw>
			<br>
			<div class="row">
				<div class="form-group col-sm-6 ml-5">
					<div class="input-group-text">Direccion: <select class="custom-select" style="font-size: 14px" name="txt_direccion" id="txt_direccion">
							<option value=<?php echo encriptar('0'); ?>>TODAS LAS DIRECCIONES</option>
							<?php
							//--------------------
							$consult = "SELECT * FROM a_direcciones WHERE id<50 ORDER BY direccion;"; // WHERE id_direccion='$desde'
							//$consult = "SELECT * FROM a_direcciones $condicion ORDER BY direccion;"; // WHERE id_direccion='$desde'
							$tablx = $_SESSION['conexionsql']->query($consult);
							while ($registro_x = $tablx->fetch_object())
							//-------------
							{
								echo '<option value="';
								echo encriptar($registro_x->id);
								echo '" >';
								echo $registro_x->direccion;
								echo '</option>';
							}
							?>
						</select>
					</div>
				</div>
			</div>
			<!--	<br>-->
			<div id="fechas">
				<table>
					<tr>
						<td align="left" valign="top">
							<input class="form-control ml-5" type="text" name="OFECHA" id="OFECHA" size="12" placeholder="Desde" value="<?php echo date('d/m/Y'); ?>" style="text-align:center" />
						</td>
						<td>
							<input class="form-control" type="text" name="OFECHA2" id="OFECHA2" size="12" placeholder="Hasta" value="<?php echo date('d/m/Y'); ?>" style="text-align:center" />
						</td>
						<td>
							<button type="button" id="botonb" class="btn btn-primary" onClick="reportes2();"><i class="fas fa-search mr-2"></i>Ver Reporte</button>
						</td>
					</tr>
				</table>
			</div>

		</tr>
	</table>
</form>
<script language="JavaScript">
	$("#OFECHA").datepicker();
	$("#OFECHA2").datepicker();
	//---------------------------
	function reportes2() {
		var desde = document.form1.OFECHA.value.split('/').reverse().join('-');
		var hasta = document.form1.OFECHA2.value.split('/').reverse().join('-');
		var fechaDesde = new Date(desde);
		var fechaHasta = new Date(hasta);

		if (fechaDesde > fechaHasta) {
			Swal.fire({
				icon: 'warning',
				title: 'Fechas incorrectas',
				text: "La fecha 'Inicial' no puede ser mayor a la fecha 'Final'."
			});
			return false;
		}

		window.open("almacen/reporte/2_movimiento.php?desde=" + document.form1.OFECHA.value + "&hasta=" + document.form1.OFECHA2.value + "&tipo=" + document.form1.optradio.value + "&direccion=" + document.form1.txt_direccion.value, "_blank");
		window.open("almacen/reporte/2_movimiento2.php?desde=" + document.form1.OFECHA.value + "&hasta=" + document.form1.OFECHA2.value + "&tipo=" + document.form1.optradio.value + "&direccion=" + document.form1.txt_direccion.value, "_blank");
	}
</script>