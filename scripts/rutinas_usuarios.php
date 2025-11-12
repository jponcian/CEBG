<?php
//error_reporting(0);

require_once __DIR__ . '/conexion.php';
require_once __DIR__ . '/funciones.php';

class CrudUsuarios{

    protected $db;

    public function __construct()
    {
        $this->db = DB();
    }

    public function BuscarRif($rif)
    {
        $mensaje = "Rif no Registrado";
        $permitido = false;
        $id = 0;
        $data = array();
        $query = $this->db->prepare("SELECT count(contribuyente.id) as cant, contribuyente.id FROM contribuyente WHERE contribuyente.rif = '$rif'");
        $query->execute();
        $row = $query->fetch(PDO::FETCH_OBJ);
        $idexiste = $row->id;
        $cantidad = $row->cant;
        //echo $cantidad;
        if ($cantidad > 0)
        {
            //VERIFICAMOS SI HAY USUARIO REGISTRADO PARA ESTE CONTRIBUYENTE
            $query_user = $this->db->prepare("SELECT count(id_contribuyente) as existe FROM usuarios WHERE id_contribuyente = $idexiste;");
            $query_user->execute();
            $val = $query_user->fetch(PDO::FETCH_OBJ);
            $cant = $val->existe;

            if ($cant > 0)
            {
                $id = 0;
                $mensaje = "El rif posee usuario registrado $cant";
                $permitido = false;
                $usuariosinregistro = 0;                
            }
            else
            {
                $id = $idexiste;
                $mensaje = "Rif sin usuario registrado";
                $permitido = true;
                $usuariosinregistro = 0;                
            }
        } else {
            $id = 0;
            $mensaje = "Contribuyente no registrado en nuestro sistema";
            $permitido = true;
            $usuariosinregistro = 1;                            
        }

        $data = array(
            "id" => $id,
            "permitido" => $permitido,
            "mensaje" => $mensaje,
            "registrosinusuario" => $usuariosinregistro
        );

        return json_encode(['resultado' => $data]);
    }

    public function BuscarUsuario($user)
    {
        $mensaje = "Usuario Registrado";
        $permitido = false;
        $usuario = false;
        $data = array();
        $query = $this->db->prepare("SELECT usuarios.user FROM usuarios WHERE usuarios.user = '$user';");
        $query->execute();
        $cantidad = $query->RowCount();
        //echo $cantidad;
        if ($cantidad > 0)
        {
            $usuario = true;
            $mensaje = "El usuario ya esta registrado";
            $permitido = false;                
        }
        else
        {
            $usuario = false;
            $mensaje = "El usuario no esta registrado";
            $permitido = true;                
        }

        $data = array(
            "usuario" => $usuario,
            "permitido" => $permitido,
            "mensaje" => $mensaje
        );

        return json_encode(['user' => $data]);
    }

    public function BuscarPatente($num, $id)
    {
        $mensaje = "Patente no Registrada";
        $permitido = false;
        $data = array();
        $query = $this->db->prepare("SELECT patente.numero, contribuyente.id FROM patente INNER JOIN contribuyente ON contribuyente.id_patente = patente.id WHERE contribuyente.id = $id AND patente.numero = '$num';");
        $query->execute();
        $cantidad = $query->RowCount();
        //echo $cantidad;
        if ($cantidad > 0)
        {
            $mensaje = "Patente registrada";
            $permitido = true;
        }

        $data = array(
            "permitido" => $permitido,
            "mensaje" => $mensaje
        );

        return json_encode(['patente' => $data]);
    }

    public function AddUsuario($id, $user, $passw, $email, $usuario, $registrosinusuario, $rif)
    {
        $permitido = false;
        $mensaje = '';
        $clave = $cadena_encriptada = encrypt($passw);

        //SI NO EXISTE CONTRIBUYENTE REGISTRADO AGREGAMOS UNO VACIO PARA ACTUALIZAR LUEGO
        if ($registrosinusuario == 1)
        {
            $nombre='por actualizar';
            $domicilio='por actualizar';
            $ciudad=62;
            $estado=12;
            $zona=2;
            $rep='por actualizar';
            $ced='V00000000';
            $tlf="00000000000";
                  
            $detalle = $this->db->prepare("INSERT INTO contribuyente (rif, nombre, domicilio, ciudad, estado, zona, representante, ced_representante, cel_contacto, email, usuario) VALUES ('$rif', '$nombre', '$domicilio', $ciudad, $estado, $zona, '$rep', '$ced', '$tlf', '$email', '$usuario')");
            $transaccion = $detalle->execute();

            //SELECCIONAMOS EL ID ASIGNADO
            $query = $this->db->prepare("SELECT contribuyente.id FROM contribuyente WHERE contribuyente.rif = '$rif'");
            $query->execute();
            $valor = $query->fetch(PDO::FETCH_OBJ);
            $idcontribuyente = $valor->id;            

        } else {
        //Buscar nombre del contribuyente
            $query = $this->db->prepare("SELECT contribuyente.nombre FROM contribuyente WHERE contribuyente.id = $id");
            $query->execute();
            $valor = $query->fetch(PDO::FETCH_OBJ);
            $nombre = $valor->nombre;
            $idcontribuyente = $id;           
        }

       //INSERTAMOS EL USUARIO
        $transaccion = false;
        $detalle = $this->db->prepare("INSERT INTO usuarios (id_contribuyente, nombre_usuario, user, password, email, usuario, acceso) VALUES ($idcontribuyente, '$nombre', '$user', '$clave', '$email', '$usuario',1)");
        $transaccion = $detalle->execute();

        if ($transaccion)
        {
            $permitido = true;
            $mensaje = 'Usuario agregado con éxito';
        }
        else
        {
            $permitido = false;
            $mensaje = 'Problemas al agregar el usuario';
        }
  
        $data = array(
            "permitido" => $permitido,
            "mensaje" => $mensaje,
            "id_contribuyente" => $id 
        );

        return json_encode($data);
  
    }

    public function Eliminar($id_patente, $id_actividad)
    {
        $permitido = false;
        $mensaje = '';


       //REALIZAMOS LA ELIMINACION DEL TEMPORAL DE DETALLE DE LA DECLARACION
        $transaccion = false;
        //$this->db->beginTransaction();
        $sql = "DELETE FROM declaracion_detalle WHERE id_actividad=? and id_patente=? and id_declaracion=0";
        $query = $this->db->prepare($sql);
        $query->bindParam("?,?", $id_actividad, $id_patente);
        $transaccion = $query->execute(array($id_actividad, $id_patente));

        if ($transaccion)
        {
            //$this->db->commit();
            $permitido = true;
            $mensaje = 'Registro eliminado con éxito';
        }
        else
        {
            //$this->db->rollback();
            $permitido = false;
            $mensaje = 'Problemas al eliminar el registro';
        }
  
        $data = array(
            "permitido" => $permitido,
            "mensaje" => $mensaje,
            "id_patente" => $id_patente 
        );

        return json_encode(['resultado' => $data]);
  
    }

    public function EliminarTemporal($id_patente)
    {
        $permitido = false;
        $mensaje = '';


       //REALIZAMOS LA ELIMINACION DEL TEMPORAL DE DETALLE DE LA DECLARACION
        $transaccion = false;
        //$this->db->beginTransaction();
        $sql = "DELETE FROM declaracion_detalle WHERE id_patente=? AND id_declaracion=0";
        $query = $this->db->prepare($sql);
        $query->bindParam("?", $id_patente);
        $transaccion = $query->execute(array($id_patente));

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
            "mensaje" => $mensaje,
            "id_patente" => $id_patente 
        );

        return json_encode(['resultado' => $data]);
  
    }

    public function recuperarUsuario($cedula)
    {
        $mensaje = "Usuario Registrado";
        $permitido = false;
        $usuario = false;
        $data = array();
        $query = $this->db->prepare("SELECT usuarios.user as username, usuarios.password as clave, usuarios.email FROM usuarios WHERE usuarios.usuario = '$cedula'"); //echo $query;
        $query->execute();
        $row = $query->fetch(PDO::FETCH_ASSOC);
        $cantidad = $query->RowCount();

        //echo $cantidad;
        if ($cantidad > 0)
        {
            $usuario = true;
            $mensaje = "Hemos enviado un email a la dirección ".$row['email']." con su nombre de usuario y contraseña";
            $permitido = true;
            $email = $row['email'];
            $username = $row['username'];
            $clave = decrypt($row['clave']);

            ProcesarEnvioEmail($username, $clave, $email);
        }
        else
        {
            $usuario = false;
            $mensaje = "El contribuyente no posee usuario registrado en nuestro sistema";
            $permitido = false;                
            $email = '';
            $username = '';
            $clave = '';
        }

        $data = array(
            "usuario" => $usuario,
            "permitido" => $permitido,
            "mensaje" => $mensaje,
            "email" => $email,
            "username" => $username,
            "clave" => $clave
        );

        return json_encode(['user' => $data]);
    }

}

function ProcesarEnvioEmail($username, $clave, $email)
{
//	<p>Le invitamos a continuar utilizando nuestro sistema ONLINE - www.alcaldiafranciscodemiranda.com.ve - para presentar las declaraciones mensuales de ingresos brutos, estamos a su servicio.</p>
    //************************* TEXTO DEL MENSAJE ****************************************
        // destinatarios
        $para  = $email;// . ', '; // atención a la coma
        //$para .= 'wez@example.com';

        // título
        $titulo = 'Recuperación de Usuario SIACEBG - Contraloria del Estado Bolivariano de Guarico';

        // mensaje
        $mensaje = '
        <html>
        <head>
          <title>Recuperación de Usuario SIACEBG - Contraloria del Estado Bolivariano de Guarico</title>
        </head>
        <body>
          <p>Hemos recibido su solicitud de recuperación de usuario del Sistema de Gestión Interno (SIACEBG) de la Contraloria del Estado Bolivariano de Guarico.</p>
          <table>
            <tr>
              <td>Usuario:</td>
              <td>'.$username.'</td>
            </tr>
            <tr>
              <td>Clave:</td>
              <td>'.$clave.'</td>
            </tr>
          </table>
        </body>
        </html>
        ';

		// Para enviar un correo HTML, debe establecerse la cabecera Content-type
		$cabeceras  = 'MIME-Version: 1.0' . "\r\n";
		$cabeceras .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

		// Cabeceras adicionales
		//$cabeceras .= 'To: '. $para . "\r\n";
		$cabeceras .= 'From: SIACEBG <soporte@cebg.com.ve>' . "\r\n";
		//$cabeceras .= 'Cc: birthdayarchive@example.com' . "\r\n";
		$cabeceras .= 'Bcc: soporte@cebg.com.ve' . "\r\n";

        // Enviarlo
        //$respuesta = enviar_email($para, $asunto, $mensaje, $cabeceras);
        mail($para, $titulo, $mensaje, $cabeceras);
    //************************************************************************************
}