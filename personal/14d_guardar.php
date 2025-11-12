<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');
//--------
$info = array();
$tipo = 'info';
//-------------
$id = $_GET['id'];
$pago = $_GET['tipo'];
$_POST["txt_sueldo"] = str_replace('.','',$_POST['txt_sueldo']); 
$_POST["txt_sueldo"] = str_replace(',','.',$_POST['txt_sueldo']); 
$txt_sueldo = $_POST["txt_sueldo"] ;
$_POST["txt_antiguedad"] = str_replace('.','',$_POST['txt_antiguedad']); 
$_POST["txt_antiguedad"] = str_replace(',','.',$_POST['txt_antiguedad']); 
$txt_antiguedad = $_POST["txt_antiguedad"] ;
$_POST["txt_hijos"] = str_replace('.','',$_POST['txt_hijos']); 
$_POST["txt_hijos"] = str_replace(',','.',$_POST['txt_hijos']); 
$txt_hijos = $_POST["txt_hijos"] ;
$_POST["txt_prof"] = str_replace('.','',$_POST['txt_prof']); 
$_POST["txt_prof"] = str_replace(',','.',$_POST['txt_prof']); 
$txt_prof = $_POST["txt_prof"] ;
$_POST["txt_dias"] = str_replace('.','',$_POST['txt_dias']); 
$_POST["txt_dias"] = str_replace(',','.',$_POST['txt_dias']); 
$txt_dias = $_POST["txt_dias"] ;
$_POST["txt_dif"] = str_replace('.','',$_POST['txt_dif']); 
$_POST["txt_dif"] = str_replace(',','.',$_POST['txt_dif']); 
$txt_dif = $_POST["txt_dif"] ;
$_POST["txt_tickets"] = str_replace('.','',$_POST['txt_tickets']); 
$_POST["txt_tickets"] = str_replace(',','.',$_POST['txt_tickets']); 
$txt_tickets = $_POST["txt_tickets"] ;
$_POST["txt_bono"] = str_replace('.','',$_POST['txt_bono']); 
$_POST["txt_bono"] = str_replace(',','.',$_POST['txt_bono']); 
$txt_bono = $_POST["txt_bono"] ;
$sueldo = (($txt_sueldo +$txt_antiguedad +$txt_hijos +$txt_prof +$txt_dias));

if ($pago=='001')
	{
	//-------------		
	$consultau = "UPDATE nomina SET asignaciones = asignaciones-dias-diferencia, total = total-dias-diferencia WHERE id=$id;"; 
	$tablau = $_SESSION['conexionsql']->query($consultau);	
	//-------------	
	$consultau = "DELETE FROM nomina_asignaciones WHERE id_nomina = $id AND (id_asignacion=11 OR id_asignacion=15);"; 
	$tablau = $_SESSION['conexionsql']->query($consultau);	
	//-------------	
	$consultx = "SELECT * FROM nomina_asignaciones WHERE id_nomina = $id AND id_asignacion=1;"; 
	$tablx = $_SESSION['conexionsql']->query($consultx);
	$registro = $tablx->fetch_object();
		$categoria = $registro->categoria;
		$nomina = $registro->nomina;
		$partida = $registro->partida;
		$cedula = $registro->cedula;
	//-------------		
	$consultai = "INSERT INTO nomina_asignaciones (id_nomina, categoria, nomina, partida, cedula, id_asignacion, asignaciones, total_asignacion) VALUES ('$id', '$categoria', '$nomina', '$partida', '$cedula', 11, '$txt_dias', '$txt_dias');"; 
	$tablai = $_SESSION['conexionsql']->query($consultai);	
	//-------------		
	$consultai = "INSERT INTO nomina_asignaciones (id_nomina, categoria, nomina, partida, cedula, id_asignacion, asignaciones, total_asignacion) VALUES ('$id', '$categoria', '$nomina', '$partida', '$cedula', 15, '$txt_dif', '$txt_dif');"; 
	$tablai = $_SESSION['conexionsql']->query($consultai);	
	//-------------	
	$consultau = "DELETE FROM nomina_asignaciones WHERE asignaciones=0;"; 
	$tablau = $_SESSION['conexionsql']->query($consultau);	
	//-------------	
	$consultau = "UPDATE nomina SET dias = '$txt_dias', diferencia = '$txt_dif' WHERE id=$id;"; 
	$tablau = $_SESSION['conexionsql']->query($consultau);	
	//-------------		
	$consultau = "UPDATE nomina SET asignaciones = asignaciones+dias+diferencia, total = total+dias+diferencia WHERE id=$id;"; 
	$tablau = $_SESSION['conexionsql']->query($consultau);	
	//-------------	
	}
if ($pago=='002')
	{
	//-------------	
	$consultau = "UPDATE nomina_asignaciones SET asignaciones = '$txt_tickets', total_asignacion = '$txt_tickets'  WHERE id_nomina=$id AND id_asignacion=13;"; 
	$tablau = $_SESSION['conexionsql']->query($consultau);	
	//-------------		
	$consultau = "UPDATE nomina SET sueldo = '$txt_tickets', tickets = '$txt_tickets', asignaciones = '$txt_tickets', total = '$txt_tickets'  WHERE id=$id;"; 
	$tablau = $_SESSION['conexionsql']->query($consultau);	
	//-------------	
	}
if ($pago=='003')
	{
	$consultx = "SELECT rac.fecha_ingreso, rac.anos_servicio, nomina.hasta FROM rac, nomina WHERE nomina.cedula=rac.cedula AND nomina.id=$id;"; 
	$tablx = $_SESSION['conexionsql']->query($consultx);
	$registro = $tablx->fetch_object();
		$anno_ing = anno($registro->fecha_ingreso);
		$mes_ing = mes($registro->fecha_ingreso);
		$dia_ing = dia($registro->fecha_ingreso);
		$hasta = ($registro->hasta);
		$annos = annos_exacto($anno_ing, $mes_ing , $dia_ing , anno($hasta), mes($hasta), dia($hasta));
		$anos_servicio = (intval($annos) + intval($registro->anos_servicio)); //echo $anos_servicio;
	//-------------	
	$dias = dias_vacaciones($anos_servicio);	
	$vacaciones = ((($txt_sueldo +$txt_antiguedad +$txt_hijos +$txt_prof)/30)*$dias)+$txt_dias;
	//-------------	
	$consultau = "UPDATE nomina_asignaciones SET asignaciones = '$vacaciones', total_asignacion = '$vacaciones'  WHERE id_nomina=$id AND id_asignacion=12;";  //echo $consultau;
	$tablau = $_SESSION['conexionsql']->query($consultau);	
	//-------------		
	$consultau = "UPDATE nomina SET sueldo_mensual = '$txt_sueldo', sueldo = '$sueldo', prof = '$txt_prof', antiguedad = '$txt_antiguedad', hijos = '$txt_hijos', dias = '$txt_dias', asignaciones = '$vacaciones', total = '$vacaciones', dias_trabajados = '$dias' WHERE id=$id;"; 
	$tablau = $_SESSION['conexionsql']->query($consultau);	
	//-------------	
	}

$mensaje = "Monto Actualizado Exitosamente!";
//-------------	
$consultax = "DROP TABLE IF EXISTS tabla_tmp;"; //echo $consultx ;
$tablax = $_SESSION['conexionsql']->query($consultax);
//-------------	
$consultax = "CREATE TEMPORARY TABLE tabla_tmp (SELECT Sum(nomina_asignaciones.asignaciones) as suma, id_solicitud FROM nomina , nomina_asignaciones  WHERE nomina_asignaciones.id_nomina = nomina.id GROUP BY nomina.id_solicitud);"; //echo $consultx ;
$tablax = $_SESSION['conexionsql']->query($consultax);
//-------------	
$consultax = "UPDATE nomina_solicitudes, tabla_tmp SET nomina_solicitudes.asignaciones=tabla_tmp.suma WHERE nomina_solicitudes.id=tabla_tmp.id_solicitud;"; //echo $consultx ;
$tablax = $_SESSION['conexionsql']->query($consultax);
//-------------	
$consultax = "UPDATE nomina_solicitudes SET nomina_solicitudes.total=nomina_solicitudes.asignaciones-nomina_solicitudes.descuentos;"; //echo $consultx ;
$tablax = $_SESSION['conexionsql']->query($consultax);
//-------------	

$info = array ("tipo"=>$tipo, "msg"=>$mensaje, "consulta"=>$consultau );

echo json_encode($info);
?>