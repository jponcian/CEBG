<?php

$data = json_decode(file_get_contents('php://input'), TRUE);

$nombre = $data['tabla']['nombre'] ?? '';
$estado = intval($data['tabla']['estado'] ?? 0);

require __DIR__ . '/../scripts/conexion.php';

class ZCrud
{
    protected $db;
    public function __construct()
    {
        $this->db = DB();
    }
    public function listar($nombre, $estado)
    {
        if ($nombre === 'estado') {
            $sql = "SELECT dir_estados.id_estado as id, dir_estados.descripcion FROM dir_estados ORDER BY dir_estados.descripcion";
        } elseif ($nombre === 'ciudad') {
            $sql = "SELECT dir_ciudades.id_ciudad as id, dir_ciudades.descripcion FROM dir_ciudades WHERE dir_ciudades.id_estado = $estado ORDER BY dir_ciudades.descripcion";
        } else {
            $sql = "SELECT dir_zonas.id_zona as id, dir_zonas.descripcion FROM dir_zonas ORDER BY dir_zonas.descripcion";
        }
        $q = $this->db->prepare($sql);
        $q->execute();
        $data = [];
        while ($row = $q->fetch(PDO::FETCH_ASSOC)) {
            $data[] = $row;
        }
        header('Content-Type: application/json');
        echo json_encode(['zonificacion' => $data]);
    }
}

$api = new ZCrud();
$api->listar($nombre, $estado);
