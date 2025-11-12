<?php
session_start();
include_once "../conexion.php";
include_once "../funciones/auxiliar_php.php";
//--------
$info = array();
$tipo = 'info';
//-------------	
//$tablax = $_SESSION['conexionsql']->query("UPDATE a_actualizacion SET nomina = '".date("Y-m-d H:i:00")."';");
$_SESSION['prima_hijos'] = prima_hijos();
$_SESSION['prima_hogar'] = prima_hogar();
//-------------
$consultax = "SELECT id FROM rac_carga WHERE parentesco='Hijo(a)' AND DATE(DATE_ADD(fecha_nac, INTERVAL 18 YEAR))<=CURDATE();";
$tablax = $_SESSION['conexionsql']->query($consultax);
if ($tablax->num_rows>0)	
	{
	$consultx = "CALL actualizar_hijos();";
	$tablax = $_SESSION['conexionsql']->query($consultx);
	}
//-------------
$consultam = "SELECT * FROM a_nomina WHERE codigo = '".$_POST['ONOMINA']."' LIMIT 1;"; //echo $consultam;
$tablam = $_SESSION['conexionsql']->query($consultam);
while ($registrom = $tablam->fetch_object())
	{
	$fecha = voltea_fecha($_POST['OFECHA']);
	$mes = mes(voltea_fecha($_POST['OFECHA']));
	$anno = anno(voltea_fecha($_POST['OFECHA']));
	$desde = $anno.'-'.$mes.'-'.$_POST['OQUINCENA'];
	$desde_m = $anno.'-'.$mes.'-01';
	if ($_POST['OQUINCENA']=='01')
		{ 	$hasta= $anno.'-'.$mes.'-15'; 	} else 	{ 	$hasta= baja_dia(sube_mes(voltea_fecha($_POST['OFECHA']))); 	}
	if (substr($hasta,8,2)=='31')
		{$hasta = $anno.'-'.$mes.'-30';}
	
	$tipo_pago = '001';
	//-----------
	$codigo_nomina = $registrom->codigo;
	$nomina = $registrom->nomina;
	$partida = $registrom->partida;
	$partida_hijos = $registrom->hijos;
	$partida_profesion = $registrom->profesion;
	$partida_antiguedad = $registrom->antiguedad;
	//$partida_bono = $registrom->bono;
	$partida_tickets = $registrom->tickets;
	$partida_vacaciones = $registrom->vacaciones;
	$partida_sso = $registrom->seguro;
	$partida_lph = $registrom->leypolitica;
	$partida_pfo = $registrom->paro;
	$partida_fej = $registrom->jubilaciones;
	$categoria_tickets = $registrom->cat_tickets;
	$categoria = $registrom->cat_asignaciones;
	$categoria_descuentos = $registrom->cat_descuentos;
	//$categoria_jubilados = $registrom->cat_jubilados;
	//------------ POR SI ES JUBILADO
	//if ($nomina=='005 JUBILADOS' or $nomina=='006 PENSIONADO') 
	//	{ 	$categoria = $categoria_jubilados;		}
	
	//------------- SI LA NOMINA YA FUE SOLICITADA
	$consultx = "SELECT id FROM nomina WHERE estatus>0 AND nomina='$nomina' AND hasta='$hasta' AND tipo_pago='001' and lote=1;";
	$tablx = $_SESSION['conexionsql']->query($consultx);	
	if ($tablx->num_rows>0)
		{ $msg_sueldo = "no";	}
	$consultx = "SELECT id FROM nomina WHERE estatus>0 AND nomina='$nomina' AND hasta='$hasta' AND tipo_pago='002' and lote=1;";
	$tablx = $_SESSION['conexionsql']->query($consultx);
	if ($tablx->num_rows>0)
		{ $msg_tickets = "no";	}
	$consultx = "SELECT id FROM nomina WHERE estatus>0 AND nomina='$nomina' AND hasta='$hasta' AND tipo_pago='003' and lote=1;";
	$tablx = $_SESSION['conexionsql']->query($consultx);	
	if ($tablx->num_rows>0)
		{ $msg_vacaciones = "no";	}
	//-------------
	if ($msg_sueldo<>'no' and $_POST['oquincena']==1)
		{
		$consultx = "DELETE FROM nomina WHERE estatus=0 AND nomina='$nomina' AND fecha='$fecha' AND desde='$desde' AND hasta='$hasta' AND tipo_pago = '001' and lote=1;"; 
		$tablx = $_SESSION['conexionsql']->query($consultx);	
		$consultx = "DELETE FROM nomina_solicitudes WHERE estatus=0 AND nomina='$nomina' AND fecha='$fecha' AND desde='$desde' AND hasta='$hasta' AND tipo_pago = '001' and lote=1;"; 
		$tablx = $_SESSION['conexionsql']->query($consultx);	
		//------------- GUARDAR SOLICITUD
		$numero = num_nomina('PAGO DE QUINCENA', $nomina, $anno);
		//-------------
		$consultx = "INSERT INTO nomina_solicitudes(patria, tipo_pago, numero, nomina, anno, fecha, descripcion, desde, hasta, estatus, lote, usuario) VALUES (1, '001', $numero, '$nomina', '$anno', '$fecha', 'PAGO DE QUINCENA', '$desde', '$hasta', 0, 1, '".$_SESSION['CEDULA_USUARIO']."');";
		$tablx = $_SESSION['conexionsql']->query($consultx);
		//-------------	
			$consultax = "SELECT LAST_INSERT_ID() as id;";
			$tablax = $_SESSION['conexionsql']->query($consultax);	
			$registrox = $tablax->fetch_object();
			$id_solicitud_sueldo = $registrox->id;
		//-------------	FIN SOLICITUD
		}
	//-------------
	if ($msg_tickets<>'no' and  $_POST['OQUINCENA']<>'01' and $_POST['otickets']==1)
		{
		$consultx = "DELETE FROM nomina WHERE estatus=0 AND nomina='$nomina' AND fecha='$fecha' AND hasta='$hasta' AND tipo_pago = '002' and lote=1;"; 
		$tablx = $_SESSION['conexionsql']->query($consultx);	//echo $consultx;
		$consultx = "DELETE FROM nomina_solicitudes WHERE estatus=0 AND nomina='$nomina' AND fecha='$fecha' AND desde='$desde_m' AND hasta='$hasta' AND tipo_pago = '002' and lote=1;"; 
		$tablx = $_SESSION['conexionsql']->query($consultx);	
		//------------- GUARDAR SOLICITUD
		$numero = num_nomina('PAGO DE CESTATICKETS', $nomina, $anno);
		//-------------
		$consultx = "INSERT INTO nomina_solicitudes(patria, tipo_pago, numero, nomina, anno, fecha, descripcion, desde, hasta, estatus, lote, usuario) VALUES (1, '002', $numero, '$nomina', '$anno', '$fecha', 'PAGO DE CESTATICKETS', '$desde_m', '$hasta', 0, 1, '".$_SESSION['CEDULA_USUARIO']."');";
		$tablx = $_SESSION['conexionsql']->query($consultx);
		//-------------	
			$consultax = "SELECT LAST_INSERT_ID() as id;";
			$tablax = $_SESSION['conexionsql']->query($consultax);	
			$registrox = $tablax->fetch_object();
			$id_solicitud_tickets = $registrox->id;
		//-------------	FIN SOLICITUD
		}
	//-------------
	if ($msg_vacaciones<>'no' and $_POST['OQUINCENA']<>'01' and $_POST['ovacaciones']==1)
		{
		$consultx = "DELETE FROM nomina WHERE estatus=0 AND nomina='$nomina' AND fecha='$fecha' AND hasta='$hasta' AND tipo_pago = '003' and lote=1;"; 
		$tablx = $_SESSION['conexionsql']->query($consultx);	 //echo $consultx;
		$consultx = "DELETE FROM nomina_solicitudes WHERE estatus=0 AND nomina='$nomina' AND fecha='$fecha' AND hasta='$hasta' AND tipo_pago = '003' and lote=1;"; 
		$tablx = $_SESSION['conexionsql']->query($consultx);	
		//------------- GUARDAR SOLICITUD
		$numero = num_nomina('PAGO DE VACACIONES', $nomina, $anno);
		//-------------
		$consultx = "INSERT INTO nomina_solicitudes(patria, tipo_pago, numero, nomina, anno, fecha, descripcion, desde, hasta, estatus, lote, usuario) VALUES (1, '003', $numero, '$nomina', '$anno', '$fecha', 'PAGO DE VACACIONES', '$desde_m', '$hasta', 0, 1, '".$_SESSION['CEDULA_USUARIO']."');";
		$tablx = $_SESSION['conexionsql']->query($consultx);
		//-------------	
			$consultax = "SELECT LAST_INSERT_ID() as id;";
			$tablax = $_SESSION['conexionsql']->query($consultax);	
			$registrox = $tablax->fetch_object();
			$id_solicitud_vaca = $registrox->id;
		//-------------	FIN SOLICITUD
		$consultx = "DELETE FROM nomina WHERE estatus=0 AND nomina='$nomina' AND fecha='$fecha' AND hasta='$hasta' AND tipo_pago = '003' and lote=1;"; 
		$tablx = $_SESSION['conexionsql']->query($consultx);	
		}
	//-------------
	$consultx = "DELETE FROM nomina_descuentos WHERE id_nomina NOT IN (SELECT id FROM nomina);";
	$tablx = $_SESSION['conexionsql']->query($consultx);	
	$consultx = "DELETE FROM nomina_asignaciones WHERE id_nomina NOT IN (SELECT id FROM nomina);";
	$tablx = $_SESSION['conexionsql']->query($consultx);	
	//-------------
	$num_nomina = nomina_sig();
	$num_nominac = $num_nomina+1;
	$num_nominav = $num_nomina+2;

	$consultx = "SELECT * FROM rac WHERE TRIM(cuenta)<>'' AND temporal=0 AND suspendido=0 AND nomina='$nomina' ORDER BY ubicacion, cedula;"; //echo $consultx ;
	$tablx = $_SESSION['conexionsql']->query($consultx);
	while ($registro = $tablx->fetch_object())
		{
		$deducciones = 0;
		$cedula = $registro->cedula;
		$sueldo = $registro->sueldo;
		$sueldo_mensual = $registro->sueldo;
		$quincena = $registro->sueldo/2;
		$cargo = $registro->cargo;
		$des_sueldo = $registro->des_sueldo;
		$des_tickets = $registro->des_tickets;
		$ubicacion = $registro->ubicacion;
		$hijos = $registro->hijos/2;
		$profesion = $registro->profesion;
		$tickets = $registro->tickets;
		$ayuda = $registro->ayuda;
		$vacacion = $registro->suspendidov;
		$profesion1 = ($registro->prof);
		$ingreso1 = ($registro->fecha_ingreso);
		$anno_ing = anno($registro->fecha_ingreso);
		$mes_ing = mes($registro->fecha_ingreso);
		$dia_ing = dia($registro->fecha_ingreso);
		$annos = annos_exacto($anno_ing, $mes_ing , $dia_ing , anno($hasta), mes($hasta), dia($hasta));
		$anos_servicio = intval($annos) + intval($registro->anos_servicio);
		//--------------
		$fecha_desde = fecha_a_numero($desde);
		$fecha_hasta = fecha_a_numero($hasta);
		$fecha_ingreso = fecha_a_numero($registro->fecha_ingreso);
		if ($fecha_ingreso>$fecha_desde)
			{
			if ($fecha_ingreso<=$fecha_hasta)
				{
				$dias_trabajo = ($fecha_hasta-$fecha_ingreso) / 86400; 
				$sueldo = ($sueldo * ($dias_trabajo+1)) / ((($fecha_hasta-$fecha_desde)/86400)+1) ;
				$quincena = $sueldo/2;
				}
			}
		if ($des_sueldo>0)
			{
			$quincena = ((15-$des_sueldo)*($sueldo/2)) / 15 ;
			}
		
		//-------------- QUINCENA
		if ($msg_sueldo<>'no' and $_POST['oquincena']==1)
		{
		//------- SUELDO
		$consultax = "INSERT INTO nomina (profesion1, ingreso1, id_solicitud, lote, sueldo_mensual, num_nomina, tipo_pago, cargo, categoria, ubicacion, nomina, partida, cedula, anno, fecha, descripcion, desde, hasta, asignaciones, descuentos, total, estatus, usuario) VALUES ('$profesion1', '$ingreso1', '$id_solicitud_sueldo', 1, '$sueldo_mensual', '$num_nomina', '$tipo_pago', '$cargo', '$categoria', '$ubicacion', '$nomina', '$partida', '".$cedula."', ".anno($fecha).", '$fecha', 'PAGO DE QUINCENA', '$desde', '$hasta', 0, 0, 0, 0, '".$_SESSION['CEDULA_USUARIO']."')";
		$tablax = $_SESSION['conexionsql']->query($consultax);	
		//-------------	id de la nomina
		$consultax = "SELECT LAST_INSERT_ID() as id;";
		$tablax = $_SESSION['conexionsql']->query($consultax);	
		$registrox = $tablax->fetch_object();
		$id_nomina = $registrox->id;
		//-------------XXXXXXXXXXXXXXX	DESCUENTOS
		if ($registro->sus_sso>0)
			{
			$lunes = lunes($anno, $mes);
			$monto = ((($sueldo_mensual*12/52)*$registro->sus_sso/100)*$lunes)/2;
			//$monto = (((325*12/52)*$registro->sus_sso)/100*4);
			$monto2 = ((($sueldo_mensual*12/52)*9/100)*$lunes)/2;
			//$monto2 = (((325*12/52)*9)/100*4);
			$deducciones = $deducciones + $monto;
			$consultax = "INSERT INTO nomina_descuentos (id_nomina, categoria, nomina, partida, cedula, id_descuento, descuento, patrono) VALUES ($id_nomina, '$categoria_descuentos', '$nomina', '$partida_sso', '$cedula', '1', '".($monto)."', '".($monto2)."')"; //echo $consultax;
			$tablax = $_SESSION['conexionsql']->query($consultax);	
			}
		//-------------	
		if ($registro->sus_pfo>0)
			{
			$monto = ((($sueldo_mensual*12/52)*$registro->sus_pfo/100)*$lunes)/2;
			$deducciones = $deducciones + $monto;
			$consultax = "INSERT INTO nomina_descuentos (id_nomina, categoria, nomina, partida, cedula, id_descuento, descuento, patrono) VALUES ($id_nomina, '$categoria_descuentos', '$nomina', '$partida_pfo', '$cedula', '2', '".($monto)."', '".($monto*4)."')"; //echo $consultax;
			$tablax = $_SESSION['conexionsql']->query($consultax);	
			}
		//-------------	
		if ($registro->sus_lph>0)
			{
			$monto = ((($sueldo_mensual+(($hijos*2)*$_SESSION['prima_hijos'])+(($sueldo_mensual*($_SESSION['prima_prof'][intval($profesion)]))/100)+((($_SESSION['prima_anno'][intval($anos_servicio)])*$sueldo_mensual)/100))*$registro->sus_lph)/100)/2;
			$deducciones = $deducciones + $monto;
			$consultax = "INSERT INTO nomina_descuentos (id_nomina, categoria, nomina, partida, cedula, id_descuento, descuento, patrono) VALUES ($id_nomina, '$categoria_descuentos', '$nomina', '$partida_lph', '$cedula', '3', '".($monto)."', '".($monto*2)."')"; //echo $consultax;
			$tablax = $_SESSION['conexionsql']->query($consultax);	
			}
		//-------------	
		if ($registro->sus_fej>0)
			{
			$monto = (($sueldo_mensual*$registro->sus_fej)/100)/2;
			$deducciones = $deducciones + $monto;
			$consultax = "INSERT INTO nomina_descuentos (id_nomina, categoria, nomina, partida, cedula, id_descuento, descuento, patrono) VALUES ($id_nomina, '$categoria_descuentos', '$nomina', '$partida_fej', '$cedula', '4', '".($monto)."', '".($monto)."')"; //echo $consultax;
			$tablax = $_SESSION['conexionsql']->query($consultax);	
			}
		//-------------	
//		if ($registro->sus_fusamieg>0)
//			{
//			$monto = ($quincena*$registro->sus_fusamieg)/100;
//			$deducciones = $deducciones + $monto;
//			$consultax = "INSERT INTO nomina_descuentos (id_nomina, categoria, nomina, partida, cedula, id_descuento, descuento, patrono) VALUES ($id_nomina, '$categoria_descuentos', '$nomina', '$partida_fej', '$cedula', '5', '".($monto)."', '".($monto*3)."')"; //echo $consultax;
//			$tablax = $_SESSION['conexionsql']->query($consultax);	
//			}

		//------------XXXXXXXASIGNACIONES
		$consultax = "INSERT INTO nomina_asignaciones(id_nomina, categoria, nomina, partida, cedula, id_asignacion, asignaciones) VALUES ($id_nomina, '$categoria', '$nomina', '$partida', '$cedula', '1', '$quincena');"; //echo $consultax;
		$tablax = $_SESSION['conexionsql']->query($consultax);	
		//------------ 
		if ($hijos>0)
			{
			$consultax = "INSERT INTO nomina_asignaciones(id_nomina, categoria, nomina, partida, cedula, id_asignacion, asignaciones) VALUES ($id_nomina, '$categoria', '$nomina', '$partida_hijos', '$cedula', '2', '".($hijos*$_SESSION['prima_hijos'])."');"; //echo $consultax;
			$tablax = $_SESSION['conexionsql']->query($consultax);	
			//$var1 = ($hijos*$_SESSION['prima_hijos']);
			}
		//------------ 
		if ($profesion>0)
			{
			$consultax = "INSERT INTO nomina_asignaciones(id_nomina, categoria, nomina, partida, cedula, id_asignacion, asignaciones) VALUES ($id_nomina, '$categoria', '$nomina', '$partida_profesion', '$cedula', '3', '".($quincena*($_SESSION['prima_prof'][intval($profesion)])/100)."');";
			$tablax = $_SESSION['conexionsql']->query($consultax);	
			}
		//------------ 
		if ($anos_servicio>0 and ($codigo_nomina<>'005' and $codigo_nomina<>'006'))
			{
			$consultax = "INSERT INTO nomina_asignaciones(id_nomina, categoria, nomina, partida, cedula, id_asignacion, asignaciones) VALUES ($id_nomina, '$categoria', '$nomina', '$partida_antiguedad', '$cedula', '4', '".($quincena*($_SESSION['prima_anno'][intval($anos_servicio)])/100)."');";
			$tablax = $_SESSION['conexionsql']->query($consultax);	
			}
		//------------ 
		if ($hijos_estudiando>0)
			{
			$consultax = "INSERT INTO nomina_asignaciones(id_nomina, categoria, nomina, partida, cedula, id_asignacion, asignaciones) VALUES ($id_nomina, '$categoria', '$nomina', '$partida_antiguedad', '$cedula', '5', '".$hijos_estudiando*$_SESSION['prima_hijos']."');";
			$tablax = $_SESSION['conexionsql']->query($consultax);	
			}		
		//------------ 
		if ($estudiante>0)
			{
			$consultax = "INSERT INTO nomina_asignaciones(id_nomina, categoria, nomina, partida, cedula, id_asignacion, asignaciones) VALUES ($id_nomina, '$categoria', '$nomina', '$partida_antiguedad', '$cedula', '6', '".$estudiante*$_SESSION['prima_hijos']."');";
			$tablax = $_SESSION['conexionsql']->query($consultax);	
			}		
		//------------ 
		if ($hijos_discapacidad>0)
			{
			$consultax = "INSERT INTO nomina_asignaciones(id_nomina, categoria, nomina, partida, cedula, id_asignacion, asignaciones) VALUES ($id_nomina, '$categoria', '$nomina', '$partida_antiguedad', '$cedula', '7', '".$hijos_discapacidad*$_SESSION['prima_hijos']."');";
			$tablax = $_SESSION['conexionsql']->query($consultax);	
			}
		//------------ 
		if ($ayuda>0)
			{
			$bono = bono($codigo_nomina, $cargo);
			$consultax = "INSERT INTO nomina_asignaciones(id_nomina, categoria, nomina, partida, cedula, id_asignacion, asignaciones) VALUES ($id_nomina, '$categoria', '$nomina', '$partida', '$cedula', '14', '".($bono/2)."');";
//			echo $bono.' '.$codigo_nomina.' '.$cargo;
			$tablax = $_SESSION['conexionsql']->query($consultax);	
			}		
		//------------ 
		if ($codigo_nomina=='001' and 1==2)
			{
			$consultax = "INSERT INTO nomina_asignaciones(id_nomina, categoria, nomina, partida, cedula, id_asignacion, asignaciones) VALUES ($id_nomina, '$categoria', '$nomina', '$partida', '$cedula', '8', '".(prima_representacion()/2)."');";
			$tablax = $_SESSION['conexionsql']->query($consultax);	
			}		
		//------------ 
		if (($codigo_nomina=='002' or $codigo_nomina=='001') and 1==2)
			{
			$consultax = "INSERT INTO nomina_asignaciones(id_nomina, categoria, nomina, partida, cedula, id_asignacion, asignaciones) VALUES ($id_nomina, '$categoria', '$nomina', '$partida', '$cedula', '9', '".($quincena*prima_responsabilidad()/100)."');";
			$tablax = $_SESSION['conexionsql']->query($consultax);	
			}			
		//------------ PRIMA POR HOGAR
		if (($codigo_nomina=='003' or $codigo_nomina=='004') and 1==2)
			{
			$consultax = "INSERT INTO nomina_asignaciones(id_nomina, categoria, nomina, partida, cedula, id_asignacion, asignaciones) VALUES ($id_nomina, '$categoria', '$nomina', '$partida', '$cedula', '10', '".($_SESSION['prima_hogar'])."');";
			$tablax = $_SESSION['conexionsql']->query($consultax);
			}			
		}
		//------------ VACACIONES
		if ($msg_vacaciones<>'no' and $vacacion<>1 and $_POST['OQUINCENA']<>'01' and $_POST['ovacaciones']==1 and ($codigo_nomina=='003' or $codigo_nomina=='004' or $codigo_nomina=='002' or $codigo_nomina=='001'))
			{ 
			if ($anos_servicio>0 and $mes_ing==$mes)
				{
				$sueldo  = $quincena * 2;
				$dias = dias_vacaciones($anos_servicio);
				//----------
				$prima_prof = ($sueldo*($_SESSION['prima_prof'][intval($profesion)])/100);
				$prima_antiguedad = ($sueldo*($_SESSION['prima_anno'][intval($anos_servicio)])/100);
				$prima_hijos = (($hijos*2)*$_SESSION['prima_hijos']);
				$vacaciones = ($sueldo + $prima_prof + $prima_antiguedad + $prima_hijos)/30*$dias;
				$sueldo_mes = ($sueldo + $prima_prof + $prima_antiguedad + $prima_hijos);
				
				//---------
				if ($vacaciones>0)
					{
					//-----------
					$consultax = "INSERT INTO nomina (dias_trabajados, lote, prof, antiguedad, hijos, id_solicitud, sueldo_mensual, num_nomina, sueldo, asignaciones, total, tipo_pago, cargo, categoria, ubicacion, nomina, partida, cedula, anno, fecha, descripcion, desde, hasta, descuentos, estatus, usuario) VALUES ('$dias', '1', '$prima_prof', '$prima_antiguedad', '$prima_hijos', '$id_solicitud_vaca', '$sueldo_mensual', '$num_nominav', '$sueldo_mes', '$vacaciones', '$vacaciones', '003', '$cargo', '$categoria', '$ubicacion', '$nomina', '$partida_vacaciones', '".$cedula."', ".anno($fecha).", '$fecha', 'PAGO DE VACACIONES', '$desde_m', '$hasta', 0, 0, '".$_SESSION['CEDULA_USUARIO']."')";
					$tablax = $_SESSION['conexionsql']->query($consultax);	
					//-------------	id de la nomina nueva
					$consultax = "SELECT LAST_INSERT_ID() as id;";
					$tablax = $_SESSION['conexionsql']->query($consultax);	
					$registrox = $tablax->fetch_object();
					$id_nomina_ct = $registrox->id;
					//------------ ASIGNACION
					$consultax = "INSERT INTO nomina_asignaciones(id_nomina, categoria, nomina, partida, cedula, id_asignacion, asignaciones) VALUES ($id_nomina_ct, '$categoria', '$nomina', '$partida_vacaciones', '$cedula', '12', '$vacaciones');"; //echo $consultax;
					$tablax = $_SESSION['conexionsql']->query($consultax);	
					//------------
					}
				}
			}
		//------------ CESTATICKETS
		if ($msg_tickets<>'no' and $tickets>0 and $_POST['OQUINCENA']<>'01' and $_POST['otickets']==1 and ($codigo_nomina=='003' or $codigo_nomina=='004' or $codigo_nomina=='002' or $codigo_nomina=='001'))
			{
			$cestatickets = valortickets(); 
			if ($cestatickets>0)
				{
				//----- DESCUENTO DE TICKETS
				if ($des_tickets>0)
					{
					$cestatickets = ($cestatickets / 30) * (30-$des_tickets) ;
					//$cestatickets = ((30-$des_tickets)*$cestatickets) / 30 ;
					}
				//-------------
				$consultax = "INSERT INTO nomina (lote, id_solicitud, bono, tickets, sueldo_mensual, num_nomina, sueldo, asignaciones, total, tipo_pago, cargo, categoria, ubicacion, nomina, partida, cedula, anno, fecha, descripcion, desde, hasta, descuentos, estatus, usuario) VALUES ('1', '$id_solicitud_tickets', '0', '".($cestatickets)."', '$sueldo_mensual', '$num_nominac', '$cestatickets', '$cestatickets', '$cestatickets', '002', '$cargo', '$categoria', '$ubicacion', '$nomina', '$partida_tickets', '".$cedula."', ".anno($fecha).", '$fecha', 'PAGO DE CESTATICKETS', '$desde_m', '$hasta', 0, 0, '".$_SESSION['CEDULA_USUARIO']."')"; //echo $consultax;
				$tablax = $_SESSION['conexionsql']->query($consultax);	
				//-------------	id de la nomina nueva
				$consultax = "SELECT LAST_INSERT_ID() as id;";
				$tablax = $_SESSION['conexionsql']->query($consultax);	
				$registrox = $tablax->fetch_object();
				$id_nomina_ct = $registrox->id;
				//------------ ASIGNACION
				$consultax = "INSERT INTO nomina_asignaciones(id_nomina, categoria, nomina, partida, cedula, id_asignacion, asignaciones, total_asignacion) VALUES ($id_nomina_ct, '$categoria_tickets', '$nomina', '$partida_tickets', '$cedula', '13', '$cestatickets', '$cestatickets');"; //echo $consultax;
				$tablax = $_SESSION['conexionsql']->query($consultax);	
				}
			}
		}
	//-------------	
	}
$consultax = "DELETE FROM nomina_asignaciones WHERE asignaciones = 0 or TRIM(partida)='';"; 
//echo $consultx ;
$tablax = $_SESSION['conexionsql']->query($consultax);
//-------------	
$consultax = "CALL actualizar_quincenas();"; //echo $consultx ;
$tablax = $_SESSION['conexionsql']->query($consultax);
//-------------	
$mensaje = "Nomina Generada Exitosamente!";
//}

//-------------
$info = array ("tipo"=>$tipo, "msg"=>$mensaje);
echo json_encode($info);
?>