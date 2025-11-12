<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');

if ($_SESSION['VERIFICADO'] != "SI") { 
header ("Location: ../validacion.php?opcion=val"); 
exit(); }

$acceso=113;
if ($_GET['id']>0)	{$id = $_GET['id'];} else {$id = 0;} 
//------- VALIDACION ACCESO USUARIO
include_once "../validacion_usuario.php";
//-----------------------------------
$consultx = "SELECT * FROM arc_prestamos WHERE id = $id;";  //echo $consultx;
$tablx = $_SESSION['conexionsql']->query($consultx);
if ($tablx->num_rows>0)	
	{
	$registro = $tablx->fetch_object();
	//--------
	$id = $registro->id;
	$grupo = $registro->grupo;
	$numero = $registro->numero;
	$descripcion = $registro->descripcion;
	}
?>
<form id="form999" name="form999" method="post" >
			<!-- Modal Header -->
<div class="modal-header bg-fondo text-center">
	<h4 align="center" style="background-color:#0275d8; color:#FFFFFF" class="modal-title w-100 font-weight-bold py-2">Expediente(s) 
	  <button type="button" class="close" data-dismiss="modal">&times;</button></h4>
    <input type="hidden" id="oid" name="oid" value="<?php echo $id; ?>"/>
</div>
<br>
<!-- Modal body -->
		<div class="p-1">
			
			<div class="row">
				<div class="form-group col-sm-12">
					<div class="input-group">
						<div class="input-group-text" align="center">Descripcion</div>
						<textarea disabled id="txt_descripcion" name="txt_descripcion" placeholder="Descripcion del Contenido" class="form-control" rows="1" ><?php echo $descripcion; ?></textarea>
					</div>
				</div>
			</div>
			
			<div class="row">
				<div class="form-group col-sm-12">
					<div class="input-group">
<!--						<div class="input-group-text" align="center">Detalle</div>-->
						<textarea id="txt_detalle" name="txt_detalle" placeholder="Observaciones" class="form-control" rows="3" ><?php //echo $descripcion; ?></textarea>
					</div>
				</div>
			</div>

		</div>
<!-- Modal footer -->
<div class="modal-footer justify-content-center">
	<button type="button" id="boton" class="btn btn-outline-success waves-effect" onclick="devolver()" ><i class="fas fa-save prefix grey-text mr-1"></i> Devolver</button>
</div>
</div>
</div>
</form>
<script language="JavaScript">
// PARA EL SELECT2
$(document).ready(function() {
    $('.select2').select2();
	//----------------
	$('#txt_hasta').datetimepicker({timepicker: true, datepicker:true, format:'d/m/Y H:i', step:60, yearStart:<?php echo date('Y') ; ?>, yearEnd: <?php echo date('Y')+1; ?>, theme:'dark', disabledWeekDays: [0,6], allowTimes:['07:00','08:00','09:00','10:00','11:00','12:00','13:00','14:00','15:00','16:00','17:00','18:00','19:00','20:00']});//--------------------------------
	setTimeout(function()	{
//			$('#txt_grupo').focus();
			},500)	
});
//--------------------------------
//$("#txt_valor").on({
//    "focus": function (event) {
//        $(event.target).select();
//    },
//    "keyup": function (event) {
//        $(event.target).val(function (index, value ) {
//            return value.replace(/\D/g, "")
//                        .replace(/([0-9])([0-9]{2})$/, '$1,$2')
//                        .replace(/\B(?=(\d{3})+(?!\d)\.?)/g, ".");
//        });
//    }
//});
</script>