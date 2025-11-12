<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');

if ($_SESSION['VERIFICADO'] != "SI") { 
header ("Location: ../validacion.php?opcion=val"); 
exit(); }

$acceso=53;
//------- VALIDACION ACCESO USUARIO
include_once "../validacion_usuario.php";
//-----------------------------------
$condicion = "WHERE id=".$_SESSION["direccion"]; 
?>
<form id="form1" name="form1" method="post" >
<div align="center" class="TituloP">Etiquetas QR</div>
<br >
<table border="1"><tr align="center">
<td align="left" valign="top">  
<select class="select2" name="txt_division" id="txt_division"  >
<option value="0">Seleccione</option>
  <?php
	$consultx = "SELECT bn_dependencias.* FROM bn_dependencias, bn_bienes WHERE bn_bienes.id_dependencia=bn_dependencias.id GROUP BY bn_dependencias.id ORDER BY division;"; 
	$tablx = $_SESSION['conexionsql']->query($consultx);
	while ($registro_x = $tablx->fetch_array())
		{
		echo '<option value='.encriptar($registro_x['id']).'>'.$registro_x['division'].'</option>';
		}
?></select>
</td>
<!--<td align="left" valign="top"><div style="width:300px" class="form-group">	  
<div class="col-xm-6">
<a data-toggle="tooltip" title="">
<select class="form-control" name="txt_area" id="txt_area" onchange="" >
<option value="0" >Todas las Areas</option>
</select>
</a>
</div></div></td>-->
<td align="left" valign="top"><button type="button" id="botonb" class="btn btn-warning" onClick="reportes2();"><i class="fas fa-search mr-2"></i> Ver Reporte</button></td>
</tr></table>
	<br/>
</form>
<script language="JavaScript">
// PARA EL SELECT2
$(document).ready(function() {
    $('.select2').select2();
});
//--------------------------------
function area(division)
{
	$.ajax({
        type: "POST",
        url: 'bienes/1a_combo.php?division='+division,
        success: function(resp){
            $('#txt_area').html(resp);
        }
    });
}
//---------------------------
function reportes2()
 	{
	 if (document.form1.txt_division.value!=0 )//&& document.form1.txt_area.value>=0+"&area="+document.form1.txt_area.value
	 	{
		window.open("bienes/formatos/etiquetas.php?division="+document.form1.txt_division.value,"_blank");
		}
//	else
//		{alertify.alert('Debe seleccionar todas las opciones!');}	
	}
</script>