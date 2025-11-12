<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');

if ($_SESSION['VERIFICADO'] != "SI") { 
header ("Location: ../validacion.php?opcion=val"); 
exit(); }

$acceso=36;
//------- VALIDACION ACCESO USUARIO
include_once "../validacion_usuario.php";
//-----------------------------------
?>
<form id="form1" name="form1" method="post" onsubmit="return evitar();" >
 <div align="center" class="TituloP">Consultar Orden de Pago</div>
		<br >
<div class="row mb-2 ml-3">
	<strong>Tipo de Orden:</strong>
	<div class="form-check ml-3">
		<label class="form-check-label">
		<input type="radio" class="form-check-input" name="op_tipo" value="1" onclick="buscar();">Solo Ordenes</label>
	</div>
	<div class="form-check ml-3">
		<label class="form-check-label">
		<input type="radio" class="form-check-input" name="op_tipo" value="2" onclick="buscar();">Solo Patria</label>
	</div>
	<div class="form-check ml-3">
		<label class="form-check-label">
		<input type="radio" class="form-check-input" name="op_tipo" value="3" onclick="buscar();" checked><strong>Todas</strong></label>
	</div>
</div>
		<br >		<diw class="row ml-3">
            <strong>Opciones de Busqueda:</strong>
            
			<div class="form-check ml-3">
              <label class="form-check-label">
                    <input type="radio" class="form-check-input" name="optradio" value="7" onclick="ver();buscar();">
                Anulados</label>
            </div>
			
			<div class="form-check ml-3">
              <label class="form-check-label">
                    <input type="radio" class="form-check-input" name="optradio" value="1" onclick="ver();">
                Orden</label>
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
                    <input type="radio" class="form-check-input" name="optradio" value="8" onclick="ver();buscar();" >
                   Por partida
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
 <div class="row mb-1 ml-3">
	<strong>Filtro:</strong>
</div>

	<table border="0">
  <tbody>
    <tr>
      <th scope="row">Cta Pagadora</th>
      <td>
		<select class="custom-select" style="font-size: 14px" name="txt_cta" id="txt_cta" onchange="buscar();">
			<option value="0">--- Todas las Cuentas ---</option>
			<?php
			//--------------------
			$consultx = "SELECT * FROM a_cuentas ORDER BY banco, cuenta;"; 
			$tablx = $_SESSION['conexionsql']->query($consultx);
			while ($registro_x = $tablx->fetch_object())
			//-------------
			{
			echo '<option ';
			echo ' value="';
			echo $registro_x->id;
			echo '">';
			echo mayuscula($registro_x->banco).' '.($registro_x->cuenta).' '.($registro_x->descripcion);
			echo '</option>';
			}
			?>
		</select>
		</td>
    </tr>
    <tr>
      <th scope="row">Año</th>
      <td>
		<select class="custom-select" style="font-size: 14px" name="txt_anno" id="txt_anno" onchange="buscar();">
			<?php
			$i = date ('Y');
			while ($i>=2022)
			//-------------
			{
			echo '<option ';
			echo ' value="';
			echo $i;
			echo '">';
			echo $i;
			echo '</option>';
			$i--;
			}
			?>
		</select>
		</td>
    </tr>
  </tbody>
</table>

	<br>
 <div id="div1"></div>
 </form>
<script language="JavaScript">
$('#cuadro').show();
$('#fechas').hide();
$('#OFECHA').datepicker();
$('#OFECHA2').datepicker();
//---------------------
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
		{	window.open("administracion/formatos/1c_orden_pago.php?id="+id,"_blank");	}
	if (tipo=="PATRIA")
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
//---------------------
function imprimir3(id, tipo)
	{	
	if (tipo=="RET")
		{	window.open("contabilidad/formatos/1_retenciones.php?id="+id,"_blank");	}
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
//---------------------------
function rep(tipo)
 	{
	if (document.form1.optradio.value>0)
		{
			if (tipo==1)
				{	window.open("administracion/reporte/1_rep_orden.php","_blank");	}
			if (tipo==2)
				{	window.open("administracion/reporte/5_rep_orden_ret.php","_blank");	}
			if (tipo==3)
				{	window.open("administracion/reporte/1a_rep_orden.php","_blank");	}
		}
	}
//----------------
function buscar(){
if (((document.form1.optradio.value==1 || document.form1.optradio.value==2 || document.form1.optradio.value==5 || document.form1.optradio.value==8) && document.form1.obuscar.value!='') || (document.form1.optradio.value==3 && document.form1.OFECHA.value!='' && document.form1.OFECHA2.value!='') || (document.form1.optradio.value==4) || (document.form1.optradio.value==6) || (document.form1.optradio.value==7))
	{
	$('#div1').html('<div align="center"><div class="spinner-border" role="status"></div><br><strong>Un momento, por favor...</strong></div>');
	$('#div1').load('administracion/8a_tabla.php?valor='+cambia(document.form1.obuscar.value)+'&tipo='+document.form1.optradio.value+'&fecha1='+document.form1.OFECHA.value+'&fecha2='+document.form1.OFECHA2.value+'&cuenta='+document.form1.txt_cta.value+'&anno='+document.form1.txt_anno.value+'&patria='+document.form1.op_tipo.value);
	}
}
</script>