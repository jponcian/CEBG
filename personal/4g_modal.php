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
//$consultx = "SELECT * FROM rac WHERE rac = ".$_GET['id'].";";  //echo $consultx;
//$tablx = $_SESSION['conexionsql']->query($consultx);
//$registro = $tablx->fetch_object();
?>
<form id="form999" name="form999" method="post">
	<!-- Modal Header -->
	<div class="modal-header bg-fondo text-center">
		<h4 align="center" style="background-color:#0275d8; color:#FFFFFF" class="modal-title w-100 font-weight-bold py-2">Datos B&aacute;sicos
			<button type="button" class="close" data-dismiss="modal">&times;</button>
		</h4>
		<input type="hidden" id="oid" name="oid" value="<?php echo $_GET['id']; ?>" />
	</div>
	<!-- Modal body -->
	<div class="p-1">

		<div class="row">

			<div class="form-group col-sm-3">
				<div class="input-group">
					<input type="text" style="text-align:center" class="form-control " name="txt_cedula" id="txt_cedula" placeholder="Cedula" minlength="1" maxlength="11" value="" required>
				</div>
			</div>

			<div class="form-group col-sm-5">
				<div class="input-group">
					<div class="input-group-text"><!--<i class="far fa-calendar-alt mr-2"></i>-->Parentesco</div>
					<select class="select2" name="txt_parentesco" id="txt_parentesco" style="width: 180px"><!--style="width: 600px"-->
						<option value="0">Seleccione</option>
						<?php
						//--------------------
						$consultx = "SELECT * FROM a_parentesco ;";
						$tablx = $_SESSION['conexionsql']->query($consultx);
						while ($registro_x = $tablx->fetch_object())
						//-------------
						{
							echo '<option ';
							echo ' value="';
							echo $registro_x->descripcion;
							echo '">';
							echo ($registro_x->descripcion);
							echo '</option>';
						}
						?>
					</select>
				</div>
			</div>

			<div class="form-group col-sm-4">
				<div class="input-group">
					<div class="input-group-text"><i class="far fa-calendar-alt mr-2"></i>Nac.</div>
					<input type="text" style="text-align:center" class="form-control " name="txt_fecha" id="txt_fecha" placeholder="Fecha Nacimiento" minlength="1" maxlength="10" value="<?php echo (date('d/m/Y')); ?>" required>
				</div>
			</div>

			<!-- Selector de sexo/género -->
			<div class="form-group col-sm-3">
				<div class="input-group">
					<div class="input-group-text">Sexo</div>
					<select class="form-control" name="txt_sexo" id="txt_sexo" required>
						<option value="">Seleccione</option>
						<option value="M">Masculino</option>
						<option value="F">Femenino</option>
					</select>
				</div>
			</div>

			<div class="form-group col-sm-9">
				<div class="input-group">
					<div class="input-group-text"><!--<i class="far fa-calendar-alt mr-2"></i>-->Nombres</div>
					<input type="text" style="text-align:left" class="form-control " name="txt_nombres" id="txt_nombres" placeholder="Nombres y Apellidos" minlength="1" maxlength="50" value="" required>
				</div>
			</div>

			<div class="form-group col-sm-12 d-flex justify-content-center">
				<button id="boton" type="button" class="btn btn-outline-primary btn-rounded btn-sm font-weight-bold" onclick="agregar_hijo(<?php echo $_GET['id']; ?>);"><i class="fas fa-plus prefix grey-text mr-1"></i> Agregar</button>
			</div>

		</div>


	</div>
	<!-- Modal footer -->
	<div class="modal-footer justify-content-center">

		<div id="div2">
			<?php //include_once "38b_tabla.php"; 
			?>
		</div>

	</div>

</form>
<script language="JavaScript">
	// PARA EL SELECT2
	$(document).ready(function() {
		$('.select2').select2({});
		setTimeout(function() {
			$('#txt_cedula').focus();
		}, 1000)
		$("#txt_fecha").datepicker();
		tabla1(<?php echo $_GET['id']; ?>);
	});

	//------------------------
	function tabla1(id) {
		$('#div2').load('personal/4i_tabla.php?id=' + id);
	}
	//------------------------------ PARA ELIMINAR
	function eliminar_hijo(id, id_rep, parentesco) {
		alertify.confirm("Estas seguro de eliminar el Registro?",
			function() {
				var parametros = "id=" + id + "&parentesco=" + parentesco;
				$.ajax({
					url: "personal/4j_eliminar.php",
					type: "POST",
					data: parametros,
					success: function(r) {
						alertify.success('Registro Eliminado Correctamente');
						//--------------
						tabla1(id_rep);
					}
				});
			});
	}
	//------------------
	function agregar_hijo(id_rep) {
		//	if (validar()==0)
		//		{
		$('#boton').hide();
		var parametros = $("#form999").serialize();
		$.ajax({
			type: 'POST',
			url: 'personal/4h_guardar.php',
			dataType: "json",
			data: parametros,
			success: function(data) {
				if (data.tipo == "info") {
					alertify.success(data.msg);
					tabla1(id_rep);
					$('#boton').show();
				} else {
					alertify.alert(data.msg);
				}
				//--------------
			}
		});
		//		}
	}
	//------------------
</script>