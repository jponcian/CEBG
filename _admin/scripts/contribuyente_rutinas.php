<?php
//session_start();
//error_reporting(0);

require_once __DIR__ . '../../../scripts/conexion.php';

class CrudAdminContribuyente{

    protected $db;

    public function __construct()
    {
        $this->db = DB();
    }

    public function ListarZonificacion($nombre, $estado)
    {
        if ($nombre == "estado")
        {
            $sql = "SELECT dir_estados.id_estado as id, dir_estados.descripcion FROM dir_estados ORDER BY dir_estados.descripcion";
        } 
        else if ($nombre == "ciudad")
        {
            $sql = "SELECT dir_ciudades.id_ciudad as id, dir_ciudades.descripcion FROM dir_ciudades WHERE dir_ciudades.id_estado = $estado ORDER BY dir_ciudades.descripcion"; 
        } 
        else if ($nombre == "zona") 
        {
            $sql = "SELECT dir_zonas.id_zona as id, dir_zonas.descripcion FROM dir_zonas ORDER BY dir_zonas.descripcion ASC";
        }
        $query = $this->db->prepare($sql);
        $query->execute();
        $data = array();
        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $data[] = $row;
        }

        return json_encode(['zonificacion' => $data]);
    }

    public function Listar()
    {
        $query = $this->db->prepare("SELECT contribuyente.id, contribuyente.rif, contribuyente.nombre, contribuyente.domicilio, contribuyente.ciudad, contribuyente.estado, contribuyente.zona, contribuyente.representante, contribuyente.ced_representante, contribuyente.cel_contacto, contribuyente.email FROM contribuyente");
        $query->execute();
        $data = array();
        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $data[] = $row;
        }

        return json_encode(['contribuyentes' => $data]);
    }

    public function BuscarRif($rif)
    {
        $query = $this->db->prepare("SELECT contribuyente.id FROM contribuyente WHERE rif='$rif'");
        $query->execute();
        $cantidad = $query->RowCount();
        if ($cantidad > 0)
        {
            $row = $query->fetch(PDO::FETCH_ASSOC);
            $id = $row['id'];
        }
        else
        {
            $id = 0;
        }

        $data = array(
            "id" => $id
        );

        return json_encode($data);
    }

    public function BuscarRifDeclaracion($rif)
    {
        $query = $this->db->prepare("SELECT contribuyente.id, contribuyente.rif, contribuyente.nombre, contribuyente.domicilio, contribuyente.ciudad, contribuyente.estado, contribuyente.zona, contribuyente.representante, contribuyente.ced_representante, contribuyente.cel_contacto, contribuyente.email, contribuyente.usuario, contribuyente.fecha_proceso FROM contribuyente WHERE rif='$rif'");
        $query->execute();
        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $ced_representante = $row['ced_representante'];
            $cel_contacto = $row['cel_contacto'];
            $ciudad = $row['ciudad'];
            $domicilio = $row['domicilio'];
            $email = $row['email'];
            $estado = $row['estado'];
            $id = $row['id'];
            $nombre = $row['nombre'];
            $parroquia = $row['zona'];
            $representante = $row['representante'];
            $rif = $row['rif'];
        }

        $data = array(
            "ced_representante" => $ced_representante,
            "cel_contacto" => $cel_contacto,
            "ciudad" => $ciudad,
            "domicilio" => $domicilio,
            "email" => $email,
            "estado" => $estado,
            "id" => $id,
            "nombre" => $nombre,
            "parroquia" => $parroquia,
            "representante" => $representante,
            "rif" => $rif
        );
        return json_encode($data);
    }

    public function BuscarPatente($patente)
    {
        $query = $this->db->prepare("SELECT patente.id FROM patente WHERE patente.id NOT IN (SELECT DISTINCTROW contribuyente.id_patente FROM contribuyente) and patente.numero = $patente");
        $query->execute();
        $cantidad = $query->RowCount();
        if ($cantidad > 0)
        {
            $row = $query->fetch(PDO::FETCH_ASSOC);
            $id = $row['id'];
        }
        else
        {
            $id = 0;
        }

        $data = array(
            "id" => $id
        );

        return json_encode($data);
    }

    public function BuscarIdPatente($patente)
    {
        $query = $this->db->prepare("SELECT patente.id FROM patente WHERE patente.numero = '$patente'");
        $query->execute();
        $cantidad = $query->RowCount();
        if ($cantidad > 0)
        {
            $row = $query->fetch(PDO::FETCH_ASSOC);
            $id = $row['id'];
        }
        else
        {
            $id = 0;
        }

        $data = array(
            "id" => $id
        );

        return json_encode($data);
    }

    public function Agregar($rif,$nombre,$domicilio,$ciudad,$estado,$parroquia,$representante,$ced_representante,$cel_contacto,$email,$usuario)
    {
        $permitido = false;
        $mensaje = 'Problemas al registrar el contribuyente';

       //INSERTAMOS EL REGISTRO
        $transaccion = false;
        //$this->db->beginTransaction();
        $sql = "CALL sp_add_contribuyente(?,?,?,?,?,?,?,?,?,?,?)";
        $query = $this->db->prepare($sql);
        $query->bindParam("?,?,?,?,?,?,?,?,?,?,?", $rif, $nombre, $domicilio, $ciudad, $estado, $parroquia, $representante, $ced_representante, $cel_contacto, $email, $usuario);
        $transaccion = $query->execute(array($rif, $nombre, $domicilio, $ciudad, $estado, $parroquia, $representante, $ced_representante, $cel_contacto, $email, $usuario));

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
            $mensaje = 'Problemas al registrar el contribuyente';
        }
       
        $data = array(
            "permitido" => $permitido,
            "mensaje" => $mensaje 
        );

        return json_encode(['contribuyente' => $data]);
    }

    public function Editar($id,$rif,$nombre,$domicilio,$ciudad,$estado,$parroquia,$representante,$ced_representante,$cel_contacto,$email,$usuario)
    {
        $permitido = false;
        $mensaje = 'Problemas al registrar el contribuyente';

       //INSERTAMOS EL REGISTRO
        $transaccion = false;
        //$this->db->beginTransaction();
        $sql = "CALL sp_editar_contribuyente(?,?,?,?,?,?,?,?,?,?,?,?)";
        $query = $this->db->prepare($sql);
        $query->bindParam("?,?,?,?,?,?,?,?,?,?,?,?", $id, $rif, $nombre, $domicilio, $ciudad, $estado, $parroquia, $representante, $ced_representante, $cel_contacto, $email, $usuario);
        $transaccion = $query->execute(array($id, $rif, $nombre, $domicilio, $ciudad, $estado, $parroquia, $representante, $ced_representante, $cel_contacto, $email, $usuario));

        if ($transaccion)
        {
            //$this->db->commit();
            $permitido = true;
            $mensaje = 'Registro modificado con éxito';
        }
        else
        {
            //$this->db->rollback();
            $permitido = false;
            $mensaje = 'Problemas al modificar el contribuyente';
        }
       
        $data = array(
            "permitido" => $permitido,
            "mensaje" => $mensaje 
        );

        return json_encode(['contribuyente' => $data]);
    }


	public function Eliminar($id)
    {
        $permitido = false;
        $mensaje = '';

		//REALIZAMOS LA ELIMINACION DEL TEMPORAL DE DETALLE DE LA DECLARACION
        $transaccion = false;
        //$this->db->beginTransaction();
        $sql = "INSERT INTO contribuyente_ (SELECT * FROM contribuyente WHERE id=?)";
        $query = $this->db->prepare($sql);
        $query->bindParam("?", $id);
        $transaccion = $query->execute(array($id));
        $sql = "DELETE FROM contribuyente WHERE id NOT IN (SELECT id_contribuyente FROM orden GROUP BY id_contribuyente) AND id<>1000 and id=?";
        $query = $this->db->prepare($sql);
        $query->bindParam("?", $id);
        $transaccion = $query->execute(array($id));

        if ($transaccion)
        {
            //$this->db->commit();
            $permitido = true;
            $mensaje = 'Contribuyente eliminado con éxito';
        }
        else
        {
            //$this->db->rollback();
            $permitido = false;
            $mensaje = 'Problemas al eliminar el contribuyente';
        }
  
        $data = array(
            "permitido" => $permitido,
            "mensaje" => $mensaje
        );

        return json_encode(['resultado' => $data]);
  
    }
}