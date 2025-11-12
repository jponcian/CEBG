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
?>
<form id="form1" name="form1" method="post" onsubmit="return evitar();" >
<br>
<!--<div  class="text-right mb-3"><a href="" class="btn btn-outline-primary btn-rounded btn-sm font-weight-bold" onclick="agregar();" data-toggle="modal" data-target="#modal_normal" data-keyboard="false"><i class="fas fa-plus-circle" ></i> Agregar METAS</a></div>-->
<!-- <br>-->
<div class="TituloTablaP" height="41" colspan="10" align="center">Plan Operativo Anual => <select style="font-size: 14px" name="txt_anno" id="txt_anno" onChange="buscar();">
			<?php
			$i = date ('Y')+1;
			while ($i>=2022)
			//-------------
			{
			echo '<option ';
				if ($i == date ('Y')) { echo 'selected';}
			echo ' value="';
			echo $i;
			echo '">Ejercicio ';
			echo $i;
			echo '</option>';
			$i--;
			}
			?>
		</select></div>
<div id="div2"></div>
</form>
<script language="JavaScript">
//-------------
buscar();
//----------------
function eliminar(id)
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
			var parametros = "id=" + id;
			$.ajax({
			url: "poa/3c_eliminar.php",
			type: "POST",
			dataType:"json",
			data: parametros,
			success: function(data) {
			if (data.tipo=="info")
				{	alertify.success(data.msg);	
					buscar();
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
function guardar()
 {
	var parametros = $("#form999").serialize(); 
	$.ajax({  
		type : 'POST',
		url  : 'poa/3d_guardar.php',
		dataType:"json",
		data:  parametros, 
		success:function(data) {  	
			if (data.tipo=="info")
				{	alertify.success(data.msg);	
					$('#modal_largo .close').click(); 
					buscar();
				}
			else
				{	alertify.alert(data.msg);	}
			}  
		});
 }
//----------------
function modal_meta(id, unidad)
	{
	$('#modal_xl').html('<div align="center"><div class="spinner-border" role="status"></div><br><strong>Un momento, por favor...</strong></div>');
	$('#modal_xl').load('poa/3e_modal.php?id='+id+'&unidad='+unidad);
	}
//----------------
function agregar()
	{
	$('#modal_n').html('<div align="center"><div class="spinner-border" role="status"></div><br><strong>Un momento, por favor...</strong></div>');
	$('#modal_n').load('poa/3b_modal.php');
	}
//----------------
function buscar()
	{
	$('#div2').html('<div align="center"><div class="spinner-border" role="status"></div><br><strong>Un momento, por favor...</strong></div>');
	$('#div2').load('poa/3a_tabla.php?anno='+document.form1.txt_anno.value);
	}
</script>