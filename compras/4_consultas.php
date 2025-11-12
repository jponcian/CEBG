<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');

if ($_SESSION['VERIFICADO'] != "SI") { 
header ("Location: ../validacion.php?opcion=val"); 
exit(); }

$acceso=44;
//------- VALIDACION ACCESO USUARIO
include_once "../validacion_usuario.php";
//-----------------------------------
?>
<form id="form1" name="form1" method="post" onsubmit="return evitar();" >
 <div align="center" class="TituloP">Consultar Orden de Pago y/o Servicio</div>
		<br >
		<diw class="row ml-3">
            <strong>Opciones de Busqueda:</strong>
            <div class="form-check ml-3">
              <label class="form-check-label">
                    <input type="radio" class="form-check-input" name="optradio" value="6" onclick="ver();buscar();">
                Anuladas</label>
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

<div id="cuadro"><input name="obuscar" id="obuscar" type="text" size="100" class="form-control" onchange="buscar()" /></div>
<div id="fechas"><table><tr><td align="left" valign="top">
<input class="form-control" type="text" name="OFECHA" id="OFECHA" size="15" placeholder="Desde" value="<?php echo date('d/m/Y'); ?>" style="text-align:center" /></td><td>
<input class="form-control" type="text" name="OFECHA2" id="OFECHA2" size="15" placeholder="Hasta" value="<?php echo date('d/m/Y'); ?>" style="text-align:center" /></td><td>
<button type="button" id="botonb" class="btn btn-primary" onClick="buscar();"><i class="fas fa-search mr-2"></i>Buscar</button></td></tr></table></div>

  <br>
 <div class="row mb-1 ml-3">
	<strong>Filtro:</strong>
</div>

	<table border="0">
  <tbody>
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
function imprimir(id, tipo)
	{	
	window.open("compras/formatos/10_orden.php?p=1&id="+id,"_blank");
	window.open("compras/formatos/8_recepcion.php?p=1&id="+id,"_blank");
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
	if (document.form1.optradio.value==4 || document.form1.optradio.value==6)
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
		if (((document.form1.optradio.value==1 || document.form1.optradio.value==2 || document.form1.optradio.value==5) && document.form1.obuscar.value!='') || (document.form1.optradio.value==3 && document.form1.OFECHA.value!='' && document.form1.OFECHA2.value!='') || (document.form1.optradio.value==4))
			{
			if (tipo==1) {window.open("compras/reporte/1_rep_orden.php","_blank");}
			if (tipo==2) {window.open("compras/reporte/2_rep_orden.php","_blank");}
			}
		//----------------
		}
	}
//----------------
function buscar(){
if (((document.form1.optradio.value==1 || document.form1.optradio.value==2 || document.form1.optradio.value==5) && document.form1.obuscar.value!='') || (document.form1.optradio.value==3 && document.form1.OFECHA.value!='' && document.form1.OFECHA2.value!='') || (document.form1.optradio.value==4 || document.form1.optradio.value==6))
	{
	$('#div1').html('<div align="center"><div class="spinner-border" role="status"></div><br><strong>Un momento, por favor...</strong></div>');
	$('#div1').load('compras/4a_tabla.php?valor='+cambia(document.form1.obuscar.value)+'&anno='+document.form1.txt_anno.value+'&tipo='+document.form1.optradio.value+'&fecha1='+document.form1.OFECHA.value+'&fecha2='+document.form1.OFECHA2.value);
	}
}
</script>