<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');
//-----------
$id = $_GET['id'];
?>
<table class="table table-hover" width="100%" border="0" align="center">
<tr>
<td bgcolor="#99FFCC" align="center" colspan="6" height="4"><strong>Material(es) Solicitado(s)</strong></td>
</tr>
<tr>
<td  bgcolor="#CCCCCC" align="center"><strong>Item:</strong></td>
<td  bgcolor="#CCCCCC" align="center"><strong>Descripci&oacute;n:</strong></td>
<td  bgcolor="#CCCCCC" align="center"><strong>U.M.:</strong></td>
<td  bgcolor="#CCCCCC" align="center"><strong>Inventario:</strong></td>
<td  bgcolor="#CCCCCC" align="center"><strong>Solicitado:</strong></td>
<td bgcolor="#CCCCCC" align="center"><strong>Aprobado:</strong></td>
</tr>
<?php 	
$i=0;
//------ MONTAJE DE LOS DATOS
$consultx = "SELECT * FROM bn_solicitudes_detalle, bn_materiales WHERE bn_materiales.id_bien = bn_solicitudes_detalle.id_bien AND id_solicitud=$id;"; 
//echo $consultx;
$tablx = $_SESSION['conexionsql']->query($consultx);

while ($registro = $tablx->fetch_object())
	{
	$i++;
?>
<tr >
<td><div align="center" ><?php echo ($i); ?></div></td>
<td ><div align="left" ><?php echo ($registro->descripcion_bien); ?></div></td>
<td ><div align="center" ><?php echo ($registro->unidad); ?></div></td>
<td ><div align="center" ><?php echo ($registro->inventario); ?></div></td>
<td ><div align="center" ><?php echo ($registro->cantidad); ?></div></td>
<td ><div align="center" ><?php echo ($registro->cant_aprobada); ?></div></td>
<!--<td ><div align="center" ><input disabled value="<?php //echo ($registro->cant_aprobada); ?>" id="O<?php //echo ($registro->id_detalle); ?>" name="O<?php //echo ($registro->id_detalle); ?>" size="2" class="input-group-text" type="text" onkeypress="agregar2(event,'<?php //echo ($registro->id_detalle); ?>',this.value); return SoloNumero(event,this);" maxlength="10"></td>-->
</tr>
 <?php 
 }
 ?>
</table>
<div align="center"><button data-toggle="modal" data-target="#modal_largo" data-backdrop="static" data-keyboard="false" onclick="generar_solicitud('<?php echo encriptar($id); ?>');" type="button" class="btn btn-outline-success waves-effect"><i class="fas fa-cloud-upload-alt prefix grey-text mr-1"></i>Despachar</button></div>
