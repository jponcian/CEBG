<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');

if ($_SESSION['VERIFICADO'] != "SI") { 
header ("Location: ../validacion.php?opcion=val"); 
exit(); }

$acceso=81;
//------- VALIDACION ACCESO USUARIO
include_once "../validacion_usuario.php";
//-----------------------------------
//$consultx = "SELECT * FROM rac WHERE rac = ".$_GET['id'].";";  //echo $consultx;
//$tablx = $_SESSION['conexionsql']->query($consultx);
//$registro = $tablx->fetch_object();
?>
<form id="form999" name="form999" method="post" >
			<!-- Modal Header -->
<div class="modal-header bg-fondo text-center">
	<input type="hidden" id="txt_id_rif" name="txt_id_rif" value="0" />
	<input type="hidden" id="txt_total" name="txt_total" value="" />
<h4 align="center" style="background-color:#0275d8; color:#FFFFFF" class="modal-title w-100 font-weight-bold py-2">Nueva Orden de Pago
  <button type="button" class="close" data-dismiss="modal" onclick="buscar2();">&times;</button></h4>
</div>
<!-- Modal body -->
		<div class="p-1">
			
	<div class="row">
		<div class="form-group col-sm-4">
			<div class="input-group">
				<div class="input-group-text" align="center">Rif</div>
				<input placeholder="Rif" id="txt_rif" maxlength="10" name="txt_rif" class="form-control" type="text" style="text-align:center" value="" onkeyup="buscar_proveedor1(event);" />
				<input name="" type="button" id="cmdbuscar" class="btn btn-outline-info blue light-3 btn-sm" onclick="buscar_proveedor1();" value="Buscar" />
			</div>
		</div>

		<div class="form-group col-sm-4">
			<div class="input-group">
				<div class="input-group-text"><i class="far fa-calendar-alt"></i></div>
				<input onkeyup="saltar(event,'txt_concepto')" type="text" style="text-align:center" class="form-control " name="txt_fecha" id="txt_fecha" placeholder="Fecha Solicitud"  minlength="1" maxlength="10" onBlur="combo0();" value="<?php  echo date('d/m/Y');?>" required></div>
			
		</div>
		
		<div class="form-group col-sm-4">
			<div class="input-group">
				<div class="input-group-text"><i class="far fa-calendar-alt"></i></div>
				<select class="custom-select" style="font-size: 14px" name="txt_categoria" id="txt_categoria" onchange="tabla(this.value);">
			<option value="0">Seleccione</option>
			</select></div>
		</div>

		</div>	

<div class="row">

		<div class="form-group col-sm-12">
				<input id="txt_nombres" placeholder="Sujeto Pasivo" name="txt_nombres" class="form-control" type="text" style="text-align:center" readonly=""/>
		</div>
</div>
			
	<div class="row">
		<div class="form-group col-sm-12">
			<textarea id="txt_concepto" name="txt_concepto" placeholder="Escribe aqui el Concepto" class="form-control" rows="4" ></textarea>
		</div>
	</div>
				
	</div>

<!-- Modal footer -->
<div class="modal-footer justify-content-center">
	<div align="center" id="div3">			

	</div>
</div>

</form>
<script language="JavaScript">
//-------------
function combo0()
{ 
	$.ajax({
        type: "POST",
        url: 'administracion/18f_combo.php?rif='+document.form999.txt_rif.value+'&fecha='+document.form999.txt_fecha.value,
        success: function(resp){
            $('#txt_categoria').html(resp);
            $('#txt_categoria').focus();
        }
    });
}
//--------------------------------------------
$('#cmdbuscar').hide();
//*--------------------- PARA BUSCAR
function tabla(){
	$('#div3').html('<div align="center"><div class="spinner-border" role="status"></div><br><strong>Un momento, por favor...</strong></div>');
	$('#div3').load('administracion/18d_tabla.php?id='+document.form999.txt_categoria.value+'&id_cont='+document.form999.txt_id_rif.value+'&fecha='+document.form999.txt_fecha.value);
}
//--------------------------- PARA GUARDAR
function buscar_proveedor1(e)
 	 {
	 (e.keyCode)?k=e.keyCode:k=e.which;
	// Si la tecla pulsada es enter (codigo ascii 13)
	if(k==13)
		{	buscar_proveedor();	}
	}
//--------------------------------
setTimeout(function()	{
		$('#txt_rif').focus();
		//document.form999.txt_rif.focus();
		//alertify.alert('hola');
		},500)	
//--------------------------------
$("#txt_fecha").datepicker();
//--------------------------------
</script>