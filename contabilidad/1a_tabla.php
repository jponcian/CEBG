<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');

if ($_SESSION['VERIFICADO'] != "SI") { 
header ("Location: ../validacion.php?opcion=val"); 
exit(); }

$acceso=76;
//------- VALIDACION ACCESO USUARIO
include_once "../validacion_usuario.php";
//-----------------------------------
?>
<table class="formateada" border="1" align="center" width="100%">
<tr>
<td class="TituloTablaP" height="41" colspan="10" align="center">Cuentas Registradas</td>
</tr>
<tr>
<td  bgcolor="#CCCCCC" align="center"><strong>Item</strong></td>
<td  bgcolor="#CCCCCC" align="center"><strong>Banco</strong></td>
<td  bgcolor="#CCCCCC" align="center"><strong>Cuenta</strong></td>
<td bgcolor="#CCCCCC" align="center"><strong>Descripcion</strong></td>
<td bgcolor="#CCCCCC" align="center"><strong>Estatus</strong></td>
<td bgcolor="#CCCCCC" colspan="2" align="center"><strong>Opciones</strong></td>
</tr>
<?php 	
//$dato_buscar = trim($_GET['valor']);
//$filtro = $_GET['tipo'];
//
//switch ($filtro) {
//    case 1:
//        $filtrar = " AND rac LIKE '%$dato_buscar%'";
//        break;
//    case 2:
//        $filtrar = " AND cedula LIKE '%$dato_buscar%'";
//        break;
//    case 3:
//        $filtrar = " AND nombre LIKE '%$dato_buscar%'";
//        break;
//    case 4:
//        $filtrar = "";
//        break;		
//    case 5:
//        $filtrar = " AND ubicacion LIKE '%$dato_buscar%'";
//        break;
//    case 6:
//        $filtrar = " AND cargo LIKE '%$dato_buscar%'";
//        break;
//}
//------ MONTAJE DE LOS DATOS
$consultx = "SELECT * FROM a_cuentas WHERE 1=1 ;";//$filtrar.$_GET['valor'].";"; 
//echo $consultx;
$tablx = $_SESSION['conexionsql']->query($consultx);
while ($registro = $tablx->fetch_object())
	{
	$i++;
	//list($banco,$cuenta)=explode(' ', $registro->codigo);
	?>
<tr id="fila<?php echo $registro->id; ?>">
<td><div align="center" ><?php echo ($i); ?></div></td>
<td ><div align="left" ><?php echo ($registro->banco); ?></div></td>
<td ><div align="center" ><?php echo ($registro->cuenta); ?></div></td>
<td ><div align="left" ><?php echo ($registro->descripcion); ?></div></td>
<td ><div align="center" ><?php echo $_SESSION['estatus'][$registro->estatus]; ?></div></td>
<td ><div align="center" ><a data-toggle="tooltip" title="Activar o Desactivar Cuenta Bancaria"><button type="button" class="btn btn-outline-<?php if ($registro->estatus==0) echo 'info'; else echo 'success'; ?> btn-sm" onclick="activar('<?php echo ($registro->id); ?>','<?php echo $_SESSION['activar'][$registro->estatus]; ?>');"><?php echo $_SESSION['boton'][$registro->estatus]; ?></button></a></div></td>
<td ><div align="center" ><a data-toggle="tooltip" title="Eliminar"><button type="button" class="btn btn-outline-danger btn-sm" onclick="eliminar('<?php echo ($registro->id); ?>');"><i class="fas fa-trash-alt"></i></button></a></div></td>
</tr>
 <?php 
 }
 ?>
  <tr>
<td colspan="10" class="PieTabla">Contraloria del Estado Bolivariano de Gu&aacute;rico</td>
</tr>
</table>