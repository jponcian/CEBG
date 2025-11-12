<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');

if ($_SESSION['VERIFICADO'] != "SI") { 
header ("Location: ../validacion.php?opcion=val"); 
exit(); }

$acceso='111';
//------- VALIDACION ACCESO USUARIO
include_once "../validacion_usuario.php";
//-----------------------------------
//$id = decriptar($_GET['id']);
//$consultx = "SELECT * FROM rac WHERE cedula='".$id."';";  //echo $consultx;
//$tablx = $_SESSION['conexionsql']->query($consultx);
//$registro = $tablx->fetch_object();
?>
<form id="form999" name="form999" method="post" >
			<!-- Modal Header -->
<div class="modal-header bg-fondo text-center">
	<h4 align="center" style="background-color:#0275d8; color:#FFFFFF" class="modal-title w-100 font-weight-bold py-2">GENERAR COMISION
	  <button onclick="busca_empleados();" type="button" class="close" data-dismiss="modal">&times;</button></h4>
</div>
<!-- Modal body -->
	<br>
	<div class="row" >
		<div class="form-group col-sm-12 ml-5">
			<div class="btn-group btn-group-toggle" data-toggle="buttons">
				<label class="btn btn-primary active">
					<input checked type="radio" name="txt_opcion" id="txt_opcion" value="COMISION" autocomplete="off"> COMISION
				</label>
<!--
				<label class="btn btn-primary">
					<input type="radio" name="txt_opcion" id="txt_opcion" value="REPOSO" autocomplete="off"> REPOSO
				</label>
-->
			</div>
		</div>
	</div>	
	<div class="row" >
		<div class="form-group col-sm-12 ml-5">
			<div class="input-group">
				<div class="input-group-text" >Funcionario</div>
				<select onChange="validar_campo('txt_ci')" class="select2" style="width: 500px" placeholder="Seleccione el Funcionario" name="txt_ci" id="txt_ci" >
				<?php $direccion = $_SESSION["direccion"];
				$consultx = "SELECT cedula, CONCAT(rac.nombre,' ',rac.nombre2,' ',rac.apellido,' ',rac.apellido2) as  nombre FROM rac WHERE id_div=$direccion AND suspendido = '0' AND nomina <> 'EGRESADOS' ORDER BY cedula, nombre"; 
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
	
	<div class="form-group col-sm-12 ml-5"><table width="80%" border="1" align="center">
		<tr >
			<td colspan="3" align="center"><strong><h4><span class="badge badge-primary">FECHAS</span></h4></strong></td>
		</tr>
		<tr >
			<td align="center"><strong><h6>Desde</h6></strong></td>
			<td align="center"><strong><h6>Hasta</h6></strong></td>
			<td align="center"><strong><h6>Incorporación</h6></strong></td>
		</tr>
		<tr >
<td width="30%" align="center"><input onChange="validar_campo('txt_desde')" autocomplete="off" placeholder="Desde" id="txt_desde" name="txt_desde" class="form-control" type="text" style="text-align:center" value="" /></td>
<td width="30%" align="center"><input onChange="validar_campo('txt_hasta')" placeholder="Hasta" readonly id="txt_hasta" name="txt_hasta" class="form-control" type="text" style="text-align:center"/></td>
<td width="22%" align="center"><input onChange="validar_campo('txt_incorporacion')" placeholder="Incorporacion" readonly id="txt_incorporacion"  name="txt_incorporacion" class="form-control" type="text" style="text-align:center"/></td>
		</tr>
	</table></div>

	<div class="form-group col-sm-12 ml-5"><table width="80%" border="1" align="center">
		<tr >
			<td colspan="2" align="center"><strong><h5><span class="badge badge-primary">Cantidad de Dias</span></h5></strong></td>
		</tr>
		<tr >
<td align="center"><input onChange="validar_campo('ohabiles2')" placeholder="Habiles" id="ohabiles2" maxlength="3" name="ohabiles2" class="form-control" type="text" style="text-align:center" value=""  /></td>
<td align="center"><input onChange="validar_campo('ocalendario2')" placeholder="Calendario" id="ocalendario2" maxlength="3" name="ocalendario2" class="form-control" type="text" style="text-align:center" value=""  /></td>
		</tr>
	</table></div>
	<br>

<!--
	<div class="row" >
		<div class="form-group col-sm-8 ml-5">
			<div class="input-group">
				<div class="input-group-text" >Periodo a Disfrutar</div>
				<input  onkeyup="saltar(event,'txt_observacion')" placeholder="Periodo a Disfrutar" id="txt_periodo" name="txt_periodo" class="form-control" type="text" style="text-align:left" />
			</div>
		</div>
	</div>	
-->

	<div class="row" >
		<div class="form-group col-sm-10 ml-5">
			<div class="input-group">
				<textarea onChange="validar_campo('txt_observacion')" placeholder="Observaciones" id="txt_observacion" maxlength="3000" name="txt_observacion" class="form-control" type="text" style="text-align:left"></textarea>
			</div>
		</div>
	</div>	

<!--
	<div class="row" >
		<div class="form-group col-sm-10 ml-5">
			<div class="input-group">
				<textarea placeholder="Anexos, Soportes, Constancias, etc..." id="txt_anexos" maxlength="3000" name="txt_anexos" class="form-control" type="text" style="text-align:left"></textarea>
			</div>
		</div>
	</div>	
-->

<br>
<!-- Modal footer -->
<div class="modal-footer justify-content-center">
<a data-toggle="tooltip" title="Generar Vacaciones"><button id="btn_vacacion" type="button" class="btn btn-outline-success blue light-3 btn-sm" onclick="generar_vacaciones();"><i class="fas fa-save prefix grey-text mr-1"></i>Generar</button></a>
</div>

</form>
<script language="JavaScript">
$(document).ready(function() {
    $('.select2').select2();
});
//------------------------
$("#txt_incorporacion").datetimepicker({language: 'es', timepicker: true, datepicker:true, format:'d/m/Y H:i', step:30, hours12:true, yearStart:<?php echo date('Y') ; ?>, yearEnd: <?php echo date('Y')+1; ?>, disabledWeekDays: [0,6], allowTimes:['08:00','08:30','09:00','09:30','10:00','10:30','11:00','11:30','01:00','01:30','02:00','02:30','03:00','03:30']});
//----------------- PARA VALIDAR
function validar_detalle()
	{
	error = 0;
	if(document.form999.txt_observacion.value=="")
		{	 
			validar_campo('txt_observacion'); 
			document.form999.txt_observacion.focus(); 	
		 	alertify.error("Debe Indicar el motivo del permiso!");			
		 	error = 1;  }
	if(document.form999.txt_desde.value=="" || document.form999.txt_hasta.value=="" || document.form999.txt_incorporacion.value=="")
		{	 
			validar_campo('txt_desde'); 
			validar_campo('txt_hasta'); 
			validar_campo('txt_incorporacion'); 
			document.form999.txt_desde.focus(); 	
			alertify.error("Debe Indicar las Fechas Correspondientes!");			
			error = 1;  }
	return error;
	}
//------------------
function generar_vacaciones(id)
{
 if (validar_detalle()==0)
	{
//	$('#btn_vacacion').hide();
	var parametros = $("#form999").serialize(); 
	$.ajax({  
		type : 'POST',
		url  : 'personal/29e_guardar.php',
		dataType:"json",
		data:  parametros, 
		success:function(data) {  	
			if (data.tipo=="info")
				{	alertify.success(data.msg);	
//				 	if (data.formato=="PERMISO")
//						{	window.open("personal/formatos/13_permiso.php?id="+data.id,"_blank");	}
//					else
//						{	window.open("personal/formatos/15_reposo.php?id="+data.id,"_blank");	}					
				 $('#modal_largo .close').click();
				}
			else
				{	$('#btn_vacacion').show(); alertify.alert(data.msg);	}	
			//--------------
			} 
		});
	}
}
//------------------
function fechas(fecha)
{
var parametros = $("#form999").serialize(); 
$.ajax({  
	type : 'POST',
	url  : 'personal/10a_fechas.php',
	dataType:"json",
	data:  parametros, 
	success:function(data) {  	
		if (data.tipo=="info")
			{	
				document.form999.txt_incorporacion.value	= data.incorporacion;
				document.form999.ohabiles2.value	= data.habiles;
				document.form999.ocalendario2.value	= data.continuos;
				validar_campo('txt_desde'); 
				validar_campo('txt_hasta'); 
				validar_campo('txt_incorporacion'); 
			}
		else
			{	alertify.alert(data.msg);	}
		//--------------
		} 
	});
}	
//------------------------
$('#txt_desde').dateRangePicker({
//	startDate: moment().format("DD-MM-YYYY hh:mm A"),
	autoClose: true,
	format: 'DD-MM-YYYY hh:mm A',
	defaultTime: moment('<?php echo date('Y/m/d'); ?> 08:00', moment.defaultFormat).toDate(),
	defaultEndTime: moment('<?php echo date('Y/m/d'); ?> 16:00', moment.defaultFormat).toDate(),
	language:	'es',
	extraClass: 'date-range-picker19',
	separator : ' al ',
	time: {
		enabled: true
	},
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