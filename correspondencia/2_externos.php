<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');

if ($_SESSION['VERIFICADO'] != "SI") { 
header ("Location: ../validacion.php?opcion=val"); 
exit(); }

$acceso=1;
//------- VALIDACION ACCESO USUARIO
include_once "../validacion_usuario.php";
//-----------------------------------
?>
<form id="form1" name="form1" method="post" onSubmit="return evitar();" >
        <div align="center" class="TituloP">Recepción de Correspondencia Externa</div>
		<br >
<div  class="text-right mb-3"><a href="" class="btn btn-outline-primary btn-rounded btn-sm font-weight-bold" onclick="agregar(0);" data-toggle="modal" data-target="#modal_largo" ><i class="fas fa-plus-circle" ></i> Recibir</a></div>
		<diw class="row ml-3">
            <strong>Opciones de Busqueda:</strong>
             <!--<div class="form-check ml-3">
              <label class="form-check-label">
                    <input type="radio" class="form-check-input" name="optradio" value="1" >
                N&uacute;mero</label>
            </div>-->
           
            <div class="form-check ml-3">
                <label class="form-check-label">
                    <input type="radio" class="form-check-input" name="optradio" value="2" onclick="ver();">
                    Busqueda
                </label>
            </div>
			<div class="form-check ml-3">
                <label class="form-check-label">
                    <input type="radio" class="form-check-input" name="optradio" value="3" onclick="ver();buscar()" checked="checked" >
                   Pendiente
                </label>
            </div>
			<div class="form-check ml-3">
                <label class="form-check-label">
                    <input type="radio" class="form-check-input" name="optradio" value="7" onclick="ver();buscar()" >
                   Recibida
                </label>
            </div>
			<div class="form-check ml-3">
                <label class="form-check-label">
                    <input type="radio" class="form-check-input" name="optradio" value="8" onclick="ver();">
                   Por Fecha
                </label>
            </div>
        </diw>

<div id="cuadro"><input name="obuscar" id="obuscar" type="text" size="80" placeholder="Escriba aqui para buscar..." class="form-control" onKeyPress="buscar();" /></div>
<div id="fechas"><table><tr><td align="left" valign="top">
<input class="form-control" type="text" name="OFECHA" id="OFECHA" size="15" placeholder="Desde" value="<?php echo date('01/m/Y'); ?>" style="text-align:center" /></td><td>
<input class="form-control" type="text" name="OFECHA2" id="OFECHA2" size="15" placeholder="Hasta" value="<?php echo date('d/m/Y'); ?>" style="text-align:center" /></td><td>
<button type="button" id="botonb" class="btn btn-primary" onClick="buscar();"><i class="fas fa-search mr-2"></i>Buscar</button></td></tr></table></div>
	
 <br>
 
 <div id="div1"></div>
</form>
<script language="JavaScript">
$('#cuadro').show();
$('#fechas').hide();
$('#OFECHA').datepicker();
$('#OFECHA2').datepicker();
//---------------------
buscar(); ver();
//---------------------------
function ver()
 	{
	if (document.form1.optradio.value==2 || document.form1.optradio.value==8)
	 	{
		$('#cuadro').show();
		$('#fechas').hide();
		}
	if (document.form1.optradio.value==8)
	 	{
		$('#cuadro').hide();
		$('#fechas').show();
		}
	if (document.form1.optradio.value!=2 && document.form1.optradio.value!=8)
	 	{
		$('#cuadro').hide();
		$('#fechas').hide();
		}
	}
//---------------------
function borrar(id)
	{
	alertify.confirm("Estas seguro de eliminar la Correspondencia?",  
	function()
			{ 
			var parametros = "id=" + id;
			$.ajax({
			url: "correspondencia/2g_eliminar.php",
			type: "POST",
			data: parametros,
			success: function(r) {
			alertify.success('Correspondencia Eliminada Correctamente');
			//--------------
			buscar(); 
			}
			});
		});
	}
//---------------------
function buscar3(e)
 	 {
	 (e.keyCode)?k=e.keyCode:k=e.which;
	// Si la tecla pulsada es enter (codigo ascii 13)
	if(k==13)
		{buscar();}
	}
//------------------
function recibir_memo(id, detalle)
	{
	alertify.confirm("Estas seguro de Recibir la Correspondencia?",  
	function()
		{
		var parametros = "id=" + id+ "&detalle=" + detalle; 
		$.ajax({  
			type : 'POST',
			url  : 'correspondencia/2l_guardar.php?',
			dataType:"json",
			data:  parametros, 
			success:function(data) {  	
				if (data.tipo=="info")
					{	alertify.success(data.msg);	buscar(); 
						//window.open("correspondencia/formatos/memo_dir.php?p=2&origen=0&destino=0&estatus=5&id="+id,"_blank");
					}
				else
					{	alertify.alert(data.msg);	}
				//--------------
				} 
			 
			});
		});
	}
//------------------
function generar_memo(id, id_origen, anno)
	{
	alertify.confirm("Estas seguro de Aprobar el Memorando?",  
	function()
		{
		var parametros = "id=" + id + "&origen="+ id_origen+ "&anno="+ anno; 
		$.ajax({  
			type : 'POST',
			url  : 'correspondencia/2j_guardar.php?',
			dataType:"json",
			data:  parametros, 
			success:function(data) {  	
				if (data.tipo=="info")
					{	alertify.success(data.msg);	buscar(); 
						window.open("correspondencia/formatos/memo_dir.php?p=2&origen=0&destino=0&estatus=5&id="+id,"_blank");
					}
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
	if(document.form999.txt_origen.value=="0")	
		{	 document.form999.txt_origen.focus(); 	alertify.alert("Debe Seleccionar el Area de Origen");			error = 1;  }
	if(document.form999.txt_destino.value=="0")	
		{	 document.form999.txt_destino.focus(); 	alertify.alert("Debe Seleccionar el Area de Destino");			error = 1;  }
	return error;
	}
//----------------
function buscar2(){
	document.form1.optradio.value=3;
	$('#div1').html('<div align="center"><div class="spinner-border" role="status"></div><br><strong>Un momento, por favor...</strong></div>');
	$('#div1').load('correspondencia/2a_tabla.php?valor='+cambia(document.form1.obuscar.value)+'&tipo=3');
}
//----------------
function buscar(){
if((document.form1.obuscar.value=="  " || document.form1.obuscar.value==" " || document.form1.obuscar.value=="") && document.form1.optradio.value<2){}
else	{
		$('#div1').html('<div align="center"><div class="spinner-border" role="status"></div><br><strong>Un momento, por favor...</strong></div>');
		$('#div1').load('correspondencia/2a_tabla.php?valor='+cambia(document.form1.obuscar.value)+'&tipo='+document.form1.optradio.value+'&fecha1='+document.form1.OFECHA.value+'&fecha2='+document.form1.OFECHA2.value);
		}
}
//-----------------------
function editar(id)
	{
	$('#modal_lg').html('<div align="center"><div class="spinner-border" role="status"></div><br><strong>Un momento, por favor...</strong></div>');
	$('#modal_lg').load('correspondencia/2b_modal.php?id='+id);
	}
//-----------------------
function aprobar_memo(id, destino)
	{
	$('#modal_lg').html('<div align="center"><div class="spinner-border" role="status"></div><br><strong>Un momento, por favor...</strong></div>');
	$('#modal_lg').load('correspondencia/2c_modal.php?id='+id+'&destino='+destino);
	}
//-----------------------
function agregar(id)
	{
	$('#modal_lg').html('<div align="center"><div class="spinner-border" role="status"></div><br><strong>Un momento, por favor...</strong></div>');
	$('#modal_lg').load('correspondencia/2b_modal.php?id='+id);
	}
//---------------------
function imprimir(id)
	{	
	window.open("correspondencia/memos_ext/"+id+"_0.pdf","_blank");
	}
//----------------
function direccion(id){
	$('#modal_lg').html('<div align="center"><div class="spinner-border" role="status"></div><br><strong>Un momento, por favor...</strong></div>');
	$('#modal_lg').load('correspondencia/2g_modal.php?id='+id);
	}
</script>