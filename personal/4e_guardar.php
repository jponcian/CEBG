<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');
//--------
$info = array();
$tipo = 'info';
//-------------

if ($_GET['tipo']==0)
	{
	$consultx = "SELECT cedula FROM rac WHERE cedula='".$_POST['txt_cedula']."0' LIMIT 1;";
	$tablx = $_SESSION['conexionsql']->query($consultx);
	if ($tablx->num_rows>0)
		{
		$tipo = 'alerta';
		$mensaje = "Empleado ya Registrado!";
		}
	else
		{
		//-------------
		$consultx = "INSERT INTO rac (codigo, fecha_nacimiento, fecha_ingreso, fecha_contrato, cedula, ci, nombre, nombre2, apellido, apellido2, sexo, profesion, cuenta, telefono, correo, usuario) VALUES ('".trim($_POST['txt_codigo'])."', '".voltea_fecha($_POST['txt_nacimiento'])."', '".voltea_fecha($_POST['txt_ingreso'])."', '".voltea_fecha($_POST['txt_contrato'])."', '".$_POST['txt_cedula']."',  '".$_POST['txt_digito'].'-'.($_POST['txt_cedula'])."0', '".strtoupper($_POST['txt_nombre1'])."', '".strtoupper($_POST['txt_nombre2'])."', '".strtoupper($_POST['txt_apellido1'])."', '".strtoupper($_POST['txt_apellido2'])."', '".$_POST['txt_sexo']."', '".$_POST['txt_profesion']."', '".$_POST['txt_cuenta']."', '".trim($_POST['txt_telefono'])."', '".trim($_POST['txt_correo'])."', '".$_SESSION['CEDULA_USUARIO']."');";
		$tablx = $_SESSION['conexionsql']->query($consultx);
		//------------
		$id = 0;
		//------------- 
		$mensaje = "Empleado Registrado Exitosamente!";
		}
	}
if ($_GET['tipo']==1)
	{
	//----------------
	$consultx = "UPDATE rac SET codigo='".trim($_POST['txt_codigo'])."', telefono='".trim($_POST['txt_telefono'])."', correo='".trim($_POST['txt_correo'])."', fecha_nacimiento='".voltea_fecha($_POST['txt_nacimiento'])."', fecha_ingreso='".voltea_fecha($_POST['txt_ingreso'])."', fecha_contrato='".voltea_fecha($_POST['txt_contrato'])."', nombre = '".$_POST['txt_nombre1']."', nombre2 = '".$_POST['txt_nombre2']."', apellido = '".$_POST['txt_apellido1']."', apellido2 = '".$_POST['txt_apellido2']."', anos_servicio = '".trim($_POST['txt_annos'])."', sexo = '".$_POST['txt_sexo']."', profesion = '".$_POST['txt_profesion']."', cuenta = '".$_POST['txt_cuenta']."', usuario='".$_SESSION['CEDULA_USUARIO']."' WHERE rac = '".$_POST['oid']."';";
	$tablx = $_SESSION['conexionsql']->query($consultx);	
	//----------------
	$consultx = "UPDATE rac SET civil = '".trim($_POST['txt_civil'])."', sangre = '".trim($_POST['txt_sangre'])."', estatura = '".trim($_POST['txt_estatura'])."', peso = '".trim($_POST['txt_peso'])."', lentes = '".trim($_POST['txt_lentes'])."', tallac = '".trim($_POST['txt_tallac'])."', tallap = '".trim($_POST['txt_tallap'])."', tallaz = '".trim($_POST['txt_tallaz'])."', tallak = '".trim($_POST['txt_tallak'])."', deporte = '".trim($_POST['txt_deporte'])."', destreza = '".trim($_POST['txt_destreza'])."', usuario='".$_SESSION['CEDULA_USUARIO']."' WHERE rac = '".$_POST['oid']."';";
	$tablx = $_SESSION['conexionsql']->query($consultx);	
	//----------------
	$consultx = "UPDATE rac SET nac_pais = '".trim($_POST['txt_pais'])."', nac_estado = '".trim($_POST['txt_estado'])."', nac_municipio = '".trim($_POST['txt_municipio'])."', nac_parroquia = '".trim($_POST['txt_parroquia'])."', nac_ciudad = '".trim($_POST['txt_ciudad'])."', dir_estado = '".trim($_POST['txt_estadod'])."', dir_municipio = '".trim($_POST['txt_municipiod'])."', dir_parroquia = '".trim($_POST['txt_parroquiad'])."', dir_ciudad = '".trim($_POST['txt_ciudadd'])."', direccion_habitacion = '".trim($_POST['txt_direccion'])."', usuario='".$_SESSION['CEDULA_USUARIO']."' WHERE rac = '".$_POST['oid']."';";
	$tablx = $_SESSION['conexionsql']->query($consultx);	
	//------------
	$id = 0; 
	//-------------	
	$mensaje = "Empleado Actualizado Exitosamente!";
	}
if ($_GET['tipo']==2)
	{
	$_POST["txt_sueldo"] = str_replace('.','',$_POST['txt_sueldo']); 
	$_POST["txt_sueldo"] = str_replace(',','.',$_POST['txt_sueldo']); 
	//-------------
	$dia_egreso = dia(voltea_fecha($_POST['txt_egreso']));
	$mes_egreso = mes(voltea_fecha($_POST['txt_egreso']));
	$anno_egreso = anno(voltea_fecha($_POST['txt_egreso']));
	//----------------
	$consultx = "UPDATE rac SET pago_adic = '0".$_POST['txt_pago']."', evaluar_odis = '0".$_POST['txt_odis']."', vacaciones = '0".$_POST['txt_vacaciones']."', des_sueldo = '0".$_POST['txt_dias_sueldo']."', des_tickets = '0".$_POST['txt_dias_tickets']."', ayuda = '0".$_POST['txt_ayuda']."', tickets = '0".$_POST['txt_tickets']."', sus_sso = '0".$_POST['txt_sso']."', sus_pfo = '0".$_POST['txt_pf']."', sus_lph = '0".$_POST['txt_lph']."', sus_fej = '0".$_POST['txt_fej']."', fecha_jub = '".voltea_fecha($_POST['txt_jub'])."', fecha_egreso = '".voltea_fecha($_POST['txt_egreso'])."', nomina = '".$_POST['txt_nomina']."', id_area = '".trim($_POST['txt_ubicacion'])."', id_cargo = '".trim($_POST['txt_cargo'])."', sueldo = '".$_POST['txt_sueldo']."', suspendido = '".abs($_POST['txt_suspender'])."', suspendidov = '".abs($_POST['txt_suspenderv'])."', usuario='".$_SESSION['CEDULA_USUARIO']."' WHERE rac = '".$_POST['oid']."';";
	$tablx = $_SESSION['conexionsql']->query($consultx);	
	//------------
	$id = 0;
	//-------------	
	$mensaje = "Empleado Actualizado Exitosamente!";
	}
if ($_GET['tipo']==3)
	{
	$_POST["txt_sueldo"] = str_replace('.','',$_POST['txt_sueldo']); 
	$_POST["txt_sueldo"] = str_replace(',','.',$_POST['txt_sueldo']); 
	//----------------
	$consultx = "UPDATE rac SET sus_sso2 = '".$_POST['txt_sso']."', sus_pfo2 = '".$_POST['txt_pf']."', sus_lph2 = '".$_POST['txt_lph']."', sus_fej2 = '".$_POST['txt_fej']."', nomina2 = '".$_POST['txt_nomina']."', ubicacion2 = '".trim($_POST['txt_ubicacion'])."', cargo2 = '".trim($_POST['txt_cargo'])."', sueldo2 = '".$_POST['txt_sueldo']."', usuario='".$_SESSION['CEDULA_USUARIO']."' WHERE rac = '".$_POST['oid']."';";
	$tablx = $_SESSION['conexionsql']->query($consultx);	
	//------------
	$id = 0;
	//-------------	
	$mensaje = "Empleado Actualizado Exitosamente!";
	}

//-------------
$consultx1 = "UPDATE rac,a_areas SET rac.id_div = a_areas.id_direccion, rac.ubicacion = a_areas.area WHERE rac.id_area = a_areas.id";
$tablx = $_SESSION['conexionsql']->query($consultx1);	
//-------------
$consultx1 = "UPDATE a_cargo, rac SET rac.cargo = a_cargo.cargo WHERE a_cargo.codigo = rac.id_cargo;";
$tablx = $_SESSION['conexionsql']->query($consultx1);	
//-------------
$consultx1 = "UPDATE rac SET rac.jefe_division = 0 WHERE rac.jefe_division = 1;";
$tablx = $_SESSION['conexionsql']->query($consultx1);	
//-------------
$consultx1 = "UPDATE a_direcciones, rac SET rac.jefe_division = 1 WHERE a_direcciones.cedula = abs(rac.cedula);";
$tablx = $_SESSION['conexionsql']->query($consultx1);	
//-------------
$consultx1 = "UPDATE usuarios, rac SET usuarios.id_area = rac.id_area, usuarios.id_direccion = id_div WHERE usuarios.usuario = abs(rac.cedula);";
$tablx = $_SESSION['conexionsql']->query($consultx1);	
////-------------
//$consultx = "UPDATE rac , a_areas SET rac.categoria2=a_areas.categoria WHERE rac.ubicacion2 = a_areas.area;";
//$tablx = $_SESSION['conexionsql']->query($consultx);	
////-------------
//$consultx = "UPDATE rac , a_nomina SET rac.partida = a_nomina.partida WHERE rac.nomina = a_nomina.nomina";
//$tablx = $_SESSION['conexionsql']->query($consultx);	
////-------------
//$consultx = "UPDATE rac , a_nomina SET rac.partida2 = a_nomina.partida WHERE rac.nomina2 = a_nomina.nomina";
//$tablx = $_SESSION['conexionsql']->query($consultx);	
////-------------

$info = array ("tipo"=>$tipo, "msg"=>$mensaje, "consulta"=>$consultx, "id"=>$id);

echo json_encode($info);
?>