<?php
session_start();
include_once "../conexion.php";
//----------------
$id = $_POST['id']; 
$_SESSION['conexionsql']->query("INSERT INTO ordenes_pago_pagos_respaldo (SELECT *, curdate() FROM ordenes_pago_pagos WHERE id=$id)");
//-------------	
$tablx = $_SESSION['conexionsql']->query("SELECT id_orden, num_pago FROM ordenes_pago_pagos WHERE id=$id");
$registro_x = $tablx->fetch_object();
$orden = $registro_x->id_orden;
$referencia = $registro_x->num_pago;
//-------------	
$_SESSION['conexionsql']->query("DELETE FROM ordenes_pago_pagos WHERE id=$id");	
//-------------	
$_SESSION['conexionsql']->query("DELETE FROM estado_cuenta WHERE id_orden=$orden AND referencia='$referencia'");	
//-------------	
$consultax = "CALL actualizar_orden_pago_pagos(".$orden.");";
$tablax = $_SESSION['conexionsql']->query($consultax);
?>