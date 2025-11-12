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
$id_proyecto = decriptar($_GET['id']); 
$unidad = ($_GET['unidad']); 
?>
<form id="form999" name="form999" method="post" >
			<!-- Modal Header -->
<div class="modal-header bg-fondo text-center">
	<h4 align="center" style="background-color:#0275d8; color:#FFFFFF" class="modal-title w-100 font-weight-bold py-2">Gestion de Actividades 
	  <button type="button" class="close" data-dismiss="modal">&times;</button></h4>
</div>
    <input type="hidden" id="oid" name="oid" value="<?php echo encriptar($id_proyecto); ?>"/>
<!-- Modal body -->
	
<br>
<div class="p-1">

<div class="row">

<div class="form-group col-sm-12">
	<div class="input-group">
		<div class="input-group-text">Unidad Ejecutora:</div>
		<select class="custom-select" style="font-size: 14px" name="txt_unidad" id="txt_unidad" onchange="listar_metas('<?php echo encriptar($id_proyecto); ?>', this.value);">
		<option value="0">--- Seleccione ---</option>
			<?php
			//--------------------
			$consultx = "SELECT poa_proyecto_responsable.id, poa_proyecto_responsable.id_direccion, bn_dependencias.division FROM	poa_proyecto_responsable, bn_dependencias WHERE poa_proyecto_responsable.id_direccion = bn_dependencias.id AND poa_proyecto_responsable.id_proyecto = '$id_proyecto' ORDER BY division;"; 
			$tablx = $_SESSION['conexionsql']->query($consultx);
			while ($registro_x = $tablx->fetch_object())
			//-------------
			{
			echo '<option ';
				if ($unidad == $registro_x->id_direccion) { echo 'selected'; $valor = $registro_x->id.'/'.$registro_x->id_direccion;}
			echo ' value="';
			echo $registro_x->id.'/'.$registro_x->id_direccion;
			echo '">';
			echo ($registro_x->division);
			echo '</option>';
			}
			?>
		</select>
	</div>
</div>
	
</div>

</div>
</div>
<div id="div33"></div>
</form>
<script language="JavaScript">
//listar_metas('<?php //echo $_GET['id']; ?>','<?php //echo $_GET['anno']; ?>');
//----------------
<?php if ($unidad > 0 ) { ?> listar_metas('<?php echo encriptar($id_proyecto); ?>','<?php echo $valor; ?>'); <?php } ?>
//----------------
function programacion(id, anno, unidad)
	{
	$('#modal_xl').html('<div align="center"><div class="spinner-border" role="status"></div><br><strong>Un momento, por favor...</strong></div>');
	$('#modal_xl').load('poa/3i_programacion.php?id='+id+'&anno='+anno+'&id_proyecto=<?php echo $_GET['id']; ?>'+'&unidad='+unidad);
	}
//----------------
function listar_metas(id, valor)
	{
	$('#div33').html('<div align="center"><div class="spinner-border" role="status"></div><br><strong>Un momento, por favor...</strong></div>');
	$('#div33').load('poa/3f_tabla.php?id='+id+'&unidad='+valor);
	}
</script>