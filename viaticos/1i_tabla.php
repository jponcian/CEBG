<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');
//-----------
$id =$_SESSION['id'];
$zona =$_SESSION['zona'];
$contralor =$_SESSION['contralor'];
?>
<table class="table table-hover" width="100%" border="0" align="center">
<tr>
<td class="TituloTablaP" height="41" colspan="10" align="center">Items</td>
</tr>
<tr>
<td  bgcolor="#CCCCCC" width="40%" align="center"><strong>Tipo</strong></td>
<td bgcolor="#CCCCCC" align="center"><strong>Monto Unitario</strong></td>
<td  bgcolor="#CCCCCC" align="center"><strong>Cantidad</strong></td>
<td bgcolor="#CCCCCC" align="center"><strong>Total</strong></td>
<td bgcolor="#CCCCCC" align="center"><strong>Opcion</strong></td>
</tr>
<tr id="fila">
<td ><div align="center" ><select class="custom-select" onChange="montou(this.value);" style="font-size: 14px" name="txt_tipo" id="txt_tipo">
	<option value="0">Seleccione</option>
	<?php
	//--------------------
	$consultx = "SELECT * FROM a_item_viaticos WHERE id_zona=$zona AND contralor=$contralor and id not in (SELECT id_tipo FROM viaticos_solicitudes_detalle WHERE id_solicitud=$id) ;"; 
	$tablx = $_SESSION['conexionsql']->query($consultx);
	while ($registro_x = $tablx->fetch_object())
	//-------------
	{
	echo '<option ';
	echo ' value="';
	echo $registro_x->id.'-'.$registro_x->monto;
	echo '">';
	echo ($registro_x->tipo);
	echo '</option>';
	}
	?>
	</select></div></td>
<td><div align="center" ><input id="txt_monto" name="txt_monto" placeholder="Monto Unitario" class="form-control" type="hidden" style="text-align:right" readonly />
	<input id="txt_monto2" name="txt_monto2" placeholder="Monto Unitario" class="form-control" type="text" style="text-align:right" readonly /></div></td>

<td><div align="center" ><input onFocus="this.select()" onKeyPress="calcular(event,this)" onkeydown="puro_numero('txt_cantidad');" id="txt_cantidad" name="txt_cantidad" placeholder="Cant" class="form-control" type="text" style="text-align:center" /></div></td>

<td><div align="center" ><input id="txt_total" name="txt_total" placeholder="Monto Total" class="form-control" type="text" style="text-align:right" readonly /></div></td>

<td align="center" ><button type="button" id="check1" class="btn btn-outline-info waves-effect" onclick="agregar2()" ><i class="fas fa-save prefix grey-text mr-1"></i></button></td>
</tr>

</table>