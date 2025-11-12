<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');

if ($_SESSION['VERIFICADO'] != "SI") { 
header ("Location: ../validacion.php?opcion=val"); 
exit(); }

$acceso=32;
//------- VALIDACION ACCESO USUARIO
include_once "../validacion_usuario.php";
//-----------------------------------
$id = $_GET['id'];

$consultx = "SELECT ordenes_pago.descripcion, ordenes_pago.id, ordenes_pago.tipo_solicitud, ordenes_pago.numero, ordenes_pago.fecha, ordenes_pago.asignaciones, ordenes_pago.descuentos, ordenes_pago.total, ordenes_pago.estatus, contribuyente.rif, contribuyente.nombre FROM ordenes_pago , contribuyente WHERE ordenes_pago.estatus<>99 AND (ordenes_pago.estatus>=0) AND contribuyente.id = ordenes_pago.id_contribuyente AND ordenes_pago.id = '$id' LIMIT 1;"; 
$tablx = $_SESSION['conexionsql']->query($consultx);
$registro = $tablx->fetch_object();
?>
<form id="form999" name="form999" method="post" >
			<!-- Modal Header -->
<div class="modal-header bg-fondo text-center">
<input type="hidden" id="oid" name="oid" value="<?php  echo $_GET['id'];?>"/>
<h4 align="center" style="background-color:#0275d8; color:#FFFFFF" class="modal-title w-100 font-weight-bold py-2">Modificar Orden de Pago
  <button type="button" class="close" data-dismiss="modal">&times;</button></h4>
</div>
<!-- Modal body -->
		<div class="p-1">
			
	<div class="row">
		<div class="form-group col-sm-4">
			<div class="input-group">
				<div class="input-group-text" align="center">Rif</div>
				<input class="form-control" type="text" style="text-align:center" value="<?php  echo $registro->rif;?>" readonly="" />
			</div>
		</div>
						
		<div class="form-group col-sm-4">
			<div class="input-group">
				<div class="input-group-text"><i class="far fa-calendar-alt mr-2"></i>Numero</div>
				<input readonly="" type="text" style="text-align:center" class="form-control " value="<?php echo ($registro->numero);?>" required></div>
		</div>	
		
		<div class="form-group col-sm-4">
			<div class="input-group">
				<div class="input-group-text"><i class="far fa-calendar-alt mr-2"></i>Fecha</div>
				<input  readonly="" type="text" style="text-align:center" class="form-control " value="<?php echo voltea_fecha($registro->fecha);?>" required></div>
		</div>	
		
	</div>
			
	<div class="row">
		<div class="form-group col-sm-12">
				<input id="txt_nombres" placeholder="Proveedor" name="txt_nombres" class="form-control" type="text" style="text-align:center" value="<?php  echo $registro->nombre;?>" readonly=""/>
		</div>
	</div>

	<div class="row">
		<div class="form-group col-sm-12">
			<div class="input-group-text"><i class="fas fa-university mr-2"></i>
			<textarea id="txt_concepto" name="txt_concepto" placeholder="Escribe aqui el Concepto" class="form-control" rows="4"><?php  echo $registro->descripcion;?></textarea>
			</div>
		</div>
	</div>

<br>

<div align="center">			
	<button type="button" id="boton" class="btn btn-outline-success waves-effect" onclick="guardar('boton')" ><i class="fas fa-save prefix grey-text mr-1"></i> Guardar Cambios</button>			
</div>
	
	</div>
		</div>
<!-- Modal footer -->
<div class="modal-footer justify-content-center">
	<div align="center" id="div3">			

	</div>
</div>

</form>
<script language="JavaScript">
//--------------------------------
$('#txt_concepto').focus();  
</script>