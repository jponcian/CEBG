<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');

if ($_SESSION['VERIFICADO'] != "SI") { 
header ("Location: ../validacion.php?opcion=val"); 
exit(); }

$acceso=90;
//------- VALIDACION ACCESO USUARIO
include_once "../validacion_usuario.php";
//-----------------------------------
?>
<form id="form1" name="form1" method="post" onsubmit="return evitar();" >
<br>
<!--<div  class="text-right mb-3"><a href="" class="btn btn-outline-primary btn-rounded btn-sm font-weight-bold" onclick="agregar();" data-toggle="modal" data-target="#modal_normal" data-keyboard="false"><i class="fas fa-plus-circle" ></i> Agregar METAS</a></div>-->
<!-- <br>-->
<br>
<div class="p-1">
	<div class="row">
		<div class="form-group col-sm-12">
			<div class="input-group">
				<div class="input-group-text">Plan Operativo Anual:</div>
				<select class="select2" style="width: 200px; font-size: 14px" name="txt_anno" id="txt_anno" onChange="representantes();">
					<?php
					$i = date ('Y')+1;
					while ($i>=2022)
					//-------------
					{
					echo '<option ';
						if ($i == date ('Y')) { echo 'selected';}
					echo ' value="';
					echo $i;
					echo '">Ejercicio ';
					echo $i;
					echo '</option>';
					$i--;
					}
					?>
				</select>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="form-group col-sm-12">
			<div class="input-group">
				<div class="input-group-text">Unidad Ejecutora:</div>
				<select class="select2" style="width: 600px; font-size: 14px" name="txt_unidad1" id="txt_unidad1" onChange="buscar();">
				<option value="0">--- Seleccione ---</option>
				</select>
			</div>
		</div>
	</div>
	
	<div class="row">
		<div class="form-group col-sm-5">
			<div class="input-group">
				<div class="input-group-text">Gestion:</div>
				<input placeholder="Desde" id="txt_desde" maxlength="10" name="txt_desde" class="form-control" type="text" style="text-align:center" value="" />
				<input placeholder="Hasta" readonly id="txt_hasta" maxlength="10" name="txt_hasta" class="form-control" type="text" style="text-align:center"/>
			</div>
		</div>
	</div>
</div>
	
<div id="div2"></div><br/>
</form>
<script language="JavaScript">
// PARA EL SELECT2
$(document).ready(function() {
    $('.select2').select2();
	representantes();
});
//-------------
function ver_meta(id)
	{
	$('#modal_xl').html('<div align="center"><div class="spinner-border" role="status"></div><br><strong>Un momento, por favor...</strong></div>');
	$('#modal_xl').load('poa/4b_modal.php?id='+id);
	}
//-------------
function representantes() {
    $.ajax({
        type: "POST",
        url: 'poa/4c_combo.php?anno=' +document.form1.txt_anno.value,
        success: function(resp) {
            $('#txt_unidad1').html(resp);
        }
    });
}
//-------------
function buscar()
	{
	$('#div2').html('<div align="center"><div class="spinner-border" role="status"></div><br><strong>Un momento, por favor...</strong></div>');
	$('#div2').load('poa/4a_tabla.php?anno='+document.form1.txt_anno.value + '&rep='+document.form1.txt_unidad1.value + '&s1='+document.form1.txt_desde.value + '&s2='+document.form1.txt_hasta.value);
	}
//------------------------
$('#txt_desde').dateRangePicker({
//	startDate: moment().format("DD-MM-YYYY"),
	autoClose: true,
	format: 'DD-MM-YYYY',
	language:	'es',
	extraClass: 'date-range-picker19',
	separator : ' al ',
	getValue: function()
		{
		if ($('#txt_desde').val() && $('#txt_hasta').val() )
			return $('#txt_desde').val() + ' al ' + $('#txt_hasta').val();
		else
			return '';
		},
	setValue: function(s,s1,s2)
		{
		$('#txt_desde').val(s1);
		$('#txt_hasta').val(s2);
		buscar();
		}
});
</script>