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
$nomina = $_GET['nomina']; 
$codigo = $_GET['id']; 
?>
<form id="form999" name="form999" method="post" >
			<!-- Modal Header -->
<div class="modal-header bg-fondo text-center">
	<h4 align="center" style="background-color:#0275d8; color:#FFFFFF" class="modal-title w-100 font-weight-bold py-2">Registrar Excepciones para la Nomina "<?php echo $nomina; ?> "
	  <button type="button" class="close" data-dismiss="modal">&times;</button></h4>
    <input type="hidden" id="oid" name="oid" value="0"/>
	<input type="hidden" id="oidP" name="oidP" value="0"/>
</div>
<!-- Modal body -->
		<div class="p-1">
			
<div class="row">
	<div class="form-group col-sm-6 ml-3">
		<div class="input-group">
		<select class="select2" style="width: 600px" style="font-size: 14px" name="txt_cargo" id="txt_cargo" onchange="">
		<option value="0"> -SELECCIONE- </option>
		<?php
		//--------------------
		$consultx = "SELECT left(cargo,3) as cargo_corto, cargo FROM a_cargo GROUP BY left(cargo,3) ORDER BY cargo;"; 
		$tablx = $_SESSION['conexionsql']->query($consultx);
		while ($registro_x = $tablx->fetch_object())
		//-------------
		{
		$explode = explode(' ',$registro_x->cargo);
		echo '<option ';
		echo ' value="';
		echo trim(($registro_x->cargo_corto));
		echo '">';
		echo ($registro_x->cargo_corto).' ('.($explode[0]).')';
		echo '</option>';
		}
		?>
		</select>
		</div>
	</div>
	<div class="form-group col-sm-3">
		<input id="txt_original" placeholder="Monto Ayuda" maxlength="30" name="txt_original" class="form-control" type="text" style="text-align:right"/>
	</div>
</div>
			
		</div>
<!-- Modal footer -->
<div class="modal-footer justify-content-center">
	<button type="button" id="boton" class="btn btn-outline-success waves-effect" onclick="guardar2('<?php echo $codigo; ?>','<?php echo $nomina; ?>')" ><i class="fas fa-save prefix grey-text mr-1"></i> Guardar</button>
</div>
</div>
</div>
<div id="div3"></div>
</form>
<script language="JavaScript">
listar_partidas('<?php echo $codigo; ?>','<?php echo $nomina; ?>');
//----------------
$(document).ready(function() {
    $('.select2').select2();
});
//----------------
function guardar3(e,id)
{
	// Obtenemos la tecla pulsada
	(e.keyCode)?k=e.keyCode:k=e.which;
	// Si la tecla pulsada es enter (codigo ascii 13)
	if(k==13)
		{
		guardar2(id);
		}
}
//----------------
function guardar2(id, nomina)
 {
	var parametros = $("#form999").serialize(); 
	$.ajax({  
		type : 'POST',
		url  : 'personal/7g_guardar.php?id='+id+ "&nomina=" + nomina,
		dataType:"json",
		data:  parametros, 
		success:function(data) {  	
			if (data.tipo=="info")
				{	alertify.success(data.msg);	
//				 	document.form999.txt_original.value='0';
					listar_partidas(id, nomina);
				}
			else
				{	alertify.success(data.msg);	
//				 	document.form999.txt_original.value='0';
					listar_partidas(id, nomina);
				}
			}  
		});
 }
//----------------
function listar_partidas(id, anno)
	{
	$('#div3').html('<div align="center"><div class="spinner-border" role="status"></div><br><strong>Un momento, por favor...</strong></div>');
	$('#div3').load('personal/7f_tabla.php?id='+id+ "&anno=" + anno);
	}
//----------------
function editar(id, anno)
 {
	var parametros = "id=" + id+ "&anno=" + anno;
	$.ajax({
	url: "personal/7g_buscar.php",
	type: "POST",
	dataType:"json",
	data: parametros,
	success: function(data) {
	if (data.tipo=="info")
		{	
			document.form999.oidP.value=id;
			document.form999.txt_partida.value=data.codigo;
			document.form999.txt_descripcion.value=data.descripcion;
			document.form999.txt_original.value=data.original;
			document.form999.txt_partida.focus();
		}
	else
		{	alertify.alert(data.msg);	}
	}
	});
 }
//--------------------------------
$("#txt_original").on({
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