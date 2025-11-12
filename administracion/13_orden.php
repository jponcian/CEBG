<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');

if ($_SESSION['VERIFICADO'] != "SI") { 
header ("Location: ../validacion.php?opcion=val"); 
exit(); }

$acceso=23;
//------- VALIDACION ACCESO USUARIO
include_once "../validacion_usuario.php";
//-----------------------------------
?>
<form id="form1" name="form1" method="post" onsubmit="return evitar();" >
        <div align="center" class="TituloP">Relaci&oacute;n de Solicitud Orden de Pago</div>
		<br >
<div  class="text-right mb-3"><a href="" class="btn btn-outline-primary btn-rounded btn-sm font-weight-bold" onclick="agregar();" data-toggle="modal" data-target="#modal_largo" data-backdrop="static" data-keyboard="false"><i class="fas fa-plus-circle" ></i> Agregar Orden</a></div>
		<diw class="row ml-3">
            <strong>Opciones de Busqueda:</strong>
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
//------------------------------ PARA ELIMINAR
function eliminar_t(id, tipo)
	{
	alertify.confirm("Estas seguro de vaciar la Solicitud?",  
	function()
			{ 
			var parametros = "id=" + id;
			$.ajax({
			url: "administracion/13k_eliminar.php",
			type: "POST",
			data: parametros,
			success: function(r) {
			alertify.success('Registros Eliminados Correctamente');
			//--------------
			if (tipo==1)	{buscar();}
				else	{tabla(id);}
				}
			});
		});
	}
//-------------
function partida(id)
{
	//alertify.alert('Espere mientras se actualiza la Solicitud...');
	var parametros = "id=" + id; 
	$.ajax({  
		type : 'POST',
		url  : 'administracion/13l_partida.php',
		dataType:"json",
		data:  parametros, 
		success:function(data) {  	
			if (data.tipo=="info")
				{	 document.form999.txt_cantidad.value = 1;
				 	document.form999.txt_detalle.value = (data.msg);	
					document.form999.txt_cantidad.focus();
				}
			//--------------
			} 
		});
}
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
//----------------- PARA VALIDAR
function validar_detalle()
	{
	error = 0;
	if(document.form999.txt_id_rif.value=="" || document.form999.txt_id_rif.value=="0")	
		{	alertify.alert("Debe Indicar el Rif");			error = 1;  }
	if(document.form999.txt_concepto.value=="")	
		{	 document.form999.txt_concepto.focus(); 	alertify.alert("Debe Indicar el Concepto");			error = 1;  }
	if(document.form999.txt_partida.value=="0")	
		{	 document.form999.txt_partida.focus(); 	alertify.alert("Debe Seleccionar la Partida");			error = 1;  }
	if(document.form999.txt_categoria.value=="0")	
		{	 document.form999.txt_categoria.focus(); 	alertify.alert("Debe Seleccionar la Categoria");			error = 1;  }
	if(document.form999.txt_cantidad.value=="" && document.form999.txt_partida.value!="999")	
		{	 document.form999.txt_cantidad.focus(); 	alertify.alert("Debe Indicar la Cantidad");			error = 1;  }
	if(document.form999.txt_detalle.value=="" && document.form999.txt_partida.value!="999")		
		{	 document.form999.txt_detalle.focus();	alertify.alert("Debe Indicar la Descripcion");		error = 1;  }
	if(document.form999.txt_precio.value=="" && document.form999.txt_partida.value!="999")		
		{	 document.form999.txt_precio.focus();		alertify.alert("Debe Indicar el Precio Unitario");	error = 1;  }
	return error;
	}
//--------------------------- PARA GUARDAR
function guardar_detalle2(e)
 	 {
	 (e.keyCode)?k=e.keyCode:k=e.which;
	// Si la tecla pulsada es enter (codigo ascii 13)
	if(k==13)
		{guardar_detalle();}
	}
//-------------
function combo0(fecha)
{
	$.ajax({
        type: "POST",
        url: 'administracion/13f_combo.php?fecha='+fecha,
        success: function(resp){
            $('#txt_categoria').html(resp);
        }
    });
}
//-------------
function combo(categoria)
{
	$.ajax({
        type: "POST",
        url: 'administracion/13c_combo.php?categoria='+categoria+'&partida=0&fecha='+document.form999.txt_fecha.value,
        success: function(resp){
            $('#txt_partida').html(resp);
        }
    });
}
//-----------------------
function agregar()
	{
	$('#modal_lg').html('<div align="center"><div class="spinner-border" role="status"></div><br><strong>Un momento, por favor...</strong></div>');
	$('#modal_lg').load('administracion/13b_modal.php');
	}
//----------------
function buscar2(){
	document.form1.optradio.value=3;
	$('#div1').html('<div align="center"><div class="spinner-border" role="status"></div><br><strong>Un momento, por favor...</strong></div>');
	$('#div1').load('administracion/13a_tabla.php?valor='+cambia(document.form1.obuscar.value)+'&tipo=3');
}
//----------------
function buscar(){
if((document.form1.obuscar.value=="  " || document.form1.obuscar.value==" " || document.form1.obuscar.value=="") && document.form1.optradio.value<3){}
else	{
		$('#div1').html('<div align="center"><div class="spinner-border" role="status"></div><br><strong>Un momento, por favor...</strong></div>');
		$('#div1').load('administracion/13a_tabla.php?valor='+cambia(document.form1.obuscar.value)+'&tipo='+document.form1.optradio.value);
		}
}
//---------------------
function imprimir(id, tipo)
	{
	if (tipo==1)	{window.open("administracion/formatos/4_ordenb.php?id="+id,"_blank");}
				else	{window.open("administracion/formatos/4_orden.php?id="+id,"_blank");}
	}
</script>