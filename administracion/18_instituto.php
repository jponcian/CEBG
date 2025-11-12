<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');

if ($_SESSION['VERIFICADO'] != "SI") { 
header ("Location: ../validacion.php?opcion=val"); 
exit(); }

$acceso=105;
//------- VALIDACION ACCESO USUARIO
include_once "../validacion_usuario.php";
//-----------------------------------
?>
<form id="form1" name="form1" method="post" onsubmit="return evitar();" >
        <div align="center" class="TituloP">Relaci&oacute;n de Solicitud de Pago (Institutos)</div>
		<br >
<div  class="text-right mb-3"><a href="" class="btn btn-outline-primary btn-rounded btn-sm font-weight-bold" onclick="agregar();" data-toggle="modal" data-target="#modal_largo" data-backdrop="static" data-keyboard="false"><i class="fas fa-plus-circle" ></i> Agregar Orden</a></div>
		<diw class="row ml-3">
            <strong>Opciones de Busqueda:</strong>
           <!-- <div class="form-check ml-3">
              <label class="form-check-label">
                    <input type="radio" class="form-check-input" name="optradio" value="1" >
                N&uacute;mero</label>
            </div>-->
           
            <div class="form-check ml-3">
                <label class="form-check-label">
                    <input type="radio" class="form-check-input" name="optradio" value="2" checked="checked" >
                    Descripcion
                </label>
            </div>
			<div class="form-check ml-3">
                <label class="form-check-label">
                    <input type="radio" class="form-check-input" name="optradio" value="3" onclick="buscar()" >
                   Pendiente
                </label>
            </div>
			<div class="form-check ml-3">
                <label class="form-check-label">
                    <input type="radio" class="form-check-input" name="optradio" value="4" onclick="buscar()" >
                   Solicitadas
                </label>
            </div>
			<div class="form-check ml-3">
                <label class="form-check-label">
                    <input type="radio" class="form-check-input" name="optradio" value="5" onclick="buscar()" >
                   Aprobadas
                </label>
            </div>
        </diw>
 <input name="obuscar" id="obuscar" type="text" size="100" class="form-control" onchange="buscar()" />

 <br>
 
 <div id="div1"></div>
</form>
<script language="JavaScript">
//------------------
function generar_solicitud(id, boton)
	{
	alertify.confirm("Estas seguro de generar la Solicitud de Pago?",  
	function()
		{
		$('#'+boton).hide();
		//alertify.alert('Espere mientras se actualiza la Solicitud...');
		var parametros = "id=" + id; 
		$.ajax({  
			type : 'POST',
			url  : 'administracion/13j_guardar.php',
			dataType:"json",
			data:  parametros, 
			success:function(data) {  	
				if (data.tipo=="info")
					{	alertify.success(data.msg);	buscar();	}
				else
					{	alertify.alert(data.msg);	}
				//--------------
				} 
			 
			});
		});
	}
//------------------
function guardar_solicitud()
	{
	alertify.confirm("Estas seguro de Guardar la Informacion?",  
	function()
		{
		$('#boton').hide();
		//alertify.alert('Espere mientras se actualiza la Solicitud...');
		 var parametros = $("#form999").serialize(); 
		$.ajax({  
			type : 'POST',
			url  : 'administracion/18j_guardar.php',
			dataType:"json",
			data:  parametros, 
			success:function(data) {  	
				if (data.tipo=="info")
					{	alertify.success(data.msg);	$('#modal_largo .close').click(); buscar2(); 	}
				else
					{	$('#modal_largo .close').click(); buscar2(); alertify.alert(data.msg);	}
				//--------------
				} 
			 
			});
		});
	}
//----------------- PARA VALIDAR
function validar_detalle()
	{
	error = 0;
	if(document.form999.txt_id_rif.value=="" || document.form999.txt_id_rif.value=="0")	
		{	alertify.alert("Debe Indicar el Rif");			error = 1;  }
	if(document.form999.txt_concepto.value=="")	
		{	 document.form999.txt_concepto.focus(); 	alertify.alert("Debe Indicar el Concepto");			error = 1;  }
	if(document.form999.txt_fecha.value=="")	
		{	 document.form999.txt_fecha.focus(); 	alertify.alert("Debe Indicar la Fecha de la Solicitud");			error = 1;  }
	return error;
	}
//*--------------------- PARA BUSCAR
function buscar_orden(){
	var parametros = "id=" + document.form999.txt_id_rif.value;
	$.ajax({  
		type : 'POST',
		url  : 'administracion/13i_buscar.php',
		data: parametros,
		dataType:"json",
		success:function(data) {  
			if (data.tipo=="alerta")
				{	alertify.alert(data.msg);	}
			else
				{
				document.form999.txt_fecha.value = data.fecha_factura;
				document.form999.txt_concepto.value = data.concepto;
				document.form999.txt_fecha.focus();
				}
			}  
		});
}
//*--------------------- PARA BUSCAR
function buscar_proveedor(){
	$('#cmdbuscar').hide();
	var parametros = "id=" + document.form999.txt_rif.value;
	$.ajax({  
		type : 'POST',
		url  : 'funciones/buscar_contribuyente.php',
		data: parametros,
		dataType:"json",
		success:function(data) {  
			if (data.tipo=="alerta")
				{	alertify.alert(data.msg);	}
			else
				{
				document.form999.txt_id_rif.value = data.id_rif;
				document.form999.txt_rif.value = data.rif;
				document.form999.txt_nombres.value = data.contribuyente;
				//combo0(document.form999.txt_rif.value, document.form999.txt_fecha.value);
				buscar_orden();
				//tabla();
				//---------------
				//$('#cmdbuscar').show();
				}
			}  
		});
}
//*-----------------------
function agregar()
	{
	$('#modal_lg').html('<div align="center"><div class="spinner-border" role="status"></div><br><strong>Un momento, por favor...</strong></div>');
	$('#modal_lg').load('administracion/18b_modal.php');
	}
//*----------------
function buscar2(){
	document.form1.optradio.value=3;
	$('#div1').html('<div align="center"><div class="spinner-border" role="status"></div><br><strong>Un momento, por favor...</strong></div>');
	$('#div1').load('administracion/13a_tabla.php?valor='+cambia(document.form1.obuscar.value)+'&tipo=3');
}
//*----------------
function buscar(){
if((document.form1.obuscar.value=="  " || document.form1.obuscar.value==" " || document.form1.obuscar.value=="") && document.form1.optradio.value<3){}
else	{
		//valor = document.form1.obuscar.value; 
		//valor = valor.replace(/ /g, '_');
		$('#div1').html('<div align="center"><div class="spinner-border" role="status"></div><br><strong>Un momento, por favor...</strong></div>');
		$('#div1').load('administracion/13a_tabla.php?valor='+cambia(document.form1.obuscar.value)+'&tipo='+document.form1.optradio.value);
		}
}
//*---------------------
function imprimir(id)
	{	
	window.open("administracion/formatos/4_orden.php?id="+id,"_blank");
	}
</script>