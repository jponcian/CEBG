<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');

if ($_SESSION['VERIFICADO'] != "SI") { 
header ("Location: ../validacion.php?opcion=val"); 
exit(); }

$acceso=76;
//------- VALIDACION ACCESO USUARIO
include_once "../validacion_usuario.php";
//-----------------------------------
//$consultx = "SELECT * FROM rac WHERE rac = ".$_GET['id'].";";  //echo $consultx;
//$tablx = $_SESSION['conexionsql']->query($consultx);
//$registro = $tablx->fetch_object();
?>
<form id="form999" name="form999" method="post" >
			<!-- Modal Header -->
<div class="modal-header bg-fondo text-center">
	<h4 align="center" style="background-color:#0275d8; color:#FFFFFF" class="modal-title w-100 font-weight-bold py-2">Informaci&oacute;n de la Cuenta Bancaria<button type="button" class="close" data-dismiss="modal">&times;</button></h4>
    <input type="hidden" id="oid" name="oid" value="0"/>
</div>
<!-- Modal body -->
		<div class="p-1">
			
			<div class="row">
			
				<div class="form-group col-sm-4">
					<div class="input-group-text"><i class="fas fa-university mr-2"></i><select class="form-control"  style="font-size: 14px" name="txt_banco" id="txt_banco" onchange="">
						<option value="SELECCIONE"> -SELECCIONE- </option>
						<?php
						//--------------------
						$consultx = "SELECT * FROM banco_emisor WHERE banco IS NOT NULL or banco<>'' ORDER BY banco;"; 
						$tablx = $_SESSION['conexionsql']->query($consultx);
						while ($registro_x = $tablx->fetch_object())
						//-------------
						{
						echo '<option ';
						//if ($registro_x->codigo==$banco)	{echo ' selected="selected" ';}
						echo ' value="';
						echo $registro_x->banco;
						echo '">';
						echo mayuscula($registro_x->banco);
						echo '</option>';
						}
						?>
						</select>
					</select>
					</div>
				</div>

				<div class="form-group col-sm-6">
					<div class="input-group-text"><i class="fas fa-sort-numeric-up mr-2"></i>
					<input id="txt_cuenta" placeholder="Cuenta Bancaria" onkeypress="return SoloNumero(event,this)" maxlength="20" name="txt_cuenta" class="form-control" type="text" style="text-align:center"/>
					<!--<input placeholder="Ej: 9999-9999-99-9999999999" type="text" pattern='\d{4}[\ ]\d{4}[\ ]\d{2}[\ ]\d{10}' onkeyup="mascara(this.value,' ',cuenta_bancaria,true)" maxlength="23" name="numero_de_cuenta">-->

					</div>
				</div>
			</div>
			
			<div class="row">
				<div class="form-group col-sm-12">
					<div class="input-group-text"><i class="fas fa-font mr-2"></i>
					<input id="txt_descripcion" placeholder="Descripcion" name="txt_descripcion" class="form-control" type="text" style="text-align:center" maxlength="20" />
					</div>
				</div>
			</div>
			
		</div>
<!-- Modal footer -->
<div class="modal-footer justify-content-center">
	<button type="button" id="boton" class="btn btn-outline-success waves-effect" onclick="guardar(0)" ><i class="fas fa-save prefix grey-text mr-1"></i> Guardar Cuenta</button>
</div>
</div>
</div>
</form>
<script language="JavaScript">
//--------------------------------
</script>