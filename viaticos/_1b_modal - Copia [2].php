<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');

if ($_SESSION['VERIFICADO'] != "SI") { 
header ("Location: ../validacion.php?opcion=val"); 
exit(); }

$acceso=80;
//------- VALIDACION ACCESO USUARIO
include_once "../validacion_usuario.php";
//-----------------------------------
if ($_GET['id']<>0)
	{
	$consultx = "SELECT * FROM viaticos_solicitudes WHERE id = ".$_GET['id'].";";  //echo $consultx;
	$tablx = $_SESSION['conexionsql']->query($consultx);
	$registro = $tablx->fetch_object();
	//$eventual = $registro->temporal;
	}

?>
<form id="form999" name="form999" method="post" >
			<!-- Modal Header -->
<div class="modal-header bg-fondo text-center">
    <input type="hidden" id="oid" name="oid" value="<?php echo $_GET['id']; ?>"/>
<h4 align="center" style="background-color:#0275d8; color:#FFFFFF" class="modal-title w-100 font-weight-bold py-2">Solicitud de Viaticos
<button type="button" class="close" data-dismiss="modal" onclick="buscar();">&times;</button></h4>
</div>
<!-- Modal body -->
	
	<div class="row">
		<div class="form-group col-sm-12">
			<div class="input-group-text">Dirección Solicitante: 
			  <select class="custom-select" style="font-size: 14px" name="txt_area" id="txt_area">
					
					<?php
					//--------------------
					$consultx = "SELECT * FROM a_direcciones ORDER BY division;"; 
					$tablx = $_SESSION['conexionsql']->query($consultx);
					while ($registro_x = $tablx->fetch_object())
					//-------------
					{
					echo '<option ';
						if ($registro->oficina==$registro_x->id)	{echo ' selected="selected" ';}
					echo ' value="';
					echo $registro_x->id;
					echo '">';
					echo ($registro_x->division);
					echo '</option>';
					}
					?>
					</select>
				</div>
		</div>	
	</div>	
	
	<div class="row">
		<div class="form-group col-sm-8">
			<div class="input-group-text">Zona: 
			  <select class="custom-select" style="font-size: 14px" name="txt_zona" id="txt_zona">
					
					<?php
					//--------------------
					$consultx = "SELECT * FROM a_zonas_viaticos ORDER BY id;"; 
					$tablx = $_SESSION['conexionsql']->query($consultx);
					while ($registro_x = $tablx->fetch_object())
					//-------------
					{
					echo '<option ';
						if ($registro->zona==$registro_x->id)	{echo ' selected="selected" ';}
					echo ' value="';
					echo $registro_x->id;
					echo '">';
					echo ($registro_x->zona)." (".($registro_x->ciudades).")";
					echo '</option>';
					}
					?>
					</select>
				</div>
		</div>	
		<div class="form-group col-sm-4">
			<div class="input-group-text">Require Hospedaje: 
			  <select class="custom-select" style="font-size: 14px" name="txt_hotel" id="txt_hotel">
				<option <?php if ($_GET['id']<>0) { if ($registro->hotel==0){echo ' selected="selected" ';} } ?> value="0">No</option>
				<option <?php if ($_GET['id']<>0) { if ($registro->hotel==1){echo ' selected="selected" ';} } ?>value="1">Si</option>
				</select>
				</div>
		</div>	
	</div>
		
	<div class="row">
		
		<div class="form-group col-sm-4">
			<div class="input-group">
				<div class="input-group-text">Desde</div>
				<input onkeyup="saltar(event,'txt_fecha')" type="text" style="text-align:center" class="form-control " name="txt_desde" id="txt_desde" placeholder="Desde"  minlength="1" maxlength="10" value="<?php if ($_GET['id']<>0) { echo voltea_fecha($registro->desde); } else {echo date('d/m/Y');} ?>" required>
				<div class="input-group-text"><i class="far fa-calendar-alt"></i></div></div>
		</div>	
		
		<div class="form-group col-sm-4">
			<div class="input-group">
				<div class="input-group-text">Hasta</div>
				<input onkeyup="saltar(event,'txt_concepto')" type="text" style="text-align:center" class="form-control " name="txt_hasta" id="txt_hasta" placeholder="Hasta"  minlength="1" maxlength="10" value="<?php if ($_GET['id']<>0) { echo voltea_fecha($registro->hasta); } else {echo date('d/m/Y');} ?>" required><div class="input-group-text"><i class="far fa-calendar-alt"></i></div></div>
		</div>	

		<div class="form-group col-sm-4">
			<div class="input-group-text">Vehiculo Propio: 
			  <select class="custom-select" style="font-size: 14px" name="txt_vehiculo" id="txt_vehiculo">
				<option <?php if ($_GET['id']<>0) { if ($registro->vehiculo==0){echo ' selected="selected" ';} } ?> value="0">No</option>
				<option <?php if ($_GET['id']<>0) { if ($registro->vehiculo==1){echo ' selected="selected" ';} } ?>value="1">Si</option>
				</select>
				</div>
		</div>	

	</div>
			
	<div class="row">
		<div class="form-group col-sm-12">
<textarea id="txt_concepto" name="txt_concepto" placeholder="Motivo de la Comisión" class="form-control" rows="4"><?php if ($_GET['id']<>0) { echo $registro->concepto;} ?></textarea></div>
	</div>

			<br>
<div align="center">			
	<button type="button" id="boton" class="btn btn-outline-success waves-effect" onclick="guardar_detalle(0)" ><i class="fas fa-save prefix grey-text mr-1"></i> Guardar</button>			
</div>
<br>
		</div>

</form>
<script language="JavaScript">
//--------------------------------
//setTimeout(function()	{
//		$('#txt_rif').focus();
//		},1000)	
//--------------------------------
$("#txt_desde").datepicker();
$("#txt_hasta").datepicker();
//--------------------------------
</script>