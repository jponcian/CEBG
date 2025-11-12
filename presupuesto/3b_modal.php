<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');

if ($_SESSION['VERIFICADO'] != "SI") { 
header ("Location: ../validacion.php?opcion=val"); 
exit(); }

$acceso=67;
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
    <input type="hidden" id="oid" name="oid" value="0"/>
	<input type="hidden" id="txt_id_rif" name="txt_id_rif" value="0" style="text-align:center" />
<h4 align="center" style="background-color:#0275d8; color:#FFFFFF" class="modal-title w-100 font-weight-bold py-2">Registrar Decretos (Creditos Adicionales)
  <button type="button" class="close" data-dismiss="modal" onclick="buscar();">&times;</button></h4>
</div>
<!-- Modal body -->
		<div class="p-1">
			
	<!--<div class="row">
		<div class="form-group col-sm-4">
			<div class="input-group">
				<div class="input-group-text" align="center">Rif</div>
				<input placeholder="Rif" id="txt_rif" maxlength="10" name="txt_rif" class="form-control" type="text" style="text-align:center" value="" onchange="buscar_proveedor();" onkeyup="saltar(event,'txt_factura')" />
				<input name="" type="button" id="cmdbuscar" class="btn btn-outline-info blue light-3 btn-sm" onclick="buscar_proveedor();" value="Buscar" />
			</div>
		</div>

			<div class="form-group col-sm-8">
					<input id="txt_nombres" placeholder="Proveedor" name="txt_nombres" class="form-control" type="text" style="text-align:center" readonly=""/>
			</div>
		</div>-->	

	<div class="row">
		<!--<div class="form-group col-sm-4">
			<div class="input-group"><div class="input-group-text"><i class="fas fa-file-invoice"></i></div>
				<input onkeyup="saltar(event,'txt_control')" type="text" style="text-align:center" class="form-control " name="txt_factura" id="txt_factura" placeholder="Numero Factura"  minlength="1" maxlength="10" required></div>
			
		</div>	-->
		
		<div class="form-group col-sm-4">
			<div class="input-group"><div class="input-group-text"><i class="fas fa-file-alt"></i></div>
				<input onkeyup="saltar(event,'txt_fecha')" type="text" style="text-align:center" class="form-control " name="txt_control" id="txt_control" placeholder="Numero" onchange="buscar_orden();" onkeypress="return SoloNumero(event,this)" minlength="1" maxlength="5" required></div>
			
		</div>	
		
		<div class="form-group col-sm-4">
			<div class="input-group">
				<div class="input-group-text"><i class="far fa-calendar-alt"></i></div>
				<input onkeyup="saltar(event,'txt_concepto')" type="text" style="text-align:center" class="form-control " name="txt_fecha" id="txt_fecha" placeholder="Fecha" minlength="1" maxlength="10" onchange="buscar_orden();combo0(this.value);" value="<?php  echo date('d/m/Y');?>" required></div>
		</div>	

		<div class="form-group col-sm-4">
			<div class="input-group">
				<div class="input-group-text">Tipo:</div>
				<select class="custom-select" style="font-size: 14px" name="txt_tipo" id="txt_tipo">
				<option value="1">Credito Adicional</option>
				<option value="2">Ingresos Propios</option>
				<option value="3">Reservas del Tesoro</option>
				</select></div>
		</div>

	</div>
			

	<div class="row">
		<div class="form-group col-sm-12">
			<div class="input-group-text"><i class="fas fa-university mr-2"></i>
			<textarea id="txt_concepto" name="txt_concepto" placeholder="Escribe aqui el Concepto" class="form-control" rows="4" ></textarea>
			</div>
		</div>
	</div>
			
			<div class="row">
				
				<div class="form-group col-sm-5">
					<select class="select2" style="width: 320px" name="txt_categoria" id="txt_categoria" onchange="combo(this.value);">
					<option value="0">Seleccione la Actividad</option>
					</select>
				</div>

				<div class="form-group col-sm-7">
					<select class="select2" style="width: 430px" name="txt_partida" id="txt_partida" onchange="partida(this.value)">
					<option value="0">Espere miestras se cargan las partidas...</option>
					</select>
			</div>
		</div>

<table width="100%" border="1">
  <tr>
    <!--<th scope="col"><input onkeyup="saltar(event,'txt_detalle')" id="txt_cantidad" name="txt_cantidad" placeholder="Cant" class="form-control" type="text" style="text-align:center" /></th>-->
    <th width="70%" scope="col"><input onkeyup="saltar(event,'txt_precio')" id="txt_detalle" name="txt_detalle" placeholder="Partida" class="form-control" type="text" style="text-align:center" readonly="" /></th>
    <th width="20%"scope="col"><input onkeyup="guardar_detalle2(event)" id="txt_precio" name="txt_precio" placeholder="Monto Bs" class="form-control" type="text" style="text-align:center" /></th>
  </tr>
</table>
			
			<br>
<div align="center">			
	<button type="button" id="boton" class="btn btn-outline-success waves-effect" onclick="guardar_detalle(0)" ><i class="fas fa-save prefix grey-text mr-1"></i> Agregar</button>			
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
// PARA EL SELECT2
$(document).ready(function() {
    $('.select2').select2();
	$('#cmdbuscar').hide();
});
//-------------
function partida(id)
{
	//alertify.alert('Espere mientras se actualiza la Solicitud...');
	var parametros = "id=" + id; 
	$.ajax({  
		type : 'POST',
		url  : 'presupuesto/3l_partida.php',
		dataType:"json",
		data:  parametros, 
		success:function(data) {  	
			if (data.tipo=="info")
				{	 document.form999.txt_detalle.value = (data.msg);	}
			//--------------
			} 
		});
}
//--------------------- PARA BUSCAR
function buscar_orden(){
	var parametros = "id=" + document.form999.txt_control.value+'&fecha='+document.form999.txt_fecha.value;
	$.ajax({  
		type : 'POST',
		url  : 'presupuesto/3i_buscar.php',
		data: parametros,
		dataType:"json",
		success:function(data) {  
			if (data.tipo=="alerta")
				{	document.form999.txt_tipo.value = 1;
					document.form999.txt_concepto.value = '';	}
			else
				{
				tabla();
				document.form999.txt_control.value = data.numero;
				document.form999.txt_fecha.value = data.fecha;
				document.form999.txt_tipo.value = data.tipo_orden;
				document.form999.txt_concepto.value = data.concepto;
				//document.form999.txt_control.focus();
				}
			}  
		});
}
//--------------------------------
setTimeout(function()	{
		document.form999.txt_control.focus();
		},500)	
//--------------------------------
$("#txt_fecha").datepicker();
combo0('<?php  echo date('d/m/Y');?>');
//--------------------------------
$("#txt_precio").on({
    "focus": function (event) {
        $(event.target).select();
    },
    "keyup": function (event) {
        $(event.target).val(function (index, value ) {
            return value.replace(/\D/g, "")
                        .replace(/([0-9])([0-9]{2})$/, '$1,$2')
                        .replace(/\B(?=(\d{3})+(?!\d)\.?)/g, ".");
        });
    }
});
</script>