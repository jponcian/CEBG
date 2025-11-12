<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');
//--------
$info = array();
$tipo = 'info';
//-------------
$nomina=0; $patria=0;
$consultx = "SELECT id, total, patria FROM nomina_solicitudes WHERE 1=1 AND estatus=5;";
$tablx = $_SESSION['conexionsql']->query($consultx);
while ($registro = $tablx->fetch_object())
	{
	if ($_POST['osel'.$registro->id]==$registro->id)
		{
			if ($registro->patria==0)
				{
				$nomina = $nomina + $registro->total;
				}
			else
				{
				$patria = $patria + $registro->total;
				}
		}
	}
//-------------	
if ($patria>0 or $nomina>0)
{
	if ($patria>0){
		//-------------	
		$consultx = "INSERT INTO ordenes_pago(tipo_solicitud, numero, fecha, total, estatus, usuario) VALUES ('PATRIA', ".orden_sig_patria().", '".date('Y/m/d')."', 0, 0, '".$_SESSION['CEDULA_USUARIO']."')";
		//-------------	
		$consultax = "SELECT id, total, descripcion, nomina, tipo_pago, desde, hasta, id_cont FROM nomina_solicitudes WHERE patria=1 AND estatus=5 ORDER BY tipo_pago, nomina, desde;";
		//-------------	
		$tipoO='PATRIA';
			} 
	else {
		$consultx = "INSERT INTO ordenes_pago(tipo_solicitud, numero, fecha, total, estatus, usuario) VALUES ('NOMINA', ".orden_sig().", '".date('Y/m/d')."', 0, 0, '".$_SESSION['CEDULA_USUARIO']."')";
		//-------------	
		$consultax = "SELECT id, total, descripcion, nomina, tipo_pago, desde, hasta, id_cont FROM nomina_solicitudes WHERE patria=0 AND estatus=5 ORDER BY tipo_pago, nomina, desde;";
		//-------------	
		$tipoO='NOMINA';
		}
	$tablx = $_SESSION['conexionsql']->query($consultx);
	//-------------	
	$consultax1 = "SELECT LAST_INSERT_ID() as id;";
	$tablax1 = $_SESSION['conexionsql']->query($consultax1);	
	$registrox1 = $tablax1->fetch_object();
	$id = $registrox1->id;
	//-------------	
	$tablax = $_SESSION['conexionsql']->query($consultax);
	while ($registro = $tablax->fetch_object())
	{
	if ($_POST['osel'.$registro->id]==$registro->id)
		{
		$tipo_pago = $registro->tipo_pago;
		$desde = $registro->desde;
		$hasta = $registro->hasta;
		$id_cont = $registro->id_cont;
//		if ($tipo_pago=='007')
//			{	$id_cont = $registro->id_cont;	}
//		else
//			{	$id_cont = 1000;	}		
		//-------------	
		$consultx = "UPDATE nomina_solicitudes SET estatus = 7, id_orden_pago=$id WHERE id = ".$registro->id.";"; //echo $consultx;
		$tablx = $_SESSION['conexionsql']->query($consultx);
		//-------------
		if ($tipo_pago=='001')
			{
			$quincena = 'PRIMERA QUINCENA DE '.strtoupper($_SESSION['meses_anno'][intval(mes($desde))]).' '.anno($desde);
			if ((dia($desde)=='1' or dia($desde)=='01') and dia($hasta)=='15')
				{	$quincena = 'PRIMERA QUINCENA DE '.strtoupper($_SESSION['meses_anno'][intval(mes($desde))]).' '.anno($desde);	}
			if (dia($desde)=='16' and intval(dia($hasta))>=28)
				{	$quincena = 'SEGUNDA QUINCENA DE '.strtoupper($_SESSION['meses_anno'][intval(mes($desde))]).' '.anno($desde);	}
			}
		if ($tipo_pago=='002')
			{
			$quincena = 'CESTATICKETS DE '.strtoupper($_SESSION['meses_anno'][intval(mes($desde))]).' '.anno($desde);
			}
		if ($tipo_pago=='003')
			{
			$quincena = 'BONO VACACIONAL DE '.strtoupper($_SESSION['meses_anno'][intval(mes($desde))]).' '.anno($desde);
			}
		
		if ($tipo_pago=='005')
			{
			$quincena = 'PRIMAS DE '.strtoupper($_SESSION['meses_anno'][intval(mes($desde))]).' '.anno($desde);
			}
		
		//-------------
		$consultx = "UPDATE ordenes_pago SET id_contribuyente=$id_cont, descripcion=CONCAT(ordenes_pago.descripcion,' -',' PAGO DE ".$quincena.' NOMINA '.$registro->nomina."') WHERE id=$id;";
		$tablx = $_SESSION['conexionsql']->query($consultx); //echo $consultx;
		//-------------	
		}
	}
	//-------------	
	$consultax = "CALL actualizar_orden_pago_nomina();"; //echo $consultx ;
	$tablax = $_SESSION['conexionsql']->query($consultax);
	//-------------	
	$consultx = "UPDATE ordenes_pago SET total=(SELECT sum(total) from nomina_solicitudes WHERE id_orden_pago=$id), asignaciones=(SELECT sum(asignaciones) from nomina_solicitudes WHERE id_orden_pago=$id), descuentos=(SELECT sum(descuentos) from nomina_solicitudes WHERE id_orden_pago=$id) WHERE id=$id;";
	$tablx = $_SESSION['conexionsql']->query($consultx);
//-------------	
$mensaje = "Orden de Pago Generada Exitosamente!";
}
//elseif ($nomina>0)
//	{
//	//-------------	
//	$consulta= "SELECT id, total, descripcion, nomina, tipo_pago, desde, hasta, id_cont, descuentos FROM nomina_solicitudes WHERE estatus=5 ORDER BY tipo_pago, nomina, desde;";
//	$tabla = $_SESSION['conexionsql']->query($consulta);
//	while ($registro = $tabla->fetch_object())
//		{
//		$descuentos = 0;
//		//$descuentos = $registro->descuentos;
//		if ($_POST['osel'.$registro->id]==$registro->id)
//			{
//			$consulta_nomina = "SELECT nomina.id, nomina.cedula, nomina.anno, nomina.desde, nomina.hasta, nomina.tipo_pago, CONCAT(rac.nombre,' ',rac.nombre2,' ',rac.apellido,' ',rac.apellido2) as  nombre FROM nomina, rac WHERE nomina.cedula = rac.cedula AND id_solicitud=".$registro->id." ORDER BY cedula;";
//			$tabla_nomina = $_SESSION['conexionsql']->query($consulta_nomina);
//			while ($registro_nomina = $tabla_nomina->fetch_object())
//				{
//				$id_nomina = $registro_nomina->id;
//				$cedula = $registro_nomina->cedula;
//				$nombre = $registro_nomina->nombre;
//				$id_cont = id_contribuyente($cedula);
//				$anno = $registro_nomina->anno;
//				$desde = $registro_nomina->desde;
//				$hasta = $registro_nomina->hasta;
//				$tipo_pago = $registro_nomina->tipo_pago;
//				$total = 0;
//				$numero = num_orden_compra($anno,'F');
//				//-------------
//				if ($tipo_pago=='005')
//					{
//					$quincena = 'PAGO DE PRIMAS DE '.strtoupper($_SESSION['meses_anno'][intval(mes($desde))]).' '.anno($desde);
//					}
//				//-----------POR SI NO EXISTE EL CONTRIBUYENTE
////				if (abs(trim($id_cont))<1)
////					{
////					$consultai = "INSERT INTO contribuyente(rif, nombre, ciudad, estado, zona, representante, ced_representante) VALUES ('$cedula', '$nombre', 1, 12, 1, '$nombre', '$cedula');";
////					$tablai = $_SESSION['conexionsql']->query($consultai);
////					//-------------	
////					$consultax = "SELECT LAST_INSERT_ID() as id;";
////					$tablax = $_SESSION['conexionsql']->query($consultax);	
////					$registrox = $tablax->fetch_object();
////					$id_cont = $registrox->id;
////					}				
//				//-------------------
//				$consulta_asig = "SELECT nomina_asignaciones.categoria, nomina_asignaciones.partida, nomina_asignaciones.total_asignacion, a_partidas.descripcion FROM nomina_asignaciones , a_partidas WHERE nomina_asignaciones.partida = a_partidas.codigo AND nomina_asignaciones.id_nomina=$id_nomina;"; //echo $consulta_asig;
//				$tabla_asig = $_SESSION['conexionsql']->query($consulta_asig);
//				while ($registro_asig = $tabla_asig->fetch_object())
//					{
//					$categoria = $registro_asig->categoria;
//					$partida = $registro_asig->partida;
//					$descripcion = $registro_asig->descripcion;
//					$monto = $registro_asig->total_asignacion;
//					//----------------
//					$consultx = "INSERT INTO orden(tipo_orden, id_contribuyente, rif, fecha, anno, concepto, numero, categoria, partida, cantidad, descripcion, precio_uni, total, estatus, usuario) VALUES (4, '$id_cont', '$cedula', '".date('Y/m/d')."', ".$anno.", '$concepto', '0', '$categoria', '$partida', '1', '$descripcion', '$monto', '$monto', '0', '".$_SESSION['CEDULA_USUARIO']."');";
//					$tablx = $_SESSION['conexionsql']->query($consultx);
//					//----------------
//					$total = $total + $monto;
//					}
//				//-------------	
//				$consultx = "INSERT INTO ordenes_pago(descuentos, id_contribuyente, tipo_solicitud, numero, fecha, descripcion, asignaciones, total, estatus, usuario) VALUES ('$descuentos','$id_cont', 'NOMINA', '".orden_sig_nomina_m()."', '".date('Y/m/d')."', '$concepto', '$total', '$total', '0', '".$_SESSION['CEDULA_USUARIO']."')";//echo $consultx;
//				$tablx = $_SESSION['conexionsql']->query($consultx);
//				//-------------	    
//					$consultax = "SELECT LAST_INSERT_ID() as id;";
//					$tablax = $_SESSION['conexionsql']->query($consultax);	
//					$registrox = $tablax->fetch_object();
//					$id_pago = $registrox->id;
//				//-------------	
//				$consultx = "INSERT INTO orden_solicitudes(descuentos, id_orden_pago, id_contribuyente, tipo_orden, numero, fecha_sol, anno, fecha, descripcion, asignaciones, total, estatus, usuario) VALUES ('$descuentos', '$id_pago', '$id_cont', 'F', $numero, '".date('Y/m/d')."', '$anno', '".date('Y/m/d')."', '$concepto', '$total', '$total', 7, '".$_SESSION['CEDULA_USUARIO']."');"; //echo $consultx;
//				$tablx = $_SESSION['conexionsql']->query($consultx);
//				//-------------	
//					$consultax = "SELECT LAST_INSERT_ID() as id;";
//					$tablax = $_SESSION['conexionsql']->query($consultax);	
//					$registrox = $tablax->fetch_object();
//					$id = $registrox->id;
//				//-------------	
//				$consultx = "UPDATE orden SET estatus = 7, numero = $numero, id_solicitud = $id, usuario_solicitud = '".$_SESSION['CEDULA_USUARIO']."', usuario = '".$_SESSION['CEDULA_USUARIO']."' WHERE id_contribuyente=$id_cont AND tipo_orden=4 AND estatus=0;";
//				$tablx = $_SESSION['conexionsql']->query($consultx);
//				//-------------					
//				$consultx = "UPDATE nomina_solicitudes SET estatus = 7, id_orden_pago=$id_pago WHERE id = ".$registro->id.";"; 
//				$tablx = $_SESSION['conexionsql']->query($consultx);
//				}
//			}
//		}
//	//-------------	
//	$consultax = "CALL actualizar_orden_pago_nomina();"; //echo $consultx ;
//	$tablax = $_SESSION['conexionsql']->query($consultax);
//	//-------------	
//	$mensaje = "Orden de Pago Generada Exitosamente!";
//	}
else
	{
	$mensaje = "No ha seleccionado ninguna nomina!"; $tipo = 'alerta';
	}
//-------------
$info = array ("tipo"=>$tipo, "msg"=>$mensaje, "id"=>encriptar($id), "orden"=>$tipoO, "consulta"=>$consultx);
echo json_encode($info);
?>