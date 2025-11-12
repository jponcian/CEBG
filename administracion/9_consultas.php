<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');

if ($_SESSION['VERIFICADO'] != "SI") { 
header ("Location: ../validacion.php?opcion=val"); 
exit(); }

$acceso=38;
//------- VALIDACION ACCESO USUARIO
include_once "../validacion_usuario.php";
//-----------------------------------
?>
<form id="form1" name="form1" method="post" onsubmit="return evitar();" >
 <div align="center" class="TituloP">Consultar Comprobante de Pago</div>
		<br >
		<diw class="row ml-3">
            <strong>Opciones de Busqueda:</strong>
            
			<div class="form-check ml-3">
              <label class="form-check-label">
                    <input type="radio" class="form-check-input" name="optradio" value="7" onclick="ver();buscar();">
                Anulados</label>
            </div>
			
			<div class="form-check ml-3">
              <label class="form-check-label">
                    <input type="radio" class="form-check-input" name="optradio" value="1" onclick="ver();">
                Comprobante</label>
            </div>
           
            <div class="form-check ml-3">
              <label class="form-check-label">
                    <input type="radio" class="form-check-input" name="optradio" value="5" onclick="ver();">
                Contribuyente</label>
            </div>
           
            <div class="form-check ml-3">
                <label class="form-check-label">
                    <input type="radio" class="form-check-input" name="optradio" value="2" onclick="ver();" checked="checked" >
                    Descripcion
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
                    <input type="radio" class="form-check-input" name="optradio" value="8" onclick="ver();">
                   Por Fecha de Pago
                </label>
            </div>
			<div class="form-check ml-3">
                <label class="form-check-label">
                    <input type="radio" class="form-check-input" name="optradio" value="9" onclick="ver();">
                   Ref. del Pago
                </label>
            </div>
			<div class="form-check ml-3">
                <label class="form-check-label">
                    <input type="radio" class="form-check-input" name="optradio" value="4" onclick="ver();buscar();" >
                   Ver Todas
                </label>
            </div>
			
        </diw>

<div id="cuadro"><input name="obuscar" id="obuscar" type="text" size="100" class="form-control" onkeyup="buscar2(event)" /></div>
<div id="fechas"><table><tr><td align="left" valign="top">
<input class="form-control" type="text" name="OFECHA" id="OFECHA" size="15" placeholder="Desde" value="<?php echo date('d/m/Y'); ?>" style="text-align:center" /></td><td>
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
//--------------------------- 
function buscar2(e)
 	 {
	 (e.keyCode)?k=e.keyCode:k=e.which;
	// Si la tecla pulsada es enter (codigo ascii 13)
	if(k==13)
		{buscar();}
	}
//---------------------
function imprimir(id, tipo)
	{	
	if (tipo=="FINANCIERA")
		{	window.open("administracion/formatos/1b_orden_pago.php?id="+id,"_blank");	}
	if (tipo=="ORDEN" || tipo=="MANUAL")
		{	window.open("administracion/formatos/1a_orden_pago.php?id="+id,"_blank");	}
	if (tipo=="NOMINA")
		{	window.open("administracion/formatos/1_orden_pago.php?id="+id,"_blank");	}
	}
//---------------------
function imprimir2(id, tipo)
	{	
	if (tipo=="ORDEN" || tipo=="MANUAL")
		{	window.open("administracion/formatos/2a_comprobante_pago.php?id="+id,"_blank");	}
	if (tipo=="FINANCIERA")
		{	window.open("administracion/formatos/2b_comprobante_pago.php?id="+id,"_blank");	}
	if (tipo=="NOMINA")
		{	window.open("administracion/formatos/2_comprobante_pago.php?id="+id,"_blank");	}
	if (tipo=="CHEQUE")
		{	window.open("administracion/formatos/3_cheque.php?id="+id,"_blank");	}
	}
//---------------------------
function ver()
 	{
	if (document.form1.optradio.value==1 || document.form1.optradio.value==2 || document.form1.optradio.value==5 || document.form1.optradio.value==9)
	 	{
		$('#cuadro').show();
		$('#fechas').hide();
		}
	if (document.form1.optradio.value==3 || document.form1.optradio.value==8)
	 	{
		$('#cuadro').hide();
		$('#fechas').show();
		}
	if (document.form1.optradio.value==4 || document.form1.optradio.value==6 || document.form1.optradio.value==7)
	 	{
		$('#cuadro').hide();
		$('#fechas').hide();
		}
	}
//---------------------------
function rep()
 	{
	if (document.form1.optradio.value>0)
		{
		//if (((document.form1.optradio.value==1 || document.form1.optradio.value==2 || document.form1.optradio.value==5) && document.form1.obuscar.value!='') || (document.form1.optradio.value==3 && document.form1.OFECHA.value!='' && document.form1.OFECHA2.value!='') || (document.form1.optradio.value==4))
			//{
			window.open("administracion/reporte/2_rep_comprobante.php","_blank");
			//}
		//----------------
		}
	}
//----------------
function buscar(){
if (((document.form1.optradio.value==1 || document.form1.optradio.value==2 || document.form1.optradio.value==5 || document.form1.optradio.value==9) && document.form1.obuscar.value!='') || ((document.form1.optradio.value==3 || document.form1.optradio.value==8) && document.form1.OFECHA.value!='' && document.form1.OFECHA2.value!='') || (document.form1.optradio.value==4) || (document.form1.optradio.value==6) || (document.form1.optradio.value==7))
	{
	$('#div1').html('<div align="center"><div class="spinner-border" role="status"></div><br><strong>Un momento, por favor...</strong></div>');
	$('#div1').load('administracion/9a_tabla.php?valor='+cambia(document.form1.obuscar.value)+'&tipo='+document.form1.optradio.value+'&fecha1='+document.form1.OFECHA.value+'&fecha2='+document.form1.OFECHA2.value);
	}
}
</script>