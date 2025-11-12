<?php
//session_start();
//error_reporting(0);

require_once __DIR__ . '../../../scripts/conexion.php';

class CrudAdminActividades{

    protected $db;

    public function __construct()
    {
        $this->db = DB();
    }

    public function Listar()
    {
        $query = $this->db->prepare("SELECT actividades.id, actividades.codigo, actividades.descripcion, actividades.tasa, actividades.usuario, actividades.fecha_proceso FROM actividades WHERE actividades.tasa > 0");
        $query->execute();
        $data = array();
        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $data[] = $row;
        }

        return json_encode(['resultado' => $data]);
    }

    public function ListarTmp($id_patente, $numero)
    {
        $permitido = false;
        $mensaje = 'Problemas al registrar la actividad';
       
        $transaccion = false;
        $sql = "CALL sp_add_patente_detalletmp(?,?)";
        $query = $this->db->prepare($sql);
        $query->bindParam("?,?", $id_patente, $numero);
        $transaccion = $query->execute(array($id_patente, $numero));
        $data = array();
        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $data[] = $row;
        }

        return json_encode(['resultado' => $data]);        
    }

    public function ListarTmpEditando($numero)
    {
        //sleep(5);
        $temp = $this->db->prepare("SELECT patente_detalle_tmp.id_patente, patente_detalle_tmp.id_actividad, actividades.codigo, actividades.descripcion FROM actividades INNER JOIN patente_detalle_tmp ON actividades.id = patente_detalle_tmp.id_actividad INNER JOIN patente ON patente.id = patente_detalle_tmp.id_patente WHERE patente_detalle_tmp.accion = 1 AND patente.id = $numero");
        $temp->execute();
        while ($row = $temp->fetch(PDO::FETCH_ASSOC)) {
            $data[] = $row;
        }

        return json_encode(['resultado' => $data]);
    }

    public function ListarTmpAgregar($numero)
    {
        $temp = $this->db->prepare("SELECT patente_detalle_tmp.id_patente, patente_detalle_tmp.id_actividad, actividades.codigo, actividades.descripcion FROM patente_detalle_tmp INNER JOIN actividades ON actividades.id = patente_detalle_tmp.id_actividad WHERE patente_detalle_tmp.numero = '$numero' and patente_detalle_tmp.accion=0");
        $temp->execute();
        while ($row = $temp->fetch(PDO::FETCH_ASSOC)) {
            $data[] = $row;
        }

        return json_encode(['resultado' => $data]);
    }

    public function Buscar($codigo)
    {
        $query = $this->db->prepare("SELECT actividades.id FROM actividades WHERE codigo=$codigo");
        $query->execute();
        $data = array();
        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $data[] = $row;
        }

        return json_encode($data);
    }

    public function Agregar($codigo,$descripcion,$tasa,$usuario)
    {
        $permitido = false;
        $mensaje = 'Problemas al registrar la actividad';
       
       //INSERTAMOS EL REGISTRO
        $transaccion = false;
        //$this->db->beginTransaction();
        $sql = "INSERT INTO actividades (codigo, descripcion, tasa, usuario) VALUES (?,?,?,?)";
        $query = $this->db->prepare($sql);
        $query->bindParam("?,?,?,?", $codigo, $descripcion, $tasa, $usuario);
        $transaccion = $query->execute(array($codigo, $descripcion, $tasa, $usuario));
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
            $mensaje = 'Problemas al registrar la actividad';
        }
       
        $data = array(
            "permitido" => $permitido,
            "mensaje" => $mensaje 
        );

        return json_encode(['actividad' => $data]);
    }

    public function Editar($id, $codigo,$descripcion,$tasa,$usuario)
    {
        $permitido = false;
        $mensaje = 'Problemas al actualizar el slider';
       
       //INSERTAMOS EL REGISTRO
        $transaccion = false;
        //$this->db->beginTransaction();
        $sql = "UPDATE actividades SET codigo=?, descripcion=?, tasa=?  WHERE id=?";
        $query = $this->db->prepare($sql);
        $query->bindParam("?,?,?,?", $codigo, $descripcion, $tasa, $id);
        $transaccion = $query->execute(array($codigo, $descripcion, $tasa, $id));
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
            $mensaje = 'Problemas al actualizar la actividad';
        }
       
        $data = array(
            "permitido" => $permitido,
            "mensaje" => $mensaje 
        );

        return json_encode(['actividad' => $data]);
    }

	public function Eliminar($id)
    {
        $permitido = false;
        $mensaje = '';

     	//REALIZAMOS LA ELIMINACION DEL TEMPORAL DE DETALLE DE LA DECLARACION
        $transaccion = false;
        //$this->db->beginTransaction();
        $sql = "DELETE FROM actividades WHERE id=?";
        $query = $this->db->prepare($sql);
        $query->bindParam("?", $id);
        $transaccion = $query->execute(array($id));

        if ($transaccion)
        {
            //$this->db->commit();
            $permitido = true;
            $mensaje = 'Actividad eliminada con éxito';
        }
        else
        {
            //$this->db->rollback();
            $permitido = false;
            $mensaje = 'Problemas al eliminar la actividad';
        }
  
        $data = array(
            "permitido" => $permitido,
            "mensaje" => $mensaje
        );

        return json_encode(['resultado' => $data]);
  
    }

    public function EliminarTmp($id,$numero,$usuario)
    {
        $permitido = false;
        $mensaje = '';

        //REALIZAMOS LA ELIMINACION DEL TEMPORAL DE DETALLE DE LA DECLARACION
        $transaccion = false;
        //$this->db->beginTransaction();
        $sql = "DELETE FROM patente_detalle_tmp WHERE numero=? and id_actividad=?";
        $query = $this->db->prepare($sql);
        $query->bindParam("?,?", $numero, $id);
        $transaccion = $query->execute(array($numero, $id));

        if ($transaccion)
        {
            //$this->db->commit();
            $permitido = true;
            $mensaje = 'Temporal eliminado con éxito';
        }
        else
        {
            //$this->db->rollback();
            $permitido = false;
            $mensaje = 'Problemas al eliminar el temporal';
        }
  
        $data = array(
            "permitido" => $permitido,
            "mensaje" => $mensaje
        );

        return json_encode($data);
  
    }

   public function EliminarTmpAll($numero,$usuario)
    {
        $permitido = false;
        $mensaje = '';

        //REALIZAMOS LA ELIMINACION DEL TEMPORAL DE DETALLE DE LA DECLARACION
        $transaccion = false;
        //$this->db->beginTransaction();
        $sql = "DELETE FROM patente_detalle_tmp WHERE id_patente=? and accion=1";
        $query = $this->db->prepare($sql);
        $query->bindParam("?", $numero);
        $transaccion = $query->execute(array($numero));

        if ($transaccion)
        {
            //$this->db->commit();
            $permitido = true;
            $mensaje = 'Temporal eliminado con éxito';
        }
        else
        {
            //$this->db->rollback();
            $permitido = false;
            $mensaje = 'Problemas al eliminar el temporal';
        }
  
        $data = array(
            "permitido" => $permitido,
            "mensaje" => $mensaje
        );

        return json_encode($data);
  
    }

    public function EliminarTmpCarga($usuario)
    {
        $permitido = false;
        $mensaje = '';

        //REALIZAMOS LA ELIMINACION DEL TEMPORAL DE DETALLE DE LA DECLARACION
        $transaccion = false;
        //$this->db->beginTransaction();
        $sql = "DELETE FROM patente_detalle_tmp WHERE usuario=?";
        $query = $this->db->prepare($sql);
        $query->bindParam("?", $usuario);
        $transaccion = $query->execute(array($usuario));

        if ($transaccion)
        {
            //$this->db->commit();
            $permitido = true;
            $mensaje = 'Temporal eliminado con éxito';
        }
        else
        {
            //$this->db->rollback();
            $permitido = false;
            $mensaje = 'Problemas al eliminar el temporal';
        }
  
        $data = array(
            "permitido" => $permitido,
            "mensaje" => $mensaje
        );

        return json_encode($data);
  
    }

}
