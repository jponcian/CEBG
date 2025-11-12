<?php
session_start();
include_once "../conexion.php";
include_once "../funciones/auxiliar_php.php";
//--------
$info = array();
$tipo = 'info';
//-------------
$desde = voltea_fecha(extrae_fecha($_POST['txt_desde']));
$hasta = voltea_fecha(extrae_fecha($_POST['txt_hasta']));
$hora1 = (extrae_hora_laboral($_POST['txt_desde']));
$hora2 = (extrae_hora_laboral($_POST['txt_hasta']));
$horaa = (extrae_hora($_POST['txt_desde']));
$horab = (extrae_hora($_POST['txt_hasta']));
//-------------
$oficina = info_area_ci($_POST['txt_cedula']);
//-------------
if ($_POST['oid']==0)
	{
	$consultx = "INSERT INTO viaticos_solicitudes(ciudad, contralor, direccion, area, cedula, concepto, fecha, desde, hasta, hora1, hora2, horaa, horab, zona, vehiculo, hotel, estatus, usuario) VALUES ('".strtoupper($_POST['txt_ciudad'])."', '".$oficina[0]."', '".$oficina[3]."', '".$oficina[1]."', '".($_POST['txt_cedula'])."', '".strtoupper($_POST['txt_concepto'])."', '".date('Y-m-d')."', '".$desde."', '".$hasta."', '".$hora1."', '".$hora2."', '".$horaa."', '".$horab."', '".($_POST['txt_zona'])."', '".($_POST['txt_vehiculo'])."', '".($_POST['txt_hotel'])."', 0, '".$_SESSION['CEDULA_USUARIO']."');" ;
	$tablx = $_SESSION['conexionsql']->query($consultx);	
	//-------------	
	$consultax = "SELECT LAST_INSERT_ID() as id LIMIT 1;";
	$tablax = $_SESSION['conexionsql']->query($consultax);	
	$registrox = $tablax->fetch_object();
		$id = $registrox->id;
	}
else
	{
	$consultx = "UPDATE viaticos_solicitudes SET contralor = '".$oficina[0]."', direccion = '".$oficina[3]."', area = '".($oficina[1])."', cedula = '".($_POST['txt_cedula'])."', ciudad = '".strtoupper($_POST['txt_ciudad'])."', concepto = '".strtoupper($_POST['txt_concepto'])."', hora1 = '".$hora1."', hora2 = '".$hora2."', horaa = '".$horaa."', horab = '".$horab."', desde = '".$desde."', hasta = '".$hasta."', zona = '".($_POST['txt_zona'])."', vehiculo = '".($_POST['txt_vehiculo'])."', hotel = '".($_POST['txt_hotel'])."', usuario = '".$_SESSION['CEDULA_USUARIO']."' WHERE id = ".$_POST['oid'].";" ;
	$tablx = $_SESSION['conexionsql']->query($consultx);	
	$id=$_POST['oid'];
	}

//---------------- PARA LAS COMIDAS
$dias = (fecha_a_numero($hasta)-fecha_a_numero($desde))/86400+1;

$desayuno=0; $almuerzo=0; $cena=0;
$desayuno = $dias;
$almuerzo = $dias;
$cena = $dias;

if (abs(substr($horaa,0,2))>7)
	{ $desayuno --;	}

if (abs(substr($horaa,0,2))>13)
	{ $almuerzo --;	}

if (abs(substr($horab,0,2))<12)
	{ $almuerzo --;	}

if (abs(substr($horab,0,2))<18)
	{ $cena --;	}

//----------------
$_SESSION['conexionsql']->query("DELETE FROM viaticos_solicitudes_detalle WHERE id_solicitud=$id AND id_tipo IN (1,2,3,7,8,9,14,15,16,21,22,23,27,28,29,34,35,36)");	
//----------------
if ($desayuno>0)
	{
	if ($oficina[0]==0)
		{if ($_POST['txt_zona']==1)
			{
			$id_tipo = 1;
			}elseif ($_POST['txt_zona']==2)
				{
				$id_tipo = 7;
				}elseif ($_POST['txt_zona']==3)
					{
					$id_tipo = 14;
					}}
	else
		{if ($_POST['txt_zona']==1)
			{
			$id_tipo = 21;
			}elseif ($_POST['txt_zona']==2)
				{
				$id_tipo = 27;
				}elseif ($_POST['txt_zona']==3)
					{
					$id_tipo = 34;
					}
		}
	//----------
	$consultAx = "INSERT INTO viaticos_solicitudes_detalle(id_solicitud, id_tipo, precio_u, cantidad, total, usuario) values ('$id', '$id_tipo', 0, '$desayuno', 0, '".$_SESSION['CEDULA_USUARIO']."');";
	$tablx = $_SESSION['conexionsql']->query($consultAx);	
	}
//----------------
if ($almuerzo>0)
	{
	if ($oficina[0]==0)
		{if ($_POST['txt_zona']==1)
			{
			$id_tipo = 2;
			}elseif ($_POST['txt_zona']==2)
				{
				$id_tipo = 8;
				}elseif ($_POST['txt_zona']==3)
					{
					$id_tipo = 15;
					}}
	else
		{if ($_POST['txt_zona']==1)
			{
			$id_tipo = 22;
			}elseif ($_POST['txt_zona']==2)
				{
				$id_tipo = 28;
				}elseif ($_POST['txt_zona']==3)
					{
					$id_tipo = 35;
					}
		}
	//----------
	$consultAx = "INSERT INTO viaticos_solicitudes_detalle(id_solicitud, id_tipo, precio_u, cantidad, total, usuario) values ('$id', '$id_tipo', 0, '$almuerzo', 0, '".$_SESSION['CEDULA_USUARIO']."');";
	$tablx = $_SESSION['conexionsql']->query($consultAx);	
	}
//----------------
if ($cena>0)
	{
	if ($oficina[0]==0)
		{if ($_POST['txt_zona']==1)
			{
			$id_tipo = 3;
			}elseif ($_POST['txt_zona']==2)
				{
				$id_tipo = 9;
				}elseif ($_POST['txt_zona']==3)
					{
					$id_tipo = 16;
					}}
	else
		{if ($_POST['txt_zona']==1)
			{
			$id_tipo = 23;
			}elseif ($_POST['txt_zona']==2)
				{
				$id_tipo = 29;
				}elseif ($_POST['txt_zona']==3)
					{
					$id_tipo = 36;
					}
		}
	//----------
	$consultAx = "INSERT INTO viaticos_solicitudes_detalle(id_solicitud, id_tipo, precio_u, cantidad, total, usuario) values ('$id', '$id_tipo', 0, '$cena', 0, '".$_SESSION['CEDULA_USUARIO']."');";
	$tablx = $_SESSION['conexionsql']->query($consultAx);	
	}
//-------------
$consultAx = "UPDATE viaticos_solicitudes_detalle, a_item_viaticos SET viaticos_solicitudes_detalle.precio_u = a_item_viaticos.monto, viaticos_solicitudes_detalle.total = viaticos_solicitudes_detalle.cantidad * a_item_viaticos.monto WHERE viaticos_solicitudes_detalle.id_tipo = a_item_viaticos.id AND total=0;";
$tablx = $_SESSION['conexionsql']->query($consultAx);	
//-------------
$consultAx = "UPDATE viaticos_solicitudes, viaticos_solicitudes_detalle SET viaticos_solicitudes.total=viaticos_solicitudes_detalle.total WHERE viaticos_solicitudes.id=viaticos_solicitudes_detalle.id_solicitud AND viaticos_solicitudes.id = $id;";
$tablx = $_SESSION['conexionsql']->query($consultAx);	
//-------------

//----------------
$mensaje = "Solicitud Registrada Exitosamente!";

$info = array ("tipo"=>$tipo, "msg"=>$mensaje, "consulta"=>$consultx);

echo json_encode($info);
?>