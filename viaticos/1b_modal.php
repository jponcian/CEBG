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
			<div class="input-group">
				<div class="input-group-text" align="center">Solicitante</div>

				<select class="select2" style="width: 600px" name="txt_cedula" id="txt_cedula">
					
					<?php
					//--------------------
					$consultx = "SELECT *, CONCAT(rac.nombre,' ',rac.nombre2,' ',rac.apellido,' ',rac.apellido2) as  nombre FROM rac WHERE id_area>0 ORDER BY cedula,nombre;"; 
					$tablx = $_SESSION['conexionsql']->query($consultx);
					while ($registro_x = $tablx->fetch_object())
					//-------------
					{
					echo '<option ';
						if ($registro->cedula==$registro_x->cedula)	{echo ' selected="selected" ';}
					echo ' value="';
					echo $registro_x->cedula;
					echo '">';
					echo ($registro_x->cedula).' - '.($registro_x->nombre);
					echo '</option>';
					}
					?>
					</select>
			</div>
		</div>
		</div>			
	
	<div class="row">
		<div class="form-group col-sm-12">
			<div class="input-group">
				<div class="input-group-text" align="center">Zona</div>

				<select class="select2" style="width: 600px" name="txt_zona" id="txt_zona">
					
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
		</div>	
		
	<div class="row">
		
		<div class="form-group col-sm-7">
			<div class="input-group">
				<div class="input-group-text">Destino</div>
				<input onkeyup="saltar(event,'txt_desde')" type="text" style="text-align:letf" class="form-control " name="txt_ciudad" id="txt_ciudad" placeholder="Ciudad"  minlength="1" maxlength="250" value="<?php if ($_GET['id']<>0) { echo ($registro->ciudad); } else {echo '';} ?>" required>
				</div>
		</div>	
		
		

	</div>
	
	<div class="row">
		
		<div class="form-group col-sm-4">
			<div class="input-group">
				<div class="input-group-text"><i class="far fa-calendar-alt"></i></div>
				<input onkeyup="saltar(event,'txt_hasta')" type="text" style="text-align:center" class="form-control " name="txt_desde" id="txt_desde" placeholder="Desde"  minlength="1" maxlength="10" value="<?php if ($_GET['id']<>0) { echo voltea_fecha($registro->desde).' '.$registro->horaa; } else {echo '';} ?>" required>
				</div>
		</div>	
		
		<div class="form-group col-sm-4">
			<div class="input-group">
				<div class="input-group-text"><i class="far fa-calendar-alt"></i></div>
				<input onkeyup="saltar(event,'txt_concepto')" type="text" style="text-align:center" class="form-control " name="txt_hasta" id="txt_hasta" placeholder="Hasta"  minlength="1" maxlength="10" value="<?php if ($_GET['id']<>0) { echo voltea_fecha($registro->hasta).' '.$registro->horab; } else {echo '';} ?>" required>
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
$(document).ready(function() {
    $('.select2').select2();
	//----------------
	$('#txt_desde').datetimepicker({timepicker: true, datepicker:true, format:'d/m/Y H:i', step:60, yearStart:<?php echo date('Y') ; ?>, yearEnd: <?php echo date('Y')+1; ?>, theme:'dark', disabledWeekDays: [0,6], allowTimes:['07:00','08:00','09:00','10:00','11:00','12:00','13:00','14:00','15:00','16:00','17:00','18:00','19:00','20:00']});
	//--------------------------------
	$('#txt_hasta').datetimepicker({timepicker: true, datepicker:true, format:'d/m/Y H:i', step:60, yearStart:<?php echo date('Y') ; ?>, yearEnd: <?php echo date('Y')+1; ?>, theme:'dark', disabledWeekDays: [0,6], allowTimes:['07:00','08:00','09:00','10:00','11:00','12:00','13:00','14:00','15:00','16:00','17:00','18:00','19:00','20:00']});
	//-------------------------------- , allowTimes:['07:00','08:00','09:00','10:00','11:00','12:00','01:00','02:00','03:00','04:00','05:00','06:00','07:00','08:00']
});
</script>