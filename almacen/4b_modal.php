<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');

if ($_SESSION['VERIFICADO'] != "SI") { 
header ("Location: ../validacion.php?opcion=val"); 
exit(); }

$acceso=58;
if ($_GET['id']>0)	{$id = $_GET['id'];} else {$id = 0;} 
//------- VALIDACION ACCESO USUARIO
include_once "../validacion_usuario.php";
//-----------------------------------
$consultx = "SELECT * FROM bn_materiales WHERE id_bien = $id;";  //echo $consultx;
$tablx = $_SESSION['conexionsql']->query($consultx);
if ($tablx->num_rows>0)	
	{
	$registro = $tablx->fetch_object();
	//--------
	$existencia = formato_moneda($registro->inventario) ;
	$nombre = $registro->descripcion_bien ;
	$id_area = $registro->id_area ;
	$id_categoria = $registro->id_categoria ;
	$unidad = $registro->unidad ;
	$suministro = $registro->bien ;
	$valor = formato_moneda($registro->valor) ;
	}
?>
<form id="form999" name="form999" method="post" >
			<!-- Modal Header -->
<div class="modal-header bg-fondo text-center">
	<h4 align="center" style="background-color:#0275d8; color:#FFFFFF" class="modal-title w-100 font-weight-bold py-2">Datos B&aacute;sicos 
	  <button type="button" class="close" data-dismiss="modal">&times;</button></h4>
    <input type="hidden" id="oid" name="oid" value="<?php echo $id; ?>"/>
</div>
<!-- Modal body -->
		<div class="p-1">
	
			<div class="row">				
				<div class="form-group col-sm-3">
						<select class="select2" style="font-size: 14px" name="txt_material" id="txt_material" onchange="">
					<?php
					//--------------------
					echo '<option ';
					echo ' value="0"';
					if ($suministro=='0') {echo ' selected="selected" ';}
					echo '>Suministro</option>';
					//--------------------
					echo '<option ';
					echo ' value="1"';
					if ($suministro=='1') {echo ' selected="selected" ';}
					echo '>Material</option>';
					//--------------------
					?>

					</select>
				</div>
				<div class="form-group col-sm-9">
					<input id="txt_bien" onFocus="this.select()"  onkeyup="saltar(event,'txt_existencia')" placeholder="Material o Suministro" name="txt_bien" class="form-control" type="text" style="text-align:left" value="<?php echo $nombre; ?>"/>
				</div>
			</div>

<!--
<div class="row">
							
				<div class="form-group col-sm-12">
					<div class="input-group-text"><h4><span class="badge badge-warning">Categoria</span></h4> =>
					<select class="select2" style="width: 600px;text-align:right" name="txt_categoria" id="txt_categoria" onchange="" >
  <?php
//	$consultx = "SELECT * FROM bn_categorias ORDER BY codigo"; 
//	$tablx = $_SESSION['conexionsql']->query($consultx);
//	while ($registro_x = $tablx->fetch_array())
//		{
//		echo '<option value='.$registro_x['id_categoria'];
//		if ($id_categoria==$registro_x['id_categoria']) {echo ' selected="selected" ';}
//		echo '>'.$registro_x['codigo'].' - '.$registro_x['descripcion'].'</option>';
//		}
?></select>
					</div>
				</div>
		</div>
-->
		
			<div class="row">

				<div class="form-group col-sm-4">
					<div class="input-group-text"><h4><span class="badge badge-warning">Medida</span></h4> => 
						<select class="custom-select" style="font-size: 14px" name="txt_unidad" id="txt_unidad" onchange="">
						<?php
						//--------------------
						echo '<option ';
						echo ' value="UNIDAD"';
						if ($unidad=='UNIDAD') {echo ' selected="selected" ';}
						echo '>UNIDAD</option>';
						//--------------------
						echo '<option ';
						echo ' value="CAJA"';
						if ($unidad=='CAJA') {echo ' selected="selected" ';}
						echo '>CAJA</option>';
						//--------------------
						echo '<option ';
						echo ' value="KGS"';
						if ($unidad=='KILO') {echo ' selected="selected" ';}
						echo '>KILO</option>';
						//--------------------
						echo '<option ';
						echo ' value="LTS"';
						if ($unidad=='LITRO') {echo ' selected="selected" ';}
						echo '>LITRO</option>';
						//--------------------
						?>

						</select>
					</div>
				</div>
				
				<div class="form-group col-sm-4">
					<div class="input-group-text"><h4><span class="badge badge-warning">Existencia</span></h4> => 
					<input id="txt_existencia" onFocus="this.select()" placeholder="Actual" name="txt_existencia" class="form-control" onkeyup="saltar(event,'txt_valor')"   type="text" maxlength="10" style="text-align:center"  value="<?php echo $existencia; ?>"/>
					</div>
				</div>

<!--
				<div class="form-group col-sm-4">
					<div class="input-group-text"><h4><span class="badge badge-warning">Valor</span></h4> => 
					<input id="txt_valor" onFocus="this.select()" placeholder="Valor Bs" name="txt_valor" class="form-control"   type="text" maxlength="10" style="text-align:right"  value="<?php //echo $valor; ?>"/>
					</div>
				</div>
-->

			</div>
			
			
			
		</div>
<!-- Modal footer -->
<div class="modal-footer justify-content-center">
	<button type="button" id="boton" class="btn btn-outline-success waves-effect" onclick="guardar()" ><i class="fas fa-save prefix grey-text mr-1"></i> Guardar Cambios</button>
</div>
</div>
</div>
</form>
<script language="JavaScript">
//--------------------------------
$(document).ready(function() {
    $('.select2').select2();
});
//--------------------------------
setTimeout(function()	{
		$('#txt_numero').focus();
		},500)	
//--------------------------------
//$("#txt_valor").on({
//    "focus": function (event) {
//        $(event.target).select();
//    },
//    "keyup": function (event) {
//        $(event.target).val(function (index, value ) {
//            return value.replace(/\D/g, "")
//                        .replace(/([0-9])([0-9]{2})$/, '$1,$2')
//                        .replace(/\B(?=(\d{3})+(?!\d)\.?)/g, ".");
//        });
//    }
//});
</script>