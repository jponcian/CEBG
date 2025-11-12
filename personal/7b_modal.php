<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');

if ($_SESSION['VERIFICADO'] != "SI") { 
header ("Location: ../validacion.php?opcion=val"); 
exit(); }

$acceso=89;
//------- VALIDACION ACCESO USUARIO
include_once "../validacion_usuario.php";
//-----------------------------------
//$consultx = "SELECT * FROM rac WHERE rac = ".$_GET['id'].";";  //echo $consultx;
//$tablx = $_SESSION['conexionsql']->query($consultx);
//$registro = $tablx->fetch_object();
?>
<form id="form999" name="form999" method="post" >
			<!-- Modal Header -->
<div class="modal-header bg-fondo text-center">
	<h4 align="center" style="background-color:#0275d8; color:#FFFFFF" class="modal-title w-100 font-weight-bold py-2">Registrar Nomina<button type="button" class="close" data-dismiss="modal">&times;</button></h4>
    <input type="hidden" id="oid" name="oid" value="0"/>
</div>
<!-- Modal body -->
		<div class="p-1">
			<br>
<div class="row">
	<div class="form-group col-sm-6 ml-3">
		<div class="input-group">
		<select class="select2" style="width: 400px" style="font-size: 14px" name="txt_nomina" id="txt_nomina" onchange="">
		<option value="0"> -SELECCIONE- </option>
		<?php
		//--------------------
		$consultx = "SELECT * FROM a_nomina WHERE codigo<>'010' AND codigo<>'999' AND codigo NOT IN (SELECT codigo FROM a_bonos);"; 
		$tablx = $_SESSION['conexionsql']->query($consultx);
		while ($registro_x = $tablx->fetch_object())
		//-------------
		{
		echo '<option ';
		echo ' value="';
		echo $registro_x->codigo.'-'.$registro_x->nomina;
		echo '">';
		echo mayuscula($registro_x->nomina);
		echo '</option>';
		}
		?>
		</select>
		</div>
	</div>
	<div class="form-group col-sm-5">
		<input id="txt_monto" placeholder="Monto Ayuda" maxlength="30" name="txt_monto" class="form-control" type="text" style="text-align:right"/>
	</div>
</div>
			
		</div>
<!-- Modal footer -->
<div class="modal-footer justify-content-center">
	<button type="button" id="boton" class="btn btn-outline-success waves-effect" onclick="guardar()" ><i class="fas fa-save prefix grey-text mr-1"></i> Guardar</button>
</div>
</div>
</div>
<div id="div3"></div>
</form>
<script>
$(document).ready(function() {
    $('.select2').select2();
});
//--------------------------------
$("#txt_monto").on({
    "focus": function (event) {
        $(event.target).select();
    },
    "keyup": function (event) {
        $(event.target).val(function (index, value ) {
            return value.replace(/\D/g, "")
                        .replace(/([0-9])([0-9]{2})$/, '$1,$2')
                        .replace(/\B(?=(\d{3})+(?!\d)\.?)/g, ".");
        });
    }
});
</script>