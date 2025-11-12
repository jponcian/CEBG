<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');

if ($_SESSION['VERIFICADO'] != "SI") { 
header ("Location: ../validacion.php?opcion=val"); 
exit(); }

$acceso=58;
//------- VALIDACION ACCESO USUARIO
include_once "../validacion_usuario.php";
//-----------------------------------
?>
<table class="datatabla" cellspacing="0" width="100%">
<!--
<tr>
<td class="TituloTablaP" height="41" colspan="10" align="center">Materiales y Suministros en Sistema</td>
</tr>
<tr>
<td colspan="9" align="center"><button type="button" id="botonb" class="btn btn-lg btn-block btn-info" onClick="rep();"><i class="fas fa-search mr-2"></i>Ver Pdf</button></td>
</tr>
-->
<thead>
	<tr>
		<th  bgcolor="#CCCCCC" align="center"><strong>Item:</strong></th>
		<th bgcolor="#CCCCCC" align="center"><strong>Suministro:</strong></th>
		<th bgcolor="#CCCCCC" align="center"><strong>Descripcion:</strong></th>
		<th  bgcolor="#CCCCCC" align="center"><strong>U.M.:</strong></th>
		<th  bgcolor="#CCCCCC" align="center"><strong>Existencia:</strong></th>
<!--		<th  bgcolor="#CCCCCC" align="center"><strong>Valor:</strong></th>-->
		<th bgcolor="#CCCCCC" align="center"><strong>Opciones:</strong></th>
	</tr>
</thead>
<tbody><?php 	
$_SESSION['titulo'] = 'RELACIÓN DE MATERIALES EN SISTEMA';
$dato_buscar = trim($_GET['valor']);
$filtro = $_GET['tipo'];

switch ($filtro) {
    case 1:
        $filtrar = " AND area LIKE '%$dato_buscar%' ORDER BY area, descripcion_bien, numero_bien";
        break;
    case 2:
        $filtrar = " AND numero_bien LIKE '%$dato_buscar%' ORDER BY numero_bien";
        break;
    case 3:
        $filtrar = " AND descripcion_bien LIKE '%$dato_buscar%' ORDER BY descripcion_bien";
        break;
    case 4:
        $filtrar = " ORDER BY descripcion_bien, numero_bien";
        break;		
    case 5:
        $filtrar = " AND bien=1 ORDER BY descripcion_bien, numero_bien";
		$_SESSION['titulo'] = 'ARTICULOS DE TRABAJO EN SISTEMA';
        break;		
    case 6:
        $filtrar = " AND bien=0 ORDER BY descripcion_bien, numero_bien";
        break;		
}
//------ MONTAJE DE LOS DATOS
$consultx = "SELECT * FROM bn_materiales WHERE 1=1 $filtrar;";//.$_GET['valor'].";"; 
$_SESSION['consulta'] = $consultx;
//echo $consultx;
$tablx = $_SESSION['conexionsql']->query($consultx);
while ($registro = $tablx->fetch_object())
	{
	$i++;
	?>
<tr id="fila<?php echo $registro->id; ?>">
<td><div align="center" ><?php echo ($i); ?></div></td>
<td ><div align="center" ><?php echo $_SESSION['almacen'][($registro->bien)]; ?></div></td>
<td ><div align="left" ><strong><?php echo ($registro->descripcion_bien); ?></strong></div></td>
<td ><div align="center" ><?php echo ($registro->unidad); ?></div></td>
<td ><div align="right" ><?php echo formato_moneda($registro->inventario); ?></div></td>
<!--<td ><div align="right" ><?php //echo formato_moneda($registro->valor); ?></div></td>-->

<td ><div align="center" ><a data-toggle="tooltip" title="Editar"><button type="button" class="btn btn-outline-info light-3 btn-sm" data-toggle="modal" data-target="#modal_largo" onclick="basicos(<?php echo ($registro->id_bien); ?>);" data-keyboard="false"><i class="fas fa-edit"></i></button></a>
<a data-toggle="tooltip" title="Eliminar"><button type="button" class="btn btn-outline-danger btn-sm" onclick="eliminar('<?php echo encriptar($registro->id_bien); ?>');"><i class="fas fa-trash-alt"></i></button></a></div></td>
</tr>
 <?php 
 }
 ?>
<!--
  <tr>
<td colspan="10" class="PieTabla">Contraloria del Estado Bolivariano de Gu&aacute;rico</td>
</tr>
-->
</tbody></table>
<br>
<br>
<script language="JavaScript" src="funciones/datatable.js"></script>