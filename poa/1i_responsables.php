<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');

if ($_SESSION['VERIFICADO'] != "SI") { 
header ("Location: ../validacion.php?opcion=val"); 
exit(); }

$acceso=62;
//------- VALIDACION ACCESO USUARIO
include_once "../validacion_usuario.php";
//-----------------------------------
$id_proyecto = $_GET['id'];
$id_poa = $_GET['id_poa'];
$anno = $_GET['anno'];
//-----------------------------------
$consulta = "DROP TABLE IF EXISTS proyecto;"; 
$tablax = $_SESSION['conexionsql']->query($consulta);
$consulta = "CREATE TEMPORARY TABLE proyecto (SELECT * FROM poa_proyecto_responsable WHERE poa_proyecto_responsable.id_proyecto = $id_proyecto);";
$tabla_x = $_SESSION['conexionsql']->query($consulta);
?>
<form id="form999" name="form999" method="post" >
			<!-- Modal Header -->
<div class="modal-header bg-fondo text-center">
	<button type="button" class="btn btn-outline-info btn-sm" onclick="cheques('<?php echo $id_poa; ?>','<?php echo $anno; ?>');">Volver</button><h4 align="center" style="background-color:#0275d8; color:#FFFFFF" class="modal-title w-100 font-weight-bold py-2">Responsables del Proyecto
	  
		<button hidden="" type="button" class="close" data-dismiss="modal">&times;</button></h4>
    <input type="hidden" id="oid_poa" name="oid_poa" value="<?php echo $id_poa; ?>"/>
    <input type="hidden" id="oanno" name="oanno" value="<?php echo $anno; ?>"/>
    <input type="hidden" id="oid_proyecto" name="oid_proyecto" value="<?php echo $id_proyecto; ?>"/>
</div>
<!-- Modal body -->
		<div class="p-1">

<table class="table table-striped table-hover" bgcolor="#FFFFFF" width="100%" border="0" align="center">
			<?php $i=0;
//--------------------
$consult = "SELECT proyecto.id as id_detalle, proyecto.id_proyecto, bn_dependencias.division, bn_dependencias.id FROM bn_dependencias LEFT JOIN proyecto ON bn_dependencias.id = proyecto.id_direccion WHERE bn_dependencias.id<18 ORDER BY division;";// WHERE AND poa_proyecto_responsable.id_proyecto=6
$tablx = $_SESSION['conexionsql']->query($consult);
while ($registro_x = $tablx->fetch_object())
//-------------
	{ 
?>		
<tr id="fila<?php echo $registro_x->id; ?>">
<td><div align="center" ><input class="switch_new" <?php if ($registro_x->id_detalle>0) echo 'checked' ; ?>  value="<?php echo ($registro_x->id); ?>" onclick="marcar(this,'<?php echo $registro_x->id; ?>');guardar_responsables('<?php echo $id_poa; ?>','<?php echo $anno; ?>','<?php echo $id_proyecto; ?>',<?php echo $registro_x->id; ?>);" type="checkbox" id="c<?php echo ($registro_x->id); ?>" name="c<?php echo ($registro_x->id); ?>"><label for="c<?php echo ($registro_x->id); ?>" class="lbl_switch"></label></div>
<td ><?php echo ($registro_x->division); ?></td>
</tr>
<?php
	}
?>
</table>
		</div>
	<!-- Modal footer -->
<!--
<div class="modal-footer justify-content-center">
	
	<button type="button" id="boton" class="btn btn-outline-success waves-effect" onclick="guardar_responsables('<?php //echo $id_poa; ?>','<?php //echo $anno; ?>','<?php //echo $id_proyecto; ?>');" ><i class="fas fa-save prefix grey-text mr-1"></i> GUARDAR</button>
</div>
-->
</form>
<script language="JavaScript">
//--------------------------------
function guardar_responsables(id_poa, anno, id_proyecto, direccion)
	{
	var parametros = $("#form999").serialize(); 
	$.ajax({  
	type : 'POST',
	url  : 'poa/1j_guardar.php?direccion='+direccion+"&id_poa="+id_poa+"&anno="+anno+"&id_proyecto="+id_proyecto,
	dataType:"json",
	data:  parametros, 
	success:function(data) {  	
		if (data.tipo=="info")
			{	alertify.success(data.msg);	 } //cheques(id_poa, anno, id_proyecto);	//$('#modal_lg .close').click();
		else
			{	alertify.alert(data.msg);	}
		}  
		});
	}
</script>