<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');

if ($_SESSION['VERIFICADO'] != "SI") { 
header ("Location: ../validacion.php?opcion=val"); 
exit(); }
//$_SESSION['id_ct'] = $_SESSION['CEDULA_USUARIO'];
?>
  <div class="col-12 col-sm-6 col-md-3 mt-3">
	<div class="info-box" style="cursor: pointer" onClick="ver_poai();" data-toggle="modal" data-target="#modal_extra">
	  <span class="info-box-icon bg-danger elevation-1" onMouseOver=""><i class="fa-solid fa-users-gear fa-beat"></i></span>

	  <div class="info-box-content">
		<span class="info-box-text"><strong>GESTION POAI</strong></span>
	  </div>
	  <!-- /.info-box-content -->
	</div>
	<!-- /.info-box -->
  </div>
  <!-- /.col -->
  <!-- /.col -->

  <!-- fix for small devices only -->
  <div class="clearfix hidden-md-up"></div>

<script language="JavaScript">
//-----------------------
function ver_poai()
	{
	$('#modal_xl').html('<div align="center"><div class="spinner-border" role="status"></div><br><strong>Un momento, por favor...</strong></div>');
	$('#modal_xl').load('inicio/poa.php');
	}
//---------------------------
</script>
