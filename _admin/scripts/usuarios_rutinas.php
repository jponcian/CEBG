<?php
//session_start();
//error_reporting(0);

require_once __DIR__ . '../../../scripts/conexion.php';
require_once __DIR__ . '../../../scripts/funciones.php';
require_once __DIR__ . '../../../conexion.php';

//$consulta = "DELETE FROM usuarios_accesos;";
//$tabla = $_SESSION['conexionsql']->query($consulta);

$consulta = "UPDATE usuarios, rac, bn_dependencias SET usuarios.id_direccion = rac.id_div, usuarios.id_area = rac.id_area, usuarios.id_division = bn_dependencias.id WHERE usuarios.usuario = rac.cedula AND bn_dependencias.id_area_dependencia = rac.id_area;";
$tabla = $_SESSION['conexionsql']->query($consulta);

//$consulta = "SELECT usuario, acceso FROM usuarios WHERE acceso>0 AND acceso<999  AND acceso<>99;";
//$tabla = $_SESSION['conexionsql']->query($consulta);
//while ($registro = $tabla->fetch_object())
//	{
//	//---------------
//	$consultax = "SELECT ".$registro->usuario.", id from accesos_individual WHERE tipo LIKE '%" .$registro->acceso. "%';";
//	$tablax = $_SESSION['conexionsql']->query($consultax);
//	while ($registrox = $tablax->fetch_object())
//		{
//		$consultai = "INSERT INTO usuarios_accesos(usuario, acceso) VALUES ('".$registro->usuario."', ".$registrox->id.")"; 
//		$tablai = $_SESSION['conexionsql']->query($consultai);
//		}
//	}

class CrudAdminUsuarios{

    protected $db;

    public function __construct()
    {
        $this->db = DB();
    }

    public function Listar()
    {
        $query = $this->db->prepare("SELECT usuarios.id, usuarios.id_contribuyente, usuarios.nombre_usuario, usuarios.user, usuarios.password, usuarios.email, tipo_acceso.descripcion, usuarios.acceso, usuarios.usuario FROM usuarios INNER JOIN tipo_acceso ON tipo_acceso.acceso = usuarios.acceso WHERE tipo_acceso.acceso <> 99 AND usuarios.acceso >= 0 and usuarios.acceso < 200");
        $query->execute();
        $data = array();
        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $data[] = $row;
        }

        return json_encode(['resultado' => $data]);
    }

    public function ListarPermisos()
    {
        $query = $this->db->prepare("SELECT tipo_acceso.acceso, tipo_acceso.descripcion FROM tipo_acceso WHERE tipo_acceso.acceso <> 99 and tipo_acceso.acceso >= 0 and tipo_acceso.acceso < 200 order by tipo_acceso.descripcion ASC");
        $query->execute();
        $data = array();
        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $data[] = $row;
        }

        return json_encode($data);
    }

    /*public function Buscar($codigo)
    {
        $query = $this->db->prepare("SELECT actividades.id FROM actividades WHERE codigo=$codigo");
        $query->execute();
        $data = array();
        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $data[] = $row;
        }

        return json_encode($data);
    }*/

    public function Agregar($nombre, $user, $password, $email, $acceso, $usuario)
    {
        $password = encrypt($password);
        $permitido = false;
        $mensaje = 'Problemas al registrar el usuario';
       
       //INSERTAMOS EL REGISTRO
        $transaccion = false;
        //$this->db->beginTransaction();
        $sql = "CALL sp_add_usuario(?,?,?,?,?,?)";
        $query = $this->db->prepare($sql);
        $query->bindParam("?,?,?,?,?,?", $nombre, $user, $password, $email, $acceso, $usuario);
        $transaccion = $query->execute(array($nombre, $user, $password, $email, $acceso, $usuario));
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
            $mensaje = 'Problemas al registrar el usuario';
        }
       
        $data = array(
            "permitido" => $permitido,
            "mensaje" => $mensaje 
        );

        return json_encode(['usuario' => $data]);
    }

    public function Editar($id, $nombre, $user, $password, $email, $acceso, $usuario)
    {
        $password = encrypt($password);
        $permitido = false;
        $mensaje = 'Problemas al modificar el usuario';
        $data = array();

       //INSERTAMOS EL REGISTRO
        $transaccion = false;
        //$this->db->beginTransaction();
        $sql = "CALL sp_editar_usuario(?,?,?,?,?,?,?)";
        $query = $this->db->prepare($sql);
        $query->bindParam("?,?,?,?,?,?,?", $id, $nombre, $user, $password, $email, $acceso, $usuario);
        $transaccion = $query->execute(array($id, $nombre, $user, $password, $email, $acceso, $usuario));
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
            $mensaje = 'Problemas al modificar el usuario';
        }
       
        $data = array(
            "permitido" => $permitido,
            "mensaje" => $mensaje 
        );

        return json_encode(["Resultado" => $data]);
    }

	public function Eliminar($id)
    {
        $permitido = false;
        $mensaje = '';

     	//REALIZAMOS LA ELIMINACION DEL TEMPORAL DE DETALLE DE LA DECLARACION
        $transaccion = false;
        //$this->db->beginTransaction();
        $sql = "DELETE FROM usuarios WHERE id=?";
        $query = $this->db->prepare($sql);
        $query->bindParam("?", $id);
        $transaccion = $query->execute(array($id));

        if ($transaccion)
        {
            //$this->db->commit();
            $permitido = true;
            $mensaje = 'Usuario eliminado con éxito';
        }
        else
        {
            //$this->db->rollback();
            $permitido = false;
            $mensaje = 'Problemas al eliminar el usuario';
        }
  
        $data = array(
            "permitido" => $permitido,
            "mensaje" => $mensaje
        );

        return json_encode(['resultado' => $data]);
  
    }
}
