<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');

if ($_SESSION['VERIFICADO'] != "SI") { 
header ("Location: ../validacion.php?opcion=val"); 
exit(); }

$acceso=15;
//------- VALIDACION ACCESO USUARIO
include_once "../validacion_usuario.php";
//-----------------------------------
$consultx = "SELECT * FROM rac WHERE rac = ".$_GET['id'].";";  //echo $consultx;
$tablx = $_SESSION['conexionsql']->query($consultx);
$registro = $tablx->fetch_object();
$eventual = $registro->temporal;
?>
<form id="form999" name="form999" method="post" >
			<!-- Modal Header -->
<div class="modal-header bg-fondo text-center">
	<h4 align="center" style="background-color:#0275d8; color:#FFFFFF" class="modal-title w-100 font-weight-bold py-2">Informaci&oacute;n Laboral 
	  <button type="button" class="close" data-dismiss="modal">&times;</button></h4>
    <input type="hidden" id="oid" name="oid" value="<?php echo $registro->rac; ?>"/>
</div>
<!-- Modal body -->
		<div class="p-1">
			
			<div class="row">
				<div class="form-group col-sm-12">
					<div class="input-group-text"><?php echo $registro->cedula.' - '.$registro->nombre.' '.$registro->nombre2.' '.$registro->apellido.' '.$registro->apellido2; ?></div>
				</div>
			</div>
			
			<div class="row">
				<div class="form-group col-sm-2">
					<div class="input-group-text"><i class="fas fa-user-tie mr-1"></i>Nomina</div>
				</div>
				<div class="form-group col-sm-5">
					<div class="input-group">
					<select class="select2" style="width: 300px" style="font-size: 14px" name="txt_nomina" id="txt_nomina" onchange="">
					<option value="0"> -SELECCIONE- </option>
					<?php
					//--------------------
					$consultx = "SELECT * FROM a_nomina WHERE eventual=$eventual AND codigo<>'0700';"; 
					$tablx = $_SESSION['conexionsql']->query($consultx);
					while ($registro_x = $tablx->fetch_object())
					//-------------
					{
					echo '<option ';
					if ($registro->nomina==$registro_x->nomina)	{echo ' selected="selected" ';}
					echo ' value="';
					echo $registro_x->nomina;
					echo '">';
					echo mayuscula($registro_x->nomina);
					echo '</option>';
					}
					?>
					</select>
					</div>
				</div>
			</div>

			<div class="row">
				<div class="form-group col-sm-2">
					<div class="input-group-text"><i class="fas fa-map-marker-alt mr-1"></i>Ubicacion</div>
				</div>
				<div class="form-group col-sm-8">
					<div class="input-group">
					<select class="select2" style="width: 700px" style="font-size: 14px" name="txt_ubicacion" id="txt_ubicacion" onchange="">
					<option value="0"> -SELECCIONE- </option>
					<?php
					//--------------------
					$consultx = "SELECT * FROM a_areas ORDER BY area;";
					$tablx = $_SESSION['conexionsql']->query($consultx);
					while ($registro_x = $tablx->fetch_object())
					//-------------
					{
					echo '<option ';
					if (trim($registro->id_area)==trim($registro_x->id))	{echo ' selected="selected" ';}
					echo ' value="';
					echo $registro_x->id;
					echo '">';
					echo ($registro_x->area);
					echo '</option>';
					}
					?>
					</select>
					</div>
				</div>
			</div>

			<div class="row">
				<div class="form-group col-sm-2">
					<div class="input-group-text"><i class="fas fa-school mr-2"></i>Cargo:</div>
				</div>
				<div class="form-group col-sm-7">
					<div class="input-group">
					<select class="select2" style="width: 600px" style="font-size: 14px" name="txt_cargo" id="txt_cargo" onchange="">
					<option value="0"> -SELECCIONE- </option>
					<?php
					//--------------------
					$consultx = "SELECT * FROM a_cargo ORDER BY cargo;"; 
					$tablx = $_SESSION['conexionsql']->query($consultx);
					while ($registro_x = $tablx->fetch_object())
					//-------------
					{
					echo '<option ';
					if (($registro->id_cargo)==($registro_x->codigo))	{echo ' selected="selected" ';}
					echo ' value="';
					echo trim(($registro_x->codigo));
					echo '">';
					echo ($registro_x->cargo);
					echo '</option>';
					}
					?>
					</select>
					</div>
				</div>
			</div>
			
			<div class="row">
				<div class="form-group col-sm-4">
					<div class="input-group">
						<div class="input-group-text">Sueldo</div>
						<input id="txt_sueldo" name="txt_sueldo" class="form-control" value="<?php echo formato_moneda($registro->sueldo); ?>" type="text" style="text-align:right" />
					</div>
				</div>	
				<div class="form-group col-sm-4">
					<div class="input-group">
						<div class="input-group-text">Suspender Cargo:</div>
						<input id="txt_suspender" name="txt_suspender"  class="form-control" <?php if ($registro->suspendido==1) {echo 'checked="checked"';} ?> type="checkbox" value="1" />
					</div>
				</div>	

				<div class="form-group col-sm-4">
					<div class="input-group">
						<div class="input-group-text">Suspender Vacaciones</div>
						<input id="txt_suspenderv" name="txt_suspenderv"  class="form-control" <?php if ($registro->suspendidov==1) {echo 'checked="checked"';} ?> type="checkbox" value="1" />
					</div>
				</div>	

			</div>
			
			<div class="row">
		
				<div class="form-group col-sm-3">
					<div class="input-group">
						<div class="input-group-text"></i>Cestatickets</div>
						<input id="txt_tickets" name="txt_tickets"  class="form-control" <?php if ($registro->tickets==1) {echo 'checked="checked"';} ?> type="checkbox" value="1" />
					</div>
				</div>
				
				<div class="form-group col-sm-3">
					<div class="input-group">
						<div class="input-group-text">Ayuda:</div>
						<input id="txt_ayuda" name="txt_ayuda" class="form-control" <?php if ($registro->ayuda==1) {echo 'checked="checked"';} ?> type="checkbox" value="1" />
					</div>
				</div>
			</div>

			<div class="row">
				<div class="form-group col-sm-5">
					<div class="input-group">
						<div class="input-group-text"><i class="far fa-calendar-alt mr-2"></i>Fecha Egreso</div>
						<input type="text" style="text-align:center" class="form-control " name="txt_egreso" id="txt_egreso" placeholder="Fecha de Egreso"  minlength="1" maxlength="10" value="<?php  echo voltea_fecha($registro->fecha_egreso);?>">
					</div>
				</div>	

				<div class="form-group col-sm-5">
					<div class="input-group">
						<div class="input-group-text"><i class="far fa-calendar-alt mr-2"></i>Fecha Jubilacion</div>
						<input type="text" style="text-align:center" class="form-control " name="txt_jub" id="txt_jub" placeholder="Fecha de Jubilacion"  minlength="1" maxlength="10" value="<?php  echo voltea_fecha($registro->fecha_jub);?>">
					</div>
				</div>	

			</div>

			<div class="row">
		
				<div class="form-group col-sm-4">
					<div class="input-group">
						<div class="input-group-text">Descontar Sueldo</div>
						<input data-toggle="tooltip" style="text-align: center" title="Descontar Dias de Sueldo" id="txt_dias_sueldo" name="txt_dias_sueldo" value="<?php echo ($registro->des_sueldo); ?>" class="form-control" type="text" placeholder="Dias"/>
					</div>
				</div>	

				<div class="form-group col-sm-4">
					<div class="input-group">
						<div class="input-group-text">Descontar Tickets</div>
						<input data-toggle="tooltip" style="text-align: center" title="Descontar Dias de Tickets" id="txt_dias_tickets" name="txt_dias_tickets" value="<?php echo ($registro->des_tickets); ?>" class="form-control" type="text" placeholder="Dias"/>
					</div>
				</div>	
				
				<div class="form-group col-sm-4">
					<div class="input-group">
						<div class="input-group-text">Vacaciones Venc</div>
						<input data-toggle="tooltip" style="text-align: center" title="Vacaciones Vencidas" id="txt_vacaciones" name="txt_vacaciones" value="<?php echo ($registro->vacaciones); ?>" class="form-control" type="text" placeholder="Cantidad"/>
					</div>
				</div>	
				
			</div>

			<div class="row">

				<div class="form-group col-sm-2">
					<div class="input-group">
						<div class="input-group-text"><i class="fas fa-th-large mr-1"></i>SSO</div>
						<input id="txt_sso" name="txt_sso"  class="form-control" <?php if ($registro->sus_sso>0) {echo 'checked="checked"';} ?> type="checkbox" value="4" />
					</div>
				</div>
				
				<div class="form-group col-sm-2">
					<div class="input-group">
						<div class="input-group-text"><i class="fas fa-th-large mr-1"></i>FAOV</div>
						<input id="txt_lph" name="txt_lph"  class="form-control" <?php if ($registro->sus_lph>0) {echo 'checked="checked"';} ?> type="checkbox" value="1" />
					</div>
				</div>
				
				<div class="form-group col-sm-2">
					<div class="input-group">
						<div class="input-group-text"><i class="fas fa-th-large mr-1"></i>PF</div>
						<input id="txt_pf" name="txt_pf"  class="form-control" <?php if ($registro->sus_pfo>0) {echo 'checked="checked"';} ?> type="checkbox" value="0.5" />
					</div>
				</div>

				<div class="form-group col-sm-2">
					<div class="input-group">
						<div class="input-group-text"><i class="fas fa-th-large mr-1"></i>FEJ</div>
						<input id="txt_fej" name="txt_fej"  class="form-control" <?php if ($registro->sus_fej>0) {echo 'checked="checked"';} ?> type="checkbox" value="3" />
					</div>
				</div>
				
			</div>
			<div class="row">
				<div class="form-group col-sm-3">
					<div class="input-group">
						<div class="input-group-text"></i>Evaluar Odi</div>
						<input id="txt_odis" name="txt_odis"  class="form-control" <?php if ($registro->evaluar_odis==1) {echo 'checked="checked"';} ?> type="checkbox" value="1" />
					</div>
				</div>
				<div class="form-group col-sm-4">
					<div class="input-group">
						<div class="input-group-text"></i><i class="fa-solid fa-person-circle-exclamation"></i> Pago Adicional</div>
						<input id="txt_pago" name="txt_pago"  class="form-control" <?php if ($registro->pago_adic==1) {echo 'checked="checked"';} ?> type="checkbox" value="1" />
					</div>
				</div>
			</div>
			
		</div>
<!-- Modal footer -->
<div class="modal-footer justify-content-center">
	<button type="button" id="boton" class="btn btn-outline-success waves-effect" onclick="guardar(2)" ><i class="fas fa-save prefix grey-text mr-1"></i> Guardar Cambios</button>
</div>
</div>
</div>
</form>
<script language="JavaScript">
// PARA EL SELECT2
$(document).ready(function() {
    $('.select2').select2();
	$("#txt_egreso").datepicker();
	$("#txt_jub").datepicker();
});
//--------------------------------
$("#txt_sueldo").on({
    "focus": function (event) {
        $(event.target).select();
    },
    "keyup": function (event) {
        $(event.target).val(function (index, value ) {
            return value.replace(/\D/g, "")
                        .replace(/([0-9])([0-9]{2})$/, '$1,$2')
                        .replace(/\B(?=(\d{3})+(?!\d)\.?)/g, ".");
        });
    }
});
</script>