<?php
session_start();
include_once "../conexion.php";
//--------
$info = array();
//-------------
if (trim($_POST['id'])<>'' and trim($_POST['id'])<>'0')
	{
	$consulta_x = 'SELECT * FROM vista_contribuyentes_direccion WHERE rif<>"" AND rif LIKE "%'.trim($_POST['id']).'%"'; 
	$tabla_x = $_SESSION['conexionsql']->query($consulta_x);
	if ($tabla_x->num_rows>0)
		{
		$registro_x = $tabla_x->fetch_object();
		$id_rif = $registro_x->id;
		$rif = $registro_x->rif;
		$contribuyente = $registro_x->contribuyente;
		$direccion = $registro_x->direccion;
		$credito = ($registro_x->credito);
		$info = array ("tipo"=>"info", "id_rif"=>$id_rif, "rif"=>$rif, "contribuyente"=>$contribuyente, "direccion"=>$direccion, "credito"=>$credito);
		}
	else
		{
		$info = array ("tipo"=>"alerta", "msg"=>"El Contribuyente no esta registrado en la base de datos...");
		}
	}
else
	{
	$id_rif = 0;
	$rif = 0;
	$contribuyente = '';
	$info = array ("tipo"=>"info", "id_rif"=>$id_rif, "rif"=>$rif, "contribuyente"=>$contribuyente, "direccion"=>$direccion, "credito"=>$credito);
	}
echo json_encode($info);
?>