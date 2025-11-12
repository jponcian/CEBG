<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');

if ($_SESSION['VERIFICADO'] != "SI") { 
header ("Location: ../validacion.php?opcion=val"); 
exit(); }

$acceso=77;
//------- VALIDACION ACCESO USUARIO
include_once "../validacion_usuario.php";
//-----------------------------------
?>
<table class="formateada" border="1" align="center" width="100%">
<tr>
<td class="TituloTablaP" height="41" colspan="10" align="center">Chequeras Registradas</td>
</tr>
<tr>
<td  bgcolor="#CCCCCC" align="center"><strong>Item</strong></td>
<td  bgcolor="#CCCCCC" align="center"><strong>Banco</strong></td>
<td  bgcolor="#CCCCCC" align="center"><strong>Cuenta</strong></td>
<td bgcolor="#CCCCCC" align="center"><strong>Descripcion</strong></td>
<td bgcolor="#CCCCCC" align="center"><strong>Chequera</strong></td>
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
$consultx = "SELECT a_cuentas_chequera.id, a_cuentas.banco, a_cuentas.cuenta, a_cuentas.descripcion, a_cuentas_chequera.chequera FROM	a_cuentas_chequera,	a_cuentas WHERE	a_cuentas_chequera.banco = a_cuentas.banco 	AND a_cuentas_chequera.cuenta = a_cuentas.cuenta ORDER BY a_cuentas.banco, 	a_cuentas.cuenta";//$filtrar.$_GET['valor'].";"; 
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
<td ><div align="left" ><?php echo ($registro->chequera); ?></div></td>
<td ><div align="center" ><a data-toggle="tooltip" title="Agregar o Eliminar Cheques"><button data-toggle="modal" data-target="#modal_normal" data-keyboard="false" type="button" class="btn btn-outline-info btn-sm" onclick="cheques('<?php echo ($registro->id); ?>');">Cheques</button></a></div></td>
<td ><div align="center" ><a data-toggle="tooltip" title="Eliminar"><button type="button" class="btn btn-outline-danger btn-sm" onclick="eliminar('<?php echo ($registro->id); ?>');"><i class="fas fa-trash-alt"></i></button></a></div></tr>
 <?php 
 }
 ?>
  <tr>
<td colspan="10" class="PieTabla">Contraloria del Estado Bolivariano de Gu&aacute;rico</td>
</tr>
</table>