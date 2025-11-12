<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');

if ($_SESSION['VERIFICADO'] != "SI") { 
header ("Location: ../validacion.php?opcion=val"); 
exit(); }

$acceso=28;
//------- VALIDACION ACCESO USUARIO
include_once "../validacion_usuario.php";
//-----------------------------------
if (($_GET['tipo'])==2)	{	$id = ($_GET['id']);	} else {	$id = decriptar($_GET['id']);	}
//-----------------------------------
$consultx = "SELECT * FROM rac_visita WHERE cedula = ".$id.";";  //echo $consultx;
$tablx = $_SESSION['conexionsql']->query($consultx);
$registro = $tablx->fetch_object();
if ($tablx->num_rows>0)	
{$visita = $registro->cedula.' - '.$registro->nombre;}
else {$visita = $id.' - NO REGISTRADO';}
?>
<form id="form999" name="form999" method="post" >
			<!-- Modal Header -->
<div class="modal-header bg-fondo text-center">
	<h4 align="center" style="background-color:#0275d8; color:#FFFFFF" class="modal-title w-100 font-weight-bold py-2">Abrir Ticket de Atención
	  <button type="button" class="close" data-dismiss="modal">&times;</button></h4>
    <input type="hidden" id="oid" name="oid" value="<?php echo $registro->rac; ?>"/>
</div>
<!-- Modal body -->
		<div class="p-1">
			
			<div class="row">
				<div class="form-group col-sm-12">
					<div class="input-group-text"><?php echo $visita; ?></div>
				</div>
			</div>
			
			<div class="row">
				<div class="form-group col-sm-10">
					<div class="input-group">
						<div class="input-group-text">Nombres y Apellidos:</div>
						<input id="txt_nombres" name="txt_nombres" class="form-control" value="<?php echo ($registro->nombre); ?>" type="text" style="text-align:left" />
					</div>
				</div>
				<div class="form-group col-sm-2">
					<div class="input-group">
						<div class="input-group-text"><i class="fa-solid fa-cake-candles"></i></div>
						<input id="txt_edad" name="txt_edad" class="form-control" value="<?php echo ($registro->edad); ?>" placeholder="Edad" type="text" style="text-align:left" />
					</div>
				</div>
			</div>

			<div class="row">
				
				<div class="form-group col-sm-4">
					<div class="input-group">
						<div class="input-group-text"><i class="fa-regular fa-envelope"></i></div>
						<input id="txt_correo" name="txt_correo" class="form-control" value="<?php echo ($registro->correo); ?>" placeholder="Correo" type="text" style="text-align:left" />
					</div>
				</div>
				
				<div class="form-group col-sm-4">
					<div class="input-group">
						<div class="input-group-text"><i class="fas fa-mobile-alt"></i></div>
						<input id="txt_telefono" name="txt_telefono" class="form-control" value="<?php echo ($registro->telefono); ?>" type="text" maxlength="30" style="text-align:center" />
					</div>
				</div>
				
				<div class="form-group col-sm-4">
					<div class="input-group">
						<div class="input-group-text"><i class="fas fa-venus-mars"></i></div>
						<select class="custom-select" style="font-size: 14px" name="txt_sexo" id="txt_sexo" onchange="">
<!--					<option value="0"> -SELECCIONE- </option>-->
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
				<div class="form-group col-sm-12">
					<div class="input-group">
						<div class="input-group-text">Organismo:</div>
						<input id="txt_organismo" name="txt_organismo" class="form-control" value="<?php echo ($registro->organismo); ?>" type="text" style="text-align:left" />
					</div>
				</div>
			</div>
			
			<div class="row">
				<div class="form-group col-sm-12">
					<div class="input-group">
						<div class="input-group-text">Cargo:</div>
						<input id="txt_cargo" name="txt_cargo" class="form-control" value="<?php echo ($registro->cargo); ?>" type="text" style="text-align:left" />
					</div>
				</div>
			</div>
			
			<div class="row">
				<div class="form-group col-sm-12">
					<div class="input-group">
						<div class="input-group-text">Observacion:</div>
						<input id="txt_observacion" name="txt_observacion" class="form-control" value="" type="text" style="text-align:left" />
					</div>
				</div>
			</div>
			
	<!-- Modal footer -->
<div class="modal-footer justify-content-center">
	<button type="button" id="boton" class="btn btn-outline-success waves-effect" onclick="agregar('<?php echo encriptar($id); ?>','<?php echo $_GET['tipo']; ?>');" ><i class="fa-solid fa-person-arrow-up-from-line prefix grey-text mr-1"></i> ABRIR TICKET</button>
</div>
</form>
<script language="JavaScript">
setTimeout(function()	{
		$('#txt_nombres').focus(); //listar_tabla();
		},500);	//document.form1.ocedula.focus;
//----------------- PARA VALIDAR
function validar_detalle()
	{
	error = 0;
	if(document.form999.txt_nombres.value=="")	
		{	 document.form999.txt_nombres.focus(); 	alertify.alert("Debe indicar el nombre del Visitante!");			error = 1;  }
	if(document.form999.txt_telefono.value=="")	
		{	 document.form999.txt_telefono.focus(); 	alertify.alert("Debe indicar el telefono!");			error = 1;  }
	if(document.form999.txt_cargo.value=="")	
		{	 document.form999.txt_cargo.focus(); 	alertify.alert("Debe Ingresar el Cargo!");			error = 1;  }
	if(document.form999.txt_organismo.value=="")	
		{	 document.form999.txt_organismo.focus(); 	alertify.alert("Debe Ingresar el Organismo!");			error = 1;  }
	if(document.form999.txt_edad.value=="")	
		{	 document.form999.txt_edad.focus(); 	alertify.alert("Debe Ingresar la Edad!");			error = 1;  }
	return error;
	}
</script>