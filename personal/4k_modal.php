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
?>
<form id="form999" name="form999" method="post" >
			<!-- Modal Header -->
<div class="modal-header bg-fondo text-center">
	<h4 align="center" style="background-color:#0275d8; color:#FFFFFF" class="modal-title w-100 font-weight-bold py-2">Encargadur&iacute;a
      <button type="button" class="close" data-dismiss="modal">&times;</button></h4>
    <input type="hidden" id="oid" name="oid" value="<?php echo $registro->rac; ?>"/>
</div>
<!-- Modal body -->
		<div class="p-1">
			
			<div class="row">
				<div class="form-group col-sm-12">
					<div class="input-group-text"><?php echo $registro->cedula.' - '.$registro->nombre; ?></div>
				</div>
			</div>
			
			<div class="row">
				<div class="form-group col-sm-2">
					<div class="input-group-text"><i class="fas fa-user-tie mr-1"></i>Nomina</div>
				</div>
				<div class="form-group col-sm-5">
					<div class="input-group">
					<select class="custom-select" style="font-size: 14px" name="txt_nomina" id="txt_nomina" onchange="">
					<option value="0"> -NO- </option>
					<?php
					//--------------------
					$consultx = "SELECT * FROM a_nomina WHERE codigo NOT IN ('005','006','0700','0800','001');"; 
					$tablx = $_SESSION['conexionsql']->query($consultx);
					while ($registro_x = $tablx->fetch_object())
					//-------------
					{
					echo '<option ';
					if ($registro->nomina2==$registro_x->nomina)	{echo ' selected="selected" ';}
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
					<select class="custom-select" style="font-size: 14px" name="txt_ubicacion" id="txt_ubicacion" onchange="">
					<option value="0"> -NO- </option>
					<?php
					//--------------------
					$consultx = "SELECT * FROM a_areas ORDER BY descripcion;"; 
					$tablx = $_SESSION['conexionsql']->query($consultx);
					while ($registro_x = $tablx->fetch_object())
					//-------------
					{
					echo '<option ';
					if (trim($registro->ubicacion2)==trim($registro_x->descripcion))	{echo ' selected="selected" ';}
					echo ' value="';
					echo $registro_x->descripcion;
					echo '">';
					echo mayuscula($registro_x->descripcion);
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
					<select class="custom-select" style="font-size: 14px" name="txt_cargo" id="txt_cargo" onchange="">
					<option value="0"> -NO- </option>
					<?php
					//--------------------
					$consultx = "SELECT * FROM a_cargo ORDER BY cargo;"; 
					$tablx = $_SESSION['conexionsql']->query($consultx);
					while ($registro_x = $tablx->fetch_object())
					//-------------
					{
					echo '<option ';
					if ($registro->cargo2==trim(mayuscula($registro_x->cargo)))	{echo ' selected="selected" ';}
					echo ' value="';
					echo trim(mayuscula($registro_x->cargo));
					echo '">';
					echo mayuscula($registro_x->cargo);
					echo '</option>';
					}
					?>
					</select>
					</div>
				</div>
			</div>
			
			<div class="row">
				<div class="form-group col-sm-2">
					<div class="input-group-text"><i class="fas fa-money-bill-alt mr-2"></i>Sueldo:</div>
				</div>
				<div class="form-group col-sm-3">
					<div class="input-group">
					<input id="txt_sueldo" name="txt_sueldo" class="form-control" value="<?php echo formato_moneda($registro->sueldo2); ?>" type="text" style="text-align:right" />
					</div>
				</div>
				
			</div>
			
			
			
			<div class="row">

				<div class="form-group col-sm-2">
					<div class="input-group">
						<div class="input-group-text"><i class="fas fa-th-large mr-1"></i>SSO</div>
						<input id="txt_sso" name="txt_sso"  class="form-control" <?php if ($registro->sus_sso2>0) {echo 'checked="checked"';} ?> type="checkbox" value="4" />
					</div>
				</div>
				
				<div class="form-group col-sm-2">
					<div class="input-group">
						<div class="input-group-text"><i class="fas fa-th-large mr-1"></i>LPH</div>
						<input id="txt_lph" name="txt_lph"  class="form-control" <?php if ($registro->sus_lph2>0) {echo 'checked="checked"';} ?> type="checkbox" value="1" />
					</div>
				</div>
				
				<div class="form-group col-sm-2">
					<div class="input-group">
						<div class="input-group-text"><i class="fas fa-th-large mr-1"></i>PF</div>
						<input id="txt_pf" name="txt_pf"  class="form-control" <?php if ($registro->sus_pfo2>0) {echo 'checked="checked"';} ?> type="checkbox" value="0.5" />
					</div>
				</div>

				<div class="form-group col-sm-2">
					<div class="input-group">
						<div class="input-group-text"><i class="fas fa-th-large mr-1"></i>FEJ</div>
						<input id="txt_fej" name="txt_fej"  class="form-control" <?php if ($registro->sus_fej2>0) {echo 'checked="checked"';} ?> type="checkbox" value="3" />
					</div>
				</div>
				
			</div>
			
		</div>
<!-- Modal footer -->
<div class="modal-footer justify-content-center">
	<button type="button" id="boton" class="btn btn-outline-success waves-effect" onclick="guardar(3)" ><i class="fas fa-save prefix grey-text mr-1"></i> Guardar Cambios</button>
</div>
</form>
<script language="JavaScript">
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