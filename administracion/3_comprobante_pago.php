<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');

//if ($_SESSION['VERIFICADO'] != "SI") { 
//header ("Location: ../validacion.php?opcion=val"); 
//exit(); }

$acceso=30;
//------- VALIDACION ACCESO USUARIO
include_once "../validacion_usuario.php";
//-----------------------------------
?>
<form id="form1" name="form1" method="post" onsubmit="return evitar();" >
        <diw class="row ml-3">
            <strong>Opciones para Filtrar:</strong>
			
			<div class="form-check ml-3">
              <label class="form-check-label">
                    <input type="radio" class="form-check-input" name="optradio" value="1" onclick="ver();">
                Orden</label>
            </div>
           
            <div class="form-check ml-3">
              <label class="form-check-label">
                    <input type="radio" class="form-check-input" name="optradio" value="5" onclick="ver();">
                Descripcion</label>
            </div>
           
            <div class="form-check ml-3">
                <label class="form-check-label">
                    <input type="radio" class="form-check-input" name="optradio" value="10" checked="checked" onclick="ver();buscar();">
                    Pendientes
                </label>
            </div>
			<div class="form-check ml-3">
                <label class="form-check-label">
                    <input type="radio" class="form-check-input" name="optradio" value="15" onclick="ver();buscar();" >
                   Culminadas
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
                    <input type="radio" class="form-check-input" name="optradio" value="3" onclick="ver();">
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

<br>
 
<div id="div1"></div></form>
<script language="JavaScript">
$('#cuadro').hide();
$('#fechas').hide();
$('#OFECHA').datepicker();
$('#OFECHA2').datepicker();
//------------------
function guardar(id, tipo)
{
	$('#boton').hide();
	//alertify.alert('Espere mientras la Orden de Pago es Procesada...');
	var parametros = $("#form999").serialize(); 
	$.ajax({  
		type : 'POST',
		url  : 'administracion/2d_guardar.php?tipo='+tipo,
		dataType:"json",
		data:  parametros, 
		success:function(data) {  	
			if (data.tipo=="info")
				{	alertify.success(data.msg);	$('#modal_lg .close').click(); buscar();}
			else
				{	alertify.alert(data.msg);	$('#boton').show(); }
			//--------------
			} 
		 
		});
}
//---------------------------
function rep()
 	{
	if (document.form1.optradio.value>0)
		{
			//{
			window.open("administracion/reporte/1b_rep_orden.php","_blank");
			//}
		}
	}
//---------------------------
function buscar2(e)
 	 {
	 (e.keyCode)?k=e.keyCode:k=e.which;
	// Si la tecla pulsada es enter (codigo ascii 13)
	if(k==13)
		{buscar();}
	}
//---------------------------
function ver()
 	{
	if (document.form1.optradio.value==1 || document.form1.optradio.value==2 || document.form1.optradio.value==5)
	 	{
		$('#cuadro').show();
		$('#fechas').hide();
		}
	if (document.form1.optradio.value==3)
	 	{
		$('#cuadro').hide();
		$('#fechas').show();
		}
	if (document.form1.optradio.value!=1 && document.form1.optradio.value!=2 && document.form1.optradio.value!=3 && document.form1.optradio.value!=5)
	 	{
		$('#cuadro').hide();
		$('#fechas').hide();
		}
	}
//--------------------- PARA BUSCAR
function enviar_datos(id, tipo){
	$('#modal_lg').html('<div align="center"><div class="spinner-border" role="status"></div><br><strong>Un momento, por favor...</strong></div>');
	$('#modal_lg').load('administracion/2c_modal.php?id='+id+'&tipo='+tipo);
}
//---------------------
function imprimir_ord(id, tipo)
	{	
	if (tipo=="NOMINA")
		{	window.open("administracion/formatos/1_orden_pago.php?id="+id,"_blank");	}
	}
//---------------------
function imprimir(id, tipo)
	{	
	if (tipo=="NOMINA")
		{	window.open("administracion/formatos/2_comprobante_pago.php?id="+id,"_blank");	}
	if (tipo=="NOMINA MANUAL")
		{	window.open("administracion/formatos/2a_comprobante_pago.php?id="+id,"_blank");	}
	}
//----------------
function buscar(){
	$('#div1').html('<div align="center"><div class="spinner-border" role="status"></div><br><strong>Un momento, por favor...</strong></div>');
	$('#div1').load('administracion/3a_tabla.php?valor='+cambia(document.form1.obuscar.value)+'&tipo='+document.form1.optradio.value+'&fecha1='+document.form1.OFECHA.value+'&fecha2='+document.form1.OFECHA2.value);
}
//------------------
buscar();
</script>