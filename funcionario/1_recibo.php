<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');

if ($_SESSION['VERIFICADO'] != "SI") { 
header ("Location: validacion.php?opcion=val"); 
exit(); }
$nomina = nomina($_SESSION['CEDULA_USUARIO']);
?>
<form id="form999" name="form999" method="post" >
			<!-- Modal Header -->
<div class="modal-header bg-fondo text-center">
	<h4 align="center" style="background-color:#0275d8; color:#FFFFFF" class="modal-title w-100 font-weight-bold py-2">Seleccione la Quincena 
	  <button type="button" class="close" data-dismiss="modal">&times;</button></h4>
</div>
<!-- Modal body -->
		<div class="p-1">
			
			<div class="row">		
												
				<div class="form-group col-sm-12">
					<div class="input-group">
						<div class="input-group-text"><i class="fa-regular fa-calendar-check"></i></div>
						<select class="select2" id="operiodo" name="operiodo" style="width: 300px">
					<option value="0">Seleccione</option>
					<?php
						$consultx = "SELECT desde, hasta FROM nomina_solicitudes WHERE (tipo_pago='001' or tipo_pago='002' or tipo_pago='003') AND estatus>=7 AND estatus<=10 GROUP BY hasta ORDER BY desde DESC, hasta DESC;"; //estatus AND 
						$tablx = $_SESSION['conexionsql']->query($consultx);
						while ($registro_x = $tablx->fetch_array())
							{
							echo '<option value="'.encriptar($registro_x['hasta']) .'">'.voltea_fecha($registro_x['desde']).' al '.voltea_fecha($registro_x['hasta']).'</option>';
							}
					?>
				  </select>
					</div>
				</div>
				
			</div>
			
		</div>
<!-- Modal footer -->
<div class="modal-footer justify-content-center">
	<button type="button" id="boton" class="btn btn-outline-success waves-effect" onclick="recibo('<?php echo encriptar($_SESSION['CEDULA_USUARIO']) ?>')" ><i class="fa-regular fa-file-pdf fa-2x"></i></button>
</div>
</form>
<script language="JavaScript">
// PARA EL SELECT2
$(document).ready(function() {
    $('.select2').select2({});
});
//---------------------------
function recibo(id)	{ window.open("personal/formatos/6_recibo.php?id="+id+"&t="+document.form999.operiodo.value,"_blank");	}
</script>