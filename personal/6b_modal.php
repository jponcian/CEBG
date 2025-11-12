<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');

if ($_SESSION['VERIFICADO'] != "SI") { 
header ("Location: ../validacion.php?opcion=val"); 
exit(); }

$acceso=16;
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
	<h4 align="center" style="background-color:#0275d8; color:#FFFFFF" class="modal-title w-100 font-weight-bold py-2">Expediente 
	  <button type="button" class="close" data-dismiss="modal">&times;</button></h4>
    <input type="hidden" id="oid" name="oid" value="<?php echo $id; ?>"/>
</div>
<!-- Modal body -->
	<div align="center"><table width="80%" border="1" align="center">
		<tr height="45">
			<td colspan="3"><strong><h5><?php echo ($registro->cedula) .' '. ($registro->nombre); ?></h5></strong></td>
		</tr>
		<tr height="45">
			<td width="33%" align="center"><a data-toggle="tooltip" title="Recibo de Pago"><button type="button" class="btn btn-outline-info blue light-3 btn-sm" data-toggle="modal" onclick="recibo('<?php echo encriptar($registro->cedula); ?>');">Recibo de Pago</button></a></td>
			<td width="33%" align="center"><a data-toggle="tooltip" title="Constancia de Trabajo"><button type="button" class="btn btn-outline-info blue light-3 btn-sm" data-toggle="modal" onclick="trabajo('<?php echo encriptar($registro->cedula); ?>');">Constancia de Trabajo</button></a></td>
			<td width="33%" align="center"><a data-toggle="tooltip" title="ARC"><button type="button" class="btn btn-outline-info blue light-3 btn-sm" data-toggle="modal" onclick="arc('<?php echo encriptar($registro->cedula); ?>');">A.R.C.</button></a></td>
		</tr>
<!--
		<tr>
			<td width="33%" align="center"><a data-toggle="tooltip" title="Solicitar Permiso"><button type="button" id="btn_spermiso" class="btn btn-outline-primary blue light-3 btn-sm" data-toggle="modal" onclick="solicitar(1);">Solicitar Permiso</button></a></td>
			<td width="33%" align="center"><a data-toggle="tooltip" title="Solicitar Vacaciones"><button type="button" id="btn_svacacion" class="btn btn-outline-primary blue light-3 btn-sm" data-toggle="modal" onclick="solicitar(2);">Solicitar Vacaciones</button></a></td>
		</tr>
-->
	</table></div>
	<br>
	<div align="center" id="div_permiso"><table width="80%" border="1" align="center">
		<tr >
			<td colspan="6" align="center"><strong><h6>SOLICITUD DE PERMISO</h6></strong></td>
		</tr>		
		<tr >
			<td colspan="2" align="center"><strong><h6>Fecha</h6></strong></td>
			<td colspan="2" align="center"><strong><h6>Dias</h6></strong></td>
			<td rowspan="2" align="center"></td>
		</tr>
		<tr >
<td width="30%" align="center"><input placeholder="Seleccione la Fecha y Hora" id="OINICIO" maxlength="16" name="OINICIO" class="form-control" type="text" style="text-align:left" value="<?php echo date('d/m/Y') ; ?> 08:00" onChange="fechas();" /></td>
<td width="30%" align="center"><input placeholder="Selecciones la Fecha y Hora" id="OFIN" maxlength="16" name="OFIN" class="form-control" type="text" style="text-align:left" value="<?php echo voltea_fecha(sube_dia(date('Y/m/d'))) ; ?> 08:00" onChange="fechas();" /></td>
<td align="center"><input placeholder="Habiles" id="ohabiles" maxlength="3" name="ohabiles" class="form-control" type="text" style="text-align:center" value=""  /></td>
<td align="center"><input placeholder="Calendario" id="ocalendario" maxlength="3" name="ocalendario" class="form-control" type="text" style="text-align:center" value=""  /></td>
<td align="center"><a data-toggle="tooltip" title="Generar Permiso"><button id="btn_permiso" type="button" class="btn btn-outline-primary blue light-3 btn-sm" onclick="guardar_permiso('<?php echo encriptar($id ); ?>',1);">Generar</button></a></td>
		</tr>
		<tr >
<td colspan="6"><textarea placeholder="Motivo del Permiso" id="opermiso" maxlength="255" name="opermiso" class="form-control" type="text" style="text-align:left"></textarea></td>
		</tr>
	</table></div>
	
	<div align="center" id="div_vacacion"><table width="80%" border="1" align="center">
		<tr >
			<td colspan="7" align="center"><strong><h6>SOLICITUD DE VACACIONES</h6></strong></td>
		</tr>		
		<tr >
			<td colspan="3" align="center"><strong><h6>Fecha</h6></strong></td>
			<td colspan="2" align="center"><strong><h6>Dias</h6></strong></td>
			<td rowspan="2" align="center"></td>
		</tr>
		<tr >
<td width="22%" align="center"><input placeholder="Inicio" id="txt_desde" maxlength="10" name="txt_desde" class="form-control" type="text" style="text-align:center" value="" onChange="fechas2(this.value);" /></td>
<td width="22%" align="center"><input placeholder="Fin" readonly id="txt_hasta" maxlength="10" name="txt_hasta" class="form-control" type="text" style="text-align:center"/></td>
<td width="22%" align="center"><input placeholder="Incorporacion" readonly id="txt_incorporacion" maxlength="10" name="txt_incorporacion" class="form-control" type="text" style="text-align:center"/></td>
<td align="center"><input placeholder="Habiles" id="ohabiles2" maxlength="3" name="ohabiles2" class="form-control" type="text" style="text-align:center" value=""  /></td>
<td align="center"><input placeholder="Calendario" id="ocalendario2" maxlength="3" name="ocalendario2" class="form-control" type="text" style="text-align:center" value=""  /></td>
<td align="center"><a data-toggle="tooltip" title="Generar Vacaciones"><button id="btn_vacacion" type="button" class="btn btn-outline-primary blue light-3 btn-sm" onclick="guardar_permiso('<?php echo encriptar($id ); ?>',2);">Generar</button></a></td>
		</tr>
		<tr >
		</tr>
	</table></div>
<br>
<!-- Modal footer -->
<div class="modal-footer justify-content-center">
			
<div id="div3">
</div>
			
</div>

</form>
<script language="JavaScript">
$('#div_permiso').hide();
$('#btn_permiso').hide();
$('#div_vacacion').hide();
$('#btn_vacacion').hide();
tabla1('<?php echo encriptar($id ); ?>');
//------------------------
function solicitar(tipo)
{ if (tipo==1)
	{
	$('#btn_spermiso').hide("slow");
	$('#div_vacacion').hide("slow");
	$('#div_permiso').show("slow");
	$('#btn_svacacion').show("slow");
	}
else
	{
	$('#btn_svacacion').hide("slow");
	$('#div_permiso').hide("slow");
	$('#div_vacacion').show("slow");
	$('#btn_spermiso').show("slow");
	} 
}
//------------------------
function tabla1(id)
{ $('#div3').load('personal/6c_tabla.php?id='+id); }
//------------------
function guardar_permiso(id, tipo)
	{
	$('#btn_permiso').hide();
	$('#btn_vacacion').hide();
	var parametros = $("#form999").serialize(); 
	$.ajax({  
		type : 'POST',
		url  : 'personal/6e_guardar.php?tipo='+tipo+'&id='+id,
		dataType:"json",
		data:  parametros, 
		success:function(data) {  	
			if (tipo==1)
				{
				if (data.tipo=="info")
					{	$('#div_permiso').hide();
						$('#btn_spermiso').show("slow");
						alertify.success(data.msg);	tabla1(id); }
				else
					{	alertify.alert(data.msg);	}	
				}
			else
				{
				if (data.tipo=="info")
					{	$('#div_vacacion').hide();
						$('#btn_svacacion').show("slow");
						alertify.success(data.msg);	tabla1(id); }
				else
					{	alertify.alert(data.msg);	}	
				}
			//--------------
			} 
		});
	}
//------------------
function fechas()
{
var parametros = $("#form999").serialize(); 
$.ajax({  
	type : 'POST',
	url  : 'personal/6d_fechas.php',
	dataType:"json",
	data:  parametros, 
	success:function(data) {  	
		if (data.tipo=="info")
			{	//alertify.success(data.msg);	
				document.form999.ocalendario.value	= data.continuos;
				document.form999.ohabiles.value	= data.habiles;
				if (data.habiles>=0)
					{
					$('#btn_permiso').show("slow");
					}
				else
					{
					$('#btn_permiso').hide();
					}
			}
		else
			{	alertify.alert(data.msg);	$('#btn_permiso').hide(); }
		//--------------
		} 
	});
}//
//$("#txt_desde").datepicker();
//------------------
function fechas2(fecha)
{
var parametros = $("#form999").serialize(); 
$.ajax({  
	type : 'POST',
	url  : 'personal/6d_fechas2.php',
	dataType:"json",
	data:  parametros, 
	success:function(data) {  	
		if (data.tipo=="info")
			{	//alertify.success(data.msg);	
				document.form999.txt_hasta.value	= data.fin;
				document.form999.txt_incorporacion.value	= data.incorporacion;
				document.form999.ohabiles2.value	= data.habiles;
				document.form999.ocalendario2.value	= data.continuos;
				if (data.habiles>0)
					{
					$('#btn_vacacion').show("slow");
					}
				else
					{
					$('#btn_vacacion').hide();
					}
			}
		else
			{	alertify.alert(data.msg);	$('#btn_permiso').hide(); }
		//--------------
		} 
	});
}
//------------------------------ 
function aprobar(id, cedula)
	{
	alertify.confirm("Estas seguro de aprobar el Permiso?",  
	function()
			{ 
			var parametros = "id=" + id;
			$.ajax({
			url: "personal/6g_aprobar.php",
			type: "POST",
			data: parametros,
			success: function(r) {
			alertify.success('Permiso Aprobado Correctamente');
			//--------------
			tabla1(cedula);
			}
			});
		});
}
//------------------------------ PARA ELIMINAR
function eliminar(id, cedula)
	{
	alertify.confirm("Estas seguro de eliminar el Registro?",  
	function()
			{ 
			var parametros = "id=" + id;
			$.ajax({
			url: "personal/6f_eliminar.php",
			type: "POST",
			data: parametros,
			success: function(r) {
			alertify.success('Registro Eliminado Correctamente');
			//--------------
			tabla1(cedula);
			}
			});
		});
}
//--------
//$('#txt_desde').datetimepicker({timepicker: false, datepicker:true, format:'d/m/Y', yearStart:<?php //echo date('Y') ; ?>, yearEnd: <?php //echo date('Y')+1; ?>, theme:'dark', disabledWeekDays: [0,6]});
//$('#OINICIO').datetimepicker({timepicker: true, datepicker:true, format:'d/m/Y H:i', step:30, hours12:true, yearStart:<?php //echo date('Y') ; ?>, yearEnd: <?php //echo date('Y')+1; ?>, theme:'dark', disabledWeekDays: [0,6], allowTimes:['08:00','08:30','09:00','09:30','10:00','10:30','11:00','11:30','02:00','02:30','03:00','03:30','04:00','04:30']});
//$('#OFIN').datetimepicker({timepicker: true, datepicker:true, format:'d/m/Y H:i', step:30, hours12:true, yearStart:<?php //echo date('Y') ; ?>, yearEnd: <?php //echo date('Y')+1; ?>, theme:'dark', disabledWeekDays: [0,6], allowTimes:['08:00','08:30','09:00','09:30','10:00','10:30','11:00','11:30','02:00','02:30','03:00','03:30','04:00','04:30','05:00']});
</script>