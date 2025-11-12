<?php
session_start();
include_once "../conexion.php";
include_once "../funciones/auxiliar_php.php";
//-----------
$desde = voltea_fecha($_GET['fecha1']);
$hasta = voltea_fecha($_GET['fecha2']);
//-----------
if ($_GET['tipo']=='1')	
	{
	$filtro = " rac.nombre like '%".($_GET['valor'])."%' AND ";	
	} 
elseif ($_GET['tipo']=='2')	 
	{
	$filtro = " viaticos_solicitudes.concepto like '%".($_GET['valor'])."%' AND ";	
	}
elseif ($_GET['tipo']=='3')	 
	{
	$filtro = " a_zonas_viaticos.zona like '%".($_GET['valor'])."%' AND ";	
	}
elseif ($_GET['tipo']=='7')	 
	{	
	$filtro = " ((viaticos_solicitudes.fecha>='$desde' AND viaticos_solicitudes.fecha<='$hasta') or (viaticos_solicitudes.desde>='$desde' AND viaticos_solicitudes.desde<='$hasta') or (viaticos_solicitudes.hasta>='$desde' AND viaticos_solicitudes.hasta<='$hasta')) AND ";	
	}
elseif ($_GET['tipo']=='4')	 
	{	
	$filtro = " ";	
	}
elseif ($_GET['tipo']=='0')	 
	{	
	$filtro = " estatus=0 AND ";	
	}
elseif ($_GET['tipo']=='5')	 
	{	
	$filtro = " estatus=5 AND ";	
	}
elseif ($_GET['tipo']=='10')	 
	{	
	$filtro = " estatus=7 AND ";	
	}
		else {$filtro = "";}
?>
<table class="table table-hover" width="100%" border="0" align="center">
<tr>
<td class="TituloTablaP" height="41" colspan="10" align="center">Solicitudes Pre-registradas</td>
</tr>
<tr>
<td  bgcolor="#CCCCCC" align="center"><strong>N:</strong></td>
<td  bgcolor="#CCCCCC" align="center"><strong>Solicitante:</strong></td>
<td  bgcolor="#CCCCCC" align="center"><strong>Zona:</strong></td>
<td  bgcolor="#CCCCCC" align="center"><strong>Desde:</strong></td>
<td bgcolor="#CCCCCC" align="center"><strong>Hasta:</strong></td>
<td colspan="2" bgcolor="#CCCCCC" align="center"></td>
</tr>
<?php 	
//------ MONTAJE DE LOS DATOS
$consultx = "SELECT viaticos_solicitudes.estatus, viaticos_solicitudes.id, viaticos_solicitudes.cedula, viaticos_solicitudes.total, CONCAT(rac.nombre,' ',rac.nombre2,' ',rac.apellido,' ',rac.apellido2) as  nombre, viaticos_solicitudes.numero, viaticos_solicitudes.fecha, viaticos_solicitudes.desde, 	viaticos_solicitudes.hasta,	viaticos_solicitudes.direccion,	viaticos_solicitudes.contralor, viaticos_solicitudes.zona as id_zona, a_zonas_viaticos.zona, a_zonas_viaticos.ciudades, a_direcciones.direccion , ciudad FROM viaticos_solicitudes, a_direcciones, a_zonas_viaticos, rac WHERE $filtro rac.cedula=viaticos_solicitudes.cedula AND viaticos_solicitudes.direccion = a_direcciones.id AND a_zonas_viaticos.id = viaticos_solicitudes.zona ORDER BY viaticos_solicitudes.id DESC;"; //echo $consultx;
$tablx = $_SESSION['conexionsql']->query($consultx);

while ($registro = $tablx->fetch_object())
	{
	$i++;
	?>
<tr >
<td><div align="center" ><?php echo ($i); ?></div></td>
<td ><div align="left" ><?php echo ($registro->nombre); ?></div></td>
<td ><div align="left" ><strong><?php echo ($registro->zona); ?> (<?php echo ($registro->ciudad); ?>)</strong></div></td>
<td ><div align="left" ><?php echo voltea_fecha($registro->desde); ?></div></td>
<td ><div align="left" ><?php echo voltea_fecha($registro->hasta); ?></div></td>
<td ><div align="center" >
<?php if ($registro->estatus<5) { ?>
	<a data-toggle="tooltip" title="Editar Solicitud"><button type="button" class="btn btn-outline-success light-3 btn-sm" data-toggle="modal" data-target="#modal_largo" onclick="editar(<?php echo ($registro->id); ?>);" data-keyboard="false"><i class="fas fa-edit"></i></button></a>

	<a data-toggle="tooltip" title="Detalle del Viatico"><button type="button" class="btn btn-outline-primary light-3 btn-sm" data-toggle="modal" data-target="#modal_largo" onclick="empleado('<?php echo ($registro->id); ?>','<?php echo ($registro->id_zona); ?>','<?php echo ($registro->contralor); ?>');" data-keyboard="false"><i class="fas fa-user-edit"></i></button></a>
	
	<a data-toggle="tooltip" title="Eliminar Solicitud"><button type="button" class="btn btn-outline-danger light-3 btn-sm"onclick="eliminar('<?php echo encriptar($registro->id); ?>');"><i class="fas fa-trash-alt"></i></button></a>
	
<?php } elseif ($registro->estatus>5) { ?>	 
		<a data-toggle="tooltip" title="Imprimir Solicitud"><button type="button" class="btn btn-outline-success light-3 btn-sm"onclick="imprimir('<?php echo encriptar($registro->id); ?>');"><i class="fas fa-print"></i></button></a>

	<?php  } ?>
	</div>
	</td>
<?php if ($registro->total>0 and $registro->estatus==0) { ?><td ><div align="center" ><button onclick="solicitar('<?php echo encriptar($registro->id); ?>','<?php echo ($registro->oficina); ?>');" type="button" class="btn btn-outline-success waves-effect"><i class="fas fa-cloud-upload-alt prefix grey-text mr-1"></i> Generar Solicitud</button></div> </td><?php 
 }
 ?>
<?php if ($registro->total>0 and $registro->estatus==55) { ?><td ><div align="center" ><button onclick="aprobar('<?php echo encriptar($registro->id); ?>','<?php echo ($registro->oficina); ?>');" type="button" class="btn btn-outline-success waves-effect"><i class="fa-regular fa-circle-check prefix grey-text mr-1"></i> Aprobar Solicitud</button></div> </td><?php 
 }
 ?>
</tr>
 <?php 
 }
 ?>
 <tr>
<td colspan="10" class="PieTabla">Contraloria del Estado Bolivariano de Gu&aacute;rico</td>
</tr>
</table>