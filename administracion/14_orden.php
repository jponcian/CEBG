<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');

if ($_SESSION['VERIFICADO'] != "SI") { 
header ("Location: ../validacion.php?opcion=val"); 
exit(); }

$acceso=24;
//------- VALIDACION ACCESO USUARIO
include_once "../validacion_usuario.php";
//-----------------------------------
?>
<form id="form1" name="form1" method="post" onsubmit="return evitar();" >
        <div align="center" class="TituloP">Relaci&oacute;n de Orden<!-- de Pago--> Financiera</div>
		<br >
<div  class="text-right mb-3"><a href="" class="btn btn-outline-primary btn-rounded btn-sm font-weight-bold" onclick="agregar();" data-toggle="modal" data-target="#modal_largo" data-backdrop="static" data-keyboard="false"><i class="fas fa-plus-circle" ></i> Agregar Orden</a></div>
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
			url  : 'administracion/14j_guardar.php',
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
	$('#modal_lg').load('administracion/14b_modal.php');
	}
//----------------
function buscar2(){
	document.form1.optradio.value=3;
	$('#div1').html('<div align="center"><div class="spinner-border" role="status"></div><br><strong>Un momento, por favor...</strong></div>');
	$('#div1').load('administracion/14a_tabla.php?valor='+cambia(document.form1.obuscar.value)+'&tipo=3');
}
//----------------
function buscar(){
if((document.form1.obuscar.value=="  " || document.form1.obuscar.value==" " || document.form1.obuscar.value=="") && document.form1.optradio.value<3){}
else	{
		//valor = document.form1.obuscar.value; 
		//valor = valor.replace(/ /g, '_');
		$('#div1').html('<div align="center"><div class="spinner-border" role="status"></div><br><strong>Un momento, por favor...</strong></div>');
		$('#div1').load('administracion/14a_tabla.php?valor='+cambia(document.form1.obuscar.value)+'&tipo='+document.form1.optradio.value);
		}
}
//---------------------
function imprimir(id, tipo)
	{
	if (tipo==1)	{window.open("administracion/formatos/5_ordenb.php?id="+id,"_blank");}
				else	{window.open("administracion/formatos/5_orden.php?id="+id,"_blank");}
	}
</script>