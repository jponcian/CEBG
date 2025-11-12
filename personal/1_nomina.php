<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');

if ($_SESSION['VERIFICADO'] != "SI") {
	header("Location: ../validacion.php?opcion=val");
	exit();
}

$acceso = 11;
//------- VALIDACION ACCESO USUARIO
include_once "../validacion_usuario.php";
//-----------------------------------
//echo $consultx;
?>
<form id="form1" name="form1" method="post" onsubmit="return evitar();">
	<table class="formateada" border="0" align="center" width="100%">
		<tr>
			<td class="TituloTablaP" height="41" colspan="10" align="center">Generar Nomina</td>
		</tr>
		<tr>
			<td>
				<br>
				<div class="form-group col-sm-8">
					<div class="input-group">
						<div class="input-group-text"><i class="fas fa-book"> Nomina</i></div>
						<select id="ONOMINA" name="ONOMINA" onchange="ver_tipo();">
							<option value="0">Seleccione</option>
							<?php
							$consultx = "SELECT * FROM a_nomina WHERE eventual=0 AND activa = 'SI' AND nomina IN (SELECT nomina FROM rac GROUP BY nomina) ORDER BY codigo;";
							$tablx = $_SESSION['conexionsql']->query($consultx);
							while ($registro_x = $tablx->fetch_array()) {
								echo '<option value="' . $registro_x['codigo'] . '">' . $registro_x['nomina'] . '</option>';
							}
							?>
						</select>
					</div>
				</div>

				<div class="form-group col-sm-8">
					<div class="input-group">
						<div class="input-group-text"><i class="fas fa-book"> Periodo</i></div>
						<input type="text" name="OINICIO" id="OINICIO" size="15" data-date-format="mm/yyyy"
							style="text-align:center" onchange="copia(this.value,'OFECHA');"
							onblur="copia(this.value,'OFECHA');tabla();" />
					</div>
				</div>

				<div class="form-group col-sm-12">
					<div class="input-group">
						<div class="input-group-text"><i class="fas fa-book"> Quincena</i></div>
						<select id="OQUINCENA" name="OQUINCENA" onchange="ver_tipo();" onblur="ver_tipo();">
							<option value="0">Seleccione</option>
							<option value="01">Primera</option>
							<option value="16">Segunda</option>
						</select>
						<div class="input-group-text" id="quincena"><input name="oquincena" type="checkbox" value="1"
								checked="checked" /><i class="fas fa"> Quincena</i></div>
						<div class="input-group-text" id="cesta"><input name="otickets" type="checkbox" value="1" /><i
								class="fas fa"> CestaTickets</i></div>
						<div class="input-group-text" id="vaca"><input name="ovacaciones" type="checkbox" value="1" /><i
								class="fas fa"> Vacaciones</i></div>
					</div>
				</div>

				<div class="form-group col-sm-8">
					<div class="input-group">
						<button type="button" id="boton" class="btn btn-outline-success waves-effect"
							onclick="generar_nomina();"><i class="fas fa-cloud-upload-alt prefix grey-text mr-1"></i>
							Generar Nomina</button>
						<div id="espera" class="btn btn-outline-info waves-effect" onclick="">Espere unos minutos para
							generar de nuevo la nomina...</div>
					</div>
				</div>
				<label>
					<input type="hidden" name="OFECHA" id="OFECHA" />
				</label>
				<br>
			</td>
		</tr>
	</table>
	<div id="div1"></div>
</form>
<script language="JavaScript">
	tabla(); $('#cesta').hide(); $('#vaca').hide();
	//---------------------
	function ver_tipo() {
		if ((document.form1.ONOMINA.value == '003' || document.form1.ONOMINA.value == '004' || document.form1.ONOMINA.value == '0300' || document.form1.ONOMINA.value == '0400' || document.form1.ONOMINA.value == '002' || document.form1.ONOMINA.value == '001') & document.form1.OQUINCENA.value == '16') { $('#cesta').show(); $('#vaca').show(); $('#bono').show(); }
		else { $('#cesta').hide(); $('#vaca').hide(); $('#bono').hide(); }
		//-------
		//if (document.form1.ONOMINA.value=='001')
		//	{ $('#cesta').hide(); }		
	}
	//---------------------
	<?php
	//-------------	
	$consultax = "SELECT * FROM a_actualizacion LIMIT 1;";
	$tablax = $_SESSION['conexionsql']->query($consultax);
	if ($tablax->num_rows > 0) {
		$registro = $tablax->fetch_object();
		$fechayhora_nomina = strtotime(date('Y-m-d H:i:s', strtotime($registro->nomina)));
		$actual = strtotime(date('Y-m-d H:i:00'));
		$minutos = (($actual - $fechayhora_nomina) / 60);
		if (abs($minutos) > 0) {
			?>
			$('#boton').show("slow"); $('#espera').hide("slow");
		<?php
		} else {
			?>
			setTimeout(function () {
				$('#boton').show("slow"); $('#espera').hide("slow");
			}, <?php echo ($minutos * 60); ?>000);
			$('#boton').hide("slow"); $('#espera').show();
		<?php
		}
	}
	?>
	//------------------
	function eliminar_nomina(num, boton) {
		$(boton).hide();
		$('#div1').html('<div align="center"><img width="125" height="125" src="images/espera(1).gif"/><br/>Espere mientras la lista es Recargada...</div>');
		var parametros = "num=" + num;
		$.ajax({
			type: 'POST',
			url: 'personal/1c_eliminar.php',
			dataType: "json",
			data: parametros,
			success: function (data) {
				if (data.tipo == "info") { alertify.success(data.msg); tabla(); }
				else { alertify.alert(data.msg); }
				//--------------
			}

		});
	}
	//---------------------
	function imprimir_sol(id, tipo) {
		if (tipo == "001" || tipo == "004" || tipo == "006" || tipo == "010") { window.open("personal/formatos/2_nomina.php?id=" + id + "&tipo=" + tipo, "_blank"); }
		if (tipo == "002") { window.open("personal/formatos/4_tickets.php?id=" + id, "_blank"); }
		if (tipo == "003" || tipo == "005") { window.open("personal/formatos/3_vacaciones.php?id=" + id + "&tipo=" + tipo, "_blank"); }
	}
	//---------------------
	function validar() {
		error = 0;
		if (document.form1.ONOMINA.value == "0") { document.form1.ONOMINA.focus(); alertify.alert("Debe indicar la Nomina a Generar!"); error = 1; }
		if (document.form1.OFECHA.value == "") { document.form1.OFECHA.focus(); alertify.alert("Debe indicar el Periodo a Generar!"); error = 1; }
		if (document.form1.OQUINCENA.value == "0") { document.form1.OQUINCENA.focus(); alertify.alert("Debe indicar la Quincena a Generar!"); error = 1; }
		return error;
	}
	//--------------------- PARA BUSCAR
	function tabla() {
		$('#div1').html('<div align="center"><div class="spinner-border" role="status"></div><br><strong>Un momento, por favor...</strong></div>');
		$('#div1').load('personal/1b_tabla.php?periodo=' + document.form1.OFECHA.value);
	}
	//------------------
	function generar_nomina() {
		if (validar() == 0) {
			//$('#boton').hide("slow"); //fadeOut
			alertify.alert('Espere mientras la Nomina ' + document.form1.ONOMINA.value + ' es Generada...');// 
			$('#div1').html('<div align="center"><img width="125" height="125" src="images/espera(1).gif"/><br/>Espere mientras la Nomina es Generada...</div>');
			var parametros = $("#form1").serialize();
			$.ajax({
				type: 'POST',
				url: 'personal/1a_guardar.php',
				dataType: "json",
				data: parametros,
				success: function (data) {
					if (data.tipo == "info") {
						alertify.success(data.msg); tabla();
						setTimeout(function () {
							$('#boton').show("slow"); $('#espera').hide("slow");
						}, 10);//150000
						$('#espera').show("slow");
					}//$('#boton').show("slow");
					else { alertify.alert(data.msg); }
					//--------------
				}

			});
		}
	}
	//------------------
	$("#OINICIO").datepicker2({
		format: "mm/yyyy",
		viewMode: "months",
		minViewMode: "months",
		autoclose: true
	});
</script>