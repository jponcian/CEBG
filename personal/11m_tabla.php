<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');
//-----------
$nomina = ($_GET['nomina']);
$ubicacion = ($_GET['ubicacion']);
$sexo = ($_GET['sexo']);
$hijo = ($_GET['hijo']);
$pago = ($_GET['pago']);

if ($nomina<>'0' and $nomina<>'-1')
	{	$nomina = " AND (nomina)='$nomina' ";	} else {	$nomina = "";	}
if ($ubicacion<>'0')
	{	
	//--------------------
	$consultx = "SELECT * FROM a_areas WHERE id=$ubicacion;";
	$tablx = $_SESSION['conexionsql']->query($consultx);
	$registro_x = $tablx->fetch_object();
	$ubicacion = $registro_x->area;
	//--------------------
	$ubicacion = " AND ubicacion='$ubicacion' ";	
	}
	else {	$ubicacion = "";	}
if ($sexo=='F' or $sexo=='M')
	{	$sexo = " AND sexo='$sexo' ";	} else {	$sexo = "";	}
if ($hijo==0)
	{	$hijo = "";	}
if ($hijo==1)
	{	$hijo = " AND hijos>0 ";	}
if ($hijo==2)
	{	$hijo = " AND hijos=0 ";	}
?>
<table width="100%" class="formateada" border="1">
<tr >
<th width="5%" ></th>
<th width="80%" >EMPLEADO</th>
<th width="10%" >CEDULA</th>
<th width="5%" ><button id="boton" data-toggle="tooltip" data-placement="top" title="Agregarlos Todos" type="button" class="btn btn-outline-success waves-effect" onclick="guardar_todos();" ><i class="fas fa-save prefix grey-text mr-1"></i></button></th>
</tr>
<?php
$i=0; $cedulas="'0'";
$consultx = "SELECT cedula FROM nomina WHERE tipo_pago='008' AND estatus=0;"; 
$tablx = $_SESSION['conexionsql']->query($consultx);
while ($registro_x = $tablx->fetch_object())
{
	$cedulas = $cedulas.','."'".$registro_x->cedula."'";
}
//--------------------
if ($pago>0)
	{$consultx = "SELECT rac.*, CONCAT(rac.nombre,' ',rac.nombre2,' ',rac.apellido,' ',rac.apellido2) as  funcionario FROM	nomina,	rac WHERE rac.cedula = nomina.cedula AND nomina.id_solicitud = $pago AND nomina.cedula NOT IN ($cedulas);"; }
else
	{
	$consultx = "SELECT *, CONCAT(rac.nombre,' ',rac.nombre2,' ',rac.apellido,' ',rac.apellido2) as  funcionario FROM rac WHERE suspendido=0 AND temporal=0 AND (nomina)<>'EGRESADOS' $nomina $ubicacion $sexo $hijo AND cedula NOT IN ($cedulas) ORDER BY nombre;"; //
	}

//echo $consultx;
$_SESSION['consulta'] = $consultx;
$tablx = $_SESSION['conexionsql']->query($consultx);
while ($registro_x = $tablx->fetch_object())
//-------------
{
$i++;
?>
<tr>
<td align="center"><strong><?php echo $i;?></strong></td>
<td valign="middle" ><h5><strong><?php  echo $registro_x->funcionario;?></strong></h5></td>
<td valign="middle" ><h5><strong><?php  echo $registro_x->cedula;?></strong></h5></td>
<td align="center" ><button type="button" id="check_<?php  echo $registro_x->rac;?>" class="btn btn-outline-info waves-effect" onclick="guardar_detalle2('<?php echo $registro_x->rac;?>','check_<?php echo $registro_x->rac;?>')" ><i class="fas fa-save prefix grey-text mr-1"></i></button></td>
<!--<td align="center" ><input class="form-control" name="check_<?php  echo $registro_x->rac;?>" type="checkbox" value="<?php  //echo $registro_x->rac;?>" /></td>-->
</tr>
<?php
}
?>
</table><?php //echo $_SESSION['consulta']; ?>