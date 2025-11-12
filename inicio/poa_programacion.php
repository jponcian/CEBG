<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');

if ($_SESSION['VERIFICADO'] != "SI") { 
header ("Location: ../validacion.php?opcion=val"); 
exit(); }

$acceso=73;
//------- VALIDACION ACCESO USUARIO
include_once "../validacion_usuario.php";
//-----------------------------------
$id_meta = $_GET['id'];
$anno = $_GET['anno'];

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
	<button type="button" class="btn btn-outline-info btn-sm" onclick="ver_poai();">Volver</button><h4 align="center" style="background-color:#0275d8; color:#FFFFFF" class="modal-title w-100 font-weight-bold py-2">Balance de Gestion
	  
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
$consult = "SELECT frecuencia.id as id_detalle, frecuencia.id_meta, frecuencia.detalle, frecuencia.cantidad_gestion, frecuencia.cantidad, a_meses.mes, a_meses.nombre FROM a_meses , frecuencia WHERE a_meses.nombre = frecuencia.mes ORDER BY a_meses.mes;";
$tablx = $_SESSION['conexionsql']->query($consult);
while ($registro_x = $tablx->fetch_object())
//-------------
	{ 
?>		
<tr id="fila<?php echo $registro_x->nombre; ?>">
<td width="5%" valign="middle" <?php 
	if ($registro_x->cantidad <= $registro_x->cantidad_gestion) { echo 'bgcolor="#0AFF00"'; } 
	if ($registro_x->cantidad > $registro_x->cantidad_gestion and $registro_x->cantidad_gestion > 0) { echo 'bgcolor="#F8FF00"'; } 
	if ($registro_x->cantidad > 0 and $registro_x->cantidad_gestion == 0) { echo 'bgcolor=" #FF0000"'; } ?>>
	<input <?php //if ($registro_x->id_detalle>0) echo 'checked' ; ?>  value="<?php echo ($registro_x->nombre); ?>" onclick="marcar(this,'<?php echo $registro_x->nombre; ?>');activa(this,'<?php echo $registro_x->nombre; ?>');" class="form-control" type="checkbox" id="c<?php echo ($registro_x->nombre); ?>" name="c<?php echo ($registro_x->nombre); ?>">
	</td>
<td width="25%" > 
	<?php echo ($registro_x->detalle); ?>
	</td>
<td > 
	<textarea disabled id="txt_detalle<?php echo ($registro_x->nombre); ?>" onchange="validar_campo('txt_detalle<?php echo ($registro_x->nombre); ?>');" name="txt_detalle<?php echo ($registro_x->nombre); ?>" placeholder="GESTION <?php echo ($registro_x->nombre); ?>" class="form-control" rows="2" ><?php echo (''); ?></textarea>
	</td>
<td valign="middle" align="center" width="5%">
	<?php echo ($registro_x->cantidad); ?>
	</td>
<td valign="middle" width="10%" >
	<input disabled placeholder="CANTIDAD" id="txt_cantidad<?php echo ($registro_x->nombre); ?>" onchange="validar_campo_entero('txt_cantidad<?php echo ($registro_x->nombre); ?>');"  name="txt_cantidad<?php echo ($registro_x->nombre); ?>" class="form-control" type="text" style="text-align:center" onFocus="this.select()" value="<?php echo (''); ?>" />
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
<div id="div4"></div>
</form>
<script language="JavaScript">
listar_gestion('<?php echo encriptar($id_meta); ?>');
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
function listar_gestion(id)
	{
	$('#div4').html('<div align="center"><div class="spinner-border" role="status"></div><br><strong>Un momento, por favor...</strong></div>');
	$('#div4').load('poa/3g_tabla.php?id='+id);
	}
//----------------
function eliminarg(id, id_meta, mes, cantidad)
	{
	Swal.fire({
		title: 'Estas seguro de eliminar el Registro?',
		text: "Esta acción no se puede revertir!",
		icon: 'question',
		showCancelButton: true,
		confirmButtonColor: '#3085d6',
		cancelButtonColor: '#d33',
		confirmButtonText: 'Si, borrar!',
		cancelButtonText: 'Cancelar'
		}).then((result) => {
		if (result.isConfirmed) {
			//-----------------------
			var parametros = "id=" + id + "&id_meta=" + id_meta+ "&mes=" + mes+ "&cantidad=" + cantidad;
			$.ajax({
			url: "poa/3c_eliminar.php",
			type: "POST",
			dataType:"json",
			data: parametros,
			success: function(data) {
			if (data.tipo=="info")
				{	alertify.success(data.msg);	
					listar_gestion('<?php echo encriptar($id_meta); ?>');
				}
			else
				{	alertify.alert(data.msg);	}
			}
			});
			//-----------------------
			}
		})
}
//--------------------------------
function guardar_p(id_meta)
	{
	document.getElementById("boton").disabled = true;
	$('#boton').html('<div class="spinner-border text-primary" role="status"> <span class="sr-only">Guardando...</span></div> Guardando...');
	//-------------
	var parametros = $("#form999").serialize(); 
	$.ajax({  
	type : 'POST',
	url  : 'poa/3j_guardar.php?id_meta='+id_meta,
	dataType:"json",
	data:  parametros, 
	success:function(data) {  	
		if (data.tipo=="info")
			{	
			alertify.success(data.msg);	
			listar_gestion('<?php echo encriptar($id_meta); ?>'); 
			}
		else
			{	alertify.alert(data.msg);	}
		}  
		});
	$('#boton').html('<i class="fas fa-save prefix grey-text mr-1"></i> Guardar');
	document.getElementById("boton").disabled = false;
	}
</script>