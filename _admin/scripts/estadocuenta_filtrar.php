<?php

$data = json_decode(file_get_contents('php://input'), TRUE);

$dato_buscar = $data['buscar']['dato'];
$filtro = $data['buscar']['filtro'];

//$numero = $_POST['numero'];

switch ($filtro) {
    case 1:
        $filtrar = "patente.numero = '$dato_buscar'";
        break;
    case 2:
        $filtrar = "patente.rif LIKE '%{$dato_buscar}%'";
        break;
    case 3:
        $filtrar = "patente.descripcion_establecimiento LIKE '%{$dato_buscar}%'";
        break;
}

require __DIR__ . '/patentes_rutinas.php';

$estadocuenta = new CrudAdminPatentes();

echo $estadocuenta->ListarEstadoCuenta($filtrar);
//echo $filtrar;

?>

