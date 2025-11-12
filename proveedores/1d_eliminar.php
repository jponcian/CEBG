<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');

header('Content-Type: application/json');

if ($_SESSION['VERIFICADO'] != 'SI') {
    echo json_encode(['tipo' => 'error', 'msg' => 'Sesión no válida']);
    exit();
}

$id = intval($_POST['id'] ?? 0);
if ($id <= 0) {
    echo json_encode(['tipo' => 'alerta', 'msg' => 'ID inválido']);
    exit();
}

// Respaldar y eliminar (siguiendo lógica similar a admin contribuyente)
$ok = true;
$sql = "INSERT INTO contribuyente_ (SELECT * FROM contribuyente WHERE id=$id)";
$ok = $ok && $_SESSION['conexionsql']->query($sql);
$sql = "DELETE FROM contribuyente WHERE id NOT IN (SELECT id_contribuyente FROM orden GROUP BY id_contribuyente) AND id<>1000 AND id=$id";
$ok = $ok && $_SESSION['conexionsql']->query($sql);

if ($ok) {
    echo json_encode(['tipo' => 'info', 'msg' => 'Proveedor eliminado con éxito']);
} else {
    echo json_encode(['tipo' => 'error', 'msg' => 'No se pudo eliminar el proveedor']);
}
