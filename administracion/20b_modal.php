<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');

if ($_SESSION['VERIFICADO'] != "SI") { 
header ("Location: ../validacion.php?opcion=val"); 
exit(); }

$acceso=31;
//------- VALIDACION ACCESO USUARIO
include_once "../validacion_usuario.php";
//-----------------------------------
$consultx = "SELECT orden.id_contribuyente, orden.numero, orden.anno, orden.rif, orden.fecha, orden.concepto, contribuyente.nombre, orden.tipo_orden, orden.id_solicitud FROM orden, contribuyente WHERE orden.id_solicitud=".$_GET['id']." AND (tipo_orden='M') AND orden.id_contribuyente = contribuyente.id GROUP BY numero, id_contribuyente ORDER BY fecha DESC, orden.id DESC;"; 
$tablx = $_SESSION['conexionsql']->query($consultx);
$registro = $tablx->fetch_object();
$anno = $registro->anno;
$numero = $registro->numero;
$fecha = $registro->fecha;
$id_contribuyente = $registro->id_contribuyente;
$tipo_orden = $registro->tipo_orden;
?>
<form id="form999" name="form999" method="post" >
			<!-- Modal Header -->
<div class="modal-header bg-fondo text-center">
<input type="hidden" id="oid" name="oid" value="<?php  echo $_GET['id'];?>"/>
<input type="hidden" id="txt_tipo" name="txt_tipo" value="<?php  echo $_GET['tipo'];?>" />
<input type="hidden" id="txt_estatus" name="txt_estatus" value="<?php  echo $_GET['estatus'];?>" />
<input type="hidden" id="txt_id_sol" name="txt_id_sol" value="<?php  echo $registro->id_solicitud;?>" />
<input type="hidden" id="txt_anno" name="txt_anno" value="<?php  echo $registro->anno;?>" />
<input type="hidden" id="txt_id_contribuyente" name="txt_id_contribuyente" value="<?php  echo $registro->id_contribuyente;?>" />
<h4 align="center" style="background-color:#0275d8; color:#FFFFFF" class="modal-title w-100 font-weight-bold py-2">Modificar Solicitud
<button type="button" class="close" data-dismiss="modal">&times;</button></h4>
</div>
<!-- Modal body -->
		<div class="p-1">
			
	<div class="row">
		<div class="form-group col-sm-5">
			<div class="input-group">
				<div class="input-group-text" align="center">Rif</div>
				<input placeholder="Rif del Proveedor" id="txt_rif" maxlength="10" name="txt_rif" class="form-control" type="text" style="text-align:center" value="<?php  echo $registro->rif;?>" readonly="" />
			</div>
		</div>
		<div class="form-group col-sm-3">
			<div class="input-group">
				<div class="input-group-text">Numero</div>
				<input onfocus="this.select()" id="txt_numero" name="txt_numero" type="text" style="text-align:center" class="form-control " value="<?php echo ($registro->numero);?>" required></div>
		</div>	
						
		<div class="form-group col-sm-4">
			<div class="input-group">
				<div class="input-group-text"><i class="far fa-calendar-alt mr-2"></i></div>
				<input type="text" style="text-align:center" class="form-control " name="txt_fecha" id="txt_fecha" placeholder="Fecha de la Orden"  minlength="1" maxlength="10" value="<?php  echo voltea_fecha($registro->fecha);?>" required readonly="" > </div>
		</div>	
		
	</div>
			
	<div class="row">
		<div class="form-group col-sm-12">
				<input id="txt_nombres" placeholder="Proveedor" name="txt_nombres" class="form-control" type="text" style="text-align:center" value="<?php  echo $registro->nombre;?>" readonly=""/>
		</div>
	</div>

	<div class="row">
		<div class="form-group col-sm-12">
			<div class="input-group-text"><i class="fas fa-university mr-2"></i>
			<input id="txt_concepto" placeholder="Concepto" name="txt_concepto" class="form-control" type="text" style="text-align:center" value="<?php  echo $registro->concepto;?>" />
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
$consultax = "SELECT exento, id, categoria, partida, descripcion FROM orden WHERE numero='$numero' AND fecha='$fecha' AND id_contribuyente='$id_contribuyente' AND tipo_orden='$tipo_orden' ORDER BY categoria, partida;"; 
$tablax = $_SESSION['conexionsql']->query($consultax);
while ($registro = $tablax->fetch_object())
{
?>
  <tr>
    <td valign="middle" ><?php  echo $registro->descripcion;?></td>
    <td ><select class="custom-select" style="font-size: 14px" name="txt_categoria<?php  echo $registro->id;?>" id="txt_categoria<?php  echo $registro->id;?>" onchange="combo(this.value, 'txt_partida<?php  echo $registro->id;?>');">
<?php
$consultx = "SELECT a_presupuesto_$anno.categoria, a_categoria.descripcion FROM a_presupuesto_$anno , a_categoria WHERE left(trim(a_presupuesto_$anno.codigo),3) <> '401' AND a_presupuesto_$anno.categoria = a_categoria.codigo GROUP BY a_presupuesto_$anno.categoria ORDER BY a_presupuesto_$anno.categoria ASC;";
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
    <td ><select class="custom-select" style="font-size: 14px" name="txt_partida<?php  echo $registro->id;?>" id="txt_partida<?php  echo $registro->id;?>" onchange="">
<?php
//--------------------
$consultx = "SELECT * FROM a_presupuesto_$anno WHERE categoria='".$registro->categoria."' AND left(trim(codigo),3)<>'401' ORDER BY codigo;";
$tablx = $_SESSION['conexionsql']->query($consultx);
while ($registro_x = $tablx->fetch_object())
//-------------
	{
	echo '<option value="';
	echo $registro_x->codigo;
	echo '" ';
	if ($registro->partida==$registro_x->codigo) {echo 'selected="selected"';}
	echo ' >';
	echo $registro_x->codigo;
	echo '</option>';
	}
?>
					</select></td>
	<td align="center" ><input id="txt_exento<?php  echo $registro->id;?>" name="txt_exento<?php  echo $registro->id;?>" type="checkbox" value="1" <?php  if ($registro->exento==1) {echo 'checked';}?> /></td>
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
//-------------
$("#txt_fecha").datepicker();
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
$('#txt_concepto').focus();  
//--------------------------------
$("#txt_precio").on({
    "focus": function (event) {
        $(event.target).select();
    },
    "keyup": function (event) {
        $(event.target).val(function (index, value ) {
            return value.replace(/\D/g, "")
                        .replace(/([0-9])([0-9]{2})$/, '$1,$2')
                        .replace(/\B(?=(\d{3})+(?!\d)\.?)/g, ".");
        });
    }
});
</script>