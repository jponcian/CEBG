<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');

if ($_SESSION['VERIFICADO'] != "SI") { 
header ("Location: ../validacion.php?opcion=val"); 
exit(); }

$acceso=55;
//------- VALIDACION ACCESO USUARIO
include_once "../validacion_usuario.php";
//-----------------------------------
$id = decriptar($_GET["id"]); 
//---------
$consult = "SELECT bn_solicitudes.fecha, bn_solicitudes.numero, a_direcciones.direccion, a_direcciones.id FROM `bn_solicitudes`, a_direcciones WHERE bn_solicitudes.id=$id AND bn_solicitudes.division=a_direcciones.id;"; // WHERE id_direccion='$desde'
$tablx = $_SESSION['conexionsql']->query($consult);
$registro_x = $tablx->fetch_object();
//-----
$division = $registro_x->direccion;
$numero = $registro_x->numero;
$fecha = voltea_fecha($registro_x->fecha);
?>
<form id="form999" name="form999" method="post" >
			<!-- Modal Header -->
<div class="modal-header bg-fondo text-center">
<h4 align="center" style="background-color:#0275d8; color:#FFFFFF" class="modal-title w-100 font-weight-bold py-2">Procesar Solicitud N° <?php echo rellena_cero($numero,3); ?>
<button type="button" class="close" data-dismiss="modal" onclick="buscar2();">&times;</button></h4>
</div>
<!-- Modal body -->
		<div class="p-1">
			<div class="row">
				
<div class="form-group col-sm-9">
	<div class="input-group-text"><?php echo $division; ?></div>
</div>
<div class="form-group col-sm-3">
	<div class="input-group-text">Fecha: <?php echo $fecha; ?></div>
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
//$('#cmdbuscar').hide();
//--------------------------------
setTimeout(function()	{
		listar_bienes(<?php echo $id; ?>);
		},500)	
//--------------------------------
</script>