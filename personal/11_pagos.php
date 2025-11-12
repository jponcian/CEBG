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
?>
<form id="form1" name="form1" method="post" onsubmit="return evitar();" >
        <div align="center" class="TituloP">Relaci&oacute;n de Pagos Empleados</div>
		<br >
<div  class="text-right mb-3"><a href="" class="btn btn-outline-primary btn-rounded btn-sm font-weight-bold" onclick="agregar();" data-toggle="modal" data-target="#modal_largo" data-keyboard="false"><i class="fas fa-plus-circle" ></i> Generar Pago</a></div>
		
		<diw class="row ml-3">
            <strong>Opciones de Busqueda:</strong>
           
            <div class="form-check ml-3">
                <label class="form-check-label">
                    <input type="radio" class="form-check-input" name="optradio" value="1" checked="checked" >
                    Descripcion
                </label>
            </div>
			<div class="form-check ml-3">
                <label class="form-check-label">
                    <input type="radio" class="form-check-input" name="optradio" value="2" onclick="buscar()" >
                   Pendientes
                </label>
            </div>
			<div class="form-check ml-3">
                <label class="form-check-label">
                    <input type="radio" class="form-check-input" name="optradio" value="3" onclick="buscar()" >
                   Solicitados
                </label>
            </div>
			<div class="form-check ml-3">
                <label class="form-check-label">
                    <input type="radio" class="form-check-input" name="optradio" value="4" onclick="buscar()" >
                   Aprobados
                </label>
            </div>
			<div class="form-check ml-3">
                <label class="form-check-label">
                    <input type="radio" class="form-check-input" name="optradio" onclick="buscar2()" >
                   TXT
                </label>
            </div>
        </diw>
 <input name="obuscar" id="obuscar" type="text" size="100" class="form-control" onchange="buscar()" />

 <br>
 
 <div id="div1"></div>
</form>
<script language="JavaScript">
//------------------
function solicitar(id, boton)
	{
	alertify.confirm("Estas seguro de realizar la Solicitud?",  
	function()
		{
		$('#'+boton).hide();
		//alertify.alert('Espere mientras se actualiza la Solicitud...');
		var parametros = "id=" + id; 
		$.ajax({  
			type : 'POST',
			url  : 'personal/11j_guardar.php',
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
//-----------------------
function agregar()
	{
	$('#modal_lg').html('<div align="center"><div class="spinner-border" role="status"></div><br><strong>Un momento, por favor...</strong></div>');
	$('#modal_lg').load('personal/11b_modal.php');
	}
//----------------
function buscar(){
if((document.form1.obuscar.value=="  " || document.form1.obuscar.value==" " || document.form1.obuscar.value=="") && document.form1.optradio.value==1){}
else	{
		$('#div1').html('<div align="center"><div class="spinner-border" role="status"></div><br><strong>Un momento, por favor...</strong></div>');
		$('#div1').load('personal/11a_tabla.php?valor='+cambia(document.form1.obuscar.value)+'&tipo='+document.form1.optradio.value);
		}
}
//----------------
function buscar2(){
	$('#div1').html('<div align="center"><div class="spinner-border" role="status"></div><br><strong>Un momento, por favor...</strong></div>');
	$('#div1').load('personal/5a_tabla.php?valor='+cambia(document.form1.obuscar.value)+'&tipo='+document.form1.optradio.value);
}
//---------------------
function imprimir(id, tipo, estatus, solicitud,nomina)
	{	
	if (estatus>0)
		{	window.open("personal/formatos/1_memo.php?id="+solicitud+"&tipo="+tipo+"&estatus="+estatus,"_blank");	}
		
	if (tipo=="008")
		{	window.open("personal/formatos/8_pagos.php?id="+id+"&tipo="+tipo+"&estatus="+estatus+"&solicitud="+solicitud+"&nomina="+nomina,"_blank");	}
	}
</script>