<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');

if ($_SESSION['VERIFICADO'] != "SI") { 
header ("Location: validacion.php?opcion=val"); 
exit(); }

$acceso=98;
//------- VALIDACION ACCESO USUARIO
include_once "../validacion_usuario.php";
//-----------------------------------
$cedula = decriptar($_GET['cedula']);
$consultx = "SELECT * FROM rac WHERE cedula = '$cedula';"; // echo $consultx;
$tablx = $_SESSION['conexionsql']->query($consultx);
$registro = $tablx->fetch_object();
$id_area = $registro->id_area;
//--------------
$proyecto_actual = proyecto_actual();
?>
<form id="form999" name="form999" method="post" >
			<!-- Modal Header -->
<div class="modal-header bg-fondo text-center">
	<h4 align="center" style="background-color:#0275d8; color:#FFFFFF" class="modal-title w-100 font-weight-bold py-2">Asignar ODI
	  <button type="button" class="close" data-dismiss="modal">&times;</button></h4>
</div>
<!-- Modal body -->
<div class="p-1">
			
<!--<br>-->
<table class="formateada table" border="1" align="center" width="100%">
<thead>
	<tr>
		<th bgcolor="#CCCCCC" align="center"><strong>Item</strong></th>
		<th bgcolor="#CCCCCC" align="center"><strong>Descripción</strong></th>
		<th bgcolor="#CCCCCC" align="center"><strong>Estatus</strong></th>
	</tr>
</thead>
<tbody><?php 
$consultx = "SELECT eval_odis.*, a_areas.area FROM	a_areas, eval_odis, eval_asignacion WHERE eval_odis.id = eval_asignacion.id_odi AND cedula='$cedula' AND eval_odis.id_area = a_areas.id AND eval_odis.id_area=$id_area AND eval_odis.id_proyecto='".$proyecto_actual[0]."' ORDER BY	a_areas.id ASC";//$filtrar.$_GET['valor'].";"; 
$tablx = $_SESSION['conexionsql']->query($consultx);
if ($tablx->num_rows>0)
	{		}
else
	{	?><tr><td colspan="4"><div align="center" ><h4>No Existen ODIS Asignados</h4></div></td></tr><?php }
//-------------
while ($registro = $tablx->fetch_object())
	{ 	
		$i++;
		$id_odi = $registro->id;
		$id_direccion = $registro->id_direccion;
		$id_area = $registro->id_area;
		//---------
		$consultx1 = "SELECT * FROM eval_asignacion WHERE id_odi=$id_odi AND cedula='$cedula' LIMIT 1"; 
		$tablx1 = $_SESSION['conexionsql']->query($consultx1);
		if ($tablx1->num_rows>0)
			{	$valor1 = 'checked';	$valor2 = 'no';	}
		else
			{	$valor1 = '';	$valor2 = 'si';	}
		?>
	<tr>
		<td style="vertical-align: middle"><strong><?php echo $i; ?></strong></td>
		<td style="vertical-align: middle"><strong><?php echo $registro->descripcion; ?></strong></td>
		<td style="vertical-align: middle" align="right">

	<input onClick="asignar('<?php echo $registro->id; ?>', '<?php echo $cedula; ?>', '<?php echo $id_direccion; ?>', '<?php echo $id_area; ?>', '<?php echo $valor2; ?>');" id="txt_id<?php echo $registro->id; ?>" name="txt_id<?php echo $registro->id; ?>" type="checkbox" class="switch_new" value="1" <?php echo $valor1; ?> />
	<label for="txt_id<?php echo $registro->id; ?>" class="lbl_switch"></label>	
 <?php 
	 }
 ?>
 </tbody>  
</table>

</div>
<!-- Modal footer -->
<div class="modal-footer justify-content-center">

</div>
</form>
<script language="JavaScript">
//--------------------------------
function asignar(id, cedula, id_direccion, id_area, tipo)
 {
	var parametros = "id="+id+"&cedula="+cedula+"&id_direccion="+id_direccion+"&id_area="+id_area+"&tipo="+tipo;
	$.ajax({  
		type : 'POST',
		url  : 'personal/24c_guardar.php',
		dataType:"json",
		data:  parametros, 
		success:function(data) {  	
			if (data.tipo=="info")
				{	alertify.success(data.msg);	
//					$('#modal_normal .close').click(); 
//					buscar();
				}
			else
				{	alertify.alert(data.msg);	}
			}  
		});
 }
//----------------
</script>