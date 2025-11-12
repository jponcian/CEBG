<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');
//--------
$info = array();
$tipo = 'info';
//-------------	
$id_meta = decriptar($_GET['id_meta']);
$anno = ($_POST['oanno']);
$fecha = date('Y/m/d');
//-------------	
$consulta = "SELECT * FROM a_meses;"; 
$tabla = $_SESSION['conexionsql']->query($consulta);
while ($registro = $tabla->fetch_object())	
	{
	if ($_POST["c".($registro->nombre)]==$registro->nombre)
		{ 
		if (trim($_POST['txt_detalle'.($registro->nombre)])<>'')	// and trim($_POST['txt_cantidad'.($registro->nombre)])>0
			{			
			//-------------
			$detalle = ($_POST['txt_detalle'.($registro->nombre)]);
			$fecha = voltea_fecha($_POST['txt_fecha'.($registro->nombre)]);
			$cantidad = ($_POST['txt_cantidad'.($registro->nombre)]);
			//-------------
			$consulta_x = "INSERT INTO poa_metas_gestion (id_meta, mes_meta, mes_gestion, fecha_gestion, fecha_real, detalle, cantidad, usuario) VALUES ('$id_meta', '".$registro->nombre."', '".mayuscula($_SESSION['meses_anno'][abs(mes($fecha))])."', '$anno-".$registro->mes."-1', '$fecha', '$detalle', '$cantidad', '".$_SESSION['CEDULA_USUARIO']."');"; 
			$tablax = $_SESSION['conexionsql']->query($consulta_x);
			//-------------
			$consulta = "DROP TABLE IF EXISTS gestion;"; 
			$tablax = $_SESSION['conexionsql']->query($consulta);
			$consulta = "CREATE TEMPORARY TABLE gestion (SELECT SUM(cantidad) as cantidad, id_meta, fecha_gestion FROM poa_metas_gestion WHERE id_meta = $id_meta GROUP BY id_meta, fecha_gestion);";
			$tabla_x = $_SESSION['conexionsql']->query($consulta); //echo $consulta;
			$consulta = "UPDATE poa_metas_frecuencia, gestion SET poa_metas_frecuencia.cantidad_gestion = (gestion.cantidad) WHERE gestion.id_meta=poa_metas_frecuencia.id_meta AND gestion.fecha_gestion=poa_metas_frecuencia.fecha;";
			$tabla_x = $_SESSION['conexionsql']->query($consulta); //echo $consulta;
			//-------------
			$consulta = "UPDATE poa_metas_frecuencia SET estatus = 0 WHERE cantidad<=0;"; 
			$tablax = $_SESSION['conexionsql']->query($consulta);
			$consulta = "UPDATE poa_metas_frecuencia SET estatus = 5 WHERE cantidad>cantidad_gestion AND cantidad_gestion>0;";
			$tabla_x = $_SESSION['conexionsql']->query($consulta); //echo $consulta;
			$consulta = "UPDATE poa_metas_frecuencia SET estatus = 10 WHERE cantidad<=cantidad_gestion;";
			$tabla_x = $_SESSION['conexionsql']->query($consulta); //echo $consulta;
			}
		}
	}
//-------------
$mensaje = "Informacion Actualizada Exitosamente!";
//-------------
$info = array ("tipo"=>$tipo, "msg"=>$mensaje, "consulta"=>$consulta_x);
echo json_encode($info);
?>