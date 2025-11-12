<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');
$_SESSION['conexionsql']->query("SET NAMES 'utf8'");
//-----------
if ($_GET['tipo']=='1')	
	{
	$filtro = " revisado=0 AND ";	$_SESSION['titulo'] = 'POR VERIFICAR';
	} 
elseif ($_GET['tipo']=='2')	 
	{
	$filtro = " revisado=1 AND "; $_SESSION['titulo'] = 'VERIFICADOS';	
	}
	elseif ($_GET['tipo']=='3')	 
		{	
		$filtro = " ";	 $_SESSION['titulo'] = '';
		}
		else {$filtro = "";}
if ($_GET['id']=='0')	
	{
	$filtro2 = " ";	
	} 
elseif ($_GET['id']>0)
	{
	$filtro2 = " id_dependencia=".$_GET['id']." AND ";	
	}
?>
<table class="table table-hover" width="100%" border="0" align="center">
<tr >
<td colspan="10" align="center"><button type="button" id="botonb" class="btn btn-lg btn-block btn-info" onClick="rep();"><i class="fas fa-search mr-2"></i>Ver Pdf</button></td>
</tr><tr>
<td class="TituloTablaP" height="41" colspan="10" align="center">Bienes en Sistema</td>
</tr>
<tr >
<td  bgcolor="#CCCCCC" align="center"><strong>N:</strong></td>
<!--<td  bgcolor="#CCCCCC" align="center"><strong>Dependencia</strong></td>-->
<td  bgcolor="#CCCCCC" align="center"><strong>N&deg; Bien</strong></td>
<td  bgcolor="#CCCCCC" align="left"><strong>Descripcion</strong></td>
<td  bgcolor="#CCCCCC" align="center"><strong>Estatus</strong></td>
</tr>
<?php 	
//------ MONTAJE DE LOS DATOS
$consultx = "SELECT bn_bienes.*, bn_dependencias.division FROM bn_bienes, bn_dependencias WHERE $filtro $filtro2 bn_dependencias.id=bn_bienes.id_dependencia ORDER BY id_direccion, descripcion_bien;";
//echo $consultx;
$_SESSION['consulta'] = $consultx;
$tablx = $_SESSION['conexionsql']->query($consultx);

while ($registro = $tablx->fetch_object())
	{
	$i++;
	?>
<tr class="texto12">
<td><div align="center" ><?php echo ($i); ?></div></td>
<!--<td ><div align="left" ><?php //echo ($registro->division); ?></div></td>-->
<td ><div align="center" ><strong><?php echo ($registro->numero_bien); ?></strong></div></td>
<td ><div align="left" ><?php echo ucfirst(minuscula($registro->descripcion_bien)); ?></div></td>
<td align="center"><div><button type="button" class="badge badge-<?php if ($registro->revisado==1) {echo 'success';} else {echo 'danger';} ?>" onclick="cambiar('<?php echo ($registro->id_bien); ?>','<?php echo ($registro->revisado); ?>')" ><i class="<?php if ($registro->revisado==1) {echo 'far fa-check-circle';} else {echo 'far fa-times-circle';} ?>"></i> <?php if ($registro->revisado==1) {echo 'Verificado';} else {echo 'Pendiente';} ?></button></div>
</td></tr>
 <?php 
 }
 ?>
 <tr>
<td colspan="10" class="PieTabla">Contraloria del Estado Bolivariano de Gu&aacute;rico</td>
</tr>
</table>