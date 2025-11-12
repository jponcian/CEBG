<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');
//-----------
//if ($_GET['periodo']<>'')	{$periodo = " WHERE desde='".voltea_fecha($_GET['periodo'])."'";} else {$periodo = "";}
?>
<table class="table table-hover" width="100%" border="0" align="center">
<tr>
<td class="TituloTablaP" height="41" colspan="10" align="center">Solicitud de Pago (Viaticos)</td>
</tr>
<tr>
<td  bgcolor="#CCCCCC" align="center"><strong>N:</strong></td>
<td  bgcolor="#CCCCCC" align="left"><strong>Funcionario:</strong></td>
<td  bgcolor="#CCCCCC" align="left"><strong>Fecha:</strong></td>
<td bgcolor="#CCCCCC" align="left"><strong>Numero:</strong></td>
<td bgcolor="#CCCCCC" align="left"><strong>Concepto:</strong></td>
<td bgcolor="#CCCCCC" align="right"><strong>Total:</strong></td>
<td bgcolor="#CCCCCC" align="center"></td>
<td bgcolor="#CCCCCC" align="center"></td>
</tr>
<?php 	
$i=0;
//------ MONTAJE DE LOS DATOS
$consultx = "SELECT viaticos_solicitudes.concepto, viaticos_solicitudes.estatus, viaticos_solicitudes.id, viaticos_solicitudes.cedula, viaticos_solicitudes.total, CONCAT(rac.nombre,' ',rac.nombre2,' ',rac.apellido,' ',rac.apellido2) as  nombre, viaticos_solicitudes.numero, viaticos_solicitudes.fecha, viaticos_solicitudes.desde, 	viaticos_solicitudes.hasta,	viaticos_solicitudes.direccion,	viaticos_solicitudes.contralor, viaticos_solicitudes.zona as id_zona, a_zonas_viaticos.zona, a_zonas_viaticos.ciudades, a_direcciones.direccion , ciudad FROM viaticos_solicitudes, a_direcciones, a_zonas_viaticos, rac WHERE viaticos_solicitudes.estatus=7 AND rac.cedula=viaticos_solicitudes.cedula AND viaticos_solicitudes.direccion = a_direcciones.id AND a_zonas_viaticos.id = viaticos_solicitudes.zona ORDER BY viaticos_solicitudes.id DESC;";
//echo $consultx;
$tablx = $_SESSION['conexionsql']->query($consultx);

while ($registro = $tablx->fetch_object())
	{
	$i++;
//	if ($registro->tipo_orden==1)	{$tipo='COMPRA ';}
//	if ($registro->tipo_orden==2)	{$tipo='SERVICIO ';}
//	if ($registro->tipo_orden==3)	{$tipo='MANUAL ';}
//	if ($registro->tipo_orden==4)	{$tipo='FINANCIERA ';}
//	if ($registro->tipo_orden==5)	{$tipo='VIATICO ';}
	if ($registro->tipo_orden==5)	{$tipo='';}
	?>
<tr >
<td><div align="center" ><?php echo ($i); ?></div></td>
<td ><div align="left" ><strong><?php echo ($registro->nombre); ?></strong></div></td>
<td ><div align="left" ><?php echo voltea_fecha($registro->fecha); ?></div></td>
<td ><div align="left" ><strong><?php echo $tipo.rellena_cero($registro->numero,6); ?></strong></div></td>
<td ><div align="left" ><?php echo ($registro->concepto); ?></div></td>
<td ><div align="right" ><?php echo formato_moneda($registro->total); ?></div></td>
<td ><div align="right" ><div class="input-group-prepend">
<span class="input-group-text">
<input name="osel<?php echo ($registro->id); ?>" type="checkbox" value="<?php echo ($registro->id); ?>" /></span>
</div>
</div></td>
</tr>
 <?php 
 }
 ?>
 <tr>
<td colspan="10" class="PieTabla">Contraloria del Estado Bolivariano de Gu&aacute;rico</td>
</tr>
</table>
<?php 	
if ($i>0) {
?> <div align="center">
		<button type="button" id="boton" class="btn btn-outline-success waves-effect" onclick="generar_pago();" ><i class="fas fa-cloud-upload-alt prefix grey-text mr-1"></i> Generar Memo</button>
</div>
<?php 	
}
?>