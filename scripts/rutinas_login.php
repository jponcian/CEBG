<?php
session_start();
//-------------
require_once __DIR__ . '/conexion.php';
require_once __DIR__ . '/funciones.php';

class Crud{

    protected $db;

    public function __construct()
    {
        $this->db = DB();
    }


    public function Read($user, $pass)
    {
        $clave = encrypt($pass);        
        $query = $this->db->prepare("SELECT * FROM usuarios WHERE user = '$user' AND password = '$clave';");
        $query->execute();
        $mensaje = "Usuario no Registrado";
        $permitido = false;
        $id_contribuyente = 0;
        $data = array();
        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $permitido = true;
            $mensaje = "Usuario registrado";
            $nombre = $row['nombre_usuario'];
            $user = $row['user'];
            $passw = $row['password'];
            $acceso = $row['acceso'];
            $usuario = $row['usuario'];
            $id_contribuyente = $row['id_contribuyente'];
            $_SESSION["id_usuario"]=$row['id_contribuyente'];
			$_SESSION["direccion"]=$row['id_direccion'];
			$_SESSION["division"]=$row['id_division'];
			$_SESSION["bienes"]=$row['bienes'];
			$_SESSION['VERIFICADO'] = "SI";
			$_SESSION['USER'] = $user;
			$_SESSION['CEDULA_USUARIO'] = $usuario;
			$_SESSION['USUARIO'] = ucwords(strtolower($nombre));
			if ($acceso==99) {	$_SESSION['ADMINISTRADOR'] = 1;	} else	{$_SESSION['ADMINISTRADOR'] = 0;}
			
			include_once "../conexion.php";
			$_SESSION['ip'] = $ip;
			$consulta_x = "UPDATE usuarios SET sesion = 1, ip='$ip' WHERE user = '$user';";
			$tabla_x = $_SESSION['conexionsql']->query($consulta_x);
//			echo $consulta_x ;
        }

        $data = array(
            "permitido" => $permitido,
            "mensaje" => $mensaje,
            "nombre_usuario" => $nombre,
            "user" => $user,
//            "pass" => $pass,
            "id_contribuyente" => $id_contribuyente,
            "tipo_acceso" =>  $acceso,
            "usuario" => $usuario
        );

        return json_encode($data);
    }


}