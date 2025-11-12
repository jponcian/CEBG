<?php
session_start();
include_once "../conexion.php";
include_once "../funciones/auxiliar_php.php";
//-----------
//$id = $_GET['id'];
//$nombre = $_GET['nombre'];
$origen = $_GET['origen'];
?>
<table class="table table-hover" width="100%" border="0" align="center">
<tr>
<td bgcolor="#99FFCC" align="center" colspan="5" height="4"><strong>Bienes por Reasignar</strong></td>
</tr>
<tr>
<td  bgcolor="#CCCCCC" align="center"><strong>Item:</strong></td>
<td  bgcolor="#CCCCCC" align="left"><strong>Numero:</strong></td>
<td  bgcolor="#CCCCCC" align="center"><strong>Descripci&oacute;n:</strong></td>
<td  bgcolor="#CCCCCC" align="left"><strong>Acci&oacute;n:</strong></td>
<td bgcolor="#CCCCCC" align="center"></td>
</tr>
<?php 	
//------ MONTAJE DE LOS DATOS
$consultx = "SELECT * FROM bn_bienes WHERE id_dependencia=$origen AND por_reasignar=1;"; 
//echo $consultx;
$tablx = $_SESSION['conexionsql']->query($consultx);

while ($registro = $tablx->fetch_object())
	{
	$i++;
?>
<tr >
<td><div align="center" ><?php echo ($i); ?></div></td>
<td ><div align="center" ><?php echo ($registro->numero_bien); ?></div></td>
<td ><div align="left" ><?php echo ($registro->descripcion_bien); ?></div></td>
<td ><div align="center" ><a data-toggle="tooltip" title="Eliminar"><button type="button" class="btn btn-outline-danger btn-sm" onclick="eliminar('<?php echo encriptar($registro->id_bien); ?>');"><i class="fas fa-trash-alt"></i></button></a></div></td>
</tr>
 <?php 
 }
 ?>
</table>
<div class="accordion" id="accordionExample">
  <div class="card">
    <div class="card-header" id="headingOne">
      <h2 class="mb-0">
        <button class="TituloTablaP btn-block text-center" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
          Bienes en Inventario (Click Aquí)
        </button>
      </h2>
    </div>
    <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordionExample">
      <div class="card-body">
<table class="table table-hover" width="100%" border="0" align="center">
<tr>
<td  bgcolor="#CCCCCC" align="center"><strong>Item:</strong></td>
<td  bgcolor="#CCCCCC" align="left"><strong>Numero:</strong></td>
<td  bgcolor="#CCCCCC" align="center"><strong>Descripci&oacute;n:</strong></td>
<td  bgcolor="#CCCCCC" align="left"><strong>Acci&oacute;n:</strong></td>
<td bgcolor="#CCCCCC" align="center"></td>
</tr>
<?php 	
//------ MONTAJE DE LOS DATOS
$consultx = "SELECT * FROM bn_bienes WHERE id_dependencia=$origen AND por_reasignar=0;"; 
//echo $consultx;
$tablx = $_SESSION['conexionsql']->query($consultx);

while ($registro = $tablx->fetch_object())
	{
	$i++;
?>
<tr >
<td><div align="center" ><?php echo ($i); ?></div></td>
<td ><div align="center" ><?php echo ($registro->numero_bien); ?></div></td>
<td ><div align="left" ><?php echo ($registro->descripcion_bien); ?></div></td>
<td ><div align="center" ><a data-toggle="tooltip" title="Reasignar"><button type="button" class="btn btn-outline-success btn-sm" onclick="reasignar('<?php echo encriptar($registro->id_bien); ?>');"><i class="fas fa-plus"></i></button></a></div></td>
</tr>
 <?php 
 }
 ?>
</table>
      </div>
    </div>
  </div>
</div>