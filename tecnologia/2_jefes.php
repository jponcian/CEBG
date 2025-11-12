<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');

if ($_SESSION['VERIFICADO'] != "SI") { 
header ("Location: ../validacion.php?opcion=val"); 
exit(); }

$acceso=93;
//------- VALIDACION ACCESO USUARIO
include_once "../validacion_usuario.php";
//-----------------------------------
?>
<form id="form1" name="form1" method="post" onsubmit="return evitar();" ><br>

<!-- <input placeholder="Escriba aqui la informacion a buscar..." name="obuscar" id="obuscar" type="text" size="100" class="form-control" onFocus="this.select()"/>-->

 <br>
<div id="div2"></div>
 <br>
</form>
<script language="JavaScript">
//---------------------
 buscar();
//---------------------
function buscar()
 	 {
	$('#div2').html('<div align="center"><div class="spinner-border" role="status"></div><br><strong>Un momento, por favor...</strong></div>');
	$('#div2').load('tecnologia/2a_tabla.php');
	}
//--------------------------------------------
function cambiarc(id)
	{
	$('#modal_n').html('<div align="center"><div class="spinner-border" role="status"></div><br><strong>Un momento, por favor...</strong></div>');
	$('#modal_n').load('tecnologia/2d_modal.php?id='+id);
	}
//--------------------------------------------
function cambiar(id)
	{
	$('#modal_n').html('<div align="center"><div class="spinner-border" role="status"></div><br><strong>Un momento, por favor...</strong></div>');
	$('#modal_n').load('tecnologia/2b_modal.php?id='+id);
	}
</script>