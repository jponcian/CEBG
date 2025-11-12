<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');

if ($_SESSION['VERIFICADO'] != "SI") { 
header ("Location: ../validacion.php?opcion=val"); 
exit(); }

$acceso=80;
//------- VALIDACION ACCESO USUARIO
include_once "../validacion_usuario.php";
//-----------------------------------
?>
<form id="form999" name="form999" method="post" >
			<!-- Modal Header -->
<div class="modal-header bg-fondo text-center">
    <input type="hidden" id="oid" name="oid" value="0"/>
<h4 align="center" style="background-color:#0275d8; color:#FFFFFF" class="modal-title w-100 font-weight-bold py-2">Nueva Solicitud
<button type="button" class="close" data-dismiss="modal" onclick="buscar2();">&times;</button></h4>
</div>
<!-- Modal body -->
	
	<div class="row">
		<div class="form-group col-sm-12">
			<div class="input-group-text">Dirección Solicitante: 
			  <select class="custom-select" style="font-size: 14px" name="txt_area" id="txt_area">
					
					<?php
					//--------------------
					$consultx = "SELECT * FROM a_direcciones ORDER BY division;"; 
					$tablx = $_SESSION['conexionsql']->query($consultx);
					while ($registro_x = $tablx->fetch_object())
					//-------------
					{
					echo '<option ';
					echo ' value="';
					echo $registro_x->id;
					echo '">';
					echo ($registro_x->division);
					echo '</option>';
					}
					?>
					</select>
				</div>
		</div>	
	</div>	
	
	<div class="row">
		<div class="form-group col-sm-12">
			<div class="input-group-text">Zona: 
			  <select class="custom-select" style="font-size: 14px" name="txt_area" id="txt_area">
					
					<?php
					//--------------------
					$consultx = "SELECT * FROM a_zonas_viaticos ORDER BY id;"; 
					$tablx = $_SESSION['conexionsql']->query($consultx);
					while ($registro_x = $tablx->fetch_object())
					//-------------
					{
					echo '<option ';
					echo ' value="';
					echo $registro_x->id;
					echo '">';
					echo ($registro_x->zona)."(".($registro_x->ciudades).")";
					echo '</option>';
					}
					?>
					</select>
				</div>
		</div>	
	</div>
		
	<div class="row">
		
		<div class="form-group col-sm-5">
			<div class="input-group">
				<div class="input-group-text">Desde</div>
				<input onkeyup="saltar(event,'txt_fecha')" type="text" style="text-align:center" class="form-control " name="txt_desde" id="txt_desde" placeholder="Desde"  minlength="1" maxlength="10" value="<?php  echo date('d/m/Y');?>" required>
				<div class="input-group-text"><i class="far fa-calendar-alt"></i></div></div>
		</div>	
		
		<div class="form-group col-sm-5">
			<div class="input-group">
				<div class="input-group-text">Hasta</div>
				<input onkeyup="saltar(event,'txt_concepto')" type="text" style="text-align:center" class="form-control " name="txt_hasta" id="txt_hasta" placeholder="Hasta"  minlength="1" maxlength="10" onchange="combo0(this.value);" value="<?php  echo date('d/m/Y');?>" required><div class="input-group-text"><i class="far fa-calendar-alt"></i></div></div>
		</div>	
		
	</div>
			
	<div class="row">
		<div class="form-group col-sm-12">
<textarea id="txt_concepto" name="txt_concepto" placeholder="Motivo de la Comisión" class="form-control" rows="4" ></textarea></div>
	</div>

			<br>
<div align="center">			
	<button type="button" id="boton" class="btn btn-outline-success waves-effect" onclick="guardar_detalle(0)" ><i class="fas fa-save prefix grey-text mr-1"></i> Guardar</button>			
</div>
<br>
		</div>
<!-- Modal footer -->
<div class="modal-footer justify-content-center">
	<div align="center" id="div3">			

	</div>
</div>
<div class="modal-footer justify-content-center">
	<div align="center" id="div5">			

	</div>
</div>

</form>
<script language="JavaScript">
empleados();
function empleados(){
	$('#div3').html('<div align="center"><div class="spinner-border" role="status"></div><br><strong>Un momento, por favor...</strong></div>');
	$('#div3').load('viaticos/1a_tabla.php');
	$('#div5').html('<div align="center"><div class="spinner-border" role="status"></div><br><strong>Un momento, por favor...</strong></div>');
	$('#div5').load('viaticos/1c_tabla.php');
}
//--------------------- PARA BUSCAR
function buscar_factura(){
	//$('#cmdbuscar').hide();
	var parametros = "id=" + document.form999.txt_id_rif.value ;
	$.ajax({  
		type : 'POST',
		url  : 'viaticos/1k_buscar.php?tipo=1',
		data: parametros,
		dataType:"json",
		success:function(data) {  
			if (data.tipo=="alerta")
				{	alertify.alert(data.msg);	}
			}  
		});
}
//--------------------------------
//setTimeout(function()	{
//		$('#txt_rif').focus();
//		},1000)	
//--------------------------------
$("#txt_desde").datepicker();
$("#txt_hasta").datepicker();
//combo0('<?php  echo date('d/m/Y');?>');
//--------------------------------
</script>