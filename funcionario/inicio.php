<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');

if ($_SESSION['VERIFICADO'] != "SI") {
	header("Location: ../validacion.php?opcion=val");
	exit();
}
//$_SESSION['id_ct'] = $_SESSION['CEDULA_USUARIO'];
?>
<div class="row">
	<div class="col-12 col-sm-6 col-md-3 mt-3">
		<div class="info-box" style="cursor: pointer" onClick="ver_recibo();" data-toggle="modal" data-target="#modal_normal">
			<span class="info-box-icon bg-info elevation-1" onMouseOver=""><i class="fa-solid fa-file-lines"></i></span>
			<div class="info-box-content">
				<span class="info-box-text"><strong>MI RECIBO DE PAGO</strong></span>
			</div>
			<!-- /.info-box-content -->
		</div>
		<!-- /.info-box -->
	</div>
	<!-- /.col -->
	<div class="col-12 col-sm-6 col-md-3 mt-3">
		<div class="info-box mb-3" style="cursor: pointer" onClick="trabajo();">
			<span class="info-box-icon bg-danger elevation-1"><i class="fa-solid fa-user-tie"></i></span>
			<div class="info-box-content">
				<span class="info-box-text "><strong>MI CONSTANCIA DE TRABAJO</strong></span>
			</div>
			<!-- /.info-box-content -->
		</div>
		<!-- /.info-box -->
	</div>
	<!-- /.col -->
	<div class="col-12 col-sm-6 col-md-3 mt-3">
		<div class="info-box mb-3" style="cursor: pointer" onClick="arc('<?php echo encriptar($_SESSION['CEDULA_USUARIO']) ?>');">
			<span class="info-box-icon bg-success elevation-1"><i class="fa-solid fa-money-bill-trend-up"></i></span>
			<div class="info-box-content">
				<span class="info-box-text"><strong>MI A.R.C.</strong></span>
				<!--    <span class="info-box-number">760</span>-->
			</div>
			<!-- /.info-box-content -->
		</div>
		<!-- /.info-box -->
	</div>
	<div class="col-12 col-sm-6 col-md-3 mt-3">
		<div class="info-box mb-3" style="cursor: pointer" onClick="eval();" data-toggle="modal" data-target="#modal_normal">
			<span class="info-box-icon bg-success elevation-1"><i class="fa-solid fa-arrows-to-eye"></i></span>
			<div class="info-box-content">
				<span class="info-box-text"><strong>MI EVALUACION</strong></span>
				<!--    <span class="info-box-number">760</span>-->
			</div>
			<!-- /.info-box-content -->
		</div>
		<!-- /.info-box -->
	</div>
</div>

<script language="JavaScript">
	//-----------------------
	function ver_recibo() {
		$('#modal_n').html('<div align="center"><div class="spinner-border" role="status"></div><br><strong>Un momento, por favor...</strong></div>');
		$('#modal_n').load('funcionario/1_recibo.php');
	}
	//---------------------------
	function trabajo() {
		window.open("personal/formatos/5_cons_trabajo.php", "_blank");
	}

	function arc(id) {
		window.open("personal/formatos/12_arc.php?id=" + id, "_blank");
	}

	function eval() {
		$('#modal_n').html('<div align="center"><div class="spinner-border" role="status"></div><br><strong>Un momento, por favor...</strong></div>');
		$('#modal_n').load('funcionario/2_hist_odi.php');
	}
</script>