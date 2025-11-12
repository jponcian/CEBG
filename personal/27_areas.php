<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');

if ($_SESSION['VERIFICADO'] != "SI") { 
header ("Location: ../validacion.php?opcion=val"); 
exit(); }

$acceso=109;
//------- VALIDACION ACCESO USUARIO
include_once "../validacion_usuario.php";
//-----------------------------------
?>
<form id="form1" name="form1" method="post" onsubmit="return evitar();" >
<!--<br>-->
<!--<div  class="text-right mb-3"><a href="" class="btn btn-outline-primary btn-rounded btn-sm font-weight-bold" onclick="agregar();" data-toggle="modal" data-target="#modal_normal" data-keyboard="false"><i class="fas fa-plus-circle" ></i> Agregar Area</a></div>-->
<!-- <br>-->
<!--<div class="TituloTablaP" height="41"  align="center">AREAS REGISTRADAS</div>-->
	<h1 align="center" ><span class="badge badge-primary">AREAS REGISTRADAS</span></h1>
	 <input placeholder="Escriba aqui la informacion a buscar..." name="obuscar" id="obuscar" type="text" size="100" class="form-control" />
<div id="div2"></div>
</form>
<script language="JavaScript">
buscar();
//----------------
function cheques(id)
	{
	$('#modal_n').html('<div align="center"><div class="spinner-border" role="status"></div><br><strong>Un momento, por favor...</strong></div>');
	$('#modal_n').load('personal/27e_modal.php?id='+id);
	}
//----------------
function buscar()
	{
	$('#div2').html('<div align="center"><div class="spinner-border" role="status"></div><br><strong>Un momento, por favor...</strong></div>');
	$('#div2').load('personal/27a_tabla.php');
	}
</script>