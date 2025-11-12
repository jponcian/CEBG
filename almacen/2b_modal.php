<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');

if ($_SESSION['VERIFICADO'] != "SI") { 
header ("Location: ../validacion.php?opcion=val"); 
exit(); }

$acceso=126;
//------- VALIDACION ACCESO USUARIO
include_once "../validacion_usuario.php";
//-----------------------------------
if ($_SESSION["bienes"]==0)
	{	$condicion = " AND id_direccion=".$_SESSION["direccion"]; 	}
else
	{	$condicion = ""; 	}
?>
<form id="form999" name="form999" method="post" >
			<!-- Modal Header -->
<div class="modal-header bg-fondo text-center">
<h4 align="center" style="background-color:#0275d8; color:#FFFFFF" class="modal-title w-100 font-weight-bold py-2">Nuevo Movimiento
<button type="button" class="close" data-dismiss="modal" onclick="buscar2();">&times;</button></h4>
</div>
<!-- Modal body -->
		<div class="p-1">
			<div class="row">
				
				<div class="form-group col-sm-6">
					<div class="input-group-text">Origen: <select class="custom-select" style="font-size: 14px" name="txt_origen" id="txt_origen" onchange="combo(this.value);">
					<option value="0">Seleccione</option>
<?php
//--------------------
$consult = "SELECT a_direcciones.direccion, trim(a_areas.area) as area, a_areas.* FROM a_areas, bn_bienes, a_direcciones WHERE a_direcciones.id = a_areas.id_direccion 	AND a_areas.id = bn_bienes.id_area $condicion GROUP BY id_area ORDER BY area;";// WHERE id_direccion='$desde'
$tablx = $_SESSION['conexionsql']->query($consult);
while ($registro_x = $tablx->fetch_object())
//-------------
	{
	echo '<option value="';
	echo $registro_x->id;
	echo '" ';
	if ($partida==$registro_x->id) {echo 'selected="selected"';}
	echo ' >';
	echo $registro_x->area . ' ('.$registro_x->division.')';
	echo '</option>';
	}
?>
					</select>
					</div>
				</div>

				<div class="form-group col-sm-6">
					<div class="input-group-text">Destino: <select class="custom-select" style="font-size: 14px" name="txt_destino" id="txt_destino" onchange="">
					<option value="0">Debe Seleccionar el Area de Destino...</option>
					</select>
				</div>
			</div>
		</div>

<table width="100%" border="1">
  <tr>
    <th scope="col"><input onkeydown="puro_numero('txt_numero');" onkeyup="saltar(event,'txt_bien');listar_bienes2(event);" id="txt_numero" name="txt_numero" placeholder="Numero" class="form-control" type="text" style="text-align:center" /></th>
    <th width="80%" scope="col"><input onkeyup="listar_bienes2(event);" id="txt_bien" name="txt_bien" placeholder="Descripcion del Bien Nacional" class="form-control" type="text" style="text-align:center" /></th>
  </tr>
</table>
			<br>
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
//setTimeout(function()	{
//		$('#txt_rif').focus();
//		},1000)	
//--------------------------------
</script>