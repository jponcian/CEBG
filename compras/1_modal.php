<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');

if ($_SESSION['VERIFICADO'] != "SI") {
	header("Location: ../validacion.php?opcion=val");
	exit();
}

$acceso = 19;
//------- VALIDACION ACCESO USUARIO
include_once "../validacion_usuario.php";
//-----------------------------------
?>
<form id="form888" name="form888" method="post">
	<!-- Modal Header -->
	<div class="modal-header bg-fondo text-center">
		<h4 align="center" style="background-color:#0275d8; color:#FFFFFF" class="modal-title w-100 font-weight-bold py-2">Presupuesto
			<button type="button" class="close" data-dismiss="modal">&times;</button>
		</h4>
	</div>
	<!-- Modal body -->
	<div class="p-1">

		<div class="row">
			<div class="form-group col-sm-12">
				<div class="input-group">
					<div class="input-group-text" align="center"><i class="fas fa-list-ol mr-2"></i>N° Presupuesto</div>
					<select class="form-control " id="txt_nuevo" name="txt_nuevo">
						<option value="0">Correlativo</option>
						<?php
						//	$consultx = "SELECT id, numero, fecha FROM orden_solicitudes WHERE tipo_orden=1 AND orden_solicitudes.estatus=99 AND (descripcion='A.P.A.R.T.A.D.A' or descripcion='R.E.V.E.R.S.A.D.A') ORDER BY numero DESC;";
						//	$tablx = $_SESSION['conexionsql']->query($consultx); //year(fecha)=year(curdate()) AND 
						//	while ($registro_x = $tablx->fetch_array())
						//		{
						//		echo '<option value="'.$registro_x['numero'].'*'.$registro_x['fecha'].'*'.$registro_x['id'].'">'.'Asignar el '.$registro_//x['numero'].' de fecha '.voltea_fecha($registro_x['fecha']).'</option>';
						//		}
						//		
						?>
					</select>
				</div>
			</div>
		</div>

		<br>

		<div align="center">
			<button type="button" id="boton" class="btn btn-outline-success waves-effect" onclick="generar_solicitud('<?php echo $_GET['id']; ?>');"><i class="fas fa-save prefix grey-text mr-1"></i> Guardar Cambios</button>
		</div>
	</div>

</form>
<script language="JavaScript">
	//--------------------------------
	$('#txt_nuevo').focus();
	//----------------- PARA VALIDAR
	function validar_num() {
		error = 0;
		//if(document.form999.txt_nuevo.value==0 || document.form999.txt_nuevo.value=="0")		
		//{	 document.form999.txt_nuevo.focus();	alertify.alert("Debe Seleccionar el Nuevo Numero de //Orden!");
		//error = 1;  }
		return error;
	}
</script>