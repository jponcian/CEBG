<?php
session_start();
include_once "../conexion.php";
header('Content-Type: application/json');
if ($_SESSION['VERIFICADO'] != 'SI') {
    echo json_encode(['id' => 0]);
    exit;
}
$rif = isset($_GET['rif']) ? $_SESSION['conexionsql']->real_escape_string($_GET['rif']) : '';
// Normalizar: quitar guiones, espacios y puntos para comparar
$rif = strtoupper($rif);
$rif = preg_replace('/[^A-Z0-9]/', '', $rif);
$id = 0;
if ($rif !== '') {
    $sql = "SELECT id FROM contribuyente WHERE REPLACE(REPLACE(REPLACE(UPPER(rif), '-', ''), ' ', ''), '.', '')='$rif'";
    $tab = $_SESSION['conexionsql']->query($sql);
    if ($tab && $tab->num_rows > 0) {
        $row = $tab->fetch_object();
        $id = intval($row->id);
    }
}
echo json_encode(['id' => $id]);
