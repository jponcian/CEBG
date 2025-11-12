<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');

if ($_SESSION['VERIFICADO'] != "SI") { 
header ("Location: ../validacion.php?opcion=val"); 
exit(); }

$acceso=16;
//------- VALIDACION ACCESO USUARIO
include_once "../validacion_usuario.php";
//-----------------------------------
?>
<table class="formateada" border="1" align="center" width="100%">
<tr>
<td class="TituloTablaP" height="41" colspan="10" align="center">Empleados </td>
</tr>
<tr>
<td  bgcolor="#CCCCCC" align="center"><strong>Item:</strong></td>
<td  bgcolor="#CCCCCC" align="center"><strong>Cedula:</strong></td>
<td  bgcolor="#CCCCCC" align="center"><strong>Empleado:</strong></td>
<td bgcolor="#CCCCCC" align="center"><strong>Nomina:</strong></td>
<td  bgcolor="#CCCCCC" align="center"><strong>Ubicacion:</strong></td>
<td  bgcolor="#CCCCCC" align="center"><strong>Cargo:</strong></td>
<td  bgcolor="#CCCCCC" align="center"><strong>Fecha Ingreso:</strong></td>
<td colspan="3" bgcolor="#CCCCCC" align="center"><strong>Opciones:</strong></td>
</tr>
<?php 	
$dato_buscar = trim($_GET['valor']);
$filtro = $_GET['tipo'];

switch ($filtro) {
    case 1:
        $filtrar = " AND rac LIKE '%$dato_buscar%'";
        break;
    case 2:
        $filtrar = " AND cedula LIKE '%$dato_buscar%'";
        break;
    case 3:
        $filtrar = " AND nombre LIKE '%$dato_buscar%'";
        break;
    case 4:
        $filtrar = "";
        break;		
    case 5:
        $filtrar = " AND ubicacion LIKE '%$dato_buscar%'";
        break;
    case 6:
        $filtrar = " AND cargo LIKE '%$dato_buscar%'";
        break;
}
//------ MONTAJE DE LOS DATOS
$consultx = "SELECT * FROM rac WHERE 1=1 $filtrar;";//.$_GET['valor'].";"; 
//echo $consultx;
$tablx = $_SESSION['conexionsql']->query($consultx);
while ($registro = $tablx->fetch_object())
	{
	$i++;
	if ($registro->nomina<>'005 JUBILADOS' and $registro->nomina<>'006 PENSIONADO' and $registro->nomina<>'0700 CUERPO DE BOMBEROS' and $registro->nomina<>'0800 EGRESADOS') { $constancia='si';} else { $constancia='no';}
	if ($registro->nomina<>'0700 CUERPO DE BOMBEROS' and $registro->nomina<>'0800 EGRESADOS') { $recibo='si'; $arc='si';} else { $recibo='no'; $arc='no';}
	?>
<tr id="fila<?php echo $registro->rac; ?>">
<td><div align="center" ><?php echo ($i); ?></div></td>
<td ><div align="center" ><?php echo ($registro->cedula); ?></div></td>
<td ><div align="left" ><?php echo ($registro->nombre); ?></div></td>
<td ><div align="left" ><?php echo ($registro->nomina); ?></div></td>
<td ><div align="left" ><?php echo ($registro->ubicacion); ?></div></td>
<td ><div align="left" ><?php echo ($registro->cargo); ?></div></td>
<td ><div align="center" ><?php echo voltea_fecha($registro->fecha_ingreso); ?></div></td>
<td valign="middle" align="center"><div><a href="" class="btn btn-outline-primary btn-rounded btn-sm font-weight-bold" onclick="expendiente('<?php echo encriptar($registro->cedula); ?>');" data-toggle="modal" data-target="#modal_largo" data-keyboard="false">Expendiente</a></div></td></tr>
 <?php 
 }
 ?>
  <tr>
<td colspan="10" class="PieTabla">Contraloria del Estado Bolivariano de Gu&aacute;rico</td>
</tr>
</table>
<script language="JavaScript">
//----------------
function expendiente(id){
	$('#modal_lg').html('<div align="center"><div class="spinner-border" role="status"></div><br><strong>Un momento, por favor...</strong></div>');
	$('#modal_lg').load('personal/6b_modal.php?id='+id);
	}
</script>