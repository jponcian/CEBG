<?php
//session_start();
//error_reporting(0);
require_once __DIR__ . '../../../scripts/conexion.php';
class CrudAdminVehiculos{

    protected $db;
    public function __construct()
    {
        $this->db = DB();
    }

    public function Listar()
    {
        $query = $this->db->prepare("SELECT vehiculo.placa AS numero, contribuyente.rif AS rif, vehiculo.id AS id, vehiculo.id_contribuyente AS id_contribuyente, contribuyente.nombre AS nombre, contribuyente.domicilio AS direccion, vehiculo.marca AS marca, vehiculo.anno AS anno, vehiculo.modelo AS modelo, vehiculo.color AS color FROM ((( contribuyente JOIN dir_ciudades ) JOIN dir_estados ) JOIN vehiculo ) WHERE vehiculo.id_contribuyente = contribuyente.id AND dir_ciudades.id_ciudad = contribuyente.ciudad AND dir_estados.id_estado = contribuyente.estado"); 
        $query->execute();
        $data = array();
        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $data[] = $row;
        }

        return json_encode(['resultado' => $data]);
    }

    public function buscarNumero($numero)
    {
        $query = $this->db->prepare("SELECT vehiculo.id, vehiculo.placa FROM vehiculo WHERE vehiculo.placa = '$numero'"); 
        $query->execute();
        $data = array();
        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $data[] = $row;
        }

        return json_encode(['resultado' => $data]);
    }

    public function buscarVehiculo($filtrar)
    {
        $query = $this->db->prepare("SELECT vehiculo.placa AS numero, contribuyente.rif AS rif, vehiculo.id AS id, vehiculo.id_contribuyente AS id_contribuyente, contribuyente.nombre AS nombre, contribuyente.domicilio AS direccion, vehiculo.marca AS marca, vehiculo.anno AS anno, vehiculo.modelo AS modelo, vehiculo.color AS color FROM ((( contribuyente JOIN dir_ciudades ) JOIN dir_estados ) JOIN vehiculo ) WHERE vehiculo.id_contribuyente = contribuyente.id AND dir_ciudades.id_ciudad = contribuyente.ciudad AND dir_estados.id_estado = contribuyente.estado AND $filtrar"); 
        $query->execute();
        $data = array();
        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $data[] = $row;
        }

        return json_encode(['resultado' => $data]);
    }

    public function Agregar($id_contribuyente,$placa,$marca,$modelo,$anno,$color,$usuario)
    {

       //INSERTAMOS EL REGISTRO

        $transaccion = false;
        //$this->db->beginTransaction();
        $sql = "INSERT INTO vehiculo (id_contribuyente,placa,marca,modelo,anno,color,usuario) VALUES (?,?,?,?,?,?,?)";
        $query = $this->db->prepare($sql);
        $query->bindParam("?,?,?,?,?,?,?", $id_contribuyente, $placa, $marca, $modelo, $anno, $color, $usuario);
        $transaccion = $query->execute(array($id_contribuyente, $placa, $marca, $modelo, $anno, $color, $usuario));

        if ($transaccion)
        {
            //$this->db->commit();
            $permitido = true;
            $mensaje = 'Registro agregado con éxito';
        }
        else
        {
            //$this->db->rollback();
            $permitido = false;
            $mensaje = 'Problemas al registrar la patente';
        }

        $data = array(
            "permitido" => $permitido,
            "mensaje" => $mensaje 
        );

        return json_encode(['vehiculo' => $data]);
    }

    public function Editar($id,$id_contribuyente,$placa,$marca,$modelo,$anno,$color,$usuario)
    {
        
       //INSERTAMOS EL REGISTRO
        $transaccion = false;
        //$this->db->beginTransaction();
        $sql = "UPDATE vehiculo SET id_contribuyente = ?, placa=?, marca=?, modelo=?, anno=?, color=?, usuario=? WHERE id = ?";
        $query = $this->db->prepare($sql);
        $query->bindParam("?,?,?,?,?,?,?,?", $id_contribuyente, $placa, $marca, $modelo, $anno, $color, $usuario, $id);
        $transaccion = $query->execute(array($id_contribuyente, $placa, $marca, $modelo, $anno, $color, $usuario, $id));
        if ($transaccion)
        {
            //$this->db->commit();
            $permitido = true;
            $mensaje = 'Registro actualizado con éxito';
            unlink($archivo);
        }
        else
        {
            //$this->db->rollback();
            $permitido = false;
            $mensaje = 'Problemas al actualizar la patente';
        }

        $data = array(
            "permitido" => $permitido,
            "mensaje" => $mensaje 
        );

        return json_encode(['vehiculo' => $data]);
    }

    public function Eliminar($id)
    {
        $permitido = false;
        $mensaje = '';

        //REALIZAMOS LA ELIMINACION DEL TEMPORAL DE DETALLE DE LA DECLARACION
        $transaccion = false;
        //$this->db->beginTransaction();
        //$sql = "DELETE FROM patente WHERE id=? and id not in (SELECT DISTINCTROW id_patente FROM contribuyente)";
        $sql = "DELETE FROM vehiculo WHERE id=?";
        $query = $this->db->prepare($sql);
        $query->bindParam("?", $id);
        $transaccion = $query->execute(array($id));

        if ($transaccion)
        {
            //$this->db->commit();
            $permitido = true;
            $mensaje = 'Vehiculo eliminada con éxito';
        }
        else
        {
            //$this->db->rollback();
            $permitido = false;
            $mensaje = 'Problemas al eliminar el vehiculo';
        }

        $data = array(
            "permitido" => $permitido,
            "mensaje" => $mensaje
        );

        return json_encode(['resultado' => $data]);
    }

}