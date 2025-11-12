<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');

if ($_SESSION['VERIFICADO'] != "SI") { 
header ("Location: ../validacion.php?opcion=val"); 
exit(); }

$acceso=126;
//------- VALIDACION ACCESO USUARIO
include_once "../validacion_usuario.php";
//-----------------------------------
?>
<form id="form1" name="form1" method="post" onsubmit="return evitar();" >
        <div align="center" class="TituloP">Movimientos entre Areas</div>
		<br >
<div  class="text-right mb-3"><a href="" class="btn btn-outline-primary btn-rounded btn-sm font-weight-bold" onclick="agregar();" data-toggle="modal" data-target="#modal_largo" data-backdrop="static" data-keyboard="false"><i class="fas fa-plus-circle" ></i> Agregar Movimiento</a></div>
		<diw class="row ml-3">
            <strong>Opciones de Busqueda:</strong>
             <!--<div class="form-check ml-3">
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
                   Aprobados
                </label>
            </div>
			<!--<div class="form-check ml-3">
                <label class="form-check-label">
                    <input type="radio" class="form-check-input" name="optradio" value="4" onclick="buscar()" >
                   Ver Todos
                </label>
            </div>-->
        </diw>
 <input name="obuscar" id="obuscar" type="text" size="100" class="form-control" onchange="buscar()" />

 <br>
 
 <div id="div1"></div>
</form>
<script language="JavaScript">
//--------------------- PARA BUSCAR
function listar_bienes(){
	$('#div3').html('<div align="center"><div class="spinner-border" role="status"></div><br><strong>Un momento, por favor...</strong></div>');
	$('#div3').load('bienes/2d_tabla.php?id='+cambia(document.form999.txt_numero.value)+'&nombre='+cambia(document.form999.txt_bien.value)+'&area='+(document.form999.txt_origen.value));
}
//------------------
function generar_solicitud(id)
	{
	alertify.confirm("Estas seguro de generar el Movimiento?",  
	function()
		{
		var parametros = "id=" + id; 
		$.ajax({  
			type : 'POST',
			url  : 'bienes/2j_guardar.php?',
			dataType:"json",
			data:  parametros, 
			success:function(data) {  	
				if (data.tipo=="info")
					{	$('#modal_normal .close').click();	alertify.success(data.msg);	buscar(); 
						//window.open("bienes/formatos/10_orden.php?p=1&id="+data.id,"_blank");
						//window.open("bienes/formatos/8_recepcion.php?p=1&id="+data.id,"_blank");
					}
				else
					{	alertify.alert(data.msg);	}
				//--------------
				} 
			 
			});
		});
	}
//------------------------------ PARA ELIMINAR
function eliminar(id)
	{
	alertify.confirm("Estas seguro de Eliminar el Movimiento?",  
	function()
			{ 
			var parametros = "id=" + id;
			$.ajax({
			url: "bienes/2h_eliminar.php",
			type: "POST",
			data: parametros,
			success: function(r) {
			alertify.success('Movimiento Eliminado Correctamente');
			//--------------
			listar_bienes();
			}
			});
		});
	}
//------------------------------ PARA ELIMINAR
function reasignar(id)
	{
	 if (validar_detalle()==0)
		{
		alertify.confirm("Estas seguro de Reasignar el Bien?",  
		function()
				{ 
				var parametros = "id=" + id + "&origen="+document.form999.txt_origen.value+ "&destino="+document.form999.txt_destino.value;
				$.ajax({
				url: "bienes/2f_reasignar.php",
				type: "POST",
				data: parametros,
				success: function(r) {
				alertify.success('Registro Reasignado Correctamente');
				//--------------
				listar_bienes();
				}
				});
			});
		}
	}
//----------------- PARA VALIDAR
function validar_detalle()
	{
	error = 0;
	if(document.form999.txt_origen.value=="0")	
		{	 document.form999.txt_origen.focus(); 	alertify.alert("Debe Seleccionar el Area de Origen");			error = 1;  }
	if(document.form999.txt_destino.value=="0")	
		{	 document.form999.txt_destino.focus(); 	alertify.alert("Debe Seleccionar el Area de Destino");			error = 1;  }
	return error;
	}
//--------------------------- PARA GUARDAR
function listar_bienes2(e)
 	 {
	 (e.keyCode)?k=e.keyCode:k=e.which;
	// Si la tecla pulsada es enter (codigo ascii 13)
	if(k==13)
		{listar_bienes();}
	}
//-------------
function combo()
{
	$.ajax({
        type: "POST",
        url: 'bienes/2c_combo.php?origen='+document.form999.txt_origen.value,
        success: function(resp){
            $('#txt_destino').html(resp);
			listar_bienes();
        }
    });
}
//----------------
function buscar2(){
	document.form1.optradio.value=3;
	$('#div1').html('<div align="center"><div class="spinner-border" role="status"></div><br><strong>Un momento, por favor...</strong></div>');
	$('#div1').load('bienes/2a_tabla.php?valor='+cambia(document.form1.obuscar.value)+'&tipo=3');
}
//----------------
function buscar(){
if((document.form1.obuscar.value=="  " || document.form1.obuscar.value==" " || document.form1.obuscar.value=="") && document.form1.optradio.value<3){}
else	{
		$('#div1').html('<div align="center"><div class="spinner-border" role="status"></div><br><strong>Un momento, por favor...</strong></div>');
		$('#div1').load('bienes/2a_tabla.php?valor='+cambia(document.form1.obuscar.value)+'&tipo='+document.form1.optradio.value);
		}
}
//-----------------------
function agregar()
	{
	$('#modal_lg').html('<div align="center"><div class="spinner-border" role="status"></div><br><strong>Un momento, por favor...</strong></div>');
	$('#modal_lg').load('bienes/2b_modal.php');
	}
//---------------------
function imprimir(origen, destino, estatus, id)
	{	
	window.open("bienes/reporte/2_mov_internos_21.php?p=1&origen="+origen+"&destino="+destino+"&estatus="+estatus+"&id="+id,"_blank");
	window.open("bienes/reporte/2_mov_internos_31.php?p=1&origen="+origen+"&destino="+destino+"&estatus="+estatus+"&id="+id,"_blank");
	}
</script>