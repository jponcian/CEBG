<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');

if ($_SESSION['VERIFICADO'] != "SI") { 
header ("Location: ../validacion.php?opcion=val"); 
exit(); }

$acceso=113;
//------- VALIDACION ACCESO USUARIO
include_once "../validacion_usuario.php";
//-----------------------------------
?>
<table class="datatabla formateada" align="center" width="100%">
<thead>
<!--
<tr>
<td class="TituloTablaP" height="41" colspan="10" align="center">Bienes en Sistema</td>
</tr>
-->
<tr>
<th  bgcolor="#CCCCCC" align="center"><strong>Item:</strong></th>
<th  bgcolor="#CCCCCC" align="center"><strong>Grupo:</strong></th>
<th  bgcolor="#CCCCCC" align="center"><strong>Numero:</strong></th>
<th bgcolor="#CCCCCC" align="center"><strong>Descripcion:</strong></th>
<!--
<th  bgcolor="#CCCCCC" align="center"><strong>Estado:</strong></th>
<th  bgcolor="#CCCCCC" align="center"><strong>Valor:</strong></th>
-->
<!--<th bgcolor="#CCCCCC" align="center"><strong></strong></th>-->
<th bgcolor="#CCCCCC" align="center"><strong></strong></th>
<!--<th bgcolor="#CCCCCC" align="center"><strong></strong></th>-->
	</tr>
</thead>
<tbody><?php 	
$dato_buscar = trim($_GET['valor']);
$filtro = $_GET['tipo'];

switch ($filtro) {
//    case 1:
//        $filtrar = " AND division LIKE '%$dato_buscar%' ORDER BY division, descripcion_bien, numero_bien";
//        break;
//    case 2:
//        $filtrar = " AND numero_bien LIKE '%$dato_buscar%' ORDER BY numero_bien";
//        break;
//    case 3:
//        $filtrar = " AND descripcion_bien LIKE '%$dato_buscar%' ORDER BY descripcion_bien";
//        break;
    case 3:
        $filtrar = " ORDER BY id DESC";
		$consultx = "SELECT * FROM arc_prestamos WHERE estatus = 0 $filtrar;";//.$_GET['valor'].";"; 
        break;		
    case 4:
        $filtrar = " ORDER BY grupo, abs(numero), descripcion";
		$consultx = "SELECT * FROM arc_biblioteca WHERE 1=1 $filtrar;";//.$_GET['valor'].";"; 
        break;		
}
//------ MONTAJE DE LOS DATOS
//$consultx = "SELECT * FROM arc_biblioteca WHERE 1=1 $filtrar;";//.$_GET['valor'].";"; 
$_SESSION['consulta'] = $consultx;
//echo $consultx;
$tablx = $_SESSION['conexionsql']->query($consultx);
while ($registro = $tablx->fetch_object())
	{
	$i++;
	?>
<tr id="fila<?php echo $registro->id; ?>">
<td><div align="center" ><?php echo ($i); ?></div></td>
<td ><div align="center" ><?php echo ($registro->grupo); ?></div></td>
<td ><div align="center" ><?php echo ($registro->numero); ?></div></td>
<td ><div align="left" ><?php echo ($registro->descripcion); ?></div></td>

<!--<td ><div align="center" ><a data-toggle="tooltip" title="Ficha"><button type="button" class="btn btn-outline-success light-3 btn-sm" onclick="ficha('<?php //echo encriptar($registro->id); ?>');" data-keyboard="false"><i class="fas fa-file-pdf"></i></button></a></div></td>-->

<td ><div align="center" ><a data-toggle="tooltip" title="Prestar Expediente"><button type="button" class="btn btn-outline-info light-3 btn-sm" data-toggle="modal" data-target="#modal_largo" onclick="basicos(<?php echo ($registro->id); ?>);" data-keyboard="false">Prestar</button></a></div></td>

<!--<td ><div align="center" ><a data-toggle="tooltip" title="Eliminar"><button type="button" class="btn btn-outline-danger btn-sm" onclick="eliminar('<?php //echo encriptar($registro->id); ?>');"><i class="fas fa-trash-alt"></i></button></a></div></td>-->

</tr>
 <?php 
 }
 ?>
<br>
<br>
	<!--
  <tr>
<td colspan="7" class="PieTabla">Contraloria del Estado Bolivariano de Gu&aacute;rico</td>
</tr>
-->
</tbody></table>
<script language="JavaScript" src="funciones/datatable.js"></script>