<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');
//-----------
$dato_buscar = trim($_GET['valor']);
$filtro = $_GET['tipo'];
$fecha1 = voltea_fecha($_GET['fecha1']);
$fecha2 = voltea_fecha($_GET['fecha2']);

switch ($filtro) {
    case 1:
        $filtro = " numero='$dato_buscar' ";
        break;
    case 3:
        $filtro = " fecha >= '$fecha1' AND fecha <= '$fecha2' ";
        break;
    case 4:
        $filtro = " 1=1 ";
        break;
}?>
<table class="table table-hover" width="100%" border="0" align="center">
<tr>
<td class="TituloTablaP" height="41" colspan="9" align="center">Reasignaciones en Sistema</td>
</tr>
<!--
<tr>
<td colspan="9" align="center"><button type="button" id="botonb" class="btn btn-lg btn-block btn-info" onClick="rep();"><i class="fas fa-search mr-2"></i>Ver Pdf</button></td>
</tr>
-->
<tr>
<td  bgcolor="#CCCCCC" align="center"><strong>N:</strong></td>
<td  bgcolor="#CCCCCC" align="center"><strong>Fecha</strong></td>
<td  bgcolor="#CCCCCC" align="left"><strong>Direccion Origen</strong></td>
<td  bgcolor="#CCCCCC" align="left"><strong>Direccion Destino</strong></td>
<td bgcolor="#CCCCCC" align="center"></td>
<td bgcolor="#CCCCCC" align="center"></td>
</tr>
<?php 	
//------ MONTAJE DE LOS DATOS
$consultx = "SELECT bn_reasignaciones.* FROM bn_reasignaciones WHERE $filtro ORDER BY fecha DESC, numero DESC;";
//echo $consultx;
$_SESSION['consulta'] = $consultx;
$tablx = $_SESSION['conexionsql']->query($consultx);
while ($registro = $tablx->fetch_object())
	{
	$i++;
	?>
<tr >
<td ><div align="center" ><?php echo ($registro->numero); ?></div></td>
<td ><div align="center" ><strong><?php echo voltea_fecha($registro->fecha); ?></strong></div></td>
<td ><div align="left" ><?php echo ($registro->direccion_actual); ?></div></td>
<td ><div align="left" ><strong><?php echo ($registro->direccion_destino); ?></strong></div></td>

<td ><div align="center" ><a data-toggle="tooltip" title="Modificar Numero y Fecha"><button type="button" data-toggle="modal" data-target="#modal_normal" class="btn btn-info waves-effect" onclick="modificar('<?php echo ($registro->id); ?>');" >Modificar</button></a></div></td>

<td ><div align="center" ><a data-toggle="tooltip" title="Ver PDF Reasignacion"><button type="button" class="btn btn-outline-info waves-effect" onclick="imprimir('<?php echo encriptar($registro->division_actual); ?>','<?php echo encriptar($registro->division_destino); ?>','10','<?php echo encriptar($registro->id); ?>');" ><i class="fas fa-print prefix grey-text mr-1"></i></button></a></div></td>
	
</tr>
 <?php 
 }
 ?>
 <tr>
<td colspan="10" class="PieTabla">Contraloria del Estado Bolivariano de Gu&aacute;rico</td>
</tr>
</table>