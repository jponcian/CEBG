<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');

if ($_SESSION['VERIFICADO'] != "SI") { 
header ("Location: ../validacion.php?opcion=val"); 
exit(); }

$acceso=64;
//------- VALIDACION ACCESO USUARIO
include_once "../validacion_usuario.php";
//-----------------------------------
?>
<table class="formateada datatabla" align="center" width="100%">
<thead>
<!--
    <tr>
        <td class="TituloTablaP" height="41" colspan="10" align="center">Motivos de Atención Registrados</td>
    </tr>
-->
    <tr>
        <th bgcolor="#CCCCCC" align="center"><strong>Item</strong></th>
        <th bgcolor="#CCCCCC" align="center"><strong>Descripcion</strong></th>
        <th bgcolor="#CCCCCC" align="center"><strong>Opciones</strong></th>
	</tr>
</thead>
<tbody><?php 	
//------ MONTAJE DE LOS DATOS
$consultx = "SELECT * FROM a_atencion_dacs WHERE 1=1 ORDER BY descripcion;";//$filtrar.$_GET['valor'].";"; 
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
            <div align="left">
                <?php echo ($registro->descripcion); ?>
            </div>
        </td>
        <td>
            <div align="center"><a data-toggle="tooltip" title="Eliminar"><button type="button" class="btn btn-outline-danger btn-sm" onclick="eliminar('<?php echo ($registro->id); ?>');"><i class="fas fa-trash-alt"></i></button></a></div>
        </td>
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
<script language="JavaScript" src="funciones/datatable.js"></script>