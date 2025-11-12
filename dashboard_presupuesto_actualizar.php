<?php
session_start();
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/conexion.php';

if (!isset($_SESSION['conexionsql']) || !($_SESSION['conexionsql'] instanceof mysqli)) {
    echo json_encode(['ok' => false, 'error' => 'Sin conexión activa']);
    exit;
}

$conn = $_SESSION['conexionsql'];
$conn->set_charset('utf8');

function try_select_function($conn)
{
    $sql = "SELECT actualizar_presupuesto_2025() AS res";
    $rs = $conn->query($sql);
    if ($rs) {
        $row = $rs->fetch_assoc();
        $res = isset($row['res']) ? $row['res'] : null;
        if ($rs instanceof mysqli_result) {
            $rs->free();
        }
        return [true, $res];
    }
    return [false, $conn->error];
}

function try_call_procedure($conn)
{
    $sql = "CALL actualizar_presupuesto_2025()";
    $ok = $conn->query($sql);
    // Consumir resultados adicionales si los hubiese
    if ($ok) {
        while ($conn->more_results() && $conn->next_result()) {
            if ($extra = $conn->store_result()) {
                $extra->free();
            }
        }
        return [true, null];
    }
    return [false, $conn->error];
}

list($ok, $info) = try_select_function($conn);
if (!$ok) {
    list($ok2, $info2) = try_call_procedure($conn);
    if (!$ok2) {
        echo json_encode(['ok' => false, 'error' => $info2 ?: $info]);
        exit;
    }
}

echo json_encode(['ok' => true, 'result' => $ok ? $info : null]);
