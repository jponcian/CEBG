<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');

if ($_SESSION['VERIFICADO'] != "SI") { 
header ("Location: validacion.php?opcion=val"); 
exit(); }

$acceso=101;
//------- VALIDACION ACCESO USUARIO
include_once "../validacion_usuario.php";
//-----------------------------------
$cedula = decriptar($_GET['cedula']);
?>
<form id="form888" name="form888" method="post" >
			<!-- Modal Header -->
<div class="modal-header bg-fondo text-center">
	<h4 align="center" style="background-color:#0275d8; color:#FFFFFF" class="modal-title w-100 font-weight-bold py-2">Evaluación de los Objetivos de Desempeño<button type="button" class="close" data-dismiss="modal">&times;</button></h4>
</div>
<!-- Modal body -->
<div class="p-1">
			
<!--<br>-->
<table class="formateada table" border="1" align="center" width="100%">
<thead>
	<tr>
		<th bgcolor="#CCCCCC" align="center"><strong>Item</strong></th>
		<th bgcolor="#CCCCCC" align="center"><strong>Descripción</strong></th>
		<th bgcolor="#CCCCCC" align="center"><strong>Peso</strong></th>
<!--		<th bgcolor="#CCCCCC" align="center"><strong>0</strong></th>-->
		<th bgcolor="#CCCCCC" align="center"><strong>1</strong></th>
		<th bgcolor="#CCCCCC" align="center"><strong>2</strong></th>
		<th bgcolor="#CCCCCC" align="center"><strong>3</strong></th>
		<th bgcolor="#CCCCCC" align="center"><strong>4</strong></th>
		<th bgcolor="#CCCCCC" align="center"><strong>5</strong></th>
		<th bgcolor="#CCCCCC" align="center"><strong>Peso por Rango</strong></th>
	</tr>
</thead>
<tbody><?php 
$consultx = "SELECT eval_asignacion.id,	eval_asignacion.id_odi,	eval_odis.descripcion,	eval_odis.peso_o FROM eval_asignacion,	evaluaciones, eval_odis WHERE eval_asignacion.estatus=5 AND eval_asignacion.id_evaluacion = evaluaciones.id AND eval_asignacion.id_odi = eval_odis.id AND eval_asignacion.cedula = '$cedula';";
//echo $consultx;
$tablx = $_SESSION['conexionsql']->query($consultx);
if ($tablx->num_rows>0)
	{		}
else
	{	?><tr><td colspan="4"><div align="center" ><h4>No Existen Registros</h4></div></td></tr><?php }
//-------------
while ($registro = $tablx->fetch_object())
	{ 	
		$i++;
		$ii=1;
		?>
	<tr >
		<td style="vertical-align: middle"><strong><?php echo $i; ?></strong></td>
		<td style="vertical-align: middle"><strong><?php echo $registro->descripcion; ?></strong></td>
		<td style="vertical-align: middle"><strong><select onChange="recuenta_odis('<?php echo $registro->id; ?>');" name="txt_peso<?php echo $registro->id; ?>" id="txt_peso<?php echo $registro->id; ?>">
<!--					<option value="0">0</option>-->
			<?php while ($ii<=50) { ?>
				<option <?php if ($registro->peso_o==$ii) { echo "selected"; } ?> value="<?php echo $ii ?>"><?php echo $ii ?></option>
			<?php $ii++; } ?>

					</select></td>
<!--//		<td align="center" style="vertical-align: middle"><input checked type="radio" name="txt_odi<?php //echo $registro->id; ?>" id="txt_odi<?php //echo $registro->id; ?>" value="0" onclick="recuenta_odis('<?php //echo $registro->id; ?>');" ></td>-->
		<td align="center" style="vertical-align: middle" >  <input checked type="radio" name="txt_odi<?php echo $registro->id; ?>" value="1" onclick="recuenta_odis('<?php echo $registro->id; ?>');" ></td>
		<td align="center" style="vertical-align: middle" ><input type="radio" name="txt_odi<?php echo $registro->id; ?>"  value="2" onclick="recuenta_odis('<?php echo $registro->id; ?>');" ></td>
		<td align="center" style="vertical-align: middle" ><input type="radio" name="txt_odi<?php echo $registro->id; ?>"  value="3" onclick="recuenta_odis('<?php echo $registro->id; ?>');" ></td>
		<td align="center" style="vertical-align: middle" ><input type="radio" name="txt_odi<?php echo $registro->id; ?>"  value="4" onclick="recuenta_odis('<?php echo $registro->id; ?>');" ></td>
		<td align="center" style="vertical-align: middle" ><input type="radio" name="txt_odi<?php echo $registro->id; ?>"  value="5" onclick="recuenta_odis('<?php echo $registro->id; ?>');" ></td>
		<td align="center" style="vertical-align: middle"><strong><div id='peso<?php echo $registro->id; ?>'>1</div></strong><input type="hidden" id="txt_tpeso<?php echo $registro->id; ?>" name="txt_tpeso<?php echo $registro->id; ?>" value="1"></td>

 <?php 
	 }
 ?>
		<tr>
			<td align="right" style="vertical-align: middle" colspan="8"><strong><h5>Total</h5></strong></td>
			<td align="center" style="vertical-align: middle"><h5><strong><div id="suma"><?php echo $i; ?></div></strong></h5><input type="hidden" id="txt_suma" value="<?php echo $i; ?>"></td>
		</tr>
 </tbody>  
</table>

<div class="modal-header bg-fondo text-center">
	<h4 align="center" style="background-color:#0275d8; color:#FFFFFF" class="modal-title w-100 font-weight-bold py-2">Evaluación de las Competencias
	  </h4>
</div>

<table class="formateada table" border="1" align="center" width="100%">
<thead>
	<tr>
		<th bgcolor="#CCCCCC" align="center"><strong>Item</strong></th>
		<th bgcolor="#CCCCCC" align="center"><strong>Descripción</strong></th>
		<th bgcolor="#CCCCCC" align="center"><strong>Evaluar</strong></th>
		<th bgcolor="#CCCCCC" align="center"><strong>Peso</strong></th>
<!--		<th bgcolor="#CCCCCC" align="center"><strong>0</strong></th>-->
		<th bgcolor="#CCCCCC" align="center"><strong>1</strong></th>
		<th bgcolor="#CCCCCC" align="center"><strong>2</strong></th>
		<th bgcolor="#CCCCCC" align="center"><strong>3</strong></th>
		<th bgcolor="#CCCCCC" align="center"><strong>4</strong></th>
		<th bgcolor="#CCCCCC" align="center"><strong>5</strong></th>
		<th bgcolor="#CCCCCC" align="center"><strong>Peso por Rango</strong></th>
	</tr>
</thead>
<tbody><?php 
$i=0;
$consultx = "SELECT * FROM eval_competencias WHERE estatus = 0;";//$filtrar.$_GET['valor'].";"; 
$tablx = $_SESSION['conexionsql']->query($consultx);
if ($tablx->num_rows>0)
	{		}
else
	{	?><tr><td colspan="4"><div align="center" ><h4>No Existen Registros</h4></div></td></tr><?php }
//-------------
while ($registro = $tablx->fetch_object())
	{ 	
		$i++;
		?>
	<tr>
		<td style="vertical-align: middle"><strong><?php echo $i; ?></strong></td>
		<td style="vertical-align: middle"><strong><?php echo $registro->descripcion; ?></strong></td>
		<td style="vertical-align: middle" align="right">

	<input id="txt_comp_<?php echo $registro->id; ?>" name="txt_comp_<?php echo $registro->id; ?>" type="checkbox" checked class="switch_new" value="1" onChange="recuenta_comp('<?php echo $registro->id; ?>');" />
	<label for="txt_comp_<?php echo $registro->id; ?>" class="lbl_switch"></label>	</td>
		<td style="vertical-align: middle"><strong><select onChange="recuenta_comp('<?php echo $registro->id; ?>');" name="txt_pesoa2<?php echo $registro->id; ?>" id="txt_pesoa2<?php echo $registro->id; ?>">
<!--					<option value="0">0</option>-->
					<option value="1">1</option>
					<option value="2">2</option>
					<option value="3">3</option>
					<option value="4">4</option>
					<option value="5">5</option>
					<option value="6">6</option>
					<option value="7">7</option>
					<option value="8">8</option>
					<option value="9">9</option>
					<option value="10">10</option>
					</select>
			</td>
<!--//		<td align="center" style="vertical-align: middle"><input checked type="radio" name="txt_comp<?php //echo $registro->id; ?>" id="txt_comp<?php //echo $registro->id; ?>" value="0" onclick="recuenta_comp('<?php //echo $registro->id; ?>');" ></td>-->
		<td align="center" style="vertical-align: middle" ><input type="radio" name="txt_comp<?php echo $registro->id; ?>"  value="1" onclick="recuenta_comp('<?php echo $registro->id; ?>');" checked ></td>
		<td align="center" style="vertical-align: middle" ><input type="radio" name="txt_comp<?php echo $registro->id; ?>"  value="2" onclick="recuenta_comp('<?php echo $registro->id; ?>');" ></td>
		<td align="center" style="vertical-align: middle" ><input type="radio" name="txt_comp<?php echo $registro->id; ?>"  value="3"  onclick="recuenta_comp('<?php echo $registro->id; ?>');" ></td>
		<td align="center" style="vertical-align: middle" ><input type="radio" name="txt_comp<?php echo $registro->id; ?>"  value="4" onclick="recuenta_comp('<?php echo $registro->id; ?>');" ></td>
		<td align="center" style="vertical-align: middle" ><input type="radio" name="txt_comp<?php echo $registro->id; ?>"  value="5"  onclick="recuenta_comp('<?php echo $registro->id; ?>');" ></td>
		<td align="center" style="vertical-align: middle"><strong><div id='pesow2<?php echo $registro->id; ?>'>1</div></strong><input type="hidden" class="sumarC" id="txt_tpeso2<?php echo $registro->id; ?>" name="txt_tpeso2<?php echo $registro->id; ?>" value="1"></td>

 <?php 
	 }
 ?>
		<tr>
			<td align="right" style="vertical-align: middle" colspan="9"><strong><h5>Total</h5></strong></td>
			<td align="center" style="vertical-align: middle"><h5><strong><div id="suma2"><?php echo $i; ?></div></strong></h5><input type="hidden" id="txt_suma2" value="<?php echo $i; ?>"></td>
		</tr>
		<tr>
			<td align="right" style="vertical-align: middle" colspan="9"><strong><h4>Calificación Total</h4></strong></td>
			<td align="center" style="vertical-align: middle"><h4><strong><div id="sumaT">0</div></strong></h4><input type="hidden" id="txt_sumaT" value="0"></td>
		</tr>
		<tr>
			<td align="right" style="vertical-align: middle" colspan="3"><strong><h4>Rango de Actuación </h4></strong></td>
			<td colspan="7" align="center" style="vertical-align: middle"><h4><strong><div id="rango">Sin valorar</div></strong></h4></td>
		</tr>
 </tbody>  
</table>

</div>
<!-- Modal footer -->
<div class="modal-footer justify-content-center">
<button type="button" id="boton" class="btn btn-outline-success waves-effect" onclick="guardar_evaluacion('<?php echo $_GET['cedula']; ?>')" ><i class="fas fa-save prefix grey-text mr-1"></i>Guardar</button>
</div>
</form>
<script language="JavaScript">
//$(document).ready(function() {
//    $('.select2').select2();
//});
//--------------------------------
function guardar_evaluacion(cedula)
 {
	Swal.fire({
		title: 'Está seguro de Registrar la Evaluación?',
		text: "Esta acción no se puede revertir!",
		icon: 'question',
		showCancelButton: true,
		confirmButtonColor: '#3085d6',
		cancelButtonColor: '#d33',
		confirmButtonText: 'Si, Completar!',
		cancelButtonText: 'Cancelar'
		}).then((result) => {
		if (result.isConfirmed) {
			//-----------------------
			$('#boton').hide();
			var parametros = $("#form888").serialize(); 
			$.ajax({  
				type : 'POST',
				url  : 'personal/15c_guardar.php?cedula='+cedula,
				dataType:"json",
				data:  parametros, 
				success:function(data) {  	
					if (data.tipo=="info")
						{	
							Swal.fire({
//							  title: data.msg,
							  icon: 'info',				
							  text: data.msg,				
							  timer: 5500,				
					//		  timerProgressBar: true,				
							  showDenyButton: false,
							  showCancelButton: false
							})
							busca_empleados();
							$('#modal_extra .close').click(); 
						}
					else
						{	alertify.alert(data.msg);	}
					}  
				});
			//-----------------------
		}
		})	
 }
//--------------------------------
function recuenta_odis(id)
 {
	 $('#txt_suma').val($('#txt_suma').val()-$('#txt_tpeso'+id).val());
	 var selected = document.querySelector('input[type=radio][name=txt_odi'+id+']:checked');
	 $('#peso'+id).html(selected.value*$('#txt_peso'+id).val());
	 $('#txt_tpeso'+id).val(selected.value*$('#txt_peso'+id).val());
	 $('#txt_suma').val(parseFloat($('#txt_suma').val())+parseFloat(selected.value*$('#txt_peso'+id).val()));
	 $('#suma').html($('#txt_suma').val());
	 total();
 }
//--------------------------------
function recuenta_comp(id)
 {
	 if ($('#txt_comp_'+id).is(":checked"))
		{
		$("#txt_pesoa2"+id).show("fade");
		$('input[name=txt_comp'+id+']').attr("disabled",false);
		var selected = document.querySelector('input[type=radio][name=txt_comp'+id+']:checked');
		$('#txt_tpeso2'+id).val(selected.value*$('#txt_pesoa2'+id).val());
		$('#pesow2'+id).html($('#txt_tpeso2'+id).val());
//		$('#txt_suma2').val(parseFloat($('#txt_suma2').val())+parseFloat(selected.value*$('#txt_pesoa2'+id).val()));
//		$('#suma2').html($('#txt_suma2').val());
		}
	 else { 
		 $('#txt_tpeso2'+id).val(0);
		 $('#pesow2'+id).html(0);
		 $("#txt_pesoa2"+id).hide();
		 $('input[name=txt_comp'+id+']').attr("disabled",true);
	 	}
	 total();
 }
//--------------------------------
function total()
 {
	 ///// PARA LOS SUBTOTAL
	 var suma = 0;
		$('.sumarC').each(function(){
			   suma += parseFloat($(this).val());
		});
	 $('#txt_suma2').val(suma);
		 $('#suma2').html(suma);
	 ////// TOTALES
	 $('#txt_sumaT').val(parseFloat($('#txt_suma').val())+parseFloat($('#txt_suma2').val()));
	 $('#sumaT').html($('#txt_sumaT').val());
	 var parametros = "id="+$('#txt_sumaT').val();
		$.ajax({  
			type : 'POST',
			url  : 'personal/15d_ponderacion.php',
			dataType:"json",
			data:  parametros, 
			success:function(data) {  	
				if (data.tipo=="info")
					{	$('#rango').html(data.msg);		}
				}  
			});
 }
//----------------
</script>