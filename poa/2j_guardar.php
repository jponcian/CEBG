<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');
//--------
$info = array();
$tipo = 'info';
//-------------	
$id_meta = decriptar($_GET['id_meta']);
$fecha = date('Y/m/d');
//-------------	
$consulta = "SELECT * FROM a_meses;"; 
$tabla = $_SESSION['conexionsql']->query($consulta);
while ($registro = $tabla->fetch_object())	
	{
	if ($_POST["c".($registro->nombre)]==$registro->nombre)
		{ 
		if (trim($_POST['txt_detalle'.($registro->nombre)])<>'' )	//and trim($_POST['txt_cantidad'.($registro->nombre)])>0
			{			
			$consulta_x = "SELECT * FROM poa_metas_frecuencia WHERE mes = '".$registro->nombre."' AND id_meta = '$id_meta';";
			$tabla_x = $_SESSION['conexionsql']->query($consulta_x);
			if ($tabla_x->num_rows>0)	
				{
				//-------------
				$detalle = ($_POST['txt_detalle'.($registro->nombre)]);
				$cantidad = ($_POST['txt_cantidad'.($registro->nombre)]);
				//-------------
				$consultax = "UPDATE poa_metas_frecuencia SET detalle = '$detalle', cantidad = '$cantidad' WHERE mes = '".$registro->nombre."' AND id_meta = '$id_meta';"; 
				$tablax = $_SESSION['conexionsql']->query($consultax);
				}
			else
				{
				//-------------
				$detalle = ($_POST['txt_detalle'.($registro->nombre)]);
				$cantidad = ($_POST['txt_cantidad'.($registro->nombre)]);
				//-------------
				$consultax = "INSERT INTO poa_metas_frecuencia (id_meta, mes, fecha, detalle, cantidad, usuario) VALUES ('$id_meta', '".$registro->nombre."', '2023-".$registro->mes."-1', '$detalle', '$cantidad', '".$_SESSION['CEDULA_USUARIO']."');"; 
				$tablax = $_SESSION['conexionsql']->query($consultax);
				}
			}		
		}
	else
		{
		$consulta_x = "DELETE FROM poa_metas_frecuencia WHERE mes = '".$registro->nombre."' AND id_meta = '$id_meta';"; 
		$tabla_x = $_SESSION['conexionsql']->query($consulta_x);
		}
	}

//-------------
$mensaje = "Informacion Actualizada Exitosamente!";
//-------------
$info = array ("tipo"=>$tipo, "msg"=>$mensaje, "consulta"=>$consulta_x);
echo json_encode($info);
?>