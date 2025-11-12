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
//$consultx = "SELECT * FROM rac WHERE rac = ".$_GET['id'].";";  //echo $consultx;
//$tablx = $_SESSION['conexionsql']->query($consultx);
//$registro = $tablx->fetch_object();
?>
<form id="form999" name="form999" method="post" >
			<!-- Modal Header -->
<div class="modal-header bg-fondo text-center">
	<h4 align="center" style="background-color:#0275d8; color:#FFFFFF" class="modal-title w-100 font-weight-bold py-2">Datos B&aacute;sicos 
	  <button type="button" class="close" data-dismiss="modal">&times;</button></h4>
    <input type="hidden" id="oid" name="oid" value="0"/>
</div>
<!-- Modal body -->
		<div class="p-1">
			
			<div class="row">
				<div class="form-group col-sm-12">
					<div class="input-group-text">Nuevo Ingreso</div>
				</div>
			</div>
			
			<div class="row">
				
				<div class="form-group col-sm-2">
					<div class="input-group">
						<select class="custom-select" style="font-size: 14px" name="txt_digito" id="txt_digito" >
						<option value="V">V</option>
						<option value="E">E</option>
						</select>
					</div>
				</div>
								
				<div class="form-group col-sm-3">
					<div class="input-group">
						<input maxlength="8" onkeyup="saltar(event,'txt_nombres')" onChange="validar_cedula(this.value);" placeholder="Cedula" id="txt_cedula" name="txt_cedula" class="form-control" type="text" style="text-align:center" />
<!--						onKeyPress="validar_cedula(event);"-->
					</div>
				</div>
			</div>

			<div class="row">
				<div class="form-group col-sm-12">
					<div class="input-group">
					<input placeholder="1er Nombre" onChange="validar_campo('txt_nombre1');" id="txt_nombre1" name="txt_nombre1" class="form-control" value="<?php echo ($registro->nombre); ?>" type="text" style="text-align:center" />
					<input placeholder="2do Nombre" onChange="validar_campo('txt_nombre2');" id="txt_nombre2" name="txt_nombre2" class="form-control" value="<?php echo ($registro->nombre2); ?>" type="text" style="text-align:center" />
					<input placeholder="1er Apellido" onChange="validar_campo('txt_apellido1');" id="txt_apellido1" name="txt_apellido1" class="form-control" value="<?php echo ($registro->apellido); ?>" type="text" style="text-align:center" />
					<input placeholder="2do Apellido" onChange="validar_campo('txt_apellido2');" id="txt_apellido2" name="txt_apellido2" class="form-control" value="<?php echo ($registro->apellido2); ?>" type="text" style="text-align:center" />
					
					</div>
				</div>
			</div>
<div class="row">
				<div class="form-group col-sm-3">
					<div class="input-group">
						<div class="input-group-text">Codigo</div>
						<input onChange="validar_campo('txt_codigo');" type="text" style="text-align:center" class="form-control " name="txt_codigo" id="txt_codigo" placeholder="Codigo"  minlength="1" maxlength="5" value="<?php  echo ($registro->codigo);?>" >
					</div>
				</div>	
				
				<div class="form-group col-sm-5">
					<div class="input-group">
						<div class="input-group-text"><i class="far fa-calendar-alt mr-2"></i>Nacimiento</div>
						<input onChange="validar_campo('txt_nacimiento');" type="text" style="text-align:center" class="form-control " name="txt_nacimiento" id="txt_nacimiento" placeholder="Fecha de Ingreso"  minlength="1" maxlength="10" value="<?php ?>" required>
					</div>
				</div>	
				
				<div class="form-group col-sm-4">
					<div class="input-group">
						<div class="input-group-text"><i class="fas fa-venus-mars mr-2"></i></div>
						<select class="custom-select" style="font-size: 14px" name="txt_sexo" id="txt_sexo" onchange="">
						<option value="0"> -SELECCIONE- </option>
						<?php
						//--------------------
						echo '<option ';
						if ($registro->sexo=='F')	{echo ' selected="selected" ';}
						echo ' value="F">Femenino</option>';
						//--------------------
						echo '<option ';
						if ($registro->sexo=='M')	{echo ' selected="selected" ';}
						echo ' value="M">Masculino</option>';
						?>
						</select>
					</div>
				</div>

		</div>
	
			<div class="row">

				<div class="form-group col-sm-4">
					<div class="input-group"><div class="input-group-text"><i class="fas fa-mobile-alt mr-2"></i></div><input onChange="validar_campo('txt_telefono');" id="txt_telefono" name="txt_telefono" class="form-control" value="<?php echo ($registro->telefono); ?>" type="text" maxlength="30" style="text-align:center" />
					
				</div>
				</div>

				<div class="form-group col-sm-4">
					<div class="input-group">
						<div class="input-group-text"><i class="far fa-envelope mr-2"></i></div>
						<input onChange="validar_campo('txt_correo');" id="txt_correo" name="txt_correo" class="form-control" value="<?php echo ($registro->correo); ?>" type="email" maxlength="50" style="text-align:center" placeholder="Correo electrónico" />
					</div>
				</div>
				
				<div class="form-group col-sm-4">
					<div class="input-group">
						<div class="input-group-text"><i class="fas fa-graduation-cap mr-2"></i></div>
					
					<select class="custom-select" style="font-size: 14px" name="txt_profesion" id="txt_profesion" onchange="">
					<option value="0"> -SELECCIONE- </option>
					<?php
					//--------------------
					echo '<option ';
					if ($registro->profesion=='0' or $registro->profesion=='1')	{echo ' selected="selected" ';}
					echo ' value="0">Sin Profesion</option>';
					//--------------------
					echo '<option ';
					if ($registro->profesion=='2')	{echo ' selected="selected" ';}
					echo ' value="2">TSU</option>';
					//--------------------
					echo '<option ';
					if ($registro->profesion=='3')	{echo ' selected="selected" ';}
					echo ' value="3">Universitario</option>';
					//--------------------
					echo '<option ';
					if ($registro->profesion=='4')	{echo ' selected="selected" ';}
					echo ' value="4">Especialista</option>';
					//--------------------
					echo '<option ';
					if ($registro->profesion=='5')	{echo ' selected="selected" ';}
					echo ' value="5">Maestria</option>';
					//--------------------
					echo '<option ';
					if ($registro->profesion=='6')	{echo ' selected="selected" ';}
					echo ' value="6">Doctorado</option>';
					?>

					</select>
					
				</div>
			</div>
		</div>
		
			<div class="row">
				<div class="form-group col-sm-5">
					<div class="input-group">
						<div class="input-group-text"><i class="fas fa-university mr-2"></i></div>
					<input onChange="validar_campo('txt_cuenta');" id="txt_cuenta" data-placement="top" title="Cuenta Bancaria" placeholder="Cuenta Bancaria" name="txt_cuenta" class="form-control" value="<?php echo ($registro->cuenta); ?>" type="text" style="text-align:center" />
					
				</div>
				</div>
				
				<div class="form-group col-sm-3">
					<div class="input-group">
						<div class="input-group-text"><i class="far fa-building mr-2"></i></div>
					<input onChange="validar_campo('txt_annos');" data-toggle="tooltip" data-placement="top" title="A&ntilde;os de Servicio en otros Organismos Publicos" id="txt_annos" placeholder="A&ntilde;os de Servicio" name="txt_annos" class="form-control" value="<?php echo ($registro->anos_servicio); ?>" type="text" maxlength="2" style="text-align:center" />
					
					</div>
				</div>

			</div>
			<div class="row">
		
				<div class="form-group col-sm-5">
					<div class="input-group">
						<div class="input-group-text"><i class="far fa-calendar-alt mr-2"></i>Ingreso</div>
						<input onChange="validar_campo('txt_ingreso');" type="text" style="text-align:center" class="form-control " name="txt_ingreso" id="txt_ingreso" placeholder="Fecha de Ingreso"  minlength="1" maxlength="10" value="<?php  ?>" required>
					</div>
				</div>	
				
				<div class="form-group col-sm-5">
					<div class="input-group">
						<div class="input-group-text"><i class="far fa-calendar-alt mr-2"></i>Contrato</div>
						<input onChange="validar_campo('txt_contrato');" type="text" style="text-align:center" class="form-control " name="txt_contrato" id="txt_contrato" placeholder="Fecha de Ingreso"  minlength="1" maxlength="10" value="<?php  ?>" required>
					</div>
				</div>	
				
			</div>
			
		</div>
<!-- Modal footer -->
<div class="modal-footer justify-content-center">
	<button type="button" id="boton" class="btn btn-outline-success waves-effect" onclick="guardar(0)" ><i class="fas fa-save prefix grey-text mr-1"></i> Guardar Cambios</button>
</div>
</div>
</div>
</form>
<script language="JavaScript">
//---------------------
function validar_cedula(cedula)
 	 {
//	 (e.keyCode)?k=e.keyCode:k=e.which;
//	// Si la tecla pulsada es enter (codigo ascii 13)
//	if(k==13)
//		{
		var parametros = "cedula" + cedula;
		$.ajax({  
			type : 'POST',
			url  : 'funciones/buscar_empleado.php?cedula='+cedula,
			data: parametros,
			dataType:"json",
			success:function(data) {  
				if (data.tipo=="alerta")
					{	
					alertify.alert(data.msg);
					document.form999.txt_cedula.value='';
					document.form999.txt_cedula.focus();
					}
				}  
			});
//		}
	}
//--------------------------------
setTimeout(function()	{
		$('#txt_cedula').focus();
		},500)	
$("#txt_nacimiento").datepicker();
$("#txt_ingreso").datepicker();
$("#txt_contrato").datepicker();
//--------------------------------
	
//--------------------------------
</script>