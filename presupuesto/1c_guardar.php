<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');
//-------------	
$info = array();
$tipo = 'info';
//-------------	
$tablax = $_SESSION['conexionsql']->query("UPDATE a_actualizacion SET presupuesto = '".date("Y-m-d H:i:00")."';");
//-------------	
$fecha = $_GET['tipo'] ;
$anno = $_POST['oanno'] ;
$fecha1 = voltea_fecha(trim($_POST['OFECHA1']));
$fecha2 = voltea_fecha(trim($_POST['OFECHA2']));
//------------
$_SESSION['anno'] = $_POST['oanno'] ;
//------------
if (trim($_POST['OFECHA1'])<>'' and trim($_POST['OFECHA2'])<>'') //and $fecha=2
	{	
	$consultx = "CALL actualizar_presupuesto_fecha_$anno('$fecha1');";
	$tablx = $_SESSION['conexionsql']->query($consultx);
	//------------ PASAR TODO A LA TABLA1
	$consultx = "DELETE FROM a_presupuesto_1;";	
	$tablx = $_SESSION['conexionsql']->query($consultx);
	$consultx = "INSERT INTO a_presupuesto_1 (SELECT * FROM a_presupuesto_$anno);";	
	$tablx = $_SESSION['conexionsql']->query($consultx);
	//------------
	$consultx = "CALL actualizar_presupuesto_fecha_$anno('$fecha2');";	
	$tablx = $_SESSION['conexionsql']->query($consultx);
	//------------ PASAR TODO A LA TABLA2
	$consultx = "DELETE FROM a_presupuesto_2;";	
	$tablx = $_SESSION['conexionsql']->query($consultx);
	$consultx = "INSERT INTO a_presupuesto_2 (SELECT * FROM a_presupuesto_$anno);";	
	$tablx = $_SESSION['conexionsql']->query($consultx);
	//------------ LIMPIAR LA TABLA Y CALCULAR
	$consultx = "UPDATE a_presupuesto_$anno SET original = 0, modificado = 0, ajustado = 0, creditos = 0, ingreso = 0, egreso = 0, compromiso = 0, causado = 0, pagado = 0, disponible = 0;";	
	$tablx = $_SESSION['conexionsql']->query($consultx);
	$consultx = "UPDATE a_presupuesto_$anno, a_presupuesto_1 , a_presupuesto_2 SET a_presupuesto_$anno.original = a_presupuesto_1.disponible , a_presupuesto_$anno.ingreso = a_presupuesto_2.ingreso - a_presupuesto_1.ingreso , a_presupuesto_$anno.egreso = a_presupuesto_2.egreso - a_presupuesto_1.egreso , a_presupuesto_$anno.ajustado = a_presupuesto_2.ajustado - a_presupuesto_1.ajustado , a_presupuesto_$anno.creditos = a_presupuesto_2.creditos - a_presupuesto_1.creditos , a_presupuesto_$anno.compromiso = a_presupuesto_2.compromiso - a_presupuesto_1.compromiso , a_presupuesto_$anno.causado = a_presupuesto_2.causado - a_presupuesto_1.causado , a_presupuesto_$anno.modificado = a_presupuesto_2.modificado , a_presupuesto_$anno.disponible = a_presupuesto_2.disponible WHERE	a_presupuesto_$anno.id = a_presupuesto_1.id AND a_presupuesto_$anno.id = a_presupuesto_2.id;";
	$tablx = $_SESSION['conexionsql']->query($consultx);
	//--------------
	$msg = 'Ejecucion Presupuestaria desde el '.trim($_POST['OFECHA1']) .' al '. trim($_POST['OFECHA2']);
	}
else
	{	
	$consultx = "CALL actualizar_presupuesto_$anno();";	
	$tablx = $_SESSION['conexionsql']->query($consultx);
	$msg = 'Ejecucion Presupuestaria Actualizada Correctamente';
	}

//-------------	
$info = array ("msg"=>$msg, "tipo"=>$tipo);
echo json_encode($info);
?>