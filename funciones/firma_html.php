<?php

$consultx = "SELECT * FROM a_direcciones WHERE id=0".$jefe.";";
$tablx = $_SESSION['conexionsql']->query($consultx);
$registro_x = $tablx->fetch_object();

//---------------------------------
$cedula = "C.I. N° V-" .$registro_x->cedula;
$cargo = $registro_x->cargo;
$providencia = $registro_x->providencia;
$fecha_prov = $registro_x->fecha_prov;
$gaceta = $registro_x->gaceta;
$fechgac = $registro_x->fecha_gaceta;
$empleado = empleado($registro_x->cedula);
//---------------------------------
echo $empleado[1]."<br>";
echo $cargo."<br>";
echo ($providencia).' de fecha '.voltea_fecha($fecha_prov)."<br>";
echo ($gaceta).' de fecha '.voltea_fecha($fechgac)."<br>";
//----------------
?>