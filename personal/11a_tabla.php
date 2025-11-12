<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');
//-----------
$dato_buscar = trim($_GET['valor']);
$filtro = $_GET['tipo'];

switch ($filtro) {
    case 1:
        $filtrar = " AND descripcion LIKE '%$dato_buscar%'";
        break;
    case 2:
        $filtrar = " nomina.estatus = 0 AND ";
        break;
    case 3:
        $filtrar = " nomina.estatus = 5 AND ";
        break;
    case 4:
        $filtrar = " nomina.estatus > 5 AND nomina.estatus <> 99 AND ";
        break;
}?>
<table class="table table-hover" width="100%" border="0" align="center">
<tr>
<td class="TituloTablaP" height="41" colspan="10" align="center">Pagos Eventuales Registrados</td>
</tr>
<tr>
<td  bgcolor="#CCCCCC" align="center"><strong>N:</strong></td>
<td bgcolor="#CCCCCC" align="left"><strong>Nomina:</strong></td>
<td bgcolor="#CCCCCC" align="left"><strong>Concepto:</strong></td>
<td  bgcolor="#CCCCCC" align="center"><strong>Fecha:</strong></td>
<td  bgcolor="#CCCCCC" align="center"><strong>Cant. Trabajadores:</strong></td>
<td bgcolor="#CCCCCC" align="right"><strong>Total:</strong></td>
<td bgcolor="#CCCCCC" align="center"></td>
<td bgcolor="#CCCCCC" align="center"></td>
</tr>
<?php 	
//------ MONTAJE DE LOS DATOS
$consultx = "SELECT count(nomina.cedula) AS trabajadores, id_cont, nomina.id, nomina.estatus, nomina.tipo_pago, nomina.nomina, nomina.fecha, nomina.descripcion, nomina.descripcion, Sum(nomina.total) AS total, id_solicitud, nomina FROM nomina WHERE $filtrar nomina.tipo_pago = '008' GROUP BY nomina.nomina, nomina.estatus, nomina.tipo_pago ORDER BY nomina.fecha ASC;"; 
//echo $consultx;
$tablx = $_SESSION['conexionsql']->query($consultx);

while ($registro = $tablx->fetch_object())
	{
	$i++;
	?>
<tr >
<td><div align="center" ><?php echo ($i); ?></div></td>
<td ><div align="left" ><?php echo ($registro->nomina); ?></div></td>
<td ><div align="left" ><?php echo ($registro->descripcion); ?></div></td>
<td ><div align="center" ><?php echo voltea_fecha($registro->fecha); ?></div></td>
<td ><div align="center" ><?php echo ($registro->trabajadores); ?></div></td>
<td ><div align="right" ><strong><?php echo formato_moneda($registro->total); ?></strong></div></td>
<td ><div align="center" ><a data-toggle="tooltip" title="Preliminar"><button type="button" class="btn btn-outline-info waves-effect" onclick="imprimir('<?php echo encriptar($registro->id_cont); ?>','<?php echo ($registro->tipo_pago); ?>','<?php echo ($registro->estatus); ?>', '<?php echo encriptar($registro->id_solicitud); ?>', '<?php echo encriptar($registro->nomina); ?>');" ><i class="fas fa-print prefix grey-text mr-1"></i></button></a></div></td>
<td ><div align="center" ><?php if ($registro->estatus==0){ ?><button type="button" id="boton<?php echo ($registro->id); ?>" class="btn btn-outline-success waves-effect" onclick="solicitar('<?php echo encriptar($registro->id); ?>','boton<?php echo ($registro->id); ?>');" ><i class="fas fa-cloud-upload-alt prefix grey-text mr-1"></i> Generar Solicitud</button><?php } ?></div></td>
</tr>
 <?php 
 }
 ?>
 <tr>
<td colspan="10" class="PieTabla">Contraloria del Estado Bolivariano de Guarico</td>
</tr>
</table>