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
$id = $_GET['id']; 
$consultx = "SELECT ci_jefe FROM a_areas WHERE id = $id;";  //echo $consultx;
$tablx = $_SESSION['conexionsql']->query($consultx);
$registro = $tablx->fetch_object();
$cedula = $registro->ci_jefe;
?>
<form id="form999" name="form999" method="post" >
			<!-- Modal Header -->
<div class="modal-header bg-fondo text-center">
	<h4 align="center" style="background-color:#0275d8; color:#FFFFFF" class="modal-title w-100 font-weight-bold py-2">Asignar Jefe del Area
	  <button type="button" class="close" data-dismiss="modal">&times;</button></h4>
    <input type="hidden" id="oid" name="oid" value="0"/>
</div>
<!-- Modal body -->
		<div class="p-1">
			
<div class="row">
	<div class="form-group col-sm-12 ml-2">
		<div class="input-group">
		<select class="select2" style="width: 450px" style="font-size: 14px" name="txt_cedula" id="txt_cedula" onchange="">
		<option value="0"> -SELECCIONE- </option>
		<?php
		//--------------------
		$consultx = "SELECT cedula, CONCAT(rac.nombre,' ',rac.nombre2,' ',rac.apellido,' ',rac.apellido2) as nombre FROM rac ORDER BY (cedula * 1);;"; 
		$tablx = $_SESSION['conexionsql']->query($consultx);
		while ($registro_x = $tablx->fetch_object())
		//-------------
		{
		echo '<option value="';
		echo $registro_x->cedula;
		echo '" ';
		if ($cedula==$registro_x->cedula) {echo 'selected="selected"';}
		echo ' >';
		echo rellena_cero($registro_x->cedula,8) . " - " . $registro_x->nombre;
		echo '</option>';
		}
		?>
		</select>
		</div>
	</div>
</div>
			
		</div>
<!-- Modal footer -->
<div class="modal-footer justify-content-center">
	<button type="button" id="boton" class="btn btn-outline-success waves-effect" onclick="guardar2('<?php echo $id; ?>','<?php echo $nomina; ?>')" ><i class="fas fa-save prefix grey-text mr-1"></i> Guardar</button>
</div>
</div>
</div>
</form>
<script language="JavaScript">
//----------------
$(document).ready(function() {
    $('.select2').select2();
});
//----------------
function guardar2(id, nomina)
 {
	var parametros = $("#form999").serialize(); 
	$.ajax({  
		type : 'POST',
		url  : 'personal/27g_guardar.php?id='+id,
		dataType:"json",
		data:  parametros, 
		success:function(data) {  	
			if (data.tipo=="info")
				{	alertify.success(data.msg);	$('#modal_normal .close').click();
				}
			else
				{	alertify.success(data.msg);	
				}
			}  
		});
 }
</script>