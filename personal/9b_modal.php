<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');

if ($_SESSION['VERIFICADO'] != "SI") { 
header ("Location: ../validacion.php?opcion=val"); 
exit(); }

$acceso=86;
//------- VALIDACION ACCESO USUARIO
include_once "../validacion_usuario.php";
//-----------------------------------
$id = decriptar($_GET['id']);
$consultx = "SELECT * FROM rac WHERE cedula='".$id."';";  //echo $consultx;
$tablx = $_SESSION['conexionsql']->query($consultx);
$registro = $tablx->fetch_object();
?>
<form id="form999" name="form999" method="post" >
			<!-- Modal Header -->
<div class="modal-header bg-fondo text-center">
	<h4 align="center" style="background-color:#0275d8; color:#FFFFFF" class="modal-title w-100 font-weight-bold py-2">SOLICITUD DE VACACIONES
	  <button type="button" class="close" data-dismiss="modal">&times;</button></h4>
</div>
<!-- Modal body -->
	<br>
	<div class="row" >
		<div class="form-group col-sm-12 ml-5">
			<div class="input-group">
				<div class="input-group-text" >Funcionario</div>
				<select class="select2" style="width: 500px" placeholder="Seleccione el Funcionario" name="txt_ci" id="txt_ci" >
				<?php
				$consultx = "SELECT cedula, CONCAT(rac.nombre,' ',rac.nombre2,' ',rac.apellido,' ',rac.apellido2) as nombre FROM rac WHERE suspendido = '0' AND nomina <> 'EGRESADOS' AND nomina <> 'PASANTES' AND nomina <> 'COMISION' AND vacaciones>0 ORDER BY cedula, nombre"; 
				$tablx = $_SESSION['conexionsql']->query($consultx);
				while ($registro_x = $tablx->fetch_array())
					{
					echo '<option value='.$registro_x['cedula'];
					echo '>'.$registro_x['cedula'].' - '.$registro_x['nombre'].'</option>';
					}
				?></select>	
			</div>
		</div>
	</div>	
	
	<div align="center"><table width="80%" border="1" align="center">
		<tr >
			<td colspan="3" align="center"><strong><h6>Fecha</h6></strong></td>
			<td colspan="2" align="center"><strong><h6>Dias</h6></strong></td>
			<td rowspan="2" align="center"></td>
		</tr>
		<tr >
<td width="22%" align="center"><input placeholder="Desde" id="txt_desde" maxlength="10" name="txt_desde" class="form-control" type="text" style="text-align:center" value="" /></td>
<td width="22%" align="center"><input placeholder="Hasta" readonly id="txt_hasta" maxlength="10" name="txt_hasta" class="form-control" type="text" style="text-align:center"/></td>
<td width="22%" align="center"><input placeholder="Incorporacion" readonly id="txt_incorporacion" maxlength="10" name="txt_incorporacion" class="form-control" type="text" style="text-align:center"/></td>
<td align="center"><input placeholder="Habiles" id="ohabiles2" maxlength="3" name="ohabiles2" class="form-control" type="text" style="text-align:center" value=""  /></td>
<td align="center"><input placeholder="Calendario" id="ocalendario2" maxlength="3" name="ocalendario2" class="form-control" type="text" style="text-align:center" value=""  /></td>
		</tr>
	</table></div>
	<br>

	<div class="row" >
		<div class="form-group col-sm-8 ml-5">
			<div class="input-group">
				<div class="input-group-text" >Periodo a Disfrutar</div>
				<input  onkeyup="saltar(event,'txt_observacion')" placeholder="Periodo a Disfrutar" id="txt_periodo" name="txt_periodo" class="form-control" type="text" style="text-align:left" />
			</div>
		</div>
	</div>	

	<div class="row" >
		<div class="form-group col-sm-10 ml-5">
			<div class="input-group">
				<textarea placeholder="Observaciones" id="txt_observacion" maxlength="255" name="txt_observacion" class="form-control" type="text" style="text-align:left"></textarea>
			</div>
		</div>
	</div>	

<br>
<!-- Modal footer -->
<div class="modal-footer justify-content-center">
<a data-toggle="tooltip" title="Generar Vacaciones"><button id="btn_vacacion" type="button" class="btn btn-outline-success blue light-3 btn-sm" onclick="generar_vacaciones();"><i class="fas fa-save prefix grey-text mr-1"></i>Generar</button></a>
</div>

</form>
<script language="JavaScript">
$(document).ready(function() {
    $('.select2').select2();
	//----------------
});
//------------------
function generar_vacaciones(id)
	{
	$('#btn_vacacion').hide();
	var parametros = $("#form999").serialize(); 
	$.ajax({  
		type : 'POST',
		url  : 'personal/9e_guardar.php',
		dataType:"json",
		data:  parametros, 
		success:function(data) {  	
			if (data.tipo=="info")
				{	alertify.success(data.msg);	
				 	window.open("personal/formatos/14_vacaciones.php?id="+data.id,"_blank");
					$('#modal_largo .close').click();
				}
			else
				{	$('#btn_vacacion').show(); alertify.alert(data.msg);	}	
			//--------------
			} 
		});
	}
//------------------
function fechas(fecha)
{
var parametros = $("#form999").serialize(); 
$.ajax({  
	type : 'POST',
	url  : 'personal/9a_fechas.php',
	dataType:"json",
	data:  parametros, 
	success:function(data) {  	
		if (data.tipo=="info")
			{	
				document.form999.txt_incorporacion.value	= data.incorporacion;
				document.form999.ohabiles2.value	= data.habiles;
				document.form999.ocalendario2.value	= data.continuos;
			}
		else
			{	alertify.alert(data.msg);	}
		//--------------
		} 
	});
}
//------------------------
$('#txt_desde').dateRangePicker({
	//startDate: moment().format("DD-MM-YYYY"),
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
		fechas(s2);
		}
});
</script>