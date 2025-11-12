<?php
session_start();
include_once "../conexion.php";
include_once "../funciones/auxiliar_php.php";
//--------
$info = array();
$tipo = 'info';
//-------------
$guardado=0;
$direccion=0;
$area=0;

$consultax = "SELECT id, direccion, area FROM viaticos_solicitudes WHERE estatus=7 ORDER BY id DESC;";
$tablax = $_SESSION['conexionsql']->query($consultax);
while ($registro = $tablax->fetch_object())
	{
	if ($_POST['osel'.$registro->id]==$registro->id)
		{
		//------------
		$memo = memo_viatico($registro->direccion);	
		$direccion = ($registro->direccion);	
		$area = ($registro->area);	
		$fecha = date('Y/m/d');
		$guardado = 1;
		}
	}

if ($guardado>0)
	{	
	//-------------	
	$consultx = "INSERT INTO viaticos_memo(direccion, area, numero, fecha, usuario) VALUES ('$direccion', '$area', '$memo', '$fecha', '".$_SESSION['CEDULA_USUARIO']."')";
	$tablx = $_SESSION['conexionsql']->query($consultx);
	//-------------	
		$consultax = "SELECT LAST_INSERT_ID() as id;";
		$tablax = $_SESSION['conexionsql']->query($consultax);	
		$registrox = $tablax->fetch_object();
		$id = $registrox->id;
	//-------------	
	$consultax = "SELECT id FROM viaticos_solicitudes WHERE estatus=7 ORDER BY id;";
	$tablax = $_SESSION['conexionsql']->query($consultax);
	while ($registro = $tablax->fetch_object())
		{
		if ($_POST['osel'.$registro->id]==$registro->id)
			{
			//-------------
			$consultx = "UPDATE viaticos_solicitudes SET id_memo=$id, estatus = 10, usuario='".$_SESSION['CEDULA_USUARIO']."' WHERE id = ".$registro->id.";"; //echo $consultx;
			$tablx = $_SESSION['conexionsql']->query($consultx);
			}
		}
	//-------------	
	$mensaje = "Memo Generada Exitosamente!";
	}
else
	{
	//-------------	
	$mensaje = "No ha seleccionado ninguna Solicitud!"; $tipo = 'alerta';
	}
//-------------
$info = array ("tipo"=>$tipo, "msg"=>$mensaje, "orden"=>$orden, "id"=>encriptar($id));
echo json_encode($info);
?>