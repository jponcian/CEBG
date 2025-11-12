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
$id_meta = $_GET['id'];
$anno = $_GET['anno'];
$id_proyecto = $_GET['id_proyecto'];

$valor = explode("/",$_GET['unidad']);
$_SESSION['id_responsable'] = $valor[0];; 
$unidad = $valor[1];; 
//-----------------------------------
$consulta = "DROP TABLE IF EXISTS frecuencia;"; 
$tablax = $_SESSION['conexionsql']->query($consulta);
$consulta = "CREATE TEMPORARY TABLE frecuencia (SELECT * FROM poa_metas_frecuencia WHERE poa_metas_frecuencia.id_meta = $id_meta);";
$tabla_x = $_SESSION['conexionsql']->query($consulta); //echo $consulta;
?>
<form id="form999" name="form999" method="post" >
			<!-- Modal Header -->
<div class="modal-header bg-fondo text-center">
	<button type="button" class="btn btn-outline-info btn-sm" onclick="modal_meta('<?php echo ($id_proyecto); ?>','<?php echo ($unidad); ?>');">Volver</button><h4 align="center" style="background-color:#0275d8; color:#FFFFFF" class="modal-title w-100 font-weight-bold py-2">Programación de la Meta
	  
		<button hidden="" type="button" class="close" data-dismiss="modal">&times;</button></h4>
    <input type="hidden" id="oid_poa" name="oid_poa" value="<?php echo $id_poa; ?>"/>
    <input type="hidden" id="oanno" name="oanno" value="<?php echo $anno; ?>"/>
    <input type="hidden" id="oid_meta" name="oid_meta" value="<?php echo $id_meta; ?>"/>
</div>
<!-- Modal body -->
		<div class="p-1">

<table class="table table-striped table-hover" width="100%" border="1" align="center">
			<?php $i=0;
//--------------------
$consult = "SELECT frecuencia.id as id_detalle, frecuencia.id_meta, frecuencia.detalle, frecuencia.cantidad, a_meses.mes, a_meses.nombre FROM a_meses LEFT JOIN frecuencia ON a_meses.nombre = frecuencia.mes ORDER BY a_meses.mes;";
$tablx = $_SESSION['conexionsql']->query($consult);
while ($registro_x = $tablx->fetch_object())
//-------------
	{ 
?>		
<tr id="fila<?php echo $registro_x->nombre; ?>">
<td width="5%" valign="middle" >
	<input class="switch_new" <?php if ($registro_x->id_detalle>0) echo 'checked' ; ?>  value="<?php echo ($registro_x->nombre); ?>" onclick="marcar(this,'<?php echo $registro_x->nombre; ?>');activa(this,'<?php echo $registro_x->nombre; ?>');" class="form-control" type="checkbox" id="c<?php echo ($registro_x->nombre); ?>" name="c<?php echo ($registro_x->nombre); ?>">
	<label for="c<?php echo ($registro_x->nombre); ?>" class="lbl_switch"></label></td>
<!--
<td valign="middle" > 
	<h6><strong><?php //echo ($registro_x->nombre); ?></strong></h6>
	</td>
-->
<td > 
	<textarea <?php if ($registro_x->id_detalle<=0) echo 'disabled' ; ?>  onchange="validar_campo('txt_detalle<?php echo ($registro_x->nombre); ?>');" id="txt_detalle<?php echo ($registro_x->nombre); ?>" name="txt_detalle<?php echo ($registro_x->nombre); ?>" placeholder="<?php echo ($registro_x->nombre); ?>" class="form-control" rows="2" ><?php echo ($registro_x->detalle); ?></textarea>
	</td>
<td valign="middle" width="18%" >
	<input <?php if ($registro_x->id_detalle<=0) echo 'disabled' ; ?>  onkeydown="puro_numero('txt_cantidad<?php echo ($registro_x->nombre); ?>');" placeholder="CANTIDAD" onchange="validar_campo_entero('txt_cantidad<?php echo ($registro_x->nombre); ?>');" id="txt_cantidad<?php echo ($registro_x->nombre); ?>" name="txt_cantidad<?php echo ($registro_x->nombre); ?>" class="form-control" type="text" style="text-align:center" value="<?php echo ($registro_x->cantidad); ?>" />
	</td>
</tr>
<?php
	}
?>
</table>
		</div>
		<!-- Modal footer -->
<div class="modal-footer justify-content-center">
	<button type="button" id="boton" class="btn btn-outline-success waves-effect" onclick="guardar_p('<?php echo encriptar($id_meta); ?>')" ><i class="fas fa-save prefix grey-text mr-1"></i> Guardar</button>
</div>
	
</form>
<script language="JavaScript">
//---- MARCAR OBJETOS
function activa(obj,x) { 
    if (obj.checked)
	{ 
		document.getElementById("txt_detalle"+x).disabled = false;
		document.getElementById("txt_detalle"+x).focus();
		document.getElementById("txt_cantidad"+x).disabled = false;
	}
    else
	{ 
        document.getElementById("txt_detalle"+x).disabled = true;
        document.getElementById("txt_cantidad"+x).disabled = true;
	}
}
	//--------------------------------
function guardar_p(id_meta)
	{
	var parametros = $("#form999").serialize(); 
	$.ajax({  
	type : 'POST',
	url  : 'poa/2j_guardar.php?id_meta='+id_meta,
	dataType:"json",
	data:  parametros, 
	success:function(data) {  	
		if (data.tipo=="info")
			{	alertify.success(data.msg);	modal_meta('<?php echo ($id_proyecto); ?>','<?php echo ($unidad); ?>'); }
		else
			{	alertify.alert(data.msg);	}
		}  
		});
	}
</script>