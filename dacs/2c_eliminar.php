<?php
session_start();
include_once "../conexion.php";
//----------------
$id = $_POST['id']; 
$_SESSION['conexionsql']->query("DELETE FROM a_atencion_dacs WHERE id=$id AND id NOT IN (SELECT
	id_atencion
FROM
	dacs_atencion_gestion
GROUP BY
	dacs_atencion_gestion.id_atencion)");	
?>