<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');
//--------
$info = array();
$tipo = 'info';
//-------------
if (trim($_POST['txt_detalle'])<>'' and trim($_POST['txt_cedula'])<>''  and trim($_POST['txt_hasta'])<>'' )
{
	$hasta = voltea_fecha(extrae_fecha($_POST['txt_hasta']));
	$hora1 = (extrae_hora_laboral($_POST['txt_hasta']));
	$horaa = (extrae_hora($_POST['txt_hasta']));
	//----------------
	$consultx = "INSERT INTO arc_prestamos (contenido, id_expendiente, fecha, descripcion, funcionario, hasta, hora1, horaa, usuario) VALUES ('','".$_GET['id']."', '".date('Y/m/d')."', '".trim($_POST['txt_detalle'])."', '".($_POST['txt_cedula'])."', '$hasta', '$hora1', '$horaa', '".$_SESSION['CEDULA_USUARIO']."')";
	$tablx = $_SESSION['conexionsql']->query($consultx); echo $consultx;
	//------------
	$id = 0;
	//-------------	
	$mensaje = "Información Registrada Exitosamente!";
	//----------------
	$consultx = "UPDATE arc_prestamos, rac, arc_biblioteca SET arc_prestamos.contenido = arc_biblioteca.descripcion, arc_prestamos.grupo = arc_biblioteca.grupo, arc_prestamos.numero = arc_biblioteca.numero, arc_prestamos.id_direccion = rac.id_div, arc_prestamos.id_area = rac.id_area WHERE	arc_prestamos.id_expendiente = arc_biblioteca.id AND arc_prestamos.funcionario = rac.cedula AND (arc_prestamos.id_area = 0);";
	$tablx = $_SESSION['conexionsql']->query($consultx);	
	//------------
}
else
{
	$tipo = 'alerta';
	$mensaje = "Existen Campos Vacíos!";
}

//-------------
$info = array ("tipo"=>$tipo, "msg"=>$mensaje, "id"=>$id);

echo json_encode($info);
?>