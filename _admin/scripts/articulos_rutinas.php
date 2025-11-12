<?php
//session_start();
//error_reporting(0);

require_once __DIR__ . '../../../scripts/conexion.php';

class CrudAdminArticulos{

    protected $db;

    public function __construct()
    {
        $this->db = DB();
    }

    public function Listar()
    {
        $query = $this->db->prepare("SELECT articulos.id, articulos.fecha, articulos.titulo, articulos.descripcion, articulos.image FROM articulos");
        $query->execute();
        $data = array();
        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $data[] = $row;
        }

        return json_encode(['resultado' => $data]);
    }

    public function Buscar($id)
    {
        $query = $this->db->prepare("SELECT articulos.titulo, articulos.descripcion, articulos.image FROM articulos WHERE id=$id");
        $query->execute();
        $data = array();
        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $data[] = $row;
        }

        return json_encode($data);
    }

    public function Agregar($file, $titulo, $descripcion, $usuario)
    {
    	$ruta = 'images/art/'.$file;
        $permitido = false;
        $mensaje = 'Problemas la registrar el articulo';
       
       //INSERTAMOS EL REGISTRO
        $transaccion = false;
        //$this->db->beginTransaction();
        $sql = "CALL sp_add_articulo(?,?,?,?)";
        $query = $this->db->prepare($sql);
        $query->bindParam("?,?,?,?", $titulo, $descripcion, $ruta, $usuario);
        $transaccion = $query->execute(array($titulo, $descripcion, $ruta, $usuario));
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
            $mensaje = 'Problemas la registrar el articulo';
        }
       
        $data = array(
            "permitido" => $permitido,
            "mensaje" => $mensaje 
        );

        return json_encode(['articulo' => $data]);
    }

    public function Editar($id, $titulo, $descripcion, $usuario, $file, $eliminar_ruta)
    {
        if ($file != $eliminar_ruta)
        {
            $ruta = 'images/art/'.$file;            
        }
        else {
            $ruta = $file;
            $eliminar_ruta='';            
        }
            $usuario = '8632565';
        $permitido = false;
        $mensaje = 'Problemas al actualizar el articulo';
        $archivo = '../../'.$eliminar_ruta;
       
       //INSERTAMOS EL REGISTRO
        $transaccion = false;
        //$this->db->beginTransaction();
        $sql = "CALL sp_editar_articulo(?,?,?,?,?)";
        $query = $this->db->prepare($sql);
        $query->bindParam("?,?,?,?,?", $id, $titulo, $descripcion, $ruta, $usuario);
        $transaccion = $query->execute(array($id, $titulo, $descripcion, $ruta, $usuario));
        if ($transaccion)
        {
            //$this->db->commit();
            $permitido = true;
            $mensaje = 'Registro actualizado con éxito';
            if ($eliminar_ruta != '')
            {
                unlink($archivo);
            }
        }
        else
        {
            //$this->db->rollback();
            $permitido = false;
            $mensaje = 'Problemas al actualizar el articulo';
        }
       
        $data = array(
            "permitido" => $permitido,
            "mensaje" => $mensaje 
        );

        return json_encode(['articulo' => $data]);
    }

	public function Eliminar($id)
    {
        $permitido = false;
        $mensaje = '';

        //SELECCIONAMOS EL ARCHIVO DE IMAGEN A ELIMINAR
        $query = $this->db->prepare("SELECT image FROM articulos WHERE id = $id;");
        $query->execute();
        $row = $query->fetch(PDO::FETCH_ASSOC);
        $archivo = '../../'.$row['image'];

		//REALIZAMOS LA ELIMINACION DEL TEMPORAL DE DETALLE DE LA DECLARACION
        $transaccion = false;
        //$this->db->beginTransaction();
        $sql = "DELETE FROM articulos WHERE id=?";
        $query = $this->db->prepare($sql);
        $query->bindParam("?", $id);
        $transaccion = $query->execute(array($id));

        if ($transaccion)
        {
            //$this->db->commit();
            $permitido = true;
            $mensaje = 'Articulo eliminado con éxito';
            unlink($archivo);
        }
        else
        {
            //$this->db->rollback();
            $permitido = false;
            $mensaje = 'Problemas al eliminar el articulo';
        }
  
        $data = array(
            "permitido" => $permitido,
            "mensaje" => $mensaje,
            "archivo" => $archivo
        );

        return json_encode(['resultado' => $data]);
  
    }
}
