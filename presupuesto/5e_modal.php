<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');

if ($_SESSION['VERIFICADO'] != "SI") { 
header ("Location: ../validacion.php?opcion=val"); 
exit(); }

$acceso=69;
//------- VALIDACION ACCESO USUARIO
include_once "../validacion_usuario.php";
//-----------------------------------
//$consultx = "SELECT * FROM rac WHERE rac = ".$_GET['id'].";";  //echo $consultx;
//$tablx = $_SESSION['conexionsql']->query($consultx);
//$registro = $tablx->fetch_object();
$anno = $_GET['anno']; 
$categoria = $_GET['id']; 
?>
<form id="form999" name="form999" method="post" >
			<!-- Modal Header -->
<div class="modal-header bg-fondo text-center">
	<h4 align="center" style="background-color:#0275d8; color:#FFFFFF" class="modal-title w-100 font-weight-bold py-2">Registrar Partida para la Actividad <?php echo $categoria; ?> 
	  <button type="button" class="close" data-dismiss="modal">&times;</button></h4>
    <input type="hidden" id="oid" name="oid" value="0"/>
	<input type="hidden" id="oidP" name="oidP" value="0"/>
</div>
<!-- Modal body -->
		<div class="p-1">
			
<div class="row">
	<div class="form-group col-sm-3">
		<input id="txt_partida" onkeyup="saltar(event,'txt_descripcion')" placeholder="Partida" onkeypress="return SoloNumero(event,this);" maxlength="12" onchange="partida(this.value);" name="txt_partida" class="form-control" type="text" style="text-align:center"/>
	</div>
	<div class="form-group col-sm-6">
		<input id="txt_descripcion" onkeyup="saltar(event,'txt_original')" placeholder="Descripcion" maxlength="500" name="txt_descripcion" class="form-control" type="text" style="text-align:left"/>
	</div>
	<div class="form-group col-sm-3">
		<input id="txt_original" placeholder="Monto Original" onkeyup="guardar3(event,'<?php echo $categoria; ?>','<?php echo $anno; ?>')" maxlength="30" name="txt_original" class="form-control" type="text" style="text-align:right"/>
	</div>
</div>
			
		</div>
<!-- Modal footer -->
<div class="modal-footer justify-content-center">
	<button type="button" id="boton" class="btn btn-outline-success waves-effect" onclick="guardar2('<?php echo $categoria; ?>','<?php echo $anno; ?>')" ><i class="fas fa-save prefix grey-text mr-1"></i> Guardar</button>
</div>
</div>
</div>
<div id="div3"></div>
</form>
<script language="JavaScript">
listar_partidas('<?php echo $categoria; ?>','<?php echo $anno; ?>');
//----------------
function guardar3(e,id, anno)
{
	// Obtenemos la tecla pulsada
	(e.keyCode)?k=e.keyCode:k=e.which;
	// Si la tecla pulsada es enter (codigo ascii 13)
	if(k==13)
		{
		guardar2(id, anno);
		}
}
//----------------
function guardar2(id, anno)
 {
	var parametros = $("#form999").serialize(); 
	$.ajax({  
		type : 'POST',
		url  : 'presupuesto/5g_guardar.php?id='+id+ "&anno=" + anno,
		dataType:"json",
		data:  parametros, 
		success:function(data) {  	
			if (data.tipo=="info")
				{	alertify.success(data.msg);	
					document.form999.txt_partida.value='';
				 	document.form999.txt_descripcion.value='';
				 	document.form999.txt_original.value='0';
					document.form999.txt_partida.focus();
					listar_partidas(id, anno);
				}
			else
				{	alertify.success(data.msg);	
					document.form999.txt_partida.value='';
				 	document.form999.txt_descripcion.value='';
				 	document.form999.txt_original.value='0';
					document.form999.txt_partida.focus();
					listar_partidas(id, anno);}
			}  
		});
 }
//----------------
function listar_partidas(id, anno)
	{
	$('#div3').html('<div align="center"><div class="spinner-border" role="status"></div><br><strong>Un momento, por favor...</strong></div>');
	$('#div3').load('presupuesto/5f_tabla.php?id='+id+ "&anno=" + anno);
	}
//----------------
function editar(id, anno)
 {
	var parametros = "id=" + id+ "&anno=" + anno;
	$.ajax({
	url: "presupuesto/5g_buscar.php",
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