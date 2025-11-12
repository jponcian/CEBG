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
$consultx = "SELECT * FROM rac_visita WHERE cedula = ".decriptar($_GET['cedula']).";";  //echo $consultx;
$tablx = $_SESSION['conexionsql']->query($consultx);
$registro = $tablx->fetch_object();
if ($tablx->num_rows>0)	
{$visita = $registro->cedula.' - '.$registro->nombre;}
else {$visita = decriptar($_GET['id']).' - NO REGISTRADO';}
?>
<form id="form999" name="form999" method="post" >
			<!-- Modal Header -->
<div class="modal-header bg-fondo text-center">
	<h4 align="center" style="background-color:#0275d8; color:#FFFFFF" class="modal-title w-100 font-weight-bold py-2">Cerrar Ticket de Atención
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

<table class="table table-striped table-hover" bgcolor="#FFFFFF" width="100%" border="0" align="center">
			<?php $i=0;
//--------------------
$consult = "SELECT * FROM a_atencion_dacs ORDER BY descripcion;";// WHERE id_direccion='$desde'
$tablx = $_SESSION['conexionsql']->query($consult);
while ($registro_x = $tablx->fetch_object())
//-------------
	{ 
?>		
<tr id="fila<?php echo $registro_x->id; ?>">
<td><div class="form-check form-switch"><div align="center" ><input value="si" onclick="marcar(this,'<?php echo $registro_x->id; ?>');" class="form-check-input" type="checkbox" id="c<?php echo ($registro_x->id); ?>" name="c<?php echo ($registro_x->id); ?>"></div></div>
<td ><label class="form-check-label" for="c<?php echo ($registro_x->id); ?>"><?php echo ($registro_x->descripcion); ?></label></td>
</tr>
<?php
	}
?>
			
</table>
		</div>
			
	<!-- Modal footer -->
<div class="modal-footer justify-content-center">
	<button type="button" id="boton" class="btn btn-outline-success waves-effect" onclick="cerrar_ticket('<?php echo ($_GET['id']); ?>','<?php echo ($_GET['cedula']); ?>');" ><i class="fa-solid fa-person-arrow-up-from-line prefix grey-text mr-1"></i> CERRAR TICKET</button>
</div>
</form>
<script language="JavaScript">
</script>