<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');

if ($_SESSION['VERIFICADO'] != "SI") { 
header ("Location: ../validacion.php?opcion=val"); 
exit(); }

$acceso=80;
//------- VALIDACION ACCESO USUARIO
include_once "../validacion_usuario.php";
//-----------------------------------
?>
<form id="form1" name="form1" method="post" onsubmit="return evitar();" >
        <div align="center" class="TituloP">Relaci&oacute;n de Solicitudes de Viaticos</div>
		<br >
<div  class="text-right mb-3"><a href="" class="btn btn-outline-primary btn-rounded btn-sm font-weight-bold" onclick="agregar();" data-toggle="modal" data-target="#modal_largo" data-backdrop="static" data-keyboard="false"><i class="fas fa-plus-circle" ></i> Agregar Solicitud</a></div>
		<diw class="row ml-3">
            <strong>Opciones para Filtrar:</strong>
			
            <div class="form-check ml-3">
              <label class="form-check-label">
                    <input type="radio" class="form-check-input" name="optradio" value="1" onclick="ver();">
                Funcionario</label>
            </div>
           
            <div class="form-check ml-3">
              <label class="form-check-label">
                    <input type="radio" class="form-check-input" name="optradio" value="2" onclick="ver();">
                Concepto</label>
            </div>
           
            <div class="form-check ml-3">
              <label class="form-check-label">
                    <input type="radio" class="form-check-input" name="optradio" value="3" onclick="ver();">
                Zona</label>
            </div>
           
            <div class="form-check ml-3">
                <label class="form-check-label">
                    <input type="radio" class="form-check-input" name="optradio" value="0" checked="checked" onclick="buscar();ver();">
                    Pendientes
                </label>
            </div>
			<div class="form-check ml-3">
                <label class="form-check-label">
                    <input type="radio" class="form-check-input" name="optradio" value="5" onclick="ver();buscar();" >
                   Solicitadas
                </label>
            </div>
			
			<div class="form-check ml-3">
                <label class="form-check-label">
                    <input type="radio" class="form-check-input" name="optradio" value="10" onclick="ver();buscar();" >
                   Aprobadas
                </label>
            </div>
			
			<div class="form-check ml-3">
                <label class="form-check-label">
                    <input type="radio" class="form-check-input" name="optradio" value="6" onclick="ver();buscar();" >
                   Dia Actual
                </label>
            </div>
			<div class="form-check ml-3">
                <label class="form-check-label">
                    <input type="radio" class="form-check-input" name="optradio" value="7" onclick="ver();">
                   Por Fecha
                </label>
            </div>
			<div class="form-check ml-3">
                <label class="form-check-label">
                    <input type="radio" class="form-check-input" name="optradio" value="4" onclick="ver();buscar();" >
                   Ver Todas
                </label>
            </div>
			
        </diw>
<div id="cuadro"><input name="obuscar" id="obuscar" type="text" size="100" class="form-control" onkeyup="buscar2(event);"/></div>
<div id="fechas"><table><tr><td align="left" valign="top">
<input class="form-control" type="text" name="OFECHA" id="OFECHA" size="15" placeholder="Desde" value="<?php echo date('d/m/Y'); ?>" style="text-align:center" /></td><td>
<input class="form-control" type="text" name="OFECHA2" id="OFECHA2" size="15" placeholder="Hasta" value="<?php echo date('d/m/Y'); ?>" style="text-align:center" /></td><td>
<button type="button" id="botonb" class="btn btn-primary" onClick="buscar();"><i class="fas fa-search mr-2"></i>Buscar</button></td></tr></table></div>

</br>
	<div id="div1"></div>
</form>
<script language="JavaScript">
$('#cuadro').hide();
$('#fechas').hide();
$('#OFECHA').datepicker();
$('#OFECHA2').datepicker();
//------------------
function aprobar(id, oficina)
	{
	alertify.confirm("Estas seguro de Aprobar la Solicitud?",  
	function()
		{
		var parametros = "id=" + id + "&oficina" + oficina; 
		$.ajax({  
			type : 'POST',
			url  : 'viaticos/1k2_guardar.php',
			dataType:"json",
			data:  parametros, 
			success:function(data) {  	
				if (data.tipo=="info")
					{	alertify.success(data.msg);	buscar(); 
						//window.open("viaticos/formatos/1_memo.php?id="+data.id,"_blank");
						//window.open("viaticos/formatos/2_solicitud.php?id="+data.id,"_blank");
					}
				else
					{	alertify.alert(data.msg);	}
				//--------------
				} 
			 
			});
		});
	}
//------------------
function solicitar(id, oficina)
	{
	alertify.confirm("Estas seguro de generar la Solicitud?",  
	function()
		{
		var parametros = "id=" + id + "&oficina" + oficina; 
		$.ajax({  
			type : 'POST',
			url  : 'viaticos/1k_guardar.php',
			dataType:"json",
			data:  parametros, 
			success:function(data) {  	
				if (data.tipo=="info")
					{	alertify.success(data.msg);	buscar(); 
						//window.open("viaticos/formatos/1_memo.php?id="+data.id,"_blank");
						//window.open("viaticos/formatos/2_solicitud.php?id="+data.id,"_blank");
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
	alertify.confirm("Estas seguro de eliminar la Solicitud?",  
	function()
			{ 
			var parametros = "id=" + id;
			$.ajax({
			url: "viaticos/1h_eliminar.php",
			type: "POST",
			data: parametros,
			success: function(r) {
			alertify.success('Solicitud Eliminada Correctamente');
			//--------------
			buscar();
			}
			});
		});
	}
//----------------- PARA VALIDAR
function validar_detalle()
	{
	error = 0;
	if(document.form999.txt_cedula.value=="0")	
		{	alertify.alert("Seleccionar eel Empleado");			error = 1;  }
	if(document.form999.txt_zona.value=="0")	
		{	alertify.alert("Debe Indicar la Zona");			error = 1;  }
	if(document.form999.txt_concepto.value=="")	
		{	 document.form999.txt_concepto.focus(); 	alertify.alert("Debe Indicar el Concepto");			error = 1;  }
	if(document.form999.txt_desde.value=="0")	
		{	 document.form999.txt_desde.focus(); 	alertify.alert("Debe indicar la fecha Inicial");			error = 1;  }
	if(document.form999.txt_hasta.value=="0")	
		{	 document.form999.txt_hasta.focus(); 	alertify.alert("Debe indicar la fecha Final");			error = 1;  }
	return error;
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
			url  : 'viaticos/1e_guardar.php',
			dataType:"json",
			data:  parametros, 
			success:function(data) {  	
					if (data.tipo=="info")
						{	alertify.success(data.msg); $('#modal_lg .close').click(); buscar(); }
					else
					{	alertify.alert(data.msg);	}
				}  
			});
		}
	}
//----------------
function empleado(id,zona,contralor){
	$('#modal_lg').html('<div align="center"><div class="spinner-border" role="status"></div><br><strong>Un momento, por favor...</strong></div>');
	$('#modal_lg').load('viaticos/1g_modal.php?id='+id+'&zona='+zona+'&contralor='+contralor);
	}
//----------------
function editar(id){
	$('#modal_lg').html('<div align="center"><div class="spinner-border" role="status"></div><br><strong>Un momento, por favor...</strong></div>');
	$('#modal_lg').load('viaticos/1b_modal.php?id='+id);
	}
//-----------------------
function agregar()
	{
	$('#modal_lg').html('<div align="center"><div class="spinner-border" role="status"></div><br><strong>Un momento, por favor...</strong></div>');
	$('#modal_lg').load('viaticos/1b_modal.php?id=0');
	}
//---------------------------
function buscar2(e)
 	 {
	 (e.keyCode)?k=e.keyCode:k=e.which;
	// Si la tecla pulsada es enter (codigo ascii 13)
	if(k==13)
		{buscar();}
	}
//----------------
function buscar(){
	//valor = document.form1.obuscar.value; 
	//valor = valor.replace(/ /g, '_');
	$('#div1').html('<div align="center"><div class="spinner-border" role="status"></div><br><strong>Un momento, por favor...</strong></div>');
	$('#div1').load('viaticos/1a_tabla.php?valor='+cambia(document.form1.obuscar.value)+'&tipo='+document.form1.optradio.value+'&fecha1='+document.form1.OFECHA.value+'&fecha2='+document.form1.OFECHA2.value);
}
//---------------------
function imprimir(id)
	{	
	//window.open("viaticos/formatos/1_memo.php?id="+id,"_blank");
	window.open("viaticos/formatos/2_solicitud.php?id="+id,"_blank");
	}
//---------------------------
function ver()
 	{
	if (document.form1.optradio.value==1 || document.form1.optradio.value==2 || document.form1.optradio.value==3)
	 	{
		$('#cuadro').show();
		$('#fechas').hide();
		}
	if (document.form1.optradio.value==7)
	 	{
		$('#cuadro').hide();
		$('#fechas').show();
		}
	if (document.form1.optradio.value!=1 && document.form1.optradio.value!=2 && document.form1.optradio.value!=3 && document.form1.optradio.value!=7)
	 	{
		$('#cuadro').hide();
		$('#fechas').hide();
		}
	}

</script>