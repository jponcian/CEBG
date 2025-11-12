<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');

if ($_SESSION['VERIFICADO'] != "SI") { 
header ("Location: ../validacion.php?opcion=val"); 
exit(); }

$acceso=21;
//------- VALIDACION ACCESO USUARIO
include_once "../validacion_usuario.php";
//-----------------------------------
$consultx = "SELECT presupuesto.fecha_memo, presupuesto.fecha_presupuesto, presupuesto.fecha_solicitud, presupuesto.fecha_recibido, presupuesto.fecha_ofertas, presupuesto.fecha_examen, presupuesto.fecha_adjudicacion, presupuesto.fecha_notificacion, presupuesto.fecha_recepcion, presupuesto.fecha_orden, orden_solicitudes.anno, orden_solicitudes.tipo_orden, orden_solicitudes.estatus, orden_solicitudes.id, orden_solicitudes.id_contribuyente, contribuyente.rif, orden_solicitudes.fecha, orden_solicitudes.numero, orden_solicitudes.descripcion, orden_solicitudes.total, contribuyente.nombre FROM orden_solicitudes, contribuyente , presupuesto WHERE presupuesto.id_solicitud = orden_solicitudes.id_presupuesto AND  orden_solicitudes.id=".$_GET['id']." AND orden_solicitudes.id_contribuyente = contribuyente.id GROUP BY orden_solicitudes.id ;"; 
//echo $consultx ;
$tablx = $_SESSION['conexionsql']->query($consultx);
$registro = $tablx->fetch_object();
$anno = $registro->anno;
$numero = $registro->numero;
$fecha = $registro->fecha;
$id_contribuyente = $registro->id_contribuyente;
$tipo_orden = $registro->tipo_orden;
$id = $registro->id;
?>
<form id="form999" name="form999" method="post" >
			<!-- Modal Header -->
<div class="modal-header bg-fondo text-center">
<input type="hidden" id="oid" name="oid" value="<?php  echo $_GET['id'];?>"/>
<h4 align="center" style="background-color:#0275d8; color:#FFFFFF" class="modal-title w-100 font-weight-bold py-2">Modificar Orden<button type="button" class="close" data-dismiss="modal">&times;</button></h4>
</div>
<!-- Modal body -->
		<div class="p-1">
			
	<div class="row">
		<div class="form-group col-sm-4">
			<div class="input-group">
				<div class="input-group-text" align="center">Rif</div>
				<input placeholder="Rif del Proveedor" id="txt_rif" maxlength="10" name="txt_rif" class="form-control" type="text" style="text-align:center" value="<?php  echo $registro->rif;?>" readonly="" />
			</div>
		</div>
						
		<div class="form-group col-sm-4">
			<div class="input-group">
				<div class="input-group-text">Numero</div>
				<input type="text" style="text-align:center" class="form-control " name="txt_numero" id="txt_numero" placeholder="Numero de la Orden" onFocus="this.select()" minlength="1" maxlength="10" value="<?php  echo ($registro->numero);?>" required></div>
		</div>	
		
		<div class="form-group col-sm-4">
			<div class="input-group">
				<div class="input-group-text"><i class="far fa-calendar-alt mr-2"></i>Fecha</div>
				<input type="text" style="text-align:center" class="form-control " name="txt_fecha" id="txt_fecha" placeholder="Fecha de la Orden"  minlength="1" maxlength="10" value="<?php  echo voltea_fecha($registro->fecha);?>" readonly="" required></div>
		</div>	
		
	</div>
			
	<div class="row">
		<div class="form-group col-sm-12">
				<input id="txt_nombres" placeholder="Proveedor" name="txt_nombres" class="form-control" type="text" style="text-align:center" value="<?php  echo $registro->nombre;?>" readonly=""/>
		</div>
	</div>

	<div class="row">
		<div class="form-group col-sm-4">
			<div class="input-group">
				<div class="input-group-text"><!--<i class="far fa-calendar-alt mr-2"></i>-->Memo</div>
				<input type="text" style="text-align:center" class="form-control " name="txt_fechaM" id="txt_fechaM" placeholder="Memo"  minlength="1" maxlength="10" value="<?php  echo voltea_fecha($registro->fecha_memo);?>" readonly="" required>
			</div>
		</div>
						
		<div class="form-group col-sm-4">
			<div class="input-group">
				<div class="input-group-text"><!--<i class="far fa-calendar-alt mr-2"></i>-->Presupuesto</div>
				<input type="text" style="text-align:center" class="form-control " name="txt_fechaP" id="txt_fechaP" placeholder="Presupuesto"  minlength="1" maxlength="10" value="<?php  echo voltea_fecha($registro->fecha_presupuesto);?>" readonly="" required>
		</div>	</div>	

		<div class="form-group col-sm-4">
			<div class="input-group">
				<div class="input-group-text"><!--<i class="far fa-calendar-alt mr-2"></i>-->Solicitud</div>
				<input type="text" style="text-align:center" class="form-control " name="txt_fechaS" id="txt_fechaS" placeholder="Solicitud"  minlength="1" maxlength="10" value="<?php  echo voltea_fecha($registro->fecha_solicitud);?>" readonly="" required>
			</div>
		</div>
						
	</div>
	<div class="row">
		
		<div class="form-group col-sm-4">
			<div class="input-group">
				<div class="input-group-text"><!--<i class="far fa-calendar-alt mr-2"></i>-->Recibido</div>
				<input type="text" style="text-align:center" class="form-control " name="txt_fechaR" id="txt_fechaR" placeholder="Recibido"  minlength="1" maxlength="10" value="<?php  echo voltea_fecha($registro->fecha_recibido);?>" readonly="" required>
		</div></div>	
		
		<div class="form-group col-sm-4">
			<div class="input-group">
				<div class="input-group-text"><!--<i class="far fa-calendar-alt mr-2"></i>-->Oferta</div>
				<input type="text" style="text-align:center" class="form-control " name="txt_fechaO" id="txt_fechaO" placeholder="Oferta"  minlength="1" maxlength="10" value="<?php  echo voltea_fecha($registro->fecha_ofertas);?>" readonly="" required>
			</div>
		</div>
						
		<div class="form-group col-sm-4">
			<div class="input-group">
				<div class="input-group-text"><!--<i class="far fa-calendar-alt mr-2"></i>-->Examen</div>
				<input type="text" style="text-align:center" class="form-control " name="txt_fechaE" id="txt_fechaE" placeholder="Examen"  minlength="1" maxlength="10" value="<?php  echo voltea_fecha($registro->fecha_examen);?>" readonly="" required>
		</div></div>	
				
	</div>	
			<div class="row">
		<div class="form-group col-sm-4">
			<div class="input-group">
				<div class="input-group-text"><!--<i class="far fa-calendar-alt mr-2"></i>-->Adjudicacion</div>
				<input type="text" style="text-align:center" class="form-control " name="txt_fechaA" id="txt_fechaA" placeholder="Adjudicacion"  minlength="1" maxlength="10" value="<?php  echo voltea_fecha($registro->fecha_adjudicacion);?>" readonly="" required>
		</div>	</div>	
		<div class="form-group col-sm-4">
			<div class="input-group">
				<div class="input-group-text"><!--<i class="far fa-calendar-alt mr-2"></i>-->Notificacion</div>
				<input type="text" style="text-align:center" class="form-control " name="txt_fechaN" id="txt_fechaN" placeholder="Notificacion"  minlength="1" maxlength="10" value="<?php  echo voltea_fecha($registro->fecha_notificacion);?>" readonly="" required>
			</div>
		</div>
						
		<div class="form-group col-sm-4">
			<div class="input-group">
				<div class="input-group-text"><!--<i class="far fa-calendar-alt mr-2"></i>-->Recepcion</div>
				<input type="text" style="text-align:center" class="form-control " name="txt_fechaRE" id="txt_fechaRE" placeholder="Recepcion"  minlength="1" maxlength="10" value="<?php  echo voltea_fecha($registro->fecha_recepcion);?>" readonly="" required>
		</div></div>	
		
	</div>
			
	<div class="row">
		<div class="form-group col-sm-12">
			<div class="input-group-text"><i class="fas fa-university mr-2"></i>
			<input id="txt_concepto" placeholder="Concepto" name="txt_concepto" class="form-control" type="text" style="text-align:center" value="<?php  echo $registro->descripcion;?>" />
			</div>
		</div>
	</div>

<table class="formateada" width="100%" border="1">
  <tr>
    <th width="50%" scope="col">Descripcion</th>
    <th scope="col">Categoria:</th>
    <th scope="col">Partida:</th>
    <th scope="col">Exento:</th>
  </tr>
<?php
$consultax = "SELECT exento, id, categoria, partida, descripcion FROM orden WHERE id_solicitud='$id' ORDER BY categoria, partida;"; //echo $consultax;
$tablax = $_SESSION['conexionsql']->query($consultax);
while ($registro = $tablax->fetch_object())
{
?>
  <tr>
    <td valign="middle" ><?php  echo $registro->descripcion;?></td>
    <td ><select class="select2" style="font-size: 14px" name="txt_categoria<?php  echo $registro->id;?>" id="txt_categoria<?php  echo $registro->id;?>" onchange="combo(this.value, 'txt_partida<?php  echo $registro->id;?>');">
<?php
$consultx = "SELECT categoria FROM a_presupuesto_$anno GROUP BY categoria ORDER BY categoria;";//left(trim(codigo),3) <> '401' AND 
//echo $consultx; 
$tablx = $_SESSION['conexionsql']->query($consultx);
while ($registro_x = $tablx->fetch_object())
//-------------
	{
	echo '<option value="';
	echo $registro_x->categoria;
	echo '" ';
	if ($registro->categoria==$registro_x->categoria) {echo 'selected="selected"';}
	echo ' >';
	echo $registro_x->categoria;
	echo '</option>';
	}
	?>
					</select></td> 
    <td ><select class="select2" style="font-size: 14px; width: 350px" name="txt_partida<?php  echo $registro->id;?>" id="txt_partida<?php  echo $registro->id;?>" onchange="">
<?php
//--------------------
$consultx = "SELECT * FROM a_presupuesto_$anno WHERE categoria='".$registro->categoria."' ORDER BY codigo;";// AND left(trim(codigo),3)<>'401'
$tablx = $_SESSION['conexionsql']->query($consultx);
while ($registro_x = $tablx->fetch_object())
//-------------
	{
	echo '<option value="';
	echo $registro_x->codigo;
	echo '" ';
	if ($registro->partida==$registro_x->codigo) {echo 'selected="selected"';}
	echo ' >';
	echo $registro_x->codigo.' - '.$registro_x->descripcion;
	echo '</option>';
	}
?>
					</select></td>
	<td align="center" ><input class="switch_new" id="txt_exento<?php  echo $registro->id;?>" name="txt_exento<?php  echo $registro->id;?>" type="checkbox" value="1" <?php  if ($registro->exento==1) {echo 'checked';}?> /><label for="txt_exento<?php  echo $registro->id;?>" class="lbl_switch"></label></td>
  </tr>
<?php  } ?>
</table>
<br>

<div align="center">			
	<button type="button" id="boton" class="btn btn-outline-success waves-effect" onclick="guardar('boton')" ><i class="fas fa-save prefix grey-text mr-1"></i> Guardar Cambios</button>			
</div>
	
	</div>

<!-- Modal footer -->
<div class="modal-footer justify-content-center">
	<div align="center" id="div3">			

	</div>
</div>

</form>
<script language="JavaScript">
// PARA EL SELECT2
$(document).ready(function() {
    $('.select2').select2();
});
$("#txt_fecha").datepicker();
$("#txt_fechaM").datepicker();
$("#txt_fechaS").datepicker();
$("#txt_fechaR").datepicker();
$("#txt_fechaP").datepicker();
$("#txt_fechaA").datepicker();
$("#txt_fechaE").datepicker();
$("#txt_fechaO").datepicker();
$("#txt_fechaRE").datepicker();
$("#txt_fechaN").datepicker();
//-------------
function combo(categoria, nombre)
{
	$.ajax({
        type: "POST",
        url: 'compras/3c_combo.php?categoria='+categoria+'&anno='+document.form999.txt_anno.value,
        success: function(resp){
            $('#'+nombre).html(resp);
        }
    });
}
//--------------------------------
$('#txt_numero').focus();  
//--------------------------------
</script>