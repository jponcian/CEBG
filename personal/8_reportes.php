<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');

if ($_SESSION['VERIFICADO'] != "SI") { 
header ("Location: ../validacion.php?opcion=val"); 
exit(); }

$acceso=84;
//------- VALIDACION ACCESO USUARIO
include_once "../validacion_usuario.php";
//-----------------------------------
?>
<form id="form1" name="form1" method="post" >
<div align="center" class="TituloP">Formato de Asistencia</div>
<br >
<table border="1"><tr align="center">

	<br>
	<div class="row">
			<div class="form-group col-sm-6 ml-5">
				<div class="input-group-text">Direccion: <select class="custom-select" style="font-size: 14px" name="txt_direccion" id="txt_direccion" >
					<option value=<?php echo encriptar('0'); ?>>TODAS LAS DIRECCIONES</option>
<?php
//--------------------
$consult = "SELECT * FROM a_direcciones WHERE id<50 ORDER BY direccion;"; // WHERE id_direccion='$desde'
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
				</div>
			</div>
	</div>	
	<div class="row" id="div_empleado">
		<div class="form-group col-sm-6 ml-5">
			<div class="input-group-text">Funcionario: <select class="custom-select" style="font-size: 14px" name="txt_empleado" id="txt_empleado">
			</select>
			</div>
		</div>
	</div>
<!--	<br>-->
	<div id="fechas"><table><tr><td align="left" valign="top">
<input readonly class="form-control ml-5" type="text" name="OFECHA" id="OFECHA" placeholder="Desde" value="<?php echo date('d-m-Y'); ?>" style="text-align:center" /></td><td>
<input readonly class="form-control" type="text" name="OFECHA2" id="OFECHA2" placeholder="Hasta" value="<?php echo date('d-m-Y'); ?>" style="text-align:center" /></td><td>
<button type="button" id="botonb" class="btn btn-primary" onClick="reportes2();"><i class="fas fa-search mr-2"></i>Ver Reporte</button></td></tr></table></div>
</tr></table>
</form>
<script language="JavaScript">
//------------------------
$('#OFECHA').dateRangePicker({
	autoClose: true,
	batchMode: 'week',
	showShortcuts: false,
	autoClose: true,
	format: 'DD-MM-YYYY',
	language:	'es',
	extraClass: 'date-range-picker19',
	separator : ' al ',
	getValue: function()
		{
		if ($('#OFECHA').val() && $('#OFECHA2').val() )
			return $('#OFECHA').val() + ' al ' + $('#OFECHA2').val();
		else
			return '';
		},
	setValue: function(s,s1,s2)
		{
		$('#OFECHA').val(s1);
		$('#OFECHA2').val(s2);
		}
});
$('#div_empleado').hide();
//---------------------------
function ver_empleados(valor)
{
	$('#div_empleado').hide();
	$.ajax({
        type: "POST",
        url: 'seguridad/8a_combo.php?origen='+valor,
        success: function(resp){
            $('#txt_empleado').html(resp);
			$('#div_empleado').show();
			//listar_bienes();
        }
    });
}
//---------------------------
function reportes2()
 	{
	window.open("personal/reporte/control_asistencia.php?desde="+document.form1.OFECHA.value+"&hasta=" +document.form1.OFECHA2.value+"&direccion=" +document.form1.txt_direccion.value,"_blank");

	}
</script>