<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');

if ($_SESSION['VERIFICADO'] != "SI") { 
header ("Location: ../validacion.php?opcion=val"); 
exit(); }

$acceso=14;
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
<h4 align="center" style="background-color:#0275d8; color:#FFFFFF" class="modal-title w-100 font-weight-bold py-2">Nuevo Pago
<button type="button" class="close" data-dismiss="modal">&times;</button></h4>
</div>
<!-- Modal body -->
		<div class="p-1">
			
	<div class="row">
		
<div class="form-group col-sm-8">
	<div class="input-group"><div class="input-group-text"><i class="fas fa-university mr-2"></i></div>
	<input onkeyup="saltar(event,'txt_desde')" id="txt_concepto" placeholder="Concepto" name="txt_concepto" class="form-control" type="text" style="text-align:center" />
	</div>
</div>
				
<div class="form-group col-sm-3">
	<div class="input-group"><div class="input-group-text"><i class="far fa-calendar-alt"></i></div>
	<input  type="text" style="text-align:center" class="form-control " name="txt_desde" id="txt_desde" placeholder="Fecha"  minlength="10" maxlength="10" value="<?php echo date('d/m/Y');?>" required>
	</div>
</div>	
		
<div class="form-group col-sm-1">
	<div align="center" ><button id="boton3" data-toggle="tooltip" data-placement="top" title="Guardar" type="button" class="btn btn-outline-success waves-effect" onclick="guardar_concepto();" ><i class="fas fa-save prefix grey-text mr-1"></i></button></div>
</div>	
		
	</div>
			
<table width="100%" class="formateada" border="1">
<tr >
<th colspan="3" >Categoria Especifica</th>
<th >Monto del Pago</th>
<th >Segun Cant. Hijos</th>
</tr>
<tr >
<td ><input name="txt_cat" type="radio" value="2" checked="checked" /></td>
<td><select class="custom-select" style="font-size: 14px" name="txt_categoria" id="txt_categoria" onchange="combo(this.value);">
	<option value="0">Seleccione</option>
	</select></td>
<td ><select class="custom-select" style="font-size: 14px" name="txt_partida" id="txt_partida" onchange="">
	<option value="0">Espere miestras se cargan las partidas...</option>
	</select></td>
<td ><input id="txt_monto" name="txt_monto" placeholder="Monto Bs" class="form-control" type="text" style="text-align:right" /></td>
<td align="center" ><input class="form-control" name="check_hijos" type="checkbox" value="1" /></td>
	</tr>
</table>
<br>
<table width="100%" border="1">
<tr class="TituloP"><th colspan="4">Filtros</th></tr>
  <tr>
    <th scope="col"><select class="custom-select" style="font-size: 14px" name="txt_nomina" id="txt_nomina" onchange="tabla2();">
					<!--<option value="-1">--- Seleccione ---</option>-->
					<option value="0">--- Todas las Nominas ---</option>
					<?php
					//--------------------
					$consultx = "SELECT * FROM a_nomina WHERE eventual=0 AND codigo<>'0700' AND codigo<>'0800';"; 
					$tablx = $_SESSION['conexionsql']->query($consultx);
					while ($registro_x = $tablx->fetch_object())
					//-------------
					{
					echo '<option ';
					echo ' value="';
					echo $registro_x->nomina;
					echo '">';
					echo mayuscula($registro_x->nomina);
					echo '</option>';
					}
					?>
					</select></th>
    <th scope="col"><select class="custom-select" style="font-size: 14px" name="txt_ubicacion" id="txt_ubicacion" onchange="tabla2();">
					<option value="0">--- Todas la Areas ---</option>
					<?php
					//--------------------
					$consultx = "SELECT * FROM a_areas ORDER BY area;"; 
					$tablx = $_SESSION['conexionsql']->query($consultx);
					while ($registro_x = $tablx->fetch_object())
					//-------------
					{
					echo '<option ';
					echo ' value="';
					echo $registro_x->id;
					echo '">';
					echo mayuscula($registro_x->area);
					echo '</option>';
					}
					?>
					</select></th>
    <th scope="col"><select class="custom-select" style="font-size: 14px" name="txt_sexo" id="txt_sexo" onchange="tabla2();">
					<option value="0">--- Todos ---</option>
					<?php
					//--------------------
					echo '<option ';
					if ($registro->sexo=='F')	{echo ' selected="selected" ';}
					echo ' value="F">Femenino</option>';
					//--------------------
					echo '<option ';
					if ($registro->sexo=='M')	{echo ' selected="selected" ';}
					echo ' value="M">Masculino</option>';
					?>
					</select></th>
    <th scope="col"><select class="custom-select" style="font-size: 14px" name="txt_hijo" id="txt_hijo" onchange="tabla2();">
					<option value="0">--- Todos ---</option>
					<option value="1">--- Con Hijos ---</option>
					<option value="2">--- Sin Hijos ---</option>
					</select></th>
  </tr>
<tr>
    <th colspan="3" scope="col"><select class="custom-select" style="font-size: 14px" name="txt_pagos" id="txt_pagos" >
					<!--<option value="-1">--- Seleccione ---</option>-->
					<option value="0">--- Pagos Anteriores ---</option>
					<?php
					//--------------------
					$consultx = "SELECT id, descripcion, fecha FROM nomina_solicitudes WHERE tipo_pago IN ('008') and estatus>0 GROUP BY descripcion ORDER BY fecha;"; 
					$tablx = $_SESSION['conexionsql']->query($consultx);
					while ($registro_x = $tablx->fetch_object())
					//-------------
					{
					echo '<option ';
					echo ' value="';
					echo $registro_x->id;
					echo '">';
					echo mayuscula($registro_x->descripcion).' de fecha '.voltea_fecha($registro_x->fecha);
					echo '</option>';
					}
					?>
					</select></th>
	<th><button type="button" id="boton2" class="btn btn-outline-success waves-effect" onclick="duplicar();" >Duplicar</button></th>
  </tr>
</table>

<div  align="center" id="div2">

</div>

<br>
<div align="center">			
	<button type="button" id="boton" class="btn btn-outline-success waves-effect" onclick="tabla();tabla2();" >Refrescar Listados</button>			<!--	<button type="button" id="boton" class="btn btn-outline-success waves-effect" onclick="guardar_detalle(0,'si')" ><i class="fas fa-save prefix grey-text mr-1"></i> Agregar</button>			-->
</div>
	
	</div>

<!-- Modal footer -->
<div class="modal-footer justify-content-center">
	<div align="center" id="div3">			

	</div>
</div>

</form>
<script language="JavaScript">
//--------------------------------
setTimeout(function()	{
		buscar_pago();
		tabla(); //tabla2();
		},500)	
//--------------------------------
$("#txt_desde").datepicker();
combo0('<?php  echo date('d/m/Y');?>');
//--------------------------- PARA GUARDAR
function guardar_concepto()
 	 {
	$('#boton3').hide();
	//Obtenemos datos formulario.
	var parametros = $("#form999").serialize(); 
	$.ajax({  
		type : 'POST',
		url  : 'personal/11e3_guardar.php',
		dataType:"json",
		data:  parametros, 
		success:function(data) {  	
			if (data.tipo=="info")
				{	
				alertify.success(data.msg);
				//agregar();
				//tabla();
				//tabla2();
				$('#boton3').show();
				}
			else
				{	alertify.alert(data.msg);	}
			}  
		});
	}
//--------------------------- PARA GUARDAR
function duplicar()
 	 {
	$('#boton2').hide();
	//Obtenemos datos formulario.
	var parametros = $("#form999").serialize(); 
	$.ajax({  
		type : 'POST',
		url  : 'personal/11e2_guardar.php',
		dataType:"json",
		data:  parametros, 
		success:function(data) {  	
			if (data.tipo=="info")
				{	
				alertify.success(data.msg);
				agregar();
				//tabla();
				//tabla2();
				//$('#boton').show();
				}
			else
				{	alertify.alert(data.msg);	}
			}  
		});
	}
//--------------------------------
$("#txt_monto").on({
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
//------------------------------ PARA ELIMINAR
function eliminar_to(id)
	{
	alertify.confirm("Estas seguro de eliminar todos los Registros?",  
	function()
			{ 
			var parametros = "id=" + id;
			$.ajax({
			url: "personal/11k_eliminar.php",
			type: "POST",
			data: parametros,
			success: function(r) {
			alertify.success('Registros Eliminados Correctamente');
			//--------------
			tabla(); tabla2();
			}
			});
		});
	}
//--------------------- PARA BUSCAR
function buscar_pago(){
	var parametros = "id=0";
	$.ajax({  
		type : 'POST',
		url  : 'personal/11i_buscar.php',
		data: parametros,
		dataType:"json",
		success:function(data) {  
			if (data.tipo=="alerta")
				{	document.form999.txt_concepto.focus();	}
			else
				{
				document.form999.txt_desde.value = data.desde;
				document.form999.txt_concepto.value = data.concepto;
				document.form999.txt_concepto.focus();
				}
			}  
		});
}
//----------------- PARA VALIDAR
function validar_detalle()
	{
	error = 0;
	if(document.form999.txt_concepto.value=="")	
			{	 document.form999.txt_concepto.focus(); alertify.alert("Debe Indicar el Concepto");			error = 1;  }
	if(document.form999.txt_desde.value=="")	
			{	 document.form999.txt_desde.focus(); alertify.alert("Debe Indicar la Fecha");			error = 1;  }
//	if(document.form999.txt_partida.value=="0" & document.form999.txt_cat.value=="2")	
//			{	 document.form999.txt_partida.focus(); 	alertify.alert("Debe Seleccionar la Partida");			error = 1;  }
//	if(document.form999.txt_categoria.value=="0" & document.form999.txt_cat.value=="2")	
//			{	 document.form999.txt_categoria.focus();alertify.alert("Debe Seleccionar la Categoria");			error = 1;  }
	if(document.form999.txt_monto.value=="")		
			{	 document.form999.txt_monto.focus();	alertify.alert("El monto a pagar debe ser mayor a cero...");	error = 1;  }
	return error;
	}
//--------------------------- PARA GUARDAR
function guardar_todos()
 	{
	if (validar_detalle()==0)
		{
		$('#boton').hide();
		//Obtenemos datos formulario.
		var parametros = $("#form999").serialize(); 
		$.ajax({  
			type : 'POST',
			url  : 'personal/11e_guardar.php?rac=123456789',
			dataType:"json",
			data:  parametros, 
			success:function(data) {  	
				if (data.tipo=="info")
					{	
					alertify.success(data.msg);
					tabla();
					tabla2();
					$('#boton').show();
					}
				else
					{	alertify.alert(data.msg);	}
				}  
			});
		}
	}
//--------------------------- PARA GUARDAR
function guardar_detalle2(rac,boton)
 	 {
	if (validar_detalle()==0)
		{
		$('#'+boton).hide();
		//Obtenemos datos formulario.
		var parametros = $("#form999").serialize(); 
		$.ajax({  
			type : 'POST',
			url  : 'personal/11e_guardar.php?rac='+rac,
			dataType:"json",
			data:  parametros, 
			success:function(data) {  	
				if (data.tipo=="info")
					{	
					alertify.success(data.msg);
					tabla();
					tabla2();
					//$('#boton').show();
					}
				else
					{	alertify.alert(data.msg);	}
				}  
			});
		}
	}
//--------------------------- PARA GUARDAR
function guardar_detalle()
 	 {
	if (validar_detalle()==0)
		{
		$('#boton').hide();
		//Obtenemos datos formulario.
		var parametros = $("#form999").serialize(); 
		$.ajax({  
			type : 'POST',
			url  : 'personal/11e_guardar.php',
			dataType:"json",
			data:  parametros, 
			success:function(data) {  	
				if (data.tipo=="info")
					{	
					alertify.success(data.msg);
					tabla();
					tabla2();
					$('#boton').show();
					}
				else
					{	alertify.alert(data.msg);	}
				}  
			});
		}
	}
//--------------------- PARA BUSCAR
function tabla2(){
	$('#div2').html('<div align="center"><div class="spinner-border" role="status"></div><br><strong>Un momento, por favor...</strong></div>');
	$('#div2').load('personal/11m_tabla.php?nomina='+document.form999.txt_nomina.value+'&ubicacion='+(document.form999.txt_ubicacion.value)+'&pago='+(document.form999.txt_pagos.value)+'&sexo='+(document.form999.txt_sexo.value)+'&hijo='+(document.form999.txt_hijo.value));tabla();
}
//-------------
function combo0(fecha)
{
	$.ajax({
        type: "POST",
        url: 'personal/11f_combo.php?fecha='+fecha,
        success: function(resp){
            $('#txt_categoria').html(resp);
			combo(document.form999.txt_categoria.value);
        }
    });
}
//-------------
function combo(categoria)
{
	$.ajax({
        type: "POST",
        url: 'personal/11c_combo.php?categoria='+categoria+'&partida=0&fecha='+document.form999.txt_desde.value,
        success: function(resp){
            $('#txt_partida').html(resp);
        }
    });
}
//---------------------
function tabla(){
	$('#div3').html('<div align="center"><div class="spinner-border" role="status"></div><br><strong>Un momento, por favor...</strong></div>');
	$('#div3').load('personal/11d_tabla.php');
}
//------------------------------ PARA ELIMINAR
function eliminar(id)
	{
	alertify.confirm("Estas seguro de eliminar el Registro?",  
	function()
			{ 
			var parametros = "id=" + id;
			$.ajax({
			url: "personal/11h_eliminar.php",
			type: "POST",
			data: parametros,
			success: function(r) {
			alertify.success('Registro Eliminado Correctamente');
			//--------------
			//tabla(); 
				tabla2();
			}
			});
		});
	}
</script>