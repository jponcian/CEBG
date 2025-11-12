<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');

if ($_SESSION['VERIFICADO'] != "SI") { 
header ("Location: ../validacion.php?opcion=val"); 
exit(); }

$acceso=92;
//------- VALIDACION ACCESO USUARIO
include_once "../validacion_usuario.php";
//-----------------------------------
?>
<form id="form1" name="form1" method="post" >
<div align="center" class="TituloP">Movimientos de Bienes</div>
<br >
	<div class="form-group">
          <div class="input-group col-sm-12">
            <div class="input-group-prepend">
              <div class="input-group-text"><i class="fa-solid fa-building-circle-arrow-right"></i></div>
            </div>
				<select class="select2" name="txt_division" id="txt_division"  >
<option value="0">Todos</option>
  <?php
	$consultx = "SELECT bn_dependencias.* FROM bn_dependencias, bn_bienes WHERE bn_bienes.id_dependencia=bn_dependencias.id GROUP BY bn_dependencias.id ORDER BY division;"; 
	$tablx = $_SESSION['conexionsql']->query($consultx);
	while ($registro_x = $tablx->fetch_array())
		{
		echo '<option value='.encriptar($registro_x['id']).'>'.$registro_x['division'].'</option>';
		}
?></select>          
			</div>
        </div>

	<div class="form-group">
          <div class="input-group col-sm-12">
            <div class="input-group-prepend">
              <div class="input-group-text"><i class="fa-brands fa-bimobject"></i></div>
            </div>
				<select  name="txt_bien" id="txt_bien" class="select2" style="width: 600px">
			<option value="0" >Todos</option>
<?php
//--------------------
$consult = "SELECT * FROM bn_bienes;"; 
$tablx = $_SESSION['conexionsql']->query($consult);
while ($registro_x = $tablx->fetch_object())
//-------------
	{
	echo '<option value="';
	echo encriptar($registro_x->id_bien);
	echo '" ';
//	if ($partida==$registro_x->id) {echo 'selected="selected"';}
	echo ' >';
	echo $registro_x->numero_bien .' - '. $registro_x->descripcion_bien;
	echo '</option>';
	}
?>
			
			</select>      
			</div>
        </div>
	
	<div class="form-group">
          <div class="input-group col-sm-12">
            <div class="input-group-prepend">
              <div class="input-group-text"><i class="fa-regular fa-calendar-check"></i></div>
            </div>
				<a data-toggle="tooltip" title="Periodo de los Movimientos"><!--onchange="area(this.value)"-->
<div id="fecha">
<input class="form-control" type="text" name="OFECHA" id="OFECHA" size="15" placeholder="Desde" value="<?php echo date('d/m/Y'); ?>" style="text-align:center" />
<input class="form-control" type="text" name="OFECHA2" id="OFECHA2" size="15" placeholder="Hasta" value="<?php echo date('d/m/Y'); ?>" style="text-align:center" /></div>
</a>	          
			</div>
        </div>
	
<div class="form-group col-sm-5">
	<div class="input-group">
		<button type="button" id="botonb" class="btn btn-warning" onClick="reportes2();"><i class="fas fa-search mr-2"></i> Ver Reporte</button>
	</div>
</div>	
	
</form>
<script language="JavaScript">
// PARA EL SELECT2
$(document).ready(function() {
    $('.select2').select2();
});
//--------------------------------
$('#OFECHA').dateRangePicker({
//	startDate: moment().format("DD-MM-YYYY"),
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
});//---------------------------
function reportes2()
 	{
//	 if (document.form1.txt_division.value!=0 )//&& document.form1.txt_area.value>=0+"&area="+document.form1.txt_area.value
//	 	{
		window.open("bienes/reporte/movimientos.php?fecha1="+document.form1.OFECHA.value+"&fecha2="+document.form1.OFECHA2.value+"&division="+document.form1.txt_division.value+"&bien="+document.form1.txt_bien.value,"_blank");
//		}
//	else
//		{alertify.alert('Debe seleccionar todas las opciones!');}	
	}
</script>