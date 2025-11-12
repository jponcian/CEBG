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
<th  bgcolor="#CCCCCC" align="center"><strong>Fecha:</strong></th>
<th  bgcolor="#CCCCCC" align="center"><strong>Direccion:</strong></th>
<th bgcolor="#CCCCCC" align="center"><strong>Funcionario:</strong></th>
<th  bgcolor="#CCCCCC" align="center"><strong>Grupo:</strong></th>
<th  bgcolor="#CCCCCC" align="center"><strong>Numero:</strong></th>
<th bgcolor="#CCCCCC" align="center"><strong>Descripcion:</strong></th>
<th bgcolor="#CCCCCC" align="center"><strong>Detalle:</strong></th>
<th bgcolor="#CCCCCC" align="center"><strong>Estatus</strong></th>
<th bgcolor="#CCCCCC" align="center"><strong></strong></th>
<!--<th bgcolor="#CCCCCC" align="center"><strong></strong></th>-->
	</tr>
</thead>
<tbody><?php 	
$dato_buscar = trim($_GET['valor']);
$filtro = $_GET['tipo'];

switch ($filtro) {
    case 3:
        $filtrar = " ORDER BY arc_prestamos.id DESC";
		$consultx = "SELECT arc_prestamos.id, arc_prestamos.grupo, arc_prestamos.numero, arc_prestamos.contenido, arc_prestamos.fecha, a_direcciones.direccion, CONCAT(rac.nombre,' ',rac.nombre2,' ',rac.apellido,' ',rac.apellido2) as  nombre, arc_prestamos.hasta, arc_prestamos.hora1,	arc_prestamos.horaa, arc_prestamos.estatus, arc_prestamos.descripcion FROM arc_prestamos, a_direcciones, rac WHERE	arc_prestamos.id_direccion = a_direcciones.id AND arc_prestamos.funcionario = rac.cedula AND estatus = 0 $filtrar;";//.$_GET['valor'].";"; 
        break;		
    case 5:
        $filtrar = " ORDER BY arc_prestamos.id DESC";
		$consultx = "SELECT arc_prestamos.id, arc_prestamos.grupo, arc_prestamos.numero, arc_prestamos.contenido, arc_prestamos.fecha, a_direcciones.direccion, CONCAT(rac.nombre,' ',rac.nombre2,' ',rac.apellido,' ',rac.apellido2) as  nombre, arc_prestamos.hasta, arc_prestamos.hora1,	arc_prestamos.horaa, arc_prestamos.estatus, arc_prestamos.descripcion FROM arc_prestamos, a_direcciones, rac WHERE	arc_prestamos.id_direccion = a_direcciones.id AND arc_prestamos.funcionario = rac.cedula AND estatus = 10 $filtrar;";//.$_GET['valor'].";"; 
        break;		
}
//------ MONTAJE DE LOS DATOS
//$consultx = "SELECT * FROM arc_biblioteca WHERE 1=1 $filtrar;";//.$_GET['valor'].";"; 
//echo $consultx;
$_SESSION['consulta'] = $consultx;
//echo $consultx;
$tablx = $_SESSION['conexionsql']->query($consultx);
while ($registro = $tablx->fetch_object())
	{
	$i++;
	?>
<tr id="fila<?php echo $registro->id; ?>">
<td><div align="center" ><?php echo ($i); ?></div></td>
<td ><div align="left" ><?php echo voltea_fecha($registro->fecha); ?></div></td>
<td ><div align="center" ><?php echo ($registro->direccion); ?></div></td>
<td ><div align="left" ><?php echo ($registro->nombre); ?></div></td>
<td ><div align="left" ><?php echo ($registro->grupo); ?></div></td>
<td ><div align="left" ><?php echo ($registro->numero); ?></div></td>
<td ><div align="left" ><?php echo ($registro->contenido); ?></div></td>
<td ><div align="left" ><?php echo ($registro->descripcion); ?></div></td>
<td ><div align="center" ><?php echo $_SESSION['archivo'][($registro->estatus)]; ?></div></td>

<!--<td ><div align="center" ><a data-toggle="tooltip" title="Ficha"><button type="button" class="btn btn-outline-success light-3 btn-sm" onclick="ficha('<?php //echo encriptar($registro->id); ?>');" data-keyboard="false"><i class="fas fa-file-pdf"></i></button></a></div></td>-->

<td ><?php if ($registro->estatus==0) { ?><div align="center" ><a data-toggle="tooltip" title="Devolver Expediente"><button type="button" class="btn btn-outline-info light-3 btn-sm" data-toggle="modal" data-target="#modal_largo" onclick="expediente(<?php echo ($registro->id); ?>);" data-keyboard="false"><i class="fa-solid fa-book-open"></i></button></a></div><?php } ?></td>

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