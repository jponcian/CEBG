<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');

if ($_SESSION['VERIFICADO'] != "SI") { 
header ("Location: ../validacion.php?opcion=val"); 
exit(); }

$acceso=59;
//------- VALIDACION ACCESO USUARIO
include_once "../validacion_usuario.php";
//-----------------------------------
?>
<form id="form1" name="form1" method="post" onsubmit="return evitar();" >
 <div align="center" class="TituloP">Inventario</div>
		<br >
		<diw class="row ml-3">
            <strong>Opciones de Busqueda:</strong>
            
			<div class="form-check ml-3">
              <label class="form-check-label">
                    <input type="radio" class="form-check-input" name="optradio" value="1" onclick="ver();">
                Sin Stock</label>
            </div>
			
			<div class="form-check ml-3">
              <label class="form-check-label">
                    <input type="radio" class="form-check-input" name="optradio" value="2" onclick="ver();">
                Mas Salidas</label>
            </div>
           
			<div class="form-check ml-3">
                <label class="form-check-label">
                    <input checked type="radio" class="form-check-input" name="optradio" value="4" onclick="ver();" >
                   Ver Todas</label>
            </div>
			
        </diw>

	<br>

<div id="cuadro"><input name="obuscar" id="obuscar" type="text" size="100" class="form-control" onchange="buscar()" /></div>

<div id="fechas">
		
<div class="row mb-1 ml-3">
	<strong>Filtro:</strong>
</div>
	
<br>
	<div class="row mb-1 ml-5">

<table>
<tr>
	<td align="left" valign="middle"><strong>Fecha</strong></td>
	<td align="left" valign="top"><input class="form-control" type="text" name="OFECHA" id="OFECHA" size="15" placeholder="Desde" value="<?php echo date('d/m/Y'); ?>" style="text-align:center" /></td>
	<td><input class="form-control" type="text" name="OFECHA2" id="OFECHA2" size="15" placeholder="Hasta" value="<?php echo date('d/m/Y'); ?>" style="text-align:center" /></td>
</tr>
<tr>
	<td align="left" valign="middle"><strong>Direccion</strong></td>
	<td colspan="2" align="left" valign="top">
	<select class="custom-select" style="font-size: 14px" name="txt_direccion" id="txt_direccion">
			<option value=<?php echo encriptar('0'); ?>>TODAS LAS DIRECCIONES</option>
	<?php
	//--------------------
	$consult = "SELECT * FROM a_direcciones WHERE id<50 ORDER BY direccion;"; // WHERE id_direccion='$desde'
	//$consult = "SELECT * FROM a_direcciones $condicion ORDER BY direccion;"; // WHERE id_direccion='$desde'
	$tablx = $_SESSION['conexionsql']->query($consult);
	while ($registro_x = $tablx->fetch_object())
	//-------------
	{
	echo '<option value="';
	echo encriptar($registro_x->id);
	echo '" >';
	echo $registro_x->direccion;
	echo '</option>';
	}
	?>
		</select>
	</td>
</tr>
	
</table>
	</div>

<br>

</div>
<div class="row mb-1 ml-3">
<br>
	<button type="button" id="botonb" class="btn btn-primary" onclick="reportes2();"><i class="fas fa-search mr-2"></i>Ver Reporte</button>
</div>
	<div id="div1"></div>
<br>

 </form>
<script language="JavaScript">
$('#cuadro').hide();
$('#fechas').hide();
$('#OFECHA').datepicker();
$('#OFECHA2').datepicker();
//---------------------------
function ver()
 	{
	if (document.form1.optradio.value==1 || document.form1.optradio.value==4)
	 	{
//		$('#cuadro').show();
		$('#fechas').hide();
		}
	if (document.form1.optradio.value==2)
	 	{
//		$('#cuadro').hide();
		$('#fechas').show();
		}
	}
//---------------------------
function reportes2()
 	{
	window.open("almacen/reporte/1_inventario.php?desde="+document.form1.OFECHA.value+"&hasta=" +document.form1.OFECHA2.value+"&tipo=" +document.form1.optradio.value+"&direccion=" +document.form1.txt_direccion.value,"_blank");
	}
</script>