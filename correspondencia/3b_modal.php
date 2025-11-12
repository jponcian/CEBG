<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');

if ($_SESSION['VERIFICADO'] != "SI") { 
header ("Location: ../validacion.php?opcion=val"); 
exit(); }

$acceso=6;
//------- VALIDACION ACCESO USUARIO
include_once "../validacion_usuario.php";
//-----------------------------------
$consultx = "SELECT * FROM cr_memos_div WHERE id = 0".decriptar($_GET['id']).";";  //echo $consultx;
$tablx = $_SESSION['conexionsql']->query($consultx);
$registro = $tablx->fetch_object();
?>
<form id="form999" name="form999" method="post" >
<input type="hidden" id="oid" name="oid" value="<?php echo $_GET['id']; ?>"/>
			<!-- Modal Header -->
<div class="modal-header bg-fondo text-center">
<h4 align="center" style="background-color:#0275d8; color:#FFFFFF" class="modal-title w-100 font-weight-bold py-2">Nuevo
<button type="button" class="close" data-dismiss="modal" onclick="buscar2();">&times;</button></h4>
</div>
<!-- Modal body -->
		<div class="p-1">
			<div class="row">
				
				<div class="form-group col-sm-9">
					<div class="input-group-text">Direccion Origen: <select class="custom-select" style="font-size: 14px" name="txt_origen" id="txt_origen" onchange="combo(this.value);">
					<option value="0">Seleccione</option>
<?php
//--------------------
$consult = "SELECT * FROM a_direcciones WHERE id='".$_SESSION["direccion"]."' ORDER BY direccion;"; // WHERE id_direccion='$desde'
$tablx = $_SESSION['conexionsql']->query($consult);
while ($registro_x = $tablx->fetch_object())
//-------------
	{
	echo '<option value="';
	echo $registro_x->id;
	echo '" ';
	if ($registro->direccion_origen==$registro_x->id) {echo 'selected="selected"'; }
	echo ' >';
	echo $registro_x->direccion;
	echo '</option>';
	}
?>
					</select>
					</div>
				</div>

	<div class="form-group col-sm-3">
			<div class="input-group">
				<div class="input-group-text"><i class="far fa-calendar-alt"></i></div>
				<input onkeyup="saltar(event,'txt_origen')" type="text" style="text-align:center" class="form-control " name="txt_fecha" id="txt_fecha" placeholder="Fecha"  minlength="1" maxlength="10" value="<?php if (decriptar($_GET['id'])<>'0') { echo voltea_fecha($registro->fecha); } else { echo date('d/m/Y'); } ?>" required>
	</div>
		</div>	
				
		</div>
		<div class="row">
				
				<div class="form-group col-sm-12">
					<div class="input-group-text">Direccion Destino: <select class="custom-select" style="font-size: 14px" name="txt_destino" id="txt_destino" onchange="">
					<option value="0">Seleccione</option>
<?php
//--------------------
$consult = "SELECT * FROM a_direcciones WHERE id<>'".$registro->direccion_origen."' and (id<50 or id>=90);"; // WHERE id_direccion='$desde'
$tablx = $_SESSION['conexionsql']->query($consult);
while ($registro_x = $tablx->fetch_object())
//-------------
	{
	echo '<option value="';
	echo $registro_x->id;
	echo '" ';
	if ($registro->direccion_destino==$registro_x->id) {echo 'selected="selected"'; }
	echo ' >';
	echo $registro_x->direccion;
	echo '</option>';
	}
?>
					</select>
					</div>
				</div>

				
		</div>

<div class="row">
	<div class="form-group col-sm-12">
		<input type="text" id="txt_asunto" value="<?php if (decriptar($_GET['id'])<>'0') { echo ($registro->asunto); } ?>" name="txt_asunto" placeholder="Asunto" class="form-control">
	</div>
	</div>
</div>

<div class="row">
	<div class="form-group col-sm-12">
<textarea id="txt_concepto" name="txt_concepto" placeholder="Escribe aqui el cuerpo del Memorando" class="form-control" rows="8" ><?php if (decriptar($_GET['id'])<>'0') { echo ($registro->cuerpo); } ?></textarea></div>
	</div>
</div>
	
<div align="center">			
	<button type="button" id="boton" class="btn btn-outline-success waves-effect" onclick="guardar(0)" ><i class="fas fa-save prefix grey-text mr-1"></i> Guardar</button>			
</div>

</form>
<script language="JavaScript">
$("#txt_fecha").datepicker();
//--------------------------------
setTimeout(function()	{
	$('#txt_concepto').focus();
	},1000)	
//--------------------------------
</script>