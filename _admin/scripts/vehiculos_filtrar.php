<?php
$data = json_decode(file_get_contents('php://input'), TRUE);

$dato_buscar = $data['buscar']['dato'];
$filtro = $data['buscar']['filtro'];
//$numero = $_POST['numero'];

switch ($filtro) {
    case 1:
        $filtrar = "vehiculo.placa LIKE '%{$dato_buscar}%'";
        break;
    case 2:
        $filtrar = "contribuyente.rif LIKE '%{$dato_buscar}%'";
        break;
    case 3:
        $filtrar = "contribuyente.nombre LIKE '%{$dato_buscar}%'";
        break;
}

require __DIR__ . '/vehiculos_rutinas.php';

$vehiculo = new CrudAdminVehiculos();

echo $vehiculo->buscarVehiculo($filtrar);
//echo $filtrar;
?>