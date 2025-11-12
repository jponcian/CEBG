<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');

if ($_SESSION['VERIFICADO'] != "SI") { 
header ("Location: ../validacion.php?opcion=val"); 
exit(); }

$acceso=70;
//------- VALIDACION ACCESO USUARIO
include_once "../validacion_usuario.php";
//-----------------------------------
?>
<form id="form1" name="form1" method="post" >
<div align="center" class="TituloP">Libro de Partidas</div>
<br >
<table border="1"><tr align="center">
<td align="left" valign="top"><div style="width:100px">
<a data-toggle="tooltip" title="A&ntilde;o a consultar">
<select class="form-control" name="txt_anno" id="txt_anno" onchange="combo0(this.value);" >
<option value="0">Seleccione el A&ntilde;o a consultar</option>
	<?php
	$anno = date('Y');
	while ($anno >= (2022))
		{
		echo '<option value='.$anno.'>'.$anno.'</option>';
		$anno--;
		}
?>
</select>
</a></div>
</td></tr><tr>
<td align="left" valign="top">
<a data-toggle="tooltip" title="Actividad">
<select class="select2" style="width: 400px" name="txt_categoria" id="txt_categoria" onchange="combo(this.value);">
<option value="0">Espere miestras se carga la Lista...</option>
</select>
</a>
</td></tr><tr>
<td align="left" valign="top">
<a data-toggle="tooltip" title="Partida">
<select class="select2" style="width: 500px" name="txt_partida" id="txt_partida" onchange="">
<option value="0">Espere miestras se cargan las partidas...</option>
</select>
</a>
</td></tr>
<tr>
<td align="left" valign="top"><div class="input-group-prepend">
<input class="form-control" type="text" name="OFECHA" id="OFECHA" size="15" placeholder="Desde" value="<?php //echo '01'.date('/m/Y'); ?>" style="text-align:center" /> 
<input class="form-control" type="text" name="OFECHA2" id="OFECHA2" size="15" placeholder="Hasta" value="<?php //echo date('d/m/Y'); ?>" style="text-align:center" />
</div></td>
</tr><tr>
<td align="left" valign="top"><button type="button" id="botonb" class="btn btn-warning btn-block" onClick="reportes2();"><i class="fas fa-search mr-2"></i> Ver Reporte</button></td>
</tr></table>
</form>
<script language="JavaScript">
// PARA EL SELECT2
$(document).ready(function() {
    $('.select2').select2();
	$('#OFECHA').datepicker();
	$('#OFECHA2').datepicker();
});
//-------------
function combo0(fecha)
{
	$.ajax({
        type: "POST",
        url: 'presupuesto/2c_combo.php?fecha='+fecha,
        success: function(resp){
            $('#txt_categoria').html(resp);
			combo(0);
            document.form1.OFECHA.value= '01/01/'+fecha;
            document.form1.OFECHA2.value= '31/12/'+fecha;
        }
    });
}
//-------------
function combo(categoria)
{
	$.ajax({
        type: "POST",
        url: 'presupuesto/2b_combo.php?categoria='+categoria+'&partida=0&fecha='+document.form1.txt_anno.value,
        success: function(resp){
            $('#txt_partida').html(resp);
        }
    });
}
//---------------------------
function reportes2()
 	{
	 if (document.form1.txt_partida.value!='0') //&& document.form1.OFECHA.value!='' && document.form1.OFECHA2.value!=''
	 	{
		window.open("presupuesto/reporte/1_libro.php?partida="+document.form1.txt_partida.value+"&categoria="+document.form1.txt_categoria.value+"&fecha1="+document.form1.OFECHA.value+"&fecha2="+document.form1.OFECHA2.value,"_blank");
		}
	else
		{alertify.alert('Debe seleccionar todas las opciones!');}	
	}
</script>