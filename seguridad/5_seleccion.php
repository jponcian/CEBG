<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');

if ($_SESSION['VERIFICADO'] != "SI") { 
header ("Location: ../validacion.php?opcion=val"); 
exit(); }

?>
<form id="form1bbb" name="form1bbb" method="post" onsubmit="return evitar();" >
 <div align="center" class="TituloP">Seleccionar Tipo Ingreso</div>
	
		<br >		
<diw class="row ml-5">
			
	<div class="btn-group" role="group" aria-label="Proceso">
  <button type="button" value="1" onclick="ver('1');" class="btn btn-primary">FUNCIONARIOS</button>
  <button type="button" value="2" onclick="ver('2');" class="btn btn-primary">VISITAS</button>
  <button type="button" value="3" onclick="ver('3');" class="btn btn-secondary">BIENES</button>
</div>
	
  </diw>
<br>
<div id="divbbb"></div>

</form>
<script language="JavaScript">
//---------------------
function ver(valor)
 	{ //document.form1bbb.optradio.value
	if (valor==1)
	 	{
		$('#divbbb').html('<div align="center"><div class="spinner-border" role="status"></div><br><strong>Un momento, por favor...</strong></div>');
		$('#divbbb').load('seguridad/5_acceso.php');
		}
	if (valor==2)
	 	{
		$('#divbbb').html('<div align="center"><div class="spinner-border" role="status"></div><br><strong>Un momento, por favor...</strong></div>');
		$('#divbbb').load('seguridad/6_acceso.php');
		}
	if (valor==3)
	 	{
		$('#divbbb').html('<div align="center"><div class="spinner-border" role="status"></div><br><strong>Un momento, por favor...</strong></div>');
		$('#divbbb').load('seguridad/7_bienes.php');
		}
	}
//---------------------
</script>