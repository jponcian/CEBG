<?php
session_start();
include_once "../conexion.php";
include_once('../funciones/auxiliar_php.php');

header('Content-Type: application/json');

if ($_SESSION['VERIFICADO'] != 'SI') {
    echo json_encode(['tipo' => 'error', 'msg' => 'Sesión no válida']);
    exit();
}

$id = isset($_POST['oid']) ? intval($_POST['oid']) : 0;
// Normalizar RIF/Cédula: quitar guiones, espacios y no alfanuméricos; mayúsculas
$rif = strtoupper(trim($_POST['rif'] ?? ''));
$rif = preg_replace('/[^A-Z0-9]/', '', $rif);
$nombre = strtoupper(trim($_POST['nombre'] ?? ''));
$domicilio = strtoupper(trim($_POST['direccion'] ?? ''));
$ciudad = intval($_POST['ciudad'] ?? 0);
$estado = intval($_POST['estado'] ?? 0);
$representante = strtoupper(trim($_POST['representante'] ?? ''));
$ced_representante = strtoupper(trim($_POST['cedula'] ?? ''));
$ced_representante = preg_replace('/[^A-Z0-9]/', '', $ced_representante);
$cel_contacto = trim($_POST['celular'] ?? '');
$email = trim($_POST['correo'] ?? '');
$usuario = $_SESSION['CEDULA_USUARIO'] ?? '';

if ($rif === '' || $nombre === '' || $domicilio === '' || $ciudad === 0 || $estado === 0 || $representante === '' || $ced_representante === '' || $cel_contacto === '' || $email === '') {
    echo json_encode(['tipo' => 'alerta', 'msg' => 'Datos requeridos vacíos, verifique']);
    exit();
}

// Verificar RIF duplicado (comparación normalizada para admitir valores previos con guiones/espacios)
$rifExiste = false;
$rifEsc = $_SESSION['conexionsql']->real_escape_string($rif);
$rifQuery = "SELECT id FROM contribuyente 
             WHERE REPLACE(REPLACE(REPLACE(UPPER(rif), '-', ''), ' ', ''), '.', '') = '$rifEsc'";
if ($id > 0) {
    $rifQuery .= " AND id<>$id";
}
$tab = $_SESSION['conexionsql']->query($rifQuery);
if ($tab && $tab->num_rows > 0) {
    $rifExiste = true;
}
if ($rifExiste) {
    echo json_encode(['tipo' => 'alerta', 'msg' => 'RIF ya registrado']);
    exit();
}

if ($id > 0) {
    // Update
    $sql = "UPDATE contribuyente SET rif='$rif', nombre='$nombre', domicilio='$domicilio', ciudad=$ciudad, estado=$estado, zona=1, representante='$representante', ced_representante='$ced_representante', cel_contacto='$cel_contacto', email='$email', usuario='$usuario' WHERE id=$id";
    $ok = $_SESSION['conexionsql']->query($sql);
    if ($ok) {
        echo json_encode(['tipo' => 'info', 'msg' => 'Proveedor modificado con éxito']);
    } else {
        echo json_encode(['tipo' => 'error', 'msg' => 'Problemas al modificar el proveedor']);
    }
} else {
    // Insert
    $sql = "INSERT INTO contribuyente (rif,nombre,domicilio,ciudad,estado,zona,representante,ced_representante,cel_contacto,email,usuario,fecha_proceso) VALUES ('$rif','$nombre','$domicilio',$ciudad,$estado,1,'$representante','$ced_representante','$cel_contacto','$email','$usuario',NOW())";
    $ok = $_SESSION['conexionsql']->query($sql);
    if ($ok) {
        echo json_encode(['tipo' => 'info', 'msg' => 'Proveedor registrado con éxito']);
    } else {
        echo json_encode(['tipo' => 'error', 'msg' => 'Problemas al registrar el proveedor']);
    }
}
