<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');

if ($_SESSION['VERIFICADO'] != "SI") { 
header ("Location: ../validacion.php?opcion=val"); 
exit(); }

$acceso=68;
//------- VALIDACION ACCESO USUARIO
include_once "../validacion_usuario.php";
//-----------------------------------
$consultx = "SELECT * FROM traslados WHERE estatus=0;";
//echo $consultx;
$tablx = $_SESSION['conexionsql']->query($consultx);
if ($tablx->num_rows>0)
	{	
	$registro = $tablx->fetch_object();	
	$annoD = $registro->anno;
	$concepto = $registro->concepto;
	$fecha = voltea_fecha($registro->fecha);
	echo '<script language="JavaScript">combo('.$annoD.');</script>';
	} else { $fecha = date('d/m/Y'); }
?>
<form id="form999" name="form999" method="post" >
			<!-- Modal Header -->
<div class="modal-header bg-fondo text-center">
    <input type="hidden" id="oid" name="oid" value="0"/>
	<input type="hidden" id="txt_id_rif" name="txt_id_rif" value="0" style="text-align:center" />
<h4 align="center" style="background-color:#0275d8; color:#FFFFFF" class="modal-title w-100 font-weight-bold py-2">Registrar Traspaso
	<button type="button" class="close" data-dismiss="modal" onclick="buscar();">&times;</button></h4>
</div>
<!-- Modal body -->
		<div class="p-1">
	<div class="row">		
		<div class="form-group col-sm-4">
			<div class="input-group">
				<div class="input-group-text"><i class="far fa-calendar-alt mr-2"></i>A&ntilde;o</div>
				<select class="form-control" name="oanno" id="oanno" onchange="combo(this.value);">
  <option value="0" > Seleccione </option>
<?php
//--------------------
$anno = date('Y');
while ($anno >= 2019)
//-------------
	{
	echo '<option value="';
	echo $anno;
	echo '" ';
	if ($annoD==$anno) {echo 'selected="selected"';}
	echo ' >';
	echo $anno;
	echo '</option>';
	$anno--;
	}
?> 
</select></div>
		</div>	
		<div class="form-group col-sm-4">
			<div class="input-group">
				<div class="input-group-text"><i class="far fa-calendar-alt"></i></div>
				<input onkeyup="saltar(event,'txt_concepto')" type="text" style="text-align:center" class="form-control " name="txt_fecha" id="txt_fecha" placeholder="Fecha"  minlength="1" maxlength="10" value="<?php  echo $fecha;?>" required></div>
		</div>	
		<div class="form-group col-sm-4">
			<div class="input-group">
				<button type="button" id="boton" class="btn btn-warning btn-sm" onClick="actualizar();">Recalcular Partidas</button>
			</div>
		</div>	
	</div>
	<div class="row">
		<div class="form-group col-sm-12">
			<div class="input-group-text"><i class="fas fa-university mr-2"></i>
			<input value="<?php  echo $concepto;?>" id="txt_concepto" placeholder="RESOLUCION" name="txt_concepto" class="form-control" type="text" style="text-align:center" />
			</div>
		</div>
	</div>
				<h5>Trasladar desde (Disminuir):</h5>
			<div class="row">
				
				<div class="form-group col-sm-5">
					<div class="input-group-text">Actividad: <select class="custom-select" style="font-size: 14px" name="txt_categoria1" id="txt_categoria1" onchange="comboc1();">
					<option value="0">Seleccione</option>
					</select>
					</div>
				</div>

				<div class="form-group col-sm-7">
					<div class="input-group-text">Partida: <select class="select2" style="width: 350px" name="txt_partida1" id="txt_partida1" onchange="partida1()" >
					<option value="0">Espere miestras se cargan las partidas...</option>
					</select>
				</div>
			</div>
		</div>

<table width="100%" border="1">
  <tr>
    <th scope="col">Original</th>
    <th scope="col">Modificado</th>
    <th scope="col">Disponible</th>
    <th scope="col">Disminuir</th>
  </tr>
  <tr>
    <th scope="col"><input id="txt_original1" name="txt_original1" placeholder="0" class="form-control" type="text" style="text-align:right" readonly="" /></th>
    <th scope="col"><input id="txt_modificado1" name="txt_modificado1" placeholder="0" class="form-control" type="text" style="text-align:right" readonly="" /></th>
    <th scope="col"><input id="txt_disponible1" name="txt_disponible1" placeholder="0" class="form-control" type="text" style="text-align:right" readonly="" /></th>
	<th scope="col"><input id="txt_precio1" name="txt_precio1" placeholder="Monto Bs" class="form-control" type="text" style="text-align:right" /></th>
  </tr>
</table>
			<br>

<div align="center">			
	<button type="button" id="boton1" class="btn btn-outline-success waves-effect" onclick="guardar_traslado(1)" ><i class="fas fa-save prefix grey-text mr-1"></i> Agregar</button>			
</div>
			<br>
				<h5>Trasladar para (Incrementar):</h5>
<div class="row">
	
	<div class="form-group col-sm-5">
		<div class="input-group-text">Actividad: <select class="custom-select" style="font-size: 14px" name="txt_categoria2" id="txt_categoria2" onchange="comboc2();">
		<option value="0">Seleccione</option>
		</select>
		</div>
	</div>

	<div class="form-group col-sm-7">
		<div class="input-group-text">Partida: <select class="select2" style="width: 350px" style="font-size: 14px" name="txt_partida2" id="txt_partida2" onchange="partida2()">
		<option value="0">Espere miestras se cargan las partidas...</option>
		</select>
		</div>
	</div>

</div>

<table width="100%" border="1">
  <tr>
    <th scope="col">Original</th>
    <th scope="col">Modificado</th>
    <th scope="col">Disponible</th>
    <th scope="col">Incrementar</th>
  </tr>
  <tr>
    <th scope="col"><input id="txt_original2" name="txt_original2" placeholder="0" class="form-control" type="text" style="text-align:right" readonly="" /></th>
    <th scope="col"><input id="txt_modificado2" name="txt_modificado2" placeholder="0" class="form-control" type="text" style="text-align:right" readonly="" /></th>
    <th scope="col"><input id="txt_disponible2" name="txt_disponible2" placeholder="0" class="form-control" type="text" style="text-align:right" readonly="" /></th>
    <th scope="col"><input id="txt_precio2" name="txt_precio2" placeholder="Monto Bs" class="form-control" type="text" style="text-align:right" /></th>
  </tr>
</table>
<br>

<div align="center">			
	<button type="button" id="boton" class="btn btn-outline-success waves-effect" onclick="guardar_traslado(2)" ><i class="fas fa-save prefix grey-text mr-1"></i> Agregar</button>			
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
	tabla();
});
//------------------------------ PARA ELIMINAR
function eliminar_traslado(id)
	{
//	alertify.confirm("Estas seguro de eliminar el Registro?",  
//	function()
//			{ 
			var parametros = "id=" + id;
			$.ajax({
			url: "presupuesto/4h_eliminar.php",
			type: "POST",
			data: parametros,
			success: function(r) {
			alertify.success('Registro Eliminado Correctamente');
			tabla();
			}
			});
//		});
	}
//--------------------- PARA BUSCAR
function tabla() {
    $('#div3').html('<div align="center"><div class="spinner-border" role="status"></div><br><strong>Un momento, por favor...</strong></div>');
    $('#div3').load('presupuesto/4i_tabla.php');
}
//----------------- PARA VALIDAR
function validar_detalle1()
	{
	error = 0;
	if(document.form999.txt_concepto.value=="")	
		{	 document.form999.txt_concepto.focus(); 	alertify.alert("Debe Indicar el Concepto");			error = 1;  }
	if(document.form999.oanno.value=="0")	
		{	 alertify.alert("Debe Indicar el Año");			error = 1;  }
	if(document.form999.txt_partida1.value=="0")	
		{	alertify.alert("Debe Seleccionar la Partida");			error = 1;  }
	if(document.form999.txt_categoria1.value=="0")	
		{	alertify.alert("Debe Seleccionar la Categoria");			error = 1;  }
	if(document.form999.txt_precio1.value=="")		
		{	 document.form999.txt_precio1.focus();		alertify.alert("Debe Indicar el Monto!");	error = 1;  }
	return error;
	}
//----------------- PARA VALIDAR
function validar_detalle2()
	{
	error = 0;
	if(document.form999.txt_concepto.value=="")	
		{	 document.form999.txt_concepto.focus(); 	alertify.alert("Debe Indicar el Concepto");			error = 1;  }
	if(document.form999.oanno.value=="0")	
		{	 alertify.alert("Debe Indicar el Año");			error = 1;  }
	if(document.form999.txt_partida2.value=="0")	
		{	alertify.alert("Debe Seleccionar la Partida");			error = 1;  }
	if(document.form999.txt_categoria2.value=="0")	
		{	alertify.alert("Debe Seleccionar la Categoria");			error = 1;  }
	if(document.form999.txt_precio2.value=="")		
		{	 document.form999.txt_precio2.focus();		alertify.alert("Debe Indicar el Monto!");	error = 1;  }
	return error;
	}
//--------------------------- PARA GUARDAR
function guardar_traslado(id)
 	 {
	if (id==1)
		{ valor = validar_detalle1(); }
	if (id==2)
		{ valor = validar_detalle2(); }
	if (valor==0)
		{
		$('#boton').hide();
		//Obtenemos datos formulario.
		var parametros = $("#form999").serialize(); 
		$.ajax({  
			type : 'POST',
			url  : 'presupuesto/4e_guardar.php?tipo='+id,
			dataType:"json",
			data:  parametros, 
			success:function(data) {  	
				if (data.tipo=="info")
					{	
					alertify.success(data.msg);	
					tabla();
					//actualizar();	
					//document.form999.txt_precio.focus();	
					//$('#boton').show();
//					$('#modal_largo .close').click(); 
//					document.form1.optradio.value=3;
//					buscar();
					}
				else
					{	alertify.alert(data.msg);	}
				}  
			});
		}
	}
//--------------------
function actualizar()
{
	$('#boton').hide();
	alertify.alert('Espere mientras se recalculan las partidas...');
	var parametros = "anno=" + document.form999.oanno.value +'&OFECHA1='; 
	$.ajax({  
		type : 'POST',
		url  : 'presupuesto/1c_guardar.php',
		dataType:"json",
		data:  parametros, 
		success:function(data) {  	
			if (data.tipo=="info")
				{	alertify.success(data.msg);		$('#boton').show();	}
			else
				{	alertify.alert(data.msg);	}
			//--------------
			} 
		 
		});
}
//-------------
function combo(anno)
{
	$.ajax({
        type: "POST",
        url: 'presupuesto/4c_combo.php?anno='+anno+'&tipo=1',
        success: function(resp){
            $('#txt_categoria1').html(resp);
			$('#txt_categoria2').html(resp);
        }
    });
}
//-------------
function comboc1()
{
	$.ajax({
        type: "POST",
        url: 'presupuesto/4c_combo.php?categoria='+document.form999.txt_categoria1.value+'&tipo=2'+'&anno='+document.form999.oanno.value,
        success: function(resp){
            $('#txt_partida1').html(resp);
        }
    });
}
//-------------
function comboc2()
{
	$.ajax({
        type: "POST",
        url: 'presupuesto/4c_combo.php?categoria='+document.form999.txt_categoria2.value+'&tipo=3&anno='+document.form999.oanno.value+'&categoria1='+document.form999.txt_categoria1.value+'&partida1='+document.form999.txt_partida1.value,
        success: function(resp){
            $('#txt_partida2').html(resp);
        }
    });
}
//-------------
function partida2()
{
	//alertify.alert('Espere mientras se actualiza la Solicitud...');
	var parametros = "categoria=" + document.form999.txt_categoria2.value +"&anno=" + document.form999.oanno.value+"&partida=" + document.form999.txt_partida2.value; 
	$.ajax({  
		type : 'POST',
		url  : 'presupuesto/4d_partida.php',
		dataType:"json",
		data:  parametros, 
		success:function(data) {  	
			if (data.tipo=="info")
				{	document.form999.txt_original2.value = (data.original);
				 	document.form999.txt_modificado2.value = (data.modificado);
				  	document.form999.txt_disponible2.value = (data.disponible);
//					document.form999.txt_precio.value = (data.disponible);
//					document.form999.txt_precio.focus();
				}
			//--------------
			} 
		});
}
//--------------------------------
function partida1()
{
	//alertify.alert('Espere mientras se actualiza la Solicitud...');
	var parametros = "categoria=" + document.form999.txt_categoria1.value +"&anno=" + document.form999.oanno.value+"&partida=" + document.form999.txt_partida1.value; 
	$.ajax({  
		type : 'POST',
		url  : 'presupuesto/4d_partida.php',
		dataType:"json",
		data:  parametros, 
		success:function(data) {  	
			if (data.tipo=="info")
				{	document.form999.txt_original1.value = (data.original);
				 	document.form999.txt_modificado1.value = (data.modificado);
				  	document.form999.txt_disponible1.value = (data.disponible);
				 	document.form999.txt_precio1.focus();
//					comboc2();
				}
			//--------------
			} 
		});
}
//--------------------------------
$("#txt_fecha").datepicker();
//--------------------------------
$("#txt_precio1").on({
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
//--------------------------------
$("#txt_precio2").on({
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