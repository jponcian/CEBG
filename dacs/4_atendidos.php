<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');

if ($_SESSION['VERIFICADO'] != "SI") { 
header ("Location: ../validacion.php?opcion=val"); 
exit(); }

$acceso=42;
//------- VALIDACION ACCESO USUARIO
include_once "../validacion_usuario.php";
//-----------------------------------
?>
<form id="form1" name="form1" method="post" onsubmit="return evitar();" >
 <div align="center" class="TituloP"><i class="fa-solid fa-magnifying-glass"></i>  Buscador...</div>
		<br >
		<br >		<diw class="row ml-3">
            <strong>Opciones de Busqueda:</strong>
            
            <div class="form-check ml-3">
              <label class="form-check-label">
                    <input type="radio" class="form-check-input" name="optradio" value="5" onclick="ver();">
                Visitante</label>
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
                  <strong>Todas</strong>
                </label>
            </div>
  </diw>
<br>
<div id="cuadro"><input name="obuscar" id="obuscar" type="text" size="80" placeholder="Escriba aqui para buscar..." class="form-control" onKeyPress="buscar2(event,this);" /></div>
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
//---------------------------
function rep()
 	{
	window.open("dacs/reporte/visitas_detalle.php","_blank");	
	}
//---------------------
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
	if (document.form1.optradio.value==4 || document.form1.optradio.value==6 || document.form1.optradio.value==7)
	 	{
		$('#cuadro').hide();
		$('#fechas').hide();
		}
	}
//----------------
function buscar(){
if (((document.form1.optradio.value==1 || document.form1.optradio.value==2 || document.form1.optradio.value==5 || document.form1.optradio.value==8) && document.form1.obuscar.value!='') || (document.form1.optradio.value==3 && document.form1.OFECHA.value!='' && document.form1.OFECHA2.value!='') || (document.form1.optradio.value==4) || (document.form1.optradio.value==6) || (document.form1.optradio.value==7))
	{
	$('#div1').html('<div align="center"><div class="spinner-border" role="status"></div><br><strong>Un momento, por favor...</strong></div>');
	$('#div1').load('dacs/4a_tabla.php?valor='+cambia(document.form1.obuscar.value)+'&tipo='+document.form1.optradio.value+'&fecha1='+document.form1.OFECHA.value+'&fecha2='+document.form1.OFECHA2.value);
	}
}
</script>