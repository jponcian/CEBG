<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');

if ($_SESSION['VERIFICADO'] != "SI") { 
header ("Location: ../validacion.php?opcion=val"); 
exit(); }

$acceso=109;
//------- VALIDACION ACCESO USUARIO
include_once "../validacion_usuario.php";
//-----------------------------------
?>
<form id="form1" name="form1" method="post" onsubmit="return evitar();" >
        <div align="center" class="TituloP">Relaci&oacute;n de Pagos de Nomina</div>
		<br >
		
		<diw class="row ml-3">
            <strong>Opciones de Busqueda:</strong>
           
			<div class="form-check ml-3">
                <label class="form-check-label">
                    <input type="radio" class="form-check-input" name="optradio" value="2" onclick="ver();buscar();" >
                   Pendientes
                </label>
            </div>
			<div class="form-check ml-3">
                <label class="form-check-label">
                    <input type="radio" class="form-check-input" name="optradio" value="3" onclick="ver();">
                   Por Fecha
                </label>
            </div>
			
        </diw>
 <br>
<div id="cuadro"><input name="obuscar" id="obuscar" type="text" size="100" class="form-control" onchange="buscar()" /></div>
<div id="fechas"><table><tr><td align="left" valign="top">
<input class="form-control" type="text" name="OFECHA" id="OFECHA" size="15" placeholder="Desde" value="<?php echo date('d/m/Y'); ?>" style="text-align:center" /></td><td>
<input class="form-control" type="text" name="OFECHA2" id="OFECHA2" size="15" placeholder="Hasta" value="<?php echo date('d/m/Y'); ?>" style="text-align:center" /></td><td>
<button type="button" id="botonb" class="btn btn-primary" onClick="buscar();"><i class="fas fa-search mr-2"></i>Buscar</button></td></tr></table></div>
	
 <div id="div1"></div>
</form>
<script language="JavaScript">
$('#cuadro').hide();
$('#fechas').hide();
$('#OFECHA').datepicker();
$('#OFECHA2').datepicker();
//---------------------------
function ver()
 	{
	if (document.form1.optradio.value==1)
	 	{
		$('#cuadro').show();
		$('#fechas').hide();
		}
	if (document.form1.optradio.value==3)
	 	{
		$('#cuadro').hide();
		$('#fechas').show();
		}
	if (document.form1.optradio.value==2)
	 	{
		$('#cuadro').hide();
		$('#fechas').hide();
		}
	}
//----------------
function buscar(){
	$('#div1').html('<div align="center"><div class="spinner-border" role="status"></div><br><strong>Un momento, por favor...</strong></div>');
	$('#div1').load('presupuesto/6a_tabla.php?valor='+cambia(document.form1.obuscar.value) + '&tipo=' + document.form1.optradio.value+'&fecha1='+document.form1.OFECHA.value+'&fecha2=' + document.form1.OFECHA2.value);
}
//----------------
function imprimir_nom(fecha, tipo)
	{	
	window.open("presupuesto/formatos/3_partidas_nomina.php?fecha="+fecha+"&tipo="+tipo,"_blank");
	}
</script>