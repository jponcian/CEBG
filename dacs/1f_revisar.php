<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');
//----------------
$info = array();
$cedula = decriptar($_GET['cedula']);
$id = decriptar($_GET['id']);
$hora = date('H:i:s');
//---------
$id_atencion =0;

$consultx = "SELECT * FROM a_atencion_dacs ORDER BY id;"; 
$tablx = $_SESSION['conexionsql']->query($consultx);
while ($registro_x = $tablx->fetch_object())
	{
	if ($_POST['c'.$registro_x->id]=='si')
		{
		$id_atencion = $registro_x->id;
		$descripcion = $registro_x->descripcion;
		//--------
		$consultx1 = "INSERT INTO dacs_atencion_gestion (id_tickets, id_atencion, descripcion, usuario) VALUES ('$id', '$id_atencion', '$descripcion', '".$_SESSION['CEDULA_USUARIO']."');"; 
		$tablx1 = $_SESSION['conexionsql']->query($consultx1); //echo $consultx1;
		//--------
		}
	}
//---------
if ($id_atencion==0)		
	{
	//---------
	$mensaje = 'Debe Seleccionar al menos 1 Item!';
	$tipo='error';
	}
else
	{
	//---------
	$consultx = "UPDATE dacs_atencion SET estatus=2, fin='$hora' , usuario_fin='".$_SESSION['CEDULA_USUARIO']."' WHERE cedula='$cedula' AND estatus=0;"; 
	$tablx = $_SESSION['conexionsql']->query($consultx); //echo $consultx;
	//---------
	$consultx = "UPDATE asistencia_diaria_visita SET estatus=2 WHERE cedula='$cedula' AND estatus=1;"; 
	$tablx = $_SESSION['conexionsql']->query($consultx); //echo $consultx;
	//--------
	$mensaje = 'Procesado!';
	$tipo='success';
	}

//-------
$info = array ("tipo"=>$tipo, "msg"=>$mensaje);
echo json_encode($info);
?>