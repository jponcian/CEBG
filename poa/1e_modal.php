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
?>
<form id="form888" name="form888" method="post" >
			<!-- Modal Header -->
<div class="modal-header bg-fondo text-center">
	<h4 align="center" style="background-color:#0275d8; color:#FFFFFF" class="modal-title w-100 font-weight-bold py-2">Registrar Proyecto 
	  <button type="button" class="close" data-dismiss="modal">&times;</button></h4>
    <input type="hidden" id="oid" name="oid" value="0"/>
</div>
<!-- Modal body -->
		<div class="p-1">
			
<div class="row">
<!--
	<div class="form-group col-sm-6">
		<input id="txt_descripcion" onkeyup="saltar(event,'txt_original')" placeholder="Descripcion" maxlength="500" name="txt_descripcion" class="form-control" type="text" style="text-align:left"/>
	</div>
-->
<div class="form-group col-sm-6">
		<div class="input-group-text">Tipo: <select class="custom-select" style="font-size: 14px" name="txt_tipo" id="txt_tipo" onchange="">
		<option value="ESTRATÉGICO">ESTRATÉGICO</option>
		<option value="GESTIÓN">GESTIÓN</option>
		</select>
	</div>
</div>
	
	<div class="form-group col-sm-12">
<textarea id="txt_proyecto" name="txt_proyecto" onChange="validar_campo('txt_proyecto')" placeholder="Denominacion del Proyecto" class="form-control" rows="3" ></textarea></div>
	<div class="form-group col-sm-12">
<textarea id="txt_objetivo" name="txt_objetivo" onChange="validar_campo('txt_objetivo')" placeholder="Objetivo del Proyecto" class="form-control" rows="3" ></textarea></div>
	<div class="form-group col-sm-12">
<textarea id="txt_supuesto" name="txt_supuesto" onChange="validar_campo('txt_supuesto')" placeholder="Supuestos" class="form-control" rows="3" ></textarea></div>
</div>
			
		</div>
<!-- Modal footer -->
<div class="modal-footer justify-content-center">
	<button type="button" id="boton" class="btn btn-outline-success waves-effect" onclick="guardar2('<?php echo $_GET['id']; ?>','<?php echo $_GET['anno']; ?>')" ><i class="fas fa-save prefix grey-text mr-1"></i> Guardar</button>
</div>
</div>
</div>
<div id="div3a"></div>
</form>
<script language="JavaScript">
listar_partidas('<?php echo $_GET['id']; ?>', '<?php echo $_GET['anno']; ?>');
//----------------
function responsables(id_poa, anno, id)
	{
	$('#modal_lg').html('<div align="center"><div class="spinner-border" role="status"></div><br><strong>Un momento, por favor...</strong></div>');
	$('#modal_lg').load('poa/1i_responsables.php?id_poa='+id_poa+'&anno='+anno+'&id='+id);
	}
//----------------
function listar_partidas(id_poa, anno)
	{
	$('#div3a').html('<div align="center"><div class="spinner-border" role="status"></div><br><strong>Un momento, por favor...</strong></div>');
	$('#div3a').load('poa/1f_tabla.php?id_poa='+id_poa+'&anno='+anno);
	}
//----------------
function editar(id)
 {
	var parametros = "id=" + id;
	$.ajax({
	url: "poa/1g_buscar.php",
	type: "POST",
	dataType:"json",
	data: parametros,
	success: function(data) {
	if (data.tipo=="info")
		{	
			document.form888.oid.value=data.id;
			document.form888.txt_tipo.value=data.tipop;
			document.form888.txt_proyecto.value=data.descripcion;
			document.form888.txt_objetivo.value=data.objetivo;
			document.form888.txt_supuesto.value=data.supuestos;
			document.form888.txt_proyecto.focus();
		}
	else
		{	alertify.alert(data.msg);	}
	}
	});
 }
//----------------
function validar()
 {
	//---------------- validacion
	if (document.form888.txt_objetivo.value.length > 0 && document.form888.txt_supuesto.value.length > 0 && document.form888.txt_proyecto.value.length > 0 )
		{ error = 0; }
	else
		{ error = 1; validar_campo('txt_supuesto'); validar_campo('txt_objetivo'); validar_campo('txt_proyecto'); }

	//----------------
	return error;	
 }
//----------------
function guardar2(id, anno)
 {
if (validar()==0)
	{
	var parametros = $("#form888").serialize(); 
	$.ajax({  
		type : 'POST',
		url  : 'poa/1g_guardar.php?id='+id+'&anno='+anno,
		dataType:"json",
		data:  parametros, 
		success:function(data) {  	
			if (data.tipo=="info")
				{	alertify.success(data.msg);	
				 	document.form888.oid.value=0;
				 	document.form888.txt_proyecto.value='';
				 	document.form888.txt_objetivo.value='';
				 	document.form888.txt_supuesto.value='';
					document.form888.txt_proyecto.focus();
					listar_partidas(id, anno); validar();
				}
			else
				{	alertify.alert(data.msg);	
					document.form888.txt_proyecto.focus();
					listar_partidas(id, anno);}
			}  
		});
	 }
 }
//----------------
function eliminar2(id, id2, anno)
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
			var parametros = "id=" + id + "&id2=" + id2;
			$.ajax({
			url: "poa/1h_eliminar.php",
			type: "POST",
			dataType:"json",
			data: parametros,
			success: function(data) {
			if (data.tipo=="info")
				{	alertify.success(data.msg);	
					listar_partidas(id2, anno);
				}
			else
				{	alertify.alert(data.msg);	}
			}
			});
			//-----------------------
			}
		})
}
//----------------
</script>