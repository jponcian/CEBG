<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');

if ($_SESSION['VERIFICADO'] != "SI") { 
header ("Location: ../validacion.php?opcion=val"); 
exit(); }

$acceso='102';
//------- VALIDACION ACCESO USUARIO
include_once "../validacion_usuario.php";
//-----------------------------------
?>
<form id="form1" name="form1" method="post" >
<div align="center" class="TituloP">Relacion de Permisos, Reposos o Vacaciones</div>
<br >
<table border="1"><tr align="center">

	<diw class="row ml-3">
            <strong>Opciones de Filtrado:</strong>
            
			<div class="form-check ml-3">
                <label class="form-check-label">
                    <input type="radio" class="form-check-input" name="optradio" value="<?php echo encriptar('1'); ?>"  >
                   Permisos
                </label>
            </div>			
            		
			<div class="form-check ml-3">
                <label class="form-check-label">
                    <input type="radio" class="form-check-input" name="optradio" value="<?php echo encriptar('2'); ?>" >
                   Reposos
                </label>
            </div>			
            		
			<div class="form-check ml-3">
                <label class="form-check-label">
                    <input type="radio" class="form-check-input" name="optradio" value="<?php echo encriptar('3'); ?>" checked >
                   Vacaciones
                </label>
            </div>			
        </diw>
	<br>
	<div class="row">
			<div class="form-group col-sm-10 ml-5">
				<div class="input-group-text">Direccion: <select class="select2" style="width: 600px; font-size: 14px" name="txt_direccion" id="txt_direccion" onChange="ver_empleados(this.value);">
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
		<div class="form-group col-sm-9 ml-5">
			<div class="input-group-text">Funcionario: <select class="select2" style="width: 600px; font-size: 14px" name="txt_empleado" id="txt_empleado">
			</select>
			</div>
		</div>
	</div>
<!--	<br>-->
	<div id="fechas"><table><tr><td align="left" valign="top">
<input class="form-control ml-5" type="text" name="OFECHA" id="OFECHA" size="12" placeholder="Desde" value="<?php echo date('d/m/Y'); ?>" style="text-align:center" /></td><td>
<input class="form-control" type="text" name="OFECHA2" id="OFECHA2" size="12" placeholder="Hasta" value="<?php echo date('d/m/Y'); ?>" style="text-align:center" /></td><td>
<button type="button" id="botonb" class="btn btn-primary" onClick="reportes2();"><i class="fas fa-search mr-2"></i>Ver Reporte</button></td></tr></table></div>
</tr></table>
</form>
<script language="JavaScript">
$(document).ready(function() {
    $('.select2').select2();
	//----------------
	$("#OFECHA").datepicker();
	$("#OFECHA2").datepicker();
	$('#div_empleado').hide();
});
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
	window.open("personal/reporte/5_permisos.php?desde="+document.form1.OFECHA.value+"&hasta=" +document.form1.OFECHA2.value+"&tipo=" +document.form1.optradio.value+"&direccion=" +document.form1.txt_direccion.value+"&cedula=" +document.form1.txt_empleado.value,"_blank");
//	window.open("almacen/reporte/2_movimiento2.php?desde="+document.form1.OFECHA.value+"&hasta=" +document.form1.OFECHA2.value+"&tipo=" +document.form1.optradio.value+"&direccion=" +document.form1.txt_direccion.value,"_blank");
	}
</script>