<?php
//session_start();
//error_reporting(0);

require_once __DIR__ . '../../../scripts/conexion.php';

class CrudAdminSlider{

    protected $db;

    public function __construct()
    {
        $this->db = DB();
    }

    public function Listar()
    {
        $query = $this->db->prepare("SELECT slide_images.id, slide_images.ruta, slide_images.descripcion FROM slide_images");
        $query->execute();
        $data = array();
        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $data[] = $row;
        }

        return json_encode(['slides' => $data]);
    }

    public function Buscar($id)
    {
        $query = $this->db->prepare("SELECT  slide_images.ruta FROM slide_images WHERE id=$id");
        $query->execute();
        $data = array();
        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $data[] = $row;
        }

        return json_encode($data);
    }

    public function Agregar($file)
    {
    	$ruta = 'images/sliders/'.$file;
    	$usuario = '8632565';
        $permitido = false;
        $mensaje = 'Problemas la registrar el slider';
       
       //INSERTAMOS EL REGISTRO
        $transaccion = false;
        //$this->db->beginTransaction();
        $sql = "INSERT INTO slide_images (ruta, descripcion, usuario) VALUES (?,?,?)";
        $query = $this->db->prepare($sql);
        $query->bindParam("?,?,?", $ruta, $file, $usuario);
        $transaccion = $query->execute(array($ruta, $file, $usuario));
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
            $mensaje = 'Problemas la registrar el slider';
        }
       
        $data = array(
            "permitido" => $permitido,
            "mensaje" => $mensaje 
        );

        return json_encode(['slider' => $data]);
    }

    public function Editar($file, $id, $eliminar_ruta)
    {
        $ruta = 'images/sliders/'.$file;
        $usuario = '8632565';
        $permitido = false;
        $mensaje = 'Problemas al actualizar el slider';
        $archivo = '../../'.$eliminar_ruta;
       
       //INSERTAMOS EL REGISTRO
        $transaccion = false;
        //$this->db->beginTransaction();
        $sql = "UPDATE slide_images SET ruta=?, descripcion=?  WHERE id=?";
        $query = $this->db->prepare($sql);
        $query->bindParam("?,?,?", $ruta, $file, $id);
        $transaccion = $query->execute(array($ruta, $file, $id));
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
            $mensaje = 'Problemas al actualizar el slider';
        }
       
        $data = array(
            "permitido" => $permitido,
            "mensaje" => $mensaje 
        );

        return json_encode(['slider' => $data]);
    }

	public function Eliminar($id)
    {
        $permitido = false;
        $mensaje = '';

        //SELECCIONAMOS EL ARCHIVO DE IMAGEN A ELIMINAR
        $query = $this->db->prepare("SELECT ruta FROM slide_images WHERE id = $id;");
        $query->execute();
        $row = $query->fetch(PDO::FETCH_ASSOC);
        $archivo = '../../'.$row['ruta'];

		//REALIZAMOS LA ELIMINACION DEL TEMPORAL DE DETALLE DE LA DECLARACION
        $transaccion = false;
        //$this->db->beginTransaction();
        $sql = "DELETE FROM slide_images WHERE id=?";
        $query = $this->db->prepare($sql);
        $query->bindParam("?", $id);
        $transaccion = $query->execute(array($id));

        if ($transaccion)
        {
            //$this->db->commit();
            $permitido = true;
            $mensaje = 'Slider eliminado con éxito';
            unlink($archivo);
        }
        else
        {
            //$this->db->rollback();
            $permitido = false;
            $mensaje = 'Problemas al eliminar el slider';
        }
  
        $data = array(
            "permitido" => $permitido,
            "mensaje" => $mensaje,
            "archivo" => $archivo
        );

        return json_encode(['resultado' => $data]);
  
    }
}