<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');

if ($_SESSION['VERIFICADO'] != "SI") { 
header ("Location: ../validacion.php?opcion=val"); 
exit(); }

$acceso=110;
//------- VALIDACION ACCESO USUARIO
include_once "../validacion_usuario.php";
//-----------------------------------
?>
<table class="formateada table" border="1" align="center" width="70%">
    <tr>
        <td class="TituloTablaP" height="41" colspan="10" align="center">Días Feriados</td>
    </tr>
    <tr>
        <td bgcolor="#CCCCCC" align="center"><strong>Item</strong></td>
        <td bgcolor="#CCCCCC" align="center"><strong>Fecha</strong></td>
        <td bgcolor="#CCCCCC" align="center"><strong>Opciones</strong></td>
    </tr>
    <?php 	
//------ MONTAJE DE LOS DATOS
$consultx = "SELECT * FROM rrhh_dias_feriados WHERE 1=1 ORDER BY fecha DESC;";//$filtrar.$_GET['valor'].";"; 
//echo $consultx;
$tablx = $_SESSION['conexionsql']->query($consultx);
while ($registro = $tablx->fetch_object())
	{
	$i++;
	//list($banco,$cuenta)=explode(' ', $registro->codigo);
	?>
    <tr id="fila<?php echo $registro->id; ?>">
        <td>
            <div align="center">
                <?php echo ($i); ?>
            </div>
        </td>
        <td>
            <div align="center">
                <?php echo voltea_fecha($registro->fecha); ?>
            </div>
        </td>
        <td>
            <div align="center"><a data-toggle="tooltip" title="Eliminar"><button type="button" class="btn btn-outline-danger btn-sm" onclick="eliminar('<?php echo ($registro->id); ?>');"><i class="fas fa-trash-alt"></i></button></a></div>
        </td>
    </tr>
    <?php 
 }
 ?>
    <tr>
        <td colspan="10" class="PieTabla">Contraloria del Estado Bolivariano de Gu&aacute;rico</td>
    </tr>
</table>