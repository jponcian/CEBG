<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');

if ($_SESSION['VERIFICADO'] != "SI") { 
header ("Location: ../validacion.php?opcion=val"); 
exit(); }

$acceso=113;
//------- VALIDACION ACCESO USUARIO
include_once "../validacion_usuario.php";
//-----------------------------------
?>
<form id="form1" name="form1" method="post" onsubmit="return evitar();" >
<br>
	<!--<div  class="text-right mb-3"><a href="" class="btn btn-outline-success btn-rounded btn-sm font-weight-bold" onclick="agregar();" data-toggle="modal" data-target="#modal_largo" data-keyboard="false"><i class="fas fa-plus-circle" ></i> Generar Préstamo</a></div>-->

        <diw class="row ml-3">
            <strong>Opciones de Busqueda:</strong>
            		
			<div class="form-check ml-3">
                <label class="form-check-label">
                    <input type="radio" class="form-check-input" name="optradio" value="3" onclick="busca_empleados()" >
                   Prestados
                </label>
            </div>			
            		
			<div class="form-check ml-3">
                <label class="form-check-label">
                    <input type="radio" class="form-check-input" name="optradio" value="5" onclick="busca_empleados()" >
                   Devueltos
                </label>
            </div>			
            		
			<div class="form-check ml-3">
                <label class="form-check-label">
                    <input type="radio" class="form-check-input" name="optradio" checked value="4" onclick="busca_empleados()" >
                   Ver Expedientes
                </label>
            </div>			
        </diw>

 <input placeholder="Escriba aqui la informacion a buscar..." name="obuscar" id="obuscar" type="text" size="100" class="form-control" />

<div id="div2"></div><br>
</form>
<script language="JavaScript">
$(document).ready(function() {
    busca_empleados();
});
//---------------------------
function ficha(id)
 	{
//		window.open("archivo/formatos/ficha.php?id="+id,"_blank");
	}
//---------------------------
function rep()
 	{
//	if((document.form1.obuscar.value=="  " || document.form1.obuscar.value==" " || document.form1.obuscar.value=="") && document.form1.optradio.value!=4 && document.form1.optradio.value!=7){}
//	else	
//		{
//		window.open("archivo/reporte/1_bienes.php","_blank");
//		}
	}
//----------------
function eliminar(id)
	{
	Swal.fire({
		title: 'Estas seguro de eliminar el Registro?',
		text: "Esta acción no se puede revertir!",
		icon: 'question',
		showCancelButton: true,
		confirmButtonColor: '#3085d6',
		cancelButtonColor: '#d33',
		confirmButtonText: 'Si, borrar!',
		cancelButtonText: 'Cancelar'
		}).then((result) => {
		if (result.isConfirmed) {
			//-----------------------
			var parametros = "id=" + id;
			$.ajax({
			url: "archivo/2c_eliminar.php",
			type: "POST",
			dataType:"json",
			data: parametros,
			success: function(data) {
			if (data.tipo=="info")
				{	alertify.success(data.msg);	
					busca_empleados();
				}
			else
				{	alertify.alert(data.msg);	}
			}
			});
			//-----------------------
			}
		})
}
//--------------------------------
function devolver()
 {
	var parametros = $("#form999").serialize(); 
	$.ajax({  
		type : 'POST',
		url  : 'archivo/2f_guardar.php?id='+ document.form999.oid.value,
		dataType:"json",
		data:  parametros, 
		success:function(data) {  	
			if (data.tipo=="info")
				{	alertify.success(data.msg);	
					$('#modal_largo .close').click(); 
					busca_empleados();
				}
			else
				{	Swal.fire({
					  icon: "error",
					  title: "Error...",
					  text: data.msg,
					});	}
			}  
		});
 }
//--------------------------------
function guardar()
 {
	var parametros = $("#form999").serialize(); 
	$.ajax({  
		type : 'POST',
		url  : 'archivo/2e_guardar.php?id='+ document.form999.oid.value,
		dataType:"json",
		data:  parametros, 
		success:function(data) {  	
			if (data.tipo=="info")
				{	alertify.success(data.msg);	
					$('#modal_largo .close').click(); 
					busca_empleados();
				}
			else
				{	Swal.fire({
					  icon: "error",
					  title: "Error...",
					  text: data.msg,
					});	}
			}  
		});
 }
//--------------------------------------------
function agregar()
	{
	$('#modal_lg').html('<div align="center"><div class="spinner-border" role="status"></div><br><strong>Un momento, por favor...</strong></div>');
	$('#modal_lg').load('archivo/2b_modal.php');
	}
//----------------
function basicos(id){
	$('#modal_lg').html('<div align="center"><div class="spinner-border" role="status"></div><br><strong>Un momento, por favor...</strong></div>');
	$('#modal_lg').load('archivo/2b_modal.php?id='+id);
	}
//----------------
function expediente(id){
	$('#modal_lg').html('<div align="center"><div class="spinner-border" role="status"></div><br><strong>Un momento, por favor...</strong></div>');
	$('#modal_lg').load('archivo/2c_modal.php?id='+id);
	}
//----------------
function busca_empleados()
	{
//	if((document.form1.obuscar.value=="  " || document.form1.obuscar.value==" " || document.form1.obuscar.value=="") && document.form1.optradio.value!=4 && document.form1.optradio.value!=7){}
	if(document.form1.optradio.value==3 || document.form1.optradio.value==5)	{
		$('#div2').html('<div align="center"><div class="spinner-border" role="status"></div><br><strong>Un momento, por favor...</strong></div>');
		$('#div2').load('archivo/2aA_tabla.php?valor='+cambia(document.form1.obuscar.value)+'&tipo='+document.form1.optradio.value);
			}
	if(document.form1.optradio.value==4)	{
		$('#div2').html('<div align="center"><div class="spinner-border" role="status"></div><br><strong>Un momento, por favor...</strong></div>');
		$('#div2').load('archivo/2a_tabla.php?valor='+cambia(document.form1.obuscar.value)+'&tipo='+document.form1.optradio.value);
			}
	}
</script>